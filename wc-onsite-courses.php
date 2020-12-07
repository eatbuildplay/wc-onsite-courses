<?php

/**
 *
 * Plugin Name: WooCommerce Onsite Courses
 * Plugin URI: https://eatbuildplay.com/plugins/wc-onsite-courses
 * Description: Provides features for the listing and sale of onsite courses and is especially suited to selling first aid training and similar multiple day courses. Works with WooCommerce Vendor to enable 3rd party sellers.
 * Version: 1.0.0
 * Author: Casey Milne, Eat/Build/Play
 * Author URI: https://eatbuildplay.com/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 */

define( 'WC_ONSITE_COURSES_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_ONSITE_COURSES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_ONSITE_COURSES_VERSION', '1.0.0' );

class WC_Onsite_Courses_Plugin {

  public function __construct() {

    require( WC_ONSITE_COURSES_PLUGIN_PATH . 'assets/fields/expiry-fields.php' );
    require( WC_ONSITE_COURSES_PLUGIN_PATH . 'assets/fields/course-schedule-fields.php' );

    require( WC_ONSITE_COURSES_PLUGIN_PATH . 'src/CourseSchedule.php' );
    require( WC_ONSITE_COURSES_PLUGIN_PATH . 'src/CourseProducts.php' );

    require( WC_ONSITE_COURSES_PLUGIN_PATH . 'src/Template.php' );
    require( WC_ONSITE_COURSES_PLUGIN_PATH . 'src/CartHandler.php' );

    add_action('wp_enqueue_scripts', array( $this, 'scripts' ), 10);
    add_action('wp_enqueue_scripts', array( $this, 'fetchCourseDates' ), 20);

    require( WC_ONSITE_COURSES_PLUGIN_PATH . 'src/CertificationExpiryReport.php' );
    new WCOSCO_CerficationExpiryReport();

    // add product output for onsite courses
    // https://businessbloomer.com/woocommerce-visual-hook-guide-single-product-page/
    // try woocommerce_after_single_product_summary
    // or woocommerce_before_add_to_cart_button
    add_action('wp', array( $this, 'maybeHideCartButton' ));
    add_action('woocommerce_after_single_product_summary', array( $this, 'productSingleRender' ), 8);
    add_action('woocommerce_get_item_data', array( $this, 'cartLineItems' ), 10, 2);

    new WCOSCO_CartHandler();

  }

  public function cartLineItems( $item_data, $cart_item ) {

    $courseDates = wc_get_order_item_meta( $cart_item['key'], 'course_dates' );

    if( !$courseDates || empty( $courseDates )) {
      return $item_data;
    }

    $courseDatesDisplay = $courseDates;
    $courseDatesDisplay = '3423432';

    $item_data[] = array(
      'key'     => 'Course Dates',
      'value'   => $courseDatesDisplay,
    );

    return $item_data;

  }

  public function maybeHideCartButton() {

    global $post;
    $productId = $post->ID;

    $isCourse = get_field( 'is_course', $productId );
    if( !$isCourse ) {
      return;
    }
    add_filter('woocommerce_is_purchasable', '__return_false');

  }

  public function productSingleRender() {
    global $post;
    $product = $post;

    $isCourse = get_field( 'is_course', $product->ID );
    if( !$isCourse ) {
      return;
    }

    // show product options for onsite courses
    $cs = new WCOSCO_CourseSchedule();
    print $cs->render( $product );

    $cp = new WCOSCO_CourseProducts();
    print $cp->render( $product );

    $ch = new WCOSCO_CartHandler();
    print $ch->render( $product );

  }

  public function scripts() {

    wp_enqueue_script(
      'fullcalendar-core-js',
      WC_ONSITE_COURSES_PLUGIN_URL . 'vendor/fullcalendar/packages/core/main.min.js',
      array( 'jquery' ),
      '1.0.0',
      true
    );

    wp_enqueue_script(
      'fullcalendar-daygrid-js',
      WC_ONSITE_COURSES_PLUGIN_URL . 'vendor/fullcalendar/packages/daygrid/main.min.js',
      array( 'jquery' ),
      '1.0.0',
      true
    );

    wp_enqueue_script(
      'wc-onsite-courses-js',
      WC_ONSITE_COURSES_PLUGIN_URL . 'assets/wc-onsite-courses.js',
      array( 'jquery', 'fullcalendar-core-js', 'fullcalendar-daygrid-js' ),
      '1.0.0',
      true
    );

    wp_enqueue_style(
      'fullcalendar-core-css',
      WC_ONSITE_COURSES_PLUGIN_URL . '/vendor/fullcalendar/packages/core/main.min.css',
      array(),
      '1.0.0',
      'all'
    );

    wp_enqueue_style(
      'fullcalendar-daygrid-css',
      WC_ONSITE_COURSES_PLUGIN_URL . 'vendor/fullcalendar/packages/daygrid/main.min.css',
      array(),
      '1.0.0',
      'all'
    );

    wp_enqueue_style(
      'wc-onsite-courses-css',
      WC_ONSITE_COURSES_PLUGIN_URL . 'assets/wc-onsite-courses.css',
      array(),
      '1.0.0',
      'all'
    );

  }

  public function fetchCourseDates() {

    $args = array(
      'post_type' => 'product',
      'numberposts' => -1,
      'meta_key'		=> 'is_course',
	    'meta_value'	=> 1
    );
    $posts = get_posts( $args );

    if( empty( $posts )) {
      return;
    }

    $courseDates = [];
    foreach( $posts as $productPost ) {
      $fields = get_field( 'conditional_fields', $productPost->ID );
      $courseSchedules = $fields['course_schedules'];

      if( empty( $courseSchedules )) {
        continue;
      }

      foreach( $courseSchedules as $courseScheduleField ) {
        $courseSchedule = $courseScheduleField['course_schedule'];

        if( empty( $courseSchedule )) {
          continue;
        }

        foreach( $courseSchedule as $courseDateField ) {

          $date = $courseDateField['course_date'];
          $startTime = $courseDateField['start_time'];
          $endTime = $courseDateField['end_time'];

          $courseDate['title'] = $productPost->post_title;
          $courseDate['product_url'] = get_permalink( $productPost );
          $startTimeObj = DateTime::createFromFormat( 'm-d-Y g:i a', $date. ' ' . $startTime );
          $courseDate['start'] = $startTimeObj->format('Y-m-d h:i:s');
          $endTimeObj = DateTime::createFromFormat( 'm-d-Y g:i a', $date . ' ' . $endTime );
          $courseDate['end'] = $endTimeObj->format('Y-m-d h:i:s');
          $courseDates[] = $courseDate;

        }
      }
    }

    $courseDatesJson = json_encode( $courseDates );
    wp_localize_script(
      'wc-onsite-courses-js',
      'wcOnsiteCourseDates',
      $courseDatesJson
    );

  }

}

new WC_Onsite_Courses_Plugin();
