<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>VIERA - Opening</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      box-sizing: border-box;
      width: 100%;
      height: 100vh;
      background-size: contain;
      background-position: center;
      background-repeat: no-repeat;
      background-color: #f9f9f9;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: center;
    }

    .visible-button {
      position: relative;
      margin-bottom: 5px;
      padding: 10px 30px;
      font-size: 12px;
      font-weight: bold;
      color: #000;
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 5px;
      cursor: pointer;
      box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    }

    .visible-button:hover {
      background-color: #f0f0f0;
    }
  </style>
</head>

<body>
  <button id="nextButton" class="visible-button" onclick="nextPage()">
    Selanjutnya
  </button>

  <script>
    // Validate token before allowing access
    const token = sessionStorage.getItem("api_token");
    const stdCode = sessionStorage.getItem("std_code");

    if (!token || !stdCode) {
      alert("Sesi tidak valid. Silakan login kembali.");
      window.location.href = "/user/login";
    }

    // Daftar gambar opening
    const images = [
      "/assets/image/opening1.png",
      "/assets/image/opening3.png",
      "/assets/image/opening4.png",
      "/assets/image/opening5.png",
      "/assets/image/opening6.png",
    ];

    let currentIndex = 0;
    let examResultId = null;

    // Call start exam API when opening page loads
    async function startExam() {
      try {
        console.log("🚀 Starting exam for:", stdCode);
        
        const response = await fetch('/api/exam/start', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({
            std_code: stdCode,
            device: navigator.userAgent,
          }),
        });

        const result = await response.json();
        console.log("📦 API Response:", result);
        
        if (!result.success) {
          console.error("❌ API Error:", result.message);
          alert(result.message || 'Gagal memulai exam.');
          window.location.href = "/user/welcome";
          return;
        }

        examResultId = result.data.exam_result_id;
        sessionStorage.setItem('exam_result_id', examResultId);
        console.log("✅ Exam started successfully. Result ID:", examResultId);
      } catch (error) {
        console.error('❌ Error starting exam:', error);
        // Don't block the exam if API fails - system has fallback
        console.warn("⚠️ Continuing without exam_result_id (fallback mode)");
      }
    }

    // Start exam when page loads
    startExam();

    // Fungsi untuk memperbarui background
    function updateBackground() {
      document.body.style.backgroundImage = `url('${images[currentIndex]}')`;

      const button = document.getElementById("nextButton");
      if (currentIndex === 2) {
        button.innerText = "Setuju";
      } else {
        button.innerText = "Selanjutnya";
      }
    }

    // Panggil pertama kali
    updateBackground();

    function nextPage() {
      if (currentIndex < images.length - 1) {
        currentIndex++;
        updateBackground();
      } else {
        // Setelah opening selesai, pindah ke test page
        console.log("🎯 Moving to test page. Exam Result ID:", examResultId || "Not set (fallback mode)");
        window.location.href = "/user/exam/test";
      }
    }

    function toggleFullScreen() {
      var doc = window.document;
      var docEl = doc.documentElement;

      var requestFullScreen =
        docEl.requestFullscreen ||
        docEl.mozRequestFullScreen ||
        docEl.webkitRequestFullScreen ||
        docEl.msRequestFullscreen;
      var exitFullScreen =
        doc.exitFullscreen ||
        doc.mozCancelFullScreen ||
        doc.webkitExitFullscreen ||
        doc.msExitFullscreen;

      if (
        !doc.fullscreenElement &&
        !doc.mozFullScreenElement &&
        !doc.webkitFullscreenElement &&
        !doc.msFullscreenElement
      ) {
        requestFullScreen.call(docEl);
      } else {
        exitFullScreen.call(doc);
      }
    }

    document.body.addEventListener("click", (_) => toggleFullScreen(), {
      once: true,
    });
  </script>
</body>
</html>
