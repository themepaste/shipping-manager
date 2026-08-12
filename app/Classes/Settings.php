<?php
/**
 * Settings Page Class
 *
 * Handles the settings page registration and assets for the Shipping Manager plugin.
 *
 * @package ThemePaste\ShippingManager
 */

namespace ThemePaste\ShippingManager\Classes;

defined( 'ABSPATH' ) || exit;

use ThemePaste\ShippingManager\Helpers\Utility;
use ThemePaste\ShippingManager\Traits\Asset;
use ThemePaste\ShippingManager\Traits\Hook;

/**
 * Class Settings
 *
 * Register settings page, enqueue assets, and add settings link in plugin list.
 */
class Settings {

    use Hook;
    use Asset;

    /**
     * Settings Page Slug
     */
    const SETTING_PAGE_ID = 'shipping-manager';

    /**
     * Capability required to view and save the plugin settings.
     *
     * Must match the capability the menu page is registered with, otherwise
     * shop managers can open the page but get "Unauthorized user" on save.
     */
    const CAPABILITY = 'manage_woocommerce';

    public $setting_page_url;
    public $woocommerce_shipping_page_url;

    /**
     * URL of the plugin's section under the WooCommerce shipping tab.
     *
     * This is the plugin's landing screen — the setup guide.
     *
     * @var string
     */
    public $method_settings_url;

    private $localize_data = [];

    /**
     * Initialize the plugin settings page and hook into WordPress actions/filters.
     *
     * @return void
     */
    public function init() {

        $this->setting_page_url = add_query_arg(
            [
                'page' => self::SETTING_PAGE_ID,
            ],
            admin_url( 'admin.php' )
        );

        $this->woocommerce_shipping_page_url = add_query_arg(
            [
                'page' => 'wc-settings',
                'tab'  => 'shipping',
            ],
            admin_url( 'admin.php' )
        );

        $this->method_settings_url = add_query_arg(
            [
                'section' => self::SETTING_PAGE_ID,
            ],
            $this->woocommerce_shipping_page_url
        );

        $this->action( 'admin_head', [$this, 'remove_save_button'] );
        $this->action( 'admin_menu', [$this, 'shipping_manager_setting_page'], 51 );
        $this->action( 'admin_init', [$this, 'handle_settings_submit'] );
        $this->action( 'admin_init', [$this, 'redirect_to_default_tab'] );
        $this->action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_css'] );
        $this->action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'] );
        $this->action( 'admin_enqueue_scripts', [$this, 'plugins_page_assets'] );
        $this->filter( 'plugin_action_links_' . TPSM_PLUGIN_BASENAME, [$this, 'settings_link'] );
    }

    public function remove_save_button() {
        $screen = get_current_screen();

        if ( !$screen || 'woocommerce_page_wc-settings' !== $screen->id ) {
            return;
        }

        $section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen check.

        if ( self::SETTING_PAGE_ID === $section ) {
            echo '<style>
                .woocommerce-save-button { display: none !important; }
            </style>';
        }
    }

    /**
     * Add action links on the plugins page.
     *
     * Everything is configured per shipping zone in WooCommerce's own shipping
     * settings, so the only link worth surfacing is the one that takes the
     * merchant there. A "Settings" link is added only if something (the Pro
     * add-on) has actually registered a settings tab.
     *
     * @param array $links Existing plugin action links.
     * @return array Modified plugin action links.
     */
    public function settings_link( $links ) {
        // "Settings" goes to the plugin's own section under the WooCommerce
        // shipping tab — the setup guide, which is where a freshly installed
        // site should land. When an add-on registers its own settings tabs,
        // that richer screen takes over the link instead.
        $settings_url = $this->has_settings_tabs()
            ? $this->setting_page_url
            : $this->method_settings_url;

        // Named keys rather than array_unshift: WordPress wraps each action in
        // <span class="{key}">, so this yields meaningful hooks instead of "0".
        $tpsm_links = [
            'tpsm-settings' => sprintf(
                '<a class="tpsm-plugin-action tpsm-plugin-action-primary" href="%1$s">%2$s</a>',
                esc_url( $settings_url ),
                esc_html__( 'Settings', 'shipping-manager' )
            ),
            'tpsm-zones'    => sprintf(
                '<a class="tpsm-plugin-action" href="%1$s">%2$s</a>',
                esc_url( $this->woocommerce_shipping_page_url ),
                esc_html__( 'Setup Method to Zones', 'shipping-manager' )
            ),
        ];

        return array_merge( $tpsm_links, $links );
    }

    /**
     * Style this plugin's action links on the Plugins screen.
     *
     * Keeps them visually distinct from WordPress's own blue actions, using the
     * plugin's brand colour.
     *
     * @param string $screen Current admin screen ID.
     * @return void
     */
    public function plugins_page_assets( $screen ) {
        if ( 'plugins.php' !== $screen ) {
            return;
        }

        $this->enqueue_style(
            'tpsm-plugins-page',
            TPSM_ASSETS_URL . '/admin/css/plugins-page.css'
        );
    }

    /**
     * Whether any settings tab is registered.
     *
     * The free plugin registers none — the shipping method's own instance
     * settings cover everything — so the settings screen and its menu entry
     * only exist when an add-on hooks `tpsm_settings_options`.
     *
     * @return bool
     */
    private function has_settings_tabs() {
        return (bool) tpsm_settings_options();
    }

    /**
     * Enqueue admin CSS styles on the settings page.
     *
     * @param string $screen Current admin screen ID.
     * @return void
     */
    public function admin_enqueue_css( $screen ) {
        // Setup guide on WooCommerce -> Settings -> Shipping -> Shipping Manager.
        if ( $this->is_method_settings_section( $screen ) ) {
            $this->enqueue_style(
                'tpsm-method-guide',
                TPSM_ASSETS_URL . '/admin/css/method-guide.css'
            );
        }

        if ( 'woocommerce_page_' . self::SETTING_PAGE_ID === $screen ) {
            $this->enqueue_style(
                'tpsm-settings',
                TPSM_ASSETS_URL . '/admin/css/settings.css'
            );

            $this->enqueue_style(
                'tpsm-fields',
                TPSM_ASSETS_URL . '/admin/css/fields.css'
            );
        }
    }

    /**
     * Enqueue admin JavaScript files on the settings page.
     *
     * @param string $screen Current admin screen ID.
     * @return void
     */
    public function admin_enqueue_scripts( $screen ) {
        if ( 'woocommerce_page_' . self::SETTING_PAGE_ID === $screen ) {
            $this->enqueue_script(
                'tpsm-settings',
                TPSM_ASSETS_URL . '/admin/js/settings.js'
            );
        }

        // The React rules builder is only rendered inside a shipping method's
        // instance settings, so keep the bundle (and the WooCommerce lookups it
        // needs) off every other admin screen.
        if ( !$this->is_shipping_settings_screen( $screen ) ) {
            return;
        }

        $this->enqueue_script(
            'tpsm-settings-react',
            TPSM_ASSETS_URL . '/admin/dist/bundle.js',
            [],
            null,
            ['in_footer' => false]
        );

        $this->localize_data['woocommerce_data'] = [
            'currency'        => get_woocommerce_currency(), // e.g., 'USD'
            'currency_symbol' => get_woocommerce_currency_symbol(), // e.g., '$'
            'weight_unit'     => get_option( 'woocommerce_weight_unit' ), // e.g., 'kg', 'g', 'lbs'
        ];
        $this->localize_data['shipping_rules_select'] = tpsm_get_conditions_data();
        $this->localize_data['condition_groups'] = tpsm_get_condition_groups();
        $this->localize_data['condition_help'] = $this->get_condition_help();
        $this->localize_data['operators'] = tpsm_get_filter_operators();
        $this->localize_data['wc_shipping_classess'] = $this->get_all_wc_classes();
        $this->localize_data['product_categories'] = tpsm_get_product_categories();
        $this->localize_data['product_tags'] = tpsm_get_product_tags();
        // Reuse WooCommerce's own product search rather than shipping another
        // endpoint; it already handles variations, permissions and large catalogs.
        $this->localize_data['product_search'] = [
            'url'   => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'search-products' ),
        ];
        $this->localize_data['assets_url'] = TPSM_ASSETS_URL;
        $this->localize_data['i18n'] = $this->get_builder_strings();

        $this->localize_script( 'tpsm-settings-react', 'TPSM_ADMIN', $this->localize_data );
    }

    /**
     * Whether the current screen is the WooCommerce shipping settings tab.
     *
     * @param string $screen Current admin screen ID.
     * @return bool
     */
    /**
     * Whether the current screen is the plugin's own section under the
     * WooCommerce shipping tab (the setup guide).
     *
     * @param string $screen Current admin screen ID.
     * @return bool
     */
    private function is_method_settings_section( $screen ) {
        if ( 'woocommerce_page_wc-settings' !== $screen ) {
            return false;
        }

        $section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen check.

        return self::SETTING_PAGE_ID === $section;
    }

    private function is_shipping_settings_screen( $screen ) {
        if ( 'woocommerce_page_wc-settings' !== $screen ) {
            return false;
        }

        $tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen check.

        return 'shipping' === $tab;
    }

    /**
     * Short help text per condition, shown in the rules builder.
     *
     * @return array
     */
    private function get_condition_help() {
        $help = [];

        foreach ( array_keys( tpsm_get_conditions_data() ) as $slug ) {
            $help[$slug] = tpsm_get_condition_description( $slug );
        }

        return $help;
    }

    /**
     * Translatable strings for the React rules builder.
     *
     * @return array
     */
    private function get_builder_strings() {
        return [
            'addRule'         => __( 'Add Rule', 'shipping-manager' ),
            'duplicate'       => __( 'Duplicate', 'shipping-manager' ),
            'deleteSelected'  => __( 'Delete Selected', 'shipping-manager' ),
            'enabled'         => __( 'Enabled', 'shipping-manager' ),
            'disabled'        => __( 'Disabled', 'shipping-manager' ),
            'label'           => __( 'Label', 'shipping-manager' ),
            'labelPlaceholder' => __( 'Optional name for this rule', 'shipping-manager' ),
            'condition'       => __( 'Condition', 'shipping-manager' ),
            'cost'            => __( 'Cost', 'shipping-manager' ),
            'actions'         => __( 'Actions', 'shipping-manager' ),
            'noRules'         => __( 'No shipping rules yet. Add your first rule to start charging for delivery.', 'shipping-manager' ),
            'min'             => __( 'Min', 'shipping-manager' ),
            'max'             => __( 'Max', 'shipping-manager' ),
            'value'           => __( 'Value', 'shipping-manager' ),
            'selectClasses'   => __( 'Select shipping classes…', 'shipping-manager' ),
            'selectCategories' => __( 'Select product categories…', 'shipping-manager' ),
            'selectTags'      => __( 'Select product tags…', 'shipping-manager' ),
            'searchProducts'  => __( 'Search products…', 'shipping-manager' ),
            'typeToSearch'    => __( 'Type to search products', 'shipping-manager' ),
            'noResults'       => __( 'No products found', 'shipping-manager' ),
            'postcodes'       => __( 'e.g. 1000...2000, SW1*, 90210', 'shipping-manager' ),
            'states'          => __( 'e.g. CA, NY, TX', 'shipping-manager' ),
            'coupons'         => __( 'e.g. FREESHIP, SUMMER25', 'shipping-manager' ),
            'importExport'    => __( 'Import / Export', 'shipping-manager' ),
            'copy'            => __( 'Copy', 'shipping-manager' ),
            'copied'          => __( 'Copied to clipboard', 'shipping-manager' ),
            'paste'           => __( 'Paste', 'shipping-manager' ),
            'download'        => __( 'Download', 'shipping-manager' ),
            'importFile'      => __( 'Import file', 'shipping-manager' ),
            'replaceAll'      => __( 'Replace all rules', 'shipping-manager' ),
            'appendRules'     => __( 'Append to existing', 'shipping-manager' ),
            'invalidJson'     => __( 'That does not look like valid Shipping Manager rule data.', 'shipping-manager' ),
            'imported'        => __( 'Imported %d rules.', 'shipping-manager' ),
            'ruleCount'       => __( '%d rules', 'shipping-manager' ),
            'clipboardFailed' => __( 'Could not reach the clipboard. Copy the text manually.', 'shipping-manager' ),
            'summary'         => __( 'Rules summary', 'shipping-manager' ),
            'totalNote'       => __( 'Costs from every matching rule are added together.', 'shipping-manager' ),
        ];
    }

    private function get_all_wc_classes() {
        if ( !function_exists( 'WC' ) || !WC()->shipping() ) {
            return [];
        }

        $shipping_classes = WC()->shipping()->get_shipping_classes();
        $new_shipping_class = [];
        foreach ( $shipping_classes as $shipping_class ) {
            $new_shipping_class[] = [
                'value' => $shipping_class->slug,
                'label' => $shipping_class->name,
            ];
        }
        return $new_shipping_class;
    }

    /**
     * Register the top-level menu page for the Shipping Manager settings.
     *
     * @return void
     */
    public function shipping_manager_setting_page() {
        if ( !$this->has_settings_tabs() ) {
            return;
        }

        add_submenu_page(
            'woocommerce', // Parent slug (WooCommerce)
            esc_html__( 'Shipping Manager', 'shipping-manager' ), // Page title
            esc_html__( 'Shipping Manager', 'shipping-manager' ), // Menu title
            self::CAPABILITY, // Capability
            self::SETTING_PAGE_ID, // Menu slug
            [$this, 'settings_page_layout'] // Callback
        );
    }

    /**
     * Send the settings screen to a default tab.
     *
     * Runs on `admin_init` rather than inside the page callback: by the time the
     * page renders, the admin header has already been sent and wp_safe_redirect()
     * would emit a "headers already sent" warning instead of redirecting.
     *
     * @return void
     */
    public function redirect_to_default_tab() {
        if ( !$this->is_settings_page_request() || isset( $_GET['tpsm-setting'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen check.
            return;
        }

        // Land on whichever tab is registered first, rather than a hardcoded
        // slug that may not exist.
        $tabs = tpsm_settings_options();

        wp_safe_redirect(
            add_query_arg(
                [
                    'tpsm-setting' => key( $tabs ),
                ],
                $this->setting_page_url
            )
        );
        exit;
    }

    /**
     * Handle a settings form submission.
     *
     * Also runs on `admin_init` so that saving happens before any output and the
     * post-save redirect actually works.
     *
     * @return void
     */
    public function handle_settings_submit() {
        if ( !$this->is_settings_page_request() ) {
            return;
        }

        $screen_slug = Utility::get_screen( 'tpsm-setting' );

        if ( !$screen_slug ) {
            return;
        }

        $fields = tpsm_get_settings_fields_for_screen( $screen_slug );

        if ( empty( $fields ) ) {
            return;
        }

        $submit_button = 'tpsm-' . $screen_slug . '_submit';

        if ( !isset( $_POST[$submit_button] ) ) {
            return;
        }

        if ( !isset( $_POST['tpsm-nonce_name'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tpsm-nonce_name'] ) ), 'tpsm-nonce_action' ) ) {
            wp_die( esc_html__( 'Nonce verification failed.', 'shipping-manager' ) );
        }

        if ( !current_user_can( self::CAPABILITY ) ) {
            wp_die( esc_html__( 'Unauthorized user', 'shipping-manager' ) );
        }

        update_option(
            'tpsm-' . $screen_slug . '_settings',
            tpsm_sanitize_settings_fields( $fields, $screen_slug )
        );

        // The free shipping screen stores the progress-bar styling in its own option.
        if ( isset( $fields['free-shipping-bar']['child-fields'] ) ) {
            update_option(
                'tpsm-free-shipping-bar' . $screen_slug . '_settings',
                tpsm_sanitize_settings_fields( $fields['free-shipping-bar']['child-fields'], $screen_slug )
            );
        }

        wp_safe_redirect(
            add_query_arg(
                [
                    'page'         => self::SETTING_PAGE_ID,
                    'tpsm-setting' => $screen_slug,
                    'tpsm-saved'   => 1,
                ],
                admin_url( 'admin.php' )
            )
        );
        exit;
    }

    /**
     * Whether the current request targets this plugin's settings page.
     *
     * @return bool
     */
    private function is_settings_page_request() {
        $page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen check.

        return self::SETTING_PAGE_ID === $page
            && $this->has_settings_tabs()
            && current_user_can( self::CAPABILITY );
    }

    /**
     * Load the main settings page layout.
     *
     * @return void
     */
    public function settings_page_layout() {
        if ( !current_user_can( self::CAPABILITY ) ) {
            wp_die( esc_html__( 'Unauthorized user', 'shipping-manager' ) );
        }

        echo Utility::get_template( 'settings/layout.php' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Template output is escaped at the point of use.
    }

    /**
     * Placeholder for Pro settings layout.
     *
     * @return void
     */
    public function settings_page_layout_pro() {
        esc_html_e( 'Pro Features Loading...', 'shipping-manager' );
    }
}
