=== Plugin Name ===
Contributors: catalinsendsms
Tags: sms, admin, dashboard, sendsms, marketing, subscribers, campaign, phone, 2fa
Requires at least: 4.0
Tested up to: 5.8
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

IMPORTANT: Our 2fa features was designed to work with wordpress default login form (the one accessed via wp-admin when you first install wordpress). If you are having another login form, this may break it. Make sure to check compatibility on a development environment.

Use our SMS shipping solution to deliver the right information at the right time. Give your customers a superior experience!
With our service you can secure your website with 2fa on a role basis.
Please make an account on https://www.sendsms.ro/en/

== Description ==

Why use SMS Notifications?

Simple - it is the simplest and handy channel through which you can communicate information about their orders. SMS as a communication method has an opening rate of 95% and most are read within 5 seconds of receiving them. It was found to be 3 times more productive than email and by far the easiest to customize.

== Installation ==

1. Unzip the folder under `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Features ==

* Two-factor authentication based on role with custom verification message
* Subscription system with widgets, ip-ban, SMS verification and limitation on the number of requests coming from a specific ip
* SMS history table
* Subscribers table
* Sync subscribers with sendSMS contacts
* Phone field in user edit form
* Send SMS to any number (Send a test SMS)
* Send SMS to your SMS subscribers or users (based on role)
* Both subscribe and unsubscribe widget

== Usage ==

Appearance > Widgets:

You will have 2 widgets: 
* SendSMS Subscription: add a title and the link to your gdpr page/document
* SendSMS Unsubscribe: just add a title to it

SendSMS Dashboard > SendSMS Dashboard

* General: add your sendSMS credentials here and set the country code of your phone numbers
* User: here you can enable the 2fa system; each field has a description below it
* Subscription: here you can enable SMS verification, change the subscribe message, set an ip limit or restrict specific ips

SendSMS Dashboard > Send a test SMS

Here you can send a message to every number. 
You can add an unsubscribe link or shorten every link you insert inside the message

SendSMS Dashboard > History

Here you can see a log of every message you sent

SendSMS Dashboard > Subscribers

Here you can see, add, edit, delete and sync your contacts.
The synchronization is not needed if you want to send SMS to your subscribers

SendSMS Dashboard > SMS sending

This is the place where you can send a SMS to your subscribers/ users

== Changelog ==

= 1.0 =
* Initial version
