<?php
/**
 * The post metabox screen.
 *
 * @since      1.0.25
 * @package    RankMath
 * @subpackage RankMath\Admin\Metabox
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Admin\Metabox;

use RankMath\KB;
use RankMath\Helper;
use RankMath\Traits\Hooker;
use RankMath\Helpers\Editor;
use RankMath\Frontend_SEO_Score;
use RankMath\Admin\Admin_Helper;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Url;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Post metabox class.
 */
class Post_Screen implements IScreen {

	use Hooker;

	/**
	 * Hold primary taxonomy
	 *
	 * @var object
	 */
	private $primary_taxonomy = null;

	/**
	 * Class construct
	 */
	public function __construct() {
		$this->filter( 'rank_math/researches/tests', 'remove_tests', 10, 2 );
	}

	/**
	 * Get object id
	 *
	 * @return int
	 */
	public function get_object_id() {
		global $post;

		return $post->ID;
	}

	/**
	 * Get object type
	 *
	 * @return string
	 */
	public function get_object_type() {
		return 'post';
	}

	/**
	 * Get object types to register metabox to
	 *
	 * @return array
	 */
	public function get_object_types() {
		return Helper::get_allowed_post_types();
	}

	/**
	 * Enqueue Styles and Scripts required for screen.
	 */
	public function enqueue() {
		$is_elementor    = Helper::is_elementor_editor();
		$is_block_editor = Helper::is_block_editor() && \rank_math_is_gutenberg();

		if ( ! $is_elementor ) {
			$this->enqueue_custom_fields();
		}

		wp_register_script(
			'rank-math-formats',
			rank_math()->plugin_url() . 'assets/admin/js/gutenberg-formats.js',
			[],
			rank_math()->version,
			true
		);

		if ( $is_block_editor || $is_elementor ) {
			$this->enqueue_commons();
			Helper::add_json( 'postType', get_post_type() );
		}

		if ( $is_block_editor && ! $is_elementor && Editor::can_add_editor() ) {
			$this->enqueue_for_gutenberg();
			return;
		}

		if ( $is_elementor ) {
			return;
		}

		// Classic.
		if ( Helper::is_block_editor() ) {
			wp_enqueue_script( 'rank-math-formats' );
			wp_enqueue_script( 'rank-math-primary-term', rank_math()->plugin_url() . 'assets/admin/js/gutenberg-primary-term.js', [], rank_math()->version, true );
		}
	}

	/**
	 * Get values for localize.
	 *
	 * @return array
	 */
	public function get_values() {
		$post_type = $this->get_current_post_type();
		return [
			'homeUrl'                => home_url(),
			'parentDomain'           => Url::get_domain( home_url() ),
			'noFollowDomains'        => Str::to_arr_no_empty( Helper::get_settings( 'general.nofollow_domains' ) ),
			'noFollowExcludeDomains' => Str::to_arr_no_empty( Helper::get_settings( 'general.nofollow_exclude_domains' ) ),
			'noFollowExternalLinks'  => Helper::get_settings( 'general.nofollow_external_links' ),
			'featuredImageNotice'    => esc_html__( 'The featured image should be at least 200 by 200 pixels to be picked up by Facebook and other social media sites.', 'rank-math' ),
			'pluginReviewed'         => $this->plugin_reviewed(),
			'postSettings'           => [
				'linkSuggestions' => Helper::get_settings( 'titles.pt_' . $post_type . '_link_suggestions' ),
				'useFocusKeyword' => 'focus_keywords' === Helper::get_settings( 'titles.pt_' . $post_type . '_ls_use_fk' ),
			],
			'siteFavIcon'            => $this->get_site_icon(),
			'frontEndScore'          => Frontend_SEO_Score::show_on(),
			'postName'               => get_post_field( 'post_name', get_post() ),
			'assessor'               => [
				'hasTOCPlugin'     => $this->has_toc_plugin(),
				'sentimentKbLink'  => KB::get( 'sentiments' ),
				'focusKeywordLink' => admin_url( 'edit.php?focus_keyword=%focus_keyword%&post_type=%post_type%' ),
				'registrationUrl'  => Helper::get_connect_url(),
				'hasBreadcrumb'    => Helper::get_settings( 'general.breadcrumbs' ),
				'hasRedirection'   => Helper::is_module_active( 'redirections' ),
				'isUserEdit'       => Admin_Helper::is_user_edit(),
				'socialPanelLink'  => Helper::get_admin_url( 'options-titles#setting-panel-social' ),
				'primaryTaxonomy'  => $this->get_primary_taxonomy(),
				'stopwords'        => Helper::get_settings( 'general.url_strip_stopwords' ) ? $this->get_stopwords() : false,
			],
		];
	}

	/**
	 * Get object values for localize
	 *
	 * @return array
	 */
	public function get_object_values() {
		global $post;

		return [
			'primaryTerm'         => $this->get_primary_term_id(),
			'authorName'          => get_the_author_meta( 'display_name' ),
			'titleTemplate'       => Helper::get_settings( "titles.pt_{$post->post_type}_title", '%%title%% %%sep%% %%sitename%%' ),
			'descriptionTemplate' => Helper::get_settings( "titles.pt_{$post->post_type}_description", '' ),
			'showScoreFrontend'   => ! Helper::get_post_meta( 'dont_show_seo_score', $this->get_object_id() ),
		];
	}

	/**
	 * Get analysis to run.
	 *
	 * @return array
	 */
	public function get_analysis() {
		$tests = [
			'contentHasTOC'             => true,
			'contentHasShortParagraphs' => true,
			'contentHasAssets'          => true,
			'keywordInTitle'            => true,
			'keywordInMetaDescription'  => true,
			'keywordInPermalink'        => true,
			'keywordIn10Percent'        => true,
			'keywordInContent'          => true,
			'keywordInSubheadings'      => true,
			'keywordInImageAlt'         => true,
			'keywordDensity'            => true,
			'keywordNotUsed'            => true,
			'lengthContent'             => true,
			'lengthPermalink'           => true,
			'linksHasInternal'          => true,
			'linksHasExternals'         => true,
			'linksNotAllExternals'      => true,
			'titleStartWithKeyword'     => true,
			'titleSentiment'            => true,
			'titleHasPowerWords'        => true,
			'titleHasNumber'            => true,
		];

		return $tests;
	}

	/**
	 * Remove few tests on static Homepage.
	 *
	 * @since 1.0.42
	 *
	 * @param array  $tests Array of tests with score.
	 * @param string $type  Object type. Can be post, user or term.
	 */
	public function remove_tests( $tests, $type ) {
		if ( ! Admin_Helper::is_home_page() ) {
			return $tests;
		}

		$remove = [
			'contentHasTOC'        => true,
			'keywordInPermalink'   => true,
			'lengthPermalink'      => true,
			'linksHasExternals'    => true,
			'linksNotAllExternals' => true,
			'titleSentiment'       => true,
			'titleHasPowerWords'   => true,
			'titleHasNumber'       => true,
		];

		return array_diff_assoc( $tests, $remove );
	}

	/**
	 * Get site fav icon.
	 *
	 * @return string
	 */
	private function get_site_icon() {
		$favicon = get_site_icon_url( 16 );

		return ! empty( $favicon ) ? $favicon : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABs0lEQVR4AWL4//8/RRjO8Iucx+noO0MWUDo16FYABMGP6ZfUcRnWtm27jVPbtm3bttuH2t3eFPcY9pLz7NxiLjCyVd87pKnHyqXyxtCs8APd0rnyxiu4qSeA3QEDrAwBDrT1s1Rc/OrjLZwqVmOSu6+Lamcpp2KKMA9PH1BYXMe1mUP5qotvXTywsOEEYHXxrY+3cqk6TMkYpNr2FeoY3KIr0RPtn9wQ2unlA+GMkRw6+9TFw4YTwDUzx/JVvARj9KaedXRO8P5B1Du2S32smzqUrcKGEyA+uAgQjKX7zf0boWHGfn71jIKj2689gxp7OAGShNcBUmLMPVjZuiKcA2vuWHHDCQxMCz629kXAIU4ApY15QwggAFbfOP9DhgBJ+nWVJ1AZAfICAj1pAlY6hCADZnveQf7bQIwzVONGJonhLIlS9gr5mFg44Xd+4S3XHoGNPdJl1INIwKyEgHckEhgTe1bGiFY9GSFBYUwLh1IkiJUbY407E7syBSFxKTszEoiE/YdrgCEayDmtaJwCI9uu8TKMuZSVfSa4BpGgzvomBR/INhLGzrqDotp01ZR8pn/1L0JN9d9XNyx0AAAAAElFTkSuQmCC';
	}

	/**
	 * Enqueque scripts common for all builders.
	 */
	private function enqueue_commons() {
		wp_register_style( 'rank-math-post-metabox', rank_math()->plugin_url() . 'assets/admin/css/sidebar.css', [], rank_math()->version );
	}

	/**
	 * Enqueue script to analyze custom fields data.
	 */
	private function enqueue_custom_fields() {
		global $post;

		$custom_fields = Str::to_arr_no_empty( Helper::get_settings( 'titles.pt_' . $post->post_type . '_analyze_fields' ) );
		if ( empty( $custom_fields ) ) {
			return;
		}

		$file = Helper::is_block_editor() ? 'glue-custom-fields.js' : 'custom-fields.js';

		wp_enqueue_script( 'rank-math-custom-fields', rank_math()->plugin_url() . 'assets/admin/js/' . $file, [ 'wp-hooks', 'rank-math-analyzer' ], rank_math()->version, true );
		Helper::add_json( 'analyzeFields', $custom_fields );
	}

	/**
	 * Enqueue scripts for gutenberg screen.
	 */
	private function enqueue_for_gutenberg() {
		wp_enqueue_style( 'rank-math-post-metabox' );
		wp_enqueue_script( 'rank-math-formats' );
		wp_enqueue_script(
			'rank-math-gutenberg',
			rank_math()->plugin_url() . 'assets/admin/js/gutenberg.js',
			[
				'tagify',
				'wp-autop',
				'wp-blocks',
				'wp-components',
				'wp-editor',
				'wp-edit-post',
				'wp-element',
				'wp-i18n',
				'wp-plugins',
				'wp-wordcount',
				'rank-math-analyzer',
			],
			rank_math()->version,
			true
		);
	}

	/**
	 * Function to replace domain with seo-by-rank-math in translation file.
	 *
	 * @param string|false $file   Path to the translation file to load. False if there isn't one.
	 * @param string       $handle Name of the script to register a translation domain to.
	 * @param string       $domain The text domain.
	 */
	public function load_script_translation_file( $file, $handle, $domain ) {
		if ( 'rank-math' !== $domain ) {
			return $file;
		}

		$data                       = explode( '/', $file );
		$data[ count( $data ) - 1 ] = preg_replace( '/rank-math/', 'seo-by-rank-math', $data[ count( $data ) - 1 ], 1 );

		return implode( '/', $data );
	}

	/**
	 * Get current post type.
	 *
	 * @return string
	 */
	private function get_current_post_type() {
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			return $screen->post_type;
		}

		return get_post_type();
	}

	/**
	 * Check if any TOC plugin detected
	 *
	 * @return bool
	 */
	private function has_toc_plugin() {
		if ( \defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			return true;
		}

		$plugins_found  = [];
		$active_plugins = get_option( 'active_plugins' );

		/**
		 * Allow developers to add plugins to the TOC list.
		 *
		 * @param array TOC plugins.
		 */
		$toc_plugins = $this->do_filter(
			'researches/toc_plugins',
			[
				'wp-shortcode/wp-shortcode.php'         => 'WP Shortcode by MyThemeShop',
				'wp-shortcode-pro/wp-shortcode-pro.php' => 'WP Shortcode Pro by MyThemeShop',
			]
		);

		foreach ( $toc_plugins as $plugin_slug => $plugin_name ) {
			if ( in_array( $plugin_slug, $active_plugins, true ) !== false ) {
				$plugins_found[ $plugin_slug ] = $plugin_name;
			}
		}

		return empty( $plugins_found ) ? false : $plugins_found;
	}

	/**
	 * Plugin already reviewed.
	 *
	 * @return bool
	 */
	private function plugin_reviewed() {
		return get_option( 'rank_math_already_reviewed' ) || get_option( 'rank_math_install_date' ) + ( 2 * WEEK_IN_SECONDS ) > current_time( 'timestamp' );
	}

	/**
	 * Get primary taxonomy.
	 *
	 * @return bool|array
	 */
	private function get_primary_taxonomy() {
		if ( ! is_null( $this->primary_taxonomy ) ) {
			return $this->primary_taxonomy;
		}

		$taxonomy  = false;
		$post_type = $this->get_current_post_type();

		/**
		 * Filter: Allow disabling the primary term feature.
		 * 'rank_math/primary_term' is deprecated,
		 * use 'rank_math/admin/disable_primary_term' instead.
		 *
		 * @param bool $return True to disable.
		 */
		if ( false === apply_filters_deprecated( 'rank_math/primary_term', array( false ), '1.0.43', 'rank_math/admin/disable_primary_term' )
			&& false === $this->do_filter( 'admin/disable_primary_term', false ) ) {
			$taxonomy = Helper::get_settings( 'titles.pt_' . $post_type . '_primary_taxonomy', false );
		}

		if ( ! $taxonomy ) {
			return false;
		}

		$taxonomy = get_taxonomy( $taxonomy );

		$this->primary_taxonomy = [
			'title'         => $taxonomy->labels->singular_name,
			'name'          => $taxonomy->name,
			'singularLabel' => $taxonomy->labels->singular_name,
			'restBase'      => ( $taxonomy->rest_base ) ? $taxonomy->rest_base : $taxonomy->name,
		];

		return $this->primary_taxonomy;
	}

	/**
	 * Get primary term id.
	 *
	 * @return int
	 */
	private function get_primary_term_id() {
		$taxonomy = $this->get_primary_taxonomy();
		if ( ! $taxonomy ) {
			return 0;
		}

		$id = Helper::get_post_meta( 'primary_' . $taxonomy['name'], $this->get_object_id() );

		return $id ? absint( $id ) : 0;
	}

	/**
	 * Get stop words.
	 *
	 * @return array List of stop words.
	 */
	private function get_stopwords() {

		/* translators: this should be an array of stop words for your language, separated by comma's. */
		$stopwords = explode( ',', esc_html__( "a,about,above,after,again,against,all,am,an,and,any,are,as,at,be,because,been,before,being,below,between,both,but,by,could,did,do,does,doing,down,during,each,few,for,from,further,had,has,have,having,he,he'd,he'll,he's,her,here,here's,hers,herself,him,himself,his,how,how's,i,i'd,i'll,i'm,i've,if,in,into,is,it,it's,its,itself,let's,me,more,most,my,myself,nor,of,on,once,only,or,other,ought,our,ours,ourselves,out,over,own,same,she,she'd,she'll,she's,should,so,some,such,than,that,that's,the,their,theirs,them,themselves,then,there,there's,these,they,they'd,they'll,they're,they've,this,those,through,to,too,under,until,up,very,was,we,we'd,we'll,we're,we've,were,what,what's,when,when's,where,where's,which,while,who,who's,whom,why,why's,with,would,you,you'd,you'll,you're,you've,your,yours,yourself,yourselves", 'rank-math' ) );

		$custom = Helper::get_settings( 'general.stopwords' );
		$custom = Str::to_arr_no_empty( $custom );

		return array_unique( array_merge( $stopwords, $custom ) );
	}
}
