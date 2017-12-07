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

    private $price = 0;
    private $rotary_user = -1;
    private $id;
    //default constructor
    public function __construct($user_id){
        if( ! $this->price )
        {
            $this->price = 5.00;
        }
        if( ! $this->user )
        {
            $this->user = 'user_'.$user_id;
        }
        if( ! $this->id)
        {
            $this->id = $user_id;
        }
    }

    //add to owed balance
    private function add_to_owed_amount($number_purchased){
        $current = $this->get_current_balance();
        $additional = (int)$number_purchased * floatval($this->price);
        $total = $current + $additional;
        update_field('field_5a29a466c8593', $total, $post_id);
    }

    //add amount tokens to account
    private function add_to_account_tokens($number_purchased){
        $current = (int)$this->get_available_tokens();
        $additional = (int)$number_purchased;
        $total = $current + $additional;
        update_field('field_5a29b5970fc47', $total, $post_id);
    }

    //deduct token
    private function deduct_from_account_tokens($number_used){
        $current = (int)$this->get_available_tokens();
        $removal = (int)$number_used;
        $total = $current + $removal;
        update_field('field_5a29b5970fc47', $total, $post_id);
    }

    //deduct from owed balance
    private function deduct_from_owed_balance($amount_paid){
        $current = $this->get_current_balance();
        $total = $current - $amount_paid;
        update_field('field_5a29a466c8593', $total, $post_id);
    }

    //public function that calls add_to_owed_amount and add_to_account_tokens
    public function purchase_tokens($number_to_purchase){
        $this->add_to_owed_amount($number_purchased);
        $this->add_to_account_tokens($number_to_purchase);
    }

    //public function that calls deduct_from_account_balance
    public function pay_balance($amount_to_pay){
        $this->deduct_from_owed_balance($amount_paid);
    }

    public function get_available_tokens(){
        return get_field('field_5a29b5970fc47', $this->user);
    }

    public function get_current_balance(){
        return get_field('field_5a29a466c8593', $this->user);
    }

    public function get_total_ad_count(){
        $args = array(
            'author'        =>  $this->id,
            'orderby'       =>  'post_date',
            'order'         =>  'ASC',
            'post_type'     =>  'vendi-rotary-flyer'
        );
        return count(get_posts( $args ));
    }

    public function get_total_ad_count_in_date_range($start_date, $end_date){
        $args = array(
            'author'        =>  $this->id,
            'orderby'       =>  'post_date',
            'order'         =>  'ASC',
            'post_type'     =>  'vendi-rotary-flyer',
            'date_query' => array(
                array(
                    'after'     => $start_date,
                    'before'    => $end_date,
                    'inclusive' => true,
                ),
            )
        );
        return count(get_posts( $args ));
    }

    public function get_user_id(){
        return $this->user;
    }

    public static function init(){
        self::get_instance();
    }
}
