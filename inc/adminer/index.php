<?php
/**
 * SudoWP Secure Bootstrapper for Adminer
 * * This file acts as a firewall and configuration injector for the Adminer library.
 * It prevents direct access from unauthenticated users and fixes SSRF vulnerabilities.
 */

// 1. Load WordPress Environment to check permissions
// We look for wp-load.php by traversing up directories.
$wp_load_path = __DIR__ . '/../../../../../wp-load.php';

if ( file_exists( $wp_load_path ) ) {
    require_once( $wp_load_path );
} else {
    // Fallback for non-standard directory structures
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    if ( file_exists( $document_root . '/wp-load.php' ) ) {
        require_once( $document_root . '/wp-load.php' );
    } else {
        die( 'SudoWP Security Error: Could not locate WordPress core files.' );
    }
}

// 2. Strict Access Control (The Gatekeeper)
if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
    wp_die( '<h1>Access Denied</h1><p>You do not have sufficient permissions to access the database manager.</p>', 403 );
}

// 3. Define the Customization Class (The Patch)
function adminer_object() {
    
    class SudoWP_Adminer_Extension extends Adminer {
        
        /**
         * SECURITY FIX: SSRF Prevention
         * Force connection to the local WordPress database only.
         * The user cannot enter a different server address.
         */
        function credentials() {
            return array( DB_HOST, DB_USER, DB_PASSWORD );
        }
        
        /**
         * Auto-Login
         * Since the user is already authenticated via WordPress,
         * we skip the Adminer login screen.
         */
        function login( $login, $password ) {
            return true;
        }

        /**
         * Select the WP Database by default
         */
        function database() {
            return DB_NAME;
        }
        
        /**
         * UI Cleanup: Remove the logout button since it's controlled by WP session
         */
        function loginForm() {
             echo '<div style="background:#fff; padding:20px; border-left:4px solid #46b450; box-shadow:0 1px 4px rgba(0,0,0,0.1);">';
             echo '<h3>SudoWP Secure Mode</h3>';
             echo '<p>Connected securely to <strong>' . DB_NAME . '</strong> on <strong>' . DB_HOST . '</strong>.</p>';
             echo '</div>';
        }

        // Optional: Disable checking for updates to prevent external calls
        function versionCheck() {
            return false;
        }
    }
    
    return new SudoWP_Adminer_Extension;
}

// 4. Load the actual Adminer Library
// This assumes the original library file is in the same folder named 'adminer.php'
if ( file_exists( __DIR__ . '/adminer.php' ) ) {
    include __DIR__ . '/adminer.php';
} else {
    echo "Error: Adminer library core not found.";
}