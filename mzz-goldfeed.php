 <?php
 /*
Plugin Name: mzz-goldfeed
Description: A feed companion plugin for gold-price plugin
License: GPLv2
*/

/*
1. make a cron job as below:
	
	-A. set it to fire: 'twice-daily'

	A. find which currency

	B. pull four metals' ounce values from yahoo for that currency

	C. if grams, then multiply/divide to get the four gram values 

	D. put the resulting four values into wp_options
*/
	
	
	
// downloads the feed into a local file
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

	
	//mzzGoldOzPrice
	//(oz) x (28.35) = g.
	$mzzGoldGramPrice = number_format((float)($mzzGoldOzPrice / 28.3495), 4, '.', '') ;
	$mzzSilverGramPrice = number_format((float)($mzzSilverOzPrice / 28.3495), 4, '.', '') ;
	$mzzPlatinumGramPrice = number_format((float)($mzzPlatinumOzPrice / 28.3495), 4, '.', '') ;
	$mzzPalladiumGramPrice = number_format((float)($mzzPalladiumOzPrice / 28.3495), 4, '.', '') ;

	//number_format((float)$foo, 2, '.', '')
	
	echo  $mzzGoldOzPrice . " " . $mzzSilverOzPrice . " " . $mzzPlatinumOzPrice . " " . $mzzPalladiumOzPrice . " " . $mzzGoldGramPrice . " " . $mzzSilverGramPrice . " " . $mzzPlatinumGramPrice . " " . $mzzPalladiumGramPrice;

	

	update_option( 'mzz_gp_gold_oz_price', sanitize_text_field($mzzGoldOzPrice) );
	update_option( 'mzz_gp_gold_gram_price', sanitize_text_field($mzzGoldGramPrice) );
	update_option( 'mzz_gp_silver_oz_price', sanitize_text_field($mzzSilverOzPrice) );
	update_option( 'mzz_gp_silver_gram_price', sanitize_text_field($mzzSilverGramPrice) );
	update_option( 'mzz_gp_platinum_oz_price', sanitize_text_field($mzzPlatinumOzPrice) );
	update_option( 'mzz_gp_platinum_gram_price', sanitize_text_field($mzzPlatinumGramPrice) );
	update_option( 'mzz_gp_palladium_oz_price', sanitize_text_field($mzzPalladiumOzPrice) );
	update_option( 'mzz_gp_palladium_gram_price', sanitize_text_field($mzzPalladiumGramPrice) );
	
	
	
}

// execute when the admin_notices action is called, thus the text shows at top of WP Admin Dashboard area
add_action( 'admin_notices', 'mzz_goldfeed' );



?> 
