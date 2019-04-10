function handleAddTask(event, form) {
    event.preventDefault();

    let url = form.getAttribute('action');
    let data = new FormData(form);

    fetch(url, {method: 'POST', body: data})
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
    deleteButton.setAttribute('data-action', newDeleteTaskAction);

    newTask.classList.remove('hide');
    document.getElementById('taskList').appendChild(newTask);
}

function handleRemoveTask(button) {
    let url = button.getAttribute('data-action');

    fetch(url, {method: 'POST'})
        .then(response => response.json())
        .then(body => {
            if (body.status === 1) removeTask(body.task);
        }).catch(error => console.log(error));
}

function removeTask(task) {
    document.getElementById(task.id).remove();
}

function removeCompletedTasks(url) {

    let taskList = document.getElementById('taskList').querySelectorAll('input');
    taskList.forEach((task) => {
        if (task.checked === true) task.closest('.task').remove();
    });
}

function removeAllTasks(url) {

    let taskList = document.getElementById('taskList');
    while (taskList.lastChild) taskList.lastChild.remove();
}



