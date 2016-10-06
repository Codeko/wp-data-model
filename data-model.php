<?php

/*
  Plugin Name: Modelo de datos
  Description: Modelo de datos con los CPT, Taxonomías y ACF
  Version: 1.1
 */

/** Custom post types * */
require_once( plugin_dir_path(__FILE__) . 'cpt.php' );
/** Taxonomies * */
require_once( plugin_dir_path(__FILE__) . 'tax.php' );

class DataModel {

    public function __construct() {
        add_action('init', array($this, 'register_cpts'));
        add_action('init', array($this, 'register_taxs'));
        add_filter('acf/settings/save_json', array($this, 'acf_json_save_point'));
        add_filter('acf/settings/load_json', array($this, 'acf_json_load_point'));
    }

    public function register_cpts() {
        new Cpts();
    }

    public function register_taxs() {
        new Taxs();
    }

    public function acf_json_save_point($path) {
        $path = plugin_dir_path(__FILE__) . 'acf-groups/';
        return $path;
    }

    public function acf_json_load_point($paths) {
        unset($paths[0]);
        $paths[] = plugin_dir_path(__FILE__) . 'acf-groups/';
        return $paths;
    }

}

new DataModel();
