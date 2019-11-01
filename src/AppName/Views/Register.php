<?php

Gatuf::loadFunction('Gatuf_HTTP_URL_urlForView');
Gatuf::loadFunction('Gatuf_Shortcuts_RenderToResponse');

class APP_CAMEL_CASE_Views_Register {
	function register ($request, $match) {
		$title = __('Create your account');
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_Register_Register ($request->POST);
			if ($form->isValid ()) {
				$user = $form->save(); // It is sending the confirmation email
				$url = Gatuf_HTTP_URL_urlForView('APP_CAMEL_CASE_Views_Register::inputKey');
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
			if (isset($request->GET['login'])) {
				$params['initial'] = array('login' => $request->GET['login']);
			}
			$form = new APP_CAMEL_CASE_Form_Register_Register (null, $params);
		}
		$context = new Gatuf_Template_Context (array());
		$tmpl = new Gatuf_Template('APP_LOWER/register/terms.html');
		$terms = Gatuf_Template::markSafe($tmpl->render($context));
		return Gatuf_Shortcuts_RenderToResponse('APP_LOWER/register/index.html', 
		                                         array ('page_title' => $title,
		                                         'form' => $form,
		                                         'terms' => $terms),
		                                         $request);
	}
	
	function inputKey ($request, $match) {
		$title = __('Confirm your account creation');
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_Register_InputKey($request->POST);
			if ($form->isValid()) {
				$key = $form->save();
				$url = Gatuf_HTTP_URL_urlForView('APP_CAMEL_CASE_Views_Register::confirmation', array ($key));
				return new Gatuf_HTTP_Response_Redirect($url);
			}
		} else {
			$form = new APP_CAMEL_CASE_Form_Register_InputKey(null);
		}
		return Gatuf_Shortcuts_RenderToResponse('APP_LOWER/register/inputkey.html', 
		                                         array('page_title' => $title,
		                                         'form' => $form),
		                                         $request);
	}
	
	function confirmation ($request, $match) {
		$title = 'Confirma la creación de tu cuenta';
		$key = $match[1];
		// first "check", full check is done in the form.
		$email_id = APP_CAMEL_CASE_Form_Register_InputKey::checkKeyHash($key);
		if (false == $email_id) {
			$url = Gatuf_HTTP_URL_urlForView('APP_CAMEL_CASE_Views_Register::inputKey');
			return new Gatuf_HTTP_Response_Redirect($url);
		}
		$model = Gatuf::config('gatuf_custom_user', 'Gatuf_User')
		$user = new $model($email_id[1]);
		$extra = array ('key' => $key, 'user' => $user);
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_Register_Confirmation($request->POST, $extra);
			if ($form->isValid()) {
				$user = $form->save();
				$request->user = $user;
				$request->session->clear();
				$request->session->setData('login_time', gmdate('Y-m-d H:i:s'));
				$user->last_login = gmdate('Y-m-d H:i:s');
				$user->update();
				$request->user->setMessage(1, __('Welcome!'));
				$url = Gatuf_HTTP_URL_urlForView('APP_CAMEL_CASE_Views_Index::index');
				return new Gatuf_HTTP_Response_Redirect($url);
			}
		} else {
			$form = new APP_CAMEL_CASE_Form_RegisterConfirmation(null, $extra);
		}
		return Gatuf_Shortcuts_RenderToResponse('APP_LOWER/register/confirmation.html', 
		                                         array('page_title' => $title,
		                                         'new_user' => $user,
		                                         'form' => $form),
		                                         $request);
	}
