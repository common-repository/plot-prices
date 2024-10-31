<?php 
/**
* Plugin Name:Plot prices woocommerce product (product price history) 
* Plugin URI:
* Description: Chart plugin to view price changes of woocommerce product ,product price history
* Version: 2.1
* Author: behzad rohizadeh
* Author URI: 
*
 * @package plot
 * @category WooCommerce
 * @author behzad rohizadeh
*
*/
add_action('init','Behzad_localization_init');
add_action( 'save_post', 'Behzad_wp_wooc_789' );
add_action( 'post_updated', 'Behzad_wp_wooc_987'); 
add_action('wp_enqueue_scripts','Behzad_wp_wooc_968_css_and_js');
add_action( 'wp_ajax_nopriv_Behzad_wp','Behzad_wp_ajax' );
add_action('wp_ajax_Behzad_wp','Behzad_wp_ajax');
add_shortcode('chartprice', 'Behzad_wp_wooc_968_chartprice');
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'apd_settings_link' );
add_action( 'woocommerce_settings_tabs', 'wc_settings_tabs_customer_list_tab' );
add_action( 'woocommerce_settings_plot_price', 'display_customer_list_tab_content' );


///
function apd_settings_link( array $links ) {
    $url = get_admin_url() . "admin.php?page=wc-settings&tab=plot_price";
    $settings_link = '<a href="' . $url . '">' . __('Settings', 'woocommerce') . '</a>';
      $links[] = $settings_link;
    return $links;
  }
function wc_settings_tabs_customer_list_tab() {
    $current_tab = ( isset($_GET['tab']) && $_GET['tab'] === 'plot_price' ) ? 'nav-tab-active' : '';
    echo '<a href="admin.php?page=wc-settings&tab=plot_price" class="nav-tab '.$current_tab.'">'.__( "Plot Prices", "woocommerce" ).'</a>';
}

// The setting tab content
function display_customer_list_tab_content() 

{

	if (isset($_POST["charttype"])) {
		update_option("charttype",$_POST["charttype"]);
	}

	$charttype=get_option("charttype",true);

	?>
      
		

		    <table class="form-table">
				<tr class="top">
					
					<td>
					   <?php _e("Chart Type","chartprice");?>
					   <select name="charttype">
					   	<option <?php if ($charttype=="line") {echo "selected";} ?> value="line">Line</option>
					   	<option <?php if ($charttype=="bar") {echo "selected";} ?> value="bar">Bar</option>
					   	<option <?php if ($charttype=="horizontalBar") {echo "selected";} ?> value="horizontalBar">horizontalBar</option>
					   	<option <?php if ($charttype=="pie") {echo "selected";} ?> value="pie">pie</option>
					   	<option <?php if ($charttype=="radar") {echo "selected";} ?> value="radar">radar</option>
					   	<option <?php if ($charttype=="doughnut") {echo "selected";} ?> value="doughnut">doughnut</option>

					   </select>
					</td>
				</tr>
				
							    
		</table>
		 

	<?php
}

//
		
function Behzad_localization_init()
{
	 $path = dirname(plugin_basename( __FILE__ )) . '/language/';
	$loaded = load_plugin_textdomain( 'chartprice', false, $path);

}
  function chart_price_apply_filter_format_date ($date='')
  {
      if (function_exists('jdate')) {
		 	  $date=jdate('Y-m-d'); 
		 	}
		 	if (function_exists('parsidate')) {
		 	   $date=parsidate('Y-m-d');
		 	}
		 	if (empty($date)) {
		 	    $date=date('Y-m-d'); 
		    }
		$date=apply_filters('chart_price_apply_filter_format_date', $date );
   return $date;
  }
	function Behzad_wp_wooc_789()
	{
	 $post_id=( isset( $_POST['ID'] ) ) ? intval($_POST['ID']) : 0; 
	 $saleprice=get_post_meta($post_id, '_chart_sale_price');
	 $date=chart_price_apply_filter_format_date();
	 if (isset($_POST['post_type']) && $_POST['post_type']=='product' && empty($saleprice) && $_POST['product-type']=='simple') {		
		 $sale_price=( isset( $_POST['_sale_price'] ) ) ? intval($_POST['_sale_price']) : 0;
	     $regular_price=( isset( $_POST['_regular_price'] ) ) ? intval($_POST['_regular_price']) : 0; 
		 add_post_meta($post_id, '_chart_sale_price', $sale_price); 
		 add_post_meta($post_id, '_chart_regular_price', $regular_price);
		 add_post_meta($post_id, '_chart_date',$date); 
		}
		if (isset($_POST['post_type']) && $_POST['post_type']=='product' && $_POST['product-type']!='simple') {
			  if (!empty($_POST['attribute_names']) && !empty($_POST['variable_post_id']))
			     {
                    $re=0;
                    $e=0;
                    
			     	foreach ($_POST['variable_post_id'] as  $value) {
                        	$variable_post_id=( !empty(  $value ) ) ? intval($value) : 0;
                        	$state=get_post_meta($variable_post_id, '_chart_sale_price');
                        	  if (empty($state) && $variable_post_id!=0) {
                        	  	//add new meta post
                        	        $variable_regular_price=$_POST['variable_regular_price'];
                                    $variable_sale_price=$_POST['variable_sale_price'];
                                    $rprice=( array_key_exists($re, $variable_regular_price) ) ? intval($variable_regular_price[$re]) : 0;
                                    $sprice=( array_key_exists($re, $variable_sale_price) ) ? intval($variable_sale_price[$re]) : 0;
	                        	    add_post_meta($variable_post_id, '_chart_sale_price', $sprice); 
			                        add_post_meta($variable_post_id, '_chart_regular_price', $rprice);
				                        if ($re==0) {
				                        			   add_post_meta($post_id, '_chart_date',$date); //save post id parrent
				                                    }
                        	    }if (!empty($state) && $variable_post_id!=0) {
                        	        $saleprice=get_post_meta($variable_post_id, '_chart_sale_price'); 
		                            $regularprice=get_post_meta($variable_post_id,'_chart_regular_price');
                                    if (isset($_POST['variable_regular_price']) && isset($_POST['variable_sale_price'])) {
                                    	 $variable_regular_price=$_POST['variable_regular_price'];
                                    	 $variable_sale_price=$_POST['variable_sale_price'];
                                    	 	$rprice=( array_key_exists($re, $variable_regular_price) ) ? intval($variable_regular_price[$re]) : 0;
                                    	 	$sprice=( array_key_exists($re, $variable_sale_price) ) ? intval($variable_sale_price[$re]) : 0;
                                    	 	$saleprice=( !empty($saleprice) ) ? intval($saleprice[count($saleprice)-1]) : 0;
                                    	 	$regularprice=( !empty($regularprice) ) ? intval($regularprice[count($regularprice)-1]) : 0;
                                    	 	if ($saleprice!=$sprice || $regularprice!=$rprice) {
											     $e++;

				                         }
                                    }
                        	  }
			     	      $re++; 
			     	         
                    
			     	  }//end foreach
			     	    if ($e > 0) {
			     	    		     add_post_meta($post_id, '_chart_date',$date); //save post id parrent
			     	    		         $variable_regular_price=$_POST['variable_regular_price'];
                                    	 $variable_sale_price=$_POST['variable_sale_price'];
                                    	 $t=0;
			     	                 foreach ($_POST['variable_post_id'] as  $valuee) 
			     	                        {
			     	                        	$rprice=( array_key_exists($t, $variable_regular_price) ) ? intval($variable_regular_price[$t]) : 0;
                                    	 	    $sprice=( array_key_exists($t, $variable_sale_price) ) ? intval($variable_sale_price[$t]) : 0;
                        	                     $variable_post_id=( !empty(  $value ) ) ? intval($valuee) : 0;
			     	     	                     add_post_meta($variable_post_id, '_chart_sale_price', $sprice); 
											     add_post_meta($variable_post_id, '_chart_regular_price',$rprice);
			     	                        $t++;}
			     	     }//end if
			     	 
		 		 }	
		}

}
function Behzad_wp_wooc_987()
{
	 if (isset($_POST['post_type']) && $_POST['post_type']=='product' && $_POST['product-type']=='simple') {
	 	$date=chart_price_apply_filter_format_date();
		  $sale_price=( isset( $_POST['_sale_price'] ) ) ? intval($_POST['_sale_price']) : 0;
	      $regular_price=( isset( $_POST['_regular_price'] ) ) ? intval($_POST['_regular_price']) : 0; 
	      $post_id=( isset( $_POST['ID'] ) ) ? intval($_POST['ID']) : 0; 		
		  $saleprice=get_post_meta($post_id, '_sale_price'); 
		  $regularprice=get_post_meta($post_id,'_regular_price');	
			if (!empty($saleprice) && !empty($regularprice)) {
				$saleprice=intval($saleprice[0]);
				$regularprice=intval($regularprice[0]);
				if ($saleprice!=$sale_price || $regularprice!=$regular_price) {
				 add_post_meta( $post_id, '_chart_sale_price', $sale_price); 
			     add_post_meta( $post_id, '_chart_regular_price',$regular_price);
			     add_post_meta( $post_id, '_chart_date',$date);
				}
				
			}
	   }
}
function Behzad_wp_wooc_968_css_and_js()
{
	wp_register_style('behzad-css-style', plugins_url('css/chart.css', __FILE__) );
	
	wp_enqueue_style( 'behzad-css-style' );
	
    wp_enqueue_script( "chart-js", plugin_dir_url( __FILE__ ) . 'js/Chart.js', array( 'jquery' ) );

    wp_enqueue_script( "behzad3-js", plugin_dir_url( __FILE__ ) . 'js/myChart.js', array( 'chart-js' ) );

	wp_localize_script( 'behzad3-js', 'the_chart_url', array( 'chart_url' => admin_url( 'admin-ajax.php' ) ) );	


}
function Behzad_wp_ajax()
{

if (isset($_POST['id'])) {
$ID=( isset( $_POST['id'] ) ) ? intval($_POST['id']) : 0;
$charttype=get_option("charttype");
if (empty($charttype)) {
	$charttype="line" ; 
}
$saleprice=get_post_meta( $ID, '_chart_sale_price'); 
	$regularprice=get_post_meta( $ID, '_chart_regular_price');
	$dates=get_post_meta( $ID, '_chart_date');
//$dates=( !empty( $dates ) ) ? implode(',',$dates) : ''; 
$ar=[];
$att=get_post_meta($ID, '_product_attributes', true);
	  if (empty($att)) {
			$ar[0]['label']=__('Regular price','chartprice');
			$ar[0]['backgroundColor']="red";
			$ar[0]['borderColor']="red";
			$ar[0]['data']=$regularprice;
			$ar[0]['fill']=false;
			$ar[1]['label']=__('Sale price','chartprice');
			$ar[1]['backgroundColor']="blue";
			$ar[1]['borderColor']="blue";
			$ar[1]['data']=$saleprice;
			$ar[1]['fill']=false;
	       
	    }

	    if (!empty($att)) {
	    	$ar=array();
	    	$r=0;
	    	$color=array(
	    		'blue',
	    		'red',
	    		'yellow',
	    		'#529DFF',
	    		'#FF884D',
	    		'#C2CAFF',
	    		'#C582FF',
	    		'#FF4FAD',
	    		'#FF3061',
	    		'#FF8A8A',
	    		'#178BFF',
	    		'#12FF79',
	    		'#FFC2C2',
	    		'#6987FF',
	    		'#FF707A',
	    		'#FFB69C',
	    		'#FF1FC3',
	    		'#FF3838',
	    		'#30FFD6',
	    		'#3672FF');
	    	global $wpdb;
				     $posts=$wpdb->get_results("SELECT * FROM `".$wpdb->posts."` WHERE post_type='product_variation' AND post_parent= $ID");
					 foreach ($posts as  $value) {
					 	$variation = wc_get_product($value->ID);
                        $rr=($variation->get_formatted_name());
                        $vt = explode(" ",$rr);
                        $replacement=array(':',',','>','<','span');
                        $vt[5]=str_replace( $replacement, '', $vt[5]);
                        $vt[7]=str_replace( $replacement, '', $vt[7]);
                        $title=$variation->get_name(); 
					   	$ar[$r]['label']=$title.'('.__('Regular price','chartprice').')';
			            $ar[$r]['backgroundColor']=$color[$r];
			            $ar[$r]['borderColor']=$color[$r];
			            $ar[$r]['data']=get_post_meta( $value->ID, '_chart_sale_price');
			            $ar[$r]['fill']=false;
			            $r++;
			            $ar[$r]['label']=$title.'('.__('Sale price','chartprice').')';
			            $ar[$r]['backgroundColor']=$color[$r];
			            $ar[$r]['borderColor']=$color[$r];
			            $ar[$r]['data']=get_post_meta( $value->ID, '_chart_regular_price');
			            $ar[$r]['fill']=false;
					 $r++; }

					}


	 $config = array(
    "type"=>$charttype,
    "data"=> array(
    "labels"=>$dates,
    "datasets"=> $ar
    ),
    "options"=> array(
    "responsive"=> true,
    "title"=> array(
    "display"=> true,
    "text"=>__('Chart price','chartprice')
      ),
      "scales"=> array(
        "xAxes"=> array(array(
          "display"=> true,
        )),
        "yAxes"=> array(array(
          "display"=> true,
          "labelString"=> 'value'
        ))
      )
    )
  );

  $res["data"] = $config ; 

  echo json_encode($res);
  exit();

}


}
function Behzad_wp_wooc_968_chartprice()
{
    $ID=get_the_ID();
	 
	return "<canvas id='myChart' idp='". esc_attr($ID)."'></canvas> "; 

	   
 }
