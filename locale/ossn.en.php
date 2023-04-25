<?php

$en = array(
  'recaptcha3' => 'Google reCAPTCHA v3',
  'recaptcha' => 'Google reCAPTCHA',
  'recaptcha:text' => 'Please, fill the reCAPTCHA',
  'recaptcha:error' => 'You have entered invalid reCaptcha',
  'recaptcha:com:site_key' => 'Google reCAPTCHA SITE_KEY',
  'recaptcha:com:secret_key' => 'Google reCAPTCHA SECRET_KEY',
  'recaptcha:com:note' => 'We need the API keys, go to <a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a>. To get access this page, you will need to access your Google Account. Register your site name (e.g., '. ossn_site_url() . ') where this reCAPTCHA will be used. After that, paste both SECRET_KEY and SITE_KEY in each field here.',
  'recaptcha:site_key:empty' => 'The SITE_KEY is empty.',
  'recaptcha:secret_key:empty' => 'The SECRET_KEY is empty.',
  'recaptcha:saved' => 'API keys stored.',
  'recaptcha:save:error' => 'The API keys couldn\'t be saved.',
  'recaptcha:configure' => 'Please configure google captcha in admin panel.',
);
ossn_register_languages('en', $en);