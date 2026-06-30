<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Knowly E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
       tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
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
<header class="bg-white border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="" class="text-4xl font-brand font-bold -ml-40 ">
<!--            <span class="text-techblue">Know</span>-->
<!--            <span class="text-softgray">ly</span>-->

            <p  class="text-techblue">Know<span class="text-softgray">ly</span></p>


        </a>
        <nav class="flex gap-6 font-brand text-lg font-medium text-gray-600">
            <a href="#home" class="hover:text-brandblue">Home</a>
            <a href="#program" class="hover:text-brandblue">Program</a>
            <a href="#About" class="hover:text-brandblue">About Us</a>
        </nav>


        <div class="flex items-center gap-4 -mr-40">
            <a href="auth/login_user.php" class=" font-semibold text-gray-700 hover:text-techblue transition-colors">
                Masuk
            </a>
            <a href="auth/regist_user.php" class="inline-flex items-center justify-center px-4 py-2 text-base font-bold text-white bg-techblue hover:bg-blue-700 rounded-xl shadow-sm transition-all">
                Daftar Gratis
            </a>
        </div>

    </div>
</header>
</body>
</html>
