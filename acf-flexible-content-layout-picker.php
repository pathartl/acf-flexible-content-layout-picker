<?php
    /*
    Plugin Name: Advanced Custom Fields: Flexible Content Layout Picker
    Plugin URI: https://github.com/pathartl/acf-flexible-content-layout-picker
    Description: ACF5 field to select layouts from the current flexible content field
    Version: 1.0.0
    Author: @pathartl
    Author URI: https://pathar.tl
    License: MIT
    License URI: http://opensource.org/licenses/MIT
    */

    // $version = 5 and can be ignored until ACF6 exists
    function include_field_types_flexible_content_layout_picker( $version ) {

        include_once( 'flexible-content-layout-picker-v5.php' );

    }

    add_action( 'acf/include_field_types', 'include_field_types_flexible_content_layout_picker' );

?>