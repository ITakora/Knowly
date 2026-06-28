<?php
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}


$inisial = strtoupper(substr($_SESSION['username'], 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Knowly E-Learning</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #ffffff; color: #333; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; border-bottom: 1px solid #E8EAED; background-color: #ffffff; }
        .logo { font-size: 28px; font-weight: 700; color: #1A73E8; text-decoration: none; }
        .logo span { color: #E8EAED; }
        .nav-right { display: flex; align-items: center; gap: 15px; }
        .user-info { text-align: right; }
        .user-info p { font-size: 12px; color: #5f6368; }
        .user-info h4 { font-size: 14px; font-weight: 600; }
        .avatar { width: 35px; height: 35px; background-color: #E8EAED; color: #1A73E8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; }
        .menu-container { position: relative; }
        .hamburger-btn { background: none; border: none; font-size: 24px; cursor: pointer; color: #333; padding: 5px; }
        .dropdown-menu { display: none; position: absolute; right: 0; top: 40px; background: white; border: 1px solid #E8EAED; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); border-radius: 8px; width: 160px; z-index: 1000; overflow: hidden; }
        .dropdown-menu a { display: block; padding: 12px 15px; text-decoration: none; color: #333; font-size: 14px; transition: 0.2s; }
        .dropdown-menu a:hover { background-color: #E8EAED; }
        .dropdown-menu a.logout { color: #d93025; border-top: 1px solid #E8EAED; }
        .logout-btn {
            background: #EA4335;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: 0.2s;
            cursor: pointer;
        }
        .logout-btn:hover {
            background: #C5221F;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <p  class="logo">Know<span>ly</span></p>
    <div class="nav-right">
        <div class="user-info">
            <p>Halo, Selamat Datang</p>
            <h4><?php echo htmlspecialchars($_SESSION['username']); ?></h4>
        </div>
        <div class="avatar"><?php echo $inisial; ?></div>

        <a href="../auth/logout.php" class="logout-btn" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
        </div>

</nav>

<!--<script>-->
<!--    function toggleMenu() {-->
<!--        var x = document.getElementById("myDropdown");-->
<!--        x.style.display = (x.style.display === "block") ? "none" : "block";-->
<!--    }-->
<!--    window.onclick = function(event) {-->
<!--        if (!event.target.matches('.hamburger-btn')) {-->
<!--            var dropdowns = document.getElementsByClassName("dropdown-menu");-->
<!--            for (var i = 0; i < dropdowns.length; i++) {-->
<!--                var openDropdown = dropdowns[i];-->
<!--                if (openDropdown.style.display === "block") { openDropdown.style.display = "none"; }-->
<!--            }-->
<!--        }-->
<!--    }-->
<!--</script>-->