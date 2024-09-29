<?php

namespace source\JsonModels;

class User
{

    public $id, $name, $balance;

    public function __construct($id, $name, $balance)
    {

        $this->id = $id;
        $this->name = $name;
        $this->balance = $balance;
    }


    public function incrBalance($amount): void
    {
        $this->balance += $amount;
    }

    public function toUserArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'balance' => $this->balance,
        ];

    }

}

//
//class UsersModel
//{
//    private array|null $user_info = [];
//
//    function __construct(string $token)
//    {
//        $users_file = 'C:\\OSPanel\\home\\full-example.local\\public\\db_old\\users.json';
//
//        /**
//         * @DESC db
//         */
//        $this->user_info = json_decode(file_get_contents($users_file), true)[$token] ?? null;
//        ///
//    }
//
//
//    public function getBalance()
//    {
//        return $this->user_info['balance'];
//    }
//
//    public function getID()
//    {
//        return $this->user_info['id'];
//    }
//}
////
////class VipUsers extends UsersModel
////{
////    public function setInfo(array $info): void
////    {
////        $this->user_info = $info;
////    }
////}