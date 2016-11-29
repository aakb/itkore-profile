<?php

namespace Drupal\itkore_footer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides footer content
 *
 * @Block(
 *   id = "itkore_footer",
 *   admin_label = @Translation("ITKore footer"),
 * )
 */
class ItkoreFooter extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::getContainer()->get('itkore_admin.itkore_config')->getAll();
    $menus = array();


    foreach ($config['footer_menus'] as $key => $value) {
      if ($key ===  $value) {
        $menu_tree = \Drupal::menuTree();
        $menu_name = $value;

        // Build the typical default set of menu tree parameters.
        $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);

        // Load the tree based on this set of parameters.
        $tree = $menu_tree->load($menu_name, $parameters);

        // Transform the tree using the manipulators you want.
        $manipulators = array(
          // Only show links that are accessible for the current user.
          array('callable' => 'menu.default_tree_manipulators:checkAccess'),
          // Use the default sorting of menu links.
          array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
        );
        $tree = $menu_tree->transform($tree, $manipulators);

        // Finally, build a renderable array from the transformed tree.
        $menu = $menu_tree->build($tree);
        $menu_entity = \Drupal::entityTypeManager()->getStorage('menu')->load($value);
        if ($menu_entity) {
          $menu['#menu_name'] = $menu_entity->label();
          $menu_html = render($menu);
          $menus[$value] = $menu_html;
        }
      }
    }
    $contact_text = check_markup($config['contact_text'], 'filtered_html');
    $footer_text = check_markup($config['footer_text'], 'filtered_html');

    return array(
      '#type' => 'markup',
      '#theme' => 'itkore_footer_block',
      '#cache' => array(
        'max-age' => 0,
      ),
      '#contact_text' => $contact_text,
      '#footer_text' => $footer_text,
      '#menus' => $menus,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    // Menu select list
    $form['item'] = array (
      '#type' => 'item',
      '#description' => t('NOTE: Part of the configuration for this block is found in <a href="/admin/site-setup/footer">site settings</a>'),
      '#weight' => '0',
    );

    return $form;
  }
}
?>