<?php
require_once __DIR__ . '/vendor/autoload.php';

use Bramus\Router\Router;
use Performance\Validate\PerformanceValidate;

use Src\Address\Repository\AddressRepository;
use Src\PDO\Database;
// Create Router instance

$router = new Router();


// Static route: / (homepage)
$router->get('/', function() {
    include('views/create_address.html');
});


$router->post('/address', function() {
    try {
        $db = new Database('127.0.0.1', 'test', 'root', '');
    } catch (Exception $e) {
        throw new Exception("Database error".$e->getMessage());
    }

    //filter input
    $city = filter_input(INPUT_POST, "city");
    $street = filter_input(INPUT_POST, "street");
    $postal = filter_input(INPUT_POST, "postal");
    if($city && $street && $postal) {
        $validate = new PerformanceValidate('cdQgdZl748aukV7KSHis4tJnmizE1uYZO5c6TiYN');
        $params = [
            "city" => $city,
            "street" => $street,
            "postal" => $postal
        ];
        //validate input external api
        if($validate->verify('address', $params)) {
            $addressRepo = new AddressRepository($db);
            $addressRepo->create(array($city,$street,$postal));
        }
    } else {
        echo 'input error';
    }

});

$router->set404(function() use ($router){
    var_dump($router);
});
//
//// Run it!
$router->run();
////
//$tt = new PerformanceValidate();
//
//$params = [
//    "city" => "Trier",
//    "street" => "Universitätsring 19",
//    "postal" => "54296"
//
//];
////
//$ttt = $tt->verify('address', $params);
//var_dump($ttt);