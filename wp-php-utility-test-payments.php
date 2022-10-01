<?php
/**
 * Template Name: WP PHP Utility Test Payments
 * script_name: wp-php-utility-test-payments.php
 * parent_script_name: 
 * page_name: WP PHP Utility Test Payments
 * application_name: WP PHP Utility Test Payments
 * business_use: Manage Accounts for testing all payment processes
 * author: Dave Van Abel
 * dev_site: wpappsforthat.com
 * create_date: 2020-12-09
 * last_update_date: 2021-03-27 corrected missing space on line 73
 * base_note: Manage Accounts for testing all payment processes
 * status: Complete
 * license: GNU General Public License version 3
*/

/* 
** GENERAL NOTES FOR & DURING DEVELOPMENT **  

* 12-09-20 
* Tables now are:
* 	wp_shop_payments - this is a custom table for membership payments
* 	wp_users
* 	wp_usermeta
*/

/* ***************************************************** */

get_header();
if ( ! defined( 'ABSPATH' ) ) {die( '-1' );}

?>
<head>
<link rel=”stylesheet” type=”text/css” href=”style.css”>
</head>
<h2>Utility For Resetting Test Accounts - Page 1 of 2</h2>
 <form name="PaymentTesting" id="PaymentTesting" method="post" action="/index.php/wp-php-utility-test-payments-1"  >
 																			      
	<table name="MemberPayments" id="customers" >
		<div class="row">		
			<!-- TASK to perform-->	
			<tr> 
				<td  >
					This utility allows testing of all 3 Memberships (New, Current, Expired). 
					Simply select an account, re-set and test. All user passwords = GVRNO. 
					Expired user can not process payments (Manager must process). 
					New and Current users can process their own payments and Manager APPROVE, 
					or Manager can process their payments. 
					When done, re-set to clear all data from website.	
				</td>
			</tr>
			<!-- GVR# -->
			<tr> 
			     <div >
					<td >
						<select name="user_id" required >
							<option value="">--Select--</option>
							<option value="172345|A">172345 - Associate Member for signup this &/or next year</option>
							<option value="396086|M">396086 - Current Member for renewal this year</option>
							<option value="461009|E">461009 - Expired Member 1st of this year</option>
							<option value="960599|F">960599 - Expired Member 1st of this month</option>
						</select> 
					</td> 
			     </div>			     
			</tr>	
			<!-- TASK to perform-->	
			<tr> 
				<td  >
					172345 - Associate Member: Reset new member and process Payments<br>
					396086 - Current Member: Reset current member and process Payments<br>
					461009 - Expired Member: Reset expired member and process Payments as Manager<br>	
					960599 - Expired Member: Reset expired member (this yr-cm-01). Use to test shortcode control.
				</td>
			</tr>
			<!-- SUBMIT -->
			<tr>  
				<td>
					<input type="hidden" name="action" value="submit" > 
					<input value="Submit" name="button" type="submit" />
				</td>
			</tr>
		</div>
	</table> 
</form>
