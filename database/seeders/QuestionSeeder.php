<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('viera_tomigrate/thtml5/js/test.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('File test.json tidak ditemukan!');
            return;
        }

        $jsonContent = File::get($jsonPath);
        $questions = json_decode($jsonContent, true);

        foreach ($questions as $questionData) {
            // Skip direction questions
            if ($questionData['type'] === 'direction') {
                continue;
            }

            Question::updateOrCreate(
                ['question_id' => $questionData['id']],
                [
                    'type' => $questionData['type'],
                    'question' => $questionData['question'] ?? '',
                    'options' => $questionData['options'] ?? null,
                    'answer' => $questionData['answer'] ?? 'A)',  // Default A) for testing
                    'score' => $questionData['score'] ?? 1,
                    'audio_url' => $questionData['audio'] ?? null,
                    'image_url' => $questionData['image'] ?? $questionData['image_three_question_1'] ?? null,
                ]
            );
        }

        $this->command->info('Questions imported/updated successfully!');
    }
}
