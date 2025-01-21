(function ($) {
    $(window).load(function () {
        // Script para exibir texto com url da documentação do google
        const formTable = document.querySelector('.form-table');
        const urlParams = new URLSearchParams(window.location.search);
        if(formTable && urlParams.get('tab') == 'lkn_anti_fraud') {
            const secondRow = formTable.querySelectorAll('tr')[1];
    
            // Cria o elemento <tr>
            const newRow = document.createElement('tr');
    
            // Cria o elemento <th> com atributos e o adiciona à nova linha
            const thElement = document.createElement('th');
            thElement.setAttribute('scope', 'row');
            thElement.className = 'titledesc';
            newRow.appendChild(thElement);
    
            // Cria o elemento <td>
            const tdElement = document.createElement('td');
            tdElement.style.paddingBottom = '0';
    
            // Cria o elemento <p> e adiciona o texto e estilo
            const aElement = document.createElement('a');
            aElement.href = 'https://www.google.com/recaptcha/admin/';
            aElement.target = '_blank';
            aElement.textContent = lknFraudDetectionVariables.googleRecaptchaText;
            aElement.style.fontSize = '15px';
    
            // Adiciona o <p> dentro do <td>
            tdElement.appendChild(aElement);
    
            // Adiciona o <td> à nova linha
            newRow.appendChild(tdElement);
    
            // Insere o novo <tr> após o segundo <tr>
            secondRow.insertAdjacentElement('afterend', newRow);


            // Script para fazer os campos ficarem display none
            enableRecaptchaInput = document.querySelector('#lknFraudDetectionForWoocommerceEnableRecaptcha');
            enableRecaptchaSelectInput = document.querySelector('#lknFraudDetectionForWoocommerceRecaptchaSelected');
            enableGoogleV3KeyInput = document.querySelector('#lknFraudDetectionForWoocommerceGoogleRecaptchaV3Key');
            enableGoogleV3SecretInput = document.querySelector('#lknFraudDetectionForWoocommerceGoogleRecaptchaV3Secret');
            enableGoogleV3ScoreInput = document.querySelector('#lknFraudDetectionForWoocommerceGoogleRecaptchaV3Score');
    
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
                        newRow.style.display = 'table-row';
                    } else {
                        enableGoogleV3KeyInputTr.style.display = 'none';
                        enableGoogleV3SecretInputTr.style.display = 'none';
                        enableGoogleV3ScoreInputTr.style.display = 'none';
                        newRow.style.display = 'none';
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
                    newRow.style.display = 'table-row';
                }
    
                function hideRecaptchaFields() {
                    enableRecaptchaSelectInputTr.style.display = 'none';
                    enableGoogleV3KeyInputTr.style.display = 'none';
                    enableGoogleV3SecretInputTr.style.display = 'none';
                    enableGoogleV3ScoreInputTr.style.display = 'none';
                    newRow.style.display = 'none';
                }
            }
        }
    })
})(jQuery)