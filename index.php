<?php
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
// ======================
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
}

// ======================
/* Lecture de la liste des tâches:
   - Triée du plus récent au plus ancien (created_at DESC, id DESC)
   - Stockée dans $taches sous forme d'objets */
$taches = [];
$result = $mysqli->query("SELECT id, title, done, created_at FROM todo ORDER BY created_at DESC, id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $taches[] = (object)[
            'id' => (int)$row['id'],
            'title' => $row['title'],
            'done' => (int)$row['done'],
            'created_at' => $row['created_at'],
        ];
    }
}
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : null;

    if ($action === 'new') {
        // Ajouter une nouvelle tâche (name="title")
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        if ($title !== '') {
            $stmt = $mysqli->prepare("INSERT INTO todo (title) VALUES (?)");
            $stmt->bind_param("s", $title);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action === 'delete') {
        // Supprimer une tâche (name="id")
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id > 0) {
            $stmt = $mysqli->prepare("DELETE FROM todo WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action === 'toggle') {
        // Basculer done entre true/false (name="id")
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id > 0) {
            // Indication fournie dans l'énoncé
            $stmt = $mysqli->prepare("UPDATE todo SET done = 1 - done WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Post/Redirect/Get
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
$taches = [];
$result = $mysqli->query("SELECT id, title, done, created_at FROM todo ORDER BY created_at DESC, id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $taches[] = (object)[
            'id' => (int)$row['id'],
            'title' => $row['title'],
            'done' => (int)$row['done'],
            'created_at' => $row['created_at'],
        ];
    }

    $result->free();
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>ma todo</h1>
</body>
</html>