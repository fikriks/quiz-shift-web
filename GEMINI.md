# GEMINI.md

This file provides guidance when working with code in this repository.

## Project Overview

This is **QuizShift** - a CodeIgniter 4 application for English grammar learning using the Fisher-Yates shuffle algorithm. It's a web-based quiz and learning platform.

- **Framework**: CodeIgniter 4 (PHP 8.2+ required)
- **UI Template**: Mantis Dashboard Template (Bootstrap 5)
- **CSS Framework**: Bootstrap 5.x
- **Database**: MySQL (quiz_shift)
- **Base URL**: `http://localhost:8080/`

## Development Commands

### Database Migrations
```bash
# Run all pending migrations
php spark migrate

# Rollback last migration
php spark migrate:rollback

# Refresh all migrations
php spark migrate:refresh
```

### Database Seeders
```bash
# Run all seeders
php spark db:seed

# Run specific seeder
php spark db:seed LevelSeeder
php spark db:seed SoalSeeder
php spark db:seed PesertaSeeder
```

**Available Seeders:**
- `PenggunaSeeder` - Creates admin (admin123/123456789) and instruktur (instruktur123/123456789)
- `LevelSeeder` - Creates 3 levels: BEGINNER (0-59), INTERMEDIATE (60-79), ADVANCED (80-100)
- `SoalSeeder` - Creates 24 sample English grammar questions (8 per level)
- `PesertaSeeder` - Creates 5 sample participants (peserta1-5/password123)

### Testing
```bash
# Run tests
composer test
# or
phpunit
```

### Development Server
```bash
# Start built-in PHP server (uses public/ as document root)
php spark serve
```

## Architecture

### BaseController Pattern

All controllers extend `BaseController` which provides:
- **Session management**: `$this->session` (auto-initialized)
- **Current user**: `$this->currentUser` (populated from session 'user' key)
- **View data array**: `$this->data` (passed to all views)
- **Authentication helpers**: `requireAuth()`, `requireRole($role)`, `requireAnyRole($roles)`
- **JSON response helpers**: `jsonSuccess()`, `jsonError()`, `jsonResponse()`
- **File upload helper**: `uploadFile()`
- **Pagination helper**: `paginate()`

The `shareDataToViews()` method automatically shares:
- `$currentUser` - Current logged-in user data
- `$userPhoto` - Path to user's profile photo
- `$siteName`, `$siteDescription` - Site config
- `$notifications` - User notifications

### Authentication System

**Session Structure** (set in `AuthController::attemptLogin()`):
```php
$this->session->set('user', [
    'id_pengguna'   => int,
    'nama_pengguna' => string,  // username
    'nama_lengkap'  => string,  // full name
    'hak_akses'     => 'ADMIN'|'INSTRUKTUR',  // role
    'foto_profil'   => string|null,
    'logged_in'     => bool
]);
```

Additional session vars for compatibility:
- `user_role`, `user_id`, `user_name`

**Roles**: `ADMIN` (Administrator), `INSTRUKTUR` (Instructor)

**User Status**: `AKTIF` (active), `NONAKTIF` (inactive)

### View Structure

Views use CodeIgniter's template engine with sections:

**Layout**: `layouts/app.php`
- Renders sections: `title`, `content`, `scripts`
- Includes flash messages via `partials/flash_messages.php`
- Uses Notyf toast notifications for feedback

**Extending a layout**:
```php
<?= $this->extend('layouts/app') ?>
<?= $this->section('title') ?>Page Title<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- content here -->
<?= $this->endSection() ?>
```

### Database Conventions

**Migration Naming**: `YYYY-MM-DD-HHMMSS_TableNameMigration.php`

**Table Conventions** (Indonesian language):
- `pengguna` - users table
- Primary keys: `id_*` (e.g., `id_pengguna`)
- Timestamps: `waktu_dibuat` (created_at), `waktu_diubah` (updated_at)
- Status fields: ENUM('AKTIF', 'NONAKTIF')
- Role field: `hak_akses` ENUM('ADMIN', 'INSTRUKTUR')

### Model Conventions

Models should:
- Extend `CodeIgniter\Model`
- Define `$table`, `$primaryKey`, `$allowedFields`
- Use callbacks for password hashing and timestamps
- Include validation rules with Indonesian error messages

**Example**: `PenggunaModel`
- Auto-hashes passwords via `beforeInsert`/`beforeUpdate` callbacks
- Includes `authenticate()` method for login
- Includes `getActiveUsers($hak_akses = null)` helper

### Route Organization

Routes are defined in `app/Config/Routes.php`. Current structure:
- Auth routes: `/login`, `/logout`
- Dashboard: `/dashboard`
- Level: `/level`, `/level/create`, `/level/edit/:num`, `/level/delete/:num`
- Soal: `/soal`, `/soal/create`, `/soal/edit/:num`, `/soal/delete/:num`
- Peserta: `/peserta`, `/peserta/create`, `/peserta/edit/:num`, `/peserta/delete/:num`, `/peserta/reset-token/:num`, `/peserta/token/:num`
- Pengguna: `/pengguna`, `/pengguna/create`, `/pengguna/edit/:num`, `/pengguna/delete/:num`
- Hasil: `/hasil`, `/hasil/:num`, `/hasil/delete/:num`, `/hasil/export/:num`
- API:
  - Auth: `/api/auth/login`, `/api/auth/logout`, `/api/auth/register`, `/api/auth/me`
  - Soal: `/api/soal`, `/api/soal/levels`, `/api/soal/random`, `/api/soal/:num`
  - Kuis: `/api/kuis/start`, `/api/kuis/submit`, `/api/kuis/finish`, `/api/kuis/active`, `/api/kuis/cancel`
  - Hasil: `/api/hasil`, `/api/hasil/latest`, `/api/hasil/statistics`, `/api/hasil/:num`

**Note**: All delete routes use POST method for security.

### Flash Messages

Flash messages use Notyf toast notifications:
- `session('success')` - Success toast
- `session('error')` - Error toast
- `session('errors')` - Validation errors array
- `session('info')`, `session('warning')` - Info/warning toasts

## Environment Configuration

Database settings in `.env`:
```
database.default.hostname = localhost
database.default.database = quiz_shift
database.default.username = root
database.default.password = fikrikhairul
database.default.DBDriver = MySQLi
```

## Asset Paths

- Assets: `public/assets/`
- Images: `public/assets/images/`
- CSS: `public/assets/css/`
- JS: `public/assets/js/`
- Fonts: `public/assets/fonts/`
- User uploads: `writable/uploads/profile/`

## Helpers (Auto-loaded)

- `url` - `base_url()`, `site_url()`
- `form` - `form_open()`, `form_close()`, etc.
- `html` - HTML helpers
- `text` - Text manipulation helpers

## UI Components

### Bootstrap 5 Utility
Always use Bootstrap 5 utility classes. For badges, use `bg-*` instead of `badge-*` (e.g., `<span class="badge bg-primary">`).

### Level Badges
Levels use color-coded badges for visual distinction:
- **BEGINNER**: Blue (`bg-primary`)
- **INTERMEDIATE**: Orange/Yellow (`bg-warning`)
- **ADVANCED**: Red (`bg-danger`)
