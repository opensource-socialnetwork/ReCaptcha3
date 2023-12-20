<?php

$com = new OssnComponents;
$params = $com->getSettings('ReCaptcha3');
echo ossn_view_form('ReCaptcha3/admin', array(
    'action' => ossn_site_url() . 'action/recaptcha/admin/settings',
	'params' => array('recaptcha' => $params),
    'class' => 'ossn-admin-form'	
));