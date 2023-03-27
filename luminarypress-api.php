<?php
// Add action for rest_api_init hook
add_action('rest_api_init', 'luminarypress_register_custom_api_endpoints');

// Register custom API endpoints function
function luminarypress_register_custom_api_endpoints() {
  // Register the widgets endpoint
  register_rest_route('luminarypress/v1', '/widgets', array(
    'methods' => 'GET',
    'callback' => 'luminarypress_get_available_widgets',
  ));

  // Register the shortcodes endpoint
  register_rest_route('luminarypress/v1', '/shortcodes', array(
    'methods' => 'GET',
    'callback' => 'luminarypress_get_registered_shortcodes',
  ));
}

// Callback function for fetching available widgets
function luminarypress_get_available_widgets() {
  global $wp_widget_factory;

  $available_widgets = array();
  foreach ($wp_widget_factory->widgets as $widget) {
    $available_widgets[] = array(
      'name' => $widget->name,
      'id_base' => $widget->id_base,
      'option_name' => $widget->option_name,
      'control_options' => $widget->control_options,
    );
  }

  return new WP_REST_Response($available_widgets, 200);
}

// Callback function for fetching registered shortcodes
function luminarypress_get_registered_shortcodes() {
  global $shortcode_tags;

  $registered_shortcodes = array();
  foreach ($shortcode_tags as $shortcode_tag => $shortcode_function) {
    $registered_shortcodes[] = $shortcode_tag;
  }

  return new WP_REST_Response($registered_shortcodes, 200);
}
