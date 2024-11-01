<?php
/*
 * Silverlight Video Player
 * Fonctions du plugin
 *
 * @package    sl-playerss
 * @subpackage wordpress plugin
 * @version    SVN: $Id: functions.php 23 2010-05-27 11:36:23Z sushicodeur $
 *
 */

// Définit les variables globales
$sl_playerss_xml_vars_document = null;
$sl_playerss_xml_vars_xpath = null;


/**
 * Génère le HTML pour le shortcode
 * @param $atts
 * @return unknown_type
 */
function sl_playerss_do_shortcode($atts) {
  $output = '';
  $output .= '<object data="data:application/x-silverlight," type="application/x-silverlight-2" width="'. $atts['width'] .'" height="'. $atts['height'] .'">';
  $output .= '<param name="source" value="'. PLAYERSS_URL .'/silverlight/playerss.xap"/>';
  $output .= '<param name="onerror" value="onSilverlightError" />';
  if ($atts['background'] == 'transparent') {
    $output .= '<param name="windowless" value="true" />';
    $output .= '<param name="background" value="'. $atts['background'] .'" />';
  } else {
    $output .= '<param name="windowless" value="false" />';
    $output .= '<param name="background" value="#'. $atts['background'] .'" />';
  }
  $output .= '<param name="minRuntimeVersion" value="3.0.40818.0" />';
  $output .= '<param name="autoUpgrade" value="true" />';
  $output .= '<param  name="initParams" value="'. sl_playerss_get_initvars($atts) .'" />';
  $output .= '<a href="http://go.microsoft.com/fwlink/?LinkID=149156&v=4.0.50401.0" style="text-decoration: none;">';
  $output .= '<img src="http://go.microsoft.com/fwlink/?LinkID=161376" alt="Get Microsoft Silverlight" style="border-style: none"/>';
  $output .= '</a>';
  $output .= '</object>';
  return $output;
}


/**
 * Initialise les variables dynamiques
 * @return unknown_type
 */
function sl_playerss_init_vars() {
  $xml_vars = sl_playerss_get_xml_vars();
  if ($xml_vars) {
    foreach($xml_vars->query('/sl-playerss/var') as $var_node) {
      $name = $var_node->getAttribute('name');
      if (get_option('sl-playerss-'.$name) == null) {
        $default = $var_node->getAttribute('default');
        add_option('sl-playerss-'.$name, $default);
      }
    }
  }
}

/**
 * Sauvegarde les variables dynamiques
 * @return unknown_type
 */
function sl_playerss_update_vars() {
  $xml_vars = sl_playerss_get_xml_vars();
  if ($xml_vars) {
    foreach($xml_vars->query('/sl-playerss/var') as $var_node) {
      $name = $var_node->getAttribute('name');
      update_option('sl-playerss-'.$name, $_REQUEST['sl-playerss-'.$name]);
    }
  }
}

/**
 * Retourne les valeurs par défaut pour les variables du plugin
 * @return unknown_type
 */
function sl_playerss_default_vars() {
  $default_atts = array(
    'background' => get_option('sl-playerss-background'),
    'width' => get_option('sl-playerss-width'),
    'height' => get_option('sl-playerss-height'),
    'videourl' => '',
  );
  $xml_vars = sl_playerss_get_xml_vars();
  if ($xml_vars) {
    foreach($xml_vars->query('/sl-playerss/var') as $var_node) {
      $name = $var_node->getAttribute('name');
      $default_atts[strtolower($name)] = get_option('sl-playerss-'.$name);
    }
  }
  return $default_atts;
}

function sl_player_get_demo_url() {
  $xml_vars = sl_playerss_get_xml_vars();
  if ($xml_vars) {
    foreach($xml_vars->query('/sl-playerss') as $root_node) {
      return $root_node->getAttribute('demo-url');
    }
  }
  return '';
}

function sl_playerss_get_initvars($atts, $use_demo_url=false) {
  $output = '';
  $separator = ',';
  // Commence par l'adresse de la vidéo
  $video_url = ($use_demo_url)?sl_player_get_demo_url():$atts['videourl'];
  $output .= 'videoUrl=' . $video_url;
  // Puis ajoute toutes les variables
  $xml_vars = sl_playerss_get_xml_vars();
  if ($xml_vars) {
    foreach($xml_vars->query('/sl-playerss/var') as $var_node) {
      $name = $var_node->getAttribute('name');
      $control = $var_node->getAttribute('control');
      if ($control == 'checkbox') {
        $bool_value = ($atts[strtolower($name)]=='1')?'true':'false';
        $output .= $separator . $name .'='. $bool_value;
      } else {
        $output .= $separator . $name .'='. $atts[strtolower($name)];
      }
    }
  }
  return $output;
}

/**
 * Formatte et affiche les variables
 * @return unknown_type
 */
function sl_playerss_format_vars() {
  $xml_vars = sl_playerss_get_xml_vars();
  if ($xml_vars) {
    foreach($xml_vars->query('/sl-playerss/var') as $var_node) {
      sl_playerss_format_var ($var_node);
    }
  }
}

/**
 * Formatte et affiche les variables, dans le script JS générant le shortcode
 * @return unknown_type
 */
function sl_playerss_format_vars_tinymce() {
  $xml_vars = sl_playerss_get_xml_vars();
  if ($xml_vars) {
    $output = '';
    foreach($xml_vars->query('/sl-playerss/var') as $var_node) {
      $name = $var_node->getAttribute('name');
      $output .= "      if (form['sl-playerss-$name'].value) { \n";
      $output .= "        shortcode += separator + '$name=\"'+form['sl-playerss-$name'].value+'\"'; \n";
      $output .= "      } \n\n";
    }
    echo $output;
  }
}

/**
 * Formatte et affiche une variable donnée
 * @param $var_node
 * @return unknown_type
 */
function sl_playerss_format_var ($var_node) {
  $control = $var_node->getAttribute('control');
  if ($control == 'checkbox') {
    sl_playerss_format_var_checkbox($var_node);
  } elseif ($control == 'select') {
    sl_playerss_format_var_select($var_node);
  } elseif ($control == 'colorpicker') {
    sl_playerss_format_var_colorpicker($var_node);
  } elseif ($control == 'inputtext') {
    sl_playerss_format_var_inputtext($var_node);
  } else {
    // Par défaut
    sl_playerss_format_var_inputtext($var_node);
  }
}

/**
 * Formatte et affiche une variable avec un contrôle checkbox
 * @param $var_node
 * @return unknown_type
 */
function sl_playerss_format_var_checkbox($var_node) {
  $name = $var_node->getAttribute('name');
  $default = $var_node->getAttribute('default');
  $label = $var_node->getAttribute('label');
  $description = $var_node->getAttribute('description');
  $checked = (get_option('sl-playerss-'.$name) == '1')?' checked="checked" ':'';

  $output = '';
  $output .= '<tr>';
  $output .= '<th><label for="sl-playerss-'. $name .'">'. __($label, 'sl-playerss') .'</label></th>';
  $output .= '<td>';
  $output .= '<input type="checkbox" id="sl-playerss-'. $name .'" name="sl-playerss-'. $name .'" value="1" '. $checked .'/>';
  $output .= '<p class="help">'. __($description, 'sl-playerss') .'</p>';
  $output .= '</td>';
  $output .= '</tr>';

  echo $output;
}

/**
 * Formatte et affiche une variable avec un contrôle select
 * @param $var_node
 * @return unknown_type
 */
function sl_playerss_format_var_select($var_node) {
  $name = $var_node->getAttribute('name');
  $default = $var_node->getAttribute('default');
  $options = explode(',', $var_node->getAttribute('options')) ;
  $label = $var_node->getAttribute('label');
  $description = $var_node->getAttribute('description');

  $output = '';
  $output .= '<tr>';
  $output .= '<th><label for="sl-playerss-'. $name .'">'. __($label, 'sl-playerss') .'</label></th>';
  $output .= '<td>';
  $output .= '<select id="sl-playerss-'. $name .'" name="sl-playerss-'. $name .'">';
  foreach ($options as $option) {
    $option = trim($option);
    $selected = (get_option('sl-playerss-'.$name) == $option)?' selected="selected" ':'';
    $output .= '<option value="'. $option .'"'. $selected .'>'. __($option, 'sl-playerss') .'</option>'; // note : la localisation ici permettra si on le souhaites d'avoir un libellé différent du nom du mode
  }
  $output .= '</select>';
  $output .= '<p class="help">'. __($description, 'sl-playerss') .'</p>';
  $output .= '</td>';
  $output .= '</tr>';

  echo $output;
}

/**
 * Formatte et affiche une variable avec un contrôle input type=text
 * @param $var_node
 * @return unknown_type
 */
function sl_playerss_format_var_inputtext($var_node) {
  $name = $var_node->getAttribute('name');
  $default = $var_node->getAttribute('default');
  $label = $var_node->getAttribute('label');
  $description = $var_node->getAttribute('description');

  $output = '';
  $output .= '<tr>';
  $output .= '<th><label for="sl-playerss-'. $name .'">'. __($label, 'sl-playerss') .'</label></th>';
  $output .= '<td>';
  $output .= '<input type="text" id="sl-playerss-'. $name .'" name="sl-playerss-'. $name .'" value="'. get_option('sl-playerss-'.$name) .'" />';
  $output .= '<p class="help">'. __($description, 'sl-playerss') .'</p>';
  $output .= '</td>';
  $output .= '</tr>';

  echo $output;
}

/**
 * Formatte et affiche une variable avec un contrôle color picker
 * @param $var_node
 * @return unknown_type
 */
function sl_playerss_format_var_colorpicker($var_node) {
  $name = $var_node->getAttribute('name');
  $default = $var_node->getAttribute('default');
  $label = $var_node->getAttribute('label');
  $description = $var_node->getAttribute('description');

  $output = '';
  $output .= '<tr>';
  $output .= '<th><label for="sl-playerss-'. $name .'">'. __($label, 'sl-playerss') .'</label></th>';
  $output .= '<td>';
  $output .= '<input type="text" id="sl-playerss-'. $name .'" name="sl-playerss-'. $name .'" value="'. get_option('sl-playerss-'.$name) .'" class="jquery-colorpicker" />';
  $output .= '<p class="help">'. __($description, 'sl-playerss') .'</p>';
  $output .= '</td>';
  $output .= '</tr>';

  echo $output;
}

/**
 * Retourne le DOMXPath des variables du plugin. Si une erreur de chargement survient, retourne faux;
 * @return unknown_type
 */
function sl_playerss_get_xml_vars() {
global $sl_playerss_xml_vars_xpath;
  // Prend le xpath en cache
  if (isset($sl_playerss_xml_vars_xpath)) {
    return $sl_playerss_xml_vars_xpath;
  }
  // Prend le xpath sur disque
  $config_path = dirname(__file__).'/config.xml';
  $document = sl_playerss_load_xml($config_path);
  if ($document) {
    $sl_playerss_xml_vars_xpath = new DOMXPath($document);
    return $sl_playerss_xml_vars_xpath;
  }
  return false;
}

/**
 * Retourne le DOMDocument des variables du plugin. Si une erreur de chargement survient, retourne faux;
 * @param $path
 * @return unknown_type
 */
function sl_playerss_load_xml($path) {
global $sl_playerss_xml_vars_document;
  // Prend le document en cache
  if (isset($sl_playerss_xml_vars_document)) {
    return $sl_playerss_xml_vars_document;
  }
  // Prend le document sur disque
  libxml_use_internal_errors(true);
  $sl_playerss_xml_vars_document = new DOMDocument();

  if (!$sl_playerss_xml_vars_document->load($path)) {
    // Erreur de chargement du document, l'ignore
    libxml_clear_errors();
    libxml_use_internal_errors(false);
    return false;
  }

  libxml_use_internal_errors(false);

  return $sl_playerss_xml_vars_document;
}


function sl_playerss_parse_url_domain($url) {
  $parsed = parse_url($url);
  $hostname = $parsed['host'];
  return $hostname;
}