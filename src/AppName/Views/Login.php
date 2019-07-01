<?php

Gatuf::loadFunction('Gatuf_HTTP_URL_urlForView');
Gatuf::loadFunction('Gatuf_Shortcuts_RenderToResponse');

class APP_CAMEL_CASE_Views_Login {
	function login ($request, $match, $success_url = '', $extra_context=array()) {
		if (!empty($request->REQUEST['_redirect_after'])) {
			$success_url = $request->REQUEST['_redirect_after'];
		} else {
			$success_url = Gatuf::config('APP_LOWER_base').Gatuf::config ('login_success_url', '/');
		}
		
		$error = '';
		if ($request->method == 'POST') {
			foreach (Gatuf::config ('auth_backends', array ('Gatuf_Auth_ModelBackend')) as $backend) {
				$user = call_user_func (array ($backend, 'authenticate'), $request->POST);
				if ($user !== false) {
					break;
				}
			}
			
			if (false === $user) {
				$error = __('The login or the password is not valid. The login and the password are case sensitive.');
			} else {
				if (!$request->session->getTestCookie ()) {
					$error = __('You need to enable the cookies in your browser to access this website.');
				} else {
					$request->user = $user;
					$request->session->clear ();
					$request->session->setData('login_time', gmdate('Y-m-d H:i:s'));
					$request->session->setData('gatuf_language', $user->language);
					$user->last_login = gmdate('Y-m-d H:i:s');
					$user->update ();
					$request->session->deleteTestCookie ();
					return new Gatuf_HTTP_Response_Redirect ($success_url);
				}
				
			}
		}
		/* Mostrar el formulario de login */
		$request->session->createTestCookie ();
		$context = new Gatuf_Template_Context_Request ($request, array ('page_title' => __('Sign in'), '_redirect_after' => $success_url, 'error' => $error));
		$tmpl = new Gatuf_Template ('APP_LOWER/login/login.html');
		return new Gatuf_HTTP_Response ($tmpl->render ($context));
	}
	
	function logout ($request, $match) {
		$success_url = Gatuf::config ('after_logout_page', '/');
		$user_model = Gatuf::config('gatuf_custom_user','Gatuf_User');
		
		$request->user = new $user_model ();
		$request->session->delete ();
		//$request->session->setData ('logout_time', gmdate('Y-m-d H:i:s'));
		if (0 !== strpos ($success_url, 'http')) {
			$murl = new Gatuf_HTTP_URL ();
			$success_url = Gatuf::config('APP_LOWER_base').$murl->generate($success_url);
		}
		
		return new Gatuf_HTTP_Response_Redirect ($success_url);
	}
	
	function passwordRecoveryAsk ($request, $match) {
		$title = __('Password Recovery');
		
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_Login_PasswordRecovery ($request->POST);
			if ($form->isValid ()) {
				$key = $form->save ();
				$url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Login::passwordRecoveryInputCode');
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
			$form = new APP_CAMEL_CASE_Form_Login_PasswordRecovery ();
		}
		
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/login/recuperarcontra-ask.html',
		                                         array ('page_title' => $title,
		                                         'form' => $form),
		                                         $request);
	}
	
	function passwordRecoveryInputCode ($request, $match) {
		$title = __('Password Recovery');
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_Login_PasswordInputKey($request->POST);
			if ($form->isValid ()) {
				$key = $form->save ();
				$url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Login::passwordRecovery', array ($key));
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
		 	$form = new APP_CAMEL_CASE_Form_Login_PasswordInputKey ();
		}
		
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/login/recuperarcontra-inputkey.html',
		                                         array ('page_title' => $title,
		                                         'form' => $form),
		                                         $request);
	}
	
	function passwordRecovery ($request, $match) {
		$title = __('Password Recovery');
		$key = $match[1];
		
		$email_id = APP_CAMEL_CASE_Form_Login_PasswordInputKey::checkKeyHash($key);
		if (false == $email_id) {
			$url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Login::passwordRecoveryInputCode');
			return new Gatuf_HTTP_Response_Redirect ($url);
		}
		$user = new Gatuf_User ($email_id[1]);
		$extra = array ('key' => $key,
		                'user' => $user);
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_Login_PasswordReset($request->POST, $extra);
			if ($form->isValid()) {
				$user = $form->save();
				$request->user = $user;
				$request->session->clear();
				$request->session->setData('login_time', gmdate('Y-m-d H:i:s'));
				$user->last_login = gmdate('Y-m-d H:i:s');
				$user->update ();
				// Establecer un mensaje
				$request->user->setMessage(1, __('Welcome back! Next time, you can use your broswer options to remember the password.'));
				$url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views::index');
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
			$form = new APP_CAMEL_CASE_Form_Login_PasswordReset (null, $extra);
		}
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/login/recuperarcontra-reset.html',
		                                         array ('page_title' => $title,
		                                         'new_user' => $user,
		                                         'form' => $form),
		                                         $request);
	}
}
