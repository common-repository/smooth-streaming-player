<?php
/*
 * Plugin Name: Silverlight Video Player
 * Description: Embeds a video player into page or post using Silverlight
 * Version: 1.0
 * Author: Alain Diart for les-sushi-codeurs.fr &amp; Eric Ambrosi for regart.net
 *
 * @package    sl-playerss
 * @subpackage wordpress plugin
 * @version    SVN: $Id: sl-playerss.php 24 2010-05-27 11:56:58Z sushicodeur $
 *
 */

// Définition des constantes
define ('PLAYERSS_URL', WP_PLUGIN_URL .'/'. plugin_basename(dirname(__FILE__)) );
define ('PLAYERSS_ABSPATH', str_replace('\\', '/', WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__))));

// Gère les options du plugin
require_once(PLAYERSS_ABSPATH.'/functions.php');
require_once(PLAYERSS_ABSPATH.'/admin/plugin-options-controller.php');

load_plugin_textdomain( 'sl-playerss', dirname(__FILE__) . '/languages/', 'sl-playerss/languages/' );

/**
 * Ajoute l'entrée de menu dans l'admin WordPress pour la page d'option du plugin
 * @return unknown_type
 */
function sl_playerss_add_plugin_options() {
  add_options_page(__('Silverlight Video Player', 'sl-playerss'), __('Silverlight Video Player', 'sl-playerss'), 'administrator', basename(__FILE__), 'sl_playerss_plugin_options');
}

/**
 * Affiche la page d'option du plugin
 * @return unknown_type
 */
function sl_playerss_plugin_options() {
  require_once(PLAYERSS_ABSPATH.'/admin/plugin-options-form.php');
}

/**
 * Ajoute le bouton au dessus de l'éditeur pour ouvrir la popup d'ajout de shortcode
 * @return unknown_type
 */
function sl_playerss_add_media_button() {
  $iframe_href = PLAYERSS_URL.'/admin/post-iframe.php';
  $image_src = PLAYERSS_URL.'/images/media-button-sl-playerss.gif';
  $image_title = __('Insérer Silverlight Video Player', 'sl-playerss');
  echo '<a href="'. $iframe_href .'?TB_iframe=true" id="add_sl_playerss" class="thickbox" title="'. $image_title .'" onclick="return false;"><img src="'. $image_src .'" alt="'. $image_title .'" /></a>';
}

/**
 * Gère le shortcode
 * @param $atts
 * @return unknown_type
 */
function sl_playerss_shortcode($atts) {
  // Combine les valeurs par défaut et celles du shortcode
  $atts = shortcode_atts(sl_playerss_default_vars(), $atts);
  // Retourne la sortie HTML
  return sl_playerss_do_shortcode($atts);
}


// Ajoute les actions du plugin
if (is_admin()) {
  add_action('admin_menu', 'sl_playerss_add_plugin_options');
  add_action('media_buttons', 'sl_playerss_add_media_button', 20);
  wp_register_script('jquery_colorpicker', PLAYERSS_URL . '/js/jquery.colorpicker.min.js', 'jquery');
  wp_enqueue_script('jquery_colorpicker');
  wp_enqueue_style('jquery_colorpicker_css', PLAYERSS_URL . '/admin/css/colorpicker.css');
}

add_shortcode('sl-playerss', 'sl_playerss_shortcode');

wp_register_script( 'silverlight_js', PLAYERSS_URL . '/js/silverlight.js' );
wp_enqueue_script( 'silverlight_js' );