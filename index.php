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
    $result->free();
}
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