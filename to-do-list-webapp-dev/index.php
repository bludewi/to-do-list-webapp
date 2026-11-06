<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $rawTaskName = $_POST['taskInput'] ?? '';
        $trimmedTaskName = trim($rawTaskName);

        if (!empty($trimmedTaskName)) {
            $taskName = htmlspecialchars($trimmedTaskName, ENT_QUOTES, 'UTF-8');
            $_SESSION['tasks'][] = [
                'name' => $taskName,
                'completed' => false
            ];
        }
    } elseif ($action === 'toggle') {
        $index = (int)$_POST['index'];
        if (isset($_SESSION['tasks'][$index])) {
            $_SESSION['tasks'][$index]['completed'] = !$_SESSION['tasks'][$index]['completed'];
        }
    } elseif ($action === 'delete') {
        $index = (int)$_POST['index'];
        if (isset($_SESSION['tasks'][$index])) {
            unset($_SESSION['tasks'][$index]);
            $_SESSION['tasks'] = array_values($_SESSION['tasks']); 
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
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
        <!-- INCOMPLETE -->
        <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
            <?php if (!$task['completed']): ?>
                <div class="task">
                    <form method="POST" class="action-form">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <button type="submit" class="check"></button>
                    </form>
                    
                    <div class="text"><?= $task['name'] ?></div>
                    
                    <form method="POST" class="delete-form action-form">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="index" value="<?= $index ?>">
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
             <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                 <?php if ($task['completed']): ?>
                     <div class="task done">
                         <form method="POST" class="action-form">
                             <input type="hidden" name="action" value="toggle">
                             <input type="hidden" name="index" value="<?= $index ?>">
                             <button type="submit" class="check">&#10003;</button>
                         </form>
                         
                         <div class="text"><?= $task['name'] ?></div>
                         
                         <form method="POST" class="delete-form action-form">
                             <input type="hidden" name="action" value="delete">
                             <input type="hidden" name="index" value="<?= $index ?>">
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