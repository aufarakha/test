# Laravel Migration Guide for Viera Exam System

## ✅ Completed Changes

All external HTTPS calls to `https://viera.toeic.or.id` have been stripped and replaced with local Laravel API endpoints.

---

## 📋 Modified Files

### 1. **index.html** (Login Page)
- ✅ Added CSRF token meta tag
- ✅ Changed login endpoint: `/api/auth/login`
- ✅ Removed external user data fetch
- ✅ Updated response handling for Laravel JSON format

### 2. **thtml5/viera/submit.html** (Exam Submission)
- ✅ Added CSRF token meta tag
- ✅ Changed submit endpoint: `/api/exam/submit`
- ✅ Updated to send answers as JSON object (not stringified)
- ✅ Added proper error handling

### 3. **thtml5/viera/opening2.html** (User Data Form)
- ✅ Added CSRF token meta tag
- ✅ Changed get user endpoint: `GET /api/user/{std_code}`
- ✅ Changed get kompetensi endpoint: `GET /api/kompetensi-keahlian`
- ✅ Changed update user endpoint: `PUT /api/user/{std_code}`
- ✅ Updated all AJAX calls with CSRF headers

---

## 🔧 Required Laravel Implementation

### **1. Database Migration**

Create migration files for these tables:

```bash
php artisan make:migration create_users_table
php artisan make:migration create_exam_results_table
php artisan make:migration create_kompetensi_keahlian_table
php artisan make:migration create_questions_table
php artisan make:migration create_answer_keys_table
```

#### **Users Table Schema**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('std_code')->unique(); // NISN
    $table->string('std_name');
    $table->string('std_nisn')->nullable();
    $table->enum('std_gender', ['L', 'P'])->nullable();
    $table->date('std_dob')->nullable();
    $table->string('std_npsn')->nullable();
    $table->string('sch_code')->nullable();
    $table->string('std_school')->nullable();
    $table->string('std_class')->nullable();
    $table->string('std_email')->nullable();
    $table->string('std_phone')->nullable();
    $table->unsignedBigInteger('kompetensi_keahlian')->nullable();
    $table->unsignedBigInteger('program_keahlian')->nullable();
    $table->unsignedBigInteger('bidang_keahlian')->nullable();
    $table->string('password');
    $table->timestamps();
});
```

#### **Exam Results Table Schema**
```php
Schema::create('exam_results', function (Blueprint $table) {
    $table->id();
    $table->string('std_code');
    $table->string('full_name');
    $table->string('sch_code');
    $table->json('jawaban_peserta'); // Store all answers
    $table->integer('listening_score')->default(0);
    $table->integer('reading_score')->default(0);
    $table->integer('total_score')->default(0);
    $table->string('device')->nullable();
    $table->timestamp('submitted_at');
    $table->timestamps();
    
    $table->foreign('std_code')->references('std_code')->on('users');
});
```

#### **Kompetensi Keahlian Table Schema**
```php
Schema::create('kompetensi_keahlian', function (Blueprint $table) {
    $table->id('kompetensi_id');
    $table->string('kompetensi_name');
    $table->unsignedBigInteger('program_id');
    $table->string('program_name');
    $table->unsignedBigInteger('bidang_id');
    $table->string('bidang_name');
    $table->timestamps();
});
```

#### **Questions Table Schema**
```php
Schema::create('questions', function (Blueprint $table) {
    $table->id();
    $table->string('question_id'); // q1, q2, etc.
    $table->enum('type', ['listening', 'reading']);
    $table->text('question')->nullable();
    $table->json('options')->nullable();
    $table->string('correct_answer'); // Store correct answer
    $table->integer('score'); // Points for this question
    $table->string('audio_path')->nullable();
    $table->string('image_path')->nullable();
    $table->timestamps();
});
```

---

### **2. API Routes** (`routes/api.php`)

```php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\KompetensiController;

// Authentication
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

// User Management
Route::get('/user/{std_code}', [UserController::class, 'show']);
Route::put('/user/{std_code}', [UserController::class, 'update']);

// Kompetensi Keahlian
Route::get('/kompetensi-keahlian', [KompetensiController::class, 'index']);

// Exam Submission (SERVER-SIDE SCORING)
Route::post('/exam/submit', [ExamController::class, 'submit']);
```

---

### **3. Controllers**

#### **AuthController.php**
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'npsn' => 'required',
        ]);

        $user = User::where('std_code', $request->nisn)
                    ->where('std_npsn', $request->npsn)
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal. NISN atau NPSN salah.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'std_code' => $user->std_code,
                'std_name' => $user->std_name,
                'sch_code' => $user->sch_code,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Implement logout logic if needed
        return response()->json(['success' => true]);
    }
}
```

#### **UserController.php**
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($std_code)
    {
        $user = User::where('std_code', $std_code)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $std_code)
    {
        $user = User::where('std_code', $std_code)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        $user->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
            'data' => $user
        ]);
    }
}
```

#### **KompetensiController.php**
```php
<?php

namespace App\Http\Controllers;

use App\Models\KompetensiKeahlian;
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index()
    {
        $kompetensi = KompetensiKeahlian::all();
        
        return response()->json($kompetensi);
    }
}
```

#### **ExamController.php** (⚠️ **IMPORTANT: SERVER-SIDE SCORING**)
```php
<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'std_code' => 'required',
            'full_name' => 'required',
            'sch_code' => 'required',
            'jawaban_peserta' => 'required|array',
            'device' => 'nullable|string',
        ]);

        try {
            // ⚠️ IMPORTANT: Calculate scores on SERVER-SIDE for security
            $answers = $request->jawaban_peserta;
            $listeningScore = 0;
            $readingScore = 0;

            // Get all questions with correct answers from database
            $questions = Question::all()->keyBy('question_id');

            foreach ($answers as $questionId => $studentAnswer) {
                $question = $questions->get($questionId);
                
                if (!$question) continue;

                // Get student's answer
                $givenAnswer = isset($studentAnswer['answer']) 
                    ? trim($studentAnswer['answer']) 
                    : '';

                // Compare with correct answer from database
                if (strtolower($givenAnswer) === strtolower($question->correct_answer)) {
                    if ($question->type === 'listening') {
                        $listeningScore += $question->score;
                    } else {
                        $readingScore += $question->score;
                    }
                }
            }

            $totalScore = $listeningScore + $readingScore;

            // Save to database
            $result = ExamResult::create([
                'std_code' => $request->std_code,
                'full_name' => $request->full_name,
                'sch_code' => $request->sch_code,
                'jawaban_peserta' => json_encode($answers),
                'listening_score' => $listeningScore,
                'reading_score' => $readingScore,
                'total_score' => $totalScore,
                'device' => $request->device,
                'submitted_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Skor berhasil disimpan',
                'data' => [
                    'listening_score' => $listeningScore,
                    'reading_score' => $readingScore,
                    'total_score' => $totalScore,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan hasil ujian: ' . $e->getMessage()
            ], 500);
        }
    }
}
```

---

### **4. Models**

#### **User.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'std_code', 'std_name', 'std_nisn', 'std_gender', 'std_dob',
        'std_npsn', 'sch_code', 'std_school', 'std_class', 'std_email',
        'std_phone', 'kompetensi_keahlian', 'program_keahlian', 
        'bidang_keahlian', 'password'
    ];

    protected $hidden = ['password'];
}
```

#### **ExamResult.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'std_code', 'full_name', 'sch_code', 'jawaban_peserta',
        'listening_score', 'reading_score', 'total_score', 
        'device', 'submitted_at'
    ];

    protected $casts = [
        'jawaban_peserta' => 'array',
        'submitted_at' => 'datetime',
    ];
}
```

#### **KompetensiKeahlian.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KompetensiKeahlian extends Model
{
    protected $table = 'kompetensi_keahlian';
    protected $primaryKey = 'kompetensi_id';
    
    protected $fillable = [
        'kompetensi_name', 'program_id', 'program_name',
        'bidang_id', 'bidang_name'
    ];
}
```

#### **Question.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_id', 'type', 'question', 'options',
        'correct_answer', 'score', 'audio_path', 'image_path'
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
```

---

### **5. CORS Configuration** (`config/cors.php`)

```php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

---

## 🔐 Security Improvements

### **1. Remove Answer Keys from Client**

The current `test.json` contains answer keys visible to students. You must:

1. **Remove answer keys** from `thtml5/js/test.json`
2. **Store answer keys** in Laravel database (questions table)
3. **Calculate scores server-side** in `ExamController::submit()`

#### Current test.json (INSECURE):
```json
{
  "id": "q51",
  "type": "reading",
  "question": "What is...",
  "options": ["A", "B", "C", "D"],
  "answer": "B",  // ⚠️ REMOVE THIS
  "score": 1
}
```

#### New test.json (SECURE):
```json
{
  "id": "q51",
  "type": "reading",
  "question": "What is...",
  "options": ["A", "B", "C", "D"]
  // No answer key here!
}
```

### **2. Update script.js**

Remove client-side scoring logic from `thtml5/js/script.js`:

**REMOVE these lines (around line 600-625):**
```javascript
let correctAnswer = cleanText(questionData.answer || "");
let score = selectedAnswer === correctAnswer ? questionData.score : 0;
```

**REPLACE with:**
```javascript
// Just save the answer, no scoring
localStorage.setItem(
  namespace + questionData.id,
  JSON.stringify({ answer: selectedAnswer })
);
```

---

## 📦 Deployment Steps

1. **Setup Laravel Project**
   ```bash
   composer install
   php artisan key:generate
   ```

2. **Configure Database** (`.env`)
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=viera
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Run Migrations**
   ```bash
   php artisan migrate
   ```

4. **Seed Answer Keys**
   - Import correct answers into `questions` table
   - Ensure all question IDs match (q1-q100)

5. **Move Files to Laravel Public**
   ```bash
   # Move all viera files to Laravel public directory
   cp -r c:/laragon/www/viera/* /path/to/laravel/public/
   ```

6. **Update Base URLs**
   - If Laravel is in a subdirectory, update all asset paths
   - Update API endpoints if needed

7. **Test Everything**
   - Login flow
   - User data form
   - Exam submission
   - Score calculation (server-side)

---

## ⚠️ Important Notes

1. **CSRF Protection**: All forms must include `{{ csrf_token() }}` meta tag
2. **Server-Side Validation**: Always validate on server, never trust client data
3. **Answer Key Security**: Never expose answer keys to the client
4. **Score Integrity**: Always calculate scores on the server
5. **Session Management**: Consider using Laravel Sanctum for API authentication

---

## 🎯 Testing Checklist

- [ ] Login with NISN/NPSN works
- [ ] User data loads correctly
- [ ] Kompetensi dropdown populates
- [ ] User data updates successfully
- [ ] Exam loads without answer keys in client
- [ ] Exam submission works
- [ ] Server calculates scores correctly
- [ ] Results are saved to database
- [ ] No external API calls remain

---

## 📞 Support

For issues during migration, check:
- Laravel logs: `storage/logs/laravel.log`
- Browser console for JavaScript errors
- Network tab for API call responses

Good luck with your migration! 🚀
