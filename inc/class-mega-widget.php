<?php
/**
 * Widgets
 *
 * @package Mega_Widget
 */

/**
 * Mega Widget class.
 *
 * A widget that allows for quick and easy testing of multiple widgets.
 *
 * @since 1.0.0
 */
class Mega_Widget extends WP_Widget {

	/**
	 * Iterator (int).
	 *
	 * Used to set a unique html id attribute for each
	 * widget instance generated by Mega_Widget::widget().
	 *
	 * @since 1.0.0
	 * @var int
	 */
	protected static $iterator = 1;

	/**
	 * Image attachment ID.
	 *
	 * Used is Image widgets.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	protected $image_attachment_id = null;

	/**
	 * Images.
	 *
	 * Array of image IDs. Used is Gallery widgets.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $images = [];

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			'mega-widget',
			esc_html__( 'Mega Widget', 'mega-widget' ),
			[
				'classname'   => 'mega-widget',
				'description' => esc_html__( 'Test multiple widgets at the same time.', 'mega-widget' ),
			]
		);

		// Single image.
		$latest_image = get_posts(
			[
				'numberposts'    => 1,
				'post_type'      => 'attachment',
				'post_mime_type' => 'image/jpeg',
			]
		);

		if ( ! empty( $latest_image ) ) {
			$this->image_attachment_id = $latest_image[0]->ID;
		}

		// Gallery setup.
		$latest_images = get_posts(
			[
				'numberposts'    => 9,
				'post_type'      => 'attachment',
				'post_mime_type' => 'image/jpeg',
			]
		);

		if ( ! empty( $latest_images ) ) {
			foreach ( $latest_images as $val ) {
				$this->images[] = $val->ID;
			}
		}
	}

	/**
	 * Print the Mega widget on the front-end.
	 *
	 * @uses $wp_registered_sidebars
	 * @uses Mega_Widget::$iterator
	 * @uses Mega_Widget::get_widget_class()
	 * @uses $this->get_widget_config()
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Display arguments.
	 * @param array $instance Settings for the current Pages widget instance.
	 */
	public function widget( $args, $instance ) {
		global $wp_registered_sidebars;

		$id            = $args['id'];
		$args          = $wp_registered_sidebars[ $id ];
		$before_widget = $args['before_widget'];

		foreach ( $this->get_widget_config() as $widget ) {
			$_instance = ( isset( $widget[1] ) ) ? $widget[1] : null;

			// Override cache for the Recent Posts widget.
			if ( 'WP_Widget_Recent_Posts' === $widget[0] ) {
				$args['widget_id'] = 'mega-widget-recent-posts-cache-' . self::$iterator;
			}

			$args['before_widget'] = sprintf(
				$before_widget,
				'mega-widget-placeholder-' . self::$iterator,
				$this->get_widget_class( $widget[0] )
			);

			the_widget( $widget[0], $_instance, $args );

			++self::$iterator;
		}
	}

	/**
	 * Widgets (array).
	 *
	 * Numerically indexed array of Pre-configured widgets to
	 * display in every instance of a Mega widget. Each entry
	 * requires two values:
	 *
	 * 0 - The name of the widget's class as registered with register_widget().
	 * 1 - An associative array representing an instance of the widget.
	 *
	 * @uses Mega_Widget::get_text()
	 * @uses Mega_Widget::get_nav_menu()
	 *
	 * This list can be altered by using the `mega_widget_config` filter.
	 *
	 * @return array Widget configuration.
	 * @since 1.0.0
	 */
	public function get_widget_config() {
		$widgets = [
			[
				'WP_Widget_Search',
				[
					'title' => esc_html__( 'Search', 'mega-widget' ),
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:search /-->',
				],
			],
			[
				'WP_Widget_Recent_Posts',
				[
					'title'  => esc_html__( 'Recent Posts', 'mega-widget' ),
					'number' => 5,
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Recent Posts New</h2><!-- /wp:heading --><!-- wp:latest-posts /--></div><!-- /wp:group -->',
				],
			],
			[
				'WP_Widget_Media_Image',
				[
					'title'         => esc_html__( 'Simple Image', 'mega-widget' ),
					'attachment_id' => $this->image_attachment_id,
					'size'          => 'large',
					'caption'       => esc_html__( 'No caption is also a caption.', 'mega-widget' ),
				],
			],
			[
				'WP_Widget_Media_Image',
				[
					'title'             => esc_html__( 'Linked Image', 'mega-widget' ),
					'attachment_id'     => $this->image_attachment_id,
					'size'              => 'large',
					'link_type'         => 'file',
					'link_target_blank' => true,
				],
			],
			[
				'WP_Widget_Recent_Comments',
				[
					'title'  => esc_html__( 'Recent Comments', 'mega-widget' ),
					'number' => 5,
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Recent Comments New</h2><!-- /wp:heading --><!-- wp:latest-comments {"displayAvatar":false,"displayDate":false,"displayExcerpt":false} /--></div><!-- /wp:group -->',
				],
			],
			[
				'WP_Widget_Meta',
				[
					'title' => esc_html__( 'Meta', 'mega-widget' ),
				],
			],
			[
				'WP_Widget_Calendar',
				[
					'title' => esc_html__( 'Calendar', 'mega-widget' ),
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Calendar New</h2><!-- /wp:heading --><!-- wp:calendar /--></div><!-- /wp:group -->',
				],
			],
			[
				'WP_Widget_Media_Gallery',
				[
					'title'   => esc_html__( 'Gallery: 3 columns ', 'mega-widget' ),
					'ids'     => array_slice( $this->images, 0, 9 ),
					'size'    => 'large',
					'columns' => 3,
				],
			],
			[
				'WP_Widget_Media_Gallery',
				[
					'title'     => esc_html__( 'Gallery: 2 columns ', 'mega-widget' ),
					'ids'       => array_slice( $this->images, 0, 4 ),
					'size'      => 'medium',
					'link_type' => 'file',
					'columns'   => 2,
				],
			],
			[
				'WP_Widget_Media_Gallery',
				[
					'title'     => esc_html__( 'Gallery: 4 columns ', 'mega-widget' ),
					'ids'       => array_slice( $this->images, 0, 8 ),
					'size'      => 'thumbnail',
					'link_type' => 'none',
					'columns'   => 4,
				],
			],
			[
				'WP_Widget_Archives',
				[
					'title'    => esc_html__( 'Archives Dropdown', 'mega-widget' ),
					'count'    => 1,
					'dropdown' => 1,
				],
			],
			[
				'WP_Widget_Categories',
				[
					'title'        => esc_html__( 'Categories Dropdown', 'mega-widget' ),
					'count'        => 1,
					'hierarchical' => 1,
					'dropdown'     => 1,
				],
			],
			[
				'WP_Widget_Tag_Cloud',
				[
					'title'    => esc_html__( 'Tag Cloud', 'mega-widget' ),
					'taxonomy' => 'post_tag',
					'count'    => true,
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Tag Cloud New</h2><!-- /wp:heading --><!-- wp:tag-cloud {"showTagCounts":true} /--></div><!-- /wp:group -->',
				],
			],
			[
				'WP_Widget_Archives',
				[
					'title'    => esc_html__( 'Archives List', 'mega-widget' ),
					'count'    => 1,
					'dropdown' => 0,
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Archives New</h2><!-- /wp:heading --><!-- wp:archives {"showPostCounts":true} /--></div><!-- /wp:group -->',
				],
			],
			[
				'WP_Widget_Categories',
				[
					'title'        => esc_html__( 'Categories List', 'mega-widget' ),
					'count'        => 1,
					'hierarchical' => 1,
					'dropdown'     => 0,
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Categories New</h2><!-- /wp:heading --><!-- wp:categories {"showPostCounts":true} /--></div><!-- /wp:group -->',
				],
			],
			[
				'WP_Widget_Pages',
				[
					'title'   => esc_html__( 'Pages', 'mega-widget' ),
					'sortby'  => 'menu_order',
					'exclude' => '',
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Pages New</h2><!-- /wp:heading --><!-- wp:page-list /--></div><!-- /wp:group -->',
				],
			],
			[
				'WP_Widget_RSS',
				[
					'title'        => esc_html__( 'RSS', 'mega-widget' ),
					'url'          => 'https://themeshaper.com/feed/',
					'items'        => 2,
					'show_author'  => true,
					'show_date'    => true,
					'show_summary' => true,
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>RSS New</h2><!-- /wp:heading --><!-- wp:rss {"feedURL":"https://themeshaper.com/feed/","itemsToShow":2,"displayExcerpt":true,"displayAuthor":true,"displayDate":true} /--></div><!-- /wp:group -->',
				],
			],
			[
				'WP_Widget_Text',
				[
					'title'  => esc_html__( 'Text', 'mega-widget' ),
					'text'   => $this->get_text(),
					'filter' => true,
				],
			],
			[
				'WP_Widget_Media_Audio',
				[
					'title' => esc_html__( 'Audio', 'mega-widget' ),
					'url'   => 'https://wpthemetestdata.files.wordpress.com/2008/06/originaldixielandjazzbandwithalbernard-stlouisblues.mp3',
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Audio New</h2><!-- /wp:heading --><!-- wp:audio -->
					<figure class="wp-block-audio"><audio controls src="https://wpthemetestdata.files.wordpress.com/2008/06/originaldixielandjazzbandwithalbernard-stlouisblues.mp3"></audio></figure>
					<!-- /wp:audio --></div><!-- /wp:group -->',
				],
			],
			[
				'WP_Widget_Media_Video',
				[
					'title' => esc_html__( 'Video', 'mega-widget' ),
					'url'   => 'https://www.youtube.com/watch?v=SQEQr7c0-dw',
				],
			],
			[
				'WP_Widget_Block',
				[
					'content' => '<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Video New</h2><!-- /wp:heading --><!-- wp:embed {"url":"https://www.youtube.com/watch?v=SQEQr7c0-dw","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
					<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
					https://www.youtube.com/watch?v=SQEQr7c0-dw
					</div></figure>
					<!-- /wp:embed --></div><!-- /wp:group -->',
				],
			],
		];

		$menu = $this->get_nav_menu();
		if ( $menu ) {
			$widgets[] = [
				'WP_Nav_Menu_Widget',
				[
					'title'    => esc_html__( 'Nav Menu', 'mega-widget' ),
					'nav_menu' => $menu,
				],
			];
		}

		global $wp_widget_factory;
		$available_widgets = array_keys( $wp_widget_factory->widgets );
		if ( in_array( 'WP_Widget_Links', $available_widgets, true ) ) {
			$widgets[] = [
				'WP_Widget_Links',
				[
					'title'       => esc_html__( 'Links', 'mega-widget' ),
					'description' => 1,
					'name'        => 1,
					'rating'      => 1,
					'images'      => 1,
				],
			];
		}

		return apply_filters( 'mega_widget_config', $widgets );
	}

	/**
	 * Get the html class attribute value for a given widget.
	 *
	 * @uses $wp_widget_factory
	 *
	 * @param string $widget The name of a registered widget class.
	 * @return string Dynamic class name a given widget.
	 *
	 * @since 1.0.0
	 */
	public function get_widget_class( $widget ) {
		global $wp_widget_factory;

		$widget_obj = '';
		if ( isset( $wp_widget_factory->widgets[ $widget ] ) ) {
			$widget_obj = $wp_widget_factory->widgets[ $widget ];
		}

		if ( ! is_a( $widget_obj, 'WP_Widget' ) ) {
			return '';
		}

		if ( ! isset( $widget_obj->widget_options['classname'] ) ) {
			return '';
		}

		return $widget_obj->widget_options['classname'];
	}

	/**
	 * Get the nav menu with the most links.
	 *
	 * @return mixed Term object on success; False otherwise.
	 * @since 1.0.0
	 */
	public static function get_nav_menu() {
		$menus = wp_get_nav_menus();

		if ( is_wp_error( $menus ) || empty( $menus ) ) {
			return false;
		}

		$counts = wp_list_pluck( $menus, 'count' );
		$menus  = array_combine( $counts, $menus );
		ksort( $menus );
		$menus = array_reverse( $menus );
		$menus = array_values( $menus );
		$menu  = array_shift( $menus );

		if ( empty( $menu->count ) ) {
			return false;
		}

		return $menu;
	}

	/**
	 * Widget Breaker Text.
	 *
	 * Used to populate the Text widget with html designed
	 * to "break" out of the sidebar.
	 *
	 * The "mega_widget_get_text" filter can be used
	 * to modify the output.
	 *
	 * @since 1.0.0
	 */
	public function get_text() {
		$html = [];

		$html[] = '<strong>' . esc_html__( 'Large image: Hand Coded', 'mega-widget' ) . '</strong>';
		$html[] = '<img src="' . MEGA_WIDGET_URL . '/images/castle.jpg" alt="">';

		$html[] = '<strong>' . esc_html__( 'Large image: linked in a caption', 'mega-widget' ) . '</strong>';
		$html[] = '<div class="wp-caption alignnone"><a href="#"><img src="' . MEGA_WIDGET_URL . '/images/castle.jpg" class="size-large" height="720" width="960" alt=""></a><p class="wp-caption-text">' . esc_html__( 'This image is 960 by 720 pixels.', 'mega-widget' ) . ' ' . convert_smilies( ':)' ) . '</p></div>';

		$html[] = '<strong>' . esc_html__( 'Lorem title!', 'mega-widget' ) . '</strong>';
		$html[] = esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam a dolor nunc. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce imperdiet, nibh sed aliquam egestas, sem neque scelerisque urna, vel suscipit lectus ex ac magna. Nam eget viverra magna. Integer a lectus urna. Phasellus vel urna vitae lectus luctus egestas et eu sapien. In sed turpis vitae tellus ultrices ultrices sit amet et purus. Proin suscipit auctor mauris, cursus ultrices metus lobortis vel. Aenean sed lorem vel sem tempus commodo. Aenean sit amet pulvinar lorem. Nam ut mollis dui. Maecenas dapibus vulputate posuere. Aenean ut ipsum cursus, imperdiet metus ut, auctor risus.', 'mega-widget' );

		$html[] = '<strong>' . esc_html__( 'Smile!', 'mega-widget' ) . '</strong>';
		$html[] = convert_smilies( ';)' ) . ' ' . convert_smilies( ':)' ) . ' ' . convert_smilies( ':-D' );

		$html[] = '<strong>' . esc_html__( 'Select Element with long value', 'mega-widget' ) . '</strong>';

		$html[] = '<form method="get" action="/">';
		$html[] = '<select name="mega-widget-just-testing">';
		$html[] = '<option value="0">' . esc_html__( 'First', 'mega-widget' ) . '</option>';
		$html[] = '<option value="1">' . esc_html__( 'Second', 'mega-widget' ) . '</option>';
		$html[] = '<option value="2">' . esc_html__( 'Third', 'mega-widget' ) . ' OMG! How can one option contain soooo many words? This really is a lot of words.</option>';
		$html[] = '</select>';
		$html[] = '</form>';

		$html = implode( "\n", $html );

		return apply_filters( 'mega_widget_get_text', $html );
	}
}
