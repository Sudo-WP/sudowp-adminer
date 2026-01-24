=== SudoWP Adminer (Security Fork) ===
Contributors: SudoWP, WP Republic
Original Authors: Frank Bültge, Inpsyde
Tags: adminer, database, mysql, security-fork, ssrf-fix, cve-2021-21311
Requires at least: 5.8
Tested up to: 6.7
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A secure, zero-trust database management tool. Fixes critical SSRF vulnerabilities by enforcing local connections only.

== Description ==

This is SudoWP Adminer, a security-hardened fork of the popular "Adminer" WordPress plugin.

**Why this fork?**
The original plugin allowed users to connect to *any* database server via the login form. This created a critical Server-Side Request Forgery (SSRF) vulnerability (CVE-2021-21311), allowing attackers to scan internal networks. Additionally, the original plugin file was often accessible directly by bots.

**The SudoWP "Zero Trust" Approach:**
This fork converts Adminer from a general-purpose tool into a strictly locked-down WordPress utility.

**Security Patches & Features:**
* **SSRF Protection (CVE-2021-21311):** We have removed the ability to enter a database server address. The plugin creates a forced, secure connection *only* to the local WordPress database (`DB_HOST`).
* **Auto-Login:** No credentials required. Since you are already authenticated as a WordPress Admin, the plugin logs you into the database automatically.
* **Access Control:** Direct access to the PHP files is blocked. The interface loads only for users with `manage_options` capabilities.
* **Invisible Mode:** There is no public login URL. Bots cannot brute-force the database password because there is no form to attack.

== Installation ==

1. Upload the `sudowp-adminer` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the "SudoWP Adminer" menu in the sidebar.
4. You will be instantly connected to your database.

== Frequently Asked Questions ==

= Can I connect to a remote database with this? =
No. That functionality is disabled by design to prevent SSRF attacks. This tool is strictly for managing the local WordPress database.

= Where is the login screen? =
It has been removed for security. The plugin uses your WordPress session to authenticate you automatically.

= Is this compatible with the original Adminer plugin? =
Yes, but you should delete the original plugin to ensure the vulnerability is removed from your server.

== Changelog ==

= 1.5.0 (SudoWP Edition) =
* Security Fix: patched SSRF Vulnerability (CVE-2021-21311) by forcing local connection.
* Security Fix: Removed public login form.
* Security Fix: Blocked direct access to PHP files.
* Feature: Added auto-login mechanism using WP credentials.
* Rebrand: Forked as SudoWP Adminer.