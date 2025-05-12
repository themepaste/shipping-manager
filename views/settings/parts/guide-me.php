<?php 
    defined( 'ABSPATH' ) || exit;
    $current_screen = $args['current_screen'];

switch ( $current_screen ) {
    case 'shipping-fees':
        $doc_url = 'shipping-fees';
        break;
    case 'box-shipping':
        $doc_url = 'box-shipping';
        break;
    case 'free-shipping':
        $doc_url = 'free-shipping';
        break;
    case 'shipping-calculator':
        $doc_url = 'shipping-calculator';
        break;
    case 'general':
        $doc_url = 'general';
        break;
    default:
        $doc_url = 'https://themepaste.com/documentation/shipping-manager-documentation';
        
}
?>
<button class="tpsm-guide-me-button" id="tpsm-guide-me-button"><a href="<?php echo esc_url( $doc_url ); ?>"><?php esc_html_e( 'Guide Me', 'shipping-manager' ); ?></a></button>