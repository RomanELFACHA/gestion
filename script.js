document.addEventListener("DOMContentLoaded", function() {
    loadTasks();
});

function loadTasks() {
    fetch("tasks.php")
    .then(response => response.json())
    .then(data => {
        const taskList = document.getElementById("taskList");
        taskList.innerHTML = "";
        data.forEach(task => {
            const li = document.createElement("li");
            li.textContent = task.description;
            li.setAttribute("data-id", task.id);
            li.addEventListener("click", () => deleteTask(task.id));
            taskList.appendChild(li);
        });
    });
}

function addTask() {
    const taskInput = document.getElementById("taskInput");
    const description = taskInput.value.trim();
    if (description !== "") {
        fetch("tasks.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ description: description })
        })
        .then(response => {
            if (response.ok) {
                loadTasks();
                taskInput.value = "";
            } else {
                alert("Error al agregar tarea");
            }
        });
    } else {
        alert("Por favor ingresa una descripción para la tarea");
    }
}

function deleteTask(taskId) {
    fetch(`tasks.php?id=${taskId}`, {
        method: "DELETE"
    })
    .then(response => {
        if (response.ok) {
            loadTasks();
        } else {
            alert("Error al eliminar tarea");
        }
    });
}
