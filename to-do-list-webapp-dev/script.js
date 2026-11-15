document.addEventListener("DOMContentLoaded", function() {
    const ongoingTasksCount = document.querySelectorAll('#task-container .task').length;
    document.getElementById('ongoingCount').textContent = ongoingTasksCount;

    const completedTasksCount = document.querySelectorAll('.task.done').length;
    document.getElementById('completedCount').textContent = completedTasksCount;

    const editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const task = button.closest('.task');
            const taskName = task.querySelector('.text').textContent;
            const editedTaskName = prompt("Edit task:", taskName);

            if (editedTaskName === null || editedTaskName.trim() === '') {
                return;
            }

            const editForm = task.querySelector('.edit-form');
            editForm.querySelector('input[name="taskInput"]').value = editedTaskName;
            editForm.submit();
        });
    });

    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            const userConfirmed = confirm("Are you sure you want to delete this task?");
            if (!userConfirmed) {
                event.preventDefault();
            }
        });
    });

    // Dropdown for completed tasks
    const toggleBtn = document.getElementById('toggle-completed');
    const completedContainer = document.getElementById('completed-container');
    const arrow = document.getElementById('arrow');

    toggleBtn.addEventListener('click', function() {
        if (completedContainer.style.display === "none") {
            completedContainer.style.display = "block";
            arrow.classList.add('rotate-down');
        } else {
            completedContainer.style.display = "none";
            arrow.classList.remove('rotate-down');
        }
    });
});