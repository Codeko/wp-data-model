<?php

/*
  Plugin Name: Data Model
  Plugin URI: https://github.com/Codeko/wp-data-model
  Description: Wordpress data model loader from custom post types, taxonomies and ACF groups.
  Author: Codeko
  Author URI: http://codeko.com
  Version: 1.0
  Text Domain: wp-data-model
  License: AGPL version 3 or later - https://www.gnu.org/licenses/agpl-3.0.html
 */

class DataModel {

    private $errors = array();

    const ACF_PATH = WP_CONTENT_DIR . '/acf-groups/';

    public function __construct() {
        //Register CPTs
        add_action('init', array($this, 'registerCpts'));
        //Register taxonomies
        add_action('init', array($this, 'registerTaxs'));
        //Load save ACF groups
        add_filter('acf/settings/save_json', array($this, 'acf_json_save_point'));
        add_filter('acf/settings/load_json', array($this, 'acf_json_load_point'));
        //Show warnings/errors
        add_action('admin_notices', array($this, 'adminNotices'));

        add_action('plugins_loaded', array($this, 'plugins_loaded'));
    }

    public function plugins_loaded() {
        //If acf exists check acf groups path
        if (function_exists('the_field')) {
            $this->checkPath(self::ACF_PATH);
        }
    }

    /**
     * Search a file in a variety of routes and include it if exists
     * @param type $file Name of the searched file
     */
    public function registerPaths($file) {
        $rutes[] = get_template_directory() . $file;
        $rutes[] = get_stylesheet_directory() . $file;
        $rutes[] = "/wp-content/" . $file;
        $pluginsActives = wp_get_active_and_valid_plugins();
        $directoryPlugins = array();
        foreach ($pluginsActives as $value) {
            $directoryPlugins[] = plugin_dir_path($value) . $file;
        }
        $rutes = array_merge($rutes, $directoryPlugins);
        $rutes = array_unique($rutes);
        foreach ($rutes as $rute) {
            if (file_exists($rute)) {
                include $rute;
            }
        }
    }

    public function registerCpts() {
        $this->registerPaths("cpts.php");
    }

    public function registerTaxs() {
        $this->registerPaths("taxs.php");
    }

    /**
     * Check if the path exists if not create it
     * @param string $route Path to be checked or created
     */
    public function checkPath($path) {
        if (true || !file_exists($path)) {
            if (true || !mkdir($path)) {
                $this->errors[] = __('Can\'t create ACF json save point in ' . $path, 'wp-data-model');
            }
        }
    }

    public function adminNotices() {
        foreach ($this->errors AS $error) {
            echo '<div class="error notice">';
            echo '<p>' . __('ERROR: ' . $error, 'wp-data-model') . '</p>';
            echo '</div>';
        }
        $this->errors = array();
    }

    public function acf_json_save_point($path) {
        $path = self::ACF_PATH;
        return $path;
    }

    public function acf_json_load_point($paths) {
        $paths[] = self::ACF_PATH;
        return $paths;
    }

}

new DataModel();
