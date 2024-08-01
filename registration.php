
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h2>Register</h2>
    <form  method="post" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>
        <label>Profile Photo:</label>
        <input type="file" name="profile_photo"><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>

<?php
session_start();

require 'db.php';

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $profile_photo = $_FILES['profile_photo'];
}

if (empty($name) || empty($email) || empty($password) || empty($confirm_password))  {
    echo "All Fileds are required";
    exit();
}
if (!$password == $confirm_password) {
    echo "Password do not match";
}

if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
    echo"Invalid email format";
    exit();
}
$hashed_password = password_hash($password,PASSWORD_DEFAULT);
$uploade_dir = 'uploads/';
$uploade_file = $uploade_dir . basename($profile_photo['name']);
move_uploaded_file($profile_photo['tmp_name'],$uploade_file);

  // Prepare statement
  $stmt = $conn->prepare("INSERT INTO users (name, email, password, profile_photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $upload_file);
  // Execute statement
  if ($stmt->execute()) {
      echo "New record created successfully";
  } else {
      echo "Error: " . $stmt->error;
  }

  // Close statement
  $stmt->close();

  // Close connection
  $conn->close();

?>
