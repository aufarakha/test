<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Review Jawaban Anda - Viera Tryout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #10b981;
            --border-color: #e5e7eb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f9fafb;
            padding: 20px;
        }

        .review-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .score-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 20px;
        }

        .score-box {
            text-align: center;
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .score-label {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .score-value {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
        }

        .question-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
        }

        .question-number {
            font-weight: 700;
            color: #111827;
        }

        .question-type {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: 600;
        }

        .type-listening { background: #dbeafe; color: #1e40af; }
        .type-reading { background: #fef3c7; color: #92400e; }

        .question-text {
            color: #374151;
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .option {
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .option.user-answer {
            background: #dbeafe;
            border-color: #3b82f6;
            font-weight: 500;
        }

        .option.not-answered {
            opacity: 0.6;
        }

        .option-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .icon-selected {
            background: #3b82f6;
            color: white;
        }

        .audio-player {
            margin-top: 12px;
            padding: 12px;
            background: #f3f4f6;
            border-radius: 8px;
        }

        .question-image {
            max-width: 100%;
            border-radius: 8px;
            margin: 12px 0;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            .review-container {
                max-width: 100%;
            }

            .header-card {
                padding: 16px;
            }

            h3 {
                font-size: 1.3rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 16px;
            }

            .score-summary {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .score-box {
                padding: 12px;
            }

            .score-value {
                font-size: 28px;
            }

            .question-card {
                padding: 16px;
            }

            .question-header {
                flex-direction: column;
                gap: 8px;
            }

            .question-number {
                font-size: 16px;
            }

            .option {
                padding: 10px;
                font-size: 14px;
            }

            .option-icon {
                width: 20px;
                height: 20px;
                font-size: 10px;
            }

            .btn {
                width: 100%;
                margin-bottom: 8px;
            }

            audio {
                height: 40px;
            }
        }

        @media (max-width: 576px) {
            h3 {
                font-size: 1.1rem;
            }

            .score-value {
                font-size: 24px;
            }

            .question-text {
                font-size: 14px;
            }

            .option {
                font-size: 13px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="review-container">
        <!-- Header -->
        <div class="header-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 style="margin-bottom: 8px; font-weight: 700;">Review Jawaban Anda</h3>
                    <p style="color: #6b7280; margin: 0;">
                        <i class="fas fa-calendar"></i> {{ $result->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <a href="{{ route('user.welcome') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="score-summary">
                <div class="score-box">
                    <div class="score-label">Total Soal Dijawab</div>
                    <div class="score-value" style="color: var(--primary-color);">
                        {{ count(array_filter($result->jawaban_peserta, function($answer) {
                            return !empty($answer['answer']);
                        })) }} / 100
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; padding: 16px; background: #dbeafe; border-radius: 8px; border: 1px solid #3b82f6;">
                <p style="color: #1e40af; margin: 0; font-size: 14px;">
                    <i class="fas fa-info-circle"></i> <strong>Catatan:</strong> Halaman ini menampilkan jawaban yang Anda pilih untuk review dan evaluasi.
                </p>
            </div>
        </div>

        <!-- Questions Review -->
        @php
            $userAnswers = $result->jawaban_peserta;
        @endphp

        @foreach($questions as $questionId => $question)
            @if(strpos($questionId, 'q') === 0 && is_numeric(substr($questionId, 1)))
            @php
                $userAnswer = isset($userAnswers[$questionId]) ? ($userAnswers[$questionId]['answer'] ?? '') : '';
            @endphp

            <div class="question-card">
                <div class="question-header">
                    <span class="question-number">Soal {{ substr($questionId, 1) }}</span>
                    <span class="question-type {{ $question->type == 'listening' ? 'type-listening' : 'type-reading' }}">
                        {{ ucfirst($question->type) }}
                    </span>
                </div>

                @if($question->type == 'listening')
                    <div style="background: #f0f9ff; border: 2px solid #3b82f6; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                        <p style="color: #1e40af; font-weight: 600; margin: 0; text-align: center;">
                            <i class="fas fa-headphones"></i> Dengarkan audio untuk pertanyaan dan pilihan jawaban
                        </p>
                    </div>
                @endif

                <div class="question-text">{{ $question->question }}</div>

                @if($question->image_url)
                    <img src="{{ str_replace('../', '/', $question->image_url) }}" class="question-image" alt="Question Image">
                @endif

                @if($question->audio_url && $question->type == 'listening')
                    <div class="audio-player">
                        <audio controls style="width: 100%;">
                            <source src="{{ str_replace('../', '/', $question->audio_url) }}" type="audio/mpeg">
                        </audio>
                    </div>
                @endif

                <div style="margin-top: 16px;">
                    @foreach($question->options as $index => $option)
                        @php
                            $isUserAnswer = strcasecmp(trim($userAnswer), trim($option)) === 0;
                        @endphp

                        <div class="option {{ $isUserAnswer ? 'user-answer' : 'not-answered' }}">
                            @if($isUserAnswer)
                                <span class="option-icon icon-selected">
                                    <i class="fas fa-check"></i>
                                </span>
                            @else
                                <span style="width: 24px;"></span>
                            @endif

                            <span>{{ $option }}</span>

                            @if($isUserAnswer)
                                <span style="margin-left: auto; font-size: 12px; color: #3b82f6; font-weight: 600;">
                                    <i class="fas fa-user"></i> Jawaban Anda
                                </span>
                            @endif
                        </div>
                    @endforeach

                    @if(!$userAnswer)
                        <div style="color: #ef4444; font-size: 14px; margin-top: 12px; padding: 12px; background: #fef2f2; border-radius: 8px; border: 1px solid #fca5a5;">
                            <i class="fas fa-exclamation-circle"></i> <strong>Tidak dijawab</strong>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        @endforeach

        <div class="text-center mb-4">
            <a href="{{ route('user.welcome') }}" class="btn btn-primary" style="background: var(--primary-color); border: none; padding: 12px 32px;">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
