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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LAMP Stack Task Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .hero-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 3rem 0;
      margin-bottom: 2rem;
    }
    .task-card {
      border: none;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .task-card:hover {
      transform: translateY(-2px);
    }
    .stats-card {
      background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
      color: white;
      border-radius: 15px;
    }
  </style>
</head>
<body>
  <div class="hero-section">
    <div class="container text-center">
      <h1 class="display-4 mb-3"><i class="fas fa-tasks"></i> LAMP Stack Task Manager</h1>
      <p class="lead">Deployed on AWS ECS with Copilot CLI</p>
      <div class="alert alert-success d-inline-block">
        <i class="fas fa-check-circle"></i> Connected to MySQL successfully!
      </div>
    </div>
  </div>

  <div class="container">
    <!-- Stats Section -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card stats-card text-center p-3">
          <h3><?= $tasks->num_rows ?></h3>
          <p class="mb-0"><i class="fas fa-list"></i> Total Tasks</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-info text-white text-center p-3">
          <h3><i class="fas fa-server"></i></h3>
          <p class="mb-0">ECS Fargate</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-success text-white text-center p-3">
          <h3><i class="fas fa-database"></i></h3>
          <p class="mb-0">MySQL 5.7</p>
        </div>
      </div>
    </div>

    <!-- Add Task Form -->
    <div class="card task-card mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Add New Task</h5>
      </div>
      <div class="card-body">
        <form method="POST" class="d-flex gap-2">
          <input type="text" name="title" class="form-control form-control-lg" placeholder="Enter your task here..." required>
          <button type="submit" name="add" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Add Task
          </button>
        </form>
      </div>
    </div>

    <!-- Tasks List -->
    <div class="card task-card">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Your Tasks</h5>
      </div>
      <div class="card-body">
        <?php if ($tasks->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-light">
                <tr>
                  <th width="10%"><i class="fas fa-hashtag"></i> ID</th>
                  <th width="60%"><i class="fas fa-edit"></i> Task</th>
                  <th width="30%"><i class="fas fa-cogs"></i> Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php $tasks->data_seek(0); // Reset pointer to show data again ?>
                <?php while ($row = $tasks->fetch_assoc()): ?>
                  <tr>
                    <td><span class="badge bg-secondary"><?= $row['id'] ?></span></td>
                    <td>
                      <form method="POST" class="d-flex align-items-center">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" class="form-control me-2">
                        <button type="submit" name="update" class="btn btn-outline-success btn-sm">
                          <i class="fas fa-save"></i> Update
                        </button>
                      </form>
                    </td>
                    <td>
                      <a href="?delete=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?')">
                        <i class="fas fa-trash"></i> Delete
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No tasks yet!</h4>
            <p class="text-muted">Add your first task using the form above.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5 py-4 border-top">
      <p class="text-muted">
        <i class="fab fa-aws"></i> Powered by AWS ECS | 
        <i class="fas fa-rocket"></i> Deployed with Copilot CLI | 
        <i class="fab fa-php"></i> PHP & MySQL
      </p>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
