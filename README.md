# KO Google Ads Purchase Conversion

## Overview
Sends purchase conversion data from WooCommerce to Google Ads.

## Key Features
- Focused, single-purpose WordPress plugin implementation
- Intended for real-world production workflows
- Lightweight repository layout suitable for review, reuse, and extension

## Requirements
- WordPress
- WooCommerce
- Google Ads

## Installation
1. Copy the plugin into /wp-content/plugins/
2. Activate it from the WordPress admin
3. Configure any plugin-specific settings after activation
4. Test in a staging environment before production rollout

## Usage
This repository is intended to provide a clean, reviewable plugin codebase. Exact usage depends on the active theme, plugins, and site-specific workflow where the plugin is deployed.

## Configuration
- Review plugin settings, filters, actions, and any environment-specific assumptions before deployment
- Keep API keys, account IDs, license keys, and secrets out of version control
- Supply the Google Ads conversion target through configuration, filters, or plugin settings.
- Keep live account IDs out of the repository.

## Extensibility
This plugin may be extended through normal WordPress customization patterns such as actions, filters, template integration, admin settings, or project-specific wrappers, depending on the implementation.

## Development Notes
- Public-safe repository version
- No live secrets should be stored in code
- Test with your active stack before production release

## License
GPL-2.0-or-later
