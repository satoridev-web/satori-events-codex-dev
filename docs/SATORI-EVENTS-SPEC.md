# Satori Events — Plugin Specification (R3P Master Spec, v1.0.0)

## 1. Overview

**Plugin Name:** Satori Events  
**Namespace Root:** `Satori\Events`  
**Text Domain:** `satori-events`  
**Minimum WordPress:** 6.4  
**Minimum PHP:** 7.4 (preferably 8.1+)  

### 1.1 Purpose

Satori Events is a lean, extensible Events plugin that provides:

- A custom post type for events
- Archive views (grid + list) with filters
- Single event templates
- An optional shortcode-driven archive for use on any page
- A solid, modern codebase that future SATORI add-ons can hook into

This v1.0.0 is intentionally focused and opinionated: it’s the **core events engine**, not a full calendars UI or recurring events system (those can be add-ons later).

### 1.2 Target Users

- Site owners who need a clean events listing
- Developers who want a hookable, well-structured events CPT
- Future SATORI Forms users who may publish events from frontend forms

---

## 2. Feature Scope — v1.0.0 (Locked)

### 2.1 Custom Post Type

- Register CPT `event`:
  - `public` = true
  - `has_archive` = true
  - `rewrite` slug = `events`
  - Supports: `title`, `editor`, `excerpt`, `thumbnail`, `revisions`
- Labels and menu icon set appropriately
- Menu position below “Pages” (for a sensible default)

### 2.2 Taxonomies

Two hierarchal taxonomies:

- `event_category`
  - Hierarchical
  - Used to classify type of event (e.g. Workshop, Seminar, Tournament)
- `event_location`
  - Hierarchical
  - Used to classify location/region (e.g. Melbourne, Sydney, Online)

Both should:

- Appear in the Events admin edit screen
- Show up in filters on the Events list table

### 2.3 Event Meta Fields

Use standard post meta + custom meta boxes (no hard dependency on ACF).  
Prefix: `_satori_events_`.

Meta keys:

- `_satori_events_date` — **required**. Stored as `Y-m-d` string.
- `_satori_events_time_start` — optional, `H:i`.
- `_satori_events_time_end` — optional, `H:i`.
- `_satori_events_venue` — venue name (string).
- `_satori_events_address` — address text (freeform string).
- `_satori_events_external_url` — optional external URL (Eventbrite etc.).
- `_satori_events_organizer_name` — optional.
- `_satori_events_organizer_email` — optional, validated as email.

Requirements:

- Meta box on the Event edit screen
- Proper sanitization/validation on save
- Nonces and capability checks

### 2.4 Frontend Archive

Archive at `/events/` must:

- Use a custom query ordering by `_satori_events_date` ascending, then title
- Only show upcoming events by default (today or in future)
- Be paginated using standard WordPress pagination

#### 2.4.1 Grid View

- 3-column grid on desktop, 2 on tablets, 1 on mobile
- Each card shows:
  - Featured image
  - Event title
  - Date ribbon at top (e.g. `14 Nov 2025`)
  - Excerpt (auto or manual)
  - Event categories + event locations
  - “Read More” button linking to single event
- Basic hover/interaction styling (CSS only)

#### 2.4.2 List View

- Stacked list layout
- Each row shows:
  - Date (prominent)
  - Title
  - Short excerpt
  - Location
  - “Read More” link or button

#### 2.4.3 View Toggle

- Archive shows a view toggle UI above the results:
  - Two buttons: “Grid View” and “List View”
- View selection controlled by query arg `view`:
  - `?view=grid` (default)
  - `?view=list`
- The active view button should appear visually selected

### 2.5 Filters & Search

At the top of the archive:

- Keyword search field (searches title and content)
- Dropdown for `event_category`
- Dropdown for `event_location`
- “Clear filters” control to reset archive to defaults

Behaviour:

- All filters submit using regular GET requests (no AJAX required)
- Use standard `s` param for keyword search
- Use query vars for taxonomies (e.g. `event_category`, `event_location`)
- Clean URLs are preferred but not mandatory in v1.0.0 (get it functionally correct)

### 2.6 Single Event Template

Single event view must:

- Display the event title
- Show a header/meta block including:
  - Date (and times if set)
  - Venue
  - Address
  - External URL (if set, open in new tab)
  - Categories / Locations
- Render main content from the WordPress editor
- Optionally show a “Back to Events” link to `/events/`

Layout goals:

- Simple, readable, aligned with archive styling
- Works with or without featured image
- Responsive, mobile-friendly

### 2.7 Shortcode Archive

Include a core shortcode:

- Tag: `[satori_events_archive]`

Attributes:

- `view` — `grid` or `list` (default `grid`)
- `category` — slug of `event_category`
- `location` — slug of `event_location`
- `posts_per_page` — integer, default same as archive

Behaviour:

- Renders the same card/list layouts as the archive
- Filters via shortcode attributes only (no UI bar in v1.0.0)
- Uses its own internal WP_Query; no global query tampering

---

## 3. Data Model Summary

### 3.1 Post Type

- `event`

### 3.2 Taxonomies

- `event_category` (hierarchical)
- `event_location` (hierarchical)

### 3.3 Meta Keys

- `_satori_events_date` (Y-m-d)
- `_satori_events_time_start` (H:i)
- `_satori_events_time_end` (H:i)
- `_satori_events_venue`
- `_satori_events_address`
- `_satori_events_external_url`
- `_satori_events_organizer_name`
- `_satori_events_organizer_email`

---

## 4. Architecture & Folder Structure

Target structure (Codex may refine names, but should follow this layout):

- `satori-events.php` (main plugin file)
- `includes/`
  - `autoloader.php` (simple PSR-4-like autoloader for `Satori\Events\*`)
  - `class-plugin.php` (core bootstrap)
  - `post-types/class-event-post-type.php`
  - `taxonomies/class-event-taxonomies.php`
  - `meta/class-event-meta.php`
  - `templates/class-template-loader.php`
  - `frontend/class-archive-controller.php`
  - `frontend/class-single-controller.php`
  - `shortcodes/class-archive-shortcode.php`
- `templates/`
  - `archive-event.php`
  - `single-event.php`
  - `parts/archive-filters.php`
  - `parts/archive-view-toggle.php`
  - `parts/content-event-card.php`
  - `parts/content-event-list.php`
- `assets/`
  - `css/satori-events.css`
  - `js/satori-events-archive.js` (only if really needed; keep JS minimal)
- `languages/` (placeholder for future translations)
- `docs/` (this spec + other docs)

Template loading:

- Template loader should check for theme overrides under:
  - `wp-content/themes/<theme>/satori-events/`
- Fallback to plugin `templates/` if no override exists

---

## 5. Hooks & Extension Points

Define these actions:

- `do_action( 'satori_events_before_archive' )`
- `do_action( 'satori_events_after_archive' )`
- `do_action( 'satori_events_before_single' )`
- `do_action( 'satori_events_after_single' )`

Define these filters:

- `satori_events_archive_query_args` — filter archive WP_Query args
- `satori_events_shortcode_query_args` — filter shortcode query args
- `satori_events_single_meta_output` — filter rendered meta array/block

---

## 6. Coding Standards & Requirements

- All PHP code under namespace `Satori\Events`.
- Use a simple autoloader (no Composer dependency).
- Comply with:
  - WordPress-Extra standards via PHPCS.
  - SATORI internal rules where available (no BOM, UTF-8, LF endings).
- Escape all output using appropriate esc_* functions.
- Sanitize all input, especially `$_GET` from filters and shortcode attributes.
- Use nonces and capability checks in admin/meta save callbacks.
- No fatal errors if PHP version is too low; fail gracefully with admin notice.

---

## 7. Acceptance Criteria

The implementation is considered **v1.0.0 ready** when:

1. Activating the plugin registers the `event` CPT and both taxonomies.
2. Visiting `/events/` displays the grid archive with:
   - Correct ordering by event date.
   - Only today/future events shown.
   - Pagination working.
3. Switching to `?view=list` changes the layout to the list view.
4. Filters (keyword, category, location) adjust the archive query as expected.
5. Single event pages display all configured meta correctly.
6. `[satori_events_archive]` shortcode renders a working archive matching the layouts.
7. PHPCS passes with no errors (warnings only if justified).
8. Template override system works:
   - Copying `archive-event.php` into the theme at `satori-events/archive-event.php` replaces the plugin’s version.
9. A ZIP suitable for installation exists in `/build/` (Codex may create this in a later step).
