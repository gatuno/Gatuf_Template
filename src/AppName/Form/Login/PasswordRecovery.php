<?php

class APP_CAMEL_CASE_Form_Login_PasswordRecovery extends Gatuf_Form {
	public function initFields($extra=array()) {
		$this->fields['account'] = new Gatuf_Form_Field_Varchar(
		                               array('required' => true,
		                                     'label' => __('Your login or email'),
		                                     'help_text' => __('Provide either your login or your email to recover your password.'),
		));
	}
	
	public function clean_account () {
		$account = mb_strtolower (trim($this->cleaned_data['account']));
		
		$sql = new Gatuf_SQL ('email=%s OR login=%s', array ($account, $account));
		$users = Gatuf::factory ('Gatuf_User')->getList(array ('filter' => $sql->gen()));
		
		if ($users->count() == 0) {
			throw new Gatuf_Form_Invalid (__('Sorry, we cannot find a user with this email address or login. Feel free to try again.'));
		}
		$ok = false;
		foreach ($users as $user) {
			if ($user->active) {
				$ok = true;
				continue;
			}
			
			$ok = false;
		}
		
		if (!$ok) {
			throw new Gatuf_Form_Invalid (__('Sorry, we cannot find a user with this email address or login. Feel free to try again.'));
		}
		
		return $account;
	}
	
	function save ($commit=true) {
		if (!$this->isValid()) {
			throw new Exception (__('Cannot save the model from an invalid form.'));
		}
		
		$account = $this->cleaned_data['account'];
		
		$sql = new Gatuf_SQL ('email=%s OR login=%s', array ($account, $account));
		$users = Gatuf::factory ('Gatuf_User')->getList(array ('filter' => $sql->gen()));
		
		$return_url = '';
		foreach ($users as $user) {
			if ($user->active) {
				$return_url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Login::passwordRecoveryInputCode');
				$tmpl = new Gatuf_Template('APP_LOWER/login/recuperarcontra-email.txt');
				$cr = new Gatuf_Crypt (md5(Gatuf::config('secret_key')));
				$code = trim ($cr->encrypt($user->email.':'.$user->id.':'.time()), '~');
				$code = substr (md5 (Gatuf::config ('secret_key').$code), 0, 2).$code;
				$url = Gatuf::config ('url_base').Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Login::passwordRecovery', array ($code), array (), false);
				$urlic = Gatuf::config ('url_base').Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Login::passwordRecoveryInputCode', array (), array (), false);
				$context = new Gatuf_Template_Context (
				               array ('url' => Gatuf_Template::markSafe ($url),
				                      'urlik' => Gatuf_Template::markSafe ($urlic),
				                      'user' => $user,
				                      'key' => Gatuf_Template::markSafe ($code)));
				$email = new Gatuf_Mail (Gatuf::config ('from_email'), $user->email, __('Password Recovery - ?'));
				$email->setReturnPath (Gatuf::config ('bounce_email', Gatuf::config ('from_email')));
				$email->addTextMessage ($tmpl->render ($context));
				$email->sendMail ();
			}
		}
		return $return_url;
	}
}

