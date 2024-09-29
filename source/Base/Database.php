<?php

namespace source\Base;
class Database
{

    private $users_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db_old\\users.json';
    private $products_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db_old\\products.json';
    private $orders_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db_old\\orders.json';
    private $users_order_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db_old\\users_orders.json';
    private $coupons_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db_old\\coupons.json';
    private $user_coupons_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db_old\\users_coupons.json';


    public function getUsers()
    {
        return json_decode(file_get_contents($this->users_file), true);
    }

    public function getProducts()
    {
        return json_decode(file_get_contents($this->products_file), true);
    }

    public function getOrders()
    {
        return json_decode(file_get_contents($this->orders_file), true);
    }

    public function getUsersOrder()
    {
        return json_decode(file_get_contents($this->users_order_file), true);
    }

    public function saveUsers($users)
    {
        file_get_contents($this->users_file, json_encode($users, JSON_PRETTY_PRINT));
    }

    public function saveProducts($products)
    {
        file_put_contents($this->products_file, json_encode($products, JSON_PRETTY_PRINT));
    }

    public function getCouponsFile()
    {
        return $this->coupons_file;
    }

    public function getUsersCoupon()
    {
        return $this->user_coupons_file;
    }

}