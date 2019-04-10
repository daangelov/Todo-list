function handleUpdateTask(event, updateTaskAction) {
    event.preventDefault();
    fetch(updateTaskAction, {method: 'POST'})
        .then(response => response.json())
        .then(body => updateTask(body.task))
        .catch(error => console.log(error));
}

function updateTask(task) {
    let taskElement = document.getElementById(task.id),
        checkbox = taskElement.querySelector('input[type="checkbox"]'),
        title = taskElement.querySelector('.taskTitle');

    if (task.completed) {
        title.classList.add('text-line-through');
        checkbox.checked = true;
    } else {
        title.classList.remove('text-line-through');
        checkbox.checked = false;
    }
}

function handleAddTask(event, form) {
    event.preventDefault();

    let addTaskAction = form.getAttribute('action'),
        data = new FormData(form);

    fetch(addTaskAction, {method: 'POST', body: data})
        .then(response => response.json())
        .then(body => {
            if (body.status === 1) {
                addTask(body.task);
                document.getElementById('title').value = '';
            }
        }).catch(error => console.log(error));
}

function addTask(task) {
    let newTask = document.getElementById('tempTask').cloneNode(true);

    // Set all necessary attributes
    newTask.setAttribute('id', task.id);
    newTask.querySelector('.taskTitle').innerHTML = task.title;

    let deleteButton = newTask.querySelector('.deleteTaskButton');
    let newDeleteTaskAction = deleteButton.getAttribute('data-action').replace('tempTaskId', task.id);
    deleteButton.setAttribute('onclick', "handleRemoveTask('" + newDeleteTaskAction + "')");
    // Same as above: deleteButton.addEventListener('click', () => handleRemoveTask(newDeleteTaskAction));
    deleteButton.removeAttribute('data-action');

    newTask.classList.remove('hide');
    document.getElementById('taskList').appendChild(newTask);
}

function handleRemoveTask(removeTaskAction) {

    fetch(removeTaskAction, {method: 'POST'})
        .then(response => response.json())
        .then(body => {
            if (body.status === 1) removeTask(body.task);
        }).catch(error => console.log(error));
}

function removeTask(task) {
    document.getElementById(task.id).remove();
}

function handleRemoveCompletedTasks(removeCompletedTasksAction) {
    fetch(removeCompletedTasksAction, {method: 'POST'})
        .then((response) => response.json())
        .then(body => {
            if (body.status === 1) removeCompletedTasks();
        }).catch(error => console.log(error));
}

function removeCompletedTasks() {

    let taskList = document.getElementById('taskList').querySelectorAll('input');
    taskList.forEach((task) => {
        if (task.checked === true) task.closest('.task').remove();
    });
}

function handleRemoveAllTasks(removeAllTasksAction) {

    fetch(removeAllTasksAction, {method: 'POST'})
        .then((response) => response.json())
        .then(body => {
            if (body.status === 1) removeAllTasks();
        }).catch(error => console.log(error));
}

function removeAllTasks() {
    let taskList = document.getElementById('taskList');
    while (taskList.lastChild) taskList.lastChild.remove();
}



