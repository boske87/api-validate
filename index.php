<?php
require_once __DIR__ . '/vendor/autoload.php';

use Bramus\Router\Router;
use Performance\Validate\PerformanceValidate;
use Src\Json\JsonHandler;
use Src\Address\Repository\AddressRepository;
use Src\PDO\Database;
// Create Router instance

$router = new Router();


// Static route: / (homepage)
$router->get('/', function() {
    include('views/create_address.html');
});


$router->post('/address', function() {
    print_r(getenv('DATABASE_URL'));
    try {
        $db = new Database(getenv('DATABASE_URL'),  getenv('DB_NAME'), getenv('USER'), getenv('PASS'));
    } catch (Exception $e) {
        throw new Exception("Database error".$e->getMessage());
    }
    $jsonHandler = new JsonHandler();
    //filter input
    $city = filter_input(INPUT_POST, "city");
    $street = filter_input(INPUT_POST, "street");
    $postal = filter_input(INPUT_POST, "postal");
    if($city && $street && $postal) {
        $validate = new PerformanceValidate('cdQgdZl748aukV7KSHis4tJnmizE1uYZO5c6TiYN');
        $params = [
            "city" => $city,
            "street" => $street,
            "postal" => $postal,
            "country" => "Germany"
        ];
        //call api
        $validateRequest =  $validate->verify('address', $params);
        //validate input external api
        if($validateRequest->isValid()) {
            $addressRepo = new AddressRepository($db);
            $addressRepo->create(array($city,$street,$postal, 1));
            $data = $jsonHandler->encode(array("Peter"=>35, "Ben"=>37, "Joe"=>43), true);
            echo $data;
        } else {
            echo $jsonHandler->encode($validateRequest->getError(), true);
        }

    } else {
        echo $jsonHandler->encode(array('input error'), true);
    }

});

$router->set404(function() use ($router){
    var_dump($router);
});
//// Run it!
$router->run();
