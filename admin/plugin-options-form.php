<?php
/*
 * Silverlight Video Player
 * Formulaire de gestion des options du plugin
 *
 * @package    sl-playerss
 * @subpackage wordpress plugin
 * @version    SVN: $Id: plugin-options-form.php 24 2010-05-27 11:56:58Z sushicodeur $
 *
 */
?>

<div class="wrap nosubsub">

  <?php //echo screen_icon('playerss'); ?>
  <h2><?php _e('Options du plugin Silverlight Video Player', 'sl-playerss'); ?></h2>

  <p><?php _e('Cette page vous permet de configurer les options de votre plugin.', 'sl-playerss'); ?></p>

  <form action="options-general.php?page=sl-playerss.php" method="post">

    <input type="hidden" name="sl-playerss-action" value="save_options" />


    <h3><?php _e('Paramètres du composant Silverlight', 'sl-playerss'); ?></h3>
    <p class="help"><?php _e('Paramètre le composant Silverlight', 'sl-playerss'); ?></p>

    <table class="form-table">

      <tr>
        <th><label for="sl-playerss-background"><?php _e('Couleur d\'arrière plan', 'sl-playerss'); ?></label></th>
        <td>
          <input type="text" id="sl-playerss-background" name="sl-playerss-background" value="<?php echo get_option('sl-playerss-background'); ?>" />
          <p class="help"><?php _e('Paramètre la couleur de fond par défaut du composant Silverlight.', 'sl-playerss'); ?></p>
        </td>
      </tr>

      <tr>
        <th><label for="sl-playerss-width"><?php _e('Largeur', 'sl-playerss'); ?></label></th>
        <td>
          <input type="text" id="sl-playerss-width" name="sl-playerss-width" value="<?php echo get_option('sl-playerss-width'); ?>" />
          <p class="help"><?php _e('Paramètre la largeur par défaut du composant Silverlight.', 'sl-playerss'); ?></p>
        </td>
      </tr>

      <tr>
        <th><label for="sl-playerss-height"><?php _e('Hauteur', 'sl-playerss'); ?></label></th>
        <td>
          <input type="text" id="sl-playerss-height" name="sl-playerss-height" value="<?php echo get_option('sl-playerss-height'); ?>" />
          <p class="help"><?php _e('Paramètre la hauteur par défaut du composant Silverlight.', 'sl-playerss'); ?></p>
        </td>
      </tr>

    </table>


    <h3><?php _e('Valeurs par défaut', 'sl-playerss'); ?></h3>
    <p class="help"><?php _e('Paramètre les valeurs par défaut pour la lecture de vos vidéos', 'sl-playerss'); ?></p>

    <table class="form-table">

      <?php sl_playerss_format_vars(); ?>

    </table>

    <p class="submit"><input type="submit" name="submit" class="button" value="<?php esc_attr_e('Update options'); ?>" /></p>

  </form>

</div><?php // .wrap nosubsub ?>