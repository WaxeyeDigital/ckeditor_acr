<?php

namespace Drupal\ckeditor_acr\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "ACRconfig" plugin.
 *
 * @CKEditorPlugin(
 *   id = "acrconfig",
 *   label = @Translation("CKEditor Allowed Content Rules configuration")
 * )
 */
class ACRconfig extends CKEditorPluginBase implements CKEditorPluginConfigurableInterface, CKEditorPluginContextualInterface {

  /**
   * {@inheritdoc}
   */
  public function isInternal() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled(Editor $editor) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    $config = [];
    $settings = $editor->getSettings();
    if (!isset($settings['plugins']['acrconfig']['ckeditor_acr_config'])) {
      return $config;
    }

    $custom_config = $settings['plugins']['acrconfig']['ckeditor_acr_config'];
    // Check if acrconfig is populated.
    if (!empty($custom_config)) {
      // Build array from string.
      $config_array = preg_split('/\R/', $custom_config);
      $element_config = [];
      foreach ($config_array as $conf) {

        // @todo Implement better approach for getting rules.
        // Get tag name.
        preg_match("/^([a-z0-9\-*\s]+)/i", $conf, $tag);
        $tag = isset($tag[1]) ? $tag[1] : '';
        // Get attribute string.
        preg_match("/\[(.*?)\]/", $conf, $attrib);
        $attributes = isset($attrib[1]) ? $attrib[1] : FALSE;
        // Get styles string.
        preg_match("/{(.*?)}/", $conf, $styles);
        $styles = isset($styles[1]) ? $styles[1] : FALSE;
        // Get classes string.
        preg_match("/\((.*?)\)/", $conf, $classes);
        $classes = isset($classes[1]) ? $classes[1] : FALSE;

        // Create config array for each element.
        if ($tag) {
          $element_config[$tag] = [
            'attributes' => $attributes,
            'classes' => $classes,
            'styles' => $styles,
          ];
        }
      }

      $config['ckeditor_acr_rules'] = $element_config;
    }

    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state, Editor $editor) {

    $config = ['ckeditor_acr_config' => ''];
    $settings = $editor->getSettings();
    if (isset($settings['plugins']['acrconfig'])) {
      $config = $settings['plugins']['acrconfig'];
    }

    $form['ckeditor_acr_config'] = [
      '#type' => 'textarea',
      '#title' => $this->t('CKEditor Allowed Content Rules Override'),
      '#default_value' => $config['ckeditor_acr_config'],
      '#description' => $this->t('Each line may contain a CKeditor Allowed Content Rules configuration setting formatted as "<code>img[alt,!src]{width,height}</code>" A rule accepting img tag with a required "src" attribute and an optional "alt" attribute plus optional "width" and "height" styles. See <a href="@url">Allowed Content Rules</a> for more details.', ['@url' => 'https://ckeditor.com/docs/ckeditor4/latest/guide/dev_allowed_content_rules.html']),
    ];

    return $form;
  }

}
