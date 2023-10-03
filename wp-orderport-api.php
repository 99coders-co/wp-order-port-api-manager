<?php
/**
* Plugin Name: WP Order Port Api Manager
* Plugin URI: https://www.orderport.com/
* Description: Lightweight, scalable and full-featured event listings & management plugin for managing order port listings from the Frontend and Backend.
* Author: Websites in a Flash
* Author URI: https://www.orderport.com
* Text Domain: wp-event-manager
* Domain Path: /languages
* Version: 3.1.37.1
* Since: 1.0.0
* Requires WordPress Version at least: 5.4.1
* Copyright: 2019 WP Order Port Api Manager
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*
**/

// Exit if accessed directly
if(!defined('ABSPATH')) {
	exit;
}

// Include the API integration file
require_once(plugin_dir_path(__FILE__) . 'OrderportAPI.php');

// Hook the code to an appropriate action, e.g., 'init'
// function register_custom_template() {
//     $plugin_dir = plugin_dir_path(__FILE__);
//     $template_file = $plugin_dir . 'templates/products-template.php';

//     if (file_exists($template_file)) {
//         // Include the template file
//         include $template_file;
//         // Register the template
//         add_filter('theme_page_templates', function ($templates) {
//             $templates['products-template.php'] = 'Order Port Products List';
//             return $templates;
//         });
//     }
// }

//add_action('init', 'register_custom_template');
function custom_query_vars($vars) {
    $vars[] = 'slug';
    return $vars;
}
add_filter('query_vars', 'custom_query_vars');
function custom_rewrite_rules($rules) {
    $new_rules = array(
        'products/([^/]+)/?$' => 'index.php?pagename=products&slug=$matches[1]',
    );
    return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'custom_rewrite_rules');




function order_port_api_plugin_settings_link($links) {
    $settings_link = '<a href="admin.php?page=wp-orderport-api-plugin-settings">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}
$plugin_basename = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_basename", 'order_port_api_plugin_settings_link');


function order_port_api_plugin_settings_init() {
    register_setting('order_port_api_plugin_settings', 'order_port_client_id');
    register_setting('order_port_api_plugin_settings', 'order_port_access_key');
    register_setting('order_port_api_plugin_settings', 'order_port_token');
}

function order_port_api_plugin_settings_page() {
    wp_enqueue_script( 'orderport_admin_js' );
    include(plugin_dir_path(__FILE__) . 'settings-page.php');
}

add_action('admin_init', 'order_port_api_plugin_settings_init');
add_action('admin_menu', 'order_port_api_plugin_settings_menu');

function order_port_api_plugin_settings_menu() {
    add_menu_page('Order Port API Plugin Settings', 'OrderPort API Settings', 'manage_options', 'orderport-api-plugin-settings', 'order_port_api_plugin_settings_page');
}

function amai_woordjes_scripts() {
wp_register_script( 'orderport_admin_js', plugin_dir_url( __FILE__ ).'assets/js/script.js', array( 'jquery' ), rand(), true );
}

add_action( 'admin_enqueue_scripts', 'amai_woordjes_scripts' );

add_filter( 'the_content', 'my_the_content_filter' );
function my_the_content_filter( $content ) {

    
      $products_query = get_query_var('slug');
      if($products_query){
         $content = "";
         $plugin_dir = plugin_dir_path(__FILE__);
         $template_file = $plugin_dir . 'templates/products-template.php';
         global $product_sku;
         $product_sku = $products_query;
         $content=include $template_file;        
      }else{
          return $content;
      }
}

// Initialize the plugin
function order_port_api_plugin_init() {
    // Add hooks and actions here
    // Create an instance of the API integration class
    include(plugin_dir_path(__FILE__) . 'page-products.php');
	//$api_integration = new OrderportAPI();
}

add_action('init', 'order_port_api_plugin_init');

add_shortcode( 'orderport-products', 'wporderport_products' );
function wporderport_products( $atts ) {
    $api_integration = new OrderportAPI();
    // Call the API
    $resp=$api_integration->callListOfAllProductsApi();
    $products_query = get_query_var('slug');


    if($products_query){
      $plugin_dir = plugin_dir_path(__FILE__);
      $template_file = $plugin_dir . 'templates/products-template.php';
      include $template_file;
    }else{


    $type="";
    if($atts['type']){
        $type=$atts['type'];    
    }

     $api_integration = new OrderportAPI();
     // Call the API
    $resp=$api_integration->callListOfAllProductsApi();
    $responseObj=json_decode($resp);
    $html='<style>.card_section .row {
    --bs-gutter-x: 1.5rem;
    --bs-gutter-y: 0;
    display: flex;
    flex-wrap: wrap;
    margin-top: calc(var(--bs-gutter-y) * -1);
    margin-right: calc(var(--bs-gutter-x) * -.5);
    margin-left: calc(var(--bs-gutter-x) * -.5);
}
.card_section .minnville-section{
    padding: 150px 0;
    background-color: #f7f7f7;
}
.card_section .col-3 {
    width: 25%;
    padding: 10px;
}
.card_section .btn {
    color: #fff;
    background-color: #662d91;
    background-image: url(../image/button_background.png);
    background-size: cover;
    background-repeat: no-repeat;
    border-radius: 0;
    padding: 15px 40px;
    line-height: 1;
    font-size: 1.2rem;
    letter-spacing: 0.02ch;
    border: 2px solid #662d91;
    font-weight: 400;
    transition: var(--transition);
    -webkit-transition: var(--transition);
}
.card_section .minnville_content {
    padding: 30px;
    background: #fff;
}
.card_section .minnville_bottom_content .btn_block {
    margin: 50px 0;
}
.card_section .sub-heading {
    font-size: 29px;
    font-style: italic;
    line-height: 40px;
    color: #000;
    margin: 0;
    margin-top: 10px;
}
.card_section .heading {
    font-size: 41px;
    line-height: 42px;
    margin: 0;
}
.card_section .pra {
    font-size: 20px;
    line-height: 30px;
    font-weight: 400;
}
.card_section .w-100 {
    width: 100%;
    height: 400px;
    object-fit: contain;
}
.card_section a {
    font-size: 20px;
    line-height: 30px;
    text-decoration: none;
}
.card_section .minnville_block {
    background: #fff;
    height: 100%;
}
.card_section .minnville_bottom_content .sub-heading {
    margin-top: 30px;
}
.card_section .home-columns .wp-block-column {
    background: transparent;
}
.card_section .minnville_img_block {
    padding: 15px;
}
.card_section .sales-products-filter select {
  -webkit-appearance: none;
  -moz-appearance: none;
  -ms-appearance: none;
  appearance: none;
  outline: 0;
  box-shadow: none;
  background: transparent;
  background-image: none;
}
/* Custom Select */
.card_section .sales-products-filter .select {
  position: relative;
  display: block;
  width: 20em;
  height: 3em;
  line-height: 3;
  background: #4f4f55;
  overflow: hidden;
  border-radius: .25em;
}
.card_section .sales-products-filter select {
  width: 25%;
  height: 52px;
  margin: 20px 10px;
  padding: 0 0 0 1rem;
  color: #000;
  cursor: pointer;
  border-radius:0;
  font-size:.8rem;
  text-transform:uppercase;
  letter-spacing:1px;
}
.card_section .sales-products-filter select::-ms-expand {
  display: none;
}
.card_section .sales-products-filter .select::after {
  content: "\25BC";
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  padding: 0 1em;
  background: #34495e;
  pointer-events: none;
}
.card_section .sales-products-filter .select:hover::after {
  color: #f39c12;
}
.card_section .sales-products-filter .select::after {
  -webkit-transition: .25s all ease;
  -o-transition: .25s all ease;
  transition: .25s all ease;
}
.card_section .sales-products-filter .filter-btn {
  background:none;
  border:1px solid #4f4f55;
  color:#4f4f55;
  text-transform:uppercase;
  letter-spacing:1px;
  font-size: .8rem;
  padding: 16px 20px;
  display:inline-block;
  margin-left:20px;
  font-family: "EB Garamond", serif;
}
.card_section .sales-products-filter .filter-btn:hover {
    background: #662d91;
    border: 0;
    color: #fff;
}
.card_section .sales-products-filter {
    display: block;
    text-align: right;
    margin-bottom: 50px;
}
@media only screen and (max-width: 1440px) {
.card_section .col-3 {
    width: 33.333%;
    padding: 10px;
}
}
@media only screen and (max-width: 1200px) {
.card_section .heading {
    font-size: 35px;
    line-height: 40px;
}
}
@media only screen and (max-width: 991px) {
.card_section .col-3 {
    width: 50%;
}
.card_section .w-100 {
    height: 350px;
}
.card_section .minnville_block {
    text-align: center;
}
.card_section .sales-products-filter select {
    width: 74%;
}
.card_section .sales-products-filter .filter-btn {
    width: 20%;
}
}
@media only screen and (max-width: 767px) {
  .card_section .col-3 {
    width: 100%;
  }
  .card_section .sales-products-filter select {
    width: 60%;
}
 .card_section .sub-heading {
    font-size: 26px;
  }
  .card_section .heading {
    font-size: 30px;
    line-height: 35px;
}
  }
@media only screen and (max-width: 600px) {
 .card_section .sales-products-filter select {
    width: 100%;
    margin: 0 !important;
    margin-bottom: 20px !important;
  }
 .card_section .sales-products-filter .filter-btn {
    width: 100%;
    margin: 0;
  }
  .card_section .w-100 {
    height: 250px;
}
.card_section .minnville_content {
    padding: 20px;
}
}</style>';
    $html.='<div class="row">';

    $ProductObj=[];
    if(count($responseObj->Data->Groups)>0){
        for($i=0;$i<count($responseObj->Data->Groups);$i++){

            if($responseObj->Data->Groups[$i]->Name==$type){
                if(count($responseObj->Data->Groups[$i]->Products)>0){
                    for($j=0;$j<count($responseObj->Data->Groups[$i]->Products);$j++){
                        $ProductObj[]=$responseObj->Data->Groups[$i]->Products[$j]->Opsku;
                        }
                    }
                }else{
                    if($responseObj->Data->Groups[$i]->SubGroups){

                            if(count($responseObj->Data->Groups[$i]->SubGroups)>0){
                                for($j=0;$j<count($responseObj->Data->Groups[$i]->SubGroups);$j++){
                                    if($responseObj->Data->Groups[$i]->SubGroups[$j]->Name==$type){
                                        if(count($responseObj->Data->Groups[$i]->SubGroups[$j]->Products)>0){
                                            for($k=0;$k<count($responseObj->Data->Groups[$i]->SubGroups[$j]->Products);$k++){
                                                $ProductObj[]=$responseObj->Data->Groups[$i]->SubGroups[$j]->Products[$k]->Opsku;
                                                }
                                            }
                                        }
                                    }
                            }
                    }


                }
        }
    }


    if(count($responseObj->Data->Products)>0){
      for($i=0;$i<count($responseObj->Data->Products);$i++){

                if(in_array($responseObj->Data->Products[$i]->Opsku,$ProductObj)){

                    $html.='<div class="col-3"> <div class="minnville_block"> <div class="minnville_img_block">';
                  if($responseObj->Data->Products[$i]->Image->Large){
                    $html.='<img src="'.$responseObj->Data->Products[$i]->Image->Large.'" alt="'.$responseObj->Data->Products[$i]->Title.'" class="w-100">';  
                  }         
                   
                            // <a target="_blank" href="'.$responseObj->Data->Products[$i]->ProductPageUrl.'">Buy Now</a>
                         $html.='</div> <div class="minnville_content"> <h2 class="heading">'.$responseObj->Data->Products[$i]->Title. " ". $responseObj->Data->Products[$i]->ID.'</h2> <h3 class="sub-heading">'.$responseObj->Data->Products[$i]->PriceDescr.'</h3> <p class="pra">'.$responseObj->Data->Products[$i]->SummaryOverview.'</p><div class="minnville_bottom_content"><div class="wp-block-button">
        <a class="wp-block-button__link wp-element-button" href="'.site_url().'/products/'.$responseObj->Data->Products[$i]->Opsku.'">Buy Now</a></div></div></div></div></div>';
                     }
      }
    }
    $html.='</div>';
    return $html;

    }

}

