# Wordpress Data Model

[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](http://www.gnu.org/licenses/agpl-3.0)

Base template to easy load custom posts types, customs taxonomies and load/save ACF groups

## Description

This plugins loads the taxonomies, custom post types and acf groups.

The plugin search for a `taxs.php` and `cpts.php` file in the following path:

* Theme directory.
* If child themes are used search in both child theme and parent theme directory.
* Alls active plugins path.
* `wp-content` directory.

Any file found will be included. 

The plugin also define the ACF json save path to `wp-content/acf-groups` and add that path to the ACF json load paths.


