=== GeoDirectory Pricing Manager ===
Contributors: stiofansisland, paoltaia, ayecode
Donate link: https://wpgeodirectory.com
Tags: geodir pricing, package, price package, pricing, pricing manager
Requires at least: 4.9
Tested up to: 5.5
Requires PHP: 5.6
Stable tag: 2.6.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

GeoDirectory Pricing Manager is a powerful price manager that allows you to monetize your directory quickly and easily via a pay per listing business model.

== Description ==

Pricing Manager is a powerful price manager that allows you to monetize your directory quickly and easily via a pay per listing business model.

The interface allows efficient management of package features, such as images, website links and telephone numbers. You can set an expiry date for listings and edit the fields that are shown next to each price package.

== Changelog =

= 2.6.0.0 =
* Change page title on edit package - CHANGED
* Changes for AyeCode UI compatibility - CHANGED
* Sometime it sends pre-expiry notifications for auto-recurring listings - FIXED

= 2.5.1.2 =
* Saves wrong trial interval value during package to WooCommerce product synchronization - FIXED

= 2.5.1.1 =
* Allow to setup separate add listing page for each CPT - CHANGED

= 2.5.1.0 =
* Cannot unset package icon & remove package description - FIXED
* Downgrade package does not removes images when post_images excluded for package - FIXED
* Clicking on the "Create invoice for this listing" button shows "Renew Listing" even for non-recurring or initial invoices - FIXED
* Link to listing from invoice pages - ADDED
* Add an invoice note with a link to the associated listing - ADDED
* Skip invoices for free packages - ADDED
* Show package in admin posts lists - ADDED
* Filter to change the WooCommerce cart new listing checkout redirect url - ADDED
* Change html of package listing on add listing form - CHANGED
* Frontend Analytics show/hide analytics based on package settings - ADDED
* Upgrade link in email template shows wrong url - FIXED
* WooCommerce now supports recurring packages - ADDED

= 2.5.0.13 =
* Delete subsite removes data from main site on multisite network - FIXED
* Disable HTML Editor should not allow html tags in description - FIXED
* Import listings expire_date should support d/m/Y date format - CHANGED
* Displays warning on add listing page when no packages available - FIXED
* Guest user invoice requires email to checkout after add listing - FIXED
* Woocommerce subscriptions conflicts with listing payments - FIXED
* Listing shows different package in frontend & backend after upgrade - FIXED
* Add more pre expiry email options(10, 15 & 30 days) - ADDED
* Send an invoice after a claim is made - ADDED

= 2.5.0.12 =
* Allow editing of listings in inactive packages without having to change the package or activating the package first.

= 2.5.0.11 =
* Listing with active subscription should not be allowed to renew again - FIXED
* Don't show info box when auto redirecting to checkout - CHANGED
* Custom fields created during dummy data import are not visible on frontend - FIXED

= 2.5.0.10 =
* Allow renew & upgrade links in email notifications - ADDED
* Switching package should not show remove revision message - FIXED
* Image, category, tag limit not working on downgrade package - FIXED
* Show expired notification on single listing page - ADDED

= 2.5.0.9 =
* Skip checkout for free listing claim - ADDED

= 2.5.0.8 =
* GD BuddyPress member area not showing the expired listings - FIXED

= 2.5.0.7 =
* Add listing package id to body class on detail listing page - ADDED

= 2.5.0.6 =
* Category restrictions can make dummy data not have any terms - FIXED
* Changing package sometimes does not save last info entered - FIXED
* Dummy data with no pricing package can break add listing validation - FIXED
* Paid listings will now auto redirect to checkout - CHANGED

= 2.5.0.5 =
* Not submitting the claim listing form if claim package selected - FIXED
* Post updates not adding new images if price is free - FIXED
* Franchise plugin integration - ADDED

= 2.5.0.4 =
* Checkout button in submit listing message should link to checkout page - CHANGED
* Package html editor affects all textareas - FIXED
* Validate package id during post submit - FIXED
* Changes for claim listings addon - CHANGED
* Not able to set featured listing from package - FIXED
* Preview link should open preview in new window - CHANGED

= 2.5.0.3-beta =
* Old version package table fields may cause issue in update package - FIXED

= 2.5.0.2-beta =
* It shows incorrect expire date in backend listings page - FIXED
* Package description limit not working - FIXED

= 2.5.0.1-beta =
Initial beta release - INFO

== Upgrade Notice ==

= 2.5.0.1-beta =
* 2.5.0.1-beta is a major update to work with GDv2 ONLY. Make a full site backup, update your theme and extensions before upgrading.
