<?php
class Woo_Nearby_Order_List extends WP_List_Table{
    
    function __construct($plugin_name, $version) {
        parent::__construct( array(
        'singular'=> 'woo_nearby_order', //Singular label
        'plural' => 'woo_nearby_orders', //plural label, also this well be one of the table css class
        'ajax'   => false //We won't support Ajax for this table
        ));
    }

    function buildOrderData($wpdb, $query){
        $orders = $wpdb->get_results($query);
        return array_map(function($order){
            $meta = wc_get_order( $order->ID );
            $order->post_status = $meta->get_status();
            $order->total = $meta->get_total();
            $order->created_at = $meta->get_date_created();
            return $order;
        }, $orders);
    }

    function getOrders($per_page = 0, $limit = 25, $order = "post_date", $orderby = "DESC"){
        global $wpdb;
        $query = "SELECT * FROM $wpdb->posts as orders
        INNER JOIN $wpdb->postmeta as meta on orders.ID = meta.post_id 
        where orders.post_type='shop_order' AND meta.meta_key='outlet_user' AND meta.meta_value = '" . get_current_user_id() . "'";
        $total_items = $wpdb->query($query);
        $query .= "order by $order $orderby LIMIT $per_page , $limit";
        $orders = $this->buildOrderData($wpdb, $query);
        $total_page = ceil($total_items/$limit);
        return compact('orders', 'total_items', 'per_page', 'total_page');
    }

    function prepare_items(){
        $orders = $this->getOrders();
        $this->items = $orders["orders"];
        $this->set_pagination_args(array(
            "total_items" => $orders["total_items"], 
            "total_pages" => $orders["total_page"], 
            "per_page" => $orders['per_page']
        ));
        $columns = $this->get_columns();
        $this->_column_headers = array($columns);
    }

    function get_columns(){
        return array(
            "ID" => "ID", 
            "post_status" => "Status", 
            "created_at" => "Created", 
            "total" => 'Total'
        );
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'ID':
                return '<a href="' . admin_url() . 'post.php?post='.$item->$column_name.'&action=edit">' . $item->$column_name . '</a>';
            case 'post_status':
                return strtoupper($item->$column_name);
            case 'total':
                return $item->$column_name;
            case 'created_at':
                $date_format = get_option( 'date_format' );
                $time_format = get_option( 'time_format' );
                return date("$date_format $time_format", strtotime($item->$column_name));
            default:
                return "No value";
        }
    }

}