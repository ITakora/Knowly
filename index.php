<?php

require_once 'includes/header.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script
            src="https://unpkg.com/@lottiefiles/dotlottie-wc@latest/dist/dotlottie-wc.js"
            type="module"
    ></script>
</head>
<body  class="bg-softgrey">

<section id="home" class="container  flex items-center justify-between mr-10" >
    <div class=" flex flex-col  mx-auto mt-28">
        <h1 class="text-6xl font-bold  mt-10">Belajar Program di Manapun</h1>
        <h1 class="text-6xl font-bold  mt-5">Dengan Knowly</h1>
        <p class="text-xl mt-5">Kami menyediakan berbagai macam materi pemrograman yang dapat diakses secara gratis</p>

        <a href=""
           class="self-start mt-8 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Mulai Belajar
        </a>
    </div>



    <div class="mt-28 -mr-20">
        <dotlottie-wc
                src="assets/study.lottie"
                speed="1"
                style="width: 400px; height: 400px"
                mode="forward"
                loop
                autoplay
        ></dotlottie-wc>
    </div>

</section>


<section id="program" class="container mx-auto mt-[420px] mb-[380px]">

   <div class="flex flex-col items-center   gap-10 ">
       <h1 class="text-4xl font-bold">Program Kami</h1>
       <p class="text-xl mt-[50px]">Platform kami menyediakan berbagai materi yang dibutuhkan untuk siapapun yang ingin belajar.</p>
   </div>

    <div class="flex justify-center items-center gap-12  " >
        <div class=" relative  bg-techblue p-10  rounded-xl shadow-lg mt-[300px]">
            <div class="absolute -top-[290px] left-1/2 transform -translate-x-1/2 " >
                <dotlottie-wc
                        src="assets/web.lottie"
                        speed="1"
                        style="width: 380px; height: 380px"
                        mode="forward"
                        loop
                        autoplay
                ></dotlottie-wc>

            </div>

            <h3 class="text-2xl font-semibold  text-white">Web</h3>
            <p class="mt-3 text-white ">
                Pelajari cara penggunaan HTML, CSS, dan JavaScript untuk membuat web yang interaktif dan responsif.
            </p>

        </div>

        <div class=" relative bg-techblue p-10 rounded-xl shadow-lg mt-[300px]">

            <div class="absolute -top-[280px] left-1/2 transform -translate-x-1/2 ">
                <dotlottie-wc
                        src="assets/mobile.lottie"
                        speed="1"
                        style="width: 380px; height: 380px"
                        mode="forward"
                        loop
                        autoplay
                ></dotlottie-wc>
            </div>

            <h3 class="text-2xl font-semibold text-white">Mobile</h3>
            <p class="mt-3 text-white">
                Pelajari cara membuat aplikasi Android dan iOS dengan menggunakan Kotlin dan Swift maupun Flutter.
            </p>

        </div>

        <div class=" relative bg-techblue p-10 rounded-xl shadow-lg mt-[300px]">
            <div class="absolute -top-[310px] left-1/2 transform -translate-x-1/2  ">
                <dotlottie-wc
                        src="assets/backend.lottie"
                        speed="1"
                        style="width: 350px; height: 350px"
                        mode="forward"
                        loop
                        autoplay
                ></dotlottie-wc>
            </div>
            <h3 class="text-2xl font-semibold text-white">Backend</h3>
            <p class="mt-3 text-white">
                Pelajari cara membuat sebuah server dan belajar database untuk membuat backend yang berkualitas.
            </p>

        </div>

    </div>



</section>


<section id="About" class="container mx-auto -mt-40 ">

    <div class="container mx-auto max-w-6xl mb-[250px]">


        <div class="text-center mb-16">
            <h2 class="text-4xl  font-bold ">
                Kenapa mesti belajar di Knowly?
            </h2>
            <p class=" text-lg mt-4 max-w-2xl mx-auto">
                Berbagai macam materi pemrograman modern yang dirancang khusus untuk membantumu berkembang dari nol sampai siap kerja!
            </p>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">


            <div class="flex flex-col items-center">

                <div class="w-32 h-32 rounded-full bg-[#fcd34d] flex items-center justify-center mb-6 overflow-hidden p-2">

                    <span class="text-black font-bold text-xs uppercase text-center tracking-wider block">100% Gratis</span>
                </div>
                <p class=" leading-relaxed text-base max-w-sm">
                    Akses penuh ke semua materi berkualitas tanpa biaya langganan tersembunyi, membantu jutaan developer lokal berkembang.
                </p>
            </div>


            <div class="flex flex-col items-center">

                <div class="w-32 h-32 rounded-full bg-[#fcd34d] flex items-center justify-center mb-6 overflow-hidden p-2">

                    <span class="text-black font-bold text-xs uppercase text-center tracking-wider block">Kurikulum Top</span>
                </div>
                <p class=" leading-relaxed text-base max-w-sm">
                    Materi disesuaikan dengan standar industri global dan kebutuhan dunia kerja, baik untuk Web, Mobile, maupun Backend.
                </p>
            </div>


            <div class="flex flex-col items-center">

                <div class="w-32 h-32 rounded-full bg-[#fcd34d] flex items-center justify-center mb-6 overflow-hidden p-2">
                    <span class="text-black font-bold text-xs uppercase text-center tracking-wider block">Praktik Nyata</span>
                </div>
                <p class="leading-relaxed text-base max-w-sm">
                    Belajar bukan cuma teori. Dilengkapi dengan studi kasus nyata, latihan coding mandiri, dan tips.
                </p>
            </div>

        </div>

    </div>

</section>




</body>
</html>