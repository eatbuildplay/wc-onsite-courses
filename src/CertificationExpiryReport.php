<?php

/*
 * 2) User query for users with certificates
      2a) query for users with expiry dates in past
 */

class WCOSCO_CerficationExpiryReport {

  public function __construct() {
    add_filter('woocommerce_admin_reports', [$this, 'addReportTab'], 10, 2 );
  }

  public function addReportTab( $reports ) {

    $reports['wc_onsite_expiry'] = array(
      'title'       => __('Certificate Expiry','wc-onsite-courses'),
      'reports'     => [
        'certificate_expiry' => [
          'title'       => __('Certificate Expiry','wc-onsite-courses'),
          'description' => 'Reports on users with an expiring certificate.',
          'callback'    => array( 'WCOSCO_CerficationExpiryReport', 'expiryReportPage')
        ]
      ]
    );

    return $reports;

  }

  public static function expiryReportPage() {

    $users = get_users();

    $template = new WCOSCO_Template();
    $template->templatePath = 'templates/';
    $template->templateName = 'expiry-report';
    $template->data = array(
      'users' => $users,
    );
    print $template->get();

  }

}
