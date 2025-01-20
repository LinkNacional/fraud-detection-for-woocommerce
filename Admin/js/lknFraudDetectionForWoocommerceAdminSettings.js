(function ($) {
    $(window).load(function () {
        enableRecaptchaInput = document.querySelector('#lknFraudDetectionForWoocommerceEnableRecaptcha');
        enableRecaptchaSelectInput = document.querySelector('#lknFraudDetectionForWoocommerceRecaptchaSelected');
        enableGoogleV3KeyInput = document.querySelector('#lknFraudDetectionForWoocommercegoogleRecaptchaV3Key');
        enableGoogleV3SecretInput = document.querySelector('#lknFraudDetectionForWoocommercegoogleRecaptchaV3Secret');
        enableGoogleV3ScoreInput = document.querySelector('#lknFraudDetectionForWoocommercegoogleRecaptchaV3Score');

        if(enableRecaptchaInput && enableRecaptchaSelectInput && enableGoogleV3KeyInput && enableGoogleV3SecretInput && enableGoogleV3ScoreInput) {
            enableRecaptchaInputTr = enableRecaptchaInput.closest('tr')
            enableRecaptchaSelectInputTr = enableRecaptchaSelectInput.closest('tr')
            enableGoogleV3KeyInputTr = enableGoogleV3KeyInput.closest('tr')
            enableGoogleV3SecretInputTr = enableGoogleV3SecretInput.closest('tr')
            enableGoogleV3ScoreInputTr = enableGoogleV3ScoreInput.closest('tr')
            
            if(lknFraudDetectionVariables.enableRecaptcha == 'no') {
                hideRecaptchaFields()
            }

            enableRecaptchaSelectInput.addEventListener('change', function() {
                if(this.value == 'googleRecaptchaV3') {
                    enableGoogleV3KeyInputTr.style.display = 'table-row';
                    enableGoogleV3SecretInputTr.style.display = 'table-row';
                    enableGoogleV3ScoreInputTr.style.display = 'table-row';
                } else {
                    enableGoogleV3KeyInputTr.style.display = 'none';
                    enableGoogleV3SecretInputTr.style.display = 'none';
                    enableGoogleV3ScoreInputTr.style.display = 'none';
                }
            
            })

            enableRecaptchaInput.addEventListener('change', function() {
                if(this.checked) {
                    showRecaptchaFields()
                } else {
                    hideRecaptchaFields()
                }
            
            })

            function showRecaptchaFields() {
                enableRecaptchaSelectInputTr.style.display = 'table-row';
                enableGoogleV3KeyInputTr.style.display = 'table-row';
                enableGoogleV3SecretInputTr.style.display = 'table-row';
                enableGoogleV3ScoreInputTr.style.display = 'table-row';
            }

            function hideRecaptchaFields() {
                enableRecaptchaSelectInputTr.style.display = 'none';
                enableGoogleV3KeyInputTr.style.display = 'none';
                enableGoogleV3SecretInputTr.style.display = 'none';
                enableGoogleV3ScoreInputTr.style.display = 'none';
            }
        }
    })
})(jQuery)