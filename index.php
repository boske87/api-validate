<?php
require_once __DIR__ . '/vendor/autoload.php';

use Bramus\Router\Router;
use Performance\Validate\PerformanceValidate;
use Src\Address\Repository\UserRepository;
use Src\Json\JsonHandler;
use Src\Address\Repository\AddressRepository;
use Src\PDO\Database;
// Create Router instance

$router = new Router();
$jsonHandler = new JsonHandler();
try {
    $db = new Database('eu-cdbr-west-02.cleardb.net',  'heroku_9b46c2768f3e5c3', 'b748e98c5422ac', 'b120993e');
} catch (\Exception $e) {
    throw new Exception("Database error".$e->getMessage());
}

// Static route: / (homepage)
$router->get('/', function() {
    include('views/create_address.html');
});


$router->post('/address', function() use ($db, $jsonHandler) {
    //filter input
    $city = filter_input(INPUT_POST, "city");
    $street = filter_input(INPUT_POST, "street");
    $postal = filter_input(INPUT_POST, "postal");
    $first_name = filter_input(INPUT_POST, "first_name");
    $last_name = filter_input(INPUT_POST, "last_name");
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
            $addressId = $addressRepo->create(array($city,$street,$postal, 1));
            $userRepo = new UserRepository($db);
            $userRepo->create(array($first_name, $last_name, $addressId));
            $data = $jsonHandler->encode(array('status' => true), true);
            echo $data;
        } else {
            echo $jsonHandler->encode($validateRequest->getError(), true);
        }

    } else {
        echo $jsonHandler->encode(array('input error'), true);
    }

});

// Static route: / (homepage)
$router->get('/getAll', function() use ($db, $jsonHandler) {
    $userRepo = new UserRepository($db);
    $allUsers = $userRepo->getAll();
    $data = $jsonHandler->encode($allUsers, true);
    echo $data;
});


$router->set404(function() use ($router){
    var_dump($router);
});
//// Run it!
$router->run();
