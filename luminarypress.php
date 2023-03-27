<?php
/*
Plugin Name: LuminaryPress
Description: As modern and reusable visual builder for WordPress using React.
Version: 1.0
Author: Your Name
*/

// Include API endpoints file
require_once plugin_dir_path(__FILE__) . 'luminarypress-api.php';

// Enqueue the scripts and styles for the visual builder
// Enqueue the scripts and styles for the visual builder
function luminary_press_enqueue_scripts() {
    // Load scripts and styles for the frontend
    if (!is_admin()) {
        wp_enqueue_script(
            'luminary-press-script',
            plugins_url('build/static/js/main.js', __FILE__),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'build/static/js/main.js'),
            true
        );

        wp_localize_script('luminary-press-script', 'wpApiSettings', array(
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest')
        ));

        wp_enqueue_style(
            'luminary-press-style',
            plugins_url('build/static/css/main.css', __FILE__),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'build/static/css/main.css')
        );
    }

    // Load scripts and styles for the backend
    if (is_admin()) {
        wp_enqueue_script(
            'luminary-press-script-admin',
            plugins_url('build/static/js/main.js', __FILE__),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'build/static/js/main.js'),
            true
        );

        wp_enqueue_style(
            'luminary-press-style-admin',
            plugins_url('build/static/css/main.css', __FILE__),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'build/static/css/main.css')
        );

        wp_localize_script('luminary-press-script-admin', 'wpApiSettings', array(
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest')
        ));
    }
}


add_action('wp_enqueue_scripts', 'luminary_press_enqueue_scripts');
add_action('admin_enqueue_scripts', 'luminary_press_enqueue_scripts');

function luminary_press_admin_bar()
{
    global $wp_admin_bar;

    // Get the post ID of the current page
    $post_id = get_the_ID();

    if (current_user_can('edit_posts')) {
        $args = array(
            'id' => 'luminary-press',
            'title' => 'Visual Builder',
            'href' => admin_url('admin.php?page=luminary-press&post=' . $post_id),
            'meta' => array(
                'class' => 'luminary-press-button',



            ),
        );
        $wp_admin_bar->add_node($args);
    }
}

add_action('admin_bar_menu', 'luminary_press_admin_bar', 999);


// Add a custom admin menu for the visual builder
function luminary_press_admin_menu()
{
    add_menu_page(
        'LuminaryPress',
        'LuminaryPress',
        'manage_options',
        'luminary-press',
        'luminary_press_admin_page',
        'dashicons-admin-generic',
        100
    );
}

add_action('admin_menu', 'luminary_press_admin_menu');

function luminary_press_admin_page()
{
    $post_id = 0;
    if (isset($_GET['post']) && is_numeric($_GET['post'])) {
        $post_id = intval($_GET['post']);
    }
 
?>
    <div class="wrap">
        <h1 class="wp-heading-inline">LuminaryPress Visual Builder</h1>
        <hr class="wp-header-end">
        <div id="root" data-post-id="<?php echo $post_id; ?>"></div>
    </div>
<?php
}
