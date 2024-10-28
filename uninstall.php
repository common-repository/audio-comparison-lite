<?php
if ( !defined('ABSPATH') || !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
const OPTIONS_KEY = 'audiocomparisonlite_options';
if ( is_multisite() ) {
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    $original_blog_id = get_current_blog_id();
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        delete_site_option(OPTIONS_KEY);
    }
    switch_to_blog($original_blog_id);
} else { 
    delete_option(OPTIONS_KEY);
}
