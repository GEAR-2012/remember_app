const taskListTitle = document.querySelector("#task-list__title");
const addNewTaskBtn = document.querySelector("#add-new-button");
const addNewTaskInput = document.querySelector("#add-new-input");
const formList = document.querySelector("#task-list__form");
const backBtn = document.querySelector("#back-btn");
const taskListContainer = document.querySelector("#task-list__container");
const resetTaskListBtn = document.querySelector("#reset-task-list");
const deleteTaskListBtn = document.querySelector("#delete-task-list");
// 'taskListFromPHP' variable contains the task list data from PHP & from database

addNewTaskInput.setAttribute("placeholder", "Add new task to the list");

taskListTitle.textContent = taskListFromPHP.tasklist_name;

// CHECK FOR TASKLIST LIST
if (!taskListFromPHP.tasklist) {
  // if no value yet on the list key of tasklist object
  deleteTaskListHandler();
} else if (taskListFromPHP.tasklist.length === 0) {
  // if the list exist but empty
  deleteTaskListHandler();
}

// fill the task list container
generateTaskList(taskListFromPHP.tasklist);

// generates the entire task list into the task list container
function generateTaskList(taskList) {
  taskList.forEach((taskItem) => {
    insertNewTask(taskItem);
  });
}

// add new task button handler
function addNewTaskHandler() {
  if (addNewTaskInput.value) {
    if (checkForEmptyList() === 0) {
      taskListContainer.innerHTML = "";
    }
    const newTask = {
      task_name: addNewTaskInput.value,
      task_status: false,
      created_at: Date.now(),
    };
    addNewTaskInput.value = "";
    insertNewTask(newTask);
    taskListFromPHP.tasklist.push(newTask);
  }
}
addNewTaskBtn.onclick = addNewTaskHandler;

// inserts a new task into the form
function insertNewTask(taskObj) {
  taskObj.created_at = new Date(taskObj.created_at).valueOf(); // Temporary
  // make a task item container
  const newTaskItem = document.createElement("div");
  newTaskItem.classList.add("list-item");
  // MAKE A CUSTOM CHECKBOX
  // make a label item for checkbox input & as a container for custom checkbox icons
  const newLblItem = document.createElement("label");
  newLblItem.classList.add("label-as-cont");
  newLblItem.setAttribute("for", taskObj.created_at);
  newLblItem.setAttribute("title", "toggle this task");
  // make a checkbox
  const newChkItem = document.createElement("input");
  newChkItem.classList.add("chk");
  newChkItem.setAttribute("id", taskObj.created_at);
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
  newInpItem.setAttribute("id", taskObj.created_at);
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
  const taskId = parseInt(e.parentElement.childNodes[0].firstElementChild.id, 10);
  e.parentElement.remove();
  if (checkForEmptyList() === 0) {
    displayEmptyMsg();
  }
  taskListFromPHP.tasklist = taskListFromPHP.tasklist.filter((listItem) => listItem.created_at !== taskId);
}

// when a task checked
function checkTaskItemHandler(e) {
  const changedChk = e;
  const idOfChanged = parseInt(changedChk.id, 10);
  const targetElement = document.querySelector(`span[id='${idOfChanged}']`);
  if (changedChk.checked) {
    targetElement.classList.add("line-through");
  } else {
    targetElement.classList.remove("line-through");
  }
  taskListFromPHP.tasklist.map((listItem) => {
    if (listItem.created_at === idOfChanged) {
      if (changedChk.checked) {
        listItem.task_status = true;
      } else {
        listItem.task_status = false;
      }
    }
    return listItem;
  });
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
}
resetTaskListBtn.onclick = resetTaskListHandler;

// delete all task list item from task list container
function deleteTaskListHandler() {
  taskListContainer.innerHTML = "";
  displayEmptyMsg();
  taskListFromPHP.tasklist = [];
}
deleteTaskListBtn.onclick = deleteTaskListHandler;

// displays the empty message in the task list container
function displayEmptyMsg() {
  const msgElem = document.createElement("h2");
  msgElem.classList.add("empty-msg");
  msgElem.textContent = "Add new item to the list";
  taskListContainer.appendChild(msgElem);
}

// check if the task list empty
function checkForEmptyList() {
  const listItems = document.querySelectorAll(".list-item");
  return listItems.length;
}

// when a contenteditable element is edited
function editableEditHandler(e) {
  const task = e.textContent;
  if (e.id === "task-list__title") {
    taskListFromPHP.tasklist_name = task;
  } else {
    taskListFromPHP.tasklist.map((taskItem) => {
      if (taskItem.created_at === parseInt(e.id, 10)) {
        taskItem.task = task;
      }
    });
  }
}
