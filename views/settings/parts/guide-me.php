<?php 
    defined( 'ABSPATH' ) || exit;
?>

<button class="tpsm-guide-me-button"><?php esc_html_e( 'Guide Me', 'shipping-manager' ); ?></button>
<div class="tpsm-guide-me-container-wrapper">
    <div class="tpsm-guide-me-container">
        <h2><?php esc_html_e('Click the documentation link or watch the video to learn more about this feature.', 'shipping-manager' ) ?></h2>
        <button><a href="#"><?php esc_html_e( 'Documentation', 'shipping-manager' ); ?></a></button>
        <iframe src="https://www.youtube.com/embed/yVYQeDhAQWk?si=sPacxkWaGkT_g1jH" allowfullscreen></iframe>
    
        <span class="tpsm-guide-me-cross" id="tpsm-guide-me-cross"><?php echo esc_html( '&#10005;' ); ?></span>
    </div>
</div>