<?php

class APP_CAMEL_CASE_Form_User_ChangePassword extends Gatuf_Form {
	private $user = null;
	
	public function initFields($extra=array()) {
		$this->user = $extra['user'];
		$this->fields['password'] = new Gatuf_Form_Field_Varchar (
		                           array('required' => true,
		                           'label' => __('The new password'),
		                           'initial' => '',
		                           'widget' => 'Gatuf_Form_Widget_PasswordInput',
		                           //'help_text' => '',
		                           'widget_attrs' => array(
		                                             'maxlength' => 50,
		                                             'size' => 15,
		                           ),
		));
		$this->fields['password2'] = new Gatuf_Form_Field_Varchar (
		                           array('required' => true,
		                           'label' => __('Confirm the password'),
		                           'initial' => '',
		                           'widget' => 'Gatuf_Form_Widget_PasswordInput',
		                           'widget_attrs' => array(
		                                             'maxlength' => 50,
		                                             'size' => 15,
		                           ),
		));
	}
	
	public function clean () {
		if ($this->cleaned_data['password'] != $this->cleaned_data['password2']) {
			throw new Gatuf_Form_Invalid (__('The two passwords must be the same.'));
		}
		
		return $this->cleaned_data;
	}
	
	function save($commit=true) {
		if (!$this->isValid()) {
			throw new Exception (__('Cannot save an invalid form.'));
		}
		
		$this->user->setPassword ($this->cleaned_data['password']);
		if ($commit) {
			$this->user->update ();
			
			$params = array('user' => $this->user);
			Gatuf_Signal::send('Gatuf_User::passwordUpdated', 'APP_CAMEL_CASE_Form_User_ChangePassword', $params);
		}
		
		return $this->user;
	}
}

