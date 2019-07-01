<?php

require dirname(__FILE__).'/../src/APP_CAMEL_CASE/conf/path.php';

# Cargar Gatuf
require 'Gatuf.php';

# Inicializar las configuraciones
Gatuf::start(dirname(__FILE__).'/../src/APP_CAMEL_CASE/conf/APP_LOWER.php');

Gatuf_Despachador::loadControllers(Gatuf::config('APP_LOWER_views'));

Gatuf_Despachador::despachar(Gatuf_HTTP_URL::getAction());
