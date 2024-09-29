<?php

use source\Base\Database;
use source\JsonModels\Coupon;
use source\JsonModels\Product;
use source\JsonModels\User;
use source\FileModels\ProductsModel;

ini_set('error_reporting', 1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'C:\OSPanel\home\full-example.local\public\autoloader.php';

function generateToken($length = 32)
{
    return bin2hex(random_bytes($length / 2));
}


function sendResponse($data)
{
    exit(json_encode($data, JSON_PRETTY_PRINT));
}



    $users_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db\\users.json';
    $products_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db\\products.json';
    $orders_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db\\orders.json';
    $users_order_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db\\users_orders.json';

/**
 * @DESC db
 */



//    $USERS = json_decode(file_get_contents($users_file), true);
//    $ORDERS = json_decode(file_get_contents($orders_file), true);
//    $PRODUCTS = json_decode(file_get_contents($products_file), true);
//    $USERS_ORDERS = json_decode(file_get_contents($users_order_file), true);

/**
 * @DESC END db
 */


function showText(array $variables) {
    echo '<pre>';
    echo implode('<br>' , $variables);
    echo '</pre>';
}

/** @Функции Карзины */

function createBasket($user_token) {

    global $USERS_ORDERS;

     if (!isset($USERS_ORDERS[$user_token]['basket'])){
         $USERS_ORDERS[$user_token]['basket'] = [];
     }

     return $USERS_ORDERS[$user_token]['basket'];
}


function addToBasket($user_token, $product_id) {

    global $PRODUCTS,  $users_order_file, $USERS, $USERS_ORDERS, $users_order_file, $users_file ;

    createBasket($user_token);

    if (!isset($PRODUCTS[$product_id])) {
        sendResponse(['error' => 'Product not found']);
    }

    $product = $PRODUCTS[$product_id];
    $user_balance = $USERS[$user_token]['balance'];

    if($user_balance < $product['price']){
        sendResponse(['error' => 'Not enough balance']);
    }

    $USERS_ORDERS[$user_token]['basket'][] = $product;
    $USERS[$user_token]['balance'] -= $product['price'];

    file_put_contents($users_order_file, json_encode($USERS_ORDERS, JSON_PRETTY_PRINT));
    file_put_contents($users_file, json_encode($USERS, JSON_PRETTY_PRINT));

    return $USERS_ORDERS[$user_token]['basket'];
}


function removeFromBasket($user_token, $product_id)
{
    global $USERS_ORDERS, $users_order_file, $USERS, $users_file;


    createBasket($user_token);

    $product_found = false;
    $product_price = 0;


    foreach ($USERS_ORDERS[$user_token]['basket'] as $key => $product) {
        if ($product['id'] == $product_id) {
            $product_price = $product['price'];
            unset($USERS_ORDERS[$user_token]['basket'][$key]);
            $product_found = true;
            break;
        }
    }

    if($product_found) {
        $USERS[$user_token]['balance'] += $product_price;

        file_put_contents($users_order_file, json_encode($USERS_ORDERS, JSON_PRETTY_PRINT));
        file_put_contents($users_file, json_encode($USERS, JSON_PRETTY_PRINT));
    }else{
        sendResponse(['error' => 'Product not found in basket']);
    }


    return $USERS_ORDERS[$user_token]['basket'];
}


function viewBasket($user_token)
{
    global $USERS_ORDERS;


    return $USERS_ORDERS[$user_token]['basket'] ?? [];
}


/** @Функции Карзины end */

function addMultipleUsers($users, $balances){

    global $USERS, $users_file;
    $user_list = explode(',', $users);
    var_dump($user_list);
    $balance_list = explode(',', $balances);

    if (count($user_list) != count($balance_list)){

        sendResponse(['error' => 'UsersModel and Balance']);
    }

    foreach ($user_list as $index => $user_name) {

        $user_token = generateToken(32);
        $new_user = [
            'id' => count($USERS) + 1,
            'name' => $user_name,
            'balance' => $balance_list[$index]
        ];
        $USERS[$user_token] = $new_user;
    }
    file_put_contents($users_file, json_encode($USERS, JSON_PRETTY_PRINT));
    sendResponse($USERS);
}


function addMulipleProducts($products, $prices){
    global $PRODUCTS, $products_file;

    $product_list = explode(',', $products);
    $prices_list = explode(',', $prices);

    if (count($product_list) != count($prices_list)) {
        sendResponse(['error' => 'ProductsModel and Prices mismatch']);
    }

    foreach ($product_list as $index => $product_name){
        $product_id = generateToken(32);
        $new_product = [
            'id' => count($PRODUCTS) + 1,
            'name' => $product_name,
            'price' => $prices_list[$index]
        ];
        $PRODUCTS[$product_id] = $new_product;
    }

    file_put_contents($products_file, json_encode($PRODUCTS, JSON_PRETTY_PRINT));
    sendResponse($PRODUCTS);
}


$RULES = [
    '_POST' => [

        'adduser' => [
            'token',
            'product_id',
            'name',
            'balance'
        ],
        'multusers' => [
            'token',
            'users',
            'product_id',
            'name',
            'balance'
        ],
        'multproducts' => [
            'token',
            'products',
            'product_id',
            'name',
            'prices'
        ],

        'addproduct' => [
            'token',
            'name',
            'price'
        ],

        'order' => [
            'token',
            'product_id'
        ],

        'addToBasket' => [
            'token',
            'product_id'
        ],

        'removeFromBasket' => [
            'token',
            'product_id'
        ],

        'addCoupon' =>[
            'token',
            'product_id',
            'coupon_id'
        ],
    ],

    '_GET' => [

        'getusers' => [
            'token',
            'product_id'

        ],
        'insert' => [
            'token',
            'product_id'

        ],
        'update' => [
            'token',
            'product_id'

        ],
        'delete' => [
            'token',
            'product_id'

        ],
        'select' => [
            'token',
            'product_id'

        ],

        'getinfo' => [
            'token',
            'product_id'
        ],

        'getproducts' => [
            'token',
            'product_id'

        ],

        'getproduct' => [
            'token',
            'product_id',
        ],

        'getorders' => [
            'token',
            'product_id'
        ],

        'viewBasket' => [
            'token',
            'product_id'

        ]


    ]
];

$DB = new Database();
$Coupon = new Coupon($DB);
$product_model = new ProductsModel;

$request_method = '_' . $_SERVER['REQUEST_METHOD'];

$request = isset($$request_method) ? $$request_method['action'] : null;

if (array_key_exists($request, $RULES[$request_method])) {
    Logger::write(http_build_query($$request_method), 'requests');
    $variables = [];

    foreach ($RULES[$request_method][$request] as $key => $value){
        $variables[$value] = $$request_method[$value] ?? null;
    }

    if(array_key_exists('token', $variables)){


        $USERS = $DB -> getUsers();
        $PRODUCTS = $DB -> getProducts();
        $ORDERS = $DB -> getOrders();
        $USERS_ORDERS = $DB -> getUsersOrder();

        $user = $USERS[$variables['token']] ?? null;
        $product = $PRODUCTS[$variables['product_id']] ?? null;

    }else{
        sendResponse(["error" => "token not found"]);
    }


    switch ($request){
        case 'getusers':
            sendResponse($USERS);

        case 'getinfo':
            sendResponse($user);

        case "getproduct":
            sendResponse($product);

        case 'getproducts':
            sendResponse($PRODUCTS);

        case 'getorders':
            sendResponse($ORDERS);

        case 'adduser':
            $user_token = generateToken(32);

            $new_user =  new User(
                count($USERS) + 1,
                $variables['name'],
                $variables['balance']
            );

            $USERS[$user_token] = $new_user ->toUserArray();
            $DB -> saveUsers($USERS);
//            createBasket($user_token);
//
//            file_put_contents($users_file, json_encode($USERS, JSON_PRETTY_PRINT));
            sendResponse($USERS);

        case 'multusers':
            addMultipleUsers($variables['users'], $variables['balance']);

            break;

        case 'multproducts':
            addMulipleProducts($variables['products'], $variables['prices']);

            break;

        case 'addproduct':
            $product_id = generateToken(32);

            $new_product = new Product(
                count($PRODUCTS) + 1,
                $variables['name'],
                $variables['price']
            );

            $PRODUCTS[$product_id] = $new_product -> toProductArray();

            $DB->saveProducts($PRODUCTS);

            file_put_contents($products_file, json_encode($PRODUCTS, JSON_PRETTY_PRINT));
            sendResponse($PRODUCTS);

        case 'order':
            $user_balance = $USERS[$variables['token']]['balance'];
            $product_price = $PRODUCTS[$variables['product_id']]['price'];


            if($user_balance >= $product_price){
                $order_id = count($ORDERS) + 1;
                $new_order = [
                    'user_id' => $variables['token'],
                    'products' => $PRODUCTS[$variables['product_id']],
                ];


                $ORDERS[$order_id] = $new_order;
                file_put_contents($orders_file, json_encode($ORDERS, JSON_PRETTY_PRINT));
                $USERS_ORDERS[$variables['token']][] =  $order_id;
                file_put_contents($users_order_file, json_encode($USERS_ORDERS, JSON_PRETTY_PRINT));


                $USERS[$variables['token']]['balance'] -= $product_price;
                file_put_contents($users_file, json_encode($USERS, JSON_PRETTY_PRINT));


                sendResponse($ORDERS);
            }else{
                sendResponse(['error' => 'Not enough balance']);
            }


        case 'addToBasket':
            sendResponse(addToBasket($variables['token'], $variables['product_id']));

        case 'removeFromBasket':
            sendResponse(removeFromBasket($variables['token'], $variables['product_id']));

        case 'viewBasket':
            sendResponse(viewBasket($variables['token']));
        case 'addCoupon':
//            if(!isset($variables['coupon_id'])){
//                sendResponse(['error'=> 'ID not found']);
//            }

            $response = $Coupon->addCouponToUser($variables['token'], $variables['coupon_id']);

            sendResponse($response);

        case 'insert':
            $response = $product_model->insert(
                ['3'],
                [
                    [
                        'id' => 3,
                        'name' => 'Smartphone',
                        'price' => '2000'

                    ]
                ]
            );

            sendResponse($response);

        case 'update':
            $response = $product_model->update(
                ['3'],
                [
                    [
                        'price' => 5500
                    ]
                ]
            );

            sendResponse($response);

        case 'delete':
            $response = $product_model->delete(['3']);

            sendResponse($response);

        case 'select':
            $response = $product_model->select('3');

            showText([
                $response->id,
                $response->name,
                $response->price
            ]);
//            echo '<pre>' . $response->id . '<br>' . $response->name;
//            var_dump($response->id, $response->name, $response->price);
//            echo '</pre>';


    }
}
sendResponse(['error' => 'bad token']);
