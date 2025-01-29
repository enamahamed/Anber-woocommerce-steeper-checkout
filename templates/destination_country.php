<?php
add_action('woocommerce_checkout_update_order_meta', 'save_destination_country');
function save_destination_country($order_id) {
    if (!empty($_POST['custom_country_field'])) {
        update_post_meta($order_id, '_custom_country_field', sanitize_text_field($_POST['custom_country_field']));
    }
}

add_action('woocommerce_admin_order_data_after_billing_address', 'display_custom_country_in_admin_order', 10, 1);
function display_custom_country_in_admin_order($order) {
    $custom_country = get_post_meta($order->get_id(), '_custom_country_field', true);
    if ($custom_country) {
        echo '<p><strong>' . __('Destination:', 'woocommerce') . '</strong> ' . esc_html($custom_country) . '</p>';
    }
}

add_filter('woocommerce_email_order_meta_fields', 'add_custom_country_to_emails', 10, 3);
function add_custom_country_to_emails($fields, $sent_to_admin, $order) {
    $custom_country = get_post_meta($order->get_id(), '_custom_country_field', true);
    if ($custom_country) {
        $fields['custom_country_field'] = array(
            'label' => __('Destination Country', 'woocommerce'),
            'value' => $custom_country,
        );
    }

    return $fields;
}
add_filter('woocommerce_add_cart_item_data', 'add_custom_country_to_cart_item', 10, 2);
function add_custom_country_to_cart_item($cart_item_data, $product_id) {
    if (!empty($_POST['custom_country_field'])) {
        $cart_item_data['custom_country_field'] = sanitize_text_field($_POST['custom_country_field']);
    }
    return $cart_item_data;
}





add_action('woocommerce_email_after_order_table', 'add_destination_country_to_emails', 10, 4);

function add_destination_country_to_emails($order, $sent_to_admin, $plain_text, $email) {
    $custom_country = get_post_meta($order->get_id(), '_custom_country_field', true);
    if ($custom_country) {
        echo '<p><strong>' . __('Destination Country', 'your-text-domain') . ':</strong> ' . esc_html($custom_country) . '</p>';
    }
}

//Display Custom Data in Order Details

add_action('woocommerce_order_item_meta_end', 'display_destination_country_in_order_meta', 10, 5);

function display_destination_country_in_order_meta($item_id, $item, $order, $plain_text) {
    $custom_country = get_post_meta($order->get_id(), '_custom_country_field', true);
    if ($custom_country) {
        if ($plain_text) {
            echo "\n" . __('Document Type:', 'woocommerce') . ' ' . $custom_country . "\n";
        } else {
            echo '<p><strong>' . __('Destination Country:', 'woocommerce') . ' </strong>' . $custom_country . '</p>';
        }
    }
    
}