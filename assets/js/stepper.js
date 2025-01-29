jQuery(document).ready(function ($) {
    let currentStep = 1; // Start from step 1
    const totalSteps = $('.step').length;

    // Update the progress bar
    function updateProgressBar() {
        $('.progress-step').each(function () {
            const step = $(this).data('step');
            if (step < currentStep) {
                $(this).find('.step-icon').addClass('completed').text('✔');
                $(this).addClass('completed-step').removeClass('active-step');
            } else if (step === currentStep) {
                $(this).addClass('active-step').removeClass('completed-step');
                $(this).find('.step-icon').removeClass('completed').text(step);
            } else {
                $(this).removeClass('active-step completed-step');
                $(this).find('.step-icon').removeClass('completed').text(step);
            }
        });
    }

    // Show a specific step
    function showStep(step) {
        $('.step').hide();
        $(`.step[data-step="${step}"]`).show();
        currentStep = step;
        updateProgressBar();
    }

    // Validate WooCommerce fields in the current step
    function validateStep() {
        let isValid = true;
        let firstErrorField = null;

        // Use WooCommerce's validation for checkout fields
        $(`.step[data-step="${currentStep}"] .form-row .input-text, .step[data-step="${currentStep}"] .form-row input, .step[data-step="${currentStep}"] .form-row select,.step[data-step="${currentStep}"] .form-row select`).each(function () {
            const fieldWrapper = $(this).closest('.form-row');
            const isRequired = fieldWrapper.hasClass('validate-required') || $(this).hasClass('required');
            const fieldValue = $(this).val();

            // If the field is required and empty, mark it as invalid
            if (isRequired && (!fieldValue || fieldValue.trim() === '')) {
                isValid = false;
                fieldWrapper.addClass('woocommerce-invalid').removeClass('woocommerce-validated');
                if (!firstErrorField) {
                    firstErrorField = $(this);
                }
            } else {
                fieldWrapper.addClass('woocommerce-validated').removeClass('woocommerce-invalid');
            }
        });

        if (!isValid && firstErrorField) {
            // Scroll to the first invalid field
            $('html, body').animate({
                scrollTop: firstErrorField.offset().top - 20
            }, 500);
            firstErrorField.focus();
        }

        return isValid;
    }

    // Next step button handler
    $('.next-step').click(function () {
        if (validateStep()) {
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        }
    });

    // Previous step button handler
    $('.previous-step').click(function () {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    // Initialize: Show Step 1 and update progress bar
    showStep(currentStep);

    // Additional code from the second script
    $('#custom_country_field').addClass('required');


    function toggleRequiredFields() {
        const isReturnShippingSelected = $('#delivery_option_awc_return_shipping').is(':checked');
        const isCheckboxChecked = $('#sp_re_yes').is(':checked'); // Checkbox for "Yes"


        if (isReturnShippingSelected && isCheckboxChecked) {
            // Add required class to address fields
            $('#return_address_line, #delivery_address_street, #return_address_city, #return_address_post, #return_address_country').addClass('required');
        } else {
            // Remove required class if "Return Shipping" is not selected
            $('#return_address_line, #delivery_address_street, #return_address_city, #return_address_post, #return_address_country').removeClass('required');
        }

        // Automatically uncheck #sp_re_yes if delivery option changes to anything else
        if (!isReturnShippingSelected) {
            $('#sp_re_yes').prop('checked', false);
            $('#sp_re_no').prop('checked', true); // If using radio buttons for Yes/No
        }


    }

    // Attach the event listeners
    $(document).ready(function () {
        // Run the toggle function on page load
        toggleRequiredFields();

        // Bind the toggle function to the change event
        $('input[name="delivery_option"], #sp_re_yes, #sp_re_no').on('change', toggleRequiredFields);
    });





    function dttoggleRequiredFields() {
        if ($('#delivery_option_awc_inoffice_df').is(':checked')) {
            // Add required class to address fields
            $('#estimated_delivery_date').addClass('required');
        } else {
            // Remove required class from address fields
            $('#estimated_delivery_date').removeClass('required');
        }
    }
    dttoggleRequiredFields();

    // Listen for changes on the radio buttons
    $('input[name="delivery_option"]').change(function () {
        dttoggleRequiredFields();
    });
    $(function () {
        $("#estimated_delivery_date").datepicker();
    });


   });

jQuery(document).ready(function($) {
    function checkTermsFields() {
        if ($('#comment_instructions_checkbox').is(':checked')) {
            // Enable the place order button
            $('#place_order').prop('disabled', false);
        } else {
            // Disable the place order button
            $('#place_order').prop('disabled', true);
        }
    }

    // Listen for the updated_checkout event
    $(document.body).on('updated_checkout', function() {
        checkTermsFields();
    });

    // Also bind to checkbox changes
    $(document).on('change', '#comment_instructions_checkbox', function() {
        checkTermsFields();
    });
});







//showStep(currentStep); // Step 1 is active by default
//     $(function () {
//        $("#estimated_delivery_date").datepicker();
//    });

function showAdditionalField(value) {
    var delivery_address = document.getElementById('delivery_address');
    var returning_documents = document.getElementById('returning_documents');
    var spacific_returning = document.getElementById('spacific_returning_option');

    if (value === 'awc_inoffice_df') {
        delivery_address.style.display = 'flex';
        returning_documents.style.display = 'flex';
        spacific_returning.style.display = 'none';
    } else if (value === 'awc_return_shipping') {
        returning_documents.style.display = 'flex';
        delivery_address.style.display = 'none';
        spacific_returning.style.display = 'block';
    } else {
        delivery_address.style.display = 'none';
        returning_documents.style.display = 'none';
        spacific_returning.style.display = 'none';
    }
} // Call the function on load to set the initial state 

document.addEventListener('DOMContentLoaded', function () {
    var selectedOption = document.querySelector('input[name="delivery_option"]:checked');
    if (selectedOption) {
        showAdditionalField(selectedOption.value);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const inofficedrop = document.getElementById('delivery_option_awc_inoffice_df');
    const spReNo = document.getElementById('sp_re_no');
    const spReYes = document.getElementById('sp_re_yes');
    const returningDocumentsWrapper = document.getElementById('returning_documents_wrapper');
    const allOptions = returningDocumentsWrapper.querySelectorAll('.radio_button.returning_documents');
    const collectInPersonOption = document.getElementById('returning_documents_0');
    const rsa = document.getElementById('returnSA');

    function updateOptions() {
        if (spReNo.checked || inofficedrop.checked) {
            rsa.style.display = 'none';
            // Show only the "I'll Collect in Person" option
            allOptions.forEach(option => {
                if (option !== collectInPersonOption) {
                    option.style.display = 'none';
                } else {
                    option.style.display = 'flex'; // Show as needed (block, flex, etc.)
                }
            });
        } else {
            rsa.style.display = 'block';
            // Show all options
            allOptions.forEach(option => {
                option.style.display = 'flex';
            });
        }
    }

    // Add event listeners to the radio buttons
    spReNo.addEventListener('change', updateOptions);
    spReYes.addEventListener('change', updateOptions);
    inofficedrop.addEventListener('change', updateOptions);

    // Initialize the visibility based on the default state
    updateOptions();
});


document.addEventListener('DOMContentLoaded', function () {
    const ndNo = document.getElementById('name_of_documents_no');
    const ndYes = document.getElementById('name_of_documents_yes');
    const name_of_documents = document.getElementById('name_of_documents');
    function updateOptions() {
        if (ndNo.checked) {
            name_of_documents.style.display = 'block';
        } else {
            name_of_documents.style.display = 'none';
        }
    }
    // Add event listeners to the radio buttons
    ndNo.addEventListener('change', updateOptions);
    ndYes.addEventListener('change', updateOptions);

    // Initialize the visibility based on the default state
    updateOptions();
});
document.addEventListener('DOMContentLoaded', function () {
    const adsn1 = document.getElementById('adition_service_options_1');
    const which_language = document.getElementById('which_language');
    const other_language = document.querySelectorAll('input[name="adition_service_options"]');

    const adsn = document.getElementById('adition_service_options_2');
    const which_embassy = document.getElementById('which_embassy');

    function updateOptions() {
        if (adsn1.checked) {
            which_language.style.display = 'block';
            which_language.classList.add('required');

        } else {
            which_language.style.display = 'none';
            which_language.classList.remove('required');
        }

        if (adsn.checked) {
            which_embassy.style.display = 'block';
            which_embassy.classList.add('required');
        } else {
            which_embassy.style.display = 'none';
            which_embassy.classList.remove('required');
        }
    }

    other_language.forEach(option => option.addEventListener('change', updateOptions));

    // Initialize the visibility based on the default state
    updateOptions();
});


document.addEventListener('DOMContentLoaded', function () {
    const addressLineField = document.getElementById('return_address_line');
    const streetField = document.getElementById('rtn_address_street');
    const cityField = document.getElementById('return_address_city');
    const postcodeField = document.getElementById('return_address_post');
    const countryField = document.getElementById('return_address_country');

    const addressPreviewTitle = document.getElementById('preview_return_address');
    const previewAddressLine = document.querySelector('#preview_address_line');
    const previewStreet = document.querySelector('#preview_street');
    const previewCity = document.querySelector('#preview_city');
    const previewPostcode = document.querySelector('#preview_postcode');
    const previewCountry = document.querySelector('#preview_country span');


    function updatePreview() {
        const addressLineValue = addressLineField.value.trim();
        const streetValue = streetField.value.trim();
        const cityValue = cityField.value.trim();
        const postcodeValue = postcodeField.value.trim();
        const countryValue = countryField.value.trim();

        previewAddressLine.textContent = addressLineValue;
        previewStreet.textContent = streetValue;
        previewCity.textContent = cityValue;
        previewPostcode.textContent = postcodeValue;
        previewCountry.textContent = countryValue;

        // Show or hide the title based on the fields' values
        if (addressLineValue || streetValue || cityValue || postcodeValue || countryValue) {
            addressPreviewTitle.style.display = 'block';
        } else {
            addressPreviewTitle.style.display = 'none';
        }
    }

    // Add event listeners to update preview on input
    addressLineField.addEventListener('input', updatePreview);
    streetField.addEventListener('input', updatePreview);
    cityField.addEventListener('input', updatePreview);
    postcodeField.addEventListener('input', updatePreview);
    countryField.addEventListener('input', updatePreview);

    // Initial check to set the visibility of the title on page load
    updatePreview();
});




document.addEventListener('DOMContentLoaded', function () {
    const doc_type = document.getElementById('custom_service_options_2');
    const busnis1day = document.getElementById('srvnot_busnis1day');
    const other_options = document.querySelectorAll('input[name="custom_service_options"]');

    function updateOptions() {
        const doc_type = document.getElementById('doc_type');
        const busnis1day = document.getElementById('busnis1day');

        if (!doc_type) {
            console.warn("doc_type element not found. Hiding busnis1day by default.");
            if (busnis1day) {
                busnis1day.style.display = 'none';
            }
            return;
        }

        if (doc_type.checked) {
            busnis1day.style.display = 'block';
        } else {
            busnis1day.style.display = 'none';
        }
    }

    // Add event listeners to all radio buttons
    other_options.forEach(option => option.addEventListener('change', updateOptions));

    // Initialize the visibility based on the default state
    updateOptions();
});





/* 
 * 
 * chackuot form */
/* * Destination */
document.addEventListener('DOMContentLoaded', function () {
    const countryInput = document.getElementById('custom_country_field');
    const countryDisplay = document.getElementById('country_display');

    // Event listener to update the display when the user types
    countryInput.addEventListener('input', function () {
        countryDisplay.innerHTML =
                '<span class="bold">Destination:</span><span>' +
                (countryInput.value || '-') +
                '</span>';
    });

    const ad_srv = document.querySelectorAll('input[name="adition_service_options"]');
    const adsrviceDisplay = document.getElementById('adserv_display');
    const translationInput = document.getElementById('which_language');
    const translationDisplay = document.getElementById('language');
    const embassyInput = document.getElementById('which_embassy');
    const embassyDisplay = document.getElementById('embassy');

    // Loop through all matching inputs and add event listeners
    ad_srv.forEach((input) => {
        input.addEventListener('input', function () {
            // Display the value of the currently selected input
            adsrviceDisplay.innerHTML =
                    '<span class="bold">AD Service:</span> <span>' +
                    (input.value || '-') +
                    '</span>';

            // Show or hide the translation input based on the selected value
            if (input.value === 'None' && input.checked) {
                adsrviceDisplay.style.display = 'none'; // Show the translation input
            }

            if (input.value === 'Translation' && input.checked) {
                translationInput.style.display = 'block'; // Show the translation input
            } else {
                translationInput.style.display = 'none'; // Hide the translation input
                translationDisplay.innerHTML = ''; // Clear language display
            }
            // Show or hide the translation input based on the selected value
            if (input.value === 'Embassy Attestation' && input.checked) {
                embassyInput.style.display = 'block'; // Show the translation input
            } else {
                embassyInput.style.display = 'none'; // Hide the translation input
                embassyDisplay.innerHTML = ''; // Clear language display
            }
        });
    });

    // Add event listener to translation input
    translationInput.addEventListener('input', function () {
        translationDisplay.innerHTML =
                '<span class="bold">Language:</span> <span>' +
                (translationInput.value || '-') +
                '</span>';
    });
    // Add event listener to translation input
    embassyInput.addEventListener('input', function () {
        embassyDisplay.innerHTML =
                '<span class="bold">Embassy:</span> <span>' +
                (embassyInput.value || '-') +
                '</span>';
    });


    const dvmathodInputs = document.querySelectorAll('input[name="delivery_option"]');
    const eddateInput = jQuery('#estimated_delivery_date'); // Use jQuery to target the datepicker
    const eddateDisplay = document.getElementById('edDate');
    const dvmethDisplay = document.getElementById('dvmeth');



    // Loop through all delivery option inputs
    dvmathodInputs.forEach((input) => {

        input.addEventListener('change', function () {

            if (input.value === 'awc_inoffice_df' && input.checked) {
                dvmethDisplay.style.display = 'flex';
                dvmethDisplay.innerHTML =
                        '<span class="bold">Delivery Method:</span> <span>In Office Delivery</span>';
            } else if (input.value === 'awc_via_email' && input.checked) {
                dvmethDisplay.style.display = 'flex';
                dvmethDisplay.innerHTML =
                        '<span class="bold">Delivery Method:</span> <span>Via Email</span>';
            } else if (input.value === 'awc_return_shipping' && input.checked) {
                dvmethDisplay.style.display = 'flex';

                dvmethDisplay.innerHTML =
                        '<span class="bold">Delivery Method:</span> <span>Return Shipping</span>';

            } else {
                dvmethDisplay.style.display = 'none';
                dvmethDisplay.innerHTML = ''; // Clear display
            }
            // Check if the selected option is 'awc_inoffice_df' and the radio is checked
            if (input.value === 'awc_inoffice_df' && input.checked) {
                eddateInput.show(); // Show the date input field using jQuery
                eddateDisplay.style.display = 'flex'; // Show the display container
            } else {
                //eddateInput.hide(); // Hide the date input field using jQuery
                eddateDisplay.style.display = 'none'; // Hide the display container
                eddateDisplay.innerHTML = ''; // Clear the display content
            }
        });
    });

    // Initialize the datepicker and use the onSelect event to update the display
    eddateInput.datepicker({
        dateFormat: 'mm/dd/yy', // Adjust to your desired format
        onSelect: function (selectedDate) {
            eddateDisplay.innerHTML =
                    '<span class="bold">EST Date:</span> <span>' +
                    (selectedDate || '-') +
                    '</span>';
        },
    });

    const returnDocInput = document.querySelectorAll('input[name="returning_documents_options"]');

    const returnDocDisplay = document.getElementById('edDate');

});






