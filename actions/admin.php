<?php
 $component = new OssnComponents;
 
 $site_key = input('recaptcha_site_key');
 $secret_key = input('recaptcha_secret_key');
 if(empty($site_key)){
	 ossn_trigger_message(ossn_print('recaptcha:site_key:empty'), 'error');
	 redirect(REF);
 }else if(empty($secret_key)){
   ossn_trigger_message(ossn_print('recaptcha:secret_key:empty'), 'error');
   redirect(REF);
 }
 $vars = array(
	'recaptcha_site_key' => $site_key,
	'recaptcha_secret_key' => $secret_key,
  );
 if($component->setSettings('Recaptcha3', $vars)){
	 ossn_trigger_message(ossn_print('recaptcha:saved'));
	 redirect(REF);
 } else {
	 ossn_trigger_message(ossn_print('recaptcha:save:error'), 'error');
	 redirect(REF);	 
 }