<?php

use Foolz\SphinxQL\Connection;
use Foolz\SphinxQL\SphinxQL;
use Buki\Pdox;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.iolabs.nl
 * @since      1.0.0
 *
 * @package    Iolabs_Advanced_Search
 * @subpackage Iolabs_Advanced_Search/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Iolabs_Advanced_Search
 * @subpackage Iolabs_Advanced_Search/public
 * @author     Leander Huysse <info@iomedia.nl>
 */
class Iolabs_Advanced_Search_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version     The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_shortcode('iosearch_show', [$this, 'register_shortcode']);

        add_action('wp_ajax_io_get_subcategories', [$this, 'getSub']);
        add_action('wp_ajax_nopriv_io_get_subcategories', [$this, 'getSub']);

        add_action('wp_ajax_io_get_count', [$this, 'getCount']);
        add_action('wp_ajax_nopriv_io_get_count', [$this, 'getCount']);

        add_action( 'admin_post_nopriv_getData', [$this, 'getData'] );
        add_action( 'admin_post_getData', [$this, 'getData'] );

        $this->index_name = INDEX_NAME;
        $this->db_name = DB_NAME;
        $this->db_user = DB_USER;
        $this->db_pass = DB_PASSWORD;
        $this->db_host = DB_HOST;
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
         * defined in Iolabs_Advanced_Search_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Iolabs_Advanced_Search_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/iolabs-advanced-search-public.css', [], $this->version, 'all');

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
         * defined in Iolabs_Advanced_Search_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Iolabs_Advanced_Search_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        $time = date("s");
        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/iolabs-advanced-search-public.js?t=' . $time, ['jquery', 'select2-js'], $this->version, true);
        wp_localize_script(
            $this->plugin_name, 'iodata', [
              'ajaxurl' => admin_url('admin-ajax.php'),
              'url'     => plugin_dir_url(dirname(__FILE__)),
            ]
        );
        wp_enqueue_script($this->plugin_name);

    }

    public function register_shortcode() {

        include('partials/iolabs-advanced-search-public-display.php');
    }

    public function getSub() {

        $categoryId = sanitize_text_field($_POST['category']);

        $args = [
            'parent' => $categoryId,
            'order' => 'ASC',
            'orderby' => 'name',
            'hide_empty' => true
        ];

        $categories = get_terms('product_cat', $args);
        // var_dump($categories);

        echo json_encode($categories);
        wp_die();

    }

    public function getData($data, $ppp = 12, $paged = 1) {
        if($data === null) {
            return false;
        }

        unset($data['submit']);
        unset($data['action']);
        unset($data['page']);

        $d = [];
        foreach($data as $key => $value) {
            $k = str_replace('s_', '', $key);
            $d[$k] = $value;
        }

        $data = $d;

        $args = [
            'post_type' => 'product',
            's' => $data['post_title'],
            'posts_per_page' => $ppp,
            'paged' => $paged,
            'meta_query' => [
                'relation' => 'AND',
            ]
        ];

        unset($data['post_title']);

        foreach($data as $key => $term) {
            if($term !== '') {
                $args['meta_query'][] = [
                    'key' => $key,
                    'value' => $term,
                    'compare' => 'LIKE',
                ];
            }
        }

        $query = new WP_Query($args);
        $posts = $query->posts;
        $count = $query->post_count;

        $posts = array_map(function($product) {
            $product->custom = new stdClass();
            $product->custom->author_name = get_post_meta($product->ID, 'author_name', true);
            $product->custom->_price = get_post_meta($product->ID, '_price', true);
            $product->custom->_sku = get_post_meta($product->ID, '_sku', true);

            return $product;
        }, $posts);

        return [
            'products' => $posts,
            'count' => $count,
        ];
    }


}
