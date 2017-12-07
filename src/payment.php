<?php

namespace Vendi\RotaryFlyer;

class payment {
    /**
     * A reference to an instance of this class.
     */
    private static $_instance;

    public static function get_instance()
    {
        if( ! self::$_instance )
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private $price;
    private $rotary_user;

    //default constructor
    private function __construct($user_id){
        $this->price = 5.00;

        //!IMPORTANT! change $user_id to get user by id
        $this->user = $user_id;
    }

    //constructor for handling price change
    private function __construct($ad_price, $user_id)
    {
        $this->price = $ad_price;

        //!IMPORTANT! change $user_id to get user by id
        $this->user = $user_id;
    }

    //add to owed balance
    private function add_to_owed_amount($number_purchased){

    }

    //add amount tokens to account
    private function add_to_account_tokens($number_purchased){

    }

    //deduct token
    private function deduct_from_account_tokens($number_used){

    }

    //deduct from owed balance
    private function deduct_from_owed_balance($amount_paid){

    }

    //public function that calls add_to_owed_amount and add_to_account_tokens
    public function purchase_tokens($number_to_purchase){

    }

    //public function that calls deduct_from_account_balance
    public function pay_balance($amount_to_pay){

    }

    public function get_available_tokens(){

    }

    public function get_current_balance(){

    }

    public function get_total_ad_count(){

    }

    public function get_total_ad_count_in_date_range(){

    }

    public static function init(){
        self::get_instance();
    }
}
