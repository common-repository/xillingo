<?php 
		if(isset($_POST['xillingo_activation_submit']))	{
			$xillingo_post_code = $_POST['xillingo_activation_code'];
			xillingo_f9_insert_activation_code($xillingo_post_code);
		}
		xillingo_f9_activation_code();
?>

<div class="wrap">
  <div class="container">
    <div class="row" >
      <div class="col-sm-6 col-md-4 col-md-offset-4" style="background-repeat: no-repeat;background-size: cover;width:100%; padding-left: 13px; float:right; height:800px; background-image:url(<?php echo plugins_url( 'xillingo/images/') . 'background_xillingo.jpg' ?>);">
        <h2 class="text-center login-title" style="color: #7EE64A;">
          <?php if(xillingo_f9_fetch_activation_code() == '') { echo 'Inserta tu Código de Activación Aquí'; }
				  ?>
          <?php ?>
        </h2>
        <div class="account-wall">
          <?php 
					if(isset($_POST['xillingo_activation_submit'])) {
						$url = 'http://www.xillingo.com/ads/validate-code/'.$_POST['xillingo_activation_code'].'/';
						$response = file_get_contents($url);
						$response = json_decode($response);
						if($response->is_valid == 'true')
							{
								 echo '<h2 class="login-title" style="color: #FFFFC5; text-align: center;
				font-size: 2.2em;">Xillingo ha sido activado correctamente.</h2>
        <h3 class="login-title" style="color: #FFFFC5; text-align: center;
        font-size: 1.5em;">Podrás ver el Widget en cualquiera de tus Posts.</h3>
        ';
							}
						else{
								echo '<h2 class="login-title" style="color: #FFFFC5; text-align: center;
				font-size: 2.2em;">Código de Activación No Válido, Intenta Nuevamente.</h2>';
							}	
					} ?>
         
          
          <main style="margin: 0 auto; padding: 20px; display: flex; justify-content: center; align-items: center; 
  overflow: auto;">
            <div style="width: 582px; padding: 60px; overflow: auto; height: 109px; background-color: rgba(0, 0, 0, 0.12);">
              <form class="form-signin" method="post" name="xillingo_activation_form">
                <input type="text" value="<?php echo xillingo_f9_fetch_activation_code(); ?>" name="xillingo_activation_code" id="xillingo_activation_code" class="form-control" placeholder="Inserta tu Código de Activación Aquí" style="padding: 13px; width:100%; box-shadow: none !important; margin-bottom: 9px; font-size:17px" required autofocus>
                <button class="btn btn-lg btn-primary btn-block submit-btn" type="submit" name="xillingo_activation_submit" id="xillingo_activation_submit"
    
                    style="background: #74C52C; border-radius: 3px; font-family: Arial; color: #ffffff; font-size: 17px; padding: 14px 26px;
    
      text-decoration: none; border:none; width:100%; cursor: pointer;"
    
                >Validar Tu Código De Activación</button>
              </form>
            </div>
          </main>
        </div>
      </div>
      <div style="clear:both;"> </div>
    </div>
  </div>
</div>
