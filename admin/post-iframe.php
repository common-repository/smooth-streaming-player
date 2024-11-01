<?php require_once('../../../../wp-blog-header.php'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="fr-FR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php _e('Plugin Silverlight Video Player', 'sl-playerss'); ?></title>
  <script type="text/javascript" src="../../../../wp-includes/js/jquery/jquery.js"></script>
  <script type="text/javascript" src="../js/jquery.colorpicker.min.js"></script>
  <script type="text/javascript" src="../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
  <script type="text/javascript">
  var slPlayerSS = {

    init : function() {
    },

    insert : function() {

      var form = document.forms[0];
      var shortcode = '[sl-playerss';
      var separator = ' ';

      if (form['sl-playerss-background'].value) {
        shortcode += separator + 'background="'+form['sl-playerss-background'].value+'"';
      }

      if (form['sl-playerss-width'].value) {
        shortcode += separator + 'width="'+form['sl-playerss-width'].value+'"';
      }

      if (form['sl-playerss-height'].value) {
        shortcode += separator + 'height="'+form['sl-playerss-height'].value+'"';
      }

      if (form['sl-playerss-video-url'].value) {
          shortcode += separator + 'videoUrl="'+form['sl-playerss-video-url'].value+'"';
        }

<?php sl_playerss_format_vars_tinymce(); ?>

      shortcode += ']';

      tinyMCEPopup.editor.execCommand('mceInsertRawHTML', false, shortcode);
      tinyMCEPopup.close();

    }
  };
  tinyMCEPopup.onInit.add(slPlayerSS.init, slPlayerSS);
  </script>
  <script type="text/javascript">
    last_background_color = '<?php echo get_option('sl-playerss-background'); ?>';

    jQuery(document).ready(function($) {

	    $('input.jquery-colorpicker').ColorPicker({
	      onSubmit: function(hsb, hex, rgb, el) {
	        $(el).val(hex);
	        $(el).ColorPickerHide();
	        updateInitParams($(el));
	      },
	      onBeforeShow: function () {
	        $(this).ColorPickerSetColor(this.value);
	      }
	    })
	    .bind('keyup', function(){
	      $(this).ColorPickerSetColor(this.value);
	    });

      $('input').change(function() {
        changed = $(this);
        changed_name = changed.attr('name');
        changed_val = changed.val();
        player = $('#preview-player');
        if (changed_name == 'sl-playerss-width') {
          player.attr('width', changed_val);
        } else if (changed_name == 'sl-playerss-height') {
          player.attr('height', changed_val);
        } else if (changed_name == 'sl-playerss-background') {
          if (changed_val != 'transparent') changed_val = '#'+changed_val;
          player.find('param[name=background]').attr('value', changed_val);
          player.replaceWith(player.clone());
        } else {
          updateInitParams(changed);
        }
      });

      $('#sl-playerss-transparency').click(function(){
          player = $('#preview-player');
          if($(this).is(':checked')) {
            last_background_color = $('#sl-playerss-background').attr('value');
            if (last_background_color!='transparent') last_background_color = '#'+last_background_color;
            $('#sl-playerss-background').attr('value', 'transparent').attr('disabled', true);
            player.find('param[name=background]').attr('value', 'transparent');
            player.replaceWith(player.clone());
          } else {
            $('#sl-playerss-background').removeAttr('disabled').attr('value', last_background_color);
            player.find('param[name=background]').attr('value', last_background_color);
            player.replaceWith(player.clone());
          }
      });
    });

    function updateInitParams(changed) {
      changed_name = changed.attr('name');
      changed_val = changed.val();
      player = jQuery('#preview-player');
      if (changed_name=='sl-playerss-background') {
        if (changed_val != 'transparent') changed_val = '#'+changed_val;
        player.find('param[name=background]').attr('value', changed_val);
      } else {
        split_coma_reg = new  RegExp('[,]+', 'g');
        split_equal_reg = new  RegExp('[=]+', 'g');
        init_params = player.find('param[name=initParams]').attr('value');
        init_params_array = init_params.split(split_coma_reg);
        init_params = '';
        separator = '';
        for  (var i=0; i<init_params_array.length; i++)  {
          init_param_name_value_array = init_params_array[i].split(split_equal_reg)
          if ('sl-playerss-'+trim(init_param_name_value_array[0], ' ') == changed_name) {
            if (changed.attr('type') == 'checkbox') {
              changed_val = (changed.attr('checked'))?'true':'false';
              init_params = init_params + separator + init_param_name_value_array[0] + '=' + changed_val;
            } else {
              init_params = init_params + separator + init_param_name_value_array[0] + '=' + changed_val;
            }
          } else if (trim(init_param_name_value_array[0], ' ') == 'videoUrl' && changed_name == 'sl-playerss-video-url') {
            init_params = init_params + separator + 'videoUrl=' + changed_val;
            if (changed_val.indexOf('<?php echo $_SERVER['HTTP_HOST']; ?>') < 0) {
              jQuery('#crossdomain-help').show();
            } else {
              jQuery('#crossdomain-help').hide();
            }
          } else {
            init_params = init_params + separator + init_params_array[i];
          }
          separator = ',';
        }
        player.find('param[name=initParams]').attr('value', init_params);
      }
      player.replaceWith(player.clone());
    }
    function trim(str, chars) {
      return ltrim(rtrim(str, chars), chars);
    }
    function ltrim(str, chars) {
      chars = chars || "\\s";
      return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
    }
    function rtrim(str, chars) {
      chars = chars || "\\s";
      return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
    }

  </script>
  <style type="text/css">
    h3 {
      display:block;
      color:#5A5A5A;
      font-family:Georgia,"Times New Roman",Times,serif;
      font-weight:normal;
      font-size:1.6em;
      margin:1em 0;
    }
    h4 {
      display:block;
      color:#5A5A5A;
      font-family:Georgia,"Times New Roman",Times,serif;
      font-weight:normal;
      border-bottom:1px solid #DADADA;
      font-size:1.3em;
      padding:0 0 3px;
    }
    div.fieldlist {
      border:1px solid #DFDFDF;
      width:615px;
      padding:12px;
      margin-bottom:20px;
      color:#333333;
    }
    th {
      vertical-align:top;
      font-size:13px;
      text-align: left;
    }
    tr {
      line-height:2em;
    }
    p.help {
      margin:0 0 10px 0;
      font-style:italic;
      line-height:1.5em;
    }
    body {
      font-size:13px;
    }
    .hide {
      display:none;
    }
    #sl-playerss-video-url {
      width:350px;
    }
  </style>
  <link rel="stylesheet" href="css/colorpicker.css" type="text/css" media="screen" />
</head>

<body id="sl-playerss">

<form onsubmit="slPlayerSS.insert(); return false;" action="#" class="media-upload-form type-form validate" id="sl-playerss-insert-form">

  <h3><?php _e('Insérer Silverlight Video Player', 'sl-playerss'); ?></h3>

  <div class="fieldlist">

    <h4 class="media-item media-blank"><?php _e('Paramètres du composant Silverlight', 'sl-playerss'); ?></h3>

    <table class="form-table">

      <tr>
        <th><label for="sl-playerss-transparency"><?php _e('Arrière plan transparent', 'sl-playerss'); ?></label></th>
        <td>
          <input type="checkbox" id="sl-playerss-transparency" name="sl-playerss-transparency" value="1" />
          <p class="help"><?php _e('En cochant cette case, le lecteur aura un fond transparent.', 'sl-playerss'); ?></p>
        </td>
      </tr>

      <tr>
        <th><label for="sl-playerss-background"><?php _e('Couleur d\'arrière plan', 'sl-playerss'); ?></label></th>
        <td>
          <input type="text" id="sl-playerss-background" name="sl-playerss-background" value="<?php echo get_option('sl-playerss-background'); ?>" class="jquery-colorpicker" />
          <p class="help"><?php _e('Paramètre la couleur de fond du composant Silverlight.', 'sl-playerss'); ?></p>
        </td>
      </tr>

      <tr>
        <th><label for="sl-playerss-width"><?php _e('Largeur', 'sl-playerss'); ?></label></th>
        <td>
          <input type="text" id="sl-playerss-width" name="sl-playerss-width" value="<?php echo get_option('sl-playerss-width'); ?>" />
          <p class="help"><?php _e('Paramètre la largeur du composant Silverlight.', 'sl-playerss'); ?></p>
        </td>
      </tr>

      <tr>
        <th><label for="sl-playerss-height"><?php _e('Hauteur', 'sl-playerss'); ?></label></th>
        <td>
          <input type="text" id="sl-playerss-height" name="sl-playerss-height" value="<?php echo get_option('sl-playerss-height'); ?>" />
          <p class="help"><?php _e('Paramètre la hauteur du composant Silverlight.', 'sl-playerss'); ?></p>
        </td>
      </tr>

    </table>

  </div>
  <div class="fieldlist">

    <h3><?php _e('Paramètres du lecteur vidéo', 'sl-playerss'); ?></h3>

    <table class="form-table">

      <tr>
        <th><label for="sl-playerss-video-url"><?php _e('Url de la vidéo', 'sl-playerss'); ?></label></th>
        <td>
          <input type="text" id="sl-playerss-video-url" name="sl-playerss-video-url" value="<?php echo sl_player_get_demo_url(); ?>" />
          <p class="help"><?php _e('Url de la vidéo ou du flux Smooth Streaming.', 'sl-playerss'); ?></p>
          <p id="crossdomain-help" class="help<?php echo (sl_playerss_parse_url_domain(sl_player_get_demo_url())==$_SERVER['HTTP_HOST'])?' hide':''; ?>"><?php _e('Cette vidéo est hébergée sur un autre serveur que celui de votre site. Son accès est dépendant du fichier clientaccesspolicy.xml ou crossdomain.xml. <a href="http://community.dynamics.com/blogs/cesardalatorre/comments/9579.aspx" target="_blank">En savoir plus ici</a>.', 'sl-playerss'); ?></p>
        </td>
      </tr>

      <?php sl_playerss_format_vars(); ?>

    </table>

  </div>
  <div class="fieldlist">

    <h3><?php _e('Prévisualisation du lecteur vidéo', 'sl-playerss'); ?></h3>

    <object id="preview-player" data="data:application/x-silverlight," type="application/x-silverlight-2" width="<?php echo get_option('sl-playerss-width'); ?>" height="<?php echo get_option('sl-playerss-height'); ?>">
      <param name="source" value="<?php echo PLAYERSS_URL ?>/silverlight/playerss.xap"/>
      <param name="onerror" value="onSilverlightError" />
      <param name="windowless" value="true" />
      <param name="background" value="<?php echo get_option('sl-playerss-background'); ?>" />
      <param name="minRuntimeVersion" value="3.0.40818.0" />
      <param name="autoUpgrade" value="true" />
      <param  name="initParams" value="<?php echo sl_playerss_get_initvars(sl_playerss_default_vars(), true) ?>" />
      <a href="http://go.microsoft.com/fwlink/?LinkID=149156&v=4.0.50401.0" style="text-decoration: none;">
        <img src="http://go.microsoft.com/fwlink/?LinkID=161376" alt="Get Microsoft Silverlight" style="border-style: none"/>
      </a>
    </object>

  </div>

  <p class="savebutton ml-submit">
    <input type="submit" class="button" name="save" value="<?php esc_attr_e('Enregistrer toutes les modifications', 'sl-playerss'); ?>" />
  </p>

</body>
</html>
