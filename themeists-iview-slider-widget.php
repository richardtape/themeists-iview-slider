<?php

	if( !class_exists( 'WidgetImageField' ) )
		require_once( 'class.WidgetImageField.php' );

	if( !class_exists( 'themeists_iview_slider_widget' ) )
	{

		class themeists_iview_slider_widget extends WP_Widget
		{
		
			
			/**
			 * The name shown in the widgets panel
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 */
			
			const name 		= 'Themeists iView Slider';

			/**
			 * For helping with translations
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 */

			const locale 	= 'themeistsiviewslider';

			/**
			 * The slug for this widget, which is shown on output
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 */
			
			const slug 		= 'themeists_iview_slider_widget';

			var $image_field = 'image';
		

			/* ============================================================================ */
		
			/**
			 * The widget constructor. Specifies the classname and description, instantiates
			 * the widget, loads localization files, and includes necessary scripts and
			 * styles. 
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 * @param None
			 * @return None
			 */
			
			function themeists_iview_slider_widget()
			{
		
				load_plugin_textdomain( self::locale, false, plugin_dir_path( dirname( __FILE__ ) ) . '/languages/' );

				//Load the new image size (as early as we possibly can for this widget)
				add_action( 'register_sidebar', array( &$this, 'add_slider_image_size' ), 10 );

		
				$widget_opts = array (

					'classname' => 'themeists_iview_slider_widget', 
					'description' => __( 'An incredibly powerful image and content slider', self::locale )

				);

				$control_options = array(

					'width' => '400'

				);

				//we need to add a filter to plugins_url as we use symlinks in our dev setup
				add_filter( 'plugins_url', array( &$this, 'local_dev_symlink_plugins_url_fix' ), 10, 3 );

				//Register the widget
				$this->WP_Widget( self::slug, __( self::name, self::locale ), $widget_opts, $control_options );
		
		    	// Load JavaScript and stylesheets
		    	$this->register_scripts_and_styles();
		
			}/* themeists_iview_slider_widget() */
		

			/* ============================================================================ */


			/**
			 * Outputs the content of the widget.
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 * @param (array) $args - The array of form elements
			 * @param (array) $instance - The saved options from the widget controls
			 * @return None
			 */
			

			function widget( $args, $instance )
			{
		
				extract( $args, EXTR_SKIP );
		
				//Get vars
	    		$title					=	$instance['title'];
	    		$subtitle				=	$instance['subtitle'];
	    		$button_text			=	$instance['button_text'];
	    		$button_link			=	$instance['button_link'];
	    		$menu_title				=	$instance['menu_title'];

	    		//Image
	    		$image_id   = $instance[$this->image_field];

	    		wp_enqueue_script( 'jquery' );
		      	wp_enqueue_script( 'iview-slider-js', plugins_url( 'assets/js/themeists-iview-slider-front.js' , __FILE__ ), array( 'jquery' ), false, true );

		      	wp_enqueue_style( 'iview-slider-css', plugins_url( 'assets/css/themeists-iview-slider-front.css' , __FILE__ ) );

	    		//If this theme has a template file for this widget use it. It should be in
	    		//  /templates/iviewslider-widget/widget.php

	    		if( file_exists( locate_template( '/templates/iviewslider-widget/widget.php', false  ) ) ) :
	
					include locate_template( '/templates/iviewslider-widget/widget.php' );
			
				else : 

					echo $before_widget;

		    		?>

			    		<div class=" themeists_iview_container">

			    			<div class="iview_slider" id="inner_<?php echo $args['widget_id']; ?>">

			    				<?php foreach( $title as $id => $data ) : ?>

			    				<?php

			    					//Get image url from attachment id 
			    					$image_attributes = wp_get_attachment_image_src( $image_id[$id][$id], 'full_width_slider_1600_690' );
			    					$image_url = $image_attributes[0];

			    				?>

			    				<div class="caption_container" data-iview:image="<?php echo $image_url; ?>" data-iview:title="<?php echo $menu_title[$id][$id]; ?>">
									
									<div class="iview-caption caption1" data-x="100" data-y="125" data-transition="expandDown"><h3><?php echo $data[$id]; ?></h3></div>

									<div class="iview-caption caption2" data-x="100" data-y="205" data-transition="expandDown"><p><?php echo $subtitle[$id][$id]; ?></p></div>

									<div class="iview-caption caption3" data-x="100" data-y="280" data-transition="expandDown"><p><a href="<?php echo $button_link[$id][$id]; ?>" title="" class="button"><?php echo $button_text[$id][$id]; ?></a></p></div>
								
								</div>

			    				<?php endforeach; ?>

			    			</div><!-- .iview_slider -->

			    		</div><!-- .themeists_iview -->

			    		<?php

					echo $after_widget;

				endif;

				//Now we run the actual javascript. We run it through a filter so it can be
				//adjust elsewhere
				do_action( 'themeists_iview_slider_before_output_js' );

				?>

				<script>
					jQuery(document).ready(function(){

						<?php 

						$themeists_iview_js ="

							jQuery('#inner_" . $args['widget_id'] . "').iView({

								easing: false,
								fx: 'fade',
								pauseTime: 7000,
								pauseOnHover: true,
								controlNav: true,
								controlNavNextPrev: false,
								directionNavHoverOpacity: 0,
								controlNavHoverOpacity: 1,
								controlNavTooltip: false,
								timer: 'Bar',
								timerDiameter: '50%',
								timerPadding: 1,
								timerStroke: 1,
								timerBarStroke: 0,
								timerColor: '#FFF'

							});

						";

						echo apply_filters( 'themeists_iview_slider_js', $themeists_iview_js, $args );

						?>
					});
				</script>

				<?php

				do_action( 'themeists_iview_slider_after_output_js' );
		
			}/* widget() */


			/* ============================================================================ */

		
			/**
			 * Processes the widget's options to be saved.
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 * @param $new_instance	The previous instance of values before the update.
			 * @param @old_instance	The new instance of values to be generated via the update. 
			 * @return $instance The saved values
			 */
			
			function update( $new_instance, $old_instance )
			{

		    	//Upon update, we have to have the $_POST data
				$title = "";
				$subtitle ="";
				$button_text = "";
				$button_link = "";

		    	foreach( $_POST['widget-themeists_iview_slider_widget'] as $id => $data )
		    	{

		    		$instance['title'][$id] = array( $id => $data['title'] );
		    		$instance['subtitle'][$id] = array( $id => $data['subtitle'] );

		    		$instance['button_text'][$id] = array( $id => $data['button_text'] );
		    		$instance['button_link'][$id] = array( $id => $data['button_link'] );

		    		$instance['menu_title'][$id] = array( $id => $data['menu_title'] );

		    		$instance[$this->image_field][$id]    = array( $id => $data[$this->image_field] );
		    	
		    	}
		    
				return $instance;
		
			}/* update() */


			/* ============================================================================ */


			/**
			 * Generates the administration form for the widget.
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 * @param $instance	The array of keys and values for the widget.
			 * @return None
			 */
			

			function form( $instance )
			{
		
				$instance = wp_parse_args(

					(array)$instance,
					array(
						'title' => '',
						'subtitle' => '',
						'button_text' => '',
						'button_link' => '',
						'menu_title' => ''
					)

				);

				//If the data is an array, then it's been submitted (as cloned), in which case
				//we need to output several items. Otherwise, it's just the first instance (i.e. the
				//the widget has just been added to the sidebar)
				if( is_array( $instance['title'] ) )
				{

					//Get the arrays of data which are [id] => [content]
					$title_data = $instance['title'];
					$subtitle_data = $instance['subtitle'];

					$button_text_data = $instance['button_text'];
					$button_link_data = $instance['button_link'];

					$menu_title_data = $instance['menu_title'];

					$image_data = $instance[$this->image_field];

					foreach( $title_data as $id => $data )
					{

						$image_id   = $image_data[$id][$id];
						$image      = new WidgetImageField( $this, $image_id );

						?>

						<div class="cloneable widget-themeists_iview_slider_widget-<?php echo $id; ?>-id">

							<p>
								<label for="widget-themeists_iview_slider_widget-<?php echo $id; ?>-title">Title</label>
								<input type="text" value="<?php echo $data[$id]; ?>" name="widget-themeists_iview_slider_widget[<?php echo $id; ?>][title]" id="widget-themeists_iview_slider_widget-<?php echo $id; ?>-title" class="widefat titlefield">
							</p>

							<p>
								<label for="widget-themeists_iview_slider_widget-<?php echo $id; ?>-subtitle">Subtitle</label>
								<input type="text" value="<?php echo $subtitle_data[$id][$id]; ?>" name="widget-themeists_iview_slider_widget[<?php echo $id; ?>][subtitle]" id="widget-themeists_iview_slider_widget-<?php echo $id; ?>-subtitle" class="widefat subtitlefield">
							</p>

							<p>
								<label for="widget-themeists_iview_slider_widget-<?php echo $id; ?>-button_text">Button Text</label>
								<input type="text" value="<?php echo $button_text_data[$id][$id]; ?>" name="widget-themeists_iview_slider_widget[<?php echo $id; ?>][button_text]" id="widget-themeists_iview_slider_widget-<?php echo $id; ?>-button_text" class="widefat button_textfield">
							</p>

							<p>
								<label for="widget-themeists_iview_slider_widget-<?php echo $id; ?>-button_link">Button Link</label>
								<input type="text" value="<?php echo $button_link_data[$id][$id]; ?>" name="widget-themeists_iview_slider_widget[<?php echo $id; ?>][button_link]" id="widget-themeists_iview_slider_widget-<?php echo $id; ?>-button_link" class="widefat button_linkfield">
							</p>

							<div>
			                	<label><?php _e( 'Image' ); ?></label>
			                	<?php echo $image->get_widget_field( $this, $image_id, $id ); ?>
			            	</div>

			            	<p>
								<label for="widget-themeists_iview_slider_widget-<?php echo $id; ?>-menu_title">Menu Title</label>
								<input type="text" value="<?php echo $menu_title_data[$id][$id]; ?>" name="widget-themeists_iview_slider_widget[<?php echo $id; ?>][menu_title]" id="widget-themeists_iview_slider_widget-<?php echo $id; ?>-menu_title" class="widefat menu_titlefield">
							</p>

			            	<input type="button" class="remove_clone button button-secondary" value="x" />

						</div>

						<?php



					}

					echo '<input type="button" class="button button-secondary clonebutton" value="' . __( 'Add New', self::locale ) . '" />';

				}
				else
				{

					$image_id   = esc_attr( isset( $instance[$this->image_field] ) ? $instance[$this->image_field] : 0 );
					$image      = new WidgetImageField( $this, $image_id );

					?>

					<div class="cloneable <?php echo $this->get_field_id( 'id' ); ?>">
			    		<p>
							<label for="<?php echo $this->get_field_id( 'title' ); ?>">
								<?php _e( "Title", self::locale ); ?>
							</label>
							<input type="text" class="widefat titlefield" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
						</p>

						<p>
							<label for="<?php echo $this->get_field_id( 'subtitle' ); ?>">
								<?php _e( "Subtitle", self::locale ); ?>
							</label>
							<input type="text" class="widefat subtitlefield" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" value="<?php echo $instance['subtitle']; ?>" />
						</p>

						<p>
							<label for="<?php echo $this->get_field_id( 'button_text' ); ?>">
								<?php _e( "Button Text", self::locale ); ?>
							</label>
							<input type="text" class="widefat button_textfield" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $instance['button_text']; ?>" />
						</p>

						<p>
							<label for="<?php echo $this->get_field_id( 'button_link' ); ?>">
								<?php _e( "Button Link", self::locale ); ?>
							</label>
							<input type="text" class="widefat button_linkfield" id="<?php echo $this->get_field_id( 'button_link' ); ?>" name="<?php echo $this->get_field_name( 'button_link' ); ?>" value="<?php echo $instance['button_link']; ?>" />
						</p>

						<div>
			                <label><?php _e( 'Image' ); ?></label>
			                <?php echo $image->get_widget_field(); ?>
			            </div>

			            <p>
							<label for="<?php echo $this->get_field_id( 'menu_title' ); ?>">
								<?php _e( "Menu Title", self::locale ); ?>
							</label>
							<input type="text" class="widefat menu_titlefield" id="<?php echo $this->get_field_id( 'menu_title' ); ?>" name="<?php echo $this->get_field_name( 'menu_title' ); ?>" value="<?php echo $instance['menu_title']; ?>" />
						</p>

					</div>

					<input type="button" class="button button-secondary clonebutton" value="<?php _e( 'Add New', self::locale ); ?>" />

					<?php

				}

		
			}/* form() */


			/* ============================================================================ */
		

			/**
			 * Registers and enqueues stylesheets for the administration panel and the
			 * public facing site.
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 * @param None
			 * @return None
			 */
			

			private function register_scripts_and_styles()
			{

				if( is_admin() )
				{

					wp_enqueue_script( 'jquery-ui-core' );
					wp_enqueue_script( 'thickbox' );
					wp_enqueue_script( 'media-upload' );
					
					wp_enqueue_script( 'widgetimagefield', plugins_url( 'assets/js/themeists-iview-slider-admin.js' , __FILE__ ), array( 'jquery', 'jquery-ui-core', 'thickbox', 'media-upload' ), false, true );

					wp_enqueue_style( 'thickbox' );
					wp_enqueue_style( 'widgetimagefield', plugins_url( 'assets/css/themeists-iview-slider-admin.css' , __FILE__ ) );

				}
				else
				{ 

		      		

				}

			}/* register_scripts_and_styles() */


			/* ============================================================================ */


			/**
			 * Helper function for registering and enqueueing scripts and styles.
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 * @param $name 		The ID to register with WordPress
			 * @param $file_path	The path to the actual file
			 * @param $is_script	Optional argument for if the incoming file_path is a JavaScript source file.
			 * @return None
			 */
			
			function load_file( $name, $file_path, $is_script = false )
			{
		
		    	//$url = content_url( $file_path, __FILE__ );
				//$file = $file_path;
					
				if( $is_script )
				{

					wp_register_script( $name, $file_path, '', '', true );
					wp_enqueue_script( $name );

				}
				else
				{

					wp_register_style( $name, $file_path, '', '', true );
					wp_enqueue_style( $name );

				}
			
			}/* load_file() */


			/* ============================================================================ */


			/**
			 * Edit the plugins_url() url to be appropriate for this widget (we use symlinks on local dev)
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 */
			
			function local_dev_symlink_plugins_url_fix( $url, $path, $plugin )
			{

				// Do it only for this plugin
				if ( strstr( $plugin, basename( __FILE__ ) ) )
					return str_replace( dirname( __FILE__ ), '/' . basename( dirname( $plugin ) ), $url );

				return $url;

			}/* local_dev_symlink_plugins_url_fix() */


			/* ============================================================================ */


			/**
			 * Add the image size so that when images are uploaded they are recreated at the correct
			 * size
			 *
			 * @author Richard Tape
			 * @package themeists_iview_slider_widget
			 * @since 1.0
			 * @param None
			 * @return Calls add_image_size()
			 */
			
			function add_slider_image_size()
			{
				
				//Check there isn't already an image size called 'full_width_slider_1600_690'
				$registered_sizes = get_intermediate_image_sizes();

				if( is_array( $registered_sizes ) && !array_key_exists( 'full_width_slider_1600_690', $registered_sizes ) )
					add_image_size( 'full_width_slider_1600_690', 1600, 690 );

			}/* add_slider_image_size() */
		
		
		}/* class themeists_iview_slider_widget */

	}

	//Register The widget
	//register_widget( "themeists_iview_slider_widget" );
	add_action( 'widgets_init', create_function( '', 'register_widget( "themeists_iview_slider_widget" );' ) );

?>