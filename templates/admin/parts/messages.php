<?php
/**
 * Message template
 *
 * @since TSM_SINCE
 */

defined( 'ABSPATH' ) || exit;
?>

<?php if ( empty( $messages ) ) : ?>

<?php else: ?>

	<?php foreach( $messages as $type => $specific_type_message ) : ?>
		<div class="admin-messages message-type-<?php esc_attr_e( $type ); ?>">
		<?php foreach ( $specific_type_message as $message ): ?>
			<div class="admin-single-message">
				<?php echo esc_html( $message ); ?>
			</div>
		<?php endforeach; ?>

	<?php endforeach; ?>

<?php endif; ?>