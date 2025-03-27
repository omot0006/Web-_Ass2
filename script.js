// Sample task array to simulate a database
let tasks = [];

// DOM Elements
const addTaskForm = document.getElementById('add-task-form');
const taskList = document.getElementById('task-list');
const searchInput = document.getElementById('search');
const filterButton = document.getElementById('filter-btn');

// Handle Task Addition
addTaskForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const taskName = document.getElementById('task-name').value;
    const dueDate = document.getElementById('due-date').value;
    const priority = document.getElementById('priority').value;

    if (!taskName || !dueDate || !priority) {
        alert('Please fill all fields!');
        return;
    }

    const task = {
        id: Date.now(),
        name: taskName,
        dueDate: dueDate,
        priority: priority,
        completed: false
    };

    tasks.push(task);
    renderTasks();
    addTaskForm.reset(); // Reset form after adding task
});

// Render Tasks to DOM
function renderTasks() {
    taskList.innerHTML = ''; // Clear previous list

    tasks.forEach((task) => {
        const taskDiv = document.createElement('div');
        taskDiv.classList.add('task-item');
        
        taskDiv.innerHTML = `
            <span>${task.name} - ${task.dueDate} - ${task.priority}</span>
            <button onclick="toggleTaskCompletion(${task.id})">${task.completed ? 'Completed' : 'Mark as Done'}</button>
            <button onclick="deleteTask(${task.id})">Delete</button>
        `;
        taskList.appendChild(taskDiv);
    });
}

// Handle Task Deletion
function deleteTask(id) {
    tasks = tasks.filter(task => task.id !== id);
    renderTasks();
}

// Toggle Task Completion
function toggleTaskCompletion(id) {
    const task = tasks.find(task => task.id === id);
    task.completed = !task.completed;
    renderTasks();
}

// Search Filter
filterButton.addEventListener('click', function () {
    const searchTerm = searchInput.value.toLowerCase();
    const filteredTasks = tasks.filter(task =>
        task.name.toLowerCase().includes(searchTerm) ||
        task.dueDate.includes(searchTerm) ||
        task.priority.toLowerCase().includes(searchTerm)
    );
