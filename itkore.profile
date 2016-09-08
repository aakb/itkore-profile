<?php

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function itkore_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  $form['#submit'][] = 'itkore_form_install_configure_submit';
  $form['itkore_settings'] = array(
    '#type' => 'details',
    '#open' => TRUE,
    '#title' => 'ITKore Settings',
    '#weight' => -1,
    '#description' => 'Enable custom modules',
    'modules' => array(
      '#type' => 'checkboxes',
      '#title' => 'Activate modules',
      '#options' => array(
        'itkore_admin' => 'ITKore site specific config page',
        'itk_admin_links' => 'ITK admin links tool',
        'itk_cookie_message' => 'ITK cookie message',
        'itkore_user_roles' => 'ITK default user roles and permissions',
        'itkore_user_theme' => 'ITK admin theme for user login pages',
        'itkore_content_types' => 'Paragraphs, fields and default content types (Page, overview page, news)',
        'itk_hamburger_menu' => 'ITK hamburger menu'
      ),
    ),
  );
}

/**
 * Submission handler to setup site.
 */
function itkore_form_install_configure_submit($form, FormStateInterface $form_state) {
  $form_values = $form_state->getValues();

  foreach($form_values['modules'] as $key => $value) {
    if ($key == $value) {
      // Install custom modules.
      \Drupal::service('module_installer')
        ->install([$key]);
    }
  }

  // Install themes.
  \Drupal::service('theme_installer')
    ->install(['itkore_base']);

  \Drupal::service('theme_installer')
    ->install(['adminimal_theme']);


  // ----- Set config variables. ---- //

  // Set main theme
  \Drupal::service('config.factory')
    ->getEditable('system.theme')
    ->set('default', 'itkore_base')
    ->save();

  // Set admin theme
  \Drupal::service('config.factory')
    ->getEditable('system.theme')
    ->set('admin', 'adminimal_theme')
    ->save();

  // Set admin theme on node editing
  \Drupal::configFactory()
    ->getEditable('node.settings')
    ->set('use_admin_theme', TRUE)
    ->save(TRUE);

  // Disable the user pictures on nodes.
  \Drupal::configFactory()
    ->getEditable('system.theme.global')
    ->set('features.node_user_picture', FALSE)
    ->save(TRUE);

  // Allow visitor account creation, but with administrative approval.
  \Drupal::configFactory()
    ->getEditable('user.settings')
    ->set('register', USER_REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL)
    ->save(TRUE);

  // Set front page.
  \Drupal::configFactory()
    ->getEditable('system.site')
    ->set('page.front', '/front')
    ->save(TRUE);

  // Set imce settings.
  \Drupal::configFactory()
    ->getEditable('imce.settings')
    ->set('roles_profiles.editor.public', 'custom_profile')
    ->save(TRUE);

  // Set meta-tag settings
  \Drupal::configFactory()
    ->getEditable('metatag.metatag_defaults.global')
    ->set('tags.title', '[current-page:title]')
    ->save(TRUE);
  \Drupal::configFactory()
    ->getEditable('metatag.metatag_defaults.node')
    ->set('tags.title', '[node:title]')
    ->save(TRUE);
  \Drupal::configFactory()
    ->getEditable('metatag.metatag_defaults.front'
    )->set('tags.title', '[site:name]')
    ->save(TRUE);

  // Set date format.
  \Drupal::configFactory()
    ->getEditable('core.date_format.medium')
    ->set('pattern', 'j. F Y')
    ->save(TRUE);
}