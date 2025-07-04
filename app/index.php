<?php
// Load environment variables
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: 3306;
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'rootpassword';
$db = getenv('DB_NAME') ?: 'lampdb';

$mysqli = new mysqli($host, $user, $pass, $db, $port);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Create table if not exists
$mysqli->query("CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL
)");

// Handle create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $title = $mysqli->real_escape_string($_POST['title']);
    $mysqli->query("INSERT INTO tasks (title) VALUES ('$title')");
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $mysqli->query("DELETE FROM tasks WHERE id = $id");
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = (int) $_POST['id'];
    $title = $mysqli->real_escape_string($_POST['title']);
    $mysqli->query("UPDATE tasks SET title = '$title' WHERE id = $id");
}

// Fetch tasks
$tasks = $mysqli->query("SELECT * FROM tasks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Simple LAMP App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2 class="mb-4">Simple LAMP App</h2>
  <p class="text-success">✅ Connected to MySQL successfully!</p>

  <form method="POST" class="mb-3 d-flex gap-2">
    <input type="text" name="title" class="form-control" placeholder="Enter task title" required>
    <button type="submit" name="add" class="btn btn-primary">Add Task</button>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $tasks->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td>
            <form method="POST" class="d-flex">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" class="form-control">
              <button type="submit" name="update" class="btn btn-success ms-2">Update</button>
            </form>
          </td>
          <td>
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this task?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
