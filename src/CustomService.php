<?php

namespace Drupal\custom_date;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Class CustomService
 * @package Drupal\custom_date\Services
 */
class CustomService {

  protected $currentTimeZone;

  /**
   * CustomService constructor.
   * @param AccountInterface $currentUser
   */
  public function __construct() {
    $custom_date_obj = \Drupal::config('custom_date.common_settings');
    $custom_date_timezone = $custom_date_obj->get('Timezone');
    $this->currentTimeZone = $custom_date_timezone;
  }

  /**
   * @return \Drupal\Component\Render\MarkupInterface|string
   */
  public function getData() {
    //$date = \Drupal::service('date.formatter');
    $date = new DrupalDateTime();
    $date->setTimezone(new \DateTimeZone($this->currentTimeZone));
    $curr_date = $date->format('jS M, Y - g:i A');
    return $curr_date;
  }

}
