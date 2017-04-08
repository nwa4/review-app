<?php

try {
	//Autoloader
	$loader = new \Phalcon\Loader();
	$loader->registerDirs([
		'../app/controllers',
		'../app/models/'
	]);
	$loader->register();

	//Dependency Injection
	$di = new \Phalcon\DI\FactoryDefault();
	
	$di->set('view', function(){
		$view = new \Phalcon\Mvc\View();
		$view->setViewsDir('../app/views');
		return $view;
	});
	
	$di->set('db', function(){
		$db = new \Phalcon\Db\Adapter\Pdo\Mysql([
			'host'=>'localhost',
			'username'=>'root',
			'password'=>'',
			'dbname'=>'review-app'
		]);
		return $db;
	});
	
	// Sesion
	$di->setShared(
		"session", function () {
			$session = new Phalcon\Session\Adapter\Files();
			$session->start();
			return $session;
		}
	);
	session_start();

	//Application Deployment
	$app = new \Phalcon\Mvc\Application($di);
	echo $app->handle()->getContent();


} catch (\Phalcon\Exception $e) {
	echo $e->getMessage();
}