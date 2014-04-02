<?php
/*
 * Plugin Name: WPIndexer
 * Plugin URI: http://www.draganmitic.com
 * Description: Improve wordpress search performance by leveraging an ElasticSearch server.
 * Version: 0.2.0
 * Author: Dragan Mitic
 * Author URI: http://www.draganmitic.com
 * Author Email: miticdjd@gmail.com
 * Licence: GPL2
*/

/* Load WPIndexer bootstrap */
require 'src/bootstrap.php';

if (!defined('WPINDEXER_PATH')) {
    define('WPINDEXER_PATH', dirname(__FILE__) . '/');
}

/* Register init */
add_action('init', function() {
    new \WPIndexer\Administration\Administration();
    new \WPIndexer\Administration\Actions();
    new \WPIndexer\WP\Search();
});