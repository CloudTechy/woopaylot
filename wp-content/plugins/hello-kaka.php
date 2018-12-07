<?php
/**
 * @package Hello_Kaka
 * @version 1.6
 */
/*
Plugin Name: Hello alex
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: i created this plugin so i can make you happy when i say hello
Version: 1.6
Author URI: http://ma.tt/
*/

function sayHello(){

    echo "<p id = 'hello'> hello alex </p>";

}

function sayHelloClass(){

    echo "
    <style>
        #hello {
            color : red;
            font-size: 11px;
            font-familiy : 'cursive';
            padding-left: 12px;
            margin: 0px;
            float:left;
        }
    </style>
    ";
}

add_action('admin_notices','sayHello');
add_action('admin_notices','sayHelloClass');
?>




