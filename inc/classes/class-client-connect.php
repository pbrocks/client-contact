<?php

add_action( 'wp_dashboard_setup', array( 'PBrx_Client_Connect', 'init' ) );

class PBrx_Client_Connect {
	/**
	 * A unique id/slug for this widget.
	 */
	const WIDGET = 'client-connect';

	/**
	 * Hook to wp_dashboard_setup to add the widget.
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'wp_ajax_send_contact_form_email', array( __CLASS__, 'send_contact_form_email' ) );

		// Register widget settings...
		self::update_dashboard_widget_options(
			self::WIDGET,
			array(
				'title_bar'       => 'Connect with us here!',
				'contact_header'  => 'Contact Form Header',
				'opening_message' => 'This site, ' . get_bloginfo( 'name' ) . ', was delivered by us!',
				'current_user'    => self::user( 'user_login' ),
				'our_logo'        => self::user( 'our_logo' ),
				'first_name'      => self::user( 'first_name' ),
				'last_name'       => self::user( 'last_name' ),
				'user_email'      => self::user( 'user_email' ),
				'admin_email'     => get_bloginfo( 'admin_email' ),
				'show_welcome'    => '',
			),
			true
		);

		// Register the widget...
		wp_add_dashboard_widget( self::WIDGET, self::get_dashboard_widget_option( self::WIDGET, 'title_bar' ), array( __CLASS__, 'widget' ), array( __CLASS__, 'widget_configuration' )
		);
	}

	/**
	 * Enqueue scripts and styles
	 */
	public static function enqueue( $hook ) {
		if ( 'index.php' === $hook ) {
			wp_enqueue_style( 'client-connect', plugins_url( '../css/client-connect.css', __FILE__ ) );
			wp_enqueue_script( 'client-connect1', plugins_url( '../js/client-connect.js', __FILE__ ), array( 'jquery' ) );
		}
	}
	/**
	 * Load the widget code
	 */
	public static function widget() {
		$sample_logo_url = plugins_url( '../images/sample-logo.png', __FILE__ );
		?>
		<h3><?php echo self::get_dashboard_widget_option( self::WIDGET, 'opening_message' ); ?></h3>
		<div class="block">

		<div class="header">
			<h2><?php echo esc_html( self::get_dashboard_widget_option( self::WIDGET, 'contact_header' ) ); ?></h2>
		</div>

		<div id="slide">
			<?php $connect_form = self::contact_form(); ?>
		</div>
		<div class="text">
			<div class="logo-area">
			<?php $logo = self::get_dashboard_widget_option( self::WIDGET, 'our_logo' );
			if ( esc_url( $logo ) ) {
				?>
				<img src="<?php echo esc_url( $logo ); ?>" />
			<?php } else { ?>
				<img src="<?php echo esc_url( $sample_logo_url ); ?>" />
			<?php } ?>
			</div>
		</div>
		</div>
	<?php
	}

	/**
	 * Get user information.
	 *
	 * This is what will display when an admin clicks
	 *
	 * @$param string
	 */
	public static function user( $param ) {
		$user = wp_get_current_user();
		return $user->$param;
	}

	/**
	 * Load widget widget_configuration code.
	 *
	 * This is what will display when an admin clicks
	 */
	public static function widget_configuration() {
		$widget = self::WIDGET;
		if ( $widget === $_REQUEST['widget_id'] ) {

			self::update_dashboard_widget_options( $widget, $_REQUEST[ $widget ] );
		}
	?>

		<h2>Configurable Options</h2>

		<h4>
		Customize the widget using the fields below:
		</h4>

	<h2 class="nav-tab-wrapper">
	  <a class="nav-tab nav-tab-active" href="#">Hide Other Widgets</a>
	  <a class="nav-tab" href="#">Configure Client Connect</a>
	</h2>

	<div id='sections'>
	<section>This alters the main dashboard screen re widgets
	<?php
	/**
	 * [$checked (required) One of the values to compare.]
	 * Default: None
	 * @checked mixed 
	 */
	$checked = self::get_dashboard_widget_option( $widget, 'show_welcome' );

	/**
	 * [$current (optional) The other value to compare if not just true.]
	 * Default: true
	 * @current mixed 
	 */
	$current = '1';
	/**
	 * [$echo (optional) Whether to echo or just return the string.]
	 * Default: true
	 * @echo boolean 
	 */
		$dashboard_options = get_option( 'dashboard_widget_options' );

	echo '<pre>';
	print_r( $dashboard_options );
	echo '</pre>';
	echo '<input type="hidden" name="todo_postback" />';

	echo '<p><label><input type="checkbox" name="todo_show_age" ';
	if ( self::get_dashboard_widget_option( $widget, 'show_welcome' ) ) { echo 'checked '; }
	echo '/>Display freakin Welcome?? </label></p>';

	echo '<p><label><input type="checkbox" name="todo_show_completed" ';
	if ( $dashboard_options['todo_options']['todo_show_completed'] ) { echo 'checked ';}
	echo '/>Display completed option? </label></p>';

	echo '<p><label><input type="checkbox" name="todo_cleanup" ';
	if ( $dashboard_options['todo_options']['todo_cleanup'] ) { echo 'checked ';}
	echo '/>Remove all items and settings when deleting plugin? </label></p>';


	?>
		<p>
			<label for="<?php echo $widget; ?>-welcome"><?php _e( 'Show Welcome Bar:','pbrx-client-connect' ) ?></label>
			<input id="<?php echo $widget; ?>-welcome" name="<?php echo $widget; ?>[show_welcome]" type="checkbox" checked( $checked, $current ) value="<?php echo self::get_dashboard_widget_option( $widget, 'show_welcome' ); ?>" />
			<h3><?php echo self::get_dashboard_widget_option( $widget, 'show_welcome' ); ?> is the setting</h3>
		</p>

	</section>
	<section>
		<p>
		<label for="<?php echo $widget; ?>-title"><?php _e( 'Title Bar:','pbrx-client-connect' ) ?></label><br>
		<input id="<?php echo $widget; ?>-title" name="<?php echo $widget; ?>[title_bar]" type="text" value="<?php echo self::get_dashboard_widget_option( $widget, 'title_bar' ); ?>" />
		</p>
		<p>
		<label for="<?php echo $widget; ?>-message"><?php _e( 'Opening Message:','pbrx-client-connect' ) ?></label><br>
		<input id="<?php echo $widget; ?>-message" name="<?php echo $widget; ?>[opening_message]" type="text" value="<?php echo self::get_dashboard_widget_option( $widget, 'opening_message' ); ?>" />
		</p>
		<p>
		<label for="<?php echo $widget; ?>-header"><?php _e( 'Contact Form Header:','pbrx-client-connect' ) ?></label><br>
		<input id="<?php echo $widget; ?>-header" name="<?php echo $widget; ?>[contact_header]" type="text" value="<?php echo self::get_dashboard_widget_option( $widget, 'contact_header' ); ?>" />
		</p>
		<p>
		<label for="<?php echo $widget; ?>-logo"><?php _e( 'Logo Image URL:','pbrx-client-connect' ) ?></label>
		<input id="<?php echo $widget; ?>-logo" name="<?php echo self::WIDGET; ?>[our_logo]" type="url" value="<?php echo self::get_dashboard_widget_option( self::WIDGET, 'our_logo' ); ?>" />
		</p>
		<p>
		<label for="<?php echo $widget; ?>-email"><?php _e( 'Send email to:','pbrx-client-connect' ) ?></label>
		<input id="<?php echo $widget; ?>-email" name="<?php echo self::WIDGET; ?>[admin_email]" type="email" value="<?php echo self::get_dashboard_widget_option( self::WIDGET, 'admin_email' ); ?>" />
		</p>
	</div>
	</section>
	<?php
	}

	/**
	 * Gets the options for a widget of the specified name.
	 *
	 * @param string $widget_id Optional. If provided, will only get options for the specified widget.
	 * @return array An associative array containing the widget's options and values. False if no widget options found.
	 */
	public static function get_dashboard_widget_options( $widget_id = '' ) {
		// Fetch ALL dashboard widget options from the db...
		$options = get_option( 'dashboard_widget_options' );

		// If no widget is specified, return everything
		if ( empty( $widget_id ) ) {
			return $options;
		}

		// If we request a widget and it exists, return it
		if ( isset( $options[ $widget_id ] ) ) {
			return $options[ $widget_id ];
		}

		// Something went wrong...
		return false;
	}

	/**
	 * Gets one specific option for the specified widget.
	 *
	 * @param $widget_id
	 * @param $option
	 * @param null      $default
	 *
	 * @return string
	 */
	public static function get_dashboard_widget_option( $widget_id, $option, $default = null ) {

		$options = self::get_dashboard_widget_options( $widget_id );

		// If widget options dont exist, return false
		if ( ! $options ) {
			return false;
		}

		// Otherwise fetch the option or use default
		if ( isset( $options[ $option ] ) && ! empty( $options[ $option ] ) ) {
			return $options[ $option ];
		} else {
			return ( isset( $default ) ) ? $default : false;
		}

	}

	/**
	 * Saves an array of options for a single dashboard widget to the database. Can also be used to define default
	 * values for a widget.
	 *
	 * @param string $widget_id The name of the widget being updated
	 * @param array  $args An associative array of options being saved.
	 * @param bool   $add_only If true, options will not be added if widget options already exist
	 * @return bool
	 */
	public static function update_dashboard_widget_options( $widget_id, $args = array(), $add_only = false ) {
		// Fetch ALL dashboard widget options from the db...
		$options = get_option( 'dashboard_widget_options' );

		// Get just our widget's options, or set empty array
		$w_options = ( isset( $options[ $widget_id ] ) ) ? $options[ $widget_id ] : array();

		if ( $add_only ) {
			// Flesh out any missing options (existing ones overwrite new ones)
			$options[ $widget_id ] = array_merge( $args,$w_options );
		} else {
			// Merge new options with existing ones, and add it back to the widgets array
			$options[ $widget_id ] = array_merge( $w_options,$args );
		}
			// Save the entire widgets array back to the db
			return update_option( 'dashboard_widget_options', $options );
	}

	public static function connect_form() {
		?>
		<form action="">
			First name:<br>
			<input type="text" value="<?php echo self::user( 'first_name' ); ?>"><br>
			Last name:<br>
			<input type="text" name="lastname" value="<?php echo self::user( 'last_name' ); ?>"><br>
			<br>
			<input type="submit" value="Submit">
		</form> 
	<?php
	}

	public static function send_contact_form_email() {
		$to = self::get_dashboard_widget_option( self::WIDGET, 'admin_email' );
		$subject = 'Donation';
		$message = 'message message message message message message message ';

		if ( wp_mail( $to, $subject, $message ) ) {
			echo 'mail sent';
		} else {
			echo 'mail not sent';
		}
		die();
	}

	public function contact_form() {
		?>
		<div id="respond">
		<?php echo $response; ?>
			<form action="<?php the_permalink(); ?>" method="post">
			<p><label for="name">Subject:<br><input type="text" name="message_name" value="<?php echo esc_attr( $_POST['message_name'] ); ?>"></label></p>
			<p><label for="message_text">Message:<br><textarea type="text" name="message_text"><?php echo esc_textarea( $_POST['message_text'] ); ?></textarea></label></p>
			<input type="hidden" name="submitted" value="1">
			<p><input type="submit" class="button button-primary"></p>
			</form>
		</div>
	<?php
	}

	// function to generate response
	public function my_contact_form_generate_response( $type, $message ) {
		global $response;
		if ( 'success' === $type ) {
			$response = "<div class='success'>{$message}</div>";
		} else {
			$response = "<div class='error'>{$message}</div>";
		}
	}
}
