<?
// Save custom fields to order meta
add_action('woocommerce_checkout_update_order_meta', 'save_custom_delivery_address_fields');
function save_custom_delivery_address_fields($order_id) {
    if (!empty($_POST['delivery_address'])) {
        update_post_meta($order_id, '_delivery_address', sanitize_text_field($_POST['delivery_address']));
    }
    if (!empty($_POST['estimated_delivery_date'])) {
        update_post_meta($order_id, '_estimated_delivery_date', sanitize_text_field($_POST['estimated_delivery_date']));
    }
    // Debugging
    error_log('Custom fields saved for Order ID: ' . $order_id);
}



// Add custom radio field value to order admin page
add_action('woocommerce_admin_order_data_after_order_details', 'display_delivery_address_option_in_admin_order');

function display_delivery_address_option_in_admin_order($order) {
   $delivery_address = get_post_meta($order->get_id(), '_delivery_address', true);
    $estimated_date = get_post_meta($order->get_id(), '_estimated_delivery_date', true);

    if ($delivery_address) {
        echo '<p><strong>' . __('Delivery address', 'woocommerce') . ':</strong> ' . esc_html($delivery_address) . '</p>';
    }
    if ($estimated_date) {
        echo '<p><strong>' . __('Estimated delivery date', 'woocommerce') . ':</strong> ' . esc_html($estimated_date) . '</p>';
    }
}

/* Estimated delivery date */

add_action('woocommerce_checkout_update_order_meta', 'save_adition_service_estimated_delivery_date');

function save_adition_service_estimated_delivery_date($order_id) {
    if (!empty($_POST['estimated_delivery_date'])) {
        update_post_meta($order_id, '_estimated_delivery_date', sanitize_textarea_field($_POST['estimated_delivery_date']));
    }
}


add_action('woocommerce_admin_order_data_after_order_details', 'display_estimated_delivery_date_in_admin_order');

function display_estimated_delivery_date_in_admin_order($order) {
    $estimated_delivery_date = get_post_meta($order->get_id(), '_estimated_delivery_date', true);
    if (!empty($estimated_delivery_date)) {
        echo '<p><strong>' . __('Estimated delivery date:', 'your-text-domain') . '</strong> ' . esc_html($estimated_delivery_date) . '</p>';
    }
}


add_action('woocommerce_order_item_meta_end', 'display_estimated_delivery_date_option_per_item', 10, 4);

function display_estimated_delivery_date_option_per_item($item_id, $item, $order, $plain_text) {
    $estimated_delivery_date = get_post_meta($order->get_id(), '_estimated_delivery_date', true);
    if (!empty($estimated_delivery_date)) {
        echo '<p><strong>' . __('Estimated delivery date:', 'your-text-domain') . '</strong> ' . esc_html($estimated_delivery_date) . '</p>';
    }
}

//add_action('woocommerce_email_order_meta', 'display_estimated_delivery_date_option_in_emails', 10, 3);
//
//function display_estimated_delivery_date_option_in_emails($order, $sent_to_admin, $plain_text) {
//    $estimated_delivery_date = get_post_meta($order->get_id(), '_estimated_delivery_date', true);
//    if (!empty($estimated_delivery_date)) {
//        echo '<p><strong>' . __('Estimated delivery date:', 'your-text-domain') . ':</strong> ' . esc_html($estimated_delivery_date) . '</p>';
//    }
//}
