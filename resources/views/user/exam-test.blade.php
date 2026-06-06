<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>VIERA Test</title>
  <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="/js/app-scale.js"></script>
  <!-- script.js will be loaded at the end of body for proper initialization -->
  <style>
    body {
      background-image: url("/assets/image/bg-test.png");
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      box-sizing: border-box;
      width: 100%;
      height: 100vh;
      overflow: hidden;
      background-size: contain;
      background-position: center;
      background-repeat: no-repeat;
      background-color: #f9f9f9;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #next-listening-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 9999;
      padding: 10px 20px;
      font-size: 1rem;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      transition: background 0.3s ease;
    }

    .instruction-text {
      position: fixed !important;
      margin-top: -60px;
      margin-left: -10px;
      font-weight: bold;
      font-size: 1.1rem !important;
    }

    .single-question-box {
      border: 2px solid #007bff !important;
      padding: 20px !important;
      margin: 15px 0 !important;
      width: 100% !important;
      max-width: 800px !important;
    }

    .question-header {
      padding-bottom: 10px !important;
    }

    .question-title {
      font-size: 1.3rem !important;
      margin: 0 !important;
    }

    .single-options-wrapper {
      display: flex !important;
      flex-direction: column !important;
    }

    .option-item input[type="radio"] {
      margin-right: 10px !important;
      transform: scale(1.1) !important;
    }

    .option-text {
      font-size: 1.1rem !important;
    }

    .three-question-layout {
      width: 100% !important;
      max-width: 1200px !important;
      margin: 0 auto !important;
      display: flex !important;
      justify-content: center !important;
      align-items: center !important;
      gap: 15px !important;
    }

    .question-box {
      width: 400px !important;
      min-width: 452px !important;
      height: 340px !important;
      padding: 20px !important;
      margin: 10px !important;
      box-sizing: border-box !important;
    }

    .active-question {
      font-size: 1.3rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2) !important;
    }

    .muted-question {
      font-size: 1.1rem;
      opacity: 0.7 !important;
    }

    #question-image-container.active-three-question-image {
      position: fixed !important;
      top: 20% !important;
      left: 50% !important;
      transform: translateX(-50%) !important;
      z-index: 9999 !important;
      max-width: 450px;
      width: 90%;
      margin: 0 auto;
      display: block !important;
    }

    .three-question-layout.has-three-question-image {
      padding-top: 350px !important;
      transition: padding 0.3s ease;
    }

    .instruction-text {
      font-size: 16px;
      font-weight: bold;
      color: #333;
      margin-bottom: 10px;
    }

    .question-wrapper {
      margin-bottom: 20px;
    }

    .option-item {
      margin-bottom: 10px;
    }

    #global-timer {
      position: fixed;
      top: 2px;
      right: 80px;
      font-size: 0.75rem;
      font-weight: bold;
      color: #fff;
      background-color: #202775;
      padding: 5px 10px;
      border-radius: 4px;
      z-index: 1000;
    }

    .container {
      width: 90%;
      max-width: 600px;
      position: relative;
      top: 3%;
    }

    #question-text {
      font-size: 1.3rem;
      margin-bottom: 15px;
    }

    .hidden {
      display: none;
    }

    #options label {
      display: block;
      width: 100%;
      padding: 4px 8px;
      font-size: 1.1rem;
      border-radius: 5px;
      margin-bottom: 4px;
      cursor: pointer;
      transition: background 0.2s;
      text-align: left;
    }

    #buttons {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      justify-content: center;
      gap: 100px;
      z-index: 9999;
    }

    #buttons button {
      box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
      transition: background-color 0.2s ease;
    }

    #continue-button {
      padding: 6px 8px;
      font-size: 0.85rem;
      border-radius: 5px;
      width: auto;
      min-width: 80px;
      margin-top: 3px;
    }

    #continue-direction {
      position: fixed;
      bottom: 20px;
      margin-bottom: -40px;
      left: 60%;
      transform: translateX(-50%);
      border: none;
      border-radius: 5px;
      background-color: #007bff;
      color: #fff;
      cursor: pointer;
      opacity: 1;
      pointer-events: auto;
    }

    #btn-prev {
      position: fixed;
      bottom: 20px;
      margin-bottom: -60px;
      left: 45%;
      transform: translateX(-50%);
      border: none;
      border-radius: 5px;
      background-color: #007bff;
      color: #fff;
      cursor: pointer;
      opacity: 1;
      pointer-events: auto;
    }

    .main-content {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      gap: 20px;
    }

    #question-image-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 100%;
      max-width: 800px;
      max-height: 550px;
      overflow-y: auto;
      padding: 10px;
      margin: 0 auto;
      margin-top: 100px;
    }

    .question-image {
      width: 100%;
      max-width: 850px;
      height: auto;
      border-radius: 8px;
    }

    .container {
      max-width: 608px;
      width: 50%;
    }
  </style>
</head>

<body>
  <div id="global-timer">60:00</div>
  
  <!-- Debug Mode Button -->
  @if(config('app.debug'))
  <button id="debug-fill-button" onclick="debugFillRandomAnswers()" style="position: fixed; top: 50px; left: 20px; z-index: 10000; padding: 10px 20px; background: #ff6b6b; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
    <i class="fas fa-bug"></i> DEBUG: Fill Random Answers
  </button>
  @endif

  <div class="main-content">
    <div id="question-image-container">
      <img id="question-image" class="question-image" src="" alt="Question Image" />
    </div>

    <div class="container">
      <div id="direction-box" class="text-center hidden">
        <p id="direction-text" class="fw-bold"></p>
        <button id="continue-button" class="btn btn-primary mt-3" onclick="continueTest()">
          Continue
        </button>
      </div>

      <div id="question-box">
        <p id="question-text"></p>
        <div id="options" class="d-flex flex-column gap-2 text-start"></div>
        <div id="buttons" class="text-center mt-1"></div>
      </div>
    </div>
  </div>

  <script>
    // Validation already handled in script.js
    // Full-screen toggle
    document.addEventListener("DOMContentLoaded", function () {
      // Mencegah klik kanan
      document.addEventListener("contextmenu", function (event) {
        event.preventDefault();
      });

      // Mencegah blok teks (highlight)
      document.addEventListener("selectstart", function (event) {
        event.preventDefault();
      });

      // Mencegah copy teks (Ctrl + C)
      document.addEventListener("copy", function (event) {
        event.preventDefault();
      });
    });

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
  
  <!-- Debug Mode Script -->
  @if(config('app.debug'))
  <script>
    function debugFillRandomAnswers() {
      const stdCode = sessionStorage.getItem("std_code");
      if (!stdCode) {
        alert('Session tidak ditemukan!');
        return;
      }

      const button = document.getElementById('debug-fill-button');
      button.disabled = true;
      button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filling...';

      // Options untuk random selection
      const options = ['A)', 'B)', 'C)', 'D)'];
      
      // Generate random answers untuk semua 100 soal
      let filledCount = 0;
      for (let i = 1; i <= 100; i++) {
        const questionId = 'q' + i;
        const randomOption = options[Math.floor(Math.random() * options.length)];
        
        // Simulate lengkap format jawaban
        const answer = {
          answer: randomOption,
          score: 0 // Will be calculated server-side
        };

        // Save to localStorage
        localStorage.setItem(stdCode + "::" + questionId, JSON.stringify(answer));
        filledCount++;
      }

      button.innerHTML = '<i class="fas fa-check"></i> Done! ' + filledCount + ' answers';
      button.style.background = '#51cf66';
      
      console.log('✅ DEBUG: Filled ' + filledCount + ' random answers');
      
      // Show "Go to Submit" button
      if (!document.getElementById('debug-submit-button')) {
        const submitBtn = document.createElement('button');
        submitBtn.id = 'debug-submit-button';
        submitBtn.onclick = function() {
          window.location.href = '/user/exam/submit';
        };
        submitBtn.style.cssText = `
          position: fixed;
          top: 50px;
          left: 280px;
          z-index: 10000;
          padding: 10px 20px;
          background: #3b82f6;
          color: white;
          border: none;
          border-radius: 8px;
          cursor: pointer;
          font-weight: bold;
          box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        `;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Go to Submit Page';
        document.body.appendChild(submitBtn);
      }

      alert('✅ Random answers filled for all 100 questions!\n\nClick the blue "Go to Submit Page" button or navigate to the last question.');
    }
  </script>
  @endif
  
  <!-- Load main exam script after DOM is ready -->
  <script src="/js/script.js?v=20260606074000"></script>
</body>
</html>
