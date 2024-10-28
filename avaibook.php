<?php
/**
 * @package AvaiBook
 * @version 1.2
 */
/*
Plugin Name: AvaiBook
Plugin URI: http://wordpress.org/plugins/avaibook/
Description: Show Avaibook booking form in your wordpress
Author: AvaiBook
Author URI: https://www.avaibook.com/
Text Domain: avaibook
Domain Path: /languages
Version: 1.2
Author URI: 
*/


//Evitar ejecucion indebida
if ( ! defined( 'WPINC' ) ) {
    die;
}



//////////////////////////
// CONFIGURATION
//////////////////////////

define("AVAIBOOK_BASE_URL",     "https://www.avaibook.com");




//////////////////////////
//Menu admin page
//////////////////////////

//Iniciar pestaña del admin
add_action( 'admin_menu', 'avaibook_menu' );

function avaibook_menu(){

  $page_title = 'AvaiBook';
  $menu_title = 'AvaiBook';
  $capability = 'manage_options';
  $menu_slug  = 'avaibook';
  $function   = 'avaibook_menu_page';
  $icon_url   = 'dashicons-calendar-alt';
  $position   = 4;

  add_menu_page( $page_title,
                 $menu_title, 
                 $capability, 
                 $menu_slug, 
                 $function, 
                 $icon_url, 
                 $position );
}

//Página del admin
function avaibook_menu_page(){

        //Check user can manage options. Security request from wordpress.org
        if (!current_user_can('manage_options')){
            echo "We are sorry, Your user can not manage options for your wordpress site. Please contact with your administrator.";
            return false;
        }

        $avaibookOptions = array(
            "rentalType" => null,
            "rentalId" => null,
            "ownerId" => null,
            "reference" => null,
            "showRentalUnits" => null,
            "showZones" => null,
            "showPeople" => null,
            "requestDates" => null,
            "requestGuestNumber" => null,
            "backgroundColor" => null,
            "mainColor" => null,
            "textColor" => null
        );

        

        
        //get options
        $avaibookOptions = unserialize(get_option("avaibookOptions"));

        //update options
        $screen = get_current_screen();
        for ($formNumber=1;$formNumber<4;$formNumber++){
            if (isset($_POST["update".$formNumber]) && sanitize_text_field($_POST["update".$formNumber]) && $screen->base == "toplevel_page_avaibook"){

                //check nonce. Security request from wordpress.org
                
                check_admin_referer( 'avaibook_update_'.$formNumber);

                //Get options from POST request.
                //Sanitized. Security request from wordpress.org
                $avaibookOptions[$formNumber] = array(
                    "rentalType" => sanitize_text_field($_POST["rentalType".$formNumber]),
                    "rentalId" => (int) sanitize_text_field($_POST["rentalId".$formNumber]),
                    "ownerId" => (int) sanitize_text_field($_POST["ownerId".$formNumber]),
                    "reference" => sanitize_text_field($_POST["rentalType".$formNumber] == "single" ? $_POST["singleReference".$formNumber] : $_POST["multipleReference".$formNumber]),
                    "showRentalUnits" => (bool) sanitize_text_field($_POST["showRentalUnits".$formNumber]),
                    "showZones" => (bool) sanitize_text_field($_POST["showZones".$formNumber]),
                    "showPeople" => (bool) sanitize_text_field($_POST["showPeople".$formNumber]),
                    "requestDates" => (bool) sanitize_text_field($_POST["requestDates".$formNumber]),
                    "requestGuestNumber" => (bool) sanitize_text_field($_POST["requestGuestNumber".$formNumber]),
                    "backgroundColor" => isColour(sanitize_text_field($_POST["backgroundColor".$formNumber]))?sanitize_text_field($_POST["backgroundColor".$formNumber]):'',
                    "mainColor" => isColour(sanitize_text_field($_POST["mainColor".$formNumber]))?sanitize_text_field($_POST["mainColor".$formNumber]):'',
                    "textColor" => isColour(sanitize_text_field($_POST["textColor".$formNumber]))?sanitize_text_field($_POST["textColor".$formNumber]):'',
                    "title"=> sanitize_text_field($_POST["title".$formNumber])
                );

                //Update
                update_option( "avaibookOptions", serialize($avaibookOptions));
            }
        }

        //get options after update
        $actOptions = get_option("avaibookOptions");
        if ($actOptions){
            $avaibookOptions = unserialize(get_option("avaibookOptions"));
        }

        //show admin page
        include_once(__DIR__.'/includes/admin.php');  
}


//Javascript admin
add_action('admin_enqueue_scripts', 'avaibook_admin_scripts');
function avaibook_admin_scripts($hook){
  if ($hook != 'toplevel_page_avaibook'){
      return;
  }
  //jquery
  wp_enqueue_script('jquery');
  //color picker
  wp_enqueue_script('avaibook_colorpicker_script', plugin_dir_url(__FILE__) . '/js/colorpicker.js');
  //javascript
  wp_enqueue_script('avaibook_admin_script', plugin_dir_url(__FILE__) . '/js/admin.js');
}


//css admin
add_action('admin_enqueue_scripts', 'avaibook_admin_css');
function avaibook_admin_css($hook) {
    if ($hook != 'toplevel_page_avaibook'){
        return;
    }
    wp_enqueue_style('avaibook_admin_css', plugin_dir_url(__FILE__).'/css/admin.css');
}








///////////////////////////////
// FRONT
//////////////////////////////

//Get front form. Template at /includes/front.php
function getFrontContent($formNumber=1){
    $avaibookAllOptions = unserialize(get_option("avaibookOptions"));
    $avaibookOptions = $avaibookAllOptions[$formNumber];

    if (
        ($avaibookOptions["rentalType"]=="single" && $avaibookOptions["rentalId"]) ||
        ($avaibookOptions["rentalType"]=="multiple" && $avaibookOptions["ownerId"])
    ){

        $htmlId = uniqid();
        ob_start();
            include( dirname ( __FILE__ ) . '/includes/front.php' );
        return ob_get_clean();
    }

    return "";
}

//Get Css to include dinamically
function getCss($formNumber=1){
    $avaibookOptions = unserialize(get_option("avaibookOptions"));
    ob_start();
        include( dirname ( __FILE__ ) . '/includes/frontCss.php' );
    return ob_get_clean();
}

add_action('wp_head', 'avaibook_dinamic_css');

function avaibook_dinamic_css(){
    echo minifyCss(getCss());
}


//Add scripts
add_action('wp_enqueue_scripts','avaibook_scripts');

function avaibook_scripts(){
    $avaibookOptions = unserialize(get_option("avaibookOptions"));
    if ( (isset($avaibookOptions[1]) && $avaibookOptions[1]["requestDates"]) ||
         (isset($avaibookOptions[2]) && $avaibookOptions[2]["requestDates"]) ||
         (isset($avaibookOptions[3]) && $avaibookOptions[3]["requestDates"])
        ){

        wp_register_script('moment',plugin_dir_url(__FILE__) . '/js/moment-with-locales.min.js',null,'2.22.1',true);
        wp_enqueue_script('moment');

        wp_register_script('avaibook_datepicker_script',plugin_dir_url(__FILE__) . '/js/lightpick.js',null,'1.0',true);
        wp_enqueue_script('avaibook_datepicker_script');

        wp_register_style('avaibook_datepicker_css', plugin_dir_url(__FILE__).'/css/lightpick.css', null,'1.0', 'all');
        wp_enqueue_style('avaibook_datepicker_css');
    }

    wp_register_script('avaibook_main_script',plugin_dir_url(__FILE__) . '/js/front.js',array('moment','avaibook_datepicker_script'),'1.0',true);
    wp_enqueue_script('avaibook_main_script');
    
}


///////////////////////
// shortcodes
//////////////////////

add_shortcode('avaibook1', 'avaibook_shortcode1');
function avaibook_shortcode1(){
    return getFrontContent(1);
}
add_shortcode('avaibook2', 'avaibook_shortcode2');
function avaibook_shortcode2(){
    return getFrontContent(2);
}
add_shortcode('avaibook3', 'avaibook_shortcode3');
function avaibook_shortcode3(){
    return getFrontContent(3);
}




////////////////////
// widget
////////////////////

//Widget Avaibook 1
add_action( 'widgets_init', 'avaibook_widget_1' );

function avaibook_widget_1() { 
    register_widget( 'avaibook_Widget_1' );
}

class avaibook_Widget_1 extends WP_Widget {
    public function __construct() {
        $widget_options = array( 
          'classname' => 'avaibook_Widget_1',
          'description' => __('Show Avaibook book form 1','avaibook'),
        );
        parent::__construct( 'avaibook_widget_1', 'Avaibook 1', $widget_options );
      }

      public function widget( $args, $instance ) {
          echo getFrontContent(1);
      }
}

//Widget Avaibook 2
add_action( 'widgets_init', 'avaibook_widget_2' );

function avaibook_widget_2() { 
    register_widget( 'avaibook_Widget_2' );
}

class avaibook_Widget_2 extends WP_Widget {
    public function __construct() {
        $widget_options = array( 
          'classname' => 'avaibook_Widget_2',
          'description' => __('Show Avaibook book form 2','avaibook'),
        );
        parent::__construct( 'avaibook_widget_2', 'Avaibook 2', $widget_options );
      }

      public function widget( $args, $instance ) {
          echo getFrontContent(2);
      }
}


//Widget Avaibook 3
add_action( 'widgets_init', 'avaibook_widget_3' );

function avaibook_widget_3() {
    register_widget( 'avaibook_Widget_3' );
}

class avaibook_Widget_3 extends WP_Widget {
    public function __construct() {
        $widget_options = array( 
          'classname' => 'avaibook_Widget_3',
          'description' => __('Show Avaibook book form 3','avaibook'),
        );
        parent::__construct( 'avaibook_widget_3', 'Avaibook 3', $widget_options );
      }

      public function widget( $args, $instance ) {
          echo getFrontContent(3);
      }
}




//////////////////////////////////////
//LANGUAGES
/////////////////////////////////////
add_action( 'init', 'avaibook_load_textdomain' );
function avaibook_load_textdomain() {
    load_plugin_textdomain( 'avaibook', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

//////////////////////////////////////
//AUX
//////////////////////////////////////

function getAvaibookLanguaje(){
    $arr = explode("-",get_bloginfo("language"));
    return $arr[0];
}

function hex2rgba($color, $opacity = false) {

    $default = 'rgb(0,0,0)';

    //Return default if no color provided
    if(empty($color))
          return $default;

    //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
}


function minifyCss($css) {
    // some of the following functions to minimize the css-output are directly taken
    // from the awesome CSS JS Booster: https://github.com/Schepp/CSS-JS-Booster
    // all credits to Christian Schaefer: http://twitter.com/derSchepp
    // remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    // backup values within single or double quotes
    preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
    for ($i=0; $i < count($hit[1]); $i++) {
      $css = str_replace($hit[1][$i], '##########' . $i . '##########', $css);
    }
    // remove traling semicolon of selector's last property
    $css = preg_replace('/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css);
    // remove any whitespace between semicolon and property-name
    $css = preg_replace('/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css);
    // remove any whitespace surrounding property-colon
    $css = preg_replace('/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css);
    // remove any whitespace surrounding selector-comma
    $css = preg_replace('/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css);
    // remove any whitespace surrounding opening parenthesis
    $css = preg_replace('/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css);
    // remove any whitespace between numbers and units
    $css = preg_replace('/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css);
    // shorten zero-values
    $css = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);
    // constrain multiple whitespaces
    $css = preg_replace('/\p{Zs}+/ims',' ', $css);
    // remove newlines
    $css = str_replace(array("\r\n", "\r", "\n"), '', $css);
    // Restore backupped values within single or double quotes
    for ($i=0; $i < count($hit[1]); $i++) {
      $css = str_replace('##########' . $i . '##########', $hit[1][$i], $css);
    }
    return $css;
  }
  


  function isColour($colour){
      return preg_match('/#([a-f0-9]{3}){1,2}\b/i',$colour);
  }


?>
