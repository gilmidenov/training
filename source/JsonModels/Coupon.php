<?php

namespace source\JsonModels;

use source\Base\Database;

//include 'C:\\OSPanel\\home\\full-example.local\\public\\db_old\\coupons.json';

class Coupon
{
        private $couponse_file;
        private $users_coupons_file;
        private $USERS_COUPONS;
        private $COUPONS;

        public function __construct(Database $db)
        {
            $this->couponse_file = $db ->getCouponsFile();
            $this->users_coupons_file = $db->getUsersCoupon();
            $this->COUPONS = json_decode(file_get_contents($this->couponse_file), true);
            $this->USERS_COUPONS = json_decode(file_get_contents($this->users_coupons_file), true);
        }

        private function saveUsersCoupons()
        {
            file_put_contents($this->users_coupons_file, json_encode($this->USERS_COUPONS, JSON_PRETTY_PRINT));
        }

        public function addCouponToUser($user_token, $coupon_id)
        {
            if(!isset($this->USERS_COUPONS[$user_token])){
                $this->USERS_COUPONS[$user_token] = [
                    'coupon_id' => $coupon_id,
                    'status' => 0
                ];

                $this->saveUsersCoupons();
                return ['success' => 'Coupon added to user'];
            }
            return ['error' => 'Coupon exists'];
        }

}