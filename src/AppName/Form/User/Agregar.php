<?php

class APP_CAMEL_CASE_Form_User_Agregar extends Gatuf_Form {
	
	public function initFields($extra=array()) {
		$this->fields['login'] = new Gatuf_Form_Field_Varchar (
			array (
				'required' => true,
				'label' => __('Username'),
				'help_text' => '',
				'initial' => '',
		));
		
		$this->fields['first_name'] = new Gatuf_Form_Field_Varchar (
			array (
				'required' => true,
				'label' => __('First name'),
				'help_text' => '',
				'initial' => '',
		));
		
		$this->fields['last_name'] = new Gatuf_Form_Field_Varchar (
			array (
				'required' => true,
				'label' => __('Last name'),
				'help_text' => '',
				'initial' => '',
		));
		
		$this->fields['email'] = new Gatuf_Form_Field_Email (
			array (
				'required' => true,
				'label' => __('Email'),
				'help_text' => __('A valid email address for password recovery propuse'),
				'initial' => '',
		));
		
		$langs = array ();
		foreach (Gatuf::config('languages', array('en')) as $lang) {
			$langs[$lang] = $lang;
		}
		
		$this->fields['language'] = new Gatuf_Form_Field_Varchar (
			array (
				'required' => true,
				'label' => __('Language'),
				'help_text' => __('Default language for the user'),
				'initial' => '',
				'choices' => $langs,
				'widget' => 'Gatuf_Form_Widget_SelectInput',
		));
		
		$this->fields['password'] = new Gatuf_Form_Field_Varchar (
			array(
				'required' => true,
				'label' => __('The password for the user'),
				'initial' => '',
				'widget' => 'Gatuf_Form_Widget_PasswordInput',
				//'help_text' => '',
				'widget_attrs' => array(
					'maxlength' => 50,
					'size' => 15,
				),
		));
		$this->fields['password2'] = new Gatuf_Form_Field_Varchar (
			array(
				'required' => true,
				'label' => __('Confirm the password'),
				'initial' => '',
				'widget' => 'Gatuf_Form_Widget_PasswordInput',
				'widget_attrs' => array(
					'maxlength' => 50,
					'size' => 15,
				),
		));
	}
	
	public function clean_login () {
		$login = $this->cleaned_data['login'];
		
		$sql = new Gatuf_SQL ('login=%s', $login);
		$users = Gatuf::factory (Gatuf::config('gatuf_custom_user', 'Gatuf_User'))->getList (array ('filter' => $sql->gen (), 'count' => true));
		
		if ($users > 0) {
			throw new Gatuf_Form_Invalid (__('The selected username already exists'));
		}
		
		return $this->cleaned_data['login'];
	}
	
	public function clean () {
		if ($this->cleaned_data['password'] != $this->cleaned_data['password2']) {
			throw new Gatuf_Form_Invalid (__('The two passwords must be the same.'));
		}
		
		return $this->cleaned_data;
	}
	
	public function save ($commit = true) {
		if (!$this->isValid()) {
			throw new Exception (__('Cannot save an invalid form.'));
		}
		
		$user = Gatuf::factory (Gatuf::config('gatuf_custom_user', 'Gatuf_User'));
		
		$user->first_name = $this->cleaned_data['first_name'];
		$user->last_name = $this->cleaned_data['last_name'];
		$user->login = $this->cleaned_data['login'];
		$user->email = $this->cleaned_data['email'];
		$user->language = $this->cleaned_data['language'];
		$user->setPassword ($this->cleaned_data['password']);
		$user->administrator = false;
		
		if ($commit) {
			$user->create ();
		}
		
		return $user;
	}
}
