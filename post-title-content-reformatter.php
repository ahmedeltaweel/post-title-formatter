<?php
/*
 * Plugin Name: Post Title formatter.
 * Description: WordPress Plugin that remove double spaces and full stops also Capitalize letter at the beginning of the sentence.
 * Version:     1.0.0
 * Author:      Ahmed El-Taweel.
 * Author URI:  http://ahmedeltaweel.com
 * License: GPL2 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Wordpress\Post\formatter;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Post_formatter
{

	/**
	 * The unique instance of the plugin.
	 *
	 * @var Post_formatter
	 */
	private static $instance;

	/**
	 * Post_formatter constructor.
	 */
	public function __construct()
	{
		// hook to post title
		add_filter( 'the_title', [ &$this, 'format' ], 200 );

		// hook to post content
		add_filter( 'the_content', [ &$this, 'format' ], 1 );
	}

	/**
	 * Gets an instance of our plugin.
	 *
	 * @return Post_formatter
	 */
	public static function get_instance()
	{
		if ( null === self::$instance )
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Format content to  Removal of double spacing and full stops.
	 * also Capitalize letter at the beginning of the sentence.
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	public function format( $content )
	{
		// remove p tag
		$content = wp_strip_all_tags( $content );

		// remove . from beginning
		$content = ltrim( $content, '.' );

		// remove spaces form beginning
		$content = trim( $content );

		// remove double spaces
		$content = preg_replace( '/[\p{Z}\s]{2,}/u', ' ', $content );

		// remove double dots
		$content = preg_replace( '/\.+/', '.', $content );

		// make everything lowercase, and then make the first letter if the entire string capitalized
		$content = ucfirst( $content );

		// capitalize every letter after .
		$content = preg_replace_callback(
			'/[.!?].*?\w/', function ( $matches )
		{
			return strtoupper( $matches[ 0 ] );
		}, $content
		);

		return "<p>{$content}</p>";
	}

}

// boot system
Post_formatter::get_instance();