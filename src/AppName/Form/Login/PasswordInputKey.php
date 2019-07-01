<?php

Gatuf::loadFunction ('Gatuf_HTTP_URL_urlForView');

class APP_CAMEL_CASE_Form_Login_PasswordInputKey extends Gatuf_Form {
	public function initFields($extra=array()) {
		$this->fields['key'] = new Gatuf_Form_Field_Varchar(
		                               array('required' => true,
		                                     'label' => __('Your verification key'),
		                                     'initial' => '',
		                                     'widget_attrs' => array (
		                                         'size' => 50,
		                                     ),
		));
	}
	
	public function clean_key () {
		$this->cleaned_data ['key'] = trim ($this->cleaned_data['key']);
		
		$error = __('We are sorry but this validation key is not valid. Maybe you should directly copy/paste it from your validation email.');
		if (false === ($cres = self::checkKeyHash ($this->cleaned_data['key']))) {
			throw new Gatuf_Form_Invalid ($error);
		}
		
		$guser = new Gatuf_User ();
		$sql = new Gatuf_SQL ('email=%s AND id=%s', array ($cres[0], $cres[1]));
		if ($guser->getcount(array ('filter' => $sql->gen())) != 1) {
			throw new Gatuf_Form_Invalid ($error);
		}
		
		if ((time() - $cres[2]) > 10800) {
			throw new Gatuf_Form_Invalid (__('Sorry, but this verification key has expired, please restart the password recovery sequence. For security reasons, the verification key is only valid 3h.'));
		}
		return $this->cleaned_data['key'];
	}
	
	function save ($commit=true) {
		if (!$this->isValid()) {
			throw new Exception(__('Cannot save an invalid form.'));
		}
		
		return $this->cleaned_data['key'];
	}
	
	public static function checkKeyHash ($key) {
		$hash = substr ($key, 0, 2);
		$encrypted = substr ($key, 2);
		if ($hash != substr(md5(Gatuf::config('secret_key').$encrypted), 0, 2)) {
			return false;
		}
		$cr = new Gatuf_Crypt (md5(Gatuf::config('secret_key')));
		$f = explode (':', $cr->decrypt($encrypted), 3);
		if (count ($f) != 3) {
			return false;
		}
		return $f;
	}
}

