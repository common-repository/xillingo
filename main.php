<?php
	/*
	Plugin Name: Xillingo
	Plugin URI: http://www.xillingo.com/wordpress
	Description: Incrementa tu RPM. Contenido que incrementa tu ganancia por millar.
	Version: 0.1.1
	Author: Xillingo
	Author URI: http://www.xillingo.com
	License: GPL2
	*/
?>
<?php ob_start(); ?>
<?php
	add_action( 'admin_menu', 'xillingo_f9_menu' );
	add_action( 'wp_enqueue_scripts', 'xillingo_f9_embed_external_resources');

	function xillingo_f9_embed_external_resources(){
		$url_style_xillingo = '//cdn.xillingo.com/vivavive/xillingo_style.css';
		$url_script_xillingo = '//cdn.xillingo.com/xillingo_render_code_production.js';
		wp_enqueue_style('xillingo-css-010',$url_style_xillingo);
		wp_enqueue_script('xillingo-js-010',$url_script_xillingo, array(), '0.1.0',true);
	}

	function xillingo_f9_menu() {

			add_menu_page( __( 'Xillingo', 'xillingo' ), __( 'Xillingo', 'xillingo' ), 'admin_dashboard', 'xillingo', 'xillingo_f9_options',plugins_url( 'xillingo/images/') . 'xillingo_logo.png' );
			add_submenu_page('xillingo', 'Dashboard', __( 'Dashboard', 'dashboard' ), 'administrator', 'xillingo-dashboard', 'xillingo_f9_dashboard');
			add_submenu_page('xillingo', 'Activacíon', __( 'Activación', 'activacion' ), 'administrator', 'xillingo-settings', 'xillingo_f9_setting');
	
	}
	function xillingo_f9_dashboard() {

		include('dashboard.php');

	}
	function xillingo_f9_setting() {
		
		include('settings.php');	
	}
	function xillingo_f9_activation_code(){  

			global $wpdb;
			$xillingo_table_name = $wpdb->prefix.'xillingo';
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			if($wpdb->get_var("show tables like `$xillingo_table_name`") != $xillingo_table_name) 
				{
					$xillingo_sql_create = "CREATE TABLE `$xillingo_table_name` (
							`Id` INT(10) NOT NULL AUTO_INCREMENT,
							`xillingo_f9_activation_code` VARCHAR(100) NULL DEFAULT NULL,
							 PRIMARY KEY (`Id`)
							);";
					dbDelta($xillingo_sql_create);
				}

	}
	function xillingo_f9_insert_activation_code($Code){

			global $wpdb;
			$xillingo_table_name = $wpdb->prefix.'xillingo';
			require_once(ABSPATH . 'wp-load.php');
			$wpdb->insert($xillingo_table_name,array('xillingo_f9_activation_code' => $Code),array('%s'));

	}
	function xillingo_f9_fetch_activation_code(){

			global $wpdb;
			$xillingo_table_name = $wpdb->prefix.'xillingo';
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$xillingo_sql_select = "SELECT * FROM `$xillingo_table_name` ORDER BY Id DESC LIMIT 1;";
			$mylink = $wpdb->get_row($xillingo_sql_select, ARRAY_A);
			return $mylink['xillingo_f9_activation_code'];

	}
    function xillingo_f9_form_code() { 

		?>

<div class="wrap">
  <div class="container">
    <div class="row" >
      <div class="col-sm-6 col-md-4 col-md-offset-4" style="width:49%; float:left">
        <iframe src="http://www.xillingo.com/login/" width="98%" height="595px" style="border:none">
        <p>Your browser does not support iframes.</p>
        </iframe>
      </div>
      <div class="col-sm-6 col-md-4 col-md-offset-4" style="width:47.5%; padding-left: 13px; float:right; height:595px; background-image:url(<?php echo plugins_url( 'xillingo-wordpress/images/') . 'background_xillingo.jpg' ?>);">
        <h2 class="text-center login-title" style="color: #7EE64A;">
          <?php if(xillingo_f9_fetch_activation_code() == '') { echo 'Please Insert Your Activation Code'; }
				 else { echo 'Your widget is already activated <br />
						 if you want to type another code <br /> please do it'; } ?>
          <?php ?>
        </h2>
        <div class="account-wall">
          <?php 
					if(isset($_POST['xillingo_activation_submit'])) { echo '<h2 class="text-center login-title" style="color: #7EE64A;">Thanks for activating xillingo</h2>';} ?>
          <form class="form-signin" method="post" name="xillingo_activation_form">
            <input type="text" value="<?php echo xillingo_f9_fetch_activation_code(); ?>" name="xillingo_f9_activation_code" id="xillingo_f9_activation_code" class="form-control" placeholder="Put your Activation Code Here" style="width: 97%; padding: 13px; box-shadow: none !important;     margin-bottom: 9px; font-size:17px" required autofocus>
            <button class="btn btn-lg btn-primary btn-block submit-btn" type="submit" name="xillingo_activation_submit" id="xillingo_activation_submit"

            	style="background: #74C52C; border-radius: 3px; font-family: Arial; color: #ffffff; font-size: 17px; padding: 14px 26px;

  text-decoration: none; border:none; cursor: pointer; width:97%"

            >Submit Your Activation Code</button>
          </form>
        </div>
      </div>
      <div style="clear:both;"> </div>
    </div>
  </div>
</div>
<?php  } 
	function xillingo_f9_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		xillingo_f9_activation_code();
		xillingo_f9_form_code();
	}	
	function xillingo_f9_add_script() {
	  if(xillingo_f9_fetch_activation_code() != ''){
		 $url = 'http://www.xillingo.com/ads/validate-code/'.xillingo_f9_fetch_activation_code().'/';
		 $response = file_get_contents($url);
		 $response = json_decode($response);
		 if($response->is_valid == 'true')
		 	{
				add_filter('the_content', 'xillingo_f9_get_xillingo_ad',10);
				function xillingo_f9_get_xillingo_ad($content){
					  $content .='<div id="xillingo_widget"'; 
					  $content .=' xillingo-code="'; 
					  $content .= xillingo_f9_fetch_activation_code();
					  $content .='"'; 
					  $content .='>'; 
					  $content .='</div>'; 
					  return $content;            
				}
			}	
		}	
	}	 
	xillingo_f9_add_script();
?>
