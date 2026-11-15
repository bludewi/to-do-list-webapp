<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $trimmedTaskName = trim($_POST['taskInput'] ?? '');

        if ($trimmedTaskName !== '') {
            $stmt = $pdo->prepare("INSERT INTO tasks (task_name) VALUES (?)");
            $stmt->execute([$trimmedTaskName]);
        }
    } elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE tasks SET is_done = IF(is_done = 0, 1, 0) WHERE id = ?");
            $stmt->execute([$id]);
        }
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $trimmedTaskName = trim($_POST['taskInput'] ?? '');

        if ($id > 0 && $trimmedTaskName !== '') {
            $stmt = $pdo->prepare("UPDATE tasks SET task_name = ? WHERE id = ?");
            $stmt->execute([$trimmedTaskName, $id]);
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->execute([$id]);
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// READ: Fetch all tasks from MySQL
$tasks = $pdo->query("SELECT * FROM tasks ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link href="https://rsms.me/inter/inter.css" rel="stylesheet">   
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>To-Do List</h1>

    <form method="POST" id="input">
        <input type="hidden" name="action" value="create">
        <input type="text" id="taskInput" name="taskInput" placeholder="Enter Task" required>
        <button type="submit" id="add-button">+</button>
    </form>

    <section id="task-container">
        <p id="ongoing-heading">Ongoing (<span id="ongoingCount">0</span>)</p>

        <!-- INCOMPLETE -->
        <?php foreach ($tasks as $task): ?>
            <?php if (!$task['is_done']): ?>
                <div class="task">
                    <form method="POST" class="action-form">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                        <button type="submit" class="check"></button>
                    </form>
                    <div class="text"><?= htmlspecialchars($task['task_name'], ENT_QUOTES, 'UTF-8') ?></div>

                    <button type="button" class="edit" aria-label="Edit task" title="Edit task">&#9998;</button>
                    <form method="POST" class="edit-form action-form">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                        <input type="hidden" name="taskInput">
                    </form>

                    <form method="POST" class="delete-form action-form">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                        <button type="submit" class="delete">&#215;</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </section> 

    <section id="task-list">
        <p id="toggle-completed"><span id="arrow">&gt;</span> Completed (<span id="completedCount">0</span>)</p>
        
        <!-- COMPLETED -->
        <div id="completed-container" style="display: none;">
            <?php foreach ($tasks as $task): ?>
                <?php if ($task['is_done']): ?>
                    <div class="task done">
                        <form method="POST" class="action-form">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                            <button type="submit" class="check">&#10003;</button>
                        </form>
                        
                        <div class="text"><?= htmlspecialchars($task['task_name'], ENT_QUOTES, 'UTF-8') ?></div>
                        
                        <form method="POST" class="delete-form action-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$task['id'] ?>">
                            <button type="submit" class="delete">&#215;</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>