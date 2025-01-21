<?php
namespace Lkn\FraudDetectionForWoocommerce\Includes;

use Exception;

class LknFraudDetectionForWoocommerceHelper {

	public function addSettingTab( $tabs ) {
		wp_enqueue_script( 'lknFraudDetectionForWoocommerceAdminSettings', FRAUD_DETECTION_FOR_WOOCOMMERCE_DIR_URL . 'Admin/js/lknFraudDetectionForWoocommerceAdminSettings.js', array( 'jquery' ), FRAUD_DETECTION_FOR_WOOCOMMERCE_VERSION, false );
		wp_enqueue_style( 'lknFraudDetectionForWoocommerceAdminSettings', FRAUD_DETECTION_FOR_WOOCOMMERCE_DIR_URL . 'Admin/css/lknFraudDetectionForWoocommerceAdminSettings.css', array(), FRAUD_DETECTION_FOR_WOOCOMMERCE_VERSION, 'all' );
		$slug = 'lknFraudDetectionForWoocommerce';

		wp_localize_script('lknFraudDetectionForWoocommerceAdminSettings', 'lknFraudDetectionVariables', array(
            'enableRecaptcha' => get_option($slug . 'EnableRecaptcha', 'no'),
			'recaptchaSelected' => get_option($slug . 'RecaptchaSelected'),
			'googleRecaptchaText' => __('Gerar chaves do Google Recaptcha V3.', 'meu-plugin'),
        ));
		$tabs['anti_fraud'] = __( 'Antifraude', 'meu-plugin' );
		return $tabs;
	}

	public function showSettingTabContent() {
		// Carrega os campos de configuração
		wp_enqueue_script( 'woocommerce_admin' );
		woocommerce_admin_fields( $this->getSettings() );
	}
	
	public function saveSettings() {
		woocommerce_update_options( $this->getSettings() );
	}
	
	private function getSettings() {
		$slug = 'lknFraudDetectionForWoocommerce';

		$settingsFields = array(
			'sectionTitle' => array(
				'type'     => 'title',
			),
			$slug . 'EnableRecaptcha' => array(
				'name'     => __( 'Habilitar reCAPTCHA', 'meu-plugin' ),
				'type'     => 'checkbox',
				'desc'     => __( 'Ative para habilitar o recaptcha durante o checkout.', 'meu-plugin' ),
				'id'       => $slug . 'EnableRecaptcha',
				'default'  => 'no',
			),
			$slug . 'RecaptchaSelected' => array(
				'title'    => esc_attr__( 'Versão do recaptcha', 'rede-for-woocommerce-pro' ),
				'type'     => 'select',
				'default'  => 'googleRecaptcha',
				'id' 	   => $slug . 'RecaptchaSelected',
				'options'  => array(
					'googleRecaptchaV3' => 'Google Recaptcha V3',
				),
			),
			$slug . 'GoogleRecaptchaV3Key' => array(
				'name'     => __( 'Chave Recaptcha do site', 'meu-plugin' ),
				'type'     => 'text',
				'desc'     => __( 'Chave do serviço Google Recaptcha V3.', 'meu-plugin' ),
				'id'       => $slug . 'GoogleRecaptchaV3Key',
                'desc_tip' => true,
			),
			$slug . 'GoogleRecaptchaV3Secret' => array(
				'name'     => __( 'Chave Recaptcha secreta', 'meu-plugin' ),
				'type'     => 'text',
				'desc'     => __( 'Chave secreta do Google Recaptcha V3.', 'meu-plugin' ),
				'id'       => $slug . 'GoogleRecaptchaV3Secret',
                'desc_tip' => true,
			),
			$slug . 'GoogleRecaptchaV3Score' => array(
				'name'     => __( 'Score mínimo', 'meu-plugin' ),
				'type'     => 'number',
				'desc'     => __( 'O score mínimo validado pelo Recaptcha para que o pagamento seja aceito. Varia entre 0 e 1.', 'meu-plugin' ),
				'id'       => $slug . 'GoogleRecaptchaV3Score',
                'desc_tip' => true,
				'default'  => '0.5',
				'custom_attributes' => array(
					'min'  => '0',
					'max'  => '1',
					'step' => '0.1',
				),
			),
			'sectionEnd' => array(
				'type' => 'sectionend'
			)
		);


		
		return $settingsFields;
	}

	public function enqueueRecaptchaScripts(){
		if (is_checkout() || is_cart()) {
			$googleKey = get_option('lknFraudDetectionForWoocommercegoogleRecaptchaV3Key');
			wp_enqueue_script(
				'google-recaptcha',
				'https://www.google.com/recaptcha/api.js?render=' . $googleKey,
				[],
				null,
				true
			);
			if (is_checkout()) {
				wp_enqueue_script( 'lknFraudDetectionForWoocommerceRecaptch', FRAUD_DETECTION_FOR_WOOCOMMERCE_DIR_URL . 'Public/js/lknFraudDetectionForWoocommerceRecaptch.js', array( 'jquery' ), FRAUD_DETECTION_FOR_WOOCOMMERCE_VERSION, false );
		
				wp_localize_script('lknFraudDetectionForWoocommerceRecaptch', 'lknFraudDetectionVariables', array(
					'googleKey' => $googleKey,
					'googleTermsText' => 'This site is protected by reCAPTCHA and the <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and Google <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.'
				));
			}
		}
	}

	public function processPayments($context, $result){
		add_option('woocommerce_api_payment MEU' . uniqid());
		$_POST = $context->payment_data;
		$score = (float) get_option('lknFraudDetectionForWoocommerceGoogleRecaptchaV3Score');

		$response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
			'body' => [
				'secret' => get_option('lknFraudDetectionForWoocommerceGoogleRecaptchaV3Secret'),
				'response' => $_POST['grecaptchav3response'],
				'remoteip' => $_SERVER['REMOTE_ADDR'],
			],
		]);
	
		$responseBody = json_decode(wp_remote_retrieve_body($response), true);

		//throw new Exception(json_encode($responseBody));
		if($responseBody['success'] == true){
			if($responseBody['score'] < $score){
				throw new Exception('Recaptcha inválido (Score)');
			}
		}else{
			throw new Exception('Recaptcha inválido');
		}
	}
}
