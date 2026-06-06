<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Submit Hasil - Viera Tryout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --primary-color: #10b981;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .submit-container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      max-width: 600px;
      width: 100%;
      padding: 50px 40px;
      text-align: center;
    }

    .success-icon {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 30px;
      animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
      from {
        transform: scale(0);
      }
      to {
        transform: scale(1);
      }
    }

    .success-icon i {
      font-size: 50px;
      color: white;
    }

    h2 {
      color: #111827;
      font-weight: 700;
      margin-bottom: 15px;
      font-size: 28px;
    }

    .lead {
      color: #6b7280;
      margin-bottom: 30px;
      font-size: 16px;
    }

    .btn-submit {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      border: none;
      padding: 16px 40px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
      width: 100%;
      max-width: 300px;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .btn-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .btn-exit {
      background: white;
      color: #6b7280;
      border: 2px solid #e5e7eb;
      padding: 16px 40px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s;
      width: 100%;
      max-width: 300px;
      margin-top: 15px;
    }

    .btn-exit:hover {
      background: #f9fafb;
      border-color: #d1d5db;
    }

    #status-box {
      margin-top: 30px;
      padding: 20px;
      border-radius: 12px;
      font-size: 15px;
      line-height: 1.8;
    }

    #status-box.success {
      background: #d1fae5;
      border: 2px solid #10b981;
      color: #065f46;
    }

    #status-box.error {
      background: #fee2e2;
      border: 2px solid #ef4444;
      color: #991b1b;
    }

    #status-box.warning {
      background: #fef3c7;
      border: 2px solid #f59e0b;
      color: #92400e;
    }

    #countdown {
      margin-top: 20px;
      font-size: 16px;
      font-weight: 600;
      color: #059669;
    }

    .spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    @media (max-width: 576px) {
      .submit-container {
        padding: 40px 24px;
      }

      h2 {
        font-size: 24px;
      }

      .success-icon {
        width: 80px;
        height: 80px;
      }

      .success-icon i {
        font-size: 40px;
      }

      .lead {
        font-size: 14px;
      }

      .btn-submit, .btn-exit {
        padding: 14px 30px;
        font-size: 14px;
      }
    }
  </style>
</head>

<body>
  <div class="submit-container">
    <div class="success-icon">
      <i class="fas fa-check"></i>
    </div>

    <h2>Tes Selesai!</h2>
    <p class="lead">
      Terima kasih telah mengikuti tes. Klik tombol di bawah untuk menyimpan jawaban Anda.
    </p>

    <button id="submitButton" class="btn-submit" onclick="submitResults()">
      <i class="fas fa-paper-plane"></i> Simpan Jawaban
    </button>

    <div id="status-box" style="display: none;"></div>
    <div id="countdown" style="display: none;"></div>

    <button id="exitButton" class="btn-exit" style="display: none;" onclick="exitApp()">
      <i class="fas fa-home"></i> Kembali ke Dashboard
    </button>
  </div>

  <script>
    // Validate token
    const token = sessionStorage.getItem("api_token");
    const std_code = sessionStorage.getItem("std_code");
    const vieraData = JSON.parse(localStorage.getItem("vieraData::" + std_code));

    if (!token || !vieraData || !std_code) {
      alert("Data peserta tidak ditemukan. Anda akan diarahkan ke halaman awal.");
      window.location.href = "/";
    }

    const listeningQuestions = [
      "q1","q2","q3","q4","q5","q6","q7","q8","q9","q10",
      "q11","q12","q13","q14","q15","q16","q17","q18","q19","q20",
      "q21","q22","q23","q24","q25","q26","q27","q28","q29","q30",
      "q31","q32","q33","q34","q35","q36","q37","q38","q39","q40",
      "q41","q42","q43","q44","q45","q46","q47","q48","q49","q50"
    ];
    
    const readingQuestions = [
      "q51","q52","q53","q54","q55","q56","q57","q58","q59","q60",
      "q61","q62","q63","q64","q65","q66","q67","q68","q69","q70",
      "q71","q72","q73","q74","q75","q76","q77","q78","q79","q80",
      "q81","q82","q83","q84","q85","q86","q87","q88","q89","q90",
      "q91","q92","q93","q94","q95","q96","q97","q98","q99","q100"
    ];

    function getDeviceType() {
      const ua = navigator.userAgent;
      if (/windows phone/i.test(ua)) return "Windows Phone";
      if (/android/i.test(ua)) return "Android";
      if (/iPad|iPhone|iPod/.test(ua)) return "iOS";
      if (/Macintosh/.test(ua)) return "macOS";
      if (/Windows NT/.test(ua)) return "Windows";
      if (/Linux/.test(ua)) return "Linux";
      return "Unknown";
    }

    function exitApp() {
      // Redirect to dashboard
      window.location.href = "/user/welcome";
    }

    function submitResults() {
      if (!vieraData) {
        alert("Data peserta tidak ditemukan! Silakan kembali ke halaman awal.");
        return;
      }

      const submitButton = document.getElementById("submitButton");
      submitButton.disabled = true;
      submitButton.innerHTML = '<span class="spinner"></span> Mengirim...';

      const examResultId = sessionStorage.getItem('exam_result_id');
      // Log for debugging
      console.log("Exam Result ID:", examResultId);

      let allAnswers = {};
      const allQuestionIds = [...listeningQuestions, ...readingQuestions];

      allQuestionIds.forEach(q => {
        const stored = localStorage.getItem(std_code + "::" + q);
        if (stored) {
          try {
            allAnswers[q] = JSON.parse(stored);
          } catch (e) {
            console.warn(`Gagal parse jawaban ${q}:`, e);
          }
        }
      });

      let dataToSend = {
        full_name: vieraData.std_name,
        std_code: vieraData.std_code,
        sch_code: vieraData.sch_code || "UNKNOWN",
        jawaban_peserta: allAnswers,
        device: getDeviceType(),
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      };

      // Add exam_result_id if available
      if (examResultId) {
        dataToSend.exam_result_id = parseInt(examResultId);
      }

      fetch("/api/exam/submit", {
        method: "POST",
        headers: { 
          "Content-Type": "application/json",
          "Accept": "application/json",
          "Authorization": "Bearer " + token
        },
        body: JSON.stringify(dataToSend),
      })
        .then((response) => {
          if (!response.ok) throw new Error(`HTTP Error! Status: ${response.status}`);
          return response.json();
        })
        .then((result) => {
          let statusBox = document.getElementById("status-box");
          let countdownBox = document.getElementById("countdown");
          
          statusBox.style.display = "block";

          if (result.success) {
            statusBox.className = 'success';
            statusBox.innerHTML = `
              <i class="fas fa-check-circle"></i> <strong>Jawaban berhasil disimpan!</strong><br>
              <small style="opacity: 0.8;">Review jawaban Anda sudah tersedia di dashboard</small>
            `;
            
            document.getElementById("submitButton").style.display = "none";
            document.getElementById("exitButton").style.display = "block";
            countdownBox.style.display = "block";

            // Clear localStorage after successful submission
            Object.keys(localStorage).forEach((key) => {
              if (key.startsWith(std_code + "::") || key.startsWith("vieraData::")) {
                localStorage.removeItem(key);
              }
            });

            localStorage.removeItem(std_code + "::remainingTime");
            localStorage.removeItem(std_code + "::examExpired");
            sessionStorage.removeItem("api_token");
            sessionStorage.removeItem("std_code");
            sessionStorage.removeItem("exam_result_id");

            let countdown = 5;
            countdownBox.textContent = `Redirect otomatis dalam ${countdown} detik...`;

            let timer = setInterval(() => {
              countdown--;
              if (countdown > 0) {
                countdownBox.textContent = `Redirect otomatis dalam ${countdown} detik...`;
              } else {
                clearInterval(timer);
                exitApp();
              }
            }, 1000);
          } else {
            statusBox.className = result.message.includes('kuota') ? 'warning' : 'error';
            statusBox.innerHTML = `<i class="fas fa-exclamation-circle"></i> <strong>${result.message || 'Gagal menyimpan skor'}</strong>`;
          }
        })
        .catch((error) => {
          console.error("❌ Error:", error);
          let statusBox = document.getElementById("status-box");
          statusBox.className = 'error';
          statusBox.innerHTML = `<i class="fas fa-times-circle"></i> <strong>Terjadi kesalahan: ${error.message}</strong>`;
          statusBox.style.display = "block";
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
      console.log("📦 DOMContentLoaded terpanggil!");

      if (vieraData && std_code) {
        console.log("✅ vieraData dan std_code tersedia");
        // Auto submit when page loads
        // submitResults();
      } else {
        console.warn("⚠️ vieraData atau std_code TIDAK tersedia!");
      }
    });
  </script>
</body>
</html>
