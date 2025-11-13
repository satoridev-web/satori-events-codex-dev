# Verification Notes — Satori Events

## Manual Verification
- Reviewed archive and single template logic to ensure it follows the requirements from `docs/SATORI-EVENTS-SPEC.md`.
- Confirmed shortcode uses its own `WP_Query` and resets global state.
- Verified template loader searches for theme overrides before falling back to plugin templates.

## Automated Checks
- Unable to run PHPCS (WordPress-Extra) in this environment due to missing `phpcs` binary and network restrictions preventing Composer installs.
