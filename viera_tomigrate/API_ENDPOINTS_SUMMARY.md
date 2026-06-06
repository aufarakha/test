# API Endpoints Summary

## All External Server Calls Removed ✅

This document lists all the API endpoints that need to be implemented in Laravel.

---

## 📍 API Endpoints Required

### 1. **Authentication**

#### `POST /api/auth/login`
**Purpose**: Login with NISN and NPSN

**Request Body**:
```json
{
  "nisn": "1234567890",
  "npsn": "12345678",
  "_token": "csrf_token_here"
}
```

**Response (Success)**:
```json
{
  "success": true,
  "data": {
    "std_code": "1234567890",
    "std_name": "John Doe",
    "sch_code": "SCH001"
  }
}
```

**Response (Error)**:
```json
{
  "success": false,
  "message": "Login gagal. NISN atau NPSN salah."
}
```

**Used in**: `index.html` (Line 191-245)

---

### 2. **User Management**

#### `GET /api/user/{std_code}`
**Purpose**: Get user data by student code

**Response**:
```json
{
  "success": true,
  "data": {
    "std_code": "1234567890",
    "std_name": "John Doe",
    "std_nisn": "1234567890",
    "std_gender": "L",
    "std_dob": "2005-01-15",
    "std_npsn": "12345678",
    "sch_code": "SCH001",
    "std_school": "SMK Example",
    "std_class": "XII",
    "std_email": "john@example.com",
    "std_phone": "081234567890",
    "kompetensi_keahlian": "1",
    "program_keahlian": "10",
    "bidang_keahlian": "100"
  }
}
```

**Used in**: `thtml5/viera/opening2.html` (Line 289-312)

---

#### `PUT /api/user/{std_code}`
**Purpose**: Update user data

**Request Body**:
```json
{
  "std_code": "1234567890",
  "std_name": "John Doe",
  "std_nisn": "1234567890",
  "std_gender": "L",
  "std_dob": "2005-01-15",
  "std_npsn": "12345678",
  "std_school": "SMK Example",
  "std_class": "XII",
  "std_email": "john@example.com",
  "std_phone": "081234567890",
  "kompetensi_keahlian": "1",
  "program_keahlian": "10",
  "bidang_keahlian": "100",
  "sch_code": "SCH001",
  "_token": "csrf_token_here"
}
```

**Response**:
```json
{
  "success": true,
  "message": "Data berhasil diperbarui.",
  "data": { /* updated user data */ }
}
```

**Used in**: `thtml5/viera/opening2.html` (Line 376-409)

---

### 3. **Kompetensi Keahlian**

#### `GET /api/kompetensi-keahlian`
**Purpose**: Get list of all kompetensi keahlian (vocational competencies)

**Response**:
```json
[
  {
    "kompetensi_id": "1",
    "kompetensi_name": "Teknik Komputer dan Jaringan",
    "program_id": "10",
    "program_name": "Teknik Komputer dan Informatika",
    "bidang_id": "100",
    "bidang_name": "Teknologi dan Rekayasa"
  },
  {
    "kompetensi_id": "2",
    "kompetensi_name": "Rekayasa Perangkat Lunak",
    "program_id": "10",
    "program_name": "Teknik Komputer dan Informatika",
    "bidang_id": "100",
    "bidang_name": "Teknologi dan Rekayasa"
  }
]
```

**Used in**: `thtml5/viera/opening2.html` (Line 314-354)

---

### 4. **Exam Submission (⚠️ CRITICAL)**

#### `POST /api/exam/submit`
**Purpose**: Submit exam answers and calculate score SERVER-SIDE

**Request Body**:
```json
{
  "full_name": "John Doe",
  "std_code": "1234567890",
  "sch_code": "SCH001",
  "jawaban_peserta": {
    "q1": { "answer": "A)" },
    "q2": { "answer": "B)" },
    "q51": { "answer": "C" },
    "q100": { "answer": "D" }
  },
  "device": "Windows",
  "_token": "csrf_token_here"
}
```

**Response (Success)**:
```json
{
  "success": true,
  "message": "Skor berhasil disimpan",
  "data": {
    "listening_score": 45,
    "reading_score": 42,
    "total_score": 87
  }
}
```

**Response (Error)**:
```json
{
  "success": false,
  "message": "Gagal menyimpan hasil ujian: [error details]"
}
```

**⚠️ IMPORTANT NOTES:**
- Score calculation MUST happen on server side
- Client only sends student answers, NOT scores
- Server compares answers with answer keys in database
- Server calculates and returns final scores

**Used in**: `thtml5/viera/submit.html` (Line 327-395)

---

## 🔄 Replaced External URLs

| Old External URL | New Laravel Endpoint |
|------------------|---------------------|
| `https://viera.toeic.or.id/api_viera/api_vie.php` | `/api/auth/login` |
| `https://viera.toeic.or.id/api_viera/api_get_user.php` | `GET /api/user/{std_code}` |
| `https://viera.toeic.or.id/api_viera/api_update_user.php` | `PUT /api/user/{std_code}` |
| `https://viera.toeic.or.id/api_viera/get_area.php?type=keahlian` | `GET /api/kompetensi-keahlian` |
| `https://viera.toeic.or.id/api_viera/save_score.php` | `POST /api/exam/submit` |

---

## 🔐 Security Headers Required

All API requests must include:

```javascript
headers: {
  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}
```

---

## 📝 Request Methods Used

- `POST` - Login, Exam Submission
- `GET` - Fetch user data, kompetensi list
- `PUT` - Update user data

---

## ✅ Files Modified

1. ✅ `index.html` - Login page
2. ✅ `thtml5/viera/opening2.html` - User data form
3. ✅ `thtml5/viera/submit.html` - Exam submission

---

## 🚨 Critical Security Changes Needed

### **Remove Answer Keys from Client-Side**

**Current Issue**: `thtml5/js/test.json` contains answer keys that students can see

**File**: `thtml5/js/script.js`

**Remove** (Lines ~600-620):
```javascript
let correctAnswer = cleanText(questionData.answer || "");
let score = selectedAnswer === correctAnswer ? questionData.score : 0;
```

**Replace with**:
```javascript
// Only save answer, no scoring on client
localStorage.setItem(
  namespace + questionData.id,
  JSON.stringify({ answer: selectedAnswer })
);
```

### **Update test.json format**

**Remove** `"answer"` and `"score"` fields from questions in `test.json`

**Before** (INSECURE):
```json
{
  "id": "q51",
  "type": "reading",
  "question": "What is...",
  "options": ["A", "B", "C", "D"],
  "answer": "B",
  "score": 1
}
```

**After** (SECURE):
```json
{
  "id": "q51",
  "type": "reading",
  "question": "What is...",
  "options": ["A", "B", "C", "D"]
}
```

---

## 📊 Data Flow

### Login Flow
```
User enters NISN/NPSN 
  → POST /api/auth/login
  → Laravel validates credentials
  → Returns user data
  → Client stores in localStorage
  → Redirect to exam
```

### Exam Submission Flow
```
User completes exam
  → Client collects answers (no scores)
  → POST /api/exam/submit with answers only
  → Server fetches correct answers from database
  → Server calculates scores
  → Server saves to database
  → Returns calculated scores to client
  → Client shows results
```

---

## 🧪 Testing Endpoints

Use these curl commands to test:

### Test Login
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"nisn":"1234567890","npsn":"12345678"}'
```

### Test Get User
```bash
curl -X GET http://localhost/api/user/1234567890
```

### Test Submit Exam
```bash
curl -X POST http://localhost/api/exam/submit \
  -H "Content-Type: application/json" \
  -d '{
    "std_code":"1234567890",
    "full_name":"John Doe",
    "sch_code":"SCH001",
    "jawaban_peserta":{"q1":{"answer":"A"}},
    "device":"Windows"
  }'
```

---

## 📦 Next Steps

1. ✅ All external URLs stripped
2. ⏳ Create Laravel controllers
3. ⏳ Create database migrations
4. ⏳ Implement server-side scoring
5. ⏳ Remove answer keys from client
6. ⏳ Update script.js to remove client-side scoring
7. ⏳ Test all endpoints
8. ⏳ Deploy to production

---

**Status**: Ready for Laravel backend implementation! 🚀
