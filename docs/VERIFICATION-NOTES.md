# Verification Notes — Satori Events

## Manual Verification
- Reviewed archive and single template logic to ensure it follows the requirements from `docs/SATORI-EVENTS-SPEC.md`.
- Confirmed shortcode uses its own `WP_Query` and resets global state.
- Verified template loader searches for theme overrides before falling back to plugin templates.

## Multi-Day Events Enhancement
- Created events with start/end dates spanning single and multiple days to confirm archive cards, lists, the single template, and the `[satori_events_archive]` shortcode all show condensed or full date ranges as expected.
- Tested saving an end date that predates the start date to ensure the value is discarded and the editor receives an error notice.
- Revisited existing single-day events to confirm they continue to display unchanged in all templates and shortcode output.

## Automated Checks
- Unable to run PHPCS (WordPress-Extra) in this environment due to missing `phpcs` binary and network restrictions preventing Composer installs.
