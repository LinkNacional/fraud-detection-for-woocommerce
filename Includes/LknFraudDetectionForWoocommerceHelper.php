<?php
namespace Lkn\FraudDetectionForWoocommerce\Includes;

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
			$slug . 'googleRecaptchaV3Key' => array(
				'name'     => __( 'Chave Recaptcha do site', 'meu-plugin' ),
				'type'     => 'text',
				'desc'     => __( 'Chave do serviço Google Recaptcha V3.', 'meu-plugin' ),
				'id'       => $slug . 'googleRecaptchaV3Key',
                'desc_tip' => true,
			),
			$slug . 'googleRecaptchaV3Secret' => array(
				'name'     => __( 'Chave Recaptcha secreta', 'meu-plugin' ),
				'type'     => 'text',
				'desc'     => __( 'Chave secreta do Google Recaptcha V3.', 'meu-plugin' ),
				'id'       => $slug . 'googleRecaptchaV3Secret',
                'desc_tip' => true,
			),
			$slug . 'googleRecaptchaV3Score' => array(
				'name'     => __( 'Score mínimo', 'meu-plugin' ),
				'type'     => 'number',
				'desc'     => __( 'O score mínimo validado pelo Recaptcha para que o pagamento seja aceito. Varia entre 0 e 10.', 'meu-plugin' ),
				'id'       => $slug . 'googleRecaptchaV3Score',
                'desc_tip' => true,
				'default'  => '5',
				'custom_attributes' => array(
					'min'  => '0',
					'step' => '1',
				),
			),
			'sectionEnd' => array(
				'type' => 'sectionend'
			)
		);


		
		return $settingsFields;
	}

	public function validatePayment(){
		
	}
}
