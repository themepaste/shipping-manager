<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Get the current settings screen key from arguments
$current_screen = $args['current_screen'];

// Determine the corresponding documentation URL based on the current screen
$doc_url = 'https://themepaste.com/documentation/shipping-manager/';
?>

<!-- Button linking to the relevant documentation section -->
<button class="tpsm-guide-me-button" id="tpsm-guide-me-button">
    <a href="<?php echo esc_url( $doc_url ); ?>" target="_blank" rel="noopener noreferrer">
        <?php esc_html_e( 'Guide Me', 'shipping-manager' ); ?>
    </a>
</button>