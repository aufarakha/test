<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - VIERA</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
            width: 100%;
            height: 100vh;
            background-image: url('/assets/image/halamandepan.png');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
        }

        .fullscreen-image {
            width: 100%;
            height: 100vh;
            object-fit: contain;
            display: block;
        }
    </style>
</head>
<body class="dashboard-body">
    <img src="/assets/image/halamandepan.png" alt="VIERA Background" class="fullscreen-image">

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Check if user has valid Laravel session/token
            const token = "{{ session('api_token') }}";
            const userData = @json(session('user_data'));

            console.log("🔍 Checking user session...");
            console.log("🔑 Token:", token ? "Valid" : "Invalid");

            if (!token || !userData) {
                console.log("⚠️ Tidak ada sesi login, redirect ke login...");
                window.location.href = "/";
                return;
            }

            // Store user data in sessionStorage for exam pages
            sessionStorage.setItem("std_code", userData.std_code);
            sessionStorage.setItem("api_token", token);
            localStorage.setItem("vieraData::" + userData.std_code, JSON.stringify(userData));

            const namespace = userData.std_code + "::";

            if (localStorage.getItem(namespace + "examExpired") === "true") {
                console.log("⏰ Waktu habis, redirect ke submit...");
                localStorage.removeItem(namespace + "remainingTime");
                localStorage.removeItem(namespace + "currentQuestion");
                window.location.href = "/user/exam/submit";
                return;
            }

            let lastQuestion = localStorage.getItem(namespace + "currentQuestion");

            if (lastQuestion) {
                console.log("✅ Melanjutkan tes dari soal:", lastQuestion);
                window.location.href = "/user/exam/test";
            } else {
                console.log("🔄 Tidak ada progress, mulai tes baru...");
                // Clear old exam_result_id when starting new exam
                sessionStorage.removeItem("exam_result_id");
                setTimeout(() => {
                    window.location.href = "/user/exam/opening";
                }, 2000);
            }
        });
    </script>
</body>
</html>
