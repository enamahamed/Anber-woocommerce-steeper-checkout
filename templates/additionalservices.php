<?php
function get_aditoinal_service_option() {
    $options = array();
    // Retrieve fields from Carbon Fields 
    $fields = carbon_get_theme_option('awc-aditoionsrv');
    if (!empty($fields)) {
        foreach ($fields as $field) {
            $options[] = array(
                'awc-aditoionsrv_title' => $field['awc-aditoionsrv_title'],
            );
        }
    }
    return $options;
}



// Save the selected option in the session
add_action('woocommerce_checkout_update_order_review', 'save_adition_service_option');

function save_adition_service_option($posted_data) {
    parse_str($posted_data, $output);
    if (isset($output['adition_service_options'])) {  // Fix the name to match the form field
        WC()->session->set('adition_service_options', sanitize_text_field($output['adition_service_options']));
    }
}

// Save the custom radio field value to order meta
add_action('woocommerce_checkout_create_order', 'save_adition_service_option_to_order', 10, 2);

function save_adition_service_option_to_order($order, $data) {
    if (!empty($_POST['adition_service_options'])) {  // Fix the name to match the form field
        $order->update_meta_data('adition_service_options', sanitize_text_field($_POST['adition_service_options']));
    }
}

// Add custom radio field value to order admin page
add_action('woocommerce_admin_order_data_after_order_details', 'display_adition_service_option_in_admin_order');

function display_adition_service_option_in_admin_order($order) {
    $adition_service_option = $order->get_meta('adition_service_options');  // Fix the meta key to match the saved data
    if (!empty($adition_service_option)) {
        echo '<p><strong>' . __('Additional Services', 'your-text-domain') . ':</strong> ' . esc_html($adition_service_option) . '</p>';
    }
}


// Add custom  value to the order invoice
add_action('woocommerce_order_item_meta_end', 'display_adition_service_option_in_order_invoice', 10, 4);

function display_adition_service_option_in_order_invoice($item_id, $item, $order, $plain_text) {
    $adition_service_option = $order->get_meta('adition_service_options');  // Fix the meta key to match the saved data
    if (!empty($adition_service_option)) {
        echo '<p><strong>' . __('Additional Services', 'your-text-domain') . ':</strong> ' . esc_html($adition_service_option) . '</p>';
    }
}

/* Language */

// Save the selected option in the session
add_action('woocommerce_checkout_update_order_meta', 'save_adition_service_which_language');

function save_adition_service_which_language($order_id) {
    if (!empty($_POST['which_language'])) {
        update_post_meta($order_id, '_which_language', sanitize_textarea_field($_POST['which_language']));
    }
}

// Save the custom radio field value to order meta
add_action('woocommerce_checkout_create_order', 'save_adition_service_which_language_to_order', 10, 2);

function save_adition_service_which_language_to_order($order, $data) {
    if (!empty($_POST['which_language'])) {  // Fix the name to match the form field
        $order->update_meta_data('which_language', sanitize_text_field($_POST['which_language']));
    }
}

add_action('woocommerce_admin_order_data_after_order_details', 'display_which_language_in_admin_order');

function display_which_language_in_admin_order($order) {
    $which_language = get_post_meta($order->get_id(), '_which_language', true);
    if (!empty($which_language)) {
        echo '<p><strong>' . __('Language', 'your-text-domain') . ':</strong> ' . esc_html($which_language) . '</p>';
    }
}


add_action('woocommerce_order_item_meta_end', 'display_which_language_in_order_items', 10, 4);

function display_which_language_in_order_items($item_id, $item, $order, $plain_text) {
    // Get the meta value from the order (wp_postmeta)
    $which_language = get_post_meta($order->get_id(), '_which_language', true);

    // Check if the value exists and display it
    if (!empty($which_language)) {
        echo '<p><strong>' . __('Language', 'your-text-domain') . ':</strong> ' . esc_html($which_language) . '</p>';
    }
}






add_action('woocommerce_email_order_meta', 'display_which_language_option_in_emails', 10, 5);

function display_which_language_option_in_emails($order, $sent_to_admin, $plain_text) {
    $which_language = get_post_meta($order->get_id(), '_which_language', true); // Retrieve order meta using WooCommerce method
    if (!empty($which_language)) {
        echo '<p><strong>' . __('Language', 'your-text-domain') . ':</strong> ' . esc_html($which_language) . '</p>';
    }
}


/* which_embassy */

// Save the selected option in the session
add_action('woocommerce_checkout_update_order_meta', 'save_adition_service_which_embassy');

function save_adition_service_which_embassy($order_id) {
    if (!empty($_POST['which_embassy'])) {
        update_post_meta($order_id, '_which_embassy', sanitize_textarea_field($_POST['which_embassy']));
    }
}


add_action('woocommerce_admin_order_data_after_order_details', 'display_which_embassy_in_admin_order');

function display_which_embassy_in_admin_order($order) {
    $which_embassy = get_post_meta($order->get_id(), '_which_embassy', true);
    if (!empty($which_embassy)) {
        echo '<p><strong>' . __('Embassy / Country', 'your-text-domain') . ':</strong> ' . esc_html($which_embassy) . '</p>';
    }
}


add_action('woocommerce_order_item_meta_end', 'display_which_embassy_option_per_item', 10, 4);

function display_which_embassy_option_per_item($item_id, $item, $order, $plain_text) {
    $which_embassy = get_post_meta($order->get_id(), '_which_embassy', true);
    if (!empty($which_embassy)) {
        echo '<p><strong>' . __('Embassy / Country', 'your-text-domain') . ':</strong> ' . esc_html($which_embassy) . '</p>';
    }
}

add_action('woocommerce_email_order_meta', 'display_which_embassy_option_in_emails', 10, 3);

function display_which_embassy_option_in_emails($order, $sent_to_admin, $plain_text) {
    $which_embassy = get_post_meta($order->get_id(), '_which_embassy', true);
    if (!empty($which_embassy)) {
        echo '<p><strong>' . __('Embassy / Country', 'your-text-domain') . ':</strong> ' . esc_html($which_embassy) . '</p>';
    }
}


