<?php

/**
 * @file
 * Renders breadcrumbs.
 */

namespace Drupal\custom_date\Plugin\Block;

use Drupal\Core\Block\BlockBase;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;


/**
 * Create a block for manual Date.
 *
 * @Block(
 *   id = "custom_date",
 *   admin_label = @Translation("Custom Date Formatter")
 * )
 */
class DatetimeFormat extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $custom_date_obj = \Drupal::config('custom_date.common_settings');
    $custom_city_lsv = $custom_date_obj->get('City');
    $GOOGLE_API_KEY_HERE = "AIzaSyBN2sYMbpjo7bvKR00v2e_aJ6mkwX2Xyaw";

    $data_location = "https://maps.google.com/maps/api/geocode/json?key=".$GOOGLE_API_KEY_HERE."&address=".str_replace(" ", "+", $custom_city_lsv)."&sensor=false";
    $data = file_get_contents($data_location);
    usleep(200000);
      $data = json_decode($data);
      if ($data->status=="OK") {
        $lat = $data->results[0]->geometry->location->lat;
        $lng = $data->results[0]->geometry->location->lng;
      }
    $html = '';
    $data = \Drupal::service('custom_date.custom_services')->getData();
    $DateTime =  [
      '#theme' => 'location_date',
      '#lat' => $lat,
      '#long' => $lng,
      '#date' => $date
    ];
    return array($DateTime);
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['my_block_settings'] = $form_state->getValue('my_block_settings');
  }
}
