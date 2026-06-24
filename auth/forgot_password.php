<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['username'])) {
    header("Location: ../student/student_list_class.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $new_password = $_POST['new_password'];

    if (empty($username) || empty($new_password)) {
        $_SESSION['error'] = "Please fill in all fields.";
    } else {
        // 1. Check if the username actually exists in the database
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);

        if ($check_stmt) {
            $check_stmt->bind_param("s", $username);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if ($result->num_rows > 0) {
                // 2. Username exists, so we UPDATE the password
                // Note: Saving exactly as typed to match your login system
                $update_sql = "UPDATE users SET password = ? WHERE username = ?";
                $update_stmt = $conn->prepare($update_sql);

                if ($update_stmt) {
                    $update_stmt->bind_param("ss", $new_password, $username);
                    if ($update_stmt->execute()) {
                        $_SESSION['success'] = "Password successfully updated! Please log in.";
                        header("Location: login_user.php");
                        exit();
                    } else {
                        $_SESSION['error'] = "Failed to update password. Please try again.";
                    }
                }
            } else {
                $_SESSION['error'] = "Username not found.";
            }
        } else {
            $_SESSION['error'] = "Database error occurred.";
        }
    }

    // Redirect back to this page to show error if we didn't exit above
    header("Location: forgot_password.php");
    exit();
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
<div class="flex min-h-screen items-center justify-center p-4">
    <form method="post" action="forgot_password.php" class="flex w-full max-w-md flex-col p-8 bg-white shadow-lg rounded-xl">
        <h2 class="text-techblue font-brand font-bold text-center text-3xl mb-2">Reset Password</h2>
        <p class="text-center text-gray-500 text-sm mb-6">Enter your username to set a new password.</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 bg-red-50 border border-red-500 p-3 rounded-md text-sm text-red-700 font-medium">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <label class="mt-2 font-medium text-gray-700">Username:</label>
        <input type="text" name="username" placeholder="Enter Your Username" class="border mt-1 p-3 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-techblue" required/>

        <label class="mt-4 font-medium text-gray-700">New Password:</label>
        <input type="password" name="new_password" placeholder="Enter New Password" class="border mt-1 p-3 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-techblue" required/>

        <button type="submit" class="p-3 font-brand rounded-md bg-techblue hover:bg-blue-700 transition text-white mt-6">Reset Password</button>

        <div class="text-center mt-6">
            <a href="login_user.php" class="text-techblue hover:underline text-sm font-medium">← Back to Login</a>
        </div>
    </form>
</div>
</body>
</html>
