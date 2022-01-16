<?php
namespace Drupal\qr_link_generator\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "qr_link_generator",
 *   admin_label = @Translation("QR Link Generator Block"),
 *   category = @Translation("Product"),
 * )
 */
class QRLinkGeneratorBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    // Set default markup in case this block will be placed elsewhere,
    // other than nodes with Product content type.
    $result = [
      '#markup' => '<div class="product__qr-code__wrap">' . $this->t('Generates QR code for products only!') . '</div>',
    ];

    if ($node) {
      $baseURL = \Drupal::request()->getSchemeAndHttpHost();
      $moduleHanlder = \Drupal::service('module_handler');
      $qrCodePath = $moduleHanlder->getModule('qr_link_generator')->getPath() . '/assets/images/qrcodes/';
      // No need to check the app purchase url field value if empty since the field is required.
      $appPurchaseLinkURL = $node->get('field_app_purchase_link')->get(0)->getUrl();
      $appPurchaseLinkAbsURL = URL::fromUri($appPurchaseLinkURL->toUriString(), [
        'absolute' => TRUE,
      ])->toString();
      // Create a QR code file name.
      $qrCodeFilename = "product-{$node->get('nid')->value}-qr-code.png";
      // Generate QR Code.
      $renderer = new ImageRenderer(
        new RendererStyle(400),
        new ImagickImageBackEnd()
      );
      $writer = new Writer($renderer);
      $writer->writeFile($appPurchaseLinkAbsURL, $qrCodePath . $qrCodeFilename);

      // QR code section texts.
      $qrCodeHeading = $this->t('Scan here on your mobile');
      $qrCodeDescription = $this->t('To purchase this product on our app to avail exclusive app-only');
      // Create the QR Code section markup.
      $result['#markup'] = "
        <div class=\"product__qr-code__wrap\">
          <div class=\"product__qr-code__head\">
            <h3>{$qrCodeHeading}</h3>
          </div>
          <div class=\"product__qr-code__body\">
            <h4>{$qrCodeDescription}</h4>
            <img alt=\"QR Code\" src=\"{$baseURL}/{$qrCodePath}{$qrCodeFilename}\" />
          </div>
        </div>";
    }

    return $result;
  }

  /**
   * {@inheritdoc}
   * return 0 If you want to disable caching for this block.
   */
  public function getCacheMaxAge() {
    return 0;
  }

}