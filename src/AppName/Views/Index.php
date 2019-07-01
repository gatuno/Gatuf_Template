<?php

Gatuf::loadFunction('Gatuf_HTTP_URL_urlForView');
Gatuf::loadFunction('Gatuf_Shortcuts_RenderToResponse');

class APP_CAMEL_CASE_Views_Index {
	public $index_precond = array ('Gatuf_Precondition::loginRequired');
	public function index ($request, $match) {
		return Gatuf_Shortcuts_RenderToResponse ('APP_LOWER/index.html', array ('page_title' => __('Start page')), $request);
	}
}

