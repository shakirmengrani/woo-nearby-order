<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/shakirmengrani
 * @since      1.0.0
 *
 * @package    Woo_Nearby_Order
 * @subpackage Woo_Nearby_Order/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Nearby_Order
 * @subpackage Woo_Nearby_Order/admin
 * @author     Shakir Mengrani <shakirmengrani@gmail.com>
 */
class Woo_Nearby_Order_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-nearby-order-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-nearby-order-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_menu(){
		add_menu_page("Nearby Orders", "Nearby Orders", "manage_woocommerce", "woo-nearby-order",array($this, 'render'));
	}

	function render() {
		$wp_list_table = new Woo_Nearby_Order_List($this->plugin_name, $this->version);
		$wp_list_table->prepare_items();
		$wp_list_table->display();	
	}

	function showUserLocation($user){
		?>
		<h3>User Location</h3>
		<div class="row">
			<div class="col-33ptg p-all-15px">
				<input type="text" name="lat" placeholder="Enter Latitude" class="fullWidth" value="<?php echo get_the_author_meta('user_lat', $user->ID) ?>" />
			</div>
			<div class="col-33ptg p-all-15px">
				<input type="text" name="lng" placeholder="Enter Longitude" class="fullWidth" value="<?php echo get_the_author_meta('user_lng', $user->ID) ?>" />
			</div>
		</div>
		<div class="row">
			<div class="col-100ptg p-all-15px">
				<input type="text" name="user_address" placeholder="Enter address" class="fullWidth" value="<?php echo get_the_author_meta('user_address', $user->ID) ?>" />
			</div>
		</div>
		<?php
	}

	function updateUserLocation($user_id){
		if ( !current_user_can( 'edit_user', $user_id ) ) { 
			return false; 
		}else{
			if(isset($_POST["lat"]) && isset($_POST["lng"])){
				update_usermeta($user_id, "user_lat", $_POST["lat"]);
				update_usermeta($user_id, "user_lng", $_POST["lng"]);
				update_usermeta($user_id, "user_address", $_POST["user_address"]);
			}
		}
	}

}
