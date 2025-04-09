const form = document.getElementById("add-task-form");
const taskList = document.getElementById("task-list");
const searchInput = document.getElementById("search");
const filterPriority = document.getElementById("filter-priority");
const filterStatus = document.getElementById("filter-status");

let tasks = [];

form.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("task-name").value;
    const dueDate = document.getElementById("due-date").value;
    const priority = document.getElementById("priority").value;

    const task = {
        id: Date.now(),
        name,
        dueDate,
        priority,
        completed: false
    };

    tasks.push(task);
    displayTasks(tasks);
    form.reset();
});

function displayTasks(taskArray) {
    taskList.innerHTML = "";
    taskArray.forEach(task => {
        const taskDiv = document.createElement("div");
        taskDiv.classList.add("task-item");

        taskDiv.innerHTML = `
            <strong>${task.name}</strong> 
            <p>Due: ${task.dueDate}</p>
            <p>Priority: ${task.priority}</p>
            <p>Status: ${task.completed ? "Completed" : "Pending"}</p>
            <button onclick="toggleStatus(${task.id})">${task.completed ? "Mark as Pending" : "Mark as Completed"}</button>
        `;

        taskList.appendChild(taskDiv);
    });
}

function toggleStatus(id) {
    const task = tasks.find(t => t.id === id);
    if (task) {
        task.completed = !task.completed;
        displayTasks(tasks);
    }
}

document.getElementById("filter-btn").addEventListener("click", () => {
    const searchTerm = searchInput.value.toLowerCase();
    const priorityFilter = filterPriority.value;
    const statusFilter = filterStatus.value;

    const filtered = tasks.filter(task => {
        const matchSearch = task.name.toLowerCase().includes(searchTerm);
        const matchPriority = priorityFilter ? task.priority === priorityFilter : true;
        const matchStatus = statusFilter ? (statusFilter === "Completed" ? task.completed : !task.completed) : true;
        return matchSearch && matchPriority && matchStatus;
    });

    displayTasks(filtered);
});
