<?php
require_once "connect.php";

session_start();

$username = $_POST["f_username"];
$password = $_POST["f_password"];

$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $conn->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if ($user !== false) {
    $error = urlencode("Käyttäjätunnus on käytössä.");
    header("Location: register.php?error=$error");
    exit();
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'username' => $username,
    'password_hash' => $password_hash
]);

header("location:login.php");
exit();