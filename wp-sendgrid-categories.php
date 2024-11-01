<?php
/**
 * Plugin Name: WP SendGrid Categories
 * Description: Associates a category with emails that are sent through SendGrid
 * Plugin URI: http://github.com/codeawhile/wp-sendgrid-categories/
 * Author: CodeAwhile.com
 * Author URI: http://codeawhile.com/
 * Version: 1.0
 */

class WP_SendGrid_Categories {
	const CATEGORY_SECTION_ID = 'wp-sendgrid-email-category';

	public static function start() {
		add_action( 'admin_init', array( __CLASS__, 'add_category_settings' ), 11 );
		add_filter( 'wp_sendgrid_xsmtpapi', array( __CLASS__, 'add_category' ) );
	}

	public static function add_category_settings() {
		if ( class_exists( 'WP_SendGrid_Settings' ) ) {
			add_settings_section( self::CATEGORY_SECTION_ID, __( 'Email Category' ),
				array( __CLASS__, 'show_section_description' ), WP_SendGrid_Settings::SETTINGS_PAGE_SLUG );
			add_settings_field( self::CATEGORY_SECTION_ID . '-category', __( 'Email Category' ),
				array( __CLASS__, 'display_category_field' ), WP_SendGrid_Settings::SETTINGS_PAGE_SLUG, self::CATEGORY_SECTION_ID );
		}
	}

	public static function show_section_description() {
		echo '<p>' . __( 'Configure a category to be added to all emails sent through WP SendGrid' ) . '</p>';
	}

	public static function display_category_field() {
		$settings = WP_SendGrid_Settings::get_settings();
		$category = isset( $settings['category'] ) ? $settings['category'] : '';
		echo '<input type="text" name="' . esc_attr( WP_SendGrid_Settings::SETTINGS_OPTION_NAME . '[category]' )
			. '" id="' . self::CATEGORY_SECTION_ID . '-category" value="' . $category . '"/>';
		echo ' <span class="description">' . __( 'This category will be associated with all emails sent through SendGrid' ) . '</span>';
	}

	public static function add_category( $xsmtpapi ) {
		$settings = WP_SendGrid_Settings::get_settings();
		if ( !empty( $settings['category'] ) ) {
			$xsmtpapi['category'] = $settings['category'];
		}
		return $xsmtpapi;
	}
}

WP_SendGrid_Categories::start();
