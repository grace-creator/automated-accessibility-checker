<?php
/*
Plugin Name: Automated Accessibility Checker
Description: A WordPress plugin that automatically checks and reports accessibility issues in your site based on WCAG (Web Content Accessibility Guidelines) standards. It provides suggestions for fixing issues, monitors improvements, and generates accessibility reports for compliance purposes.
Version: 1.0
Author: Grecia Baker
Author URI: https://gracespyware.wordpress.com/
License: GPLv2 or later
Text Domain: automated-accessibility-checker
*/

// Enqueue the necessary JavaScript and CSS files
function automated_accessibility_checker_enqueue_scripts() {
    wp_enqueue_script('automated-accessibility-checker', plugin_dir_url(__FILE__) . 'js/ai.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('automated-accessibility-checker', plugin_dir_url(__FILE__) . 'css/ai.css', array(), '1.0.0');
    
    // Pass the API key to the JavaScript file
    $config = array(
        'openai_api_key' => OPENAI_API_KEY
    );
    wp_localize_script('automated-accessibility-checker', 'automated_accessibility_checker', $config);
}
add_action('admin_enqueue_scripts', 'automated_accessibility_checker_enqueue_scripts');

// Add the plugin's settings page to the admin menu
function automated_accessibility_checker_add_menu_page() {
    add_menu_page('Automated Accessibility Checker', 'Automated Accessibility Checker', 'manage_options', 'automated-accessibility-checker', 'automated_accessibility_checker_render_settings_page', 'dashicons-visibility', 200);
}
add_action('admin_menu', 'automated_accessibility_checker_add_menu_page');

// Render the plugin's settings page
function automated_accessibility_checker_render_settings_page() {
    // Check if the user has the necessary permissions
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'automated-accessibility-checker'));
    }

    // Render the settings page's HTML
    echo '<div class="wrap">';
    echo '<h1>' . __('Automated Accessibility Checker', 'automated-accessibility-checker') . '</h1>';
    echo '<p>' . __('Paste the content you want to analyze below, and then click the "Analyze" button to get accessibility suggestions.', 'automated-accessibility-checker') . '</p>';
    echo '<textarea id="automated-accessibility-checker-content" class="widefat" rows="10"></textarea>';
    echo '<button id="automated-accessibility-checker-analyze" class="button button-primary">' . __('Analyze', 'automated-accessibility-checker') . '</button>';
    echo '<div id="automated-accessibility-checker-results"></div>';

    // Add the disclaimer
    echo '<p class="automated-accessibility-checker-disclaimer">';
    echo __('Please note that the suggestions provided by the AI are for guidance purposes and may not cover all aspects of accessibility guidelines. We encourage users to consult WCAG documentation and seek expert advice for a comprehensive accessibility audit.', 'automated-accessibility-checker');
    echo '</p>';

    echo '</div>';
}
