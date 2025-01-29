<?php

/**
 * Plugin Name:Anber wo checkout
 * Description: A custom plugin 
 * Version: 1.0
 * Author: Your Name
 */
// Ensure Carbon Fields is loaded
if (!class_exists('Carbon_Fields\Carbon_Fields')) {
    if (file_exists(plugin_dir_path(__FILE__) . 'vendor/autoload.php')) {
        require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
    } else {
        wp_die('Carbon Fields dependency not found. Please install it using Composer.');
    }
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Initialize Carbon Fields
add_action('after_setup_theme', 'my_plugin_init_carbon_fields');

function my_plugin_init_carbon_fields() {
    \Carbon_Fields\Carbon_Fields::boot();
}

add_action('carbon_fields_register_fields', 'my_plugin_add_carbon_fields');

function my_plugin_add_carbon_fields() {
    // Create a container for a post's metadata
    Container::make('post_meta', 'Discount')
            ->where('post_type', '=', 'product')
            ->add_fields(array(
                Field::make('text', 'awcpd_discount_item', 'Quantity threshold for discount')->set_width(30),
                Field::make('text', 'awcpd_asppatt', __('Discount amount per product above the threshold '))->set_width(70)
            ))
    ;

    // Create a container for theme options
    Container::make('theme_options', 'Anber Checkout Setting')
            ->set_layout('tabbed-vertical')
            ->add_tab(__('Document Types'), array(
                Field::make('text', 'awc_dpo_sectitle', 'Section Title'),
                Field::make('complex', 'awc-dpo', __('Document Type Items'))
                ->set_layout('tabbed-vertical')
                ->add_fields(array(
                    Field::make('image', 'icon', 'Icon')->set_width(20),
                    Field::make('text', 'title', 'Title')->set_width(80),
                )),
                Field::make('text', 'awc_docnumber_text', 'Title for Number of documents'),
            ))
            ->add_tab(__('Service'), array(
                Field::make('text', 'awc_so_sectitle', 'Section Title'),
                Field::make('complex', 'awc-so', __('Service option'))
                ->set_layout('tabbed-vertical')
                ->add_fields(array(
                    Field::make('image', 'awc_so_icon', 'Icon')->set_width(20),
                    Field::make('text', 'awc_so_type', 'Type')->set_width(20),
                    Field::make('text', 'awc_so_title', 'Title')->set_width(40),
                    Field::make('text', 'awc_so_price', 'Price')->set_width(20),
                )),
                Field::make('text', 'awc-so_note', __('Service Note')),
                Field::make('complex', 'awc-aditoionsrv', __('Additional Services'))
                ->set_layout('tabbed-vertical')
                ->add_fields(array(
                    Field::make('text', 'awc-aditoionsrv_title', 'Title')->set_width(30),
                )),
            ))
            ->add_tab(__('Delivery'), array(
                Field::make('text', 'awc_inoffice_df', 'In-Office Drop-Off Title')->set_width(50),
                Field::make('text', 'awc_inoffice_tag', 'Tag')->set_width(30),
                Field::make('image', 'awc_inoffice_df_icon', 'In-Office Drop-Off Icon')->set_width(20),
                Field::make('text', 'awc_inoffice_df_offaddress', 'Office Address')->set_width(100),
                Field::make('text', 'awc_return_shipping', 'Return Shipping')->set_width(50),
                Field::make('text', 'awc_return_shipping_tag', 'Tag')->set_width(30),
                Field::make('image', 'awc_return_shipping_icon', 'Return Shipping Icon')->set_width(20),
                Field::make('text', 'awc_via_email', 'Via Email')->set_width(50),
                Field::make('text', 'awc_via_email_tag', 'Tag')->set_width(30),
                Field::make('image', 'awc_via_email_icon', 'Via Email Icon')->set_width(20),
            ))
            ->add_tab(__('Returning'), array(
                Field::make('complex', 'awc_returning_op', __('Returning Option'))
                ->set_layout('tabbed-vertical')
                ->add_fields(array(
                    Field::make('text', 'awc_returning_title', 'Option Item')->set_width(60),
                    Field::make('text', 'awc_returning_price', 'Price')->set_width(20),
                ))
            ))
            ->add_tab(__('Name on documents'), array(
                Field::make('text', 'nod_title_sec', 'Section Title'),
                Field::make('text', 'nod_title_tooltip', 'Tooltip'),
            ))
            ->add_tab(__('Terms and Conditions'), array(
                Field::make('textarea', 'tc_content', 'Message'),
    ));
}

require_once( plugin_dir_path(__FILE__) . 'block/anber-custom-cackout-block.php' );

/* Woocommerch Checkout Modification */

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'wsc_enqueue_assets');

function wsc_enqueue_assets() {
    if (is_checkout()) {
        wp_enqueue_style('wsc-stepper-style', plugin_dir_url(__FILE__) . 'assets/css/stepper.css');
        wp_enqueue_style('wsc-datepick', 'https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css');

        wp_enqueue_script('wsc-stepper-script', plugin_dir_url(__FILE__) . 'assets/js/stepper.js', array('jquery'), null, true);
        wp_enqueue_script('wsc-datepicker', 'https://code.jquery.com/ui/1.14.1/jquery-ui.js', array('jquery'), null, true);
        wp_localize_script('wsc-stepper-script', 'wc_params', [
            'currency_symbol' => get_woocommerce_currency_symbol(),
        ]);
    }
}

// Override WooCommerce checkout template
function wsc_override_checkout_template($template, $template_name, $template_path) {
    if ($template_name === 'checkout/form-checkout.php') {
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/form-checkout.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}

add_filter('woocommerce_locate_template', 'wsc_override_checkout_template', 10, 3);

/* test */

require_once( plugin_dir_path(__FILE__) . 'templates/dpo.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/service-option.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/number_of_documents.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/destination_country.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/additionalservices.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/delivery_option.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/delivery_address.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/returning.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/remove-posible-field.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/nameondocuments.php' );
require_once( plugin_dir_path(__FILE__) . 'templates/comment_instructions.php' );

/* chackuot customize */
add_action('woocommerce_checkout_order_review', 'reposition_order_review_sections', 1);

function reposition_order_review_sections() {
    // Remove default rendering of payment method
    remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
    remove_action('woocommerce_checkout_payment', 'woocommerce_checkout_payment');
    // Add custom hooks for order review and payment method
    add_action('woocommerce_order_review_section', 'woocommerce_order_review', 10);
    add_action('woocommerce_payment_section', 'woocommerce_checkout_payment', 10);
    add_action('woocommerce_checkout_payment', 'woocommerce_checkout_payment');
}

// Hook payment methods to another location
add_action('woocommerce_after_checkout_form', 'custom_payment_section', 20);

function custom_payment_section() {
    echo '<div class="custom-payment-methods">';
    do_action('woocommerce_payment_section');
    echo '</div>';
}

/* Service price */

// Add the service price to the order total
add_action('woocommerce_cart_calculate_fees', 'add_service_price_to_order_total');

function add_service_price_to_order_total($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    // Check if the required POST fields are set
    if (!empty($_POST['custom_service_options']) && !empty($_POST['number_of_documents'])) {
        // Get service options
        $service_options = get_service_option();
        $selected_option = sanitize_text_field($_POST['custom_service_options']);
        $document_quantity = absint($_POST['number_of_documents']);

        // Find the selected service price
        $selected_service_price = 0;
        foreach ($service_options as $option) {
            if ($option['awc_so_type'] === $selected_option) {
                $selected_service_price = floatval($option['awc_so_price']);
                break;
            }
        }

        // Calculate additional fees based on the quantity
        $total_service_price = $selected_service_price * $document_quantity;
        $regular_price = WC()->cart->get_cart_contents_total();

        if ($total_service_price > $regular_price) {
            $additional_price = $total_service_price - $regular_price;
            $cart->add_fee(__('Service Option Fee'), $additional_price);
        }
    }
}

/* discount */
add_action('woocommerce_cart_calculate_fees', 'apply_quantity_based_discount');

function apply_quantity_based_discount() {
    // Ensure the cart is not empty
    if (WC()->cart->is_empty()) {
        return;
    }

    $total_discount = 0; // Initialize total discount
    // Loop through all items in the cart
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product_id = $cart_item['product_id']; // Get the product ID
        $quantity = $cart_item['quantity']; // Get the quantity of this product in the cart
        // Fetch the custom meta fields for the product
        $quantity_threshold = get_post_meta($product_id, '_awcpd_discount_item', true);
        $discount_per_product = get_post_meta($product_id, '_awcpd_asppatt', true);

        // Check if meta values are valid
        if (!is_numeric($quantity_threshold) || !is_numeric($discount_per_product)) {
            continue; // Skip this product if no valid meta fields are set
        }

        // Convert meta values to proper types
        $quantity_threshold = (int) $quantity_threshold;
        $discount_per_product = (float) $discount_per_product;

        // Debugging output to log if needed
        error_log("Product ID: $product_id, Quantity: $quantity, Threshold: $quantity_threshold, Discount: $discount_per_product");

        // Check if the quantity exceeds the threshold and apply the discount
        if ($quantity > $quantity_threshold) {
            // Calculate discount for this item
            $excess_quantity = $quantity - $quantity_threshold;
            $item_discount = $excess_quantity * $discount_per_product; // Discount based on excess quantity

            $total_discount += $item_discount; // Add this item's discount to the total
        }
    }

    // Apply the discount if applicable
    if ($total_discount > 0) {
        WC()->cart->add_fee(__('Quantity Discount', 'your-text-domain'), -$total_discount);
    }
}

// Override the WooCommerce Thank You page template
add_filter('woocommerce_locate_template', 'my_custom_thankyou_page', 10, 3);

function my_custom_thankyou_page($template, $template_name, $template_path) {
    // Debugging: Log or print the template being loaded
    error_log("Template Name: $template_name");
    error_log("Template Path: $template");

    if ('checkout/thankyou.php' === $template_name) {
        $plugin_path = plugin_dir_path(__FILE__) . 'templates/checkout/thankyou.php';

        // Debugging: Log custom template path
        error_log("Using Custom Template: $plugin_path");

        if (file_exists($plugin_path)) {
            return $plugin_path;
        }
    }

    return $template;
}

add_filter('woocommerce_template_loader_files', 'my_plugin_add_template_path');

function my_plugin_add_template_path($paths) {
    $paths[] = plugin_dir_path(__FILE__) . 'templates/';
    return $paths;
}

add_filter('template_include', 'override_thankyou_template', 99);

function override_thankyou_template($template) {
    if (is_checkout() && is_wc_endpoint_url('order-received')) {
        $custom_template = plugin_dir_path(__FILE__) . 'templates/checkout/thankyou.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }

    return $template;
}

add_action('woocommerce_before_thankyou', 'display_custom_thank_you_map', 10, 1);
function display_custom_thank_you_map($order_id) {
    $order = wc_get_order($order_id);

    // Retrieve the return address fields from the order meta
    $return_address_line = $order->get_meta('_return_address_line');
    $rtn_address_street = $order->get_meta('_rtn_address_street');
    $return_address_city = $order->get_meta('_return_address_city');
    $return_address_post = $order->get_meta('_return_address_post');
    $return_address_country = $order->get_meta('_return_address_country');

    // Combine the address fields into a single address string
    $full_address = $return_address_line . ' ' . $rtn_address_street . ', ' . $return_address_city . ' ' . $return_address_post . ', ' . $return_address_country;

    // Check if any address field is not empty
    //if (!empty($return_address_line) || !empty($return_address_city) || !empty($return_address_post) || !empty($return_address_country)) {
    // Generate the Google Maps URL
    $google_maps_url = 'https://maps.google.com/maps?width=100%25&height=600&hl=en&q=' . urlencode($full_address) . '&t=&z=14&ie=UTF8&iwloc=B&output=embed';

    // Display the Google Map iframe
    echo '<div class="custom-thank-you-page-body-wrap">';
    echo '<div style="width: 100%">';
    echo '<iframe width="100%" height="180" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' . esc_url($google_maps_url) . '"></iframe>';
    echo '</div>';
    echo '</div>';
   // }
}

// Hook the function to an action that occurs before the order details

