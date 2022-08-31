<?php

namespace Drupal\custom_date\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;

/**
 * Configuration of manual configurations.
 */
class DateConfiguration extends ConfigFormBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new CompletionRegister object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, Connection $connection) {
    $this->entityTypeManager = $entity_type_manager;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'), $container->get('database')
   );
  }

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'custom_date.common_settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_date_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $instructions = "<div style='background:lightblue;padding:10px;line-height:2;'>
      Please enter  Configuration values for location wise timezon selection <br/>
    </div>";

    $form['custom_date_instructions'] = [
      '#markup' => Markup::create($instructions),
    ];

    $form['date_info'] = [
      '#type' => 'textarea',
      '#title' => 'Custom Date ',
      '#description' => "Please refer above instructions.",
      '#default_value' => $config->get('date_info'),
    ];
    $form['Country'] = [
      '#type' => 'textarea',
      '#title' => 'Country',
      '#description' => "Please Enter Country Name.",
      '#default_value' => $config->get('Country'),
    ];
    $form['City'] = [
      '#type' => 'textarea',
      '#title' => 'City',
      '#description' => "Please Enter City Name.",
      '#default_value' => $config->get('City'),
    ];

    $form['Timezone'] = [
      '#type' => 'select',
      '#title' => 'Select Timezone',
      '#options' => [
         '' => '--Select--',
        'America/Chicago' => 'America/Chicago',
        'America/New_York' => 'America/New_York',
        'Asia/Tokyo' => 'Asia/Tokyo',
        'Asia/Dubai' => 'Asia/Dubai',
        'Asia/Kolkata' => 'Asia/Kolkata',
        'Europe/Amsterdam' => 'Europe/Amsterdam',
        'Europe/Oslo' => 'Europe/Oslo',
        'Europe/London' => 'Europe/London'
      ],
      '#default_value' => $config->get('Timezone'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('date_info', $form_state->getValue('date_info'))
      ->set('Country', $form_state->getValue('Country'))
      ->set('City', $form_state->getValue('City'))
      ->set('Timezone', $form_state->getValue('Timezone'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
