=== Shipping Manager – WooCommerce Table Rate, Weight Based & Advanced Shipping Rules ===
Contributors: themepaste, habibnote
Tags: woocommerce shipping, table rate shipping, weight based shipping, shipping rates, shipping zones
Requires at least: 5.8
Tested up to: 6.8
WC requires at least: 6.6
WC tested up to: 11.0
Requires PHP: 7.4
Stable tag: 1.2.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Table rate shipping for WooCommerce. Build shipping rules by cart total, weight, quantity and shipping class. No coding required.

== Description ==

**Shipping Manager is a table rate shipping plugin for WooCommerce.** It lets you charge exactly what shipping costs you, by building rules based on cart total, cart subtotal, item quantity, total weight, weight unit, and WooCommerce shipping classes — all from one screen, with no code.

WooCommerce ships with Flat Rate and Free Shipping. Those work until your pricing has an *"it depends"* in it: heavier orders cost more, big orders ship free, fragile items carry a surcharge. Shipping Manager adds a shipping method you drop into any WooCommerce shipping zone, and inside it you build a table of rules. At checkout, every rule that matches the cart is added up into a single, accurate shipping rate.

= Why store owners choose Shipping Manager =

* **Rules, not guesswork.** Stop rounding shipping up "to be safe" and losing conversions, or rounding down and eating the cost.
* **One method, unlimited rules.** Stack as many conditions as you need. Matching costs add together.
* **Per zone.** Different countries and regions can have completely different pricing.
* **Nothing to learn.** It lives inside WooCommerce's own shipping settings, where you already work.
* **No coding.** No snippets, no `functions.php`, no filters.

= How it works =

Shipping Manager plugs into WooCommerce's native shipping system rather than replacing it:

`Shipping zone → Shipping Manager method → your rules → rate shown at checkout`

1. **Add the method to a shipping zone.** Go to *WooCommerce → Settings → Shipping*, open a zone, choose *Add shipping method*, and pick **Shipping Manager**.
2. **Build your rules.** Click the method to open its settings. Each row in the rules table is one condition and one cost.
3. **Done.** WooCommerce shows the calculated rate at cart and checkout.

Because it is a normal WooCommerce shipping method, everything you already know still applies: shipping zones, shipping classes, tax settings, and the cart/checkout blocks all work as usual.

= Shipping rules you can build =

Every rule row picks one condition and a cost. **Costs from all matching rules are added together**, which is what makes stacked pricing possible.

Rules are grouped by what they inspect. Every row picks one condition and a cost.

**General**

* **Flat Rate** — always applies. Use it for a base handling charge.
* **Per Item** — multiplies the cost by the number of items in the cart.

**Cart**

* **Quantity** — compares the total number of items in the cart using *equals, not equal, greater than, less than, greater than or equal, less than or equal*.
* **Line Items** — compares how many *different* products are in the cart, with the same operators. Useful for pick-and-pack fees.
* **Subtotal** — applies when the cart subtotal falls inside a minimum/maximum range.
* **Total** — applies when the cart total falls inside a minimum/maximum range.
* **Cart Volume** — applies when the combined volume (length x width x height x quantity) falls in a range. This is dimensional / volumetric pricing, which WooCommerce cannot do at all.

**Product**

* **Per Weight Unit** — multiplies the cost by the cart's total weight. Enter the price of one kg (or lb) and it scales automatically.
* **Total Weight** — applies when the cart's total weight falls inside a minimum/maximum range.
* **Shipping Class** — applies when the cart contains any of the WooCommerce shipping classes you select.
* **Product Category** — applies when the cart contains a product from any category you select. No shipping class setup required.
* **Product Tag** — the same, keyed on product tags.
* **Specific Products** — target individual products, or a single variation, by searching for them. Uses WooCommerce's own product search, so it works on catalogs of any size.

**Destination**

* **Postcode** — applies when the delivery postcode matches. Accepts exact codes, `*` wildcards (`SW1*`) and numeric ranges (`1000...2000`) — the same shorthand WooCommerce shipping zones use, so you can price by postcode without creating a zone per area.
* **State / County** — applies when the delivery state or county code matches, e.g. `CA, NY, TX`.

**Order**

* **Coupon Applied** — applies when any of the listed coupon codes is on the order. Lets a coupon change the shipping price, which core WooCommerce coupons cannot do.

Leave a minimum or maximum **empty** to mean "no limit" on that side. A rule with a minimum of 100 and an empty maximum applies to every cart of 100 and above.

= Recipes: common setups =

**Weight-based shipping**
Add one *Per Weight Unit* rule at your per-kg rate. Optionally add a *Flat Rate* row as a base handling fee. A 3 kg order at 2.00/kg plus a 5.00 base = 11.00.

**Tiered table rate by order value**
Add three *Subtotal* rules: 0–49.99 → 9.95, 50–99.99 → 4.95, 100 and above (leave max empty) → 0. Bigger carts ship cheaper, automatically.

**Free shipping over a threshold**
Add a *Subtotal* rule with a minimum of 0 and a maximum just under your threshold, carrying your normal cost. Carts above the threshold match no rule, so no cost is added.

**Surcharge for bulky or fragile goods**
Create a WooCommerce shipping class (for example "Fragile"), assign it to those products, then add a *Shipping Class* rule selecting it with your surcharge. It only applies when one of those products is in the cart.

**Per-item packing fee**
Add a *Quantity* rule using *greater than or equal* with your break point, and the extra packing cost.

**Remote-area surcharge by postcode**
Add a *Postcode* rule listing the ranges you want to surcharge, for example `1000...1999, HS*, ZE*`, with the extra cost. It applies on top of your normal rules.

**Category-based handling fee**
Add a *Product Category* rule selecting, say, "Furniture", with its handling cost. No shipping classes to set up.

**Charge more for one specific product**
Add a *Specific Products* rule, search for the product (or a single variation), and set its surcharge.

**Let a coupon change the shipping price**
Add a *Coupon Applied* rule listing codes such as `FREESHIP`. Core WooCommerce coupons can only grant free shipping outright; this adjusts the calculated rate instead.

**Dimensional / volumetric pricing**
Fill in length, width and height on your products, then add *Cart Volume* rules in bands — 0–5000 at 6.95, 5000 and above at 12.95.

**Regional surcharge by state**
Add a *State / County* rule listing the codes you surcharge, e.g. `AK, HI`.

**Different pricing per country**
Add the Shipping Manager method to each shipping zone separately. Each one keeps its own independent rules table.

= Free features =

* Table rate shipping for WooCommerce
* Weight-based shipping — per weight unit and total weight ranges
* Cart total and cart subtotal range pricing
* **16 rule conditions** covering the cart, its products, the destination and the order
* Cart quantity and line-item rules with six comparison operators
* Full WooCommerce shipping class support (multi-select)
* Product category and product tag rules (multi-select)
* Specific product and variation targeting, with live product search
* Postcode rules with wildcard and range matching
* State / county rules
* Coupon-aware shipping rules
* Volumetric (dimensional) pricing on total cart volume
* Per-item and per-weight-unit multipliers
* Flat rate / base handling fee
* Unlimited rules per method, each with its own label and on/off switch
* Duplicate any rule in place, or duplicate and delete in bulk
* Unlimited methods — add it to as many shipping zones as you like
* Custom method name and description shown to customers at checkout
* Per-method tax status (Taxable or None)
* Import/export rules: copy to clipboard, paste, download as a .json file, or import one — replacing or appending
* Built-in setup guide that tells you what is configured and what is missing
* Works with WooCommerce High-Performance Order Storage (HPOS)
* Works with the WooCommerce Cart and Checkout blocks
* Translation ready

= Pro features (upcoming) =

* Distance-based shipping calculation
* Role-based shipping rates
* Advanced class-based shipping
* Premium delivery day and time slot selection
* Multiple rulesets per method
* Advanced processing fees
* Coupon-aware free shipping thresholds
* Delivery date selection and customizable delivery slips
* Real-time shipment tracking
* Shipping rule analytics
* Multi-vendor marketplace support

= Compatibility =

* **WooCommerce** 6.6 and above, tested up to 11.0
* **WordPress** 5.8 and above
* **PHP** 7.4 and above
* **HPOS** (High-Performance Order Storage) — declared compatible
* **Cart & Checkout blocks** — declared compatible
* **Shipping zones and shipping classes** — uses WooCommerce's own, no duplicates to maintain
* Works with any theme, because rates render through WooCommerce's standard cart and checkout templates

= Privacy and external services =

Shipping Manager does **not** track visitors and does not send any customer or order data anywhere.

During the optional setup wizard shown after activation you may choose *"Allow & Continue"*. Only if you actively opt in, the plugin sends your **WordPress account name, your account email address, and your site URL** once to ThemePaste, so we can send you product and security update notices. Choosing *"Not now"* sends nothing. No data is transmitted at any other time.

* Service: ThemePaste — https://themepaste.com
* Privacy policy: https://themepaste.com/privacy-policy
* Terms: https://themepaste.com/terms-conditions

= Documentation and support =

* [Documentation](https://themepaste.com/documentation/shipping-manager/)
* [Support](https://themepaste.com/contact-us)

Follow ThemePaste on [Facebook](https://www.facebook.com/themepaste), [LinkedIn](https://www.linkedin.com/company/themepaste), [Instagram](https://www.instagram.com/themepasteuk) and [Pinterest](https://uk.pinterest.com/themepaste/).

== Installation ==

= Requirements =

* WordPress 5.8 or newer
* WooCommerce 6.6 or newer, installed and active
* PHP 7.4 or newer

= Install from the WordPress dashboard =

1. Go to **Plugins → Add New**.
2. Search for **Shipping Manager**.
3. Click **Install Now**, then **Activate**.

= Install from a ZIP file =

1. Download the ZIP from WordPress.org.
2. Go to **Plugins → Add New → Upload Plugin**.
3. Choose the ZIP, click **Install Now**, then **Activate**.

= After activating: set up your first rate =

1. Go to **WooCommerce → Settings → Shipping → Shipping Manager**. This screen shows a setup guide and tells you whether the method is live yet.
2. Click **Setup Shipping Methods to Zones**, or go to the **Shipping zones** tab.
3. Open the zone you want to price (or click **Add zone** and choose its regions).
4. Click **Add shipping method**, select **Shipping Manager**, and continue.
5. Click the new method to open its settings.
6. Set the **Method Name** customers see at checkout, and the **Tax status**.
7. In the **Shipping Rules** table, click **Add New Row**, pick a condition, and enter its cost. Add as many rows as you need.
8. **Save changes**, then add a product to your cart and check the rate at checkout.

== Frequently Asked Questions ==

= What is table rate shipping? =

Table rate shipping means charging different amounts depending on what is in the cart, instead of one fixed price. Shipping Manager builds that table from rules: cart total, subtotal, quantity, weight, and shipping class.

= Do I need to know how to code? =

No. Everything is configured from the WooCommerce shipping settings screen. There are no snippets, filters, or files to edit.

= Where do I configure the plugin? =

Everything lives in **WooCommerce → Settings → Shipping**. The **Shipping Manager** tab there shows a setup guide, and the actual rules are inside each shipping method you add to a zone. The quickest route is the **Settings** link under Shipping Manager on the Plugins screen, which opens that guide directly. The plugin does not add its own separate top-level admin page.

= How are multiple rules combined? =

Every rule that matches the cart contributes its cost, and those costs are **added together** into one rate. If you want rules to be mutually exclusive, give them non-overlapping ranges.

= Can I have different shipping prices per country or region? =

Yes. Add the Shipping Manager method to each WooCommerce shipping zone you want to price. Each method keeps its own separate rules table, so your domestic and international pricing are fully independent.

= Can I offer free shipping over a certain amount? =

Yes. Add a *Subtotal* rule that covers everything below your threshold and carries your normal cost. Carts above the threshold match no rule, so nothing is charged. You can also use WooCommerce's built-in Free Shipping method alongside Shipping Manager in the same zone.

= Why is my shipping rate not showing at checkout? =

Work through these in order:

1. Is the method added to a shipping zone whose regions include the customer's address?
2. Does at least one rule actually match the cart? A rate is only shown when the calculated cost is above zero.
3. For weight rules, do your products have weights set?
4. For shipping class rules, are the classes assigned to the products?
5. Is the customer's address inside the zone you configured?

The setup guide at *WooCommerce → Settings → Shipping → Shipping Manager* reports which of these are in place.

= Why is my weight rule being ignored? =

Weight rules read each product's **Weight** field under *Product data → Shipping*. Products with no weight are counted as zero, so a cart of weightless products will not reach any minimum you set.

= Can I charge by postcode without making a zone for each area? =

Yes. Add a *Postcode* rule and list the codes, wildcards or ranges you want — `1000...1999, SW1*, 90210`. That is far less work than maintaining a WooCommerce shipping zone per postcode group.

= Can I target one specific product, or one variation? =

Yes. The *Specific Products* condition searches your catalog live and matches on the parent product ID or the individual variation ID, so you can surcharge a single variation without touching the rest.

= Can shipping depend on a coupon? =

Yes. The *Coupon Applied* condition matches any coupon code on the order. Core WooCommerce coupons can only grant free shipping outright; this lets a coupon change the calculated rate.

= Does it support dimensional / volumetric weight? =

Yes, via *Cart Volume*, which sums length x width x height x quantity across the cart and matches it against a range. Products missing any dimension contribute zero.

= Can I turn a rule off without deleting it? =

Yes. Every rule has its own enable/disable switch and an optional label, so you can park seasonal pricing and switch it back on later.

= Can I copy my rules to another zone or another site? =

Yes. Each method's settings include an **Import/Export** field containing its rules as text. Copy that value and paste it into the same field on another method to duplicate the whole setup.

= Does it work with the new Cart and Checkout blocks? =

Yes. Shipping Manager declares compatibility with the WooCommerce Cart and Checkout blocks, and with High-Performance Order Storage (HPOS).

= Does it support taxes? =

Yes. Each Shipping Manager method has its own **Tax status** setting — *Taxable* lets WooCommerce apply your shipping tax rates, *None* leaves the rate untaxed.

= Does it work with product variations? =

Yes. Weight and shipping class are read from the variation that is actually in the cart, falling back to the parent product where the variation does not define them.

= Is it translation ready? =

Yes. All strings use the `shipping-manager` text domain and can be translated on translate.wordpress.org or with any translation plugin.

= Is Shipping Manager free? =

Yes. Everything listed under "Free features" above is included at no cost. A Pro version with distance-based, role-based and delivery-scheduling features is in development.

= Does the plugin collect any data? =

Only if you explicitly opt in during the setup wizard, and then only your WordPress account name, email address and site URL — once. No visitor, customer or order data is ever transmitted. See the "Privacy and external services" section above.

== Screenshots ==

1. The Shipping Manager setup guide under WooCommerce → Settings → Shipping.
2. Adding the Shipping Manager method to a WooCommerce shipping zone.
3. Building shipping rules in the rules table.
4. Weight-based and shipping class rule conditions.
5. Method name, description and tax status settings.
6. The calculated shipping rate shown at checkout.

== Changelog ==

= 1.2.7 =
* [add] Eight new rule conditions WooCommerce does not offer: **Specific Products** (live product/variation search), **Product Category**, **Product Tag**, **Postcode** (exact, `SW1*` wildcards, `1000...2000` ranges), **State / County**, **Coupon Applied**, **Cart Volume** (dimensional pricing) and **Line Items** — plus a **Per Item** multiplier. Sixteen conditions in total.
* [add] Rebuilt Import/Export as a proper panel: copy rules to the clipboard, paste them back, download a .json file or import one, choosing whether to replace or append. The raw JSON text field is gone.
* [add] Every rule now has an optional label and its own enable/disable switch, so you can park a rule without deleting it. Rules saved before this update stay enabled.
* [add] Duplicate a single rule in place, alongside the existing bulk duplicate.
* [fix] Multi-selects no longer show WordPress's blue focus ring inside the plugin's orange one; react-select's inner search input is now neutralised.
* [improvement] Rebuilt the rules builder UI: card-per-rule layout, inline help for the selected condition, clearer grouping of conditions, and a full mobile layout.
* [fix] Condition groups in the rules dropdown are now defined explicitly instead of by slicing the condition list by index, which mis-grouped every condition whenever one was added or removed.
* [add] Restored the **Settings** link on the Plugins screen, styled in the plugin's brand colour so it stands out from WordPress's own row actions. It opens the Shipping Manager setup guide, so a freshly installed site has a one-click route to everything.
* [fix] The setup guide's primary button label was invisible (orange text on the orange button) because a broader link rule out-ranked it; button styling is now specific enough to win.
* [improvement] Redesigned the setup guide: proper spacing below the WooCommerce sub-navigation, inline icons, connected step markers, stat tiles, and a fully responsive layout down to phone width.
* [improvement] Documentation and support links in the admin now point to the plugin's WordPress.org page and support forum.
* [add] WooCommerce -> Settings -> Shipping -> Shipping Manager is now a styled setup guide matching the plugin's own admin theme: it reports whether the method is added to a zone, walks through the three setup steps, documents every rule condition, shows your store's currency/weight unit/shipping classes/tax state, and links straight to each of your configured methods.
* [change] Removed the "Total Dimensions" rule condition. It appeared in the rules dropdown but had no cost calculation behind it, so any rule using it silently added nothing to the rate.
* [change] Removed the standalone WooCommerce -> Shipping Manager admin page. Everything is configured per shipping zone in WooCommerce -> Settings -> Shipping, where each Shipping Manager method already has its own Method Name, Method Description, Tax status and Shipping Rules.
* [change] The shipping method is now always available; the site-wide Disable/Enable toggle is gone. Disable it per zone in WooCommerce's shipping settings instead.
* [security] Added `wp_unslash()` before sanitizing every `$_POST`/`$_GET` value, so values containing quotes are no longer stored escaped.
* [security] Any remaining settings screen saves under the same `manage_woocommerce` capability its menu page is registered with; shop managers could previously open a settings page but were rejected on save.
* [security] Hardened template loading against directory traversal and stopped `extract()` from being able to clobber local variables.
* [security] The shipping calculator AJAX endpoint now rejects product IDs that are not published products.
* [fix] Settings are now saved on `admin_init` instead of during page render, so the post-save redirect works and the form no longer shows pre-save values.
* [fix] Fixed a PHP 8 `TypeError` during checkout when the stored shipping-rule JSON decoded to a non-array.
* [fix] Fixed "array offset on bool" warnings when the plugin options had never been saved.
* [fix] Shipping rules with only a minimum or only a maximum now apply; a blank bound means "unbounded" instead of zero.
* [fix] The `[tpsm-shipping-calculator]` shortcode now returns its markup instead of echoing it, so it renders in place rather than at the top of the page.
* [fix] The shipping rules builder now mounts inside WooCommerce's shipping-method modal, where it previously failed to render at all.
* [fix] Each shipping rule row keeps its own comparison operator; the operator dropdown was shared across all rows and saved values were not restored.
* [fix] Row selection no longer points at the wrong rules after deleting a row.
* [fix] The free shipping progress bar's position/alignment are no longer stored as translated labels, which broke them on non-English stores.
* [fix] The shipping calculator's country dropdown defaults to the shopper's/store's country instead of a hardcoded value.
* [fix] Dismissing the setup notice now sticks instead of reappearing on the next page load.
* [fix] The shipping method's Tax status setting is now applied to the generated rate.
* [improvement] Admin bundle rebuilt in production mode: 1.7 MB down to 311 KB, with no `eval()`.
* [improvement] The admin bundle and WooCommerce lookups now load only on the shipping settings tab, and frontend assets only when the calculator is enabled.
* [improvement] Declared HPOS and Cart/Checkout Blocks compatibility with WooCommerce.
* [improvement] Prefixed the global `get_conditions_data()` / `get_filter_operators()` functions to avoid collisions (old names kept as aliases).
* [improvement] The plugin now degrades with an admin notice instead of fataling when WooCommerce is unavailable.
* [improvement] Rewrote the readme as full documentation: how the rules engine works, every condition explained, worked setup recipes, a troubleshooting FAQ, and a privacy disclosure for the optional opt-in.

= 1.2.6 =
* [add] Added dimension-based shipping support.
* [add] Introduced maximum and minimum dimension settings.
* [update] Improved shipping description options.
* [update] Updated plugin banner design.
* [fix] Minor bug fixes and performance improvements.

= 1.2.5 =
* [fix] Fixed shipping cost filter firing three times per request — now cached to a single call for better performance.
* [fix] Fixed incorrect tax double-counting in cart quantity and total price shipping cost calculations.
* [fix] Fixed `WC_Cart::get_subtotal()` called with invalid argument causing subtotal to be calculated incorrectly.
* [fix] Fixed PHP TypeError on PHP 8+ when no shipping classes exist in the cart (`array_intersect` receiving null).
* [fix] Fixed shipping calculator enable/disable toggle not saving correctly due to unconditional value overwrite.
* [fix] Fixed setup wizard activation redirect using wrong page slug (`tpasg_setup_wizard` → `tpsm_setup_wizard`).
* [security] Replaced `wp_redirect()` with `wp_safe_redirect()` across all admin settings pages.
* [security] Fixed unescaped output in setup wizard notice template.
* [security] Fixed undefined array key `is-plugin-taxable` producing PHP notices.
* [improvement] Removed debug `error_log()` statement left in production code.
* [improvement] Fixed wrong text domain (`your-text-domain`) in filter operator labels — strings are now translatable.
* [improvement] Eliminated unnecessary HTTP request for empty `common.js` — AJAX data now output as inline script only.

= 1.2.4 =
* [feature] Added shipping rules based on total cart dimensions (min/max limits).
* [feature] Introduced minimum and maximum price conditions for shipping cost calculation.
* [improvement] Enhanced flexibility for creating advanced shipping rules.
* [fix] Fixed SPA-related issues affecting dynamic content updates.
* [stability] Improved overall performance and reliability of rule processing.

=1.2.3=
* [fix] Fixed React "Target container is not a DOM element" error.
* [improvement] Improved admin panel script loading performance.
* [improvement] Optimized React app initialization to prevent delayed rendering.
* [stability] Enhanced compatibility with slow-loading environments.

=1.2.2=
* [fix] Fixed notice issue.
* [fix] Fix class based shipping issues.
* [fix] Fix issues.

= 1.2.1 =
* [New] Added dimension-based shipping rules (length × width × height).
* [New] Support for combined quantity and dimension-based shipping conditions.
* [Fixed] Fixed incorrect shipping cost calculation when cart quantities update rapidly.
* [Fixed] Resolved issue where shipping rules were not reapplied on cart refresh.
* [Fixed] Fixed admin issue where saved shipping rules were not loading correctly.
* [JS] Fixed JavaScript errors during dynamic shipping rule updates.
* [JS] Improved performance of cart recalculation and event handling.
* [React] Refactored shipping rules editor components for better state management.
* [React] Fixed unnecessary re-renders causing duplicated rule previews.
* [Improved] More accurate shipping recalculation during AJAX cart updates.
* [Improved] Enhanced validation and error feedback for shipping rule inputs.
* [Compatibility] Tested and verified compatibility with the latest WooCommerce and WordPress versions.


= 1.2.0 =

* [New] Added cart quantity–based shipping cost conditions.
* [New] Shipping rules now support operators: equals, not equals, greater than, less than, greater than or equal, and less than or equal.
* [Improved] Shipping cost calculation now dynamically adjusts based on total cart item quantity.
* [Improved] Enhanced flexibility for creating advanced shipping rules.
* [Compatibility] Fully compatible with the latest WooCommerce version.



= v1.1.8 - v1.1.9 =

* [Compaibility] Fully compatible with the latest WooCommerce version.
* [New] Added blueprint.json file for improved configuration and future scalability.
* [New] Live Preview link to Playground is now enabled.
* [Improved] Updated the Setup Wizard UI and flow for easier initial configuration.
* [Improved] Webhook endpoint links have been updated for better reliability.
* [Improved] API endpoint links revised to match the latest service structure.
* [Fix] Fixed a minor issue affecting overall stability.

= v1.1.7 - 2025.07.28 = 
* [fix] a simple issue.
* [new] blueprint.json file added
* [new] The Live Preview link to Playground is currently enabled.
 

= v1.1.6 - 2025.07.14 = 
* [fix] Fixed notice issue.

= v1.1.4 - v1.1.5 - 2025.07.14 = 
* [new] Setup wizard included
* [new] Notice added
* [fix] Fixed some issues.

= v1.1.3 - 2025.06.30 = 
* [fix] Fix class based shipping issues.

= v1.1.1 - v1.1.2 - 2025.06.24 = 
* [new] Add quick method setup link
* [fix] Fix issues.

= v1.1.0 - 2025.06.23 = 
* [new] Change the settings dashboard.
* [new] Support to use unlimited shipping methods 
* [new] Import & export existing shipping methods by one click 
* [new] One click & sitewide enable/disable shipping methods 
* [new] Custom name and Description for shipping methods
* [new] Flat rate fee 
* [new] Cart total range pricing
* [new] Cart subtotal range pricing
* [new] Shipping class support
* [fix] Fix issues.

= v1.0.6 - 2025.06.16 =  
* [fix] Fix issues.

= v1.0.5 - 2025.05.26 =  
* [fix] Fix issues.

= v1.0.4 - 2025.05.19 =  
* [fix] Fix issues.

= v1.0.1 - v1.0.3 - 2025.05.13 =  
* [fix] Fix issues.

= v1.0.0 - 2025.05.12 =  
* [new] Initial release.

== Upgrade Notice ==
