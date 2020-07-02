<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use DI\ContainerBuilder;
use todo\includes\Lists;
/*############### includes for connection library and classes###############*/
require_once(dirname(__DIR__). "/todo/includes/idiorm.php");
require_once(dirname(__DIR__). "/todo/includes/connect.php");
require_once(dirname(__DIR__). "/todo/includes/classes.php");
/*########################################################################*/
require __DIR__ . '/../vendor/autoload.php';
$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->get('/', function (Request $request, Response $response, $args) {
	try{
		$renderer = new PhpRenderer('../todo');
		$lists = \ORM::for_table('list')->order_by_asc('t_order')->find_array();
		return $renderer->render($response, "index.php",  ['alllist' => $lists]);
	} catch(Exception $ex){
		print_r(array($ex->getMessage(), $ex->getTraceAsString()));
	}
});
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});
$app->get('/list', function (Request $request, Response $response, $args) {
	try{
		$lists = \ORM::for_table('list')->order_by_asc('t_order')->find_array();
		$lists = json_encode(['data'=>$lists]);
		$response->getBody()->write($lists);
		return $response;
	} catch(Exception $ex){
		print_r(array($ex->getMessage(), $ex->getTraceAsString()));
	}
});
/*############### Routes Start From Here ###############*/

/*############### insert call ###############*/
$app->post('/insert', function (Request $request, Response $response, $args) {
	$obj = new Lists();
	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);
	//echo "<pre>";var_dump($postdata);echo"</pre>";
	$list = $postdata->data->text;
	$insert = $obj->insert($list);
	if($insert =="success"){
		$lastrecord=$obj->Lastrecord();
		$response->getBody()->write($lastrecord);
		return $response;
	}
});
/*############### insert call ###############*/

/*############### colorupdate call ###############*/
$app->post('/colorupdtae', function (Request $request, Response $response, $args) {
	$obj = new Lists();
	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);
	//echo "<pre>";var_dump($postdata);echo"</pre>";
	$id = $postdata->data->id;
	$color = $postdata->data->color;
	$update = $obj->updateclr($color,$id);
	$response->getBody()->write(json_encode(['data'=>$update]));
	return $response;
});
/*############### colorupdate call ###############*/

/*############### markread call ###############*/
$app->post('/markread', function (Request $request, Response $response, $args) {
	$obj = new Lists();
	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);
	$id = $postdata->data->id;
	$status = $postdata->data->status;
	$obj->updatemark($id,$status);
	$response->getBody()->write(json_encode(['data'=>$update]));
	return $response;
});
/*############### markread call ###############*/

/*############### updatetext call ###############*/
$app->post('/updatetext', function (Request $request, Response $response, $args) {
	$obj = new Lists();
	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);
	$id = $postdata->data->id;
	$text = $postdata->data->text;
	$update = $obj->updatetext($text,$id);
	$response->getBody()->write(json_encode(['data'=>$update]));
	return $response;
});
/*############### updatetext call ###############*/

/*############### delete call ###############*/
$app->post('/delete', function (Request $request, Response $response, $args) {
	$obj = new Lists();
	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);
	$id = $postdata->data;
	$delete = $obj->deletethat($id);
	// $lists = \ORM::for_table('list')->order_by_asc('t_order')->find_array();
	// $lists = json_encode(['data'=>$lists]);
	$response->getBody()->write(json_encode(['data'=>$delete]));
	return $response;
});
/*############### delete call ###############*/

/*############### updatepositions call ###############*/
$app->post('/updatepositions', function (Request $request, Response $response, $args) {
	$obj = new Lists();
	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);
	$listarray = $postdata->data;
	foreach ($listarray as $key => $value) {
		$obj-> updateposition($key,$value);
	}
	return $response;
});
/*############### updatepositions call ###############*/

/*############### Routes end From Here ###############*/
$app->run();
