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
        $current = floatval($this->get_current_balance());
        $additional = intval($number_purchased) * $this->price;
        $total = $current + $additional;
        $result = update_field('field_5a29a466c8593', $total, $this->user);
    }

    //add amount tokens to account
    private function add_to_account_tokens($number_purchased){
        $current = intval($this->get_available_tokens());
        $additional = intval($number_purchased);
        $total = $current + $additional;
        $result = update_field('field_5a29b5970fc47', $total, $this->user);
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
        $this->add_to_owed_amount($number_to_purchase);
        $this->add_to_account_tokens($number_to_purchase);
    }

    //public function that calls deduct_from_account_balance
    public function pay_balance($amount_to_pay){
        $this->deduct_from_owed_balance($amount_to_pay);
    }

    public function get_available_tokens(){
        return get_field('field_5a29b5970fc47', $this->user);
    }

    public function get_current_balance(){
        return get_field('field_5a29a466c8593', $this->user);
    }

    public function get_total($number_purchased){
        return (int)$number_purchased * floatval($this->price);
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

    public function generate_transaction_id(){
        $stamp = date("Ymdhis");
        $ip = $_SERVER['REMOTE_ADDR'];
        $orderid = "$stamp-$ip";
        $orderid = str_replace(".", "", "$orderid");
        return $orderid;
    }

    //checks if wordpress table for record tracking exists.
    //if it exists, it inserts a new row
    //if it does not exist, table is created and first row is entered.
    public function payment_records($quantity, $confirmation_id){
        global $wpdb;
        $table_name = $wpdb->prefix.'payment_records';

        //check if table exists. If not, create it.
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
             //table not in database. Create new table
             $charset_collate = $wpdb->get_charset_collate();

             $sql = "CREATE TABLE $table_name (
                  id mediumint(9) NOT NULL AUTO_INCREMENT,
                  user text NOT NULL,
                  quantity text NOT NULL,
                  price DOUBLE(8, 2),
                  confirmation_id varchar(50) NOT NULL,
                  UNIQUE KEY id (id)
             ) $charset_collate;";
             require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
             dbDelta( $sql );
        }
        //insert our new row
        $wpdb->insert(
            $table_name,
            array(
                'user' => $this->id,
                'quantity' => (int)$quantity,
                'price' => floatval($this->price),
                'confirmation_id' => $confirmation_id
            )
        );
    }

    public function check_db_for_transaction_id($transaction_id){
        global $wpdb;
        $table_name = $wpdb->prefix.'payment_records';
        $sql = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE confirmation_id = %s", $transaction_id);
        $results = $wpdb->get_var($sql);
        if($results > 0 && $results != null){
            //it is in the database already
            return true;
        }
        else{
            //it is not in the database already
            return false;
        }
    }

    public function get_user_id(){
        return $this->user;
    }

    public function get_price(){
        return floatval($this->price);
    }

    public static function init(){
        self::get_instance();
    }
}
