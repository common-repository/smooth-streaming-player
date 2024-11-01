<?php
/*
 * Silverlight Video Player
 * Gestion des options du plugin
 *
 * @package    sl-playerss
 * @subpackage wordpress plugin
 * @version    SVN: $Id: plugin-options-controller.php 22 2010-05-24 21:20:11Z sushicodeur $
 *
 */

  // Initialise les options du plugin
  if (get_option('sl-playerss-background') == null)
    add_option('sl-playerss-background', 'white');
  if (get_option('sl-playerss-width') == null)
    add_option('sl-playerss-width', '100%');
  if (get_option('sl-playerss-height') == null)
    add_option('sl-playerss-height', '400px');

  // Initialise les variables dynamiques
  sl_playerss_init_vars();


  // Met à jour les options
  if ($_REQUEST['sl-playerss-action'] == 'save_options') {

    // Met à jour les options du plugin
    update_option('sl-playerss-background', $_REQUEST['sl-playerss-background']);
    update_option('sl-playerss-width', $_REQUEST['sl-playerss-width']);
    update_option('sl-playerss-height', $_REQUEST['sl-playerss-height']);

    // Met à jour les variables dynamiques
    sl_playerss_update_vars();

  }