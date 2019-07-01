<?php

function APP_CAMEL_CASE_Migrations_Install_setup ($params=null) {
	$models = array (
	                 // Poner modelos aquí
	                 );
	
	$db = Gatuf::db ();
	$schema = new Gatuf_DB_Schema ($db);
	foreach ($models as $model) {
		$schema->model = new $model ();
		$schema->createTables ();
	}
	
	foreach ($models as $model) {
		$schema->model = new $model ();
		$schema->createConstraints ();
	}
}

function APP_CAMEL_CASE_Migrations_Install_teardown ($params=null) {
	$models = array (
	                 // Poner modelos aquí
	                 );
	
	$db = Gatuf::db ();
	$schema = new Gatuf_DB_Schema ($db);
	
	foreach ($models as $model) {
		$schema->model = new $model ();
		$schema->dropConstraints();
	}
	
	foreach ($models as $model) {
		$schema->model = new $model ();
		$schema->dropTables ();
	}
}

