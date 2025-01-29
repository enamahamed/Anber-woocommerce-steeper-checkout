<?php
defined('ABSPATH') || exit;
get_header();
// Get the global order object if it's not directly passed
global $wp;
$order_id = !empty($wp->query_vars['order-received']) ? absint($wp->query_vars['order-received']) : 0;

// Get the order details
if ($order_id) {
    $order = wc_get_order($order_id);

    if ($order) {
        $order_id = $order->get_id();
        ?>
        <div class="custom-thank-you-page-wrapper">
            <div class="header-wrap">
                <div class="icon">
                    <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="26" cy="26" r="24.5" fill="white" stroke="#2CBC63" stroke-width="3"/>
                        <path d="M15.3652 27.9953L21.1232 33.7528L36.6319 18.2441" stroke="#2CBC63" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="heading-text">
                    <h2>Thank You for Your Order!</h2>                   
                    <p>Your order has been received</p>
                </div>

            </div>
            <div class="custom-thank-you-page-body-wrap">
                <div style="width: 100%">
                    <?php
                    do_action('woocommerce_before_thankyou', $order_id);
                    ?>

                </div>
            </div>
            <div class="">
                <ul class="woocommerce-order-overview custom-thankyou-order-details order_details">

                    <li class="woocommerce-order-overview__order order">
                        <?php esc_html_e('Order number:', 'woocommerce'); ?>
                        <strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?></strong>
                    </li>

                    <li class="woocommerce-order-overview__date date">
                        <?php esc_html_e('Date:', 'woocommerce'); ?>
                        <strong><?php echo wc_format_datetime($order->get_date_created()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?></strong>
                    </li>

                    <?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
                        <li class="woocommerce-order-overview__email email">
                            <?php esc_html_e('Email:', 'woocommerce'); ?>
                            <strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?></strong>
                        </li>
                    <?php endif; ?>

                    <li class="woocommerce-order-overview__total total">
                        <?php esc_html_e('Total:', 'woocommerce'); ?>
                        <strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?></strong>
                    </li>

                    <?php if ($order->get_payment_method_title()) : ?>
                        <li class="woocommerce-order-overview__payment-method method">
                            <?php esc_html_e('Payment method:', 'woocommerce'); ?>
                            <strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
            <div class="custom-thank-you-page-order-detail">
                <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

            </div>

            <!-- Add additional details as needed -->
            <div class="continew">
                <a href="/shop">Continue shoping</a>
            </div>
        </div>
        <?php //do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );  ?>

        <?php
    } else {
        ?>
        <p>Order not found. Please contact support.</p>
        <?php
    }
} else {
    ?>
    <p>Something went wrong. Please contact support.</p>
    <?php
}

get_footer();
?>
