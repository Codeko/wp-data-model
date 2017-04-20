<?php

/*
  Plugin Name: Modelo de datos
  Description: Modelo de datos con los CPT, Taxonomías y ACF
  Version: 1.2
 */

class DataModel {

    public function __construct() {
        add_action('init', array($this, 'register_cpts'));
        add_action('init', array($this, 'register_taxs'));
        add_filter('acf/settings/save_json', array($this, 'acf_json_save_point'));
        add_filter('acf/settings/load_json', array($this, 'acf_json_load_point'));
    }
    //Load the routes where you are $file and make the includes
    public function register_rutes($file) {
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

    public function register_cpts() {
        $this->register_rutes("cpts.php");
    }

    public function register_taxs() {
        $this->register_rutes("taxs.php");
    }
    //Verify that the route exists, otherwise he believes create
    public function check_route($route) {
        if (!file_exists($route)) {
            mkdir($route, 0777, true);
            chown($route, 'root');
        }
    }

    public function acf_json_save_point($path) {
        $path = dirname(__FILE__, 3) . '/acf-groups/';
        $this->check_route($path);
        return $path;
    }

    public function acf_json_load_point($paths) {
        unset($paths[0]);
        $paths[] = dirname(__FILE__, 3) . '/acf-groups/';
        return $paths;
    }

}

new DataModel();
