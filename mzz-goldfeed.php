 <?php
 /*
Plugin Name: mzz-goldfeed
Description: A feed companion plugin for gold-price plugin
License: GPLv2
*/



// downloads the feed into a local file
function mzz_goldfeed() {

	$mzzGoldFeed = fopen("http://finance.yahoo.com/d/quotes.csv?s=XAUARS=X+XAUAUD=X+XAUBRL=X+XAUCAD=X+XAUCHF=X+XAUCNY=X+XAUCOP=X+XAUEUR=X+XAUGBP=X+XAUHKD=X+XAUIDR=X+XAUINR=X+XAUJPY=X+XAUKWD=X+XAUMXN=X+XAUMYR=X+XAUNZD=X+XAUPEN=X+XAUPHP=X+XAURUB=X+XAUSEK=X+XAUSGD=X+XAUTRY=X+XAUUSD=X+XAUVUV=X+XAUZAR=X&f=b", "r") or die("Error 001:Unable to open file!");
	//echo fread($mzzGoldFeed,320000); //320000 bytes arbitrary max file length for safety


	$mzzGoldFile = fopen(plugin_dir_path( __FILE__ ) ."gold.csv", "w") or die("Error 002:Unable to open file!"); 

	// write from the open csv file download to the local file. 320000 bytes arbitrary max file length for safety
	fwrite($mzzGoldFile, fread($mzzGoldFeed,320000));


	fclose($mzzGoldFeed);
	fclose($mzzGoldFile);


}

// execute when the admin_notices action is called, thus the text shows at top of WP Admin Dashboard area
add_action( 'admin_notices', 'mzz_goldfeed' );













?> 
