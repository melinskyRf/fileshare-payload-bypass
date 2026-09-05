<?php
session_start();

// Mock users database
$users = [
    'admin' => ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin'],
    'guest' => ['username' => 'guest', 'password' => 'guest123', 'role' => 'guest']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $_SESSION['user'] = $users[$username];
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['error'] = 'Nome de usuário ou senha incorretos.';
        header('Location: index.php');
        exit;
    }
}

// If not POST request, redirect to index
header('Location: index.php');
exit;
?>