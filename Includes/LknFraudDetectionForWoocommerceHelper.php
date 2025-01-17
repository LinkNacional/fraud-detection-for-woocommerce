<?php
namespace Lkn\FraudDetectionForWoocommerce\Includes;

class LknFraudDetectionForWoocommerceHelper {

	public function addSettingTab( $tabs ) {
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
		$settingsFields = array(
			'section_title' => array(
				'type'     => 'title',
			),
			'campo_texto' => array(
				'name'     => __( 'Campo de Texto', 'meu-plugin' ),
				'type'     => 'text',
				'desc'     => __( 'Descrição para o campo de texto.', 'meu-plugin' ),
				'id'       => 'meu_plugin_campo_texto',
				'default'  => 'Valor padrão',
				'description' => __('Your Maxipago Merchant ID.', 'woo-rede'),
                'default' => '',
                'desc_tip' => true,
			),
			'checkbox_exemplo' => array(
				'name'     => __( 'Habilitar Google reCAPTCHA', 'meu-plugin' ),
				'type'     => 'checkbox',
				'desc'     => __( 'Ative para habilitar o recaptcha durante o checkout. Gere as chaves do Recaptcha V3 aqui.', 'meu-plugin' ),
				'id'       => 'meu_plugin_checkbox_exemplo',
				'description' => __('By disabling this feature, the plugin will be loaded during the checkout process. This feature, when enabled, prevents infinite loading errors on the checkout page. Only disable it if you are experiencing difficulties with the gateway loading.', 'woo-rede'),
                'desc_tip' => true,
			),
			'section_end' => array(
				'type' => 'sectionend',
				'id'   => 'meu_plugin_section_end',
			)
		);


		
		return $settingsFields;
	}
}
