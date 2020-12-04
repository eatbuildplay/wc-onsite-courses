<?php

class WCOSCO_CartHandler {

  public function __construct() {

    add_action('wp_ajax_onsite_courses_cart_add', array( $this, 'ajaxCartAdd'));
    add_action('wp_ajax_nopriv_onsite_courses_cart_add', array( $this, 'ajaxCartAdd'));

    add_filter('woocommerce_get_cart_item_from_session', array( $this, 'filterCartDataSession'), 1, 3 );
    add_filter('woocommerce_checkout_cart_item_quantity', array( $this, 'filterCartDisplay'), 1, 3);
    add_filter('woocommerce_cart_item_price', array( $this, 'filterCartDisplay'), 1, 3);


  }

  public function render( $product ) {

    $template = new WCOSCO_Template();
    $template->templatePath = 'templates/';
    $template->templateName = 'add-to-cart';
    return $template->get();

  }

  public function ajaxCartAdd() {

    $products = $_POST['products'];
    $courseDates = $_POST['courseDates'];
    $result = $this->addProductsToCart( $products, $courseDates );

    if( $result ) {
      $status = 100;
    } else {
      $status = 300;
    }

    $response = array(
      'status' => $status,
      'result' => $result
    );

    print json_encode( $response );

    wp_die();

  }


  public function addProductsToCart( $products, $courseDates ) {

    $courseDates = stripslashes( htmlspecialchars_decode( $courseDates ));
    $courseDates = json_decode( $courseDates );

    if( empty( $courseDates )) {
      return false;
    }

    if( empty( $products )) {
      return false;
    }

    // add to cart
    foreach( $products as $index => $productId ) {

      if( $index == 0 ) {

        $startDate = $courseDates[0];
        $cartItemKey = WC()->cart->add_to_cart(
          $productId,
          1,
          0,
          [],
          [ 'course_start_date' => $startDate->date ]
        );
      } else {
        $cartItemKey = WC()->cart->add_to_cart( $productId );
      }
    }

    return true;

  }

  public function filterCartDataSession( $item, $values, $key ) {

    //Check if the key exist and add it to item variable.
    if (array_key_exists( 'custom875', $values ) )
    {
      $item['custom875'] = $values['custom875'];
    }
    return $item;
  }


public function filterCartDisplay( $product_name, $values, $cart_item_key ) {

    global $wpdb;

    if( !empty($values['custom875'] )) {
        $return_string = "<table>
                            <tr>
                              <th>" . ucfirst($values['custom875']) . "</th>"
                             ."<td>Start date: " . $values['custom875'] . "</td>
                            </tr>
                          </table>";
        return $return_string;

    }
}


}
