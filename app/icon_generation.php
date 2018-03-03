<?php
require_once 'vendor/autoload.php';
use ColorThief\ColorThief;

/**
 * @param $conf_path string path to the conf file to load
 *
 * @return array
 */
function _load_conf($conf_path) {
  global $conf;
  $json_raw = file_get_contents($conf_path);
  $conf = json_decode($json_raw, TRUE);
  return $conf;
}

/**
 * @param $conf_path string path to the conf file to generate
 *
 * @return array an array with the generated icons path
 */
function icon_generation($conf_path, $keep = FALSE) {
  _load_conf($conf_path);

  global $conf;

  $generated_icons_list = array();

  $icons_generation_dir = $conf['icons_generation_dir'];

  if (!$keep) {
    _delete_dir($icons_generation_dir);
  }

  foreach ($conf['apps'] as $app_code => $app_info) {
    $app_info_path = $conf['icons_dir'] . '/' . $app_info['original_icon'];
    list($r,$g,$b) = ColorThief::getColor($app_info_path, 1);
    list($base_hue, $base_saturation, $base_value) = rgb2hsl($r,$g,$b);

    $generated_icons_list[''][$app_code] = $app_info_path;

    foreach ($conf['environments'] as $env_code => $env_info) {

      //--- Create base layer ---
      // Load base layer
      $base_layer = imagecreatefrompng($app_info_path);
      _imagetransparency($base_layer);

      //--- Translate color ---
      list($colorize_hue, $colorize_saturation, $colorize_value) = hex2hsl($env_info['color']);
      $delta_hue = $colorize_hue - $base_hue;
      $delta_value = $colorize_value - $base_value;
      imagehue($base_layer, $delta_hue * 360, NULL ,$delta_value);

      $env_generated_dir = "$icons_generation_dir/$env_code/$app_code";

      // Save image
      $colorized_icon_path = imagepng_save($base_layer, $env_generated_dir, $app_code);

      $generated_icons_list[$env_code][$app_code][''] = $colorized_icon_path;

      if (!empty($app_info['variations'])) {
        foreach ($app_info['variations'] as $app_variation_label => $app_variation) {
          $new_icons_path = imagevariation_apply($colorized_icon_path, $env_generated_dir, $app_variation_label, $app_variation);

          if (!empty($new_icons_path)) {
            if (!isset($generated_icons_list[$env_code][$app_code]['variations'][$app_variation_label])) {
              $generated_icons_list[$env_code][$app_code]['variations'][$app_variation_label] = array();
            }

            $generated_icons_list[$env_code][$app_code]['variations'][$app_variation_label] = array_merge_recursive($generated_icons_list[$env_code][$app_code]['variations'][$app_variation_label], $new_icons_path);
          }
        }
      }
    }
  }

  return $generated_icons_list;
}

/**
 * @param $base_layer_path
 * @param $target_dir
 * @param $variation_label
 * @param $variation
 *
 * @return array|FALSE
 */
function imagevariation_apply($base_layer_path, $target_dir, $variation_label, $variation, $no_subfolder = TRUE) {
  global $conf;
  $new_generated_icon_path = FALSE;

  if (!is_array($variation) && isset($conf['variations'][$variation])) {
    $variation = $conf['variations'][$variation];
  }

  if ($no_subfolder) {
    $variation_name = basename($target_dir) . '-' . $variation_label;
    $variation_target_dir = $target_dir;
  }
  else {
    $variation_name = $variation_label;
    $variation_target_dir = $target_dir . '/' . $variation_label;
  }

  if (is_array($variation)) {
    if (isset($variation['type'])) {
      $variation_type = $variation['type'];
      $options = $variation['options'];
    }
    else {
      imagesubvariation_apply($base_layer_path, $variation_label, $variation, $variation_target_dir, $new_generated_icon_path, $no_subfolder);
    }
  }

  $imagevariation_function = 'imagevariation_' . $variation_type;

  if (function_exists($imagevariation_function)) {

    $base_layer = imagecreatefrompng($base_layer_path);
    _imagetransparency($base_layer);

    call_user_func($imagevariation_function, $base_layer, $options);

    $new_icon_path = imagepng_save($base_layer, $variation_target_dir, $variation_name);
    $new_generated_icon_path[''] = $new_icon_path;

    if (!empty($variation['variations'])) {
      imagesubvariation_apply($new_icon_path, $variation_label, $variation['variations'], $variation_target_dir, $new_generated_icon_path, $no_subfolder);
    }
  }

  return $new_generated_icon_path;
}

/**
 * @param $base_layer_path
 * @param $variation_label
 * @param $subvariations
 * @param $variation_target_dir
 * @param $new_generated_icon_path
 */
function imagesubvariation_apply($base_layer_path, $variation_label, $subvariations, $variation_target_dir, &$new_generated_icon_path, $no_subfolder) {

  foreach ($subvariations as $subvariation_label => $subvariation) {
    $subvariation_name = !empty($subvariation_label) ? $variation_label . '-' . $subvariation_label : $variation_label;
    $subvariation_icon_path = imagevariation_apply($base_layer_path, $variation_target_dir, $subvariation_name, $subvariation, $no_subfolder);
    $subvariation_key = $subvariation_label;
    $i = 1;
    while (isset($new_generated_icon_path[$subvariation_key])) {
      $subvariation_key = $subvariation_label . '(' . $i++ . ')';
    }
    $new_generated_icon_path[$subvariation_key] = $subvariation_icon_path;
  }
}

/**
 * @param $base_layer resource the png image to apply the text on
 * @param array $options
 *  array(
 *   'new_layer_png_path' => string (required)
 *   'position' => 'top-left'|'top-right'|'bottom-left'|'bottom-right',
 *   'margin' => int
 *  )
 *
 * @see \imagecreatefrompng()
 */
function imagevariation_merge($base_layer, $options = array()) {
  $options += array(
    'position' => 'top-left',
    'margin' => 0,
  );

  $base_layer_width = imagesx($base_layer);
  $base_layer_height = imagesy($base_layer);

  //--- Merge with new layer ---
  // Load and merge layer
  $new_layer = imagecreatefrompng($options['path']);
  _imagetransparency($new_layer);

  list($x, $y) = _get_position($options['position'], array(
    'margin' => $options['margin'],
    'width' => imagesx($new_layer),
    'height' => imagesy($new_layer),
    'total_width' => $base_layer_width,
    'total_height' => $base_layer_height,
  ));

  imagecopy($base_layer, $new_layer, $x, $y, 0, 0, $base_layer_width, $base_layer_height);

  imagedestroy($new_layer);
}

/**
 * @param $base_layer resource the png image to apply the text on
 * @param array $options
 *  array(
 *   'text' => string (required)
 *   'position' => 'top-left'|'top-right'|'bottom-left'|'bottom-right',
 *   'font_color' => hex color (default: #FFFFFF)
 *   'bg_color' => hex color (default: #000000)
 *   'bg_transparency' => 0~1 (default: 0.5)
 *  )
 *
 * @see \imagecreatefrompng()
 */
function imagevariation_text($base_layer, $options = array()) {
  $options += array(
    'position' => 'top-left',
    'font_color' => '#FFFFFF',
    'bg_color' => '#000000',
    'bg_transparency' => 0.5,
    'font_path' => './fonts/CONSOLAB.TTF',
  );

  $text = $options['text'];

  $font_size = 10;
  $bounds = imagettfbbox($font_size, 0, $options['font_path'], $text);
  if ($bounds !== FALSE) {
    $number_width = $bounds[2] - $bounds[0];
    $number_heigth = $bounds[1] - $bounds[7];

    list($bg_r, $bg_g, $bg_b) = hex2rgb($options['bg_color']);
    $bg_imagecolor = imagecolorallocatealpha($base_layer, $bg_r, $bg_g, $bg_b, $options['bg_transparency'] * 127);

    $padding = 1;

    $bg_width = $number_width + 2 * $padding;
    $bg_height = $number_heigth + 2 * $padding;

    $bg_width = evenize_down($bg_width);
    $bg_height = evenize_down($bg_height);

    list($bg_posx, $bg_posy) = _get_position($options['position'], array(
      'width' => $bg_width,
      'height' => $bg_height,
    ));

    imagefilledrectangle($base_layer, $bg_posx, $bg_posy, $bg_posx + $bg_width, $bg_posy + $bg_height, $bg_imagecolor);

    $number_posx = $bg_posx + $padding;
    $number_posy = $bg_posy + $number_heigth + $padding;

    list($font_r, $font_g, $font_b) = hex2rgb($options['font_color']);
    $font_imagecolor = imagecolorallocate($base_layer, $font_r, $font_g, $font_b);

    imagettftext($base_layer, $font_size, 0, $number_posx, $number_posy, $font_imagecolor, $options['font_path'], $text);
  }
}

/**
 * @param $image resource
 * @param $target_dir string
 * @param $filename
 *
 * @param bool $destroy
 *
 * @return string
 */
function imagepng_save($image, $target_dir, $filename, $destroy = TRUE) {
  // --- Save result ---
  $png_path = $target_dir . "/$filename.png";

  // Create subfolders if they don't already exist
  if (!file_exists($target_dir)) {
    mkdir($target_dir, '0775', TRUE);
  }

  // Save image
  imagepng($image, $png_path);

  // Destroy image resource
  if ($destroy) {
    imagedestroy($image);
  }

  return $png_path;
}

/**
 * @param $position
 * @param $options
 *
 * @return array
 */
function _get_position($position, $options = array()) {
  $x = $y = 0;

  $options += array(
    'margin' => 2,
    'total_width' => 32,
    'total_height' => 32,
  );


  $x_left = $options['margin'];
  $x_right = isset($options['width']) ? ($options['total_width'] - 1) - $options['margin'] - $options['width'] : (int) $options['total_width'] / 2;
  $y_top = $options['margin'];
  $y_bottom = isset($options['height']) ? ($options['total_height'] - 1) - $options['margin'] - $options['height'] : (int) $options['total_height'] / 2;

  switch ($position) {
    case 'top-left':
      $x = $x_left;
      $y = $y_top;
      break;
    case 'top-right':
      $x = $x_right;
      $y = $y_top;
      break;
    case 'bottom-left':
      $x = $x_left;
      $y = $y_bottom;
      break;
    case 'bottom-right':
      $x = $x_right;
      $y = $y_bottom;
      break;
  };
  return array(
    $x,
    $y,
  );
}


/**
 * @param $number
 *
 * @return int
 */
function evenize_up($number) {
  return ceil($number / 2) * 2;
}

/**
 * @param $number
 *
 * @return int
 */
function evenize_down($number) {
  return ceil($number / 2) * 2;
}

function getpngbasecolor($png_path) {
  if (strpos($png_path, 'drupal') !== FALSE) {
    return '#0073BA';
  }
  else {
    return '#FFC601';
  }
}

/**
 * @param $base_layer_png
 * @param $merge_layer_png
 * @param int $dst_x
 * @param int $dst_y
 * @param int $src_x
 * @param int $src_y
 * @param null $src_w
 * @param null $src_h
 *
 * @return resource
 */
function _mergepng($base_layer_png, $merge_layer_png, $dst_x = 0, $dst_y = 0, $src_x = 0, $src_y = 0, $src_w = NULL, $src_h = NULL) {
  if (!isset($src_w) || !isset($src_h)) {
    $base_layer_imagesize = getimagesize($base_layer_png);
    if (!isset($src_w)) {
      $src_w = $base_layer_imagesize[0];
    }
    if (!isset($src_h)) {
      $src_h = $base_layer_imagesize[1];
    }
  }

  $base_layer = imagecreatefrompng($base_layer_png);
  _imagetransparency($base_layer);
  imagefilter($base_layer, IMG_FILTER_COLORIZE, 0, 255, 0);

  // Load and merge layer
  $flag_layer = imagecreatefrompng($merge_layer_png);
  _imagetransparency($flag_layer);
  imagecopy($base_layer, $flag_layer, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
  imagedestroy($flag_layer);
  return $base_layer;
}

/**
 * @param $img resource
 */
function _imagetransparency(&$img) {
  $black = imagecolorallocate($img, 0, 0, 0);
  imagecolortransparent($img, $black);
  imagealphablending($img, TRUE);
  imagesavealpha($img, TRUE);
}


/**
 * @param $hex string an hex color #000000
 *
 * @return array (R,G,B)
 */
function hex2rgb($hex) {
  return sscanf($hex, "#%02x%02x%02x");
}

/**
 * @param $r
 * @param $g
 * @param $b
 *
 * @return string an hex color #000000
 */
function rgb2hex($r, $g, $b) {
  return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * @param $hex
 *
 * @return array (H,S,L)
 */
function hex2hsl($hex) {
  list($r, $g, $b) = hex2rgb($hex);
  return rgb2hsl($r, $g, $b);
}

/**
 * @param $h
 * @param $s
 * @param $v
 *
 * @return string an hex color #000000
 */
function hsl2hex($h, $s, $v) {
  list($r, $g, $b) = hsl2rgb($h, $s, $v);
  return rgb2hex($r, $g, $b);
}


/**
 * Rotate color hue of an image
 *
 * @see https://stackoverflow.com/a/1890450
 * @resource https://stackoverflow.com/a/1890450
 *
 * @param $image resource
 * @param $angle number
 */
function imagehue(&$image, $angle, $delta_s = NULL, $delta_l = NULL) {
  if ($angle % 360 == 0) {
    return;
  }
  $width = imagesx($image);
  $height = imagesy($image);

  for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
      $rgb = imagecolorat($image, $x, $y);
      $r = ($rgb >> 16) & 0xFF;
      $g = ($rgb >> 8) & 0xFF;
      $b = $rgb & 0xFF;
      $alpha = ($rgb & 0x7F000000) >> 24;
      list($h, $s, $l) = rgb2hsl($r, $g, $b);
      $h += $angle / 360;
      if ($h > 1) {
        $h--;
      }

      if (isset($delta_s)&& $delta_s > 0) {
        $s = $s + $delta_s;
        $s = min($s, 1);
        $s = max($s, 0);
      }
      if (isset($delta_l) && $delta_l > 0 ) {
        $l = $l + $delta_l;
        $l = min($l, 1);
        $l = max($l, 0);
      }

      list($r, $g, $b) = hsl2rgb($h, $s, $l);
      imagesetpixel($image, $x, $y, imagecolorallocatealpha($image, $r, $g, $b, $alpha));
    }
  }
}

/**
 * @param $r
 * @param $g
 * @param $b
 *
 * @return array (H,S,L)
 */
function rgb2hsl($r, $g, $b) {
  $var_R = ($r / 255);
  $var_G = ($g / 255);
  $var_B = ($b / 255);

  $var_Min = min($var_R, $var_G, $var_B);
  $var_Max = max($var_R, $var_G, $var_B);
  $del_Max = $var_Max - $var_Min;

  $v = $var_Max;

  if ($del_Max == 0) {
    $h = 0;
    $s = 0;
  }
  else {
    $s = $del_Max / $var_Max;

    $del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
    $del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
    $del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;

    if ($var_R == $var_Max) {
      $h = $del_B - $del_G;
    }
    else {
      if ($var_G == $var_Max) {
        $h = (1 / 3) + $del_R - $del_B;
      }
      else {
        if ($var_B == $var_Max) {
          $h = (2 / 3) + $del_G - $del_R;
        }
      }
    }

    if ($h < 0) {
      $h++;
    }
    if ($h > 1) {
      $h--;
    }
  }

  return array(
    $h,
    $s,
    $v,
  );
}

/**
 * @param $h
 * @param $s
 * @param $v
 *
 * @return array (R,G,B)
 */
function hsl2rgb($h, $s, $v) {
  if ($s == 0) {
    $r = $g = $B = $v * 255;
  }
  else {
    $var_H = $h * 6;
    $var_i = floor($var_H);
    $var_1 = $v * (1 - $s);
    $var_2 = $v * (1 - $s * ($var_H - $var_i));
    $var_3 = $v * (1 - $s * (1 - ($var_H - $var_i)));

    if ($var_i == 0) {
      $var_R = $v;
      $var_G = $var_3;
      $var_B = $var_1;
    }
    else {
      if ($var_i == 1) {
        $var_R = $var_2;
        $var_G = $v;
        $var_B = $var_1;
      }
      else {
        if ($var_i == 2) {
          $var_R = $var_1;
          $var_G = $v;
          $var_B = $var_3;
        }
        else {
          if ($var_i == 3) {
            $var_R = $var_1;
            $var_G = $var_2;
            $var_B = $v;
          }
          else {
            if ($var_i == 4) {
              $var_R = $var_3;
              $var_G = $var_1;
              $var_B = $v;
            }
            else {
              $var_R = $v;
              $var_G = $var_1;
              $var_B = $var_2;
            }
          }
        }
      }
    }

    $r = $var_R * 255;
    $g = $var_G * 255;
    $B = $var_B * 255;
  }
  return array(
    $r,
    $g,
    $B,
  );
}

/**
 * Recursively delete a file
 *
 * @param $dir_path
 *
 * @see https://stackoverflow.com/a/3349792
 *
 */
function _delete_dir($dir_path) {
  if (is_dir($dir_path)) {
    $it = new RecursiveDirectoryIterator($dir_path, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it,
      RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
      if ($file->isDir()) {
        rmdir($file->getRealPath());
      }
      else {
        unlink($file->getRealPath());
      }
    }
    rmdir($dir_path);
  }
}