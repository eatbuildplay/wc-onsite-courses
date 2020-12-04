<?php

class WCOSCO_CourseProducts {

  public function __construct() {
    add_action('init', array( $this, 'init'));
  }

  public function render( $product ) {

    $fields = get_field( 'conditional_fields', $product->ID );
    $courseProducts = $fields['course_products'];

    // form list of product ids for js
    $productIds = array( $product->ID );
    if( !empty( $courseProducts )) {
      foreach( $courseProducts as $productField ) :
        $product = $productField['product'];
        $productIds[] = $product->ID;
      endforeach;
    }

    $template = new WCOSCO_Template();
    $template->templatePath = 'templates/';
    $template->templateName = 'course-products';
    $template->data = array(
      'courseProducts' => $courseProducts,
      'productIds' => $productIds
    );
    return $template->get();

  }

}
