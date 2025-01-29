<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

if ($checkout->get_checkout_fields()) :
    ?>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" id="checkout">
        <div id="checkout-stepper">
            <div id="checkout-progress">
                <div class="progress-step" data-step="1">
                    <span class="step-icon "></span>
                    <span class="step-label"><?php esc_html_e('Service & Delivery', 'woocommerce'); ?></span>
                    <span class="progress-divider"></span>
                </div>

                <div class="progress-step" data-step="2">
                    <span class="step-icon"></span>
                    <span class="step-label"><?php esc_html_e('Contact Information', 'woocommerce'); ?></span>
                    <span class="progress-divider"></span>
                </div>

                <div class="progress-step" data-step="3">
                    <span class="step-icon"></span>
                    <span class="step-label"><?php esc_html_e('Confirmation and Payment', 'woocommerce'); ?></span>
                </div>                

            </div>
            <div class="anber-checkout-wrap">
                <div class="cart-wrapper">
                    <div class="step" data-step="1">  
                        <div class="number-docoment-wraper">
                            <h2>Destination Country</h2>
                            <p>Which country will the document be used in? <span class="req-field">*</span></p>
                            <?php
                            // Delivery address field
                            woocommerce_form_field('custom_country_field', array(
                                'type' => 'text',
                                'class' => array('form-row-wide'),
                                // 'placeholder' => __('Destination Country'),                                
                                'required' => true,
                                    ), $checkout->get_value('custom_country_field'));
                            ?>          
                        </div>
                        <?php
                        $ad_service = get_aditoinal_service_option();
                        if (!empty($ad_service)) {
                            ?>  
                            <div class="adition-service number-docoment-wraper">
                                <h2>Additional Services (optional)</h2>
                                <div class="radio-wrapper">
                                    <?php
                                    foreach ($ad_service as $index => $option) {
                                        $awc_aditoionsrv_title = $option['awc-aditoionsrv_title'];
                                        ?>
                                        <div class="radio_button"> 
                                            <input type="radio" id="adition_service_options_<?php echo $index; ?>" value="<?php echo esc_attr($awc_aditoionsrv_title); ?>" name="adition_service_options" <?php checked($index === 0); ?> />                           
                                            <span class="adition_title"><?php echo esc_html($awc_aditoionsrv_title); ?></span>  
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="aditional-srv-txarea">
                                    <?php
                                    // Delivery address field
                                    woocommerce_form_field('which_language', array(
                                        'type' => 'textarea',
                                        'class' => array('form-row-wide language'),
                                        'placeholder' => __('Which language?'),
                                        'required' => false,
                                            ), $checkout->get_value('which_language'));
                                    ?>
                                    <?php
                                    // Delivery address field
                                    woocommerce_form_field('which_embassy', array(
                                        'type' => 'textarea',
                                        'class' => array('form-row-wide embassy'),
                                        'placeholder' => __('Which embassy / country?'),
                                        'required' => false,
                                            ), $checkout->get_value('which_embassy'));
                                    ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="adition-service number-docoment-wraper">
                            <h2 class="stepertitle">Delivery Method</h2>
                            <div class="item-wrap">
                                <?php
                                $delivery_options = get_delivery_options_with_icons();
                                $default_option = 'awc_inoffice_df';
                                if (!empty($delivery_options)) {
                                    foreach ($delivery_options as $key => $option) {
                                        ?>
                                        <div class="delivery-option button">
                                            <input 
                                                type="radio" 
                                                id="delivery_option_<?php echo esc_attr($key); ?>" 
                                                name="delivery_option" 
                                                value="<?php echo esc_attr($key); ?>"
                                                <?php checked($key, $default_option); ?> 
                                                onchange="showAdditionalField(this.value)"
                                                />

                                            <label class="btn btn-default" for="delivery_option_<?php echo esc_attr($key); ?>">
                                                <img 
                                                    src="<?php echo esc_url($option['icon']); ?>" 
                                                    alt="<?php echo esc_attr($option['label']); ?>" 
                                                    class="delivery-icon" style="width: 20px; height: 20px; vertical-align: middle;"
                                                    />
                                                <p class="delivery-label"><?php echo esc_html($option['label']); ?></p>
                                                <p class="delivery-tag"><?php echo esc_html($option['tag']); ?></p> 
                                            </label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div> 
                            <div id="additional-field">  
                                <div id="delivery_address" style="display: <?php echo $default_option == 'awc_inoffice_df' ? 'flex' : 'none'; ?>;">
                                    <div class="">
                                        <label>Delivery address</label>
                                        <p class="dvofad"> <?php echo carbon_get_theme_option('awc_inoffice_df_offaddress'); ?></p>
                                    </div>
                                    <div class="delivery_address_item subtitle">
                                        <?php
                                        // Estimated delivery date field
                                        woocommerce_form_field('estimated_delivery_date', array(
                                            'type' => 'text',
                                            'class' => array('delivery_address_item subtitle input-text'),
                                            'label' => __('Estimated delivery date', 'woocommerce'),
                                            'placeholder' => __('mm / dd / yy'),
                                            'required' => false,
                                                ), $checkout->get_value('estimated_delivery_date'));
                                        ?>
                                    </div>
                                </div>

                                <div id="spacific_returning_option" class="spacific_returning" style="display:none">
                                    <div class="radio-wrapper">                                       
                                        <div class="radio_button">
                                            <input type="radio" id="sp_re_no" value="No" name="sp_re" checked>
                                            <label for="sp_re_no">No</label>
                                        </div>
                                        <div class="radio_button">
                                            <input type="radio" id="sp_re_yes" value="Yes" name="sp_re" >
                                            <label for="sp_re_yes">Yes</label>
                                        </div>
                                    </div>
                                    <div id="returnSA" class="return_shipping_address">
                                        <div class="ad_line_one">
                                            <?php
                                            woocommerce_form_field('return_address_line', array(
                                                'type' => 'text',
                                                'class' => array('rtn_address_line'),
                                                'placeholder' => __('Address'),
                                                'required' => false,
                                                    ), $checkout->get_value('return_address_line'));

                                            woocommerce_form_field('rtn_address_street', array(
                                                'type' => 'text',
                                                'class' => array('rtn_address_item subtitle'),
                                                'placeholder' => __('Street address'),
                                                'required' => false,
                                                    ), $checkout->get_value('rtn_address_street'));

                                            woocommerce_form_field('return_address_city', array(
                                                'type' => 'text',
                                                'class' => array('rtn_address_city'),
                                                'placeholder' => __('City'),
                                                'required' => false,
                                                    ), $checkout->get_value('return_address_city'));
                                            ?>
                                        </div>
                                        <div class="ad_line_one">
                                            <?php
                                            woocommerce_form_field('return_address_post', array(
                                                'type' => 'text',
                                                'class' => array('rtn_address_post'),
                                                'placeholder' => __('Postcode'),
                                                'required' => false,
                                                    ), $checkout->get_value('return_address_post'));

                                            woocommerce_form_field('return_address_country', array(
                                                'type' => 'text',
                                                'class' => array('rtn_address_country'),
                                                'placeholder' => __('Country'),
                                                'required' => false,
                                                    ), $checkout->get_value('return_address_country'));
                                            ?>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="returning_documents_wrapper" class="adition-service"> 
                            <div id="returning_documents" class="number-docoment-wraper" style="display: <?php echo $default_option == 'awc_inoffice_df' ? 'block' : 'none'; ?>;">
                                <?php
                                $returning_documents = get_returning_documents_option();
                                if (!empty($returning_documents)) {
                                    ?>
                                    <div class="adition-service returning">
                                        <h2>Returning Documents</h2> 
                                        <div class="radio-wrapper colam"> <?php
                                            foreach ($returning_documents as $index => $option) {
                                                $returning_documents_title = $option['awc_returning_title'];
                                                $returning_documents_price = $option['awc_returning_price'];
                                                ?> 
                                                <div id="returning_documents_<?php echo $index; ?>" class="radio_button returning_documents"> 
                                                    <input type="radio" id="returning_documents_options_<?php echo $index; ?>"
                                                           data-title="<?php echo esc_attr($returning_documents_title); ?>"
                                                           data-price="<?php echo esc_attr($returning_documents_price); ?>" 
                                                           name="returning_documents_options" <?php checked($index === 0); ?> /> 
                                                    <span class="adition_title"><?php echo esc_html($returning_documents_title); ?></span> 
                                                    <span class="adition_price"> <?php echo get_woocommerce_currency_symbol(); ?><?php echo esc_html($returning_documents_price); ?></span> 
                                                </div> 
                                            <?php } ?> 
                                        </div> 
                                    </div> <?php } ?> 
                            </div> 
                        </div>
                        <button type="button" class="next-step"><?php esc_html_e('Continue to Contact Information', 'woocommerce'); ?></button>
                    </div>

                    <div class="step contact-info" data-step="2">
                        <h3 class="stepertitle"><?php esc_html_e('Contact Information', 'woocommerce'); ?></h3>
                        <div class="secseperet">
                            <?php do_action('woocommerce_checkout_billing'); ?>
                        </div>

                        <div id="preview_return_address" class="secseperet">
                            <h3 id="addressPreviewTitle" class="stepertitle-sub">Shipping address</h3>
                            <p ><span id="preview_address_line"></span>, <span id="preview_street"></span></p>                           
                            <p ><span id="preview_city"></span>, <span id="preview_postcode"></span></p>                        
                            <p id="preview_country"><span></span></p>
                        </div>

                        <div class="secseperet">
                            <h3 class="stepertitle-sub"><?php echo carbon_get_theme_option('nod_title_sec'); ?></h3>
                            <div class="radio-wrapper">
                                <div class="radio_button">
                                    <input type="radio" id="name_of_documents_yes" value="Yes" name="name_of_documents" checked>
                                    <label for="name_of_documents_yes">Same as contact information</label>
                                </div>
                                <div class="radio_button">
                                    <input type="radio" id="name_of_documents_no" value="No" name="name_of_documents" >
                                    <label for="name_of_documents_no">Others</label>
                                </div>
                            </div>
                            <?php
                            woocommerce_form_field('name_of_documents', array(
                                'type' => 'textarea',
                                'class' => array('name_of_documents'),
                                //'placeholder' => __('Please list the names for each document'),
                                'placeholder' => __(carbon_get_theme_option('nod_title_tooltip')),
                                'required' => false,
                                    ), $checkout->get_value('name_of_documents'));
                            ?>
                        </div>

                        <div class="next-pr-btnwrap">
                            <button type="button" class="previous-step"><?php esc_html_e('Back', 'woocommerce'); ?></button>
                            <button type="button" class="next-step"><?php esc_html_e('Continue to Confirmation and Payment', 'woocommerce'); ?></button>
                        </div>
                    </div>

                    <div class="step" data-step="3">
                        <h3 class="stepertitle"><?php esc_html_e('Confirmation and payment', 'woocommerce'); ?></h3>
                        <div class="secseperet">
                            <h3 class="stepertitle-sub">Leave a comment or instructions (Optional)</h3>
                            <div class="comment_instructions_wrapper">
                                <?php
                                woocommerce_form_field('comment_instructions', array(
                                    'type' => 'textarea',
                                    'class' => array('comment_instructions'),
                                    'placeholder' => __('Leave a comment'),
                                    'required' => false,
                                        ), $checkout->get_value('comment_instructions'));
                                ?>
                            </div>
                        </div>
                        <div class="secseperet">
    <div class="tc_wrapper">
        <?php
        woocommerce_form_field('comment_instructions_checkbox', array(
            'type' => 'checkbox',
            'class' => array('comment_instructions_checkbox'),
            'required' => false,
        ), $checkout->get_value('comment_instructions_checkbox'));
        ?>
        <p><?php echo carbon_get_theme_option('tc_content'); ?></p>
    </div>
</div>
    <!--                        <button type="button" class="previous-step"><?php esc_html_e('Back', 'woocommerce'); ?></button>-->
                        <!-- Payment Method Section -->
                        <div class="custom-payment-methods">
                            <h3 class="stepertitle-sub">  Payment method</h3>
                            <?php
                            wc_get_template('checkout/payment.php');
                            ?>
                        </div>


                    </div>
                </div>
                <div class="cart-sideber">

                    <?php require_once( plugin_dir_path(__FILE__) . 'custom-order-summary.php' ); ?>       
                </div>
            </div>
    </form>

    </div>
    <?php



endif;


