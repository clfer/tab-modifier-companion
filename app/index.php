<?php
require_once 'icon_generation.php';
?>
    <style>
        table {
            text-align: center;
            width: 100%;
        }

        table, tr, td {
            border-collapse: collapse;
            padding: 0;
        }

        td {
            border: 1px solid black;
        }

    </style>

<?php


try {
  $icons = icon_generation();
  print _print_table($icons, TRUE);

} catch (Exception $e) {
  ?>
    <h2>Fail...</h2>

    <p>Exception: <?php print $e->getMessage() ?></p>
    <pre><?php print $e->getTraceAsString() ?></pre>
  <?php
}

/**
 * @param $values
 * @param bool $with_header
 *
 * @return string
 */
function _print_table($values, $with_header = FALSE) {
  $return = '';
  if (is_array($values)) {
    $return .= '<table>';

    $total_count = 1;
    foreach ($values as $row) {
      $count = 1;

      if (is_array($row)) {
        $count = count($row);
      }

      $total_count *= $count;
    }

    if ($with_header) {
      $headers = array_keys(reset($values));
      $colspan = $total_count / count($headers);

      $return .= '<thead><tr>';
      $return .= '<td></td>';
      foreach ($headers as $header) {
        $return .= "<td colspan=\"$colspan\">$header</td>";
      }
      $return .= '</tr></thead>';
    }
    $return .= '<tbody>';

    foreach ($values as $value_label => $row) {
      $return .= _print_table_row($value_label, $row, $total_count, $with_header);
    }

    $return .= '</tbody>';
    $return .= '</table>';
  }

  else {
    $return .= _print_icon($values);
  }

  return $return;
}

/**
 * @param $label
 * @param $values
 * @param $total_colspan
 * @param bool $with_headers
 *
 * @return string
 */
function _print_table_row($label, $values, $total_colspan, $with_headers = FALSE) {
  $return = '<tr>';
  $return .= '<td>' . $label . '</td>';

  if (is_array($values)) {

    $subarrays = array_filter($values, 'is_array');
    if (!empty($subarrays) && !$with_headers) {

      $return .= '<td colspan="' . $total_colspan . '">';
      $return .= _print_table($values);
      $return .= '</td>';
    }
    else {

      $colspan = $total_colspan / count($values);
      foreach ($values as $value) {
        $return .= '<td colspan="' . $colspan . '">';
        if (is_array($value)) {
          $return .= _print_table($value);
        }
        else {
          $return .= _print_icon($value);
        }
        $return .= '</td>';
      }
    }
  }
  else {
    $return .= '<td colspan="' . $total_colspan . '">';
    $return .= _print_icon($values);
    $return .= '</td>';
  }
  $return .= '</tr>';

  return $return;
}

function _print_icon($icon_path) {
  return '<img src="' . $icon_path . '"/>';
}