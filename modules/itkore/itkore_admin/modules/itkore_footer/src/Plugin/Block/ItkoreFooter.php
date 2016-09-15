<?php

namespace Drupal\itkore_footer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Menu\MenuTreeParameters;

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
    $menu_tree = \Drupal::menuTree();
    foreach ($config['footer_menus'] as $key => $value) {
      if ($key ===  $value) {
        $parameters = new MenuTreeParameters();
        $parameters
          ->setRoot('')
          ->excludeRoot()
          ->onlyEnabledLinks();
        $variables['menu'][$key] = $menu_tree->load($value, $parameters);
        $manipulators = array(
          array('callable' => 'menu.default_tree_manipulators:checkAccess'),
          array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
          array('callable' => 'menu.default_tree_manipulators:flatten'),
        );
        $variables['menu'][$key] = $menu_tree->transform($variables['menu'][$key], $manipulators);
        $menu[$key] = $menu_tree->build($variables['menu'][$key]);
        $menus[$key] = render($menu[$key]);
      }
    }

    $footer_text = check_markup($config['footer_text'], 'filtered_html');

    return array(
      '#type' => 'markup',
      '#theme' => 'itkore_footer_block',
      '#cache' => array(
        'max-age' => 0,
      ),
      '#footer_text' => $footer_text,
      '#menus' => $menus,
    );
  }
}
?>