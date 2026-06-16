# FINKI Admin Request Portal - Complete Documentation

**Project:** Web-based system for submitting, managing, and issuing administrative documents at FINKI (Faculty of Informatics and Computer Engineering), Ss. Cyril and Methodius University in Skopje.

**Version:** 1.0 (June 2026)  
**Author:** Serenity (Student Project)

---

## 1. Project Overview

This is a full-stack Laravel application that digitizes the administrative document request process at FINKI. 

**Core Goals:**
- Allow students to easily request official documents (potvrda za redoven student, uverenija, etc.)
- Provide **AI-powered validation** in Macedonian language before submission
- Give administrators powerful tools to review, approve/reject, and issue official documents
- Maintain clean audit trail and document storage

The system has two distinct user roles with completely different interfaces and capabilities.

---

## 2. Key Features

### For Students
- Browse available document types
- Submit new requests with description + optional attachment (PDF/images up to 7MB)
- Get **real-time AI feedback** in Macedonian (structured analysis + improvement suggestions)
- Edit pending/processing requests
- Track status of all their requests (pending → processing → approved/rejected)
- View issued documents when approved

### For Administrators
- **Dashboard** with live statistics (total, pending, approved, today’s requests)
- Full request management with advanced filters (search, type, status)
- Approve / Reject / Change status + add internal notes
- Upload official issued PDF documents
- View and manage all students + their request history
- Configure available request types (add/remove services)

### AI System (Unique Feature)
- Powered by local **Ollama** (`qwen2.5:7b`)
- Strict prompt engineering that forces **structured Macedonian output**
- Uses a fixed Markdown template so feedback is always consistent and professional
- Helps students improve weak descriptions before they submit

---

## 3. Technology Stack

| Layer              | Technology                          |
|--------------------|-------------------------------------|
| Backend            | Laravel 11 (PHP 8.2+)              |
| Frontend           | Blade + Tailwind CSS + Alpine.js   |
| Database           | SQLite (default) / MySQL / PostgreSQL |
| AI / LLM           | Ollama (qwen2.5:7b)                |
| File Storage       | Laravel Storage (public disk)      |
| Authentication     | Laravel Breeze (or Sanctum)        |
| Other              | Carbon, Laravel HTTP Client        |

---

## 4. Project Structure (Key Files)

```
app/
├── Actions/
│   └── AskAiAgentAction.php          # Core AI logic (Ollama HTTP call)
├── Http/Controllers/
│   ├── Admin/
│   │   ├── AdminDashboardController.php
│   │   ├── AdminStudentController.php
│   │   └── RequestController.php     # Full request lifecycle + document upload
│   └── Student/
│       ├── AdministrativeRequestController.php
│       └── AIAnalysisController.php  # AJAX endpoint for AI feedback
├── Models/
│   ├── AdministrativeRequest.php
│   ├── RequestType.php
│   └── User.php (extended)
database/
├── migrations/
└── seeders/ (RequestTypeSeeder recommended)
resources/
└── views/
    ├── admin/          (dashboard, requests, students, service_config)
    ├── student/        (requests index/create/edit/show)
    └── layouts/app.blade.php
routes/web.php
```

---

## 5. Installation & Setup

### 5.1 Requirements
- PHP 8.2+
- Composer
- Node.js + npm (for Vite/Tailwind)
- Ollama running locally with `qwen2.5:7b` (or any compatible model)
- SQLite or MySQL database

### 5.2 Steps

```bash
git clone <your-repo>
cd admin-request-portal

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate
```

### 5.3 Environment Variables (important)

```env
DB_CONNECTION=sqlite
# or mysql + credentials

OLLAMA_URL=http://127.0.0.1:11434          # Ollama server
OLLAMA_MODEL=qwen2.5:7b

# File uploads
FILESYSTEM_DISK=public
```

### 5.4 Database & Seeders

```bash
php artisan migrate

# Recommended: Create a seeder for request types
php artisan make:seeder RequestTypeSeeder
# Add common FINKI document types (Potvrda za redoven student, Uverenje za polozeni ispiti, etc.)
php artisan db:seed --class=RequestTypeSeeder
```

### 5.5 Start Ollama

```bash
ollama serve
ollama pull qwen2.5:7b
```

### 5.6 Run the Application

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000`

---

## 6. Database Schema (Core Tables)

### `users`
- Standard Laravel + `is_admin` boolean

### `request_types`
- `id`, `name`, `description` (optional), `requires_fields` (JSON - future use)

### `administrative_requests`
| Column                | Type          | Description                              |
|-----------------------|---------------|------------------------------------------|
| user_id               | foreignId     | Student who submitted                    |
| request_type_id       | foreignId     | Type of document requested               |
| description           | text          | Student's description                    |
| student_attachment    | string        | Path to uploaded file (nullable)         |
| status                | string        | pending / processing / approved / rejected |
| ai_suggestion         | text          | AI feedback (if used)                    |
| ai_feedback           | text          | Additional AI notes                      |
| admin_note            | text          | Internal admin notes                     |
| issued_document       | string        | Path to final PDF issued by admin        |
| created_at / updated_at | timestamps  | -                                        |

---

## 7. User Roles & Permissions

| Feature                    | Student          | Administrator     |
|---------------------------|------------------|-------------------|
| Submit new request        | Yes              | Yes (on behalf of student) |
| Edit own pending request  | Yes              | Yes               |
| View own requests         | Yes              | All requests      |
| AI feedback               | Yes              | N/A               |
| Approve / Reject          | No               | Yes               |
| Upload official document  | No               | Yes               |
| Manage students           | No               | Yes               |
| Configure request types   | No               | Yes               |
| Dashboard stats           | No               | Yes               |

---

## 8. Core Workflows

### Student Flow
1. Student logs in → sees Welcome page with all available document types
2. Clicks "Поднеси Барање" on desired type
3. Fills description + optional attachment
4. Clicks **AI Проверка** → gets structured Macedonian feedback
5. Improves description if needed → Submits
6. Tracks status in "Мои Барања"

### Admin Flow
1. Admin logs in → Dashboard with KPIs
2. Goes to "Сите Барања" → filters by status/type/student
3. Opens a request → reviews description + attachment
4. Changes status + writes internal note
5. When approved → uploads final PDF (`issued_document`)
6. Student can then download it from their side

---

## 9. AI System Deep Dive (`AskAiAgentAction`)

**Location:** `app/Actions/AskAiAgentAction.php`

**How it works:**
- Receives `description` + `requestTypeName`
- Builds a very strict prompt in Macedonian
- Sends to Ollama via direct HTTP (`/api/generate`)
- Forces output in a fixed Markdown template:
  - 🔍 Оценка на барањето
  - 🛠️ Што недостига / Треба да се смени
  - 💡 Конкретен предлог-текст

**Why this design?**
- Prevents generic/chatty answers
- Guarantees consistent, professional tone
- Students get actionable advice, not just "looks good"

**Current Model:** `qwen2.5:7b` (good balance of speed/quality for Macedonian)

**Fallback:** If Ollama is down or rate-limited → user sees friendly error message.

---

## 10. Important Routes Summary

| Method | URI                              | Controller + Action          | Role     |
|--------|----------------------------------|------------------------------|----------|
| GET    | `/`                              | welcome                      | Public   |
| GET    | `/student/requests`              | Student index                | Student  |
| GET    | `/student/requests/create`       | Student create form          | Student  |
| POST   | `/student/requests`              | Student store                | Student  |
| POST   | `/ai/check`                      | AIAnalysisController         | Student  |
| GET    | `/admin/dashboard`               | AdminDashboardController     | Admin    |
| GET    | `/admin/requests`                | RequestController@index      | Admin    |
| PUT    | `/admin/requests/{id}/status`    | updateStatus (with file)     | Admin    |
| GET    | `/admin/students`                | AdminStudentController       | Admin    |
| GET    | `/admin/service-config`          | typesIndex + CRUD            | Admin    |

---

## 11. Views & Design System

Current design uses:
- Dark theme (`#0b0f17`, `#161b22`)
- Retro + modern hybrid (VT323 font in some places + clean sans)
- Strong use of cards, status pills, and hover effects
- Responsive (Tailwind)

Two navigation styles exist in the codebase:
1. Classic dark retro navbar
2. Floating modern navbar (recommended for production)

---

## 12. Known Issues / Technical Debt

- AI prompt can still produce repetitive output on some models (mitigated with `temperature: 0.3`)
- File upload size limit is currently 7MB (good for students)
- No email notifications yet (can be added with Laravel Notifications + queues)
- `requires_fields` JSON column exists but not fully used yet (future dynamic forms)
- Some controllers have debug `dd()` calls that should be removed in production

---

## 13. Recommended Future Improvements

1. **Email notifications** when status changes
2. **Student dashboard** with statistics (how many approved this year, etc.)
3. **Dynamic form fields** per RequestType (using `requires_fields`)
4. **Better AI prompt** or fine-tuned model for FINKI-specific language
5. **Public statistics page** (how many documents issued this month)
6. **Mobile-first** improvements + PWA support
7. **Audit log** for every status change

---

## 14. Quick Start for New Developers

```bash
# After cloning + composer install
php artisan migrate --seed
php artisan serve
ollama serve
# In another terminal:
ollama run qwen2.5:7b
```

Then visit `http://127.0.0.1:8000` and register a student account.

---
