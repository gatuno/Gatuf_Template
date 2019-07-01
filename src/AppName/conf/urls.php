<?php
$base = Gatuf::config('APP_LOWER_base');
$ctl = array ();

/* Bloque base:
$ctl[] = array (
	'regex' => '#^/ /$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views',
	'method' => '',
);
*/

/* Sistema de login, y vistas base */
$ctl[] = array (
	'regex' => '#^/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Index',
	'method' => 'index',
);

$ctl[] = array (
	'regex' => '#^/login/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Login',
	'method' => 'login',
	'name' => 'login_view'
);

$ctl[] = array (
	'regex' => '#^/logout/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Login',
	'method' => 'logout',
);

/* Recuperar contraseña */
$ctl[] = array (
	'regex' => '#^/password/recovery/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Login',
	'method' => 'passwordRecoveryAsk',
);

$ctl[] = array (
	'regex' => '#^/password/recovery/ik/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Login',
	'method' => 'passwordRecoveryInputCode',
);

$ctl[] = array (
	'regex' => '#^/password/recovery/k/(.*)/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Login',
	'method' => 'passwordRecovery',
);

/* Registro de nuevos usuarios */
if (Gatuf::config ('enable_register', false) !== false) {
	$ctl[] = array (
		'regex' => '#^/register/$#',
		'base' => $base,
		'model' => 'APP_CAMEL_CASE_Views_Register',
		'method' => 'register',
	);

	$ctl[] = array (
		'regex' => '#^/register/ik/$#',
		'base' => $base,
		'model' => 'APP_CAMEL_CASE_Views_Register',
		'method' => 'inputKey',
	);

	$ctl[] = array (
		'regex' => '#^/register/k/(.*)/$#',
		'base' => $base,
		'model' => 'APP_CAMEL_CASE_Views_Register',
		'method' => 'confirmation',
	);
}

/* Gestión de usuarios */
$ctl[] = array (
	'regex' => '#^/users/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Users',
	'method' => 'index',
);

$ctl[] = array (
	'regex' => '#^/users/add/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Users',
	'method' => 'agregar',
);

$ctl[] = array (
	'regex' => '#^/users/(\d+)/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Users',
	'method' => 'ver',
);

$ctl[] = array (
	'regex' => '#^/users/(\d+)/update/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Users',
	'method' => 'actualizar',
);

$ctl[] = array (
	'regex' => '#^/users/(\d+)/reset/password/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Users',
	'method' => 'reset_pass',
);

$ctl[] = array (
	'regex' => '#^/password/change/$#',
	'base' => $base,
	'model' => 'APP_CAMEL_CASE_Views_Users',
	'method' => 'cambiar_pass',
);

return $ctl;
