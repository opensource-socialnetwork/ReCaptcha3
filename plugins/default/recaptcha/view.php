<?php
$recaptchaCom = new OssnComponents();
$recaptcha = $recaptchaCom->getSettings('ReCaptcha3');
if(!isset($recaptcha->recaptcha_site_key) || (isset($recaptcha->recaptcha_site_key) && empty($recaptcha->recaptcha_site_key))){
	echo "<div class='alert alert-danger'>".ossn_print('recaptcha:configure')."</div>";
	return;	
}
?>
<!-- add extra div to place the captcha inside of the form -->
<div id="grecaptcha-box" style="margin-top: 10px; margin-bottom:10px"></div>

<script src="https://www.google.com/recaptcha/api.js?render=explicit&onload=onRecaptchaLoadCallback"></script>

<script>
// find the form the captcha will be attached to
var form_to_protect = $('#grecaptcha-box').parents('.ossn-form').first();
// get action of that form
var action_to_protect = Ossn.ParseUrl(form_to_protect[0].action).path;
// appemd token field to form
form_to_protect.append("<input type='hidden' id='gcaptcha-token' name='g-recaptcha-response' value='UNSET' />");

function onRecaptchaLoadCallback(clientID) {
	var clientId = grecaptcha.render('grecaptcha-box', {
		'sitekey': '<?php echo $recaptcha->recaptcha_site_key; ?>',
		'badge': 'inline',
		'size': 'invisible'
	});

	grecaptcha.ready(function() {
		// chaptchaRefresh(clientId);
		// rendered chapchas are following standard DOM rules
		// that is: the first (and here only) one is getting clientId = 0;
		// so we need no extra code to save it for re-using later
		// code moved outside for later executions
	});
}
	
function captchaRefresh(clientId, action) {
	grecaptcha.execute(clientId, {
		action: action
	})
	.then(function(token) {
		// replace former (outdated) captcha with new one
		document.getElementById('gcaptcha-token').value = token;
	});
}

grecaptcha.ready(function() {
	// get initial captcha on page load
	captchaRefresh(0, action_to_protect);

	// alternate automatic refreshing
	// setInterval(chaptchaRefresh(0), 1000 * 60 * 1);
});


$(form_to_protect).submit(function (e) {
	captchaRefresh(0, action_to_protect);
	// captcha is received here, but too late for the submit already on the run
	// so it will be available on next submit
	// a meaningful message will be displayed in this case
});	
</script>