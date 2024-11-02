<?php
if ( !defined('ABSPATH') || !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
const OPTIONS_KEY = 'audiocomparisonlite_options';
if ( is_multisite() ) {
    global $wpdb;
    $original_blog_id = get_current_blog_id();
    $blogs = get_sites();
    foreach ($blogs as $b) {
        switch_to_blog($b->blog_id);
        delete_site_option(OPTIONS_KEY);
    }
    switch_to_blog($original_blog_id);
} else {
    delete_option(OPTIONS_KEY);
}
