(function ($) {
    $(window).load(function () {
        const placeOrderButton = document.querySelector('.wc-block-components-checkout-place-order-button');

        if (placeOrderButton) {
            grecaptcha.ready(() => {
                var tokenButton = '';
                placeOrderButton.addEventListener('click', (e) => {
                    executeRecaptcha()
                });
                executeRecaptcha()
                function executeRecaptcha() {
                    grecaptcha.execute(lknFraudDetectionVariables.googleKey, { action: 'submit' }).then((token) => {
                        tokenButton = token;
                    });
                }
                // Intercepta o fetch para /wc/store/v1/checkout
                const originalFetch = window.fetch;
                
                window.fetch = async (input, init) => {
                    if (typeof input === 'string' && input.includes('/wc/store/v1/checkout')) {
                        // Clona o payload existente
                        const body = JSON.parse(init.body);
                        
                        // Adiciona o token do reCAPTCHA
                        body['payment_data'].push({
                            'key': 'gRecaptchaV3Response',
                            'value': tokenButton
                        })

                        // Recria o init com o payload modificado
                        init.body = JSON.stringify(body);
                    }
                    return originalFetch(input, init);
                };
            });
        }

        formDesc = document.querySelector('.wc-block-checkout__terms.wc-block-checkout__terms--with-separator.wp-block-woocommerce-checkout-terms-block')
        if(formDesc){
            const spanElement = document.createElement('span');
            spanElement.innerHTML = lknFraudDetectionVariables.googleTermsText;
            formDesc.appendChild(spanElement);
        }
    })
})(jQuery)