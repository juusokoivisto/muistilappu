<?php
require_once "connect.php";

session_start();

$username = $_POST["f_username"];
$password = $_POST["f_password"];

$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $conn->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if(isset($user) == false) {
    $error = urlencode("Käyttäjätunnus tai salasana on väärin.");
    header("Location: login.php?error=$error");
    exit();
}

if(password_verify($password, $user["password_hash"])) {
    $_SESSION["id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    header("location:index.php");
    exit();
}

$error = urlencode("Käyttäjätunnus tai salasana on väärin.");
header("Location: login.php?error=$error");
exit();