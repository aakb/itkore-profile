<?php

namespace Drupal\itkore_frontpage_header\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\file\Entity\File;

/**
 * Provides frontpage header
 *
 * @Block(
 *   id = "itkore_frontpage_header",
 *   admin_label = @Translation("ITKore frontpage header"),
 * )
 */
class ItkoreFrontpageHeader extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::getContainer()->get('itkore_admin.itkore_config')->getAll();

    // Fetch header top file.
    $file = ($config['frontpage_image']) ? File::load($config['frontpage_image']) : FALSE;
    $config['frontpage_image_url'] = $file ? $file->url() : '';

    return array(
      '#type' => 'markup',
      '#theme' => 'itkore_frontpage_header_block',
      '#cache' => array(
        'max-age' => 0,
      ),
      '#variables' => $config,
    );
  }
}
?>