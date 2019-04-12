function handleUpdateTask(checkbox, event) {
    event.preventDefault();

    let updateTaskAction = checkbox.getAttribute('data-action');
    let data = new FormData();
    data.append('completed', checkbox.checked ? 1 : 0);

    fetch(updateTaskAction, {method: 'POST', body: data})
        .then(response => response.json())
        .then(body => body.status === 1 ? updateTask(body.task) : alert(body.msg))
        .catch(error => alert('Error'));
}

function updateTask(task) {
    let taskElement = document.getElementById(task.id),
        checkbox = taskElement.querySelector('input[type="checkbox"]'),
        title = taskElement.querySelector('.taskTitle');

    if (task.completed === "1") {
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
            } else {
                alert(body.msg);
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

    let checkbox = newTask.querySelector('input[type="checkbox"]');
    let newUpdateTaskAction = checkbox.getAttribute('data-action').replace('tempTaskId', task.id);
    checkbox.setAttribute('data-action', newUpdateTaskAction);
    checkbox.setAttribute('onclick', "handleUpdateTask(this, event)");
    // Same as above: checkbox.addEventListener('click', () => handleUpdateTask(event, newUpdateTaskAction));

    newTask.classList.remove('hide');
    document.getElementById('taskList').appendChild(newTask);
}

function handleRemoveTask(removeTaskAction) {

    fetch(removeTaskAction, {method: 'POST'})
        .then(response => response.json())
        .then(body => body.status === 1 ? removeTask(body.taskId) : alert(body.msg))
        .catch(error => console.log(error));
}

function removeTask(taskId) {
    document.getElementById(taskId).remove();
}

function handleRemoveCompletedTasks(removeCompletedTasksAction) {
    fetch(removeCompletedTasksAction, {method: 'POST'})
        .then((response) => response.json())
        .then(body => body.status === 1 ? removeCompletedTasks() : alert(body.msg))
        .catch(error => console.log(error));
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
        .then(body => body.status === 1 ? removeAllTasks() : alert(body.msg))
        .catch(error => console.log(error));
}

function removeAllTasks() {
    let taskList = document.getElementById('taskList');
    while (taskList.lastChild) taskList.lastChild.remove();
}



