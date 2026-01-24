# SudoWP Adminer (Security Fork)

**Contributors:** SudoWP, WP Republic  
**Original Authors:** Frank Bültge, Inpsyde  
**Tags:** adminer, database, security, cve-2021-21311, ssrf-patch  
**Requires at least:** 5.8  
**Tested up to:** 6.7  
**Stable tag:** 1.5.0  
**License:** GPLv2 or later  

## Security Notice (CVE-2021-21311)
This is a **security-hardened fork** of the "Adminer" WordPress plugin.

**The Threat:**
Standard Adminer installations often allow **Server-Side Request Forgery (SSRF)**. Attackers can use the login form to force the server to connect to internal services (e.g., AWS Metadata, internal APIs), leading to information disclosure.

**The Solution:**
SudoWP Adminer enforces a **"Localhost Only"** policy. It hardcodes the connection to the WordPress database defined in `wp-config.php`, completely mitigating the SSRF vector.

---

## Description

**SudoWP Adminer** provides a powerful database management interface (similar to phpMyAdmin) but secured specifically for WordPress environments.

### Security Hardening Features

1.  **SSRF Prevention:**
    * The "Server", "Username", and "Password" fields are removed.
    * Connection is strictly limited to the local `DB_HOST`, `DB_USER`, and `DB_PASSWORD`.

2.  **Access Control:**
    * **Admins Only:** The tool strictly checks for `current_user_can('manage_options')` before loading.
    * **Direct Access Block:** Attempting to access the `.php` files directly via the browser returns a 403 Forbidden error.

3.  **Auto-Login:**
    * Authentication is handled via the WordPress session. If you are logged into WP Admin, you are logged into the database.

## Installation

1.  Download the repository.
2.  **Important:** Deactivate and delete any existing Adminer plugins.
3.  Upload the `sudowp-adminer` folder to your `/wp-content/plugins/` directory.
4.  Activate the plugin.
5.  Access via the **SudoWP Adminer** menu item.

## Changelog

### Version 1.5.0 (SudoWP Edition)
* **Security Fix:** Patched Critical SSRF Vulnerability (CVE-2021-21311).
* **Security Fix:** Implemented "Zero Trust" access control (Admins Only).
* **UX:** Added seamless auto-login.
* **Rebrand:** Forked as SudoWP Adminer.

---
*Maintained by the SudoWP Security Project.*
