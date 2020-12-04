<div id="onsite-course-dates">

  <h2>Step 1 - Choose your course dates</h2>

  <?php foreach( $courseCalendar as $courseDates ): ?>
  <table class="course-date-selector">
    <input type="hidden" class="course-dates-json" value="<?php print htmlspecialchars(json_encode( $courseDates )); ?>" />
    <thead>
      <tr>
        <th>Course Date</th>
        <th>Start Time</th>
        <th>End Time</th>
      </tr>
    <thead>
    <tbody>
      <?php foreach( $courseDates as $courseDate ): ?>
        <tr>
          <td><?php print $courseDate->date; ?></td>
          <td><?php print $courseDate->start; ?></td>
          <td><?php print $courseDate->end; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endforeach; ?>

</div>

<script>

jQuery(document).ready(function( $ ) {

	$('.course-date-selector').on('click', function() {
    console.log('selected course dates')
    $('.course-date-selector').removeClass('selected')
    $(this).addClass('selected')
    window.selectedCourseDates = $(this).find('.course-dates-json').val()

    console.log( window.selectedCourseDates )

  })

});

</script>
