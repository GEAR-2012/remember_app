// 'taskListCollectionFromPHP' variable contains the task list collections data from PHP & from database

const collectionForm = document.querySelector("#collection__form");
const collectionContainer = document.querySelector("#collection-container");
const createNewListBtn = document.querySelector("#add-new-button");
const createNewListInput = document.querySelector("#add-new-input");
const hiddenTaskListId = document.querySelector("#hidden-task_list_open");
const hiddenTaskListNew = document.querySelector("#hidden-task_list_new");
const hiddenTaskListDelete = document.querySelector("#hidden-task_list_delete");

// Prevent form submission on enter key press
window.onkeydown = (e) => {
  if (e.keyCode == 13) {
    e.preventDefault();
    return false;
  }
};

createNewListInput.setAttribute("placeholder", "Create new task list");

if (taskListCollectionFromPHP.length === 0) {
  collectionContainer.appendChild(makeMessage());
} else {
  taskListCollectionFromPHP.forEach((taskList) => {
    const id = taskList.task_list_id;
    const text = taskList.task_list_name;
    collectionContainer.appendChild(makeListItem(id, text));
  });
}

// makes only one tasklistcollection element
function makeListItem(id, text) {
  // make the container
  const listElem = document.createElement("div");
  listElem.classList.add("task-list__cont");
  listElem.setAttribute("id", id);
  // make icon
  const listIcon = document.createElement("i");
  listIcon.classList.add("far", "fa-list-alt", "list-icon");
  listIcon.setAttribute("onclick", "listItemOnClickHandler(this)");
  listIcon.setAttribute("title", "open this task list");
  // make the text
  const listText = document.createElement("span");
  listText.classList.add("task-list__name");
  listText.setAttribute("onclick", "listItemOnClickHandler(this)");
  listText.setAttribute("title", "open this task list");
  listText.textContent = text;
  // make a delete button
  const listDelete = document.createElement("i");
  listDelete.classList.add("fas", "fa-minus-circle", "remove-btn");
  listDelete.setAttribute("onclick", "deleteTaskListHandler(this)");
  listDelete.setAttribute("title", "delete this task list");

  listElem.appendChild(listIcon);
  listElem.appendChild(listText);
  listElem.appendChild(listDelete);

  return listElem;
}

// generates a message element
function makeMessage() {
  const msgElem = document.createElement("h2");
  msgElem.classList.add("empty-msg");
  msgElem.textContent = "You have no task list";
  return msgElem;
}

// list item click handler
function listItemOnClickHandler(e) {
  const listId = parseInt(e.parentNode.id, 10);
  hiddenTaskListId.value = listId;
  collectionForm.submit();
}

// create new task list button handler
function createNewTaskListHandler() {
  const newListName = createNewListInput.value.trim();
  if (newListName) {
    createNewListInput.value = "";
    hiddenTaskListNew.value = newListName;
    collectionForm.submit();
  }
}
createNewListBtn.onclick = createNewTaskListHandler;

// delete a task list button handler
function deleteTaskListHandler(e) {
  const listId = parseInt(e.parentNode.id, 10);
  hiddenTaskListDelete.value = listId;
  collectionForm.submit();
}
createNewListBtn.onclick = createNewTaskListHandler;
