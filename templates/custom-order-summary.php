<?php
// do_action('woocommerce_checkout_order_review');
add_action('woocommerce_before_checkout_form', 'custom_order_review_section', 20);

function custom_order_review_section() {
    ?>
    <div class="custom-order-summary">
        <h3 class="odsum_title">Order Summary</h3>
        <?php
        $cart_items = WC()->cart->get_cart();
       
        $subtotal_amount = 0; // Initialize subtotal amount
        foreach ($cart_items as $cart_item) {
            $product = $cart_item['data'];
           // var_dump($product);
            $product_image = $product->get_image('thumbnail');
            $product_name = $product->get_name();
            $quantity = $cart_item['quantity'];
            $price_per_unit = wc_price($product->get_price());
            $subtotal = wc_price($product->get_price() * $quantity);
            $subtotal_amount += $product->get_price() * $quantity; // Add to subtotal amount

            $doc_type = isset($cart_item['custom_variation_label']) ? $cart_item['custom_variation_label'] : '';
            $service_option = isset($cart_item['variation']['attribute_service-option']) ? $cart_item['variation']['attribute_service-option'] : '';
            $awcpd_dpo = isset($cart_item['awcpd_dpo']) ? $cart_item['awcpd_dpo'] : '';
            ?>
            <div class="product-summary">
                <div class="product-image"><?php echo $product_image; ?></div>
                <div class="product-details">
                    <div class="product-name-qnt">
                        <span class="product-name"><?php echo $product_name; ?></span>
                        <span class="product-quantity"><?php echo $quantity; ?> X <?php echo $price_per_unit; ?></span>
                        <div class="dpo-textincart">
                            <?php if (!empty($awcpd_dpo)): ?> 
                                <?php echo $awcpd_dpo; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <span class="product-subtotal"><?php echo $subtotal; ?></span>
                </div>
            </div>

            <div class="product_info">
                <?php if (!empty($doc_type)): ?> 
                    <div class="order-info"><span class="bold">Type: </span> <span><?php echo $doc_type; ?></span> - <span><?php echo $service_option; ?></span></div>
                <?php endif; ?>               
                <div class="order-info-full"> <span class="cart-list" id="country_display"></span></div>
                <div class="order-info-full"> <span class="cart-list" id="adserv_display"></span></div>
                <div class="order-info-full"> <span class="cart-list" id="language"></span></div>
                <div class="order-info-full"> <span class="cart-list" id="embassy"></span></div>
                <div class="order-info-full"> <span class="cart-list" id="dvmeth"></span></div>
                <div class="order-info-full"> <span class="cart-list" id="edDate"></span></div>    
            </div>
            <?php
        }

        // Get discount and total amounts
        $fees = WC()->cart->get_fees();
        $discount = 0;

        foreach ($fees as $fee) {
            if ($fee->id === 'quantity-discount') {
                $discount += $fee->amount;
            }
        }

        //$total = WC()->cart->get_cart_contents_total() + $discount;
        $total = $subtotal_amount + $discount;
      //  var_dump($total);
        ?>
        <div class="order-totals">
            <span class="order-subtotal"><span class="bold">Subtotal:</span> <?php echo wc_price($subtotal_amount); ?></span>

            <!-- Display discount if it exists -->
            <?php if ($discount < 0): // Discount will be a negative fee ?>
                <span class="order-discount"><span class="bold">Discount:</span> <?php echo wc_price($discount); ?></span>
            <?php endif; ?>

            <span id="order_summary_price"></span>
        </div>
        
        <div class="order-info-full">
            <span id="returning_documents_display"></span>
        </div>
        
<div class="total-wrap"> <span id="order_summary_total" class="total"><span>Total:</span> <span class="total-amount"> <?php echo wc_price($total); ?></span></span> </div>
        
    </div>
    <?php
}


custom_order_review_section();
?>         
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const returningOptions = document.querySelectorAll('input[name="returning_documents_options"]');
        const returningDisplay = document.getElementById('returning_documents_display');
        const orderSummaryTotal = document.getElementById('order_summary_total');
        const orderSummaryDiscount = document.querySelector('.order-discount');

        // Get the PHP value for discount and ensure it's passed correctly
        const discount = parseFloat('<?php echo WC()->cart->get_cart_discount_total(); ?>') || 0;

        function updateReturningDocuments() {
            const selectedOption = document.querySelector('input[name="returning_documents_options"]:checked');

            if (!selectedOption) {
                return;
            }

            const title = selectedOption.dataset.title;
            const price = parseFloat(selectedOption.dataset.price);

            returningDisplay.innerHTML = `<p class="rdcls">Returning Documents:</p><span class="cart-list"><span class="bold"> ${title} </span> - <span> ${wc_price(price)}</span>`;

            const cartTotal = parseFloat('<?php echo WC()->cart->get_cart_contents_total(); ?>') || 0;
            const newTotal = cartTotal + price - discount;

            //orderSummaryTotal.innerHTML = `<span>Total:</span> <span class="total-amount">${wc_price(newTotal)}</span>`;

            if (discount > 0) {
                orderSummaryDiscount.innerHTML = `<span class="bold">Discount:</span> ${wc_price(discount)}`;
            }
        }

        returningOptions.forEach(option => {
            option.addEventListener('change', updateReturningDocuments);
        });

        updateReturningDocuments();

        function wc_price(amount) {
            return `${wc_params.currency_symbol}${amount.toFixed(2)}`;
        }
    });

</script>