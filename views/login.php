<?php
session_start();
require_once 'config.php';

// Inisialisasi variabel error
$error = "";

// Jika form login disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama_admin = $_POST['nama_admin'];
  $password = $_POST['password'];

  // Query untuk mencari admin berdasarkan nama
  $sql = "SELECT * FROM admin WHERE nama_admin = '$nama_admin'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // Verifikasi password dengan hash yang disimpan di database
    if (password_verify($password, $row['password'])) {
      // Sesuai, buat session dan redirect ke landing.php
      $_SESSION['nama_admin'] = $nama_admin;
      header("Location: landing.php");
      exit();
    } else {
      $error = "Password salah.";
    }
  } else {
    $error = "Admin tidak ditemukan.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="login-container">
    <h2>Login Admin</h2>
    <!-- Menampilkan pesan error menggunakan notifikasi JavaScript -->
    <div id="error-message" class="error"><?php echo $error; ?></div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
      <label for="nama_admin">Nama Admin:</label>
      <input type="text" id="nama_admin" name="nama_admin" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <input type="submit" value="Login">
    </form>
    <p>Belum punya akun? <a href="register.php">Buat akun</a></p>
  </div>

  <script>
    function validateForm() {
      var password = document.getElementById("password").value;
      if (password === "") {
        showErrorMessage("Password harus diisi.");
        return false;
      }
      
      return true; // Submit form jika semua valid
    }

    function showErrorMessage(message) {
      var errorMessageElement = document.getElementById("error-message");
      errorMessageElement.innerHTML = message;
      errorMessageElement.style.display = "block";
    }
  </script>
</body>
</html>
