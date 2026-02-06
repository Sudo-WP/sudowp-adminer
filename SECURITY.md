# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.5.x   | :white_check_mark: |

## Security Considerations

### Current Security Status

This plugin is a security-hardened fork specifically designed to mitigate **CVE-2021-21311 (SSRF vulnerability)**. The following security measures are in place:

#### ✅ Mitigated Vulnerabilities

1. **CVE-2021-21311 (SSRF - Critical)**
   - **Status**: FIXED
   - **Description**: Server-Side Request Forgery allowing attackers to scan internal networks
   - **Fix**: Removed ability to specify database server; hardcoded connection to WordPress database only

#### 🔒 Additional Security Hardening

2. **XSS Prevention**
   - All user-facing output is properly escaped using `esc_html()` and `esc_url()`
   - Security headers added: X-Content-Type-Options, X-XSS-Protection, Referrer-Policy

3. **Path Traversal Protection**
   - File paths are validated using `realpath()` to prevent directory traversal attacks

4. **Authentication & Authorization**
   - Strict capability checks: `manage_options` required (admin-only access)
   - Authentication re-verified on every request
   - Auto-login only works with WordPress session credentials

5. **Direct File Access Protection**
   - `.htaccess` files block direct access to `.inc.php` and helper files
   - Only `index.php` entry point is accessible

6. **Database Access Restriction**
   - Access limited to WordPress database only (no cross-database queries)
   - Database credentials hardcoded from `wp-config.php`

### Known Limitations

#### ⚠️ Outdated Adminer Core

**Important**: This plugin uses Adminer version 4.2.4 (released in 2015). While the critical SSRF vulnerability (CVE-2021-21311) has been patched in this fork, other vulnerabilities may exist in the underlying Adminer core:

- **CVE-2021-43008**: Potential XSS vulnerabilities in Adminer <= 4.8.0
- **CVE-2020-35572**: File disclosure vulnerability in Adminer < 4.7.9

**Why the old version is still used:**
- The security model of this fork (localhost-only, admin-only access) significantly reduces the attack surface
- Upgrading Adminer core requires extensive testing to ensure compatibility with the security patches
- The benefit of newer Adminer features is outweighed by the risk of breaking existing security controls

**Recommendation**: 
- Only use this plugin on trusted, private WordPress installations
- Consider this plugin for **development and staging environments only**
- For production environments, consider using phpMyAdmin or direct database access tools instead

### Security Best Practices

When using this plugin:

1. **Access Control**: Only grant `manage_options` capability to trusted administrators
2. **Network Security**: Use HTTPS for all admin panel access
3. **Environment**: Prefer using this tool in development/staging rather than production
4. **Monitoring**: Monitor database access logs for suspicious activity
5. **Updates**: Keep WordPress core and this plugin up to date

## Reporting a Vulnerability

If you discover a security vulnerability in SudoWP Adminer, please report it by:

1. **Do NOT** open a public issue
2. Email the maintainers at the contact information in the plugin header
3. Include:
   - Description of the vulnerability
   - Steps to reproduce
   - Potential impact
   - Suggested fix (if available)

We will respond within 48 hours and work to address confirmed vulnerabilities as quickly as possible.

## Acknowledgments

- Original Adminer by Jakub Vrana
- CVE-2021-21311 disclosure and patch contributors
- WordPress security team for best practices guidance
