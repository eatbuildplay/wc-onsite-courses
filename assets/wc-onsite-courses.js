document.addEventListener('DOMContentLoaded', function() {

  var calendarEl = document.getElementById('onsite_course_calendar');
  if( !calendarEl ) {
    return
  }

  function fetchEvents() {

    var courseDates = JSON.parse(wcOnsiteCourseDates)

    console.log(courseDates)

    var events = []
    courseDates.forEach(function (item, index) {
      var event = {
        title: item.title,
        description: '',
        url: item.product_url,
        start: item.start,
        end: item.end
      }
      events.push( event )
    });

    console.log( events )

    return events

  }

  // get date
  /*
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();

  today = mm + '/' + dd + '/' + yyyy;
  document.write(today);
  */

  var events = fetchEvents();
  var calendarEl = document.getElementById('onsite_course_calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: [ 'dayGrid' ],
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridDay'
    },
    navLinks: true, // can click day/week names to navigate views
    businessHours: true, // display business hours
    editable: true,
    height: 'auto',
    contentHeight: 'auto',
    //defaultView: 'month',
    eventClick: function( info ) {
      console.log( info )
    },
    eventRender: function( info ) {
      console.log( info.event )
      console.log( info.event.extendedProps )
      info.el.querySelector('.fc-title').innerHTML = '<a href="' + info.event.url + '"><div>' + info.event.title + ' - <i>' + info.event.extendedProps.description + '</i></div></a>';
    },
    events: events
  });

  calendar.render();

});



/*
 * jQuery handlers
 */
(function($) {

  // click handler
  $('#onsite-courses-cart-button').on('click', function() {

    // show loading loading and clear messages
    $('#loading').show()
    $('#onsite-courses-cart-messages').html('')
    $('#onsite-courses-cart-messages').hide()

    let productIdsJson = $('#onsite_course_product_list').val()
    let productIds = JSON.parse( productIdsJson )

    /*
     * Validate attempt to add to cart
     */

    console.log( window.selectedCourseDates );

    if( window.selectedCourseDates == undefined ) {
      $('#onsite-courses-cart-messages').html('Please choose your course dates first.')
      $('#onsite-courses-cart-messages').show()
      $('#loading').hide()
      return;
    }

    wcoscCartAddJx( productIds )

  })

  // add cart hook
  function wcoscCartAddJx( productIds ) {

    data = {
      action: 'onsite_courses_cart_add',
      products: productIds,
      courseDates: window.selectedCourseDates
    }

    $.post( woocommerce_params.ajax_url, data, function( response ) {

      response = JSON.parse( response )

      if ( response.status == 300 ) {
        $('#onsite-courses-cart-messages').html('Adding your products to the cart failed. Please try again and if the problem persists contact our support for immediate assistance.')
        $('#onsite-courses-cart-messages').show()
        return;
      }

      window.location.href = window.location.href + '/cart'

    });

  }

  $('tr.course-product-row').on('click', function() {

    if( $(this).hasClass('required')) {
      return;
    }



    if( $(this).hasClass('selected') ) {

      $(this).removeClass('selected')
      $(this).find('input').prop('checked', false)

      // remove from storage
      var productIdRemove = $(this).data('product-id')
      var productList = $('#onsite_course_product_list').val()
      productList = JSON.parse( productList )

      const index = productList.indexOf( productIdRemove );
      if (index > -1) {
        productList.splice(index, 1);
      }

      productList = JSON.stringify( productList )
      $('#onsite_course_product_list').val( productList )

      console.log( productList )

    } else {

      $(this).addClass('selected')
      $(this).find('input').prop('checked', true)

      // add to storage
      var productIdAdd = $(this).data('product-id')
      var productList = $('#onsite_course_product_list').val()
      productList = JSON.parse( productList )
      productList.push( productIdAdd )
      productList = JSON.stringify( productList )
      $('#onsite_course_product_list').val( productList )

      console.log( productList )

    }
  })

})( jQuery );
