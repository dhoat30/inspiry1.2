=== Download After Email - Subscribe & Download Form Plugin ===
Contributors: mkscripts
Tags: subscribe, download, form, email, email download, subscribe download, download link, download form, subscribe form, opt-in form, subscribe mailchimp, mailchimp
Requires at least: 4.7
Tested up to: 5.5
Stable tag: 2.0.7
Requires PHP: 5.2.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Download After Email is a free Subscribe & Download plugin that allows you to gain subscribers by offering free downloads.

== Description ==

Download After Email is a free Subscribe & Download plugin that allows you to gain subscribers by offering free downloads.

= Subscribe & Download Form =

Creating a new subscribe & download form is pretty much the same as creating a new post or page, only with some extra options. If you are satisfied with the preview you can save the form and place the generated shortcode on a page, post or widget. It is possible to create multiple subscribe & download forms.

= Enter Email Before Download =

A visitor must enter his email address before the download link will be sent via email. The secure download link can be used once by the user (optional) and the download process is protected against unauthorized use. You can choose whether to send an email notification and to which email address it should be sent.

= Responsive & Highly Customizable =

The Ajax-based opt-in form is fully responsive and adapts to the space around the form and to the screen. There are many options available to customize the layout of the subscribe & download form and to adjust the text for all notifications including the email that is sent with the download link. It is possible to use HTML and images for the email content. "From Email" and "From Name" can be set.

= GDPR Ready =

Download After Email offers all necessary tools to let you comply with the GDPR. You can enable a required checkbox and a optional checkbox. The text of the checkboxes can be adjusted. In the background, data is stored such as IP address, form content, time etc. The use of the download link functions as double opt-in.

= Hooks & Filters =

Hooks and filters are available for developers to make adjustments or implement extensions. For example, you can write your own HTML code for the subscribe & download form field(s) or for the email that is sent to the subscriber. Or you could add new actions after a download link has been sent and after a download link has been used.

== Add Premium Features ==

[Download After Email Plus](https://www.download-after-email.com/add-on) is an extension/add-on that adds the following premium features:

* Create and manage your own form fields with the Drag & Drop Form Builder.
* Export subscriber data to a CSV-file and use it for email marketing, newsletters etc.
* Integration with Mailchimp. Automatically add new subscribers to your Mailchimp audience.

Visit our website for more information: [https://www.download-after-email.com](https://www.download-after-email.com)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly (recommended).
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Adjust the settings on the messages page and the options page to your needs.
1. Start making your first download and test it with the preview option before placing the generated shortcode on a page, post or widget.

== Changelog ==

= 2.0.7 =
* Fixed missing leading zero(s) subscriber meta.
* Fixed deviation in the total number of links on the page: Admin Menu > Downloads.
* Fixed email content not translatable.
* CSS improvement for mobile devices.
* Improved nonce functionality for download links (backwards compatible).
* New hooks added in meta box "Duplicate" + improvement.
* Prevent rename() warning during saving of downloads.
* Added new error message for form submission without a download file present.
* Fixed not able to select dwg files as download file.
* Fixed issues related to multisite (subsite) usage.
* Cleanup update actions in update.php.

= 2.0.6 =
* Fixed bug column Optin Time in subscribers table, sometimes the value of a previous subscriber was displayed.
* Changed the priority (higher value) of the filters wp_mail_from and wp_mail_from_name.

= 2.0.5 =
* ! Changed text domain for translations.
* Layout download form improvements.
* New option file image width.
* Improved function mckp_sanitize_form_content().
* Fixed not displaying hex color input field by prefix colorpicker CSS class.
* Admin layout improvements.
* Fixed not displaying file image icon on edit download page in some cases.
* Remove query string vars from download url (like ?time=12345) if added by another plugin.
* Fixed not displaying optin time and optional checkbox value in admin email in some cases since last update.
* New filters added in download.php to add conditions before running integrations.
* New alignment options for download forms.
* New filter to add attachments to subscriber email.
* New column Optin Time in subscribers table.

= 2.0.4 =
* New function DAE_Subscriber::update_subscriber_meta().
* New subscriber var Subscriber->has_used_links.
* New subscriber meta value optin_time.
* Also run integrations if optional checkbox is empty but optin time isset and no links have been used.

= 2.0.3 =
* Fixed CSS not loaded with multiple shortcodes on blog page.
* New shortcode attribute to disable CSS styling options. For developers, do_shortcode() now uses CSS styling options by default.
* New preview option to display download form without CSS styling options.
* Fixed download issue with large files.

= 2.0.2 =
* New filter to make form fields optional.
* New filter to add custom validation to download forms.
* New option Unlimited Emails to send multiple emails per subscriber per download file.
* New filter to change the email subject.
* Improved update actions.
* Improved download urls. Existing urls remain valid.
* Small HTML and CSS changes download forms.

= 2.0.1 =
* Fixed bug embedded images in email.
* Fixed bug when using do_shortcode(), $GLOBALS['dae_settings'] is no longer needed.
* New placeholder download_file (filename).
* Most placeholders are now also available in email subject.
* Fixed optional checkbox value sometimes not visible in notification email.
* Improved function DAE_Subscriber::update_optional_checkbox().
* Improved CSS for Subscribe & Download Form (small changes).
* Added admin notices in case the dae-uploads folder could not be created or when file_info extension is disabled.

= 2.0 =
* Implemented new class DAE_Subscriber.
* New option to duplicate settings of another download.
* New options to set 'submit error message color' and 'submit success message color'.
* New checkbox options (enable/disable optional checkbox, enable/disable required checkbox).
* New hooks after clicking downloadlink (if optional checkbox is checked or regardless of the optional checkbox).
* Small CSS changes admin pages.
* Email subject has been made translatable.
* Improved notification email content.
* Columns File and Links added in downloads table.
* Argument $subscriber_id passed to hook 'dae_after_send_downloadlink'.