<?php
/**
 * @file
 * The primary PHP file for this theme.
 * 
 * The functions in template.php are executed on every page.
 * @see http://getlevelten.com/blog/kristin-brinner/drupal-theming-basic-primer-templatephp-and-template-files
 */


/**
 * This code is a modified copy of the function of the same name in
 * the core file modules/field/field.form.inc.  It differs only in allowing certain
 * multiple occurence elements to not have the default "add more" wrapper
 * imposed on them.
 * 
 * @param type $variables
 * @return type
 */
function bootstrap_logbooks_field_multiple_value_form($variables) {
  $element = $variables['element'];
  $output = '';
  
  $special_fields = array(
    'field_notify' => 1,
    'field_entrymakers' => 1,
  );
  
  
  if ($element['#cardinality'] > 1 || $element['#cardinality'] == FIELD_CARDINALITY_UNLIMITED) {
      $table_id = drupal_html_id($element['#field_name'] . '_values');
      $order_class = $element['#field_name'] . '-delta-order';
      $required = !empty($element['#required']) ? theme('form_required_marker', $variables) : '';

      $header = array(
        array(
          'data' => '<label>' . t('!title !required', array('!title' => $element['#title'], '!required' => $required)) . "</label>", 
          'colspan' => 2, 
          'class' => array('field-label'),
        ),
        t('Order'),
      );
      $rows = array();

      // Sort items according to '_weight' (needed when the form comes back after
      // preview or failed validation)
      $items = array();
      foreach (element_children($element) as $key) {
        if ($key === 'add_more' ) {
          $add_more_button = &$element[$key];
        }else {
          $items[] = &$element[$key];
        }
      }
      usort($items, '_field_sort_items_value_helper');
      // Add the items as table rows.
      foreach ($items as $key => $item) {
        // We just want a simple form-item div with a label and a form element 
        // inside for special fields.
        if (isset($special_fields[$element['#field_name']])){  
          unset($item['_weight']);
          $output = '<div class="form-item">';
          $output .= '<label>' . t('!title !required', array('!title' => $element['#title'], '!required' => $required)) . "</label>";
          $output .= drupal_render($item);
          $output .= $element['#description'] ? '<div class="description">' . $element['#description'] . '</div>' : '';
          $output .= '<div class="clearfix">' . drupal_render($add_more_button) . '</div>';
          $output .= '</div>';
        }else{    // Otherwise, proceed with the original Drupal code  
          $item['_weight']['#attributes']['class'] = array($order_class);
          $delta_element = drupal_render($item['_weight']);
          $cells = array(
            array(
              'data' => '',
              'class' => array('field-multiple-drag'),
            ),
            drupal_render($item),
            array(
              'data' => $delta_element,
              'class' => array('delta-order'),
            ),
          );
          $rows[] = array(
            'data' => $cells, 
            'class' => array('draggable'),
          );
        $output = '<div class="form-item">';
        $output .= theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('id' => $table_id, 'class' => array('field-multiple-table'))));
        $output .= $element['#description'] ? '<div class="description">' . $element['#description'] . '</div>' : '';
        if (! empty($add_more_button['#printed'])){
          $output .= '<div class="clearfix">' . $add_more_button['#children'] . '</div>';
        }else{
          $output .= '<div class="clearfix">' . drupal_render($add_more_button) . '</div>';
        }
        $output .= '</div>';
        drupal_add_tabledrag($table_id, 'order', 'sibling', $order_class);
      }
    }
  }else{
    foreach (element_children($element) as $key) {
      $output .= drupal_render($element[$key]);
    }
  }

  return $output;
}

/**
 * Implements hook_form_alter()
 * @see
 * @param type $form
 * @param type $form_state
 * @param type $form_id
 */
function bootstrap_logbooks_form_alter(&$form, &$form_state, $form_id) {
  
  // Suppress the "Request a new password link on the login form"
  if ($form_id == 'user_login_block') {
    $items = array();  
    $form['links'] = array('#markup' => theme('item_list', array('items' => $items)));
  }
  
  
}

/**
 * imlements theme_image()
 * 
 * Converts urls to relative and removes the itok query parameter.
 * 
 * @param type $vars
 * @return type
 */
function bootstrap_logbooks_image(&$vars) {
  
    $parts = drupal_parse_url($vars['path']);
    
    // We remove the itok from the query.
    if (isset($parts['query'][IMAGE_DERIVATIVE_TOKEN])) {
      unset($parts['query'][IMAGE_DERIVATIVE_TOKEN]);
      $vars['path'] = url($parts['path'], $parts);
    }

    // Make the URL relative rather than absolute
    if ($_SERVER['HTTPS'] == 'on') {
      $strip = 'https://' . $_SERVER['SERVER_NAME'];
    }  else {
       $strip = 'http://' . $_SERVER['SERVER_NAME'];
    }

    $vars['path'] = str_replace($strip,'', $vars['path']);

    // Invoke the core theme function
    return theme_image($vars);

  
}


function bootstrap_logbooks_preprocess_page(&$vars) {
  
  // Show vars for debugging
  //dpm($vars['node']) ;
  
  if (array_key_exists('node', $vars) && $vars['node']->type == 'logentry'){
    $vars['theme_hook_suggestions'][] = 'page__node__logentry';
    $vars['singlecolumn'] = true;
  }
  
  // Suppress the h1 title on the front page.
  if ($vars['is_front']) {
    $vars['title'] = '';
  }
  // For single column preference, the use 12 col bootstrap gridding
  if (isset($vars['user']->data['elog_page_preference']) && $vars['user']->data['elog_page_preference'] == 'one-column'){
      $vars['content_column_class'] = 'class="col-sm-12"';
      $vars['singlecolumn'] = true;
  }else{
      $vars['singlecolumn'] = false;
  }
  
   



}