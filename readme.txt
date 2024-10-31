=== Plot prices woocommerce product (product price history) ===
Contributors:behzadrohizadeh
Donate link: free
Tags:Plot,prices,Plot prices,woocommerce,product price history,Plot prices for product woocommerce,chart price product,chart price for product  woocommerce
Requires at least: 3.6
Tested up to: 6.5.4
Stable tag: 2.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Chart plugin to view plot price changes of woocommerce product.

== Description ==

 Chart plugin to view price changes of woocommerce product.

 * 1-Place the Php code  do_shortcode( '[chartprice]' ); in the single page product.
 * 2-use shortcode [chartprice] in product post.
 * 3-change setting in woocommerce page setting.


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload plugin folder to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress



== Frequently Asked Questions ==

 1-Place the Php code  do_shortcode( '[chartprice]' ); in the single page product.

 2-use shortcode [chartprice] in product post.

 3-  for change format date use php code below in function.php file:
  <code>
    add_filter('chart_price_apply_filter_format_date', 'format_date');
    function format_date($date)
    {
    $date=date('Y/m/d');//defult Y-m-d
    return $date;
    }
  <code>

== Screenshots ==

1. Plot in product 
2. Settting 

== Changelog ==

= 1.0 =
 *head version of plugin

= 1.1 =
*chang js file to chart js 

= 2.0 = 

*add the variable product to plot

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 1.1 =
This version have excellent chart.

= 2.0 =
This version have excellent chart and Support attributes Products .
= 2.1 =
Update Chart.
add serring to woocommerce setting

= 2.2 =
support plot in product tabs.




== Arbitrary section == 

 * for change format date use php code below in function.php file:

`<?php
 add_filter('chart_price_apply_filter_format_date', 'format_date');
    function format_date($date)
    {
    $date=date('Y/m/d');//defult Y-m-d
    return $date;
    } 

?>`
