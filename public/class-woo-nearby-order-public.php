<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/shakirmengrani
 * @since      1.0.0
 *
 * @package    Woo_Nearby_Order
 * @subpackage Woo_Nearby_Order/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Nearby_Order
 * @subpackage Woo_Nearby_Order/public
 * @author     Shakir Mengrani <shakirmengrani@gmail.com>
 */
class Woo_Nearby_Order_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Nearby_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Nearby_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-nearby-order-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Nearby_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Nearby_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-nearby-order-public.js', array( 'jquery' ), $this->version, false );

	}

	public function checkout_order($order_id){
		$order = new WC_Order($order_id);
		$orderMeta = $_order->get_meta("location");
		if($orderMeta){
			$location = explode(",", $_order->get_meta("location"));
			$args = array("role" => "shop_manager");
			$users = get_users($args);
			$_distance = array();
			foreach($users as $user){
				$userMeta = get_user_meta($user->ID);
				if(array_key_exists("user_lat", $userMeta) && array_key_exists("user_lng", $userMeta)){
					$point = $this->getDistanceBetweenPoints(trim($location[0]), trim($location[1]), $userMeta["user_lat"], $userMeta["user_lng"]);
					$_distance[$user->ID] = $point["kilometers"];
				}
			}
			if(count($_distance) > 0){
				ksort($_distance);
				$_distance = array_keys($_distance);
				$order->update_meta_data("outlet_user", $_distance[0]);
				$order->save();
			}
		}
	}

	/**
	 * Calculates the distance between two points, given their 
	 * latitude and longitude, and returns an array of values 
	 * of the most common distance units
	 *
	 * @param  {coord} $lat1 Latitude of the first point
	 * @param  {coord} $lon1 Longitude of the first point
	 * @param  {coord} $lat2 Latitude of the second point
	 * @param  {coord} $lon2 Longitude of the second point
	 * @return {array} Array of values in many distance units
	 */
	private function getDistanceBetweenPoints($latitudeFrom, $longitudeFrom, $latitudeTo,  $longitudeTo) {
		// Function Guard
		// if ( empty($longitudeFrom) && empty($longitudeTo) && empty($latitudeFrom) && empty($latitudeTo) ) return 0;
		// if ( ($longitudeFrom == $longitudeTo) && ($latitudeFrom == $latitudeTo) ) return 0;

		$long1 = deg2rad($longitudeFrom);
		$long2 = deg2rad($longitudeTo);
		$lat1 = deg2rad($latitudeFrom);
		$lat2 = deg2rad($latitudeTo);
		
		//Haversine Formula
		$dlong = $long2 - $long1;
		$dlati = $lat2 - $lat1;

		$val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2);
		$res = 2 * asin(sqrt($val));
		$radius = 3958.756;
		
		$kilometers = $res * $radius;
		$miles = $kilometers * 0.62137;
		$feet = $miles * 5280.0;
		$yards = $miles * 1760.0;
		$meters = $feet / 3.2808;

		return compact('miles','feet','yards','kilometers','meters');
	}

}
