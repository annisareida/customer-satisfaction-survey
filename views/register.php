<?php
include 'config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama_admin = $_POST['nama_admin'];
  $password = $_POST['password'];
  $jenis_kelamin = $_POST['jenis_kelamin'];

  // Hash the password before storing it in the database
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepare and bind
  $stmt = $conn->prepare("INSERT INTO admin (nama_admin, password, jenis_kelamin) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $nama_admin, $hashed_password, $jenis_kelamin);

  // Execute the statement
  if ($stmt->execute()) {
    header("Location: login.php");
    exit();
  } else {
    $error = "Error: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Register Admin</title>
  <link rel="stylesheet" href="register.css">
</head>

<body>
  <div class="container">
    <h2>Register Admin</h2>
    <?php if (isset($error)) : ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="">
      <label>Nama Admin:</label>
      <input type="text" name="nama_admin" required><br>
      <label>Password:</label>
      <input type="password" name="password" required><br>
      <label>Jenis Kelamin:</label>
      <select name="jenis_kelamin" required>
        <option value="laki-laki">Laki-laki</option>
        <option value="perempuan">Perempuan</option>
      </select><br>
      <input type="submit" value="Register">
    </form>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
  </div>
</body>

</html>
