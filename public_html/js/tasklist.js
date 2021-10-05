const taskListTitle = document.querySelector("#task-list__title");
const addNewTaskBtn = document.querySelector("#add-new-button");
const addNewTaskInput = document.querySelector("#add-new-input");
const formList = document.querySelector("#task-list__form");
const backBtn = document.querySelector("#back-btn");
const taskListContainer = document.querySelector("#task-list__container");
const undoBtn = document.querySelector("#undo");
const redoBtn = document.querySelector("#redo");
const uncheckTaskListBtn = document.querySelector("#uncheck-task-list");
const deleteTaskListBtn = document.querySelector("#delete-task-list");
const menuBtn = document.querySelector("#task-list__menu-btn");
const modalMenu = document.querySelector("#modal-menu");
const inputSearch = document.querySelector("#search");
const inputSortReverse = document.querySelector("#sort-reverse");
const inputSortAlpha = document.querySelector("#sort-alpha");
const inputSortCreated = document.querySelector("#sort-created");
const formMessage = document.querySelector("#form__message");
let sortReverse = inputSortReverse.checked;
let sortBy = "magic"; // set the tasklist initially to magically sorted
let searchString = "";
let hiddenTasklist = [];
let undoList = [];
let undoIndex = 0;

// 'taskListFromPHP' variable contains the task list data from PHP & from database
converTasklist(taskListFromPHP.tasklist);
saveIntoUndo();

addNewTaskInput.setAttribute("placeholder", "Add new task to the list");

displayTasklist();

function displayTasklist() {
  taskListTitle.textContent = taskListFromPHP.tasklist_name;
  emptyTasklistContainer();
  if (checkForEmptyList()) {
    sortByFunc();
    filtering();
  } else {
    displayMsg("Add new item to the list");
  }
}

// three dots menu open modal menu
menuBtn.onclick = () => {
  modalMenu.classList.remove("hide");
};

// if clicking when the modal menu is open
modalMenu.onclick = (e) => {
  const clickedId = e.target.id;
  switch (clickedId) {
    case "search-icon":
      // search label clicked
      modalMenu.classList.add("hide");
      break;
    case "sort-magic":
      // sort magic clicked
      sortBy = "magic";
      // calling sorting function
      rearrangeTasklistDOM();
      break;
    case "sort-reverse":
      // sort reverse clicked
      sortReverse = inputSortReverse.checked;
      // calling sorting function
      rearrangeTasklistDOM();
      break;
    case "sort-alpha":
      // sort alphabetically clicked
      sortBy = "task_name";
      // calling sorting function
      rearrangeTasklistDOM();
      break;
    case "sort-created":
      // sort by time created clicked
      sortBy = "created_at";
      // calling sorting function
      rearrangeTasklistDOM();
      break;
    case "modal-menu":
      // clicked outside the menu window
      modalMenu.classList.add("hide");
      break;
  }
};

function sortByFunc() {
  if (sortBy === "magic") {
    const list = taskListFromPHP.tasklist;
    // sort alphabetically
    const alpha = list.sort((a, b) => {
      return a.task_name.localeCompare(b.task_name);
    });
    // filter tasks which are done
    const done = alpha.filter((task) => {
      return task.task_status == true;
    });
    // filter tasks which aren't done
    const notDone = alpha.filter((task) => {
      return task.task_status == false;
    });
    taskListFromPHP.tasklist = [...notDone, ...done];
  } else if (sortBy === "created_at") {
    // sort by time of addition 'created_at' descending
    if (sortReverse) {
      taskListFromPHP.tasklist.sort((a, b) => a.created_at - b.created_at);
    } else {
      taskListFromPHP.tasklist.sort((a, b) => b.created_at - a.created_at);
    }
  } else if (sortBy === "task_name") {
    // sort by task name alphabetically 'task_name' ascending
    taskListFromPHP.tasklist.sort(dynamicSort(sortBy));

    function dynamicSort(property) {
      return function (a, b) {
        if (sortReverse) {
          return b[property].localeCompare(a[property]);
        } else {
          return a[property].localeCompare(b[property]);
        }
      };
    }
  }
}

// when the menu search input changing (oninput)
inputSearch.oninput = (e) => {
  searchString = e.target.value;
  filtering();
};

function filtering() {
  listSeparator(searchString);
  rearrangeTasklistDOM();
  if (searchString) {
    formMessage.classList.remove("hide");
  } else {
    formMessage.classList.add("hide");
  }
  if (checkForEmptyVisibleList() === 0) {
    displayMsg("No match was found");
  }
}

// generates the entire task list into the task list container
function generateTaskList(taskList) {
  taskList.forEach((taskItem) => {
    insertNewTask(taskItem);
  });
}

// add new task button handler
function addNewTaskHandler() {
  if (addNewTaskInput.value) {
    // if (checkForEmptyList() === 0) {
    //   emptyTasklistContainer();
    // }
    const newTask = {
      task_name: addNewTaskInput.value,
      task_status: false,
      created_at: Date.now(),
    };
    addNewTaskInput.value = "";
    // insertNewTask(newTask);
    taskListFromPHP.tasklist.push(newTask);
    rearrangeTasklistDOM();
    saveIntoUndo();
  }
}
addNewTaskBtn.onclick = addNewTaskHandler;

// inserts a new task into the form
function insertNewTask(taskObj) {
  // make an id 'created_at' because the 'task_id' doesn't exists when new task added
  const listId = taskObj.created_at;
  const labelId = "lbl_" + listId;
  // make a task item container
  const newTaskItem = document.createElement("div");
  newTaskItem.classList.add("list-item");
  newTaskItem.setAttribute("id", listId);
  // MAKE A CUSTOM CHECKBOX
  // make a label item for checkbox input & as a container for custom checkbox icons
  const newLblItem = document.createElement("label");
  newLblItem.classList.add("label-as-cont");
  newLblItem.setAttribute("for", labelId);
  newLblItem.setAttribute("title", "toggle this task");
  // make a checkbox
  const newChkItem = document.createElement("input");
  newChkItem.classList.add("chk");
  newChkItem.setAttribute("id", labelId);
  newChkItem.setAttribute("type", "checkbox");
  newChkItem.setAttribute("onchange", "checkTaskItemHandler(this)");
  if (taskObj.task_status) {
    newChkItem.setAttribute("checked", true);
  }
  // make icon 1
  const newIcon01Item = document.createElement("i");
  newIcon01Item.classList.add("far", "fa-circle", "check-btn");
  // make icon 2
  const newIcon02Item = document.createElement("i");
  newIcon02Item.classList.add("far", "fa-check-circle", "check-btn");
  // put those together
  newLblItem.appendChild(newChkItem);
  newLblItem.appendChild(newIcon01Item);
  newLblItem.appendChild(newIcon02Item);

  // make a text element
  const newInpItem = document.createElement("span");
  newInpItem.classList.add("txt-inp");
  if (taskObj.task_status) {
    newInpItem.classList.add("line-through");
  }
  newInpItem.setAttribute("id", listId);
  newInpItem.setAttribute("contenteditable", true);
  newInpItem.setAttribute("oninput", "editableEditHandler(this)");
  newInpItem.setAttribute("title", "click to edit this task");
  newInpItem.textContent = taskObj.task_name;
  // make a delete button
  const newDeleteItem = document.createElement("i");
  newDeleteItem.classList.add("fas", "fa-minus-circle", "remove-btn");
  newDeleteItem.setAttribute("onclick", "deleteTaskItemHandler(this)");
  newDeleteItem.setAttribute("title", "delete this task");

  // everything into task list item container
  newTaskItem.appendChild(newLblItem);
  newTaskItem.appendChild(newInpItem);
  newTaskItem.appendChild(newDeleteItem);

  taskListContainer.appendChild(newTaskItem);
  // chkId++;
}

// when the form submitted
formList.onsubmit = (e) => {
  // e.preventDefault();
  // combine the visible list & hidden list
  const list = [...taskListFromPHP.tasklist, ...hiddenTasklist];
  taskListFromPHP.tasklist = list;
  // add the task list object as value to the form hidden input
  // so this way to send back to php
  backBtn.value = JSON.stringify(taskListFromPHP);
};

// Prevent form submission on enter key press
window.onkeydown = (e) => {
  if (e.keyCode == 13) {
    e.preventDefault();
    return false;
  }
};

// when an entry delete button clicked
function deleteTaskItemHandler(e) {
  // get the id of 'list-item'
  const taskId = parseInt(e.parentElement.id, 10);
  e.parentElement.remove();
  taskListFromPHP.tasklist = taskListFromPHP.tasklist.filter((listItem) => listItem.created_at !== taskId);
  if (checkForEmptyList() === 0) {
    displayMsg("Add new item to the list");
  }
  saveIntoUndo();
}

// when a task checked
function checkTaskItemHandler(e) {
  // get clicked label corresponding checkbox
  const changedChk = e;
  // get the id of 'list-item'
  const taskId = parseInt(e.parentElement.parentElement.id, 10);
  // get task name element
  const targetElement = document.querySelector(`span[id='${taskId}']`);
  if (changedChk.checked) {
    targetElement.classList.add("line-through");
  } else {
    targetElement.classList.remove("line-through");
  }
  taskListFromPHP.tasklist.map((listItem) => {
    if (listItem.created_at === taskId) {
      if (changedChk.checked) {
        listItem.task_status = true;
      } else {
        listItem.task_status = false;
      }
    }
    return listItem;
  });
  sortByFunc();
  rearrangeTasklistDOM();
  saveIntoUndo();
}

// reset (uncheck) all task list item
function resetTaskListHandler() {
  const allChk = document.querySelectorAll(".chk");
  const allTxtInp = document.querySelectorAll(".txt-inp");
  allChk.forEach((chk) => {
    chk.checked = false;
  });
  allTxtInp.forEach((txtInp) => {
    txtInp.classList.remove("line-through");
  });
  taskListFromPHP.tasklist.forEach((taskListItem) => {
    taskListItem.task_status = false;
  });
  sortByFunc();
  rearrangeTasklistDOM();
  saveIntoUndo();
}
uncheckTaskListBtn.onclick = resetTaskListHandler;

// delete all task list item from task list container
function deleteTaskListHandler() {
  emptyTasklistContainer();
  taskListFromPHP.tasklist = [];
  if (searchString) {
    if (checkForEmptyVisibleList() === 0) {
      displayMsg("No match was found");
    }
  } else {
    if (checkForEmptyList() === 0) {
      displayMsg("Add new item to the list");
    }
  }
  saveIntoUndo();
}
deleteTaskListBtn.onclick = deleteTaskListHandler;

// displays the empty message in the task list container
function displayMsg(msg) {
  const msgElem = document.createElement("h2");
  msgElem.classList.add("empty-msg");
  msgElem.textContent = msg;
  taskListContainer.appendChild(msgElem);
}

// check if the task list empty
function checkForEmptyList() {
  // get the filtered & the rest of the list items
  const filteredAndtheRest = [...taskListFromPHP.tasklist, ...hiddenTasklist];
  // ...& return it's length
  return filteredAndtheRest.length;
}

// check if the visible task list empty
function checkForEmptyVisibleList() {
  return taskListFromPHP.tasklist.length;
}

// when a contenteditable element is edited
function editableEditHandler(e) {
  // get the id of 'list-item'
  const taskId = parseInt(e.parentElement.id, 10);
  const newText = e.textContent;
  if (e.id === "task-list__title") {
    taskListFromPHP.tasklist_name = newText;
  } else {
    taskListFromPHP.tasklist.map((taskItem) => {
      if (taskItem.created_at === taskId) {
        taskItem.task_name = newText;
      }
    });
  }
  saveIntoUndo();
}

// separate the tasklist based on searched string
// make visible list & hidden list
function listSeparator(str) {
  // combine the visible list & hidden list
  const list = [...taskListFromPHP.tasklist, ...hiddenTasklist];

  // Let's separate the 'list':
  // get filtered list based on 'str' variable
  const visibleTasklist = list.filter((listitem) => {
    const txt = listitem.task_name;
    const pattern = new RegExp(str, "i");
    return pattern.test(txt);
  });

  // get the rest of the original list
  hiddenTasklist = list.filter((listitem) => {
    const txt = listitem.task_name;
    const pattern = new RegExp(str, "i");
    return !pattern.test(txt);
  });

  // change the original list to filtered list
  taskListFromPHP.tasklist = visibleTasklist;
}

function emptyTasklistContainer() {
  taskListContainer.innerHTML = "";
}

function rearrangeTasklistDOM() {
  emptyTasklistContainer();
  sortByFunc();
  generateTaskList(taskListFromPHP.tasklist);
}

function saveIntoUndo() {
  undoList = undoList.slice(0, undoIndex + 1);
  // get visible & invisible tasklist arrays
  const visibleList = JSON.parse(JSON.stringify(taskListFromPHP.tasklist));
  const invisibleList = JSON.parse(JSON.stringify(hiddenTasklist));
  // clone the entire tasklist object
  const obj = JSON.parse(JSON.stringify(taskListFromPHP));
  // change tasklist object's tasklist
  obj.tasklist = [...visibleList, ...invisibleList];
  // make an object containts the full tasklist object & 'searchString' variable
  state = { tasklistObj: obj, searchString: searchString };
  state = JSON.stringify(state);
  // ..& if previous state and this state not same then push into the undo list
  const statePrev = undoList.slice(-1)[0];
  if (statePrev !== state) {
    undoList.push(state);
    undoIndex = undoList.length - 1;
  }
}

function undoHandler() {
  if (undoIndex) {
    undoIndex--;
    getUndoState();
  }
}
undoBtn.onclick = undoHandler;

function redoHandler() {
  if (undoIndex < undoList.length - 1) {
    undoIndex++;
    getUndoState();
  }
}
redoBtn.onclick = redoHandler;

function getUndoState() {
  hiddenTasklist = [];
  let state = undoList[undoIndex];
  state = JSON.parse(state);
  taskListFromPHP = { ...state.tasklistObj };
  searchString = state.searchString;
  displayTasklist();
  inputSearch.value = searchString;
}

// converts tasklist status from 0/1 to false/true
function converTasklist(tasklist) {
  return tasklist.map((task) => {
    if (task.task_status === 0) {
      task.task_status = false;
    } else if (task.task_status === 1) {
      task.task_status = true;
    }
  });
}
