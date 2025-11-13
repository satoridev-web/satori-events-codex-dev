# Plugin Detection Report — Satori Events

- **Plugin file:** `satori-events/satori-events.php`
- **Plugin Name:** Satori Events
- **Text Domain:** `satori-events`
- **Version:** 1.0.0
- **Namespace Root:** `Satori\Events`
- **Primary bootstrap class:** `Satori\Events\Plugin`
- **Key constants:**
  - `SATORI_EVENTS_VERSION`
  - `SATORI_EVENTS_PLUGIN_FILE`
  - `SATORI_EVENTS_PLUGIN_DIR`
  - `SATORI_EVENTS_PLUGIN_URL`

The plugin autoloads all namespaced classes within `Satori\Events\*` and hooks into `plugins_loaded` to initialize the `Plugin` class, ensuring discoverability by WordPress scanners.
