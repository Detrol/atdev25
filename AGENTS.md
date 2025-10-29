# Repository Guidelines

## Project Structure & Module Organization
- Laravel domain logic lives in `app/`; group Livewire Volt components under `app/Http/Livewire` and keep services in feature-focused subfolders.
- Blade and Volt templates reside in `resources/views`; front-end assets (Tailwind entrypoints, shared JS) are under `resources/js` and `resources/css` with `public/` serving the compiled output.
- Routes are split by transport in `routes/web.php`, `routes/api.php`, and `routes/auth.php`; migrations, factories, and seeders are in `database/`.
- Automated checks run against `tests/` (Pest). Feature tests sit in `tests/Feature`, while low-level utilities belong in `tests/Unit`.

## Build, Test, and Development Commands
- `composer install` and `npm install` bootstrap PHP and Node dependencies; rerun when `composer.json` or `package.json` change.
- `composer dev` starts the full stack (Laravel server, queue worker, pail log viewer, Vite dev server) with concurrent output.
- `npm run dev` runs Vite in watch mode, while `npm run build` produces production assets into `public/build`.
- `composer test` (or `php artisan test`) clears config cache and executes the Pest suite; use `php artisan migrate --env=testing` before first run when tests rely on the database.

## Coding Style & Naming Conventions
- Follow PSR-12 for PHP; format via `./vendor/bin/pint` before committing. Stick to 4-space indentation and snake_case config keys.
- Name Livewire Volt components with a feature prefix (e.g., `Account/UpdateProfile.php`) and pair them with matching Blade templates.
- Use PascalCase for PHP classes, camelCase for methods and JavaScript, and kebab-case for Blade partial filenames.
- Keep Tailwind utility layers organized by role in `resources/css/app.css`, avoiding inline `<style>` blocks.

## Testing Guidelines
- Prefer Pest for expressiveness; start files with `uses(Tests\TestCase::class)` and name them `<FeatureName>Test.php`.
- Target high-value scenarios: request lifecycles, authorization, and Livewire interactions using Pest's `livewire()` helper.
- Mock external integrations sparingly; rely on Laravel's `Http::fake()` and database factories for deterministic data.
- Add regression tests for every bugfix, and ensure suites pass locally before opening a PR.

## Commit & Pull Request Guidelines
- Use short imperative commit subjects (`fix: handle empty profile`) followed by context in the body when needed.
- Squash noisy WIP commits; each commit should leave the app in a working state and include relevant migrations or assets.
- Pull requests must describe the change, list test commands run, and link tracking tickets. Attach UI screenshots or screencasts when behaviour shifts.
- Request review only after resolving lint warnings, updating docs, and confirming `composer test` and `npm run build` succeed.
