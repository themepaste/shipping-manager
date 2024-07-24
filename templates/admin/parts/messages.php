<?php
/**
 * Message template
 *
 * @since TSM_SINCE
 */

defined( 'ABSPATH' ) || exit;
?>
<?php if ( ! empty( $messages ) ) : ?>
	<?php foreach( $messages as $type => $specific_type_message ) : ?>
		<div class="admin-messages message-type-<?php echo esc_attr( $type ); ?>">
		<?php foreach ( $specific_type_message as $message ): ?>
			<div class="admin-single-message">
				<?php echo esc_html( $message['message'] ); ?>
			</div>
		<?php endforeach; ?>
    </div>
	<?php endforeach; ?>

<?php endif; ?>