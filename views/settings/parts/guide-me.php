<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Get the current settings screen key from arguments
$current_screen = $args['current_screen'];

// Determine the corresponding documentation URL based on the current screen
switch ( $current_screen ) {
case 'shipping-fees':
    $doc_url = 'https://themepaste.com/product-name/shipping-manager/';
    break;
case 'box-shipping':
    $doc_url = 'https://themepaste.com/documentation/shipping-manager-documentation/setup-box-size-shipping';
    break;
case 'free-shipping':
    $doc_url = 'https://themepaste.com/documentation/shipping-manager-documentation/set-up-free-shipping';
    break;
case 'shipping-calculator':
    $doc_url = 'https://themepaste.com/documentation/shipping-manager-documentation/shipping-calculator';
    break;
case 'general':
    $doc_url = 'https://themepaste.com/documentation/shipping-manager-documentation';
    break;
default:
    // Fallback to base documentation if no matching section
    $doc_url = 'https://themepaste.com/product-name/shipping-manager/';
    break;
}
?>

<!-- Button linking to the relevant documentation section -->
<button class="tpsm-guide-me-button" id="tpsm-guide-me-button">
    <a href="<?php echo esc_url( $doc_url ); ?>" target="_blank" rel="noopener noreferrer">
        <?php esc_html_e( 'Guide Me', 'shipping-manager' ); ?>
    </a>
</button>