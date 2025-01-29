<?php

// Save the return_shipping_address
add_action('woocommerce_checkout_create_order', 'save_comment_instructions', 10, 2);

function save_comment_instructions($order, $data) {
    if (!empty($_POST['comment_instructions'])) {
        $order->update_meta_data('_comment_instructions', sanitize_text_field($_POST['comment_instructions']));
    }
}



add_action('woocommerce_checkout_create_order_line_item', 'save_comment_instructions_to_order_item', 10, 4);

function save_comment_instructions_to_order_item($item, $cart_item_key, $values, $order) {
    if (!empty($_POST['comment_instructions'])) {
        $item->add_meta_data('_comment_instructions', sanitize_text_field($_POST['comment_instructions']));
    }
}


// Display name of documents in the admin order page
add_action('woocommerce_admin_order_data_after_billing_address', 'display_comment_instructions_fields_in_admin', 10, 1);

function display_comment_instructions_fields_in_admin($order) {
    $comment_instructions = $order->get_meta('_comment_instructions');
    if ($comment_instructions) {
        echo '<h3>' . __('Comment/instructions', 'woocommerce') . '</h3>';
        echo '<p>' . esc_html($comment_instructions) . '</p>';
    }
}


// Display name of documents per item in the order
add_action('woocommerce_order_item_meta_end', 'display_comment_instructions_option_per_item', 10, 4);

function display_comment_instructions_option_per_item($item_id, $item, $order, $plain_text) {
    $comment_instructions = $item->get_meta('_comment_instructions', true);
    if (!empty($comment_instructions)) {
        echo '<p><strong>' . __('Comment/instructions:', 'woocommerce') . '</strong> ' . esc_html($comment_instructions) . '</p>';
    }
}


// Display name of documents in emails
add_action('woocommerce_email_order_meta', 'display_comment_instructions_option_in_emails', 10, 3);

function display_comment_instructions_option_in_emails($order, $sent_to_admin, $plain_text) {
    $comment_instructions = $order->get_meta('_comment_instructions');
    if (!empty($comment_instructions)) {
        if ($plain_text) {
            echo __('Comment/instructions:', 'woocommerce') . ' ' . esc_html($comment_instructions) . "\n";
        } else {
            echo '<p><strong>' . __('Comment/instructions:', 'woocommerce') . '</strong> ' . esc_html($comment_instructions) . '</p>';
        }
    }
}


