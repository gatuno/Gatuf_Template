<?php

Gatuf::loadFunction('Gatuf_HTTP_URL_urlForView');
Gatuf::loadFunction('Gatuf_Shortcuts_RenderToResponse');

class APP_CAMEL_CASE_Views_Users {
	public $index_precond = array ('Gatuf_Precondition::adminRequired');
	public function index ($request, $match) {
		$user_model = Gatuf::config('gatuf_custom_user', 'Gatuf_User');
		
		$pag = new Gatuf_Paginator (new $user_model);
		
		$pag->action = array ('APP_CAMEL_CASE_Views_Users::index');
		$pag->summary = __('List of users');
		
		$lista_display = array (
			array ('login', 'Gatuf_Paginator_DisplayVal', __('User')),
			array ('first_name', 'Gatuf_Paginator_DisplayVal', __('Name')),
			array ('last_name', 'Gatuf_Paginator_DisplayVal', __('Last Name')),
		);
		
		$pag->items_per_page = 20;
		$pag->no_results_text = __('No users found');
		$pag->max_number_pages = 5;
		$pag->edit_action = array ('APP_CAMEL_CASE_Views_Users::ver', 'id');
		
		$pag->configure ($lista_display);
		
		$pag->setFromRequest ($request);
		
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/users/index.html',
		                                         array('page_title' => __('Users'),
		                                         'paginador' => $pag),
		                                         $request);
	}
	
	public $agregar_precond = array ('Gatuf_Precondition::adminRequired');
	public function agregar ($request, $match) {
		$title = __('Add new user');
		
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_User_Agregar ($request->POST);
			
			if ($form->isValid ()) {
				$n_user = $form->save ();
				
				$url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Users::ver', array ($n_user->id));
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
			$form = new APP_CAMEL_CASE_Form_User_Agregar (null);
		}
		
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/users/agregar.html',
		                                         array ('page_title' => $title,
		                                                'form' => $form),
		                                         $request);
	}
	
	public $ver_precond = array ('Gatuf_Precondition::loginRequired');
	public function ver ($request, $match) {
		$title = __('View user');
		
		$user_model = Gatuf::config('gatuf_custom_user', 'Gatuf_User');
		$user = new $user_model;
		if (false === ($user->get($match[1]))) {
			throw new Gatuf_HTTP_Error404 ();
		}
		
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/users/ver.html',
		                                         array ('page_title' => $title,
		                                                'c_user' => $user),
		                                         $request);
	}
	
	public $cambiar_pass_precond = array ('Gatuf_Precondition::loginRequired');
	public function cambiar_pass ($request, $match) {
		$title = __('Change my password');
		
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_User_ChangeOwnPassword ($request->POST, array ('user' => $request->user));
			
			if ($form->isValid ()) {
				$form->save ();
				
				$url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Users::ver', array ($request->user->id));
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
			$form = new APP_CAMEL_CASE_Form_User_ChangeOwnPassword (null, array ('user' => $request->user));
		}
		
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/users/cambiar_mi_pass.html',
		                                         array ('page_title' => $title,
		                                                'form' => $form),
		                                         $request);
	}
	
	public $reset_pass_precond = array ('Gatuf_Precondition::adminRequired');
	public function reset_pass ($request, $match) {
		$title = __('Reset password');
		
		$user_model = Gatuf::config('gatuf_custom_user', 'Gatuf_User');
		$user = new $user_model;
		
		if (false === ($user->get($match[1]))) {
			throw new Gatuf_HTTP_Error404 ();
		}
		
		if ($user->id == $request->user->id) {
			$url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Users::cambiar_pass');
			
			return new Gatuf_HTTP_Response_Redirect ($url);
		}
		
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_User_ChangePassword ($request->POST, array ('user' => $user));
			
			if ($form->isValid ()) {
				$form->save ();
				
				$url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Users::ver', array ($user->id));
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
			$form = new APP_CAMEL_CASE_Form_User_ChangePassword (null, array ('user' => $user));
		}
		
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/users/reset_pass.html',
		                                         array ('page_title' => $title,
		                                                'c_user' => $user,
		                                                'form' => $form),
		                                         $request);
	}
	
	public $actualizar_precond = array ('Gatuf_Precondition::adminRequired');
	public function actualizar ($request, $match) {
		$title = __('Update user');
		
		$user_model = Gatuf::config('gatuf_custom_user', 'Gatuf_User');
		$user = new $user_model;
		
		if (false === ($user->get($match[1]))) {
			throw new Gatuf_HTTP_Error404 ();
		}
		
		if ($request->method == 'POST') {
			$form = new APP_CAMEL_CASE_Form_User_Actualizar ($request->POST, array ('user' => $user));
			
			if ($form->isValid ()) {
				$form->save ();
				
				if ($user->id == $request->user->id) {
					$request->session->setData('gatuf_language', $user->language);
				}
				
				$url = Gatuf_HTTP_URL_urlForView ('APP_CAMEL_CASE_Views_Users::ver', array ($user->id));
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
			$form = new APP_CAMEL_CASE_Form_User_Actualizar (null, array ('user' => $user));
		}
		
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/users/actualizar.html',
		                                         array ('page_title' => $title,
		                                                'c_user' => $user,
		                                                'form' => $form),
		                                         $request);
	}
}
