<?php
include_once 'config.php';

Flight::route('/', function(){
	define ( 'VERSION', "6.1.0.1" );
	define ( 'AUTHOR', "N.Suvethan" );
	define ( 'MODIFIED', "2017-12-28" );
	define ( 'DESCRIPTION', "Course API" );

	echo nl2br ( "Author - " . AUTHOR . "\n" );
	echo nl2br ( "Version - " . VERSION . "\n" );
	echo nl2br ( "Company - Ascentic" . "\n" );
	echo nl2br ( "Last modified Date - " . MODIFIED . "\n" );
	echo nl2br ( "Description - " . DESCRIPTION );
});

Flight::route('GET /categories', function () {
    $skip = $_GET["skip"];
	$take = $_GET["take"];
	$order = $_GET["order"];
	$categoryHandler=new CategoryHandler();
	$data=$categoryHandler->getAllCategories($skip,$take,$order);
	Flight::json($data);
});

Flight::route('POST /category', function () {
    $data = Flight::request()->data;
	$categoryHandler=new CategoryHandler();
	$data=$categoryHandler->insertCategory($data);
	Flight::json($data);
});

Flight::route('GET /category/@id', function ($id) {
	$categoryHandler=new CategoryHandler();
	$data=$categoryHandler->getById($id);
	Flight::json($data);
});

Flight::route('GET /courses', function () {
	$skip = $_GET["skip"];
	$take = $_GET["take"];
	$order = $_GET["order"];
	$courseHandler=new CourseHandler();
	$data=$courseHandler->getAllCourses($skip,$take,$order);
	Flight::json($data);
});

Flight::route('POST /course', function () {
	$data = Flight::request()->data;
	$courseHandler=new CourseHandler();
	$data=$courseHandler->insertCourse($data);
	Flight::json($data);
});

Flight::route('GET /course/@id', function ($id) {
	$courseHandler=new CourseHandler();
	$data=$courseHandler->getById($id);
	Flight::json($data);
});

Flight::route('POST /upload/@fileName/@folder', function ($filename,$folder) {
	$data = Flight::request()->getBody();
	$courseHandler=new CourseHandler();
	$data=$courseHandler->uploadFile($data,$filename,$folder);
	Flight::json($data);
});

Flight::route('OPTIONS /*', function() {
	Flight::json('Security check has been passed');
});

Flight::start();
?>