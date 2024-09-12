=== Force Sells For WooCommerce ===
Contributors: Jamsheed KM
Donate link: https://github.com/kmjamsheed0/
Tags: WooCommerce Force Sells, Smart Bundles, Product Add-ons, Automatic Add to Cart, Product Grouping
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 5.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add optional or mandatory add-ons to the cart with main items. Create smart bundles that automatically link and sell additional products effortlessly.

== Description ==
**Force Sells For WooCommerce** allows you to add optional or mandatory add-on products to the cart whenever a main item is added. 

- Automatically link and sell additional products with ease.
- When a main item is added to the cart, its associated linked products are also added.
- Mandatory products are synchronized with the main item’s quantity. Optional products can be removed from the cart without affecting the main item.
- The quantity of mandatory items is always synced with the main item (e.g., if one main item is added, one mandatory item will also be in the cart).
- Flexible display settings enable you to choose where the force-sell products should appear (before or after the "Add to Cart" button).
- Customize the layout of force-sell items as list view or grid view, and choose whether to show product images.

The plugin is highly developer-friendly, allowing you to easily add more functionalities with our hooks.

= Key Features =

**1. Display Styles:**
- Choose to display force-sell items in a list or grid view.
- Option to show or hide force-sell product images.

**2. Additional Display Positions:**
- Display force-sell products before or after the "Add to Cart" button.

**3. Advanced Display Rules:**
- Add mandatory or optional add-on products to a main product.
- Sync the quantity of mandatory products with the main item.
- Allow optional products to be removed from the cart without affecting the main item.

**4. Developer-Friendly:**
- Add more functionalities with our hooks, making it easy for developers to extend the plugin.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/jkm-force-sells` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Configure the settings under Products -> Force Sell Settings.

== Frequently Asked Questions ==

= How do I configure the display settings? =
You can configure the display settings by navigating to Products -> Force Sell Settings. Here, you can choose the display style, position, and image options.

= How do I add optional and mandatory add-ons to a product? =
To add optional and mandatory add-ons, go to the WooCommerce single product edit page. Under the Linked Products section, you'll find two additional fields for managing optional and mandatory add-ons, respectively.

= What happens when I add mandatory add-ons to a product? =
Mandatory add-ons will always sync with the main product’s quantity, ensuring they are added to the cart whenever the main product is purchased.

= Can I add both optional and mandatory add-ons to a product? =
Yes, the plugin allows you to add both optional and mandatory add-ons to a main product. Mandatory add-ons will always sync with the main product’s quantity.

= Is this plugin developer-friendly? =
Yes, the plugin is highly developer-friendly, providing hooks and filters to extend its functionality.

== Changelog ==

= 1.0.0 =
* Initial release
* Force-sell functionality to automatically add products to the cart.
* Display customization options for force-sell products.
* Developer hooks for extended customization and integration.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
