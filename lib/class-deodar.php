<?php 
/**
 * 
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 
 */
class Deodar{

    public function __construct() {}

    public function bind(){
        add_action('after_setup_theme', array($this, 'after_setup_theme'));
        add_action('init', array($this, 'init'));
        add_action('acf/init', array($this, 'acf_init'));
    }

    public function after_setup_theme(){}
    public function init(){}
    public function acf_init(){}
}