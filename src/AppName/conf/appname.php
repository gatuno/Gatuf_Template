<?php
$cfg = array();

$cfg['debug'] = true;

$cfg['admins'] = array(
	array('Gatuno Admin', 'mail@mail.com'),
);

# Llave de instalación,
# Debe ser única para esta instalación y lo suficientemente larga (40 caracteres)
# Puedes generar una llave con:
#	$ dd if=/dev/urandom bs=1 count=64 2>/dev/null | base64 -w 0
$cfg['secret_key'] = '';

# ---------------------------------------------------------------------------- #
#                                   Rutas                                      #
# ---------------------------------------------------------------------------- #

# Carpeta temporal donde la aplicación puede crear plantillas complicadas,
# datos en caché y otros recursos temporales.
# Debe ser escribible por el servidor web.
$cfg['tmp_folder'] = '/tmp';

# Ruta a la carpeta PEAR
$cfg['pear_path'] = '/usr/share/php';

$cfg['upload_path'] = dirname(__FILE__).'/../../../upload';

# Ruta de los templates
$cfg['template_folders'] = array(
   dirname(__FILE__).'/../templates',
);

# ---------------------------------------------------------------------------- #
#                                URL section                                   #
# ---------------------------------------------------------------------------- #

# Ejemplos:
# Tienes:
#   http://www.mydomain.com/myfolder/index.php
# Pon:
#   $cfg['calif_base'] = '/myfolder/index.php';
#   $cfg['url_base'] = 'http://www.mydomain.com';
#
# Tienes activado mod_rewrite:
#   http://www.mydomain.com/
# Pon:
#   $cfg['calif_base'] = '';
#   $cfg['url_base'] = 'http://www.mydomain.com';
#
$cfg['APP_LOWER_base'] = '/index.php';
$cfg['url_base'] = 'http://www.mydomain.com';
$cfg['url_media'] = 'http://www.mydomain.com/media';

$cfg['APP_LOWER_views'] = dirname(__FILE__).'/urls.php';

# ---------------------------------------------------------------------------- #
#                      Sección de internacionalización                         #
# ---------------------------------------------------------------------------- #

# La zona horaria
# La lista de zonas horarios puede ser encontrado aqui
# <http://www.php.net/manual/en/timezones.php>
$cfg['time_zone'] = 'America/Mexico_City';

$cfg['languages'] = array('en');

# ---------------------------------------------------------------------------- #
#                             Database section                                 #
# ---------------------------------------------------------------------------- #
#
#

$cfg['db_engine'] = 'MySQL';

# El nombre de la base de datos para MySQL y PostgreSQL, y la ruta absoluta
# al archivo de la base de datos si estás usando SQLite.
$cfg['db_database'] = '';

# El servidor a conectarse
$cfg['db_server'] = 'localhost';

# Información del usuario.
$cfg['db_login'] = '';
$cfg['db_password'] = '';

# Un prefijo para todas tus tabla; esto puede ser útil si piensas correr
# multiples instalaciones en la misma base de datos.
$cfg['db_table_prefix'] = '';

# -----------------------
#        Correo
# -----------------------

$cfg['send_emails'] = true;
$cfg['mail_backend'] = 'smtp';
$cfg['mail_host'] = 'localhost';
$cfg['mail_port'] = 25;

# the sender of all the emails.
$cfg['from_email'] = '';

# Email address for the bounced messages.
$cfg['bounce_email'] = '';

# -----------------------
# Configuraciones varias
# -----------------------

$cfg['middleware_classes'] = array(
	'Gatuf_Middleware_Session',
	'Gatuf_Middleware_Translation',
);

# Usuario personalizado
//$cfg['gatuf_custom_user'] = 'APP_CAMEL_CASE_User';

$cfg['template_tags'] = array (
	'custom_getmsgs' => 'APP_CAMEL_CASE_Template_Tag_Messages',
);

# Permite que los usuarios se "autoregistren"
$cfg['enable_register'] = false;

$cfg['installed_apps'] = array('Gatuf', 'APP_CAMEL_CASE');

$cfg['gatuf_log_file'] = '/tmp/gatuf.log';
Gatuf_Log::$level = Gatuf_Log::ALL;

return $cfg;
