<?php
/**
 * Exports ACtions
 *
 * These are actions related to exporting data from Easy Digital Downloads.
 *
 * @package     EDD
 * @subpackage  Admin/Export
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Process the download file generated by a batch export
 *
 * @since 2.4
 * @return void
 */
function edd_process_batch_export_download() {

	if( ! wp_verify_nonce( $_REQUEST['nonce'], 'edd-batch-export' ) ) {
		wp_die( __( 'Nonce verification failed', 'edd' ), __( 'Error', 'edd' ), array( 'response' => 403 ) );
	}

	require_once EDD_PLUGIN_DIR . 'includes/admin/reporting/export/class-batch-export.php';

	do_action( 'edd_batch_export_class_include', $_REQUEST['class'] );

	$export = new $_REQUEST['class'];
	$export->export();

}
add_action( 'edd_download_batch_export', 'edd_process_batch_export_download' );

/**
 * Exports earnings for a specified time period
 * EDD_Earnings_Export class.
 *
 * @since 2.0
 * @return void
 */
function edd_export_earnings() {
	require_once EDD_PLUGIN_DIR . 'includes/admin/reporting/class-export-earnings.php';

	$earnings_export = new EDD_Earnings_Export();

	$earnings_export->export();
}
add_action( 'edd_earnings_export', 'edd_export_earnings' );


/**
 * Export all the customers to a CSV file.
 *
 * Note: The WordPress Database API is being used directly for performance
 * reasons (workaround of calling all posts and fetch data respectively)
 *
 * @since 1.4.4
 * @return void
 */
function edd_export_all_customers() {
	require_once EDD_PLUGIN_DIR . 'includes/admin/reporting/class-export-customers.php';

	$customer_export = new EDD_Customers_Export();

	$customer_export->export();
}
add_action( 'edd_email_export', 'edd_export_all_customers' );

/**
 * Exports all the downloads to a CSV file using the EDD_Export class.
 *
 * @since 1.4.4
 * @return void
 */
function edd_export_all_downloads_history() {
	require_once EDD_PLUGIN_DIR . 'includes/admin/reporting/class-export-download-history.php';

	$file_download_export = new EDD_Download_History_Export();

	$file_download_export->export();
}
add_action( 'edd_downloads_history_export', 'edd_export_all_downloads_history' );

/**
 * Add a hook allowing extensions to register a hook on the batch export process
 *
 * @since  2.4.2
 * @return void
 */
function edd_register_batch_exporters() {
	if ( is_admin() ) {
		do_action( 'edd_register_batch_exporter' );
	}
}
add_action( 'plugins_loaded', 'edd_register_batch_exporters' );

/**
 * Register the payments batch exporter
 * @since  2.4.2
 */
function edd_register_payments_batch_export() {
	add_action( 'edd_batch_export_class_include', 'edd_include_payments_batch_processer', 10, 1 );
}
add_action( 'edd_register_batch_exporter', 'edd_register_payments_batch_export', 10 );

/**
 * Loads the payments batch process if needed
 *
 * @since  2.4.2
 * @param  string $class The class being requested to run for the batch export
 * @return void
 */
function edd_include_payments_batch_processer( $class ) {

	if ( 'EDD_Batch_Payments_Export' === $class ) {
		require_once EDD_PLUGIN_DIR . 'includes/admin/reporting/export/class-batch-export-payments.php';
	}

}

/**
 * Register the customers batch exporter
 * @since  2.4.2
 */
function edd_register_customers_batch_export() {
	add_action( 'edd_batch_export_class_include', 'edd_include_customers_batch_processer', 10, 1 );
}
add_action( 'edd_register_batch_exporter', 'edd_register_customers_batch_export', 10 );

/**
 * Loads the customers batch process if needed
 *
 * @since  2.4.2
 * @param  string $class The class being requested to run for the batch export
 * @return void
 */
function edd_include_customers_batch_processer( $class ) {

	if ( 'EDD_Batch_Customers_Export' === $class ) {
		require_once EDD_PLUGIN_DIR . 'includes/admin/reporting/export/class-batch-export-customers.php';
	}

}

/**
 * Register the file downloads batch exporter
 * @since  2.4.2
 */
function edd_register_file_downloads_batch_export() {
	add_action( 'edd_batch_export_class_include', 'edd_include_file_downloads_batch_processer', 10, 1 );
}
add_action( 'edd_register_batch_exporter', 'edd_register_file_downloads_batch_export', 10 );

/**
 * Loads the file downloads batch process if needed
 *
 * @since  2.4.2
 * @param  string $class The class being requested to run for the batch export
 * @return void
 */
function edd_include_file_downloads_batch_processer( $class ) {

	if ( 'EDD_Batch_File_Downloads_Export' === $class ) {
		require_once EDD_PLUGIN_DIR . 'includes/admin/reporting/export/class-batch-export-file-downloads.php';
	}

}
