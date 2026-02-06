<?php
/**
 * SudoWP Secure Bootstrapper for Adminer
 * This file acts as a firewall and configuration injector for the Adminer library.
 * It prevents direct access from unauthenticated users and fixes SSRF vulnerabilities.
 */

declare(strict_types=1);

// 1. Load WordPress Environment to check permissions
$wp_load_path = __DIR__ . '/../../../../../wp-load.php';

if ( file_exists( $wp_load_path ) ) {
    require_once( $wp_load_path );
} else {
    // Fallback for non-standard directory structures
    // Only use DOCUMENT_ROOT if it's a safe, absolute path
    $document_root = $_SERVER['DOCUMENT_ROOT'] ?? '';
    // Sanitize and validate the path
    $document_root = realpath( $document_root );
    if ( $document_root && file_exists( $document_root . '/wp-load.php' ) ) {
        require_once( $document_root . '/wp-load.php' );
    } else {
        die( 'SudoWP Security Error: Could not locate WordPress core files.' );
    }
}

// 2. Strict Access Control (The Gatekeeper)
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
    wp_die( '<h1>Access Denied</h1><p>You do not have sufficient permissions to access the database manager.</p>', 403 );
}

// 3. Security Headers
// Prevent clickjacking (we're using iframe from within WP admin which is safe)
// Note: X-Frame-Options is already removed by Adminer to allow iframe
// Add security headers to mitigate various attacks
header( "X-Content-Type-Options: nosniff" );
header( "Referrer-Policy: strict-origin-when-cross-origin" );
// Content Security Policy - Allow inline scripts (required by Adminer), restrict everything else
header( "Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; frame-ancestors 'self'" );

// 3. Define the Customization Class (The Patch)
function adminer_object(): object {
    
    // Allow Dynamic Properties for PHP 8.2+ compatibility
    // Adminer relies heavily on dynamic properties.
    #[\AllowDynamicProperties]
    class SudoWP_Adminer_Extension extends Adminer {
        
        /**
         * SECURITY FIX: SSRF Prevention
         * Force connection to the local WordPress database only.
         */
        function credentials(): array {
            // We use DB_HOST/USER/PASSWORD from wp-config.php
            return array( DB_HOST, DB_USER, DB_PASSWORD );
        }
        
        /**
         * Auto-Login
         * Security: Validate that user is still authenticated and authorized
         */
        function login( $login, $password ): bool {
            // Double-check authentication on every request
            if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
                return false;
            }
            // Only allow login with the configured WordPress database credentials
            return ( $login === DB_USER );
        }

        /**
         * Select the WP Database by default
         */
        function database(): string {
            return DB_NAME;
        }

        /**
         * SECURITY: Restrict database selection to WordPress database only
         */
        function databases(): array {
            // Only allow access to the configured WordPress database
            return array( DB_NAME );
        }
        
        /**
         * UI Cleanup: Remove the logout button since it's controlled by WP session
         */
        function loginForm(): void {
             echo '<div style="background:#fff; padding:20px; border-left:4px solid #46b450; box-shadow:0 1px 4px rgba(0,0,0,0.1);">';
             echo '<h3>SudoWP Secure Mode</h3>';
             echo '<p>Connected securely to <strong>' . esc_html( DB_NAME ) . '</strong> on <strong>' . esc_html( DB_HOST ) . '</strong>.</p>';
             echo '</div>';
        }

        // Optional: Disable checking for updates to prevent external calls
        function versionCheck(): bool {
            return false;
        }
    }
    
    return new SudoWP_Adminer_Extension;
}

// 4. Load the actual Adminer Library
if ( file_exists( __DIR__ . '/adminer.php' ) ) {
    include __DIR__ . '/adminer.php';
} else {
    echo "Error: Adminer library core not found.";
}