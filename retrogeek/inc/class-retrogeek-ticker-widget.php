<?php
/**
 * Class to create a text widget which displays a message as if it is send via an old slow modem.
 *
 * @package retrogeek
 * @since 22.03.2021
 */

/**
 * Class for Ticker-Widget.
 */
class Retrogeek_Ticker_Widget extends WP_Widget {
	/**
	 * Constructor for Ticker-Widget.
	 */
	public function __construct() {
		parent::__construct(
			'retrogeek_ticket_widget',
			__( 'RetroGeek Ticker-Widget', 'retrogeek' ),
			array( 'description' => __( 'Ticker Widget which shows the text as it were received via a slow modem connection', 'retrogeek' ) )
		);
	}

	/**
	 * Function to display the widget.
	 *
	 * @param array $args - constains the widget arguments.
	 * @param array $instance - contains the instance variables of the widget.
	 */
	public function widget( $args, $instance ) {
		$title        = apply_filters( 'widget_title', $instance['title'] );
		$tickertext   = apply_filters( 'widget_title', $instance['tickertext'] );
		$allowed_tags = wp_kses_allowed_html( 'post' );

		// before and after widget arguments are defined by themes.
		echo wp_kses( $args['before_widget'], $allowed_tags );

		if ( ! empty( $title ) ) {
			echo wp_kses( $args['before_title'] . esc_attr( $title ) . $args['after_title'], $allowed_tags );
		}

		// Add the tickertext and ticker call.
		if ( ! empty( $tickertext ) ) {
			echo '<div class="rg_tickertext" id="' . esc_attr( $this->id ) . '"></div><br />';

			// add the ticker javascript and execution.
			wp_add_inline_script(
				'retrogeek-javascript',
				'rg_terminal("' . esc_attr( $this->id ) . '", "' . esc_attr( $tickertext ) . '", "site-description, rg_tickertext" );',
				'after'
			);
		}

		echo wp_kses( $args['after_widget'], $allowed_tags );
	}

	/**
	 * Function to display the widget backend.
	 *
	 * @param array $instance - contains the instance variables of the widget.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'New title', 'retrogeek' );
		}

		if ( isset( $instance['tickertext'] ) ) {
			$tickertext = $instance['tickertext'];
		} else {
			$tickertext = __( 'New tickertext', 'retrogeek' );
		}

		// Widget admin form.
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_attr( __( 'Title:', 'retrogeek' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'tickertext' ) ); ?>"><?php echo esc_attr( __( 'Tickertext:', 'retrogeek' ) ); ?></label>
		<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tickertext' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tickertext' ) ); ?>"><?php echo esc_attr( $tickertext ); ?></textarea>
		</p>
		<?php
	}

	/**
	 * Function to update the widget data replacing the old values with the new values.
	 *
	 * @param array $new_instance - new valkues of widget instance.
	 * @param array $old_instance - old values of widget instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance               = array();
		$instance['title']      = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['tickertext'] = ( ! empty( $new_instance['tickertext'] ) ) ? wp_strip_all_tags( $new_instance['tickertext'] ) : '';
		return $instance;
	}

	// Class retrogeek_ticker_widget ends here.
}
