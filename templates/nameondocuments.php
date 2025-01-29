<?php

// Save the return_shipping_address
add_action('woocommerce_checkout_create_order', 'save_name_on_documents', 10, 2);

function save_name_on_documents($order, $data) {
    if (!empty($_POST['name_of_documents'])) {
        $order->update_meta_data('_name_of_documents', sanitize_text_field($_POST['name_of_documents']));
    }
}



add_action('woocommerce_checkout_create_order_line_item', 'save_name_on_documents_to_order_item', 10, 4);

function save_name_on_documents_to_order_item($item, $cart_item_key, $values, $order) {
    if (!empty($_POST['name_of_documents'])) {
        $item->add_meta_data('_name_of_documents', sanitize_text_field($_POST['name_of_documents']));
    }
}


// Display name of documents in the admin order page
add_action('woocommerce_admin_order_data_after_billing_address', 'display_name_on_documents_fields_in_admin', 10, 1);

function display_name_on_documents_fields_in_admin($order) {
    $name_on_documents = $order->get_meta('_name_of_documents');
    if ($name_on_documents) {
        echo '<h3>' . __('Name of Documents', 'woocommerce') . '</h3>';
        echo '<p>' . esc_html($name_on_documents) . '</p>';
    }
}


// Display name of documents per item in the order
add_action('woocommerce_order_item_meta_end', 'display_name_on_documents_option_per_item', 10, 4);

function display_name_on_documents_option_per_item($item_id, $item, $order, $plain_text) {
    $name_on_documents = $item->get_meta('_name_of_documents', true);
    if (!empty($name_on_documents)) {
        echo '<p><strong>' . __('Name of Documents:', 'woocommerce') . '</strong> ' . esc_html($name_on_documents) . '</p>';
    }
}


// Display name of documents in emails
add_action('woocommerce_email_order_meta', 'display_name_of_documents_option_in_emails', 10, 3);

function display_name_of_documents_option_in_emails($order, $sent_to_admin, $plain_text) {
    $name_on_documents = $order->get_meta('_name_of_documents');
    if (!empty($name_on_documents)) {
        if ($plain_text) {
            echo __('Name of Documents:', 'woocommerce') . ' ' . esc_html($name_on_documents) . "\n";
        } else {
            echo '<p><strong>' . __('Name of Documents:', 'woocommerce') . '</strong> ' . esc_html($name_on_documents) . '</p>';
        }
    }
}


