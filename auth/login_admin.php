

<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: ../teacher/teacher_list_class.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: login_user.php");
        exit();
    }

    $sql = "SELECT id, username, password, role FROM users WHERE username = ? AND role = 'admin'";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        $result = $stmt -> get_result();

        if ($result -> num_rows > 0) {
            $user = $result -> fetch_assoc();

            if ($password == $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header("Location: ../teacher/teacher_list_class.php");
            } else {

                $_SESSION['error'] = "Invalid username or password.";
                header("Location: login_admin.php");
            }
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login_admin.php");
        }
        exit();

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
        <h2 class=" text-techblue font-brand font-bold text-center text-4xl">Yuk Login</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mt-5 bg-red-50 border border-red-500 p-3 rounded-md text-sm text-red-700 font-medium">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        <p class="mt-[30px]">Username:</p>
        <input type="text" id="username" name="username" placeholder="Enter Your Username" class="border p-3 border-gray-300 rounded-md"/>
        <p class="mt-[10px]">Password:</p>
        <input type="password" id="password" name="password" placeholder="Enter Your Password" class="border p-3 border-gray-300 rounded-md"/>

        <button type="submit" class="p-3 font-brand rounded-md bg-blue-600 text-white mt-[16px] ">Login</button>

        <div class="flex  items-center justify-between mt-[30px]">
            <a href="forgot_password.php" class="text-techblue">Lupa password</a>
        </div>
    </form>





</div>
</body>
</html>


