<?php
/**
 * Setup guide shown on WooCommerce -> Settings -> Shipping -> Shipping Manager.
 *
 * This is the plugin's landing screen: it explains what Shipping Manager does,
 * where every piece is configured, and reports the store's current state so a
 * merchant can tell at a glance whether setup is finished.
 *
 * Rendered directly by RegisterShippingMethod::admin_options(), so the markup
 * is not filtered and inline SVG icons are safe to use here.
 *
 * @package Shipping_Manager
 *
 * @var array  $zone_usage  Zones the Shipping Manager method is added to.
 * @var array  $store       Store context (currency, weight unit, class count).
 * @var string $zones_url   URL of the WooCommerce shipping zones screen.
 * @var string $classes_url URL of the WooCommerce shipping classes screen.
 * @var string $tax_url     URL of the WooCommerce tax settings screen.
 * @var array  $conditions  Available rule conditions, slug => label.
 */

defined( 'ABSPATH' ) || exit;

$is_configured = ! empty( $zone_usage );
$total_rules   = array_sum( wp_list_pluck( $zone_usage, 'rule_count' ) );

// Step 2 only counts as done once at least one rule actually exists.
$steps_done = array(
    $is_configured,
    $total_rules > 0,
    (bool) $store['shipping_classes'],
);

/**
 * Inline icon.
 *
 * @param string $name Icon name.
 * @return string
 */
$tpsm_icon = static function ( $name ) {
    $paths = array(
        'external' => '<path d="M10 2h4v4M14 2 7.5 8.5M12 9.5V13a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3.5"/>',
        'arrow'    => '<path d="M2 8h11M9 4l4 4-4 4"/>',
        'check'    => '<path d="M3 8.5 6.5 12 13 4.5"/>',
        'zone'     => '<circle cx="8" cy="7" r="2.2"/><path d="M8 1.5c2.5 0 4.5 2 4.5 4.5 0 3.2-4.5 8.5-4.5 8.5S3.5 9.2 3.5 6c0-2.5 2-4.5 4.5-4.5Z"/>',
        'rules'    => '<path d="M2.5 4h11M2.5 8h11M2.5 12h7"/>',
        'box'      => '<path d="M8 1.8 14 5v6l-6 3.2L2 11V5l6-3.2Z"/><path d="M2 5l6 3.2L14 5M8 8.2V14"/>',
        'info'     => '<circle cx="8" cy="8" r="6.3"/><path d="M8 7.3V11M8 5.2v.1"/>',
    );

    if ( ! isset( $paths[ $name ] ) ) {
        return '';
    }

    return '<svg class="tpsm-i" viewBox="0 0 16 16" width="16" height="16" aria-hidden="true" focusable="false" '
        . 'fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">'
        . $paths[ $name ] . '</svg>';
};
?>
<div class="tpsm-guide">

    <!-- Header -->
    <div class="tpsm-guide-topbar">
        <div class="tpsm-guide-brand">
            <span class="tpsm-guide-logo">
                <img src="<?php echo esc_url( TPSM_ASSETS_URL . '/img/logo.png' ); ?>" alt="" width="52" height="48" />
            </span>
            <span class="tpsm-guide-titles">
                <span class="tpsm-guide-title"><?php esc_html_e( 'Shipping Manager', 'shipping-manager' ); ?></span>
                <span class="tpsm-guide-tagline"><?php esc_html_e( 'One solution for all shipping needs', 'shipping-manager' ); ?></span>
            </span>
        </div>
        <div class="tpsm-guide-actions">
            <a class="tpsm-guide-btn tpsm-guide-btn-ghost" href="https://wordpress.org/plugins/shipping-manager/#description" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e( 'Documentation', 'shipping-manager' ); ?>
                <?php echo $tpsm_icon( 'external' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </a>
            <a class="tpsm-guide-btn tpsm-guide-btn-primary" href="<?php echo esc_url( $zones_url ); ?>">
                <?php echo $tpsm_icon( 'zone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php esc_html_e( 'Setup Methods to Zones', 'shipping-manager' ); ?>
            </a>
        </div>
    </div>

    <!-- Setup status -->
    <div class="tpsm-guide-status <?php echo $is_configured ? 'is-ready' : 'is-pending'; ?>">
        <span class="tpsm-guide-status-icon" aria-hidden="true"></span>
        <p class="tpsm-guide-status-text">
            <?php if ( $is_configured ) : ?>
                <strong><?php esc_html_e( 'Shipping Manager is live.', 'shipping-manager' ); ?></strong>
                <?php
                printf(
                    /* translators: 1: comma-separated zone names, 2: rule count phrase. */
                    esc_html__( 'Active in %1$s with %2$s.', 'shipping-manager' ),
                    '<b>' . esc_html( implode( ', ', array_unique( wp_list_pluck( $zone_usage, 'zone_name' ) ) ) ) . '</b>',
                    '<b>' . esc_html(
                        sprintf(
                            /* translators: %d: total number of shipping rules. */
                            _n( '%d rule', '%d rules', $total_rules, 'shipping-manager' ),
                            $total_rules
                        )
                    ) . '</b>'
                );
                ?>
            <?php else : ?>
                <strong><?php esc_html_e( 'Almost there.', 'shipping-manager' ); ?></strong>
                <?php esc_html_e( 'Shipping Manager is not in a shipping zone yet, so customers will not see it at checkout.', 'shipping-manager' ); ?>
            <?php endif; ?>
        </p>
    </div>

    <!-- How it works -->
    <section class="tpsm-guide-card">
        <h2 class="tpsm-guide-h"><?php esc_html_e( 'How it works', 'shipping-manager' ); ?></h2>
        <p class="tpsm-guide-lead">
            <?php esc_html_e( 'Shipping Manager adds a shipping method that you place inside a WooCommerce shipping zone. Inside that method you build a list of rules. At checkout every rule that matches the cart is evaluated, and the matching costs are added together into a single shipping rate.', 'shipping-manager' ); ?>
        </p>
        <div class="tpsm-guide-flow">
            <span class="tpsm-guide-flow-step"><?php esc_html_e( 'Shipping zone', 'shipping-manager' ); ?></span>
            <span class="tpsm-guide-flow-sep"><?php echo $tpsm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            <span class="tpsm-guide-flow-step"><?php esc_html_e( 'Shipping Manager method', 'shipping-manager' ); ?></span>
            <span class="tpsm-guide-flow-sep"><?php echo $tpsm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            <span class="tpsm-guide-flow-step"><?php esc_html_e( 'Your rules', 'shipping-manager' ); ?></span>
            <span class="tpsm-guide-flow-sep"><?php echo $tpsm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            <span class="tpsm-guide-flow-step is-result"><?php esc_html_e( 'Rate at checkout', 'shipping-manager' ); ?></span>
        </div>
    </section>

    <!-- Setup steps -->
    <section class="tpsm-guide-card">
        <h2 class="tpsm-guide-h"><?php esc_html_e( 'Set it up in 3 steps', 'shipping-manager' ); ?></h2>
        <ol class="tpsm-guide-steps">
            <li class="<?php echo $steps_done[0] ? 'is-done' : ''; ?>">
                <span class="tpsm-guide-step-num">
                    <i>1</i><?php echo $tpsm_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </span>
                <div class="tpsm-guide-step-body">
                    <h3><?php esc_html_e( 'Add the method to a shipping zone', 'shipping-manager' ); ?></h3>
                    <p><?php esc_html_e( 'Open a zone (or create one for the regions you deliver to), choose "Add shipping method", and pick Shipping Manager.', 'shipping-manager' ); ?></p>
                    <a class="tpsm-guide-link" href="<?php echo esc_url( $zones_url ); ?>">
                        <?php esc_html_e( 'Go to shipping zones', 'shipping-manager' ); ?>
                        <?php echo $tpsm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
                </div>
            </li>
            <li class="<?php echo $steps_done[1] ? 'is-done' : ''; ?>">
                <span class="tpsm-guide-step-num">
                    <i>2</i><?php echo $tpsm_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </span>
                <div class="tpsm-guide-step-body">
                    <h3><?php esc_html_e( 'Build your rules', 'shipping-manager' ); ?></h3>
                    <p><?php esc_html_e( 'Click the method you added to open its settings. There you set the name customers see, the tax status, and the rules table where each row is one condition and its cost.', 'shipping-manager' ); ?></p>
                </div>
            </li>
            <li class="<?php echo $steps_done[2] ? 'is-done' : ''; ?>">
                <span class="tpsm-guide-step-num">
                    <i>3</i><?php echo $tpsm_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </span>
                <div class="tpsm-guide-step-body">
                    <h3><?php esc_html_e( 'Check your product data', 'shipping-manager' ); ?></h3>
                    <p><?php esc_html_e( 'Weight rules need product weights, and shipping class rules need classes assigned to products. Rules whose data is missing are simply skipped.', 'shipping-manager' ); ?></p>
                    <a class="tpsm-guide-link" href="<?php echo esc_url( $classes_url ); ?>">
                        <?php esc_html_e( 'Manage shipping classes', 'shipping-manager' ); ?>
                        <?php echo $tpsm_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
                </div>
            </li>
        </ol>
    </section>

    <div class="tpsm-guide-grid">

        <!-- Rule conditions -->
        <section class="tpsm-guide-card">
            <h2 class="tpsm-guide-h">
                <?php echo $tpsm_icon( 'rules' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php esc_html_e( 'Rule conditions', 'shipping-manager' ); ?>
            </h2>
            <p class="tpsm-guide-sub"><?php esc_html_e( 'Costs from every matching row are added together.', 'shipping-manager' ); ?></p>
            <ul class="tpsm-guide-conditions">
                <?php foreach ( $conditions as $condition_slug => $condition_label ) : ?>
                    <li>
                        <span class="tpsm-guide-chip"><?php echo esc_html( $condition_label ); ?></span>
                        <span class="tpsm-guide-chip-desc"><?php echo esc_html( tpsm_get_condition_description( $condition_slug ) ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="tpsm-guide-note">
                <?php echo $tpsm_icon( 'info' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <span><?php esc_html_e( 'Leaving a minimum or maximum empty means "no limit" on that side. A rule with a minimum of 100 and an empty maximum applies to every cart of 100 and above.', 'shipping-manager' ); ?></span>
            </p>
        </section>

        <!-- Store context -->
        <section class="tpsm-guide-card">
            <h2 class="tpsm-guide-h">
                <?php echo $tpsm_icon( 'box' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php esc_html_e( 'Your store right now', 'shipping-manager' ); ?>
            </h2>
            <p class="tpsm-guide-sub"><?php esc_html_e( 'Rules are interpreted using these WooCommerce settings.', 'shipping-manager' ); ?></p>
            <div class="tpsm-guide-stats">
                <div class="tpsm-guide-stat">
                    <span class="tpsm-guide-stat-value"><?php echo esc_html( $store['currency'] ); ?></span>
                    <span class="tpsm-guide-stat-label"><?php esc_html_e( 'Currency', 'shipping-manager' ); ?></span>
                    <span class="tpsm-guide-stat-note"><?php esc_html_e( 'Costs you enter use this.', 'shipping-manager' ); ?></span>
                </div>
                <div class="tpsm-guide-stat">
                    <span class="tpsm-guide-stat-value"><?php echo esc_html( $store['weight_unit'] ); ?></span>
                    <span class="tpsm-guide-stat-label"><?php esc_html_e( 'Weight unit', 'shipping-manager' ); ?></span>
                    <span class="tpsm-guide-stat-note"><?php esc_html_e( 'Weight rules compare in this.', 'shipping-manager' ); ?></span>
                </div>
                <div class="tpsm-guide-stat">
                    <span class="tpsm-guide-stat-value"><?php echo esc_html( $store['shipping_classes'] ); ?></span>
                    <span class="tpsm-guide-stat-label"><?php esc_html_e( 'Shipping classes', 'shipping-manager' ); ?></span>
                    <span class="tpsm-guide-stat-note">
                        <?php if ( $store['shipping_classes'] ) : ?>
                            <?php esc_html_e( 'Usable in class rules.', 'shipping-manager' ); ?>
                        <?php else : ?>
                            <a class="tpsm-guide-link" href="<?php echo esc_url( $classes_url ); ?>"><?php esc_html_e( 'Create one', 'shipping-manager' ); ?></a>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="tpsm-guide-stat">
                    <span class="tpsm-guide-stat-value">
                        <?php echo $store['taxes_enabled'] ? esc_html__( 'On', 'shipping-manager' ) : esc_html__( 'Off', 'shipping-manager' ); ?>
                    </span>
                    <span class="tpsm-guide-stat-label"><?php esc_html_e( 'Taxes', 'shipping-manager' ); ?></span>
                    <span class="tpsm-guide-stat-note">
                        <a class="tpsm-guide-link" href="<?php echo esc_url( $tax_url ); ?>"><?php esc_html_e( 'Tax settings', 'shipping-manager' ); ?></a>
                    </span>
                </div>
            </div>
        </section>
    </div>

    <?php if ( $is_configured ) : ?>
        <!-- Configured methods -->
        <section class="tpsm-guide-card">
            <h2 class="tpsm-guide-h"><?php esc_html_e( 'Where your rules live', 'shipping-manager' ); ?></h2>
            <p class="tpsm-guide-sub"><?php esc_html_e( 'Jump straight into any Shipping Manager method you have set up.', 'shipping-manager' ); ?></p>
            <ul class="tpsm-guide-methods">
                <?php foreach ( $zone_usage as $usage ) : ?>
                    <li>
                        <a href="<?php echo esc_url( $usage['edit_url'] ); ?>">
                            <span class="tpsm-guide-method-head">
                                <span class="tpsm-guide-method-name"><?php echo esc_html( $usage['method_title'] ); ?></span>
                                <span class="tpsm-guide-method-count <?php echo $usage['rule_count'] ? '' : 'is-empty'; ?>">
                                    <?php
                                    printf(
                                        /* translators: %d: number of shipping rules. */
                                        esc_html( _n( '%d rule', '%d rules', $usage['rule_count'], 'shipping-manager' ) ),
                                        absint( $usage['rule_count'] )
                                    );
                                    ?>
                                </span>
                            </span>
                            <span class="tpsm-guide-method-zone">
                                <?php echo $tpsm_icon( 'zone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                <?php echo esc_html( $usage['zone_name'] ); ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <!-- Tip -->
    <section class="tpsm-guide-card tpsm-guide-tip">
        <h2 class="tpsm-guide-h"><?php esc_html_e( 'Copying rules between methods', 'shipping-manager' ); ?></h2>
        <p>
            <?php esc_html_e( 'Every method\'s settings include an Import/Export field holding its rules as text. Copy that value from one method and paste it into another to reuse the same rules in a different zone.', 'shipping-manager' ); ?>
        </p>
    </section>

    <!-- Footer -->
    <div class="tpsm-guide-footer">
        <p class="tpsm-guide-footer-links">
            <a href="https://wordpress.org/plugins/shipping-manager/#description" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Documentation', 'shipping-manager' ); ?></a>
            <a href="https://wordpress.org/support/plugin/shipping-manager/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Get support', 'shipping-manager' ); ?></a>
        </p>
        <p class="tpsm-guide-rating">
            <?php
            printf(
                /* translators: 1: plugin name, 2: star symbols, 3: opening link tag, 4: closing link tag */
                esc_html__( 'If you like %1$s you can rate us %2$s %3$sin plugins repository →%4$s', 'shipping-manager' ),
                '<strong>' . esc_html__( 'Shipping Manager', 'shipping-manager' ) . '</strong>',
                '<span class="tpsm-guide-stars" aria-label="5 stars">★★★★★</span>',
                '<a href="' . esc_url( 'https://wordpress.org/plugins/shipping-manager/#reviews' ) . '" target="_blank" rel="noopener noreferrer">',
                '</a>'
            );
            ?>
        </p>
    </div>
</div>
