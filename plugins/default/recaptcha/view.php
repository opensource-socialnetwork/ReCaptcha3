<?php
$recaptchaCom = new OssnComponents();
$recaptcha = $recaptchaCom->getSettings('ReCaptcha3');
if(!$recaptcha->recaptcha_site_key){
	echo "<div class='alert alert-danger'>".ossn_print('recaptcha:configure')."</div>";
	return;	
}
?>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $recaptcha->recaptcha_site_key ?>"></script>
<script>
	function grcaptcha3(){
				grecaptcha.ready(function() {
            			grecaptcha.execute('<?php echo $recaptcha->recaptcha_site_key; ?>', {action:'validate_captcha'}).then(function(token) {
              			  		if($('#gcaptcha-token').length){
									$('#gcaptcha-token').remove();	
								}
								$('#ossn-home-signup').append("<input type='hidden' id='gcaptcha-token' name='g-recaptcha-response' value='"+token+"' />");	
           				});
        		});		
	}
	$(document).ready(function(){
				grcaptcha3();
				setInterval(grcaptcha3, 6000);
	});
</script>
