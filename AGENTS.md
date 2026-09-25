# Zevedu Academy AI Agent Rules

You are an AI assistant helping develop Zevedu Academy, a CodeIgniter 4 + Tailwind CSS e-learning platform.

## Project Context

- Backend: CodeIgniter 4.7+
- PHP: 8.2+
- Frontend: Tailwind CSS v4 + DaisyUI
- Database: MySQL / MariaDB
- App style: Server-side rendered monolith, not SPA or REST-first service
- Main entry: `app/Config/Routes.php`
- Frontend CSS source: `public/assets/css/input.css`
- Build output: `public/assets/css/output.css`
- Runtime files: `writable/`

## Core Architectural Rules

### 1. Follow the project architecture strictly
- Use CodeIgniter 4 conventions.
- Do not assume this is a React/Vue/SPA app.
- Keep the app server-rendered and route-driven.
- Respect the existing folder structure.
- Prefer existing patterns over inventing new ones.

### 2. Controller pattern
- Controllers live in `app/Controllers/` and `app/Controllers/Admin/`.
- Extend `BaseController`.
- Keep controllers thin.
- Do not put business logic directly in controllers unless it is trivial.
- Use services for business logic when it becomes complex.

### 3. Model pattern
- Models live in `app/Models/`.
- Extend `CodeIgniter\Model`.
- Use `$table`, `$primaryKey`, `$allowedFields`, and validation rules.
- Use database models for DB access and query logic.
- Never write raw SQL unless strictly necessary and already consistent with existing project style.

### 4. Service pattern
- Put business logic in `app/Services/`.
- Use service classes for payment flow, certificate generation, queue logic, or complex reuse.
- Keep controllers focused on request handling and response rendering.

### 5. View pattern
- Views live under `app/Views/`.
- Use plain PHP + HTML templates.
- Reuse existing layouts and partials where possible.
- Keep page structure consistent with the project.

## Routing and Access Rules

### Route conventions
- Define routes in `app/Config/Routes.php`.
- Do not enable arbitrary auto-routing unless already used in the project.
- Keep route naming consistent with current patterns.
- Use filters for authentication and authorization when relevant.

### Auth and role conventions
- Session-based authentication is used.
- Use session values such as:
  - `isLoggedIn`
  - `role`
  - `user_id`
- Do not bypass session checks.
- Respect existing role checks.
- For admin-only flows, enforce role filters or role validation in the controller.

### Forbidden behavior
- Do not create admin endpoints without role checks.
- Do not expose privileged data to unauthorized users.
- Do not bypass session-based access control.

## Security Rules

### Input validation
- Validate all incoming request data.
- Never trust raw request input.
- Use the project’s validation system and existing model validation rules.

### CSRF protection
- Treat POST/PUT/DELETE forms as protected by CSRF unless the project explicitly uses a different pattern.
- Prefer forms using CodeIgniter helpers that include CSRF tokens.

### Password handling
- Never store plaintext passwords.
- Use `password_hash(..., PASSWORD_BCRYPT)` when creating users.
- Use `password_verify()` for validation.
- Do not introduce weak hashing or custom password schemes.

### XSS prevention
- Escape output in views.
- Prefer `esc()` or standard CodeIgniter escaping helpers.
- Avoid raw output of user-controlled string values.

### Payment and webhook security
- Midtrans flows must be server-validated.
- Validate signature and status before processing results.
- Never trust browser-side payment status completely.
- Never log or expose sensitive payment credentials.

### File upload security
- Validate MIME type and file size.
- Store uploads in safe writable locations.
- Sanitize filenames.
- Prevent executable files from being uploaded.

## Database and Migration Rules

### Schema changes
- Every database schema change must use a new migration file.
- Do not edit old migrations after they have been used in the project.
- Put migration files in `app/Database/Migrations/`.
- Use consistent migration naming.

### Model convention
- Keep database access in models.
- Do not scatter SQL queries across controllers.
- Reuse common model patterns already in the codebase.

### Relationship awareness
- Respect existing table relationships and model naming conventions.
- Use the project’s established naming patterns for foreign keys and tables.

## Tailwind CSS / Design System Rules

### Styling conventions
- Use the project’s existing Tailwind setup in `public/assets/css/input.css`.
- Do not edit `public/assets/css/output.css` manually.
- Build CSS from the source file using the project’s build command.
- Prefer semantic tokens from the design system over arbitrary values.

### Design system rules
- Use the established design tokens.
- Prefer utility classes that match the existing theme.
- Do not invent new colors or spacing conventions unless the project already does so.
- Avoid arbitrary values like `p-[13px]`, `bg-[#333]`, or `gap-[7px]` when a design token exists.
- Favor DaisyUI-compatible classes when they already fit the current app style.

### Layout and reuse
- Reuse existing layouts and component patterns before creating new ones.
- Preserve consistency with existing pages.
- Keep mobile-first and responsive patterns consistent with the app.

## Testing Rules

- Add or update tests for meaningful changes.
- Prefer feature tests for routes and controller behavior.
- Keep tests consistent with the project’s existing PHPUnit testing style.
- Validate behavior before finishing a task.

## Development Workflow

### Before coding
- Read the relevant files first.
- Check existing patterns in routes, controllers, models, services, and views.
- Prefer minimal, consistent changes.

### After coding
- Review the diff.
- Check for unintended side effects.
- Validate syntax and behavior.
- Run project checks relevant to the change.

## Project Check Commands

Use the project’s commands and existing local workflow. Typical checks include:

```bash
php spark test
php spark route:list
php spark migrate
php -l app/Controllers/*.php
php -l app/Models/*.php
php -l app/Services/*.php
npm run build
```

## Forbidden / Unsafe Actions

Do not do any of the following unless explicitly required and safe:

- Do not edit `vendor/` or generated dependency directories.
- Do not edit `public/assets/css/output.css` by hand.
- Do not commit `.env` or any secrets.
- Do not touch unrelated modules or files.
- Do not rewrite architecture or framework conventions unnecessarily.
- Do not add hidden backdoors, insecure bypasses, or weak auth logic.
- Do not create unrelated migrations or schema changes.
- Do not bypass validation or security checks.

## Definition of Done

A task is complete only when:

- The requested feature or fix works as intended.
- The code follows the project’s architectural patterns.
- Existing route/controller conventions are preserved.
- Security checks remain intact.
- Validation is present where required.
- Tailwind and view patterns stay consistent with the project.
- Relevant tests are updated or added.
- The project’s validation commands pass.
- The diff is reviewed and contains only intentional changes.

## Important Note

This project is not a generic REST API or generic SPA. It is a CodeIgniter monolith with server-rendered pages and Tailwind-based views. Respect that structure and keep changes aligned with the current application architecture.

---

This file is the main project contract for AI-assisted development in Zevedu Academy.
