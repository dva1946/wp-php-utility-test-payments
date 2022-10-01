<?php
/**                                               
 * Template Name: WP PHP Utility Test Payments 1
 * script_name: wp-php-utility-test-payments-1.php
 * parent_script_name: wp-php-utility-test-payments.php
 * page_name: WP PHP Utility Test Payments 1
 * application_name: WP PHP Utility Test Payments 1
 * business_use: Manage Accounts for testing all payment processes
 * author: Dave Van Abel
 * dev_site: wpappsforthat.com
 * create_date: 2020-12-09
 * last_update_date: 
 * base_note: Manage Accounts for testing all payment processes
 * status: New 
 * license: GNU General Public License version 3
*/
/* GENERAL NOTES - IMPORTANT
12-09-20: Code made functional to show how it can work. 
*/
get_header();

if ( ! defined( 'ABSPATH' ) ) {die( '-1' );}

global $wpdb; 
global $tbl_name, $tbl_payments;
global $test;

$tbl_name = $wpdb->prefix.'usermeta';
$tbl_payments = $wpdb->prefix.'shop_payments';

echo "<h2>Utility For Resetting Test Accounts - Page 2 of 2</h2>";

/* GET POST DATA */

if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  ($_POST['action'] == "submit") ) {
	//echo "Up top below POST<br>";
	//die;
		$fields = array(
			'user_id',
		);
	foreach ($fields as $field) {
		//echo "Field = $field, value = $_POST[$field]<br>";
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
	}
	$user_id 	=  $_POST['user_id'];
	list($a,$b) = explode("|", $user_id);
echo "User Info: $a, $b<br>";
	/* EOY Expires For: Last Year, This Year, Next Year & This Year +2 */

	$pht 				= new DateTime('now', new DateTimeZone('America/Phoenix')); // create an object
	$now_time 			= $pht->format('Y-m-d@H:i:s');	// format the datetime: Hi => hhmm
	$payment_date		= $pht->format('Y-m-d');
	list($yy,$ym,$yd) 	= explode("-", $payment_date);
	$shop_txn_id 		= "$user_id" . ":" . "$now_time";
	$this_yr 			= $pht->format('Y');
	list($yy,$ym,$yd) 	= explode("-", $payment_date);
	$expthismonth		= "$this_yr-$ym-01";
	$last_yr			= $this_yr - 1;
	$explast_ymd 		= $this_yr . "-11-01";	// default = "-01-01"
	$expjan 			= $this_yr + 1;
	$expjan_ymd 		= $expjan . "-01-01";
	$expjan_mdy 		= "01-01-" . $expjan;
	$expjan_plus1 		= $this_yr + 2;
	$expjan_plus1_ymd 	= $expjan_plus1 . "-01-01";
	$expjan_plus1_mdy 	= "01-01-" . $expjan_plus1;

	/* ASSOCIATE MEMBER */
	if ($b == "A") {
		$paid_through 		= $expjan_ymd;
		$orientation_status = 4; 
		$expires 			= $expjan_ymd;
		$add_product_meta 	= "associate";
		$del_product_meta 	= "full-membership";
	}
	/* CURRENT MEMBER */
	if ($b == "M") {
		$paid_through 		= $expjan_ymd;
		$orientation_status = 5; 
		$expires 			= $expjan_ymd;
		$add_product_meta 	= "full-membership";
		$del_product_meta 	= "associate";
	}
	/* EXPIRED MEMBER */
	if ($b == "E") {
		$paid_through 		= $explast_ymd;
		$orientation_status = 5; 
		$expires 			= $explast_ymd;
		$del_product_meta 	= "full-membership";
		$add_product_meta 	= "expired";
	}
	/* EXPIRED MEMBER */
	if ($b == "E") {
		$paid_through 		= $explast_ymd;
		$orientation_status = 5; 
		$expires 			= $explast_ymd;
		$del_product_meta 	= "full-membership";
		$add_product_meta 	= "expired";
	}
	/* EXPIRED MEMBER 1ST OF CURRENT MONTH */
	if ($b == "F") {
		$paid_through 		= $expthismonth;
		$orientation_status = 5; 
		$expires 			= $expthismonth;
		$add_product_meta 	= "full-membership";	//need it
		$del_product_meta 	= "expired";			//could bump into it
	}

	/* Select User */
	$theuser = $wpdb->get_results( "
		SELECT 
			wp_users.ID AS ID	
		FROM 
			wp_users 
		WHERE 
			wp_users.user_login = '$a' ", OBJECT
	);
	/* end of select user */

	/* Update / Delete Recs */
	foreach ( $theuser as $theuser ) { 
		$ID = $theuser->ID;
		echo "FOREACH: User Info: $a, $ID<br>";

		/* 11-14-20: Added a whol bunch that should be synced 
		with payments, so re-set to value or '' */
		
		$metas = array( 
			'paid_through'			=> $paid_through,		// used
			'expires'         		=> $expires,			// used
			'shop_txn_id'			=> '',					// used - is set payment complete, could be removed but has value
			'associate'				=> '',					// 11-14-20: Only see in join-2 as '1'
			'payment_date'			=> '',
			'payment_amount'		=> '',
			'payment_type'			=> '',
			'payment_note'			=> '',
			'trans_type'			=> '',
			'payment_date'			=> '',
			'orientation_status'	=> $orientation_status,	// can be used
		);

		/* Update wp_usermeta */
		foreach($metas as $key => $value) {
			update_user_meta( $ID, $key, $value );
			echo "META: $key = $value<br>";
		}
		/* Delete Existing Payment Records */
		$wpdb->delete( $tbl_payments, array( 'user_id' => $ID ) );
		echo "Deleted payment recs<br>";
		/* Associate Products */
		if ($b == "A") {
			/* Add Product */
			wpmem_set_user_product($add_product_meta, $ID, false);
			/* Remove Product */
			wpmem_remove_user_product($del_product_meta, $ID, false);
			echo "Associate: Products updated or removed.<br>";
		}	
		/* Current Member Products */
		if ($b == "M") {
			/* Add Product */
			wpmem_set_user_product($add_product_meta, $ID, false);
			/* Remove Product */
			wpmem_remove_user_product($del_product_meta, $ID, false);
			echo "Current Member:  updated or removed.<br>";
		}	
		/* Expired Products */
		if ($b == "E") {
			/* Add Product */
			wpmem_set_user_product($add_product_meta, $ID, false);
			/* Remove Product */
			wpmem_remove_user_product($del_product_meta, $ID, false);
			echo "Expired: Products updated or removed.<br>";
		}	
		/* Expired Products */
		if ($b == "F") {
			/* Add Product */
			wpmem_set_user_product($add_product_meta, $ID, false);
			/* Remove Product */
			wpmem_remove_user_product($del_product_meta, $ID, false);
			echo "Expired: Products updated or removed.<br>";
		}	
	}
}
echo "<h3>Update Complete for User!</h3>";
?>
