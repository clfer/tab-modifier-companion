<?php

/**
 * @param $conf_path string path to the conf file to load
 *
 * @return array
 */
function _get_conf($conf_path) {
  $json_raw = file_get_contents($conf_path);
  $conf = json_decode($json_raw, TRUE);
  return $conf;
}

/**
 * @param $conf_path string path to the conf file to generate
 *
 * @return array an array with the generated icons path
 */
function icon_generation($conf_path) {
  $flag_icons = array(
    'EMEA' => 'flags/favicon-eu-16.png',
    'US' => 'flags/favicon-us-16.png',
  );

  $conf = _get_conf($conf_path);

  $generated_icons_list = array();

  foreach ($conf['apps'] as $app_code => $app_info) {
    $app_info_path = $conf['icons_dir'] . '/' . $app_info['original_icon'];
    //    $base_color = getpngbasecolor($app_info['original_icon']);
    $base_color = $app_info['original_color'];
    list($base_hue, $base_saturation, $base_value) = hex2hsl($base_color);

    $generated_icons_list[''][$app_code] = $app_info_path;

    foreach ($conf['environments'] as $env_code => $env_info) {

      //--- Create base layer ---
      // Load base layer
      $base_layer = imagecreatefrompng($app_info_path);
      _imagetransparency($base_layer);

      //--- Translate color ---
      list($colorize_hue, $colorize_saturation, $colorize_value) = hex2hsl($env_info['color']);
      $delta_hue = $colorize_hue - $base_hue;
      imagehue($base_layer, $delta_hue * 360);

      $env_generated_dir = "{$conf['icons_generation_dir']}/$env_code";
      $colorized_icon_path = $env_generated_dir . "/$app_code.png";

      // Create subfolders if they don't already exist
      $colorized_icon_dir = pathinfo($colorized_icon_path, PATHINFO_DIRNAME);
      if (!file_exists($colorized_icon_dir)) {
        mkdir($colorized_icon_dir, '0775', TRUE);
      }
      // Save image
      imagepng($base_layer, $colorized_icon_path);
      imagedestroy($base_layer);


      $generated_icons_list[$env_code][$app_code][''] = $colorized_icon_path;

      if (!empty($app_info['localize'])) {
        foreach ($flag_icons as $flag_code => $flag_icon) {
          $layer_icon_path = $conf['icons_dir'] . '/' . $flag_icon;

          //--- Create base layer ---
          // Load base layer
          $colorized_layer = imagecreatefrompng($colorized_icon_path);
          _imagetransparency($colorized_layer);

          //--- Merge with flag ---
          // Load and merge layer
          $flag_layer = imagecreatefrompng($layer_icon_path);
          _imagetransparency($flag_layer);
          imagecopy($colorized_layer, $flag_layer, 1, 1, 0, 0, 32, 32);
          imagedestroy($flag_layer);

          // --- Save result ---
          $localize_generated_dir = $env_generated_dir . "/$flag_code";
          $new_icon_path = $localize_generated_dir . "/$app_code.png";

          // Create subfolders if they don't already exist
          $new_icon_dir = pathinfo($new_icon_path, PATHINFO_DIRNAME);
          if (!file_exists($new_icon_dir)) {
            mkdir($new_icon_dir, '0775', TRUE);
          }

          // Save image
          imagepng($colorized_layer, $new_icon_path);
          imagedestroy($colorized_layer);

          $generated_icons_list[$env_code][$app_code][$flag_code][''] = $new_icon_path;

          // --- Create numbered icons ---
          if (!empty($app_info['multi'])) {
            for ($i = 1; $i <= 5; $i++) {

              //--- Create base layer ---
              // Load base layer
              $number_layer = imagecreatefrompng($new_icon_path);
              _imagetransparency($number_layer);


              //--- Add a number ---
              $font_path = './fonts/CONSOLAB.TTF';


              $font_size = 10;
              $bounds = imagettfbbox($font_size, 0, $font_path, $i);
              if ($bounds !== FALSE) {
                $number_width = $bounds[2] - $bounds[0];
                $number_heigth = $bounds[1] - $bounds[7];

                $bg_color = imagecolorallocatealpha($number_layer, 0, 0, 0, 63);

                $padding = 1;
                $bg_margin = 2;
                $bg_posx = 31 - $bg_margin;
                $bg_posy = $bg_margin;

                $bg_width = $number_width + 2 * $padding;
                $bg_height = $number_heigth + 2 * $padding;

                $bg_width = evenize_down($bg_width);
                $bg_height = evenize_down($bg_height);

                imagefilledrectangle($number_layer, $bg_posx, $bg_posy, $bg_posx - $bg_width, $bg_posy + $bg_height, $bg_color);

                $number_posx = $bg_posx - $number_width - $padding;
                $number_posy = $bg_posy + $number_heigth + $padding;

                $font_color = imagecolorallocate($number_layer, 255, 255, 255);
                imagettftext($number_layer, $font_size, 0, $number_posx, $number_posy, $font_color, $font_path, $i);
              }

              // --- Save result ---
              $number_icon_path = $localize_generated_dir . "/multi/$app_code-$flag_code$i.png";

              // Create subfolders if they don't already exist
              $number_icon_dir = pathinfo($number_icon_path, PATHINFO_DIRNAME);
              if (!file_exists($number_icon_dir)) {
                mkdir($number_icon_dir, '0775', TRUE);
              }

              // Save image
              imagepng($number_layer, $number_icon_path);
              imagedestroy($number_layer);

              $generated_icons_list[$env_code][$app_code][$flag_code]['numbers'][$i] = $number_icon_path;
            }
          }
        }
      }
    }
  }

  return $generated_icons_list;
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
function imagehue(&$image, $angle) {
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

      if (isset($delta_s)) {
        $s = $s * $delta_s;
      }
      if (isset($delta_l)) {
        $l = $l * $delta_l;
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
