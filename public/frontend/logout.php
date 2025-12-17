<?php
// logout_simple.php

// 1. Handle session
session_start();
session_unset();
session_destroy();

// 2. Delete session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// 3. Delete other cookies if any
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

<div class="text-center">
    <div class="spinner-border mb-3" role="status"></div>
    <h5 class="fw-semibold">Sedang logout...</h5>
    <p class="opacity-75">Sampai jumpa bro 😎</p>
</div>

<script>
    // redirect 1.2 detik biar user sempet liat efeknya dikit
    setTimeout(() => {
        window.location.href = "login.php";
    }, 1200);
</script>

</body>
</html>
