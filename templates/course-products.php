<div id="onsite-course-products">

  <input type="hidden" id="onsite_course_product_list" value="<?php print json_encode($productIds); ?>" />

  <?php if( !empty( $courseProducts )) : ?>

    <h2>Step 2 - Select Course Supplies</h2>
    <p>Below is a list of each product related to this course. Products that are required will be automatically added to your cart, products that are optional you may choose to uncheck if you wish.</p>

    <table>

    <?php foreach( $courseProducts as $productField ) :
      $product = $productField['product'];
      $required = $productField['required'];

      $rowClasses = 'course-product-row selected';
      if( $required ) {
        $rowClasses .= ' required';
      }

    ?>

      <tr data-product-id="<?php print $product->ID; ?>" class="<?php print $rowClasses; ?>">
        <td>
          <h3>
            <?php print $product->post_title; ?>
          </h3>
        </td>
        <td>
          <input
            type='checkbox'
            checked='checked'
            value="<?php print $product->ID; ?>"
            <?php if( $required ) { print 'disabled'; } ?>
            />
        </td>
      </tr>

    <?php endforeach; ?>
    </table>

  <?php endif; ?>

</div>
