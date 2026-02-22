# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is **QuizShift** - a CodeIgniter 4 application for Arabic language grammar learning using the LCM algorithm. It's a web-based quiz and learning platform.

- **Framework**: CodeIgniter 4 (PHP 8.2+ required)
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
    'hak_akses'     => 'ADMIN'|'GURU',  // role
    'foto_profil'   => string|null,
    'logged_in'     => bool
]);
```

Additional session vars for compatibility:
- `user_role`, `user_id`, `user_name`

**Roles**: `ADMIN` (Administrator), `GURU` (Teacher/Instructor)

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
- Role field: `hak_akses` ENUM('ADMIN', 'GURU')

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
