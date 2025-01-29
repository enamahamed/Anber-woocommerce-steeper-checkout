<?php
function get_carbon_field_options() {
    $options = array();

    $fields = carbon_get_theme_option('awc-dpo');
    if (!empty($fields)) {
        foreach ($fields as $field) {
            $options[] = array(
                'title' => $field['title'],
                'icon' => $field['icon']
            );
        }
    }

    return $options;
}

add_action('woocommerce_checkout_before_customer_details', 'add_custom_radio_field');
function add_custom_radio_field() {
    $radio_options = get_carbon_field_options();

    echo '<div id="custom-radio-field">';
    woocommerce_form_field('custom_radio_field', array(
        'type' => 'radio',
        'class' => array('form-row-wide'),
        'options' => $radio_options,
        'default' => key($radio_options), // Set the first option as default
    ), '');
    echo '</div>';
}

// Save the selected option in the session
add_action('woocommerce_checkout_update_order_review', 'save_custom_radio_field');
function save_custom_radio_field($posted_data) {
    if (isset($posted_data['custom_radio_field'])) {
        WC()->session->set('custom_radio_field', $posted_data['custom_radio_field']);
    }
}



// Save the custom radio field value to order meta
add_action('woocommerce_checkout_create_order', 'save_custom_radio_field_to_order', 10, 2);
function save_custom_radio_field_to_order($order, $data) {
    if (isset($_POST['custom_radio_field'])) {
        $order->update_meta_data('custom_radio_field', sanitize_text_field($_POST['custom_radio_field']));
    }
}

// Add custom radio field value to order admin page
add_action('woocommerce_admin_order_data_after_order_details', 'display_custom_radio_field_in_admin_order');
function display_custom_radio_field_in_admin_order($order) {
    $custom_radio_field = $order->get_meta('custom_radio_field');
    if (!empty($custom_radio_field)) {
        echo '<p><strong>' . __('Document processing options') . ':</strong> ' . esc_html($custom_radio_field) . '</p>';
    }
}

// Add custom radio field value to the order email
add_filter('woocommerce_email_order_meta_fields', 'add_custom_radio_field_to_email_order_meta', 10, 3);
function add_custom_radio_field_to_email_order_meta($fields, $sent_to_admin, $order) {
    $custom_radio_field = $order->get_meta('custom_radio_field');
    if (!empty($custom_radio_field)) {
        $fields['custom_radio_field'] = array(
            'label' => __('Document processing options'),
            'value' => esc_html($custom_radio_field),
        );
    }
    return $fields;
}

// Add custom radio field value to the order invoice
add_action('woocommerce_order_item_meta_end', 'display_custom_radio_field_in_order_invoice', 10, 4);
function display_custom_radio_field_in_order_invoice($item_id, $item, $order, $plain_text) {
    if ($custom_radio_field = $order->get_meta('custom_radio_field')) {
        echo '<p><strong>' . __('Document processing options') . ':</strong> ' . esc_html($custom_radio_field) . '</p>';
    }
}