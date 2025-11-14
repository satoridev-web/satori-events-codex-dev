# Satori Events — Enhancement Spec: Multi-Day Events

## 1. Overview

This enhancement adds support for multi-day events by introducing an optional **end date** field and updating display logic to show date ranges where applicable.

Current plugin: implemented per docs/SATORI-EVENTS-SPEC.md.  
This spec describes **delta changes** only.

## 2. Scope

### 2.1 Goals

- Add an optional event end date field.
- Display date ranges in archive and single templates when end date is present and > start date.
- Keep ordering and “upcoming events” logic based on the start date.
- Ensure backwards compatibility: existing events without an end date continue to work.

### 2.2 Non-Goals

- No full recurring events logic in this enhancement.
- No changes to shortcode attributes (for now).
- No date-range filtering in the archive (future enhancement).

---

## 3. Data Model Changes

### 3.1 New Meta Field

Add new meta key:

- `_satori_events_date_end`
  - Format: `Y-m-d`
  - Optional
  - Valid only if:
    - It parses as a valid date; and
    - It is >= `_satori_events_date` (start date). If not, ignore or clear it.

## 4. Admin UI Changes

### 4.1 Event Meta Box

Modify the existing event meta box (in includes/meta/class-event-meta.php):

- Add an **End date** field below the existing **Date** field.
- Use the same date input style/format as the start date.
- Validation:
  - If end date is empty → treat as single-day event.
  - If end date < start date → show admin notice on save and clear / ignore invalid value.

## 5. Frontend Behaviour

### 5.1 Display Logic

Update archive and single templates to show:

- If no end date:
  - Display as before: `14 Nov 2025`
- If end date is set and > start date:
  - Display `14–16 Nov 2025`
  - For different months/years, show full clarity, e.g. `30 Dec 2025 – 02 Jan 2026`.

Templates affected:

- `templates/parts/content-event-card.php`
- `templates/parts/content-event-list.php`
- `templates/single-event.php` (wherever the date is rendered)

Use a small internal helper (method or function) if helpful to keep date formatting consistent.

### 5.2 Archive Ordering & Filtering

- Keep ordering logic based on `_satori_events_date` (start date).
- “Upcoming events only” rule remains based on start date vs. today.

## 6. Shortcode Behaviour

- `[satori_events_archive]` continues to work, now with date range display matching the archive templates.
- No new shortcode attributes added for this enhancement.

---

## 7. Acceptance Criteria

1. Existing events (with only a start date) continue to display exactly as before.
2. New events can be given an end date in the Event meta box.
3. If end date is before the start date, the plugin prevents saving or clears the value (no invalid ranges).
4. Archive cards and list items show date ranges when end date is valid.
5. Single event view shows the same range format.
6. Ordering (start date ascending) remains unchanged.
7. “Upcoming events” behaviour remains set by start date, not end date.
8. PHPCS still passes with no new errors.
