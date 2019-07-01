<?php

class APP_CAMEL_CASE_Form_User_Actualizar extends Gatuf_Form {
	private $user = null;
	
	public function initFields($extra=array()) {
		$this->user = $extra['user'];
		
		$this->fields['first_name'] = new Gatuf_Form_Field_Varchar (
			array (
				'required' => true,
				'label' => __('First name'),
				'help_text' => '',
				'initial' => $this->user->first_name,
		));
		
		$this->fields['last_name'] = new Gatuf_Form_Field_Varchar (
			array (
				'required' => true,
				'label' => __('Last name'),
				'help_text' => '',
				'initial' => $this->user->last_name,
		));
		
		$this->fields['email'] = new Gatuf_Form_Field_Email (
			array (
				'required' => true,
				'label' => __('Email'),
				'help_text' => __('A valid email address for password recovery propuse'),
				'initial' => $this->user->email,
		));
		
		$langs = array ();
		foreach (Gatuf::config('languages', array('en')) as $lang) {
			$langs[$lang] = $lang;
		}
		
		$this->fields['lang'] = new Gatuf_Form_Field_Varchar (
			array (
				'required' => true,
				'label' => __('Language'),
				'help_text' => __('Default language for the user'),
				'initial' => $this->user->language,
				'choices' => $langs,
				'widget' => 'Gatuf_Form_Widget_SelectInput',
		));
	}
	
	public function save ($commit = true) {
		if (!$this->isValid()) {
			throw new Exception (__('Cannot save an invalid form.'));
		}
		
		$this->user->first_name = $this->cleaned_data['first_name'];
		$this->user->last_name = $this->cleaned_data['last_name'];
		$this->user->email = $this->cleaned_data['email'];
		$this->user->language = $this->cleaned_data['lang'];
		
		if ($commit) {
			$this->user->update ();
		}
		
		return $this->user;
	}
}
