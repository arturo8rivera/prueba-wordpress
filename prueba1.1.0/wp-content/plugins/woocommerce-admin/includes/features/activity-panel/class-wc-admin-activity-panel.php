<?php
/**
 * WooCommerce Activity Panel.
 * NOTE: DO NOT edit this file in WooCommerce core, this is generated from woocommerce-admin.
 *
 * @package Woocommerce Admin
 */

/**
 * Contains backend logic for the activity panel feature.
 */
class WC_Admin_Activity_Panel {
	/**
	 * Class instance.
	 *
	 * @var WC_Admin_Activity_Panel instance
	 */
	protected static $instance = null;

	/**
	 * Get class instance.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook into WooCommerce.
	 */
	public function __construct() {
		add_filter( 'wc_admin_get_user_data_fields', array( $this, 'add_user_data_fields' ) );
		add_action( 'woocommerce_components_settings', array( $this, 'component_settings' ), 20 ); // Run after WC_Admin_Loader.
		add_action( 'woocommerce_updated', array( $this, 'woocommerce_updated_note' ) );
	}

	/**
	 * Adds fields so that we can store activity panel last read and open times.
	 *
	 * @param array $user_data_fields User data fields.
	 * @return array
	 */
	public function add_user_data_fields( $user_data_fields ) {
		return array_merge(
			$user_data_fields,
			array(
				'activity_panel_inbox_last_read',
				'activity_panel_reviews_last_read',
			)
		);
	}

	/**
	 * Add alert count to the component settings.
	 *
	 * @param array $settings Component settings.
	 */
	public function component_settings( $settings ) {
		$settings['alertCount'] = WC_Admin_Notes::get_notes_count( array( 'error', 'update' ), array( 'unactioned' ) );
		return $settings;
	}

	/**
	 * Things to do after WooCommerce updates.
	 */
	public function woocommerce_updated_note() {
		WC_Admin_Notes_Settings_Notes::add_notes_for_settings_that_have_moved();
	}
}

new WC_Admin_Activity_Panel();
