<?php

use source\FileModels\ProductsModel;
use source\FileModels\UsersOrdersModel;
use source\JsonModels\User;

//include 'C:\OSPanel\home\full-example.local\public\DB\source\JsonModels\Users.php';

require_once 'C:\OSPanel\home\full-example.local\public\autoloader.php';

try {
    $product_model = new UsersOrdersModel();

    /**
     * @desc insert
     */
    $result = $product_model->insert(
        ['asdasdadsa'],
        [
            [
                'id' => 10,
                'order_id' => 5,
                'user_id' => 1
            ]
        ],
        [
            'id',
            'order_id',
            'user_id'
        ]
    );

    var_dump($result);die;
    /**
     * ----------
     */


    /**
     * @desc update
     */
//    $result = $product_model->update(
//        ['1'],
//        [
//            ['price' => 100005]
//        ]
//    );
//
//    echo '<pre>';
//    var_dump($result);
//    echo '</pre>';

    /**
     * ----------
     */

    /**
     * @desc select
     */
    $model = $product_model->select('1');
//    var_dump($model->id);
//    var_dump($model->name);
//    var_dump($model->price);



//    echo '<pre>';
//    var_dump($model->price);
//    echo '</pre>';
    /**
     * ----------
     */
    /**
     * @desc delete
//     */
//    $result = $product_model->delete(['1']);
//
//    var_dump($result);
//    /**
//     * ----------
//     */
} catch (\Exception $exception) {
    die($exception->getMessage());
}
/**
 * @TODO У юзера есть несколько товаров в корзине и он решил удалить один из товаров
 * @TODO deleteFromBasket , тебе нужно возвращать баланс и удалять из users_orders id товара в корзине
 *
 * @TODO e купона есть id, count, discount = 10% (90% стоимость товара)
 * @TODO users_coupons (coupon_id, status [1, 0], user_id)
 * @TODO addCoupon(discount = 10%), добавить новую табличку, купоны (Coupons)
 */


// 25/09/2024
/**
 * @TODO разберись с классами, попробуй каждый из методов ипосмотри как это вообще работает
 * @TODO добавь новых, попробуй извлеч старых, обновить данные и удалить
 */
// 1. addusers?users=Angel,Lion,Snick&balance=1,5,10
// $explode = explode(',', 'Angel,Lion,Snick');
//$user = new User('1', '1', '1');
////напомни про купоны
//
//$array = [
//    $user->id,
//    $user->balance,
//    $user->name
//];
////$user->setInfo(['test' => '1']);
//var_dump($user);

//--------------------------
/*
$array1 = array('key' => 'value');

$array2 = [
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
    'key4' => 'value4'
];

$array3 = ['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3', 'key4' => 'value4'];


$array[] = 'value1';
$array['val'] = 'value2';
//echo '<pre>';
//var_dump($array);
//
$json = json_encode($array);
//echo '</pre>';
echo '<pre>';
//var_dump($json);
echo '</pre>';
echo '<pre>';
$obj = json_decode($json, false);
//var_dump($obj);
echo '</pre>';

$obj->test = 42;

//print_r($obj->test);
$ch = curl_init('https://www.php.net/manual/ru/class.stdclass.php');
echo '<pre>';
var_dump($ch);
echo '</pre>';
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, true);
$html = curl_exec($ch);

curl_close($ch);


var_dump($html);

function re() {

}

/**
* @TODO лекция по Классам -----------------------
*/
//var_dump(http_build_query($_POST));
//class Functions
//{
//    function generateSimpleToken($length = 32)
//    {
//        return bin2hex(random_bytes($length / 2));
//    }
//
//    static function generateStaticToken($length = 32)
//    {
//        return bin2hex(random_bytes($length / 2));
//    }
//}
////$token = Functions::generateStaticToken();
////var_dump($token);
////
////$token = (new Functions)->generateStaticToken();
////die;
////->
///**
// * @property int|mixed|null $balance
// */
//class Customer
//{
//
//    public static function newObject()
//    {
//        return new static(
//            Functions::generateStaticToken(10),
//            Functions::generateStaticToken(8)
//        );
//    }
//
//    //параметры класса | свойства класса
//    public string $token, $name, $surname, $orders, $wallet;
//
//    public array $array_values = [];
//
//    function __construct(string $name, string $surname)
//    {
//        $this->name = $name;
//        $this->surname = $surname;
//        $this->token = Functions::generateStaticToken();
//    }
//
//    function __get($name)
//    {
//        return $this->array_values[$name] ?? null;
//    }
//
//    function __set($name, $value)
//    {
//        $this->array_values[$name] = $value;
//    }
//
//    function getInfo(): array
//    {
//        return [
//            $this->name,
//            $this->surname,
//            $this->orders,
//            $this->wallet
//        ];
//    }
//
//    function __destruct()
//    {
//
//        die;
//    }
//}
//
//class VipCustomer extends Customer
//{
//
//
//}
//
////$customer = new Costumer('Oleg', 'Kamikadze');
//$customer = VipCustomer::newObject();
//$customer->balance = 11;
//
//
//echo $customer->balance;

//if ($customer->balance)
//echo "<pre>";
//var_dump($customer);
//echo "</pre>";
//$customer = null;
//die;

//
//$users_orders = [0 => new UsersOrders(['id' => 1]), new UsersOrders()];
//
//$users_orders[0]->id;

















