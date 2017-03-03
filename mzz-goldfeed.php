 <?php
 /*
Plugin Name: mzz-goldfeed
Description: A (yahoo api -based) feed companion plugin for gold-price WordPress plugin
Version: 20170301.1617
License: GPLv2
*/



//if it's not already scheduled, then go ahead and schedule the next one
if ( ! wp_next_scheduled( 'mzz_crongp_task_hook' ) ) {
//  wp_schedule_event( time(), 'hourly', 'mzz_crongp_task_hook' );
	wp_schedule_event( time(), 'three_minutes', 'mzz_crongp_task_hook' );
}

//specify what function will run when the scheduled task fires
add_action( 'mzz_crongp_task_hook', 'mzz_goldfeed' );



//define what the function does
function mzz_goldfeed() {

// check if gold-price plugin is activated
if ( ! is_plugin_active( 'gold-price/admin.php' ) ) {
  //gold-price plugin is not activated so we quit
  echo "gold-price plugin is not activated! quitting...";
  return false;
} 


	$mzz_getoption_gp_currency = get_option( 'gp_currency' );

	
	$mzzGoldFeed = fopen("http://finance.yahoo.com/d/quotes.csv?s=XAU" . $mzz_getoption_gp_currency . "=X&f=b", "r") or die("Error 001:Unable to open file!");
	$mzzGoldOzPrice = fgets($mzzGoldFeed);
	fclose($mzzGoldFeed);
	
	$mzzSilverFeed = fopen("http://finance.yahoo.com/d/quotes.csv?s=XAG" . $mzz_getoption_gp_currency . "=X&f=b", "r") or die("Error 001:Unable to open file!");
	$mzzSilverOzPrice = fgets($mzzSilverFeed);
	fclose($mzzSilverFeed);
	
	$mzzPlatinumFeed = fopen("http://finance.yahoo.com/d/quotes.csv?s=XPT" . $mzz_getoption_gp_currency . "=X&f=b", "r") or die("Error 001:Unable to open file!");
	$mzzPlatinumOzPrice = fgets($mzzPlatinumFeed);
	fclose($mzzPlatinumFeed);
	
	$mzzPalladiumFeed = fopen("http://finance.yahoo.com/d/quotes.csv?s=XPD" . $mzz_getoption_gp_currency . "=X&f=b", "r") or die("Error 001:Unable to open file!");
	$mzzPalladiumOzPrice = fgets($mzzPalladiumFeed);	
	fclose($mzzPalladiumFeed);

	
	//(oz) / (28.3495) = g.
	$mzzGoldGramPrice = number_format((float)($mzzGoldOzPrice / 28.3495), 4, '.', '') ;
	$mzzSilverGramPrice = number_format((float)($mzzSilverOzPrice / 28.3495), 4, '.', '') ;
	$mzzPlatinumGramPrice = number_format((float)($mzzPlatinumOzPrice / 28.3495), 4, '.', '') ;
	$mzzPalladiumGramPrice = number_format((float)($mzzPalladiumOzPrice / 28.3495), 4, '.', '') ;
	

	update_option( 'mzz_gp_gold_oz_price', sanitize_text_field($mzzGoldOzPrice) );
	update_option( 'mzz_gp_gold_gram_price', sanitize_text_field($mzzGoldGramPrice) );
	update_option( 'mzz_gp_silver_oz_price', sanitize_text_field($mzzSilverOzPrice) );
	update_option( 'mzz_gp_silver_gram_price', sanitize_text_field($mzzSilverGramPrice) );
	update_option( 'mzz_gp_platinum_oz_price', sanitize_text_field($mzzPlatinumOzPrice) );
	update_option( 'mzz_gp_platinum_gram_price', sanitize_text_field($mzzPlatinumGramPrice) );
	update_option( 'mzz_gp_palladium_oz_price', sanitize_text_field($mzzPalladiumOzPrice) );
	update_option( 'mzz_gp_palladium_gram_price', sanitize_text_field($mzzPalladiumGramPrice) );
	
}








// let's clean up after ourselves and make sure we remove the cron job upon plugin deactivation
register_deactivation_hook( __FILE__, 'mzz_crongp_deactivate' );
 
function mzz_crongp_deactivate() {
   $timestamp = wp_next_scheduled( 'mzz_crongp_task_hook' );
   wp_unschedule_event( $timestamp, 'mzz_crongp_task_hook' );
}


?> 
