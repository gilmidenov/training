<?php

require_once 'C:\OSPanel\home\full-example.local\public\autoloader.php';

use source\FileModels\UsersModel;
use source\FileModels\ProductsModel;
use source\FileModels\BasketModel;

header('Content-Type: application/json; charset=utf-8');
/**
 * @TODO Сделать корзину;
 * @TODO 1. Добавить товар в корзину(определенный емаил/токен) добавляет товар в корзину(addProductToBasket)
 * @TODO 2.1 Просмотреть корзину([название товара, количество товаров, стоимость, ...(может быть что то еще)], общая стоимость)
 * @TODO 2.2 У каждого пользователя есть 1 корзина, в корзине может быть множество товаров, их можно добавлять и удалять
 * @TODO 3. Удалить товар/товары(несколько одинаковых) из корзины
 * @TODO 4. Купить корзину(пользователь приобретает все товары, баланс и т.д.)
 *
 * @TODO. Добавь в корзину к товару сount, price.
 */
/**
 *
 * @TODO 1. Создашь папку Data class RequestVariables - __construct() {$_GET, $_POST, $request_type}
 * @TODO через объект класса можно получить доступ к переменным, массив данных() и магические методы php.net
 * @TODO Есть объект класса $request_variables, $request_variables->action v зависимости от типа запроса
 * @TODO Возвращает либо данные определенного типа запроса, либо NULL
 *
 * @TODO 2. Создашь папку Api class ProductMarket - __construct() {$request_variables}
 * @TODO $action = str_replace('_', '', $action) => $product_market->$action();
 * @TODO вызываешь метод объекта класса $product_market->addUser()
 * @TODO т.е. ты закинешь свои switch case в методы. Если все окей, возвращаешь массив
 * @TODO [status => success, ...], если такого метода нет, то [status => error, message => no method]
 *
 * @TODO делаешь вывод json_encode()
 *
 */
$method = $_SERVER['REQUEST_METHOD'];
$action = $_REQUEST['action'] ?? null;
$data = [];

if ($method === 'POST') {
    $data = $_POST;
} else {
    $data = $_GET;
}

//echo "<pre>";
//var_dump($data);

$response = [];
try {
    switch ($action) {
        case 'add_user':
            if ($method === 'POST') {
                $name = $data['name'] ?? null;
                $balance = $data['balance'] ?? null;
                $email = $data['email'] ?? null;
                $token = $data['token'] ?? bin2hex(random_bytes(16));

                if ($name !== null && $balance !== null && $email !== null) {

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception('Incorrect email format');
                    }

                    if (strlen($token) !== 32) {
                        throw new Exception('The token must contain 32 characters');
                    }

                    $users_model = new UsersModel();

                    $email_exists = $users_model
                        ->select(['id'])
                        ->where(['email = ' . $email])
                        ->execute();

                    if (!empty($email_exists)) {
                        throw new Exception('User with this email already exist');
                    }

                    $token_exists = $users_model
                        ->select(['id'])
                        ->where(['token = ' . $token])
                        ->execute();

                    if (!empty($token_exists)) {
                        throw new Exception('The token already exists');
                    }

                    $count_users = count(
                        $users_model
                            ->select(['id'])
                            ->where(['id > 0'])
                            ->execute()
                    );

                    $users_model
                        ->insert([
                            'id' => $count_users + 1,
                            'name' => $name,
                            'balance' => $balance,
                            'email' => $email,
                            'token' => $token
                        ])
                        ->execute();

                    $response = ['status' => 'success', 'message' => 'User added'];
                } else {
                    throw new Exception('Necessary data not provided');
                }
            }

            break;

        case 'delete_user':
            if ($method === 'POST') {
                $id = $data['id'] ?? null;

                if ($id !== null) {
                    $users_model = new UsersModel();

                    $users_model
                        ->delete()
                        ->where(['id = ' . $id])//@TODO условия нужно добавлять со сравнением
                        ->execute();

                    $response = ['status' => 'success', 'message' => 'User deleted'];
                } else {
                    throw new Exception('Necessary data not provided');
                }
            }

            break;

        case 'add_product':
            if ($method === 'POST') {
                $name = $data['name'] ?? null;
                $price = $data['price'] ?? null;

                if ($name && $price !== null) {
                    $products_model = new ProductsModel();

                    $count_products = count(
                        $products_model
                            ->select(['id'])
                            ->where(['id > 0'])
                            ->execute()
                    );
//                    echo "<pre>";
//                    var_dump($count_products);

                    $products_model
                        ->insert([
                            'id' => $count_products + 1,
                            'name' => $name,
                            'price' => $price
                        ])
                        ->execute();

                    $response = [
                        'status' => 'success',
                        'message' => 'Product added'];
                } else {
                    throw new Exception('Necessary data not provided');
                }
            }

            break;

        case 'delete_product':
            if ($method === 'POST') {
                $id = $data['id'] ?? null;

                if ($id !== null) {
                    $products_model = new ProductsModel();

                    $products_model
                        ->delete()
                        ->where(['id = ' . $id])
                        ->execute();

                    $response = [
                        'status' => 'success',
                        'message' => 'Product deleted'
                    ];
                } else {
                    throw new Exception('Necessary data not provided');
                }
            }

            break;

        case 'buy_product':
            if ($method === 'POST') {
                $user_email = $data['user_email'] ?? null;
//                $user_id = $data['user_id'] ?? null;
                $product_name = $data['product_name'] ?? null;
//                $product_id = $data['product_id'] ?? null;

                if ($user_email && $product_name) {
                    $users_model = new UsersModel();
                    $product_model = new ProductsModel();

                    $user = $users_model
                        ->select(['id', 'balance'])
                        ->where(['email = ' . $user_email])
                        ->execute();

                    if (empty($user)) {
                        throw new Exception('User not found');
                    }

                    echo '<pre>';
                    var_dump($user);
                    echo '<pre>';

                    $user = $user[0];

                    $product = $product_model
                        ->select(['id, name, price'])
                        ->where(['name = ' . $product_name])
                        ->execute();

                    if (empty($product)) {
                        throw new Exception('Product not found');
                    }
                    echo '<pre>';
                    var_dump($product);
                    echo '<pre>';

                    $product = $product[0];

                    if ($user['balance'] < $product['price']) {
                        throw new Exception('Not enough balance');
                    }

                    $new_balance = $user['balance'] - $product['price'];

                    $users_model
                        ->update(['balance = ' . $new_balance])
                        ->where(['email = ' . $user_email])
                        ->execute();

                    $response = ['status' => 'success', 'message' => 'Purchase successfully completed'];
                } else {
                    throw new Exception('Necessary data not provided');
                }
            }

            break;

        case 'add_to_basket':
            if ($method === 'POST') {
                $user_email = $data['email'] ?? null;
                $product_id = $data['product_id'] ?? null;
                $count_product = $data['count'] ?? 1;

                if ($user_email && $product_id) {
                    $user_model = new UsersModel();
                    $products_model = new ProductsModel();
                    $basket_model = new BasketModel(['force' => true]);

                    $user = $user_model
                        ->select(['id'])
                        ->where(['email = ' . $user_email])
                        ->execute();

                    if (empty($user)) {
                        throw new Exception('user not found');
                    }


                    $product = $products_model
                        ->select(['id'])
                        ->where(['id = ' . $product_id])
                        ->execute();


                    $product = $product[0];
//                    echo "<pre>";
//                    var_dump($product['id']);
//                    echo "<pre>";
//                    die();

                    if (empty($product)) {
                        throw new Exception('product not found');
                    }

//                    echo "<pre>";
//                    var_dump($user[0]['token']);
//                    echo "<pre>";

//                    $count_basket = count(
//                        $basket_model
//                        ->select(['id'])
//                        ->where(['id > 0'])
//                        ->execute()
//                    );
//
//                    var_dump($count_basket);

                    $basket = $basket_model
                        ->select(['id'])
                        ->where(['user_email = ' . $user_email])
                        ->execute();


                    if (empty($basket)) {

                        $basket_id = uniqid();

                        $item = $product['id'];

                        $total_price = $product['price'];

                        $basket_model
                            ->insert([
                                'id' => $basket_id,
                                'user_email' => $user_email,
                                'item' => $item,
                                'count' => $count_product,
                                'price' => $total_price
                            ])
                            ->execute();
                    } else {
                        $basket_id = $basket[0]['id'];
                        $item = $basket[0]['item'];
                        $count = $basket[0]['count'];
                        $price = $basket[0]['price'];


                        $count += $count_product;

//                        echo '<pre>';
//                        echo 'items:';
//                        var_dump($items);
//                        echo '<pre>';
//                        die();
                        //@TODO если product id добавлен, смотреть его сount и делать +1

                        $basket_model
                            ->update([
                                "count = $count",
                            ])
                            ->where([
                                "id = $basket_id",
                                "item = $item",
                            ])
                            ->execute();
                    }


                    $response = ['status' => 'success', 'message' => 'Product added to basket'];
                } else {
                    throw new Exception('Necessary data not provided');
                }
            } else {
                throw new Exception('Wrong action');
            }

            break;


        case 'view_basket':
            if ($method === 'GET') {
                $user_token = $data['token'] ?? null;

                if ($user_token) {
                    $users_model = new UsersModel();
                    $basket_model = new BasketModel();

                    $user = $users_model
                        ->select(['id'])
                        ->where(['token = ' . $user_token])
                        ->execute();

                    if (empty($user)) {
                        throw new Exception('user not found');
                    }

                    $user_email = $user[0]['email'];

                    $basket = $basket_model
                        ->select(['id'])
                        ->where(['user_email = ' . $user_email])
                        ->execute();

                    if (empty($basket)) {
                        throw new Exception('Basket not found');
                    } else {


                    }


                }
            }

        default:
            throw new Exception('Wrong action');
    }
} catch (Exception $message) {
//    http_response_code(400);
    $response = ['status' => 'error', 'message' => $message->getMessage()];
}

echo json_encode($response);
