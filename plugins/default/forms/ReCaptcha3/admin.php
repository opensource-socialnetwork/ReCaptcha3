    <p><?php echo ossn_print('recaptcha:com:note');?></p>
 <div>
 	<label><?php echo ossn_print('recaptcha:com:site_key');?></label>
    <input type="text" name="recaptcha_site_key" value="<?php echo $params['recaptcha']->recaptcha_site_key;?>" />
 </div>
 <div>
   <label><?php echo ossn_print('recaptcha:com:secret_key');?></label>
   <input type="text" name="recaptcha_secret_key" value="<?php echo $params['recaptcha']->recaptcha_secret_key;?>" />
 </div>
 <div>
 	<input type="submit" class="ossn-admin-button btn btn-success" value="<?php echo ossn_print('contact:admin:form:button:save'); ?>"/>
 </div>
