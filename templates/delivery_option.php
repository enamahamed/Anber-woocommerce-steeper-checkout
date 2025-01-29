<?php

function get_delivery_options_with_icons() {
    return array(
        'awc_inoffice_df' => array(
            'label' => carbon_get_theme_option('awc_inoffice_df'),
            'tag' => carbon_get_theme_option('awc_inoffice_tag'),
            'icon' => wp_get_attachment_url(carbon_get_theme_option('awc_inoffice_df_icon'))
        ),
        'awc_return_shipping' => array(
            'label' => carbon_get_theme_option('awc_return_shipping'),
            'tag' => carbon_get_theme_option('awc_return_shipping_tag'),
            'icon' => wp_get_attachment_url(carbon_get_theme_option('awc_return_shipping_icon'))
        ),
        'awc_via_email' => array(
            'label' => carbon_get_theme_option('awc_via_email'),
            'tag' => carbon_get_theme_option('awc_via_email_tag'),
            'icon' => wp_get_attachment_url(carbon_get_theme_option('awc_via_email_icon'))
        ),
    );
}

function get_delivery_options() {
    return array(
        'awc_inoffice_df' => carbon_get_theme_option('awc_inoffice_df'),
        'awc_return_shipping' => carbon_get_theme_option('awc_return_shipping'),
        'awc_via_email' => carbon_get_theme_option('awc_via_email'),
    );
}

add_filter('woocommerce_checkout_before_customer_details', 'add_delivery_radio_field');

function add_delivery_radio_field($fields) {
    $delivery_options = get_delivery_options();
    $radio_options = array();

    // Populate radio options dynamically
    foreach ($delivery_options as $key => $label) {
        $radio_options[$key] = $label;
    }

    // Add a custom radio field to the checkout form
    $fields['billing']['delivery_option'] = array(
        'type' => 'radio',
        'label' => __('Delivery Method', 'woocommerce'),
        'required' => true,
        'options' => $radio_options,
        'default' => key($radio_options), // Set the first option as the default
        'class' => array('form-row-wide'),
    );

    return $fields;
}

add_action('woocommerce_checkout_update_order_meta', 'save_delivery_option');

function save_delivery_option($order_id) {
    if (!empty($_POST['delivery_option'])) {
        $delivery_options = get_delivery_options(); // Get all delivery options
        $selected_option = sanitize_text_field($_POST['delivery_option']);

        if (isset($delivery_options[$selected_option])) {
            update_post_meta($order_id, '_delivery_method', $delivery_options[$selected_option]); // Save the label
        }
    }
}

add_action('woocommerce_order_item_meta_end', 'display_delivery_option_item', 10, 4);

function display_delivery_option_item($item_id, $item, $order, $plain_text) {
    $delivery_method = get_post_meta($order->get_id(), '_delivery_method', true);
    if (!empty($delivery_method)) {
        echo '<p><strong>' . __('Delivery method:', 'your-text-domain') . ':</strong> ' . esc_html($delivery_method) . '</p>';
    }
}


add_action('woocommerce_admin_order_data_after_order_details', 'display_delivery_option_in_admin_order');

function display_delivery_option_in_admin_order($order) {
    $delivery_method = get_post_meta($order->get_id(), '_delivery_method', true);
    if ($delivery_method) {
        echo '<p><strong>' . __('Delivery Method') . ':</strong> ' . esc_html($delivery_method) . '</p>';
    }
}

//add_filter('woocommerce_email_order_meta_fields', 'add_delivery_option_to_email_order_meta', 10, 3);
//
//function add_delivery_option_to_email_order_meta($fields, $sent_to_admin, $order) {
//    $delivery_method = get_post_meta($order->get_id(), '_delivery_method', true);
//    if ($delivery_method) {
//        $fields['delivery_method'] = array(
//            'label' => __('Delivery Method'),
//            'value' => esc_html($delivery_method),
//        );
//    }
//    return $fields;
//}



