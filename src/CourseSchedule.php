<?php

class WCOSCO_CourseSchedule {

  public function render( $product ) {

    $fields = get_field( 'conditional_fields', $product->ID );
    $courseSchedules = $fields['course_schedules'];

    if( empty( $courseSchedules )) {
      return '';
    }

    $courseCalendar = [];

    foreach( $courseSchedules as $index => $courseScheduleField ) {

      $courseDates = [];
      $courseSchedule = $courseScheduleField['course_schedule'];

      foreach( $courseSchedule as $courseDateField ) {

        $date = $courseDateField['course_date'];
        $startTime = $courseDateField['start_time'];
        $endTime = $courseDateField['end_time'];

        $courseDate = new stdClass;
        $courseDate->date = $date;
        $courseDate->start = $startTime;
        $courseDate->end = $endTime;
        $courseDates[] = $courseDate;

      }

      $courseCalendar[] = $courseDates;

    }

    $template = new WCOSCO_Template();
    $template->templatePath = 'templates/';
    $template->templateName = 'course-dates';
    $template->data = array(
      'courseCalendar' => $courseCalendar,
    );
    return $template->get();

  }

}
