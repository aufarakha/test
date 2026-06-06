# Migration Checklist - Viera to Laravel

## ✅ Completed Tasks

### 1. **External Server Dependencies Removed**
- [x] Stripped all `https://viera.toeic.or.id` API calls
- [x] Replaced with local Laravel endpoint placeholders
- [x] Added CSRF token meta tags to all forms
- [x] Updated AJAX calls with proper headers

### 2. **Files Modified**
- [x] `index.html` - Login page updated
- [x] `thtml5/viera/submit.html` - Exam submission updated  
- [x] `thtml5/viera/opening2.html` - User data form updated

### 3. **Documentation Created**
- [x] `LARAVEL_MIGRATION_GUIDE.md` - Complete implementation guide
- [x] `API_ENDPOINTS_SUMMARY.md` - API endpoint specifications
- [x] `MIGRATION_CHECKLIST.md` - This checklist

---

## ⏳ Pending Tasks (Your Next Steps)

### Phase 1: Laravel Setup

- [ ] **Create Laravel Project** (if not exists)
  ```bash
  composer create-project laravel/laravel viera-backend
  cd viera-backend
  php artisan key:generate
  ```

- [ ] **Configure Database**
  - [ ] Update `.env` with database credentials
  - [ ] Create `viera` database in MySQL

### Phase 2: Database Schema

- [ ] **Create Migrations**
  ```bash
  php artisan make:migration create_users_table
  php artisan make:migration create_exam_results_table
  php artisan make:migration create_kompetensi_keahlian_table
  php artisan make:migration create_questions_table
  ```

- [ ] **Copy Migration Code**
  - [ ] Copy schema from `LARAVEL_MIGRATION_GUIDE.md`
  - [ ] Paste into migration files
  - [ ] Run migrations: `php artisan migrate`

### Phase 3: Models & Controllers

- [ ] **Create Models**
  ```bash
  php artisan make:model User
  php artisan make:model ExamResult
  php artisan make:model KompetensiKeahlian
  php artisan make:model Question
  ```

- [ ] **Create Controllers**
  ```bash
  php artisan make:controller AuthController
  php artisan make:controller UserController
  php artisan make:controller ExamController
  php artisan make:controller KompetensiController
  ```

- [ ] **Copy Controller Code**
  - [ ] Copy code from `LARAVEL_MIGRATION_GUIDE.md`
  - [ ] Paste into respective controllers
  - [ ] Review and customize as needed

### Phase 4: API Routes

- [ ] **Update `routes/api.php`**
  - [ ] Copy routes from `LARAVEL_MIGRATION_GUIDE.md`
  - [ ] Test each route with Postman/curl

### Phase 5: Security Implementation

#### 🚨 CRITICAL: Remove Client-Side Scoring

- [ ] **Update `thtml5/js/script.js`**
  - [ ] Remove lines that calculate score on client (line ~600-625)
  - [ ] Remove `correctAnswer` comparison logic
  - [ ] Only save student answers, no scores
  
  **Find and remove:**
  ```javascript
  let correctAnswer = cleanText(questionData.answer || "");
  let score = selectedAnswer === correctAnswer ? questionData.score : 0;
  ```
  
  **Replace with:**
  ```javascript
  // Only save answer, scoring happens on server
  localStorage.setItem(
    namespace + questionData.id,
    JSON.stringify({ answer: selectedAnswer })
  );
  ```

- [ ] **Update `thtml5/js/test.json`**
  - [ ] Remove `"answer"` field from all questions
  - [ ] Remove `"score"` field from all questions
  - [ ] Keep only: id, type, question, options, audio, image

- [ ] **Create Answer Key Seeder**
  ```bash
  php artisan make:seeder QuestionSeeder
  ```
  - [ ] Manually input all 100 questions with correct answers
  - [ ] Include score value for each question
  - [ ] Run seeder: `php artisan db:seed --class=QuestionSeeder`

### Phase 6: Data Migration

- [ ] **Export Old Database Data** (if exists)
  - [ ] Export user data
  - [ ] Export kompetensi keahlian data
  - [ ] Export exam results (if any)

- [ ] **Import to Laravel Database**
  - [ ] Create seeders for existing data
  - [ ] Run seeders
  - [ ] Verify data integrity

### Phase 7: File Organization

- [ ] **Move Frontend Files**
  - [ ] Copy entire `viera` folder to Laravel `public` directory
  - [ ] Or create symbolic link
  - [ ] Update asset paths if needed

- [ ] **Configure Laravel Routes**
  - [ ] Add web route for main page
  - [ ] Ensure API routes are working
  - [ ] Test CSRF token generation

### Phase 8: Testing

#### Login System
- [ ] Test login with valid credentials
- [ ] Test login with invalid credentials
- [ ] Verify session/token creation
- [ ] Check localStorage data storage

#### User Management
- [ ] Test fetching user data
- [ ] Test updating user data
- [ ] Verify data validation
- [ ] Check error handling

#### Kompetensi System
- [ ] Test fetching kompetensi list
- [ ] Verify dropdown population
- [ ] Check data relationships

#### Exam System
- [ ] Test question loading (without answer keys)
- [ ] Test answer saving to localStorage
- [ ] Test exam submission
- [ ] **CRITICAL**: Verify server-side score calculation
- [ ] Check score accuracy
- [ ] Verify data saved to database

### Phase 9: Security Audit

- [ ] **Client-Side Security**
  - [ ] Verify NO answer keys in JavaScript
  - [ ] Verify NO answer keys in JSON files
  - [ ] Check browser DevTools for exposed data
  - [ ] Verify scores NOT calculated in browser

- [ ] **Server-Side Security**
  - [ ] CSRF protection enabled
  - [ ] Input validation on all endpoints
  - [ ] SQL injection prevention (use Eloquent)
  - [ ] XSS protection enabled
  - [ ] Rate limiting on login endpoint

### Phase 10: Deployment

- [ ] **Environment Configuration**
  - [ ] Set `APP_ENV=production` in `.env`
  - [ ] Set `APP_DEBUG=false`
  - [ ] Generate new `APP_KEY`
  - [ ] Configure production database

- [ ] **Optimize Laravel**
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

- [ ] **Server Setup**
  - [ ] Configure web server (Apache/Nginx)
  - [ ] Set proper file permissions
  - [ ] Enable HTTPS
  - [ ] Configure SSL certificate

### Phase 11: Final Testing

- [ ] Test entire flow from login to submission
- [ ] Test with multiple users simultaneously
- [ ] Test on different browsers
- [ ] Test on mobile devices
- [ ] Load testing (if expecting many users)

---

## 🔍 Verification Points

### Before Going Live

| Check | Status | Notes |
|-------|--------|-------|
| No external API calls remain | ⏳ | Verify in Network tab |
| Answer keys NOT in client files | ⏳ | Check test.json and script.js |
| Scores calculated on server | ⏳ | Test submission endpoint |
| CSRF protection working | ⏳ | Try submitting without token |
| All API endpoints respond | ⏳ | Test with Postman |
| Database saves correctly | ⏳ | Check tables after submission |
| Error handling works | ⏳ | Test with invalid data |
| Login validation works | ⏳ | Try wrong credentials |
| User data updates correctly | ⏳ | Modify and verify |
| Mobile responsive | ⏳ | Test on phones/tablets |

---

## 📋 Quick Reference

### New API Endpoints
```
POST   /api/auth/login
GET    /api/user/{std_code}
PUT    /api/user/{std_code}
GET    /api/kompetensi-keahlian
POST   /api/exam/submit
```

### Modified Files
```
✅ index.html
✅ thtml5/viera/submit.html
✅ thtml5/viera/opening2.html
⏳ thtml5/js/script.js (remove scoring)
⏳ thtml5/js/test.json (remove answers)
```

### Required Laravel Files
```
Controllers:
- AuthController.php
- UserController.php
- ExamController.php
- KompetensiController.php

Models:
- User.php
- ExamResult.php
- KompetensiKeahlian.php
- Question.php

Migrations:
- create_users_table
- create_exam_results_table
- create_kompetensi_keahlian_table
- create_questions_table
```

---

## 🚨 Critical Security Reminders

1. **NEVER expose answer keys to client**
2. **ALWAYS calculate scores on server**
3. **ALWAYS validate input on server**
4. **ALWAYS use CSRF protection**
5. **NEVER trust client-submitted scores**

---

## 📞 Troubleshooting

### Common Issues

**Issue**: CSRF token mismatch
**Solution**: Ensure meta tag exists and is read correctly in AJAX calls

**Issue**: API returns 404
**Solution**: Check routes are registered, run `php artisan route:list`

**Issue**: Scores are wrong
**Solution**: Verify answer keys in database match original test.json

**Issue**: CORS errors
**Solution**: Configure `config/cors.php` properly

**Issue**: Login not working
**Solution**: Check database has user records with matching NISN/NPSN

---

## ✨ Success Criteria

Your migration is successful when:

1. ✅ Users can log in with NISN/NPSN
2. ✅ User data loads and updates correctly
3. ✅ Exam loads without showing answer keys
4. ✅ Students can take exam and submit
5. ✅ Scores are calculated correctly by server
6. ✅ Results are saved to database
7. ✅ No external API dependencies
8. ✅ All security measures in place

---

## 📈 Progress Tracking

- **Phase 1**: Laravel Setup - ⏳ Not Started
- **Phase 2**: Database Schema - ⏳ Not Started  
- **Phase 3**: Models & Controllers - ⏳ Not Started
- **Phase 4**: API Routes - ⏳ Not Started
- **Phase 5**: Security Implementation - ⏳ Not Started
- **Phase 6**: Data Migration - ⏳ Not Started
- **Phase 7**: File Organization - ⏳ Not Started
- **Phase 8**: Testing - ⏳ Not Started
- **Phase 9**: Security Audit - ⏳ Not Started
- **Phase 10**: Deployment - ⏳ Not Started
- **Phase 11**: Final Testing - ⏳ Not Started

---

**Next Step**: Start with Phase 1 - Create Laravel project and configure database

Good luck! 🚀
