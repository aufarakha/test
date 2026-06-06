<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Terkunci - Viera Tryout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #10b981;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .lock-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 50px 40px;
            text-align: center;
        }

        .lock-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite;
        }

        .lock-icon i {
            font-size: 50px;
            color: white;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(245, 158, 11, 0);
            }
        }

        h2 {
            color: #111827;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 28px;
        }

        .lock-message {
            color: #6b7280;
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 16px;
        }

        .info-box {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .info-box h5 {
            color: #92400e;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box h5 i {
            color: #f59e0b;
        }

        .info-box ul {
            color: #78350f;
            margin: 0;
            padding-left: 20px;
            line-height: 1.8;
        }

        .score-display {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            color: white;
        }

        .score-display h4 {
            font-size: 18px;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .score-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .score-item {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 12px;
        }

        .score-label {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .score-value {
            font-size: 24px;
            font-weight: 700;
        }

        .total-score {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 15px;
        }

        .total-score .score-label {
            font-size: 14px;
        }

        .total-score .score-value {
            font-size: 32px;
        }

        .btn-back {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-back:hover {
            background: #059669;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-unlock {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-unlock:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4);
        }

        .btn-unlock:active {
            transform: translateY(0);
        }

        .btn-unlock:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        #status-message.success {
            background: #d1fae5;
            border: 2px solid #10b981;
            color: #065f46;
        }

        #status-message.error {
            background: #fee2e2;
            border: 2px solid #ef4444;
            color: #991b1b;
        }

        @media (max-width: 576px) {
            .lock-container {
                padding: 40px 24px;
            }

            h2 {
                font-size: 24px;
            }

            .lock-icon {
                width: 100px;
                height: 100px;
            }

            .lock-icon i {
                font-size: 40px;
            }

            .lock-message {
                font-size: 14px;
            }

            .info-box {
                padding: 16px;
            }

            .info-box ul {
                font-size: 14px;
            }

            .score-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="lock-container">
        <div class="lock-icon">
            <i class="fas fa-lock"></i>
        </div>

        <h2>Review Jawaban Terkunci</h2>
        <p class="lock-message">
            Review jawaban untuk tryout ini masih terkunci. Anda perlu membayar kuota untuk membuka dan melihat jawaban yang telah Anda pilih.
        </p>

        <div class="info-box">
            <h5><i class="fas fa-info-circle"></i> Cara Membuka Review</h5>
            <ul>
                <li>Review jawaban membutuhkan <strong>kuota tambahan</strong> untuk dibuka</li>
                <li>Setelah dibuka, review dapat dilihat <strong>tanpa kuota</strong> berkali-kali</li>
                <li>Klik tombol "Buka Review" di bawah untuk membayar kuota dan membuka review</li>
            </ul>
        </div>

        <div id="unlock-section">
            <button id="unlockButton" class="btn-unlock" onclick="unlockReview()">
                <i class="fas fa-unlock-alt"></i> Buka Review dengan Kuota
            </button>
        </div>

        <div id="status-message" style="display: none; margin-top: 20px; padding: 16px; border-radius: 8px;"></div>

        <a href="{{ route('user.welcome') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const resultId = {{ $result->id }};
        const stdCode = "{{ session('user_data.std_code') }}";
        const csrfToken = "{{ csrf_token() }}";

        async function unlockReview() {
            const unlockButton = document.getElementById('unlockButton');
            const statusMessage = document.getElementById('status-message');
            
            // Disable button
            unlockButton.disabled = true;
            unlockButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuka Review...';

            try {
                const response = await fetch('/api/exam/unlock-review', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        exam_result_id: resultId,
                        std_code: stdCode,
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    // Show success message
                    statusMessage.className = 'success';
                    statusMessage.style.display = 'block';
                    statusMessage.innerHTML = `
                        <i class="fas fa-check-circle"></i> <strong>${result.message}</strong><br>
                        <small>Kuota tersisa: ${result.quota_remaining || 0}</small><br>
                        <small>Anda akan dialihkan ke halaman review...</small>
                    `;

                    // Hide unlock section
                    document.getElementById('unlock-section').style.display = 'none';

                    // Redirect to review page after 2 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    // Show error message
                    statusMessage.className = 'error';
                    statusMessage.style.display = 'block';
                    statusMessage.innerHTML = `
                        <i class="fas fa-exclamation-circle"></i> <strong>${result.message}</strong><br>
                        ${result.quota_needed ? `<small>Kuota dibutuhkan: ${result.quota_needed} | Kuota Anda: ${result.quota_available || 0}</small>` : ''}
                    `;

                    // Re-enable button
                    unlockButton.disabled = false;
                    unlockButton.innerHTML = '<i class="fas fa-unlock-alt"></i> Buka Review dengan Kuota';
                }
            } catch (error) {
                console.error('Error:', error);
                statusMessage.className = 'error';
                statusMessage.style.display = 'block';
                statusMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> <strong>Terjadi kesalahan. Silakan coba lagi.</strong>';
                
                // Re-enable button
                unlockButton.disabled = false;
                unlockButton.innerHTML = '<i class="fas fa-unlock-alt"></i> Buka Review dengan Kuota';
            }
        }
    </script>
</body>
</html>
