<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

function behzad_delete_plugin() {
	  delete_post_meta_by_key( '_chart_sale_price'); 
	  delete_post_meta_by_key( '_chart_regular_price');
	  delete_post_meta_by_key( '_chart_date'); 

}
behzad_delete_plugin();
?>