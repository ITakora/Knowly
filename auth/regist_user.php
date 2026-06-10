<?php

session_start();

require_once __DIR__ . '/../config/db.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    if (empty($username) || empty($email) || empty($password) || empty($repassword)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: regist_user.php");
        exit();
    }

    if ($password !== $repassword) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: regist_user.php");
        exit();
    }


    $sql = "INSERT INTO users (username, email, password) VALUES (?,?,?)";


    $pre = $conn -> prepare($sql);
    $pre -> bind_param("sss", $username, $email, $password);


  if ($pre -> execute()) {
      header("Location: login_user.php");
  }



}
?>



<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        brand: ['Poppins', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        techblue: '#1A73E8',
                        softgray: '#E8EAED',
                        brandblue: '#1A73E8',
                        cardblue: '#F3F8FF'
                    }
                }
            }
        }
    </script>
</head>
<body>
<div class="flex min-h-screen items-center justify-center">
    <form method="post" class="flex w-full max-w-md flex-col p-8  bg-white shadow-lg rounded-xl">
        <h2 class=" text-techblue font-brand font-bold text-center text-4xl">Yuk Daftar</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mt-5 bg-red-50 border border-red-500 p-3 rounded-md text-sm text-red-700 font-medium">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        <p class="mt-[30px]">Email:</p>
        <input type="text" id="email" name="email" placeholder="Masukkan Email" class="border p-3 border-gray-300 rounded-md"/>
        <p class="mt-[10px]">Username:</p>
        <input type="text" id="username" name="username" placeholder="Masukkan Username" class="border p-3 border-gray-300 rounded-md"/>
        <p class="mt-[10px]">Password:</p>
        <input type="password" id="password" name="password" placeholder="Masukkan Password" class="border p-3 border-gray-300 rounded-md"/>
        <p class="mt-[10px]">Masukkan Ulang Password:</p>
        <input type="password" id="repassword" name="repassword" placeholder="Masukkan Ulang Password" class="border p-3 border-gray-300 rounded-md"/>

        <button type="submit" class="p-3 font-brand rounded-md bg-blue-600 text-white mt-[30px] -mt-[30px]">Daftar</button>


    </form>





</div>
</body>
</html>






