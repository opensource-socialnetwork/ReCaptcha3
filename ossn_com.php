<?php
/**
 * Open Source Social Network
 *
 * @package   ReCaptcha3 
 * @author    Core Team <admin@opensource-socialnetwork.org>
 * @copyright (c) Core Team
 * @license   OPEN SOURCE SOCIAL NETWORK LICENSE 4.0
 * @link      http://www.opensource-socialnetwork.org/licence
 */

define('RECAPTCHA_V3', ossn_route()->com . 'ReCaptcha3/');

/**
 * ReCaptcha initialize
 *
 * @return void
 */
function recaptcha_v3_init() {
		ossn_extend_view('forms/signup/before/submit', 'recaptcha/view');
		$protected_components = ossn_call_hook('captcha', 'protected:components', false, null);
		// check for other components with form to be protected 
		if ($protected_components) {
			// and add their forms
			foreach ($protected_components['protected_forms'] as $protected_form) {
				ossn_extend_view('forms/' . $protected_form . '/before/submit', 'recaptcha/view');
			}
		}
		ossn_extend_view('css/ossn.default', 'recaptcha/css');
		ossn_register_callback('action', 'load', 'recaptcha3_check');
		if(ossn_isAdminLoggedin()) {
			ossn_register_com_panel('ReCaptcha3', 'settings');
			ossn_register_action('recaptcha/admin/settings', RECAPTCHA_V3 . 'actions/admin.php');
		}
}

/**
 * reCaptcha the actions which you wanted to validate
 *
 * @return array
 */
function recaptcha3_actions_validate() {
		// start with one default action to be protected
		$protected_actions = array('user/register');
		// look for other componnts which have registered for protection
		$protected_components = ossn_call_hook('captcha', 'protected:components', false, null);
		if ($protected_components) {
			// and add their action
			$protected_actions = array_merge($protected_actions, $protected_components['protected_actions']);
		}
		return $protected_actions;
}

/**
 * Validate the recaptcha actions and Verify a captcha based on the input value entered by the user and the seed token passed.
 *
 * @param string $callback The callback type
 * @param string $type The callback type
 * @param array $params The option values
 *
 * @return string in case of errors
 */
function recaptcha3_check($callback, $type, $params) {
		$recaptcha_data = input('g-recaptcha-response');

		if(isset($params['action']) && in_array($params['action'], recaptcha3_actions_validate())) {
			$recaptchaCom = new OssnComponents();
			$recaptcha = $recaptchaCom->getSettings('ReCaptcha3');

			$vetParametros = array(
				'secret'   => $recaptcha->recaptcha_secret_key,
				'response' => $recaptcha_data,
				'remoteip' => $_SERVER['REMOTE_ADDR'],
			);
			$curlReCaptcha = curl_init();
			curl_setopt($curlReCaptcha, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
			curl_setopt($curlReCaptcha, CURLOPT_POST, true);
			curl_setopt($curlReCaptcha, CURLOPT_POSTFIELDS, http_build_query($vetParametros));
			curl_setopt($curlReCaptcha, CURLOPT_RETURNTRANSFER, true);
			$vetResposta = json_decode(curl_exec($curlReCaptcha), true);
			curl_close($curlReCaptcha);

			if (isset($vetResposta['success']) && $vetResposta['success']) {
				// according to docs a score less than 0.5 means bot activity
				if ( (float) $vetResposta['score'] < .5) {
					if (ossn_is_xhr()) {
						header('Content-Type: application/json');
						echo json_encode(array(
							'dataerr' => ossn_print('recaptcha:error:low:score'),
						));
						exit();
					} else {
						ossn_trigger_message(ossn_print('recaptcha:error:low:score');
						redirect(REF);
					}

				}
			} else {
				if (isset($vetResposta['error-codes']) && isset($vetResposta['error-codes'][0]) && $vetResposta['error-codes'][0] == 'timeout-or-duplicate') {
					if (ossn_is_xhr()) {
						header('Content-Type: application/json');
						echo json_encode(array(
							'dataerr' => ossn_print('recaptcha:error:timeout'),
						));
						exit();
					} else {
						ossn_trigger_message(ossn_print('recaptcha:error:timeout'));
						redirect(REF);
					}
				} else {
					// another error has occurred, see list on https://developers.google.com/recaptcha/docs/verify
					if (ossn_is_xhr()) {
						header('Content-Type: application/json');
						echo json_encode(array(
							'dataerr' => ossn_print('recaptcha:error:other', array($vetResposta['error-codes'][0])),
						));
						exit();
					} else {
						ossn_trigger_message(ossn_print('recaptcha:error:other', array($vetResposta['error-codes'][0])));
						redirect(REF);
					}
				}
			}
		}
}

ossn_register_callback('ossn', 'init', 'recaptcha_v3_init');