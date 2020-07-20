<?php

/**
 * A simple SMTP configuration for WordPress and WooCommerce.
 *
 * Versandgigant is using the enhancement of this configuration to
 * ensure the delivery of emails and a fast and secure channel.
 * This plugin doesn't implement a new version of PHPMailer but
 * using the version from WordPress Core.
 * 
 * This simple plugin avoids the need to manually configure every
 * site in a network installation.
 *
 * * Plugin Name: Versandgigant WP Simple Secure SMTP Emails
 * * Plugin URI: https://github.com/p-w/versandgigant-wp-simple-smtp-mails
 * * Description: Fast and reliable way to configure a WordPress or WooCommerce website to send SMTP mails. Easy, secure, fast, performing and encryption ready.
 * * Version: 1.0
 * * License: GPL-3.0
 * * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * * Text Domain: vg-wp-simple-smtp-emails
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	// Disallow direct HTTP access.
	exit;
} 


/**
 * Base class for WordPress to register and initialize this plugin.
 */
class VG_WP_Simple_SMTP_Emails {
	/**
	 * Entry point for the WordPress framework into plugin code.
	 *
	 * This is the method called when WordPress loads the plugin file.
	 * It is responsible for "registering" the plugin's main functions
	 * with the {@see https://codex.wordpress.org/Plugin_API WordPress Plugin API}.
	 *
	 * @uses add_action()
	 *
	 * @return void
	 */
	public static function register () {
		add_action( 'phpmailer_init', array( __CLASS__, 'phpmailer_init' ) );
	}

	/**
	 * Overriding the PHPMailer default configuration with mail account
	 * settings defined in wp-config.php
	 *
	 * @return void
	 */
	public static function phpmailer_init ( $phpmailer ) {
		if ( ! defined( 'USE_SMTP' ) || ! USE_SMTP ) {
			// if needed, switch to PHP mail() mails with a switch in wp-config.php
			$phpmailer->IsMail();
		} else {
			// use WordPress Core PHPMailer to deliver mails through SMTP
			$phpmailer->IsSMTP();
			( defined( 'SMTP_SECURE' ) && SMTP_SECURE !== '' )	? $phpmailer->SMTPSecure = SMTP_SECURE	: '';	// set security schema, tls or ssl
			( defined( 'SMTP_AUTH' ) && SMTP_AUTH !== '' )		? $phpmailer->SMTPAuth   = SMTP_AUTH	: '';	// enable SMTP authentication
			( defined( 'SMTP_PORT' ) && SMTP_PORT !== '' )		? $phpmailer->Port       = SMTP_PORT	: '';	// set the SMTP server port, 25 or 587
			( defined( 'SMTP_HOST' ) && SMTP_HOST !== '' )		? $phpmailer->Host       = SMTP_HOST	: '';	// SMTP server
			( defined( 'SMTP_USER' ) && SMTP_USER !== '' )		? $phpmailer->Username   = SMTP_USER	: '';	// SMTP server username
			( defined( 'SMTP_PASS' ) && SMTP_PASS !== '' )		? $phpmailer->Password   = SMTP_PASS	: '';	// SMTP server password
			( defined( 'SMTP_FROM' ) && SMTP_FROM !== '' )		? $phpmailer->From       = SMTP_FROM	: '';	// SMTP From email address
			( defined( 'SMTP_NAME' ) && SMTP_NAME !== '' )		? $phpmailer->FromName   = SMTP_NAME	: '';	// SMTP From name
			( defined( 'SMTP_REPLYTO' ) && SMTP_REPLYTO !== ''
				&&
			  defined( 'SMTP_REPLYTO_NAME' ) && SMTP_REPLYTO_NAME !== '' ) ? 
					$phpmailer->AddReplyTo(SMTP_REPLYTO, SMTP_REPLYTO_NAME) : ''; // Set alternative ReplyTo Field

			$phpmailer->SMTPDebug = SMTP_DEBUG;      // debug level, 1, 2 or 0
		}
	}
}

VG_WP_Simple_SMTP_Emails::register();
