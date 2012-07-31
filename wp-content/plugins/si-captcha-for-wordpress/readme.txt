=== SI CAPTCHA Anti-Spam ===
Contributors: Mike Challis
Author URI: http://www.642weather.com/weather/scripts.php
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6105441
Tags: akismet, captcha, comment, comments, login, anti-spam, spam, security, multilingual, buddypress, wpmu, wordpressmu
Requires at least: 2.6
Tested up to: 2.9.1
Stable tag: trunk

Adds CAPTCHA anti-spam methods to WordPress on the comment form, registration form, login, or all. Also is fully WPMU and BuddyPress compatible.

== Description ==

Adds CAPTCHA anti-spam methods to WordPress on the comment form, registration form, login, or all.
In order to post comments or regiser, users will have to type in the phrase shown on the image.
This prevents spam from automated bots. Adds security. Works great with Akismet. Also is fully WPMU and BuddyPress compatible.

[Plugin URI]: (http://www.642weather.com/weather/scripts-wordpress-captcha.php)

Features:
--------
 * Configure from Admin panel
 * JavaScript is not required
 * Valid HTML
 * Section 508 and WAI Accessibility Validation.
 * Allows Trackbacks and Pingbacks
 * Setting to hide the CAPTCHA from logged in users and or admins
 * Setting to show the CAPTCHA on the comment form, registration form, login, or all.
 * I18n language translation support (see FAQ)

Captcha Image Support:
---------------------
 * Open-source free PHP CAPTCHA library by www.phpcaptcha.org is included
 * Abstract background with multi colored, angled, and transparent text
 * Arched lines through text
 * Generates audible CAPTCHA files in WAV format
 * Refresh button to reload captcha if you cannot read it

Requirements/Restrictions:
-------------------------
 * Works with Wordpress 2.6+, WPMU, and BuddyPress
 * PHP 4.0.6 or above with GD2 library support.
 * Your theme must have a `<?php do_action('comment_form', $post->ID); ?>` tag inside your comments.php form. Most themes do.
  The best place to locate the tag is before the comment textarea, you may want to move it if it is below the comment textarea.


== Installation ==

1. Upload the `si-captcha-for-wordpress` folder to the `/wp-content/plugins/` directory, or download through the `Plugins` menu in WordPress

2. Activate the plugin through the `Plugins` menu in WordPress

3. Updates are automatic. Click on "Upgrade Automatically" if prompted from the admin menu. If you ever have to manually upgrade, simply deactivate, uninstall, and repeat the installation steps with the new version. 



1. This is how to install SI Captcha globally on WPMU or BuddyPress:

2. Step 1: upload the `/si-captcha-for-wordpress/` folder and all it's contents to `/mu-plugins/`

3. Step 2: MOVE the si-captcha.php from the `/si-captcha-for-wordpress/` folder to the `/mu-plugins/` folder.

4. Site wide Settings are located in "Site Admin", "SI CAPTCHA Optioins" 



== Screenshots ==

1. screenshot-1.jpg is the captcha on the comment form.

2. screenshot-2.jpg is the captcha on the registration form.

3. screenshot-3.jpg is the `Captcha options` tab on the `Admin Plugins` page.


== Configuration ==

After the plugin is activated, you can configure it by selecting the `Captcha options` tab on the `Admin Plugins` page.
Here is a list of the options:

1. CAPTCHA on Login Form:    - Enable CAPTCHA on the Login form.(not enabled by default)

2. CAPTCHA on Register Form: - Enable CAPTCHA on the register form.

3. CAPTCHA on Comment Form:  - Enable CAPTCHA on the comment form.

4. CAPTCHA on Comment Form:  - Hide CAPTCHA for registered users (select permission level)

5. CAPTCHA on Comment Form:  - CSS class name for CAPTCHA input field on the comment form: 
(Enter a CSS class name only if your theme uses one for comment text inputs. Default is blank for none.)

6. Comment Form Rearrange: - Changes the display order of the catpcha input field on the comment form.


== Usage ==

Once activated, a captcha image and captcha code entry is added to the comment and register forms. The Login form captcha is not enabled by default because it might be annoying to users. Only enable it if you are having spam problems related to bots automatically logging in


== Frequently Asked Questions ==

= Sometimes the captcha image and captcha input field are displayed AFTER the submit button on the comment form. =

Your theme must have a `<?php do_action('comment_form', $post->ID); ?>` tag inside your comments.php form. Most themes do.
  The best place to locate the tag is before the comment textarea, you may want to move it if it is below the comment textarea.
This tag is exactly where the captcha image and captcha code entry will display on the form, so
move the line to before the comment textarea, uncheck the 'Comment Form Rearrange' box on the 'Captcha options' page,
and the problem should be fixed.

= Alternate Fix for the captcha image display order =

You can just check the 'Comment Form Rearrange' box on the admin plugins 'Captcha options' page and javascript will attempt to rearrange it for you. Editing the comments.php, moving the tag, and uncheck the 'Comment Form Rearrange' box on the 'Captcha options' page is the best solution.

= Why is it better to uncheck the 'Comment Form Rearrange' box and move the tag? =
Because the XHTML will no longer validate if it is checked.

= Why do I get "ERROR: Could not read CAPTCHA cookie. Make sure you have cookies enabled and not blocking in your web browser settings. Or another plugin is conflicting."? =

Check your web browser settings and make sure you are not blocking cookies for your blog domain. Cookies have to be enabled in your web browser and not blocked for the blog web domain.

If you get this error, your browser is blocking cookies or you have another plugin that is conflicting (in that case I would like to help you further to determine which one). I can tell you that the plugin called "Shopp" is not compatible because it handles sessions differently causing the "ERROR: Could not read CAPTCHA cookie. Make sure you have cookies enabled".

The Cookie Test can be used to test if your browser is accepting cookies from your site:
Click on the "Test if your PHP installation will support the CAPTCHA" link on the Options page.
or open this URL in your web browser to run the test:
`/wp-content/plugins/si-captcha-for-wordpress/captcha-secureimage/test/index.php`


= Troubleshooting if the CAPTCHA form fields and image is not being shown: =

Do this as a test:
Activate the SI CAPTCHA plugin and temporarily change your theme to the "Wordpress Default" theme.
Does the captcha image show now?
If it does then the theme you are using is the cause.

Your theme must have a `<?php do_action('comment_form', $post->ID); ?>` tag inside your comments.php form. Most themes do.
  The best place to locate the tag is before the comment textarea, you may want to move it if it is below the comment textarea.
This tag is exactly where the captcha image and captcha code entry will display on the form, so
move the line to before the comment textarea, uncheck the 'Comment Form Rearrange' box on the 'Captcha options' page,
and the problem should be fixed.


= Troubleshooting if the CAPTCHA image itself is not being shown: =

By default, the admin will not see the CAPTCHA. If you click "log out", go look and it will be there.

If the image is broken and you have the CAPTCHA entry box:
This can happen if a server has too low a default permission level on new folders.
Check and make sure the permission on all the captcha-secureimage folders are set to permission: 755

all these folders need to be 755:
- si-captcha-for-wordpress
  - captcha-secureimage
     - audio
     - backgrounds
     - gdfonts
     - images
     - list
     - test
     - ttffonts
     - words

Here is a [tutorial about file permissions](http://www.stadtaus.com/en/tutorials/chmod-ftp-file-permissions.php)

This script can be used to test if your PHP installation will support the CAPTCHA:
Click on the "Test if your PHP installation will support the CAPTCHA" link on the Options page.
or open this URL in your web browser to run the test:
`/wp-content/plugins/si-captcha-for-wordpress/captcha-secureimage/test/index.php`


= Is this plugin available in other languages? =

Yes. To use a translated version, you need to obtain or make the language file for it. 
At this point it would be useful to read [Installing WordPress in Your Language](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") from the Codex.
You will need an .mo file for this plugin that corresponds with the "WPLANG" setting in your wp-config.php file.
Translations are listed below -- if a translation for your language is available, all you need to do is place it in the `/wp-content/plugins/si-captcha-for-wordpress/languages` directory of your WordPress installation.
If one is not available, and you also speak good English, please consider doing a translation yourself (see the next question).


The following translations are included in the download zip file:

* Albanian (sq_AL) - Translated by [Romeo Shuka](http://www.romeolab.com)
* Arabic (ar) - Translated by [Amine Roukh](http://amine27.zici.fr/)
* Belorussian (by_BY) - Translated by [Marcis Gasuns](http://www.comfi.com/)
* Chinese (zh_CN) - Translated by [Awu](http://www.awuit.cn/) 
* Czech (cs_CZ) - Translated by [Radovan](http://algymsa.cz)
* Danish (da_DK) - Translated by [Parry](http://www.detheltnyestore.dk/)
* Dutch (nl_NL) - Translated by [Robert Jan Lamers](http://www.salek.nl/)
* French (fr_FR) - Translated by [Pierre Sudarovich](http://pierre.sudarovich.free.fr/)
* German (de_DE) - Translated by [Sebastian Kreideweiss](http://sebastian.kreideweiss.info/)
* Greek (el) - Translated by [Ioannis](http://www.jbaron.gr/)
* Hungarian (hu_HU) - Translated by [Vil]
* Indonesian (id_ID) - Translated by [Masino Sinaga](http://www.openscriptsolution.com)
* Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")
* Norwegian (nb_NO) - Translated by [Roger Sylte](http://roger.inro.net/)
* Polish (pl_PL) - Translated by [Tomasz](http://www.ziolczynski.pl/)
* Portuguese Brazil (pt_BR) - Translated by [Newton Dan Faoro]
* Romanian (ro_RO) - Translated by [Laszlo SZOKE](http://www.naturaumana.ro)
* Russian (ru_RU) - Translated by [Neponyatka](http://www.free-lance.ru/users/neponyatka)
* Slovakian (sk_SK) - Translated by [Marek Chochol]
* Spanish (en_ES) - Translated by [LoPsT](http://www.lopst.com/)
* Swedish (sv_SE) - Translated by [Benct]
* Traditional Chinese, Taiwan Language (zh_TW) - Translated by [Cjh]
* Turkish (tr_TR) - Translated by [Volkan](http://www.kirpininyeri.com/)
* More are needed... Please help translate.


= Are the CAPTCHA audio files available in other languages? =

Portuguese brazil (pt_BR) audio files are available. Wait until after you install the plugin. Download the audio files:
[Portuguese brazil (pt_BR) audio files download](http://www.642weather.com/weather/scripts/captcha-secureimage-pt_BR.zip) and follow instructions in the Readme.txt inside the zip file.

= Can I provide a translation? =

Of course! It will be very gratefully received. Use PoEdit, it makes translation easy. Please read [Translating WordPress](http://codex.wordpress.org/Translating_WordPress "Translating WordPress") first for background information on translating. Then obtain the latest [.pot file](http://svn.wp-plugins.org/si-captcha-for-wordpress/trunk/languages/si-captcha.pot ".pot file") and translate it. 
* There are some strings with a space in front or end -- please make sure you remember the space!
* When you have a translation ready, please send the .po and .mo files to wp-translation at 642weather dot com. 
* If you have any questions, feel free to email me also. Thanks!

= Is it possible to merge the translation files I sent to you with the ones of the newest version? =

If you use PoEdit to translate, it is easy to translate for a new version. You can open your current .po file, then select from the PoEdit menu: "Catalog" > "Update from POT file". Now all you have to change are the new language strings.

== Changelog ==

= 2.2.8 =
- (14 Jan 2010) - Added Dutch (nl_NL) - Translated by [Robert Jan Lamers](http://www.salek.nl/)

= 2.2.7 =
- (31 Dec 2009) - New setting for a few people who had problems with the text transparency "Disable CAPTCHA transparent text (only if captcha text is missing on the image, try this)". 
- Added Slovakian (sk_SK) - Translated by [Marek Chochol]
- Updated Arabic (ar) - Translated by [Amine Roukh](http://amine27.zici.fr/)

= 2.2.6 =
- (16 Dec 2009) - Added SSL compatibility.
- Added Hungarian (hu_HU) - Translated by [Vil]

= 2.2.5 =
- (06 Dec 2009) - More improvements for CAPTCHA images and fonts.

= 2.2.4 =
- (30 Nov 2009) - Fix blank CAPTCHA text issue some users were having.
- Added CAPTCHA difficulty level setting on the settings page (Low, Medium, Or High).
- Added Indonesian (id_ID) - Translated by [Masino Sinaga](http://www.openscriptsolution.com).
- Added Romanian (ro_RO) - Translated by [Laszlo SZOKE](http://www.naturaumana.ro).

= 2.2.3 =
- (23 Nov 2009) - Fix completely broke CAPTCHA, sorry about that

= 2.2.2 =
- (23 Nov 2009) - Added 5 random CAPTCHA fonts
- Fixed fail over to GD Fonts on the CAPTCHA when TTF Fonts are not enabled in PHP (it was broken)

= 2.2.1 =
- (21 Nov 2009) - Fixed Flash audio was not working.

= 2.2 =
- (20 Nov 2009) - Updated to SecureImage CAPTCHA library version 2.0
- New CAPTCHA features include: increased CAPTCHA difficulty using mathematical distortion, streaming MP3 audio of CAPTCHA code using Flash, random audio distortion, better distortion lines, random backgrounds and more.
- Other minor fixes.

= 2.1.1 =
- (10 Nov 2009) - Fix style and input alignments.

= 2.1 =
- (03 Nov 2009) - Fix for settings not being deleted when plugin is deleted from admin page.

= 2.0.9 =
- (30 Oct 2009) - Fixed issue on some sites with blank css fields that caused image misalignment.

= 2.0.8 =
- (29 Oct 2009) - Added new setting in advanced options: "CSS style for CAPTCHA div".

= 2.0.7 =
- (21 Oct 2009) - Added Chinese (zh_CN) - Translated by [Awu](http://www.awuit.cn/) 

= 2.0.6 =
- (13 Oct 2009) - Fixed array_merge error on WPMU, Buddypress.
- Added Czech (cs_CZ) - Translated by [Radovan](http://algymsa.cz)

= 2.0.5 =
- (09 Oct 2009) - Added Albanian (sq_AL) - Translated by [Romeo Shuka](http://www.romeolab.com)

= 2.0.4 =
- (03 Oct 2009) - Fixed session error on Buddypress versions.

= 2.0.3 =
- (01 Oct 2009) - Renamed to SI CAPTCHA Anti-Spam

= 2.0.2 =
- (30 Sep 2009) - Fixed settings were deleted at deactivation. Settings are now only deleted at uninstall.

= 2.0.1 =
- (25 Sep 2009) - BuddyPress 1.1 CSS fixes for the CAPTCHA position on the regstration form.

= 2.0 =
- (25 Sep 2009) - Added full WPMU and BuddyPress compatibility. WPMU and BuddyPress users can now protect comment form, registration, and login from spam.
- Added login form CAPTCHA. The Login form captcha is not enabled by default because it might be annoying to users. Only enable it if you are having spam problems related to bots automatically logging in.
- New feature: An "advanced options" section to the options page. Some people wanted to change the text labels for the CAPTCHA and code input field.
These advanced options fields can be filled in to override the standard included text labels.
- Added new advanced options for editing inline CSS style of captcha image, audio image, and reload image.
- Supports BuddyPress 1.0.3 and 1.1 
- Minor code cleanup.

= 1.8 =
- (15 Sep 2009) - Plugin options are now stored in a single database row instead of many. (and it will auto migrate/cleanup old options database rows).
- Language files are now stored in the `si-captcha-for-wordpress/languages` folder.
- Options are now deleted when this plugin is deleted.
- Added proper nonce protection to options forms.

= 1.7.12 =
- (08 Sep 2009) - Fixed redirect/logout problem on admin menu reported by a user.

= 1.7.11 =
- (03 Sep 2009) Updated German Language (de_DE) - Translated by [Sebastian Kreideweiss](http://sebastian.kreideweiss.info/)

= 1.7.10 =
- (02 Sep 2009) Updated Traditional Chinese, Taiwan Language (zh_TW) - Translated by [Cjh]

= 1.7.9 =
- (31 Aug 2009) Added more diagnostic test scripts: a Cookie Test, Captcha test, and a PHP Requirements Test.
Click on the "Test if your PHP installation will support the CAPTCHA" link on the Options page.
or open this URL in your web browser to run the test:
`/wp-content/plugins/si-captcha-for-wordpress/captcha-secureimage/test/index.php`
- Updated Italian language (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 1.7.8 =
- (31 Aug 2009) Improved cookie error

= 1.7.7 =
- (30 Aug 2009) Added a `cookie_test.php` to help diagnose if a web browser has cookies disabled. (see the FAQ) 

= 1.7.6 =
- (29 Aug 2009) Added this script to test if your PHP installation will support the CAPTCHA:
Click on the "Test if your PHP installation will support the CAPTCHA" link on the Options page.
or open this URL in your web browser to run the test:
`/wp-content/plugins/si-captcha-for-wordpress/captcha-secureimage/test/index.php`

= 1.7.5 =
- (28 Aug 2009) Added Arabic Language (ar) - Translated by [Amine Roukh](http://amine27.zici.fr/)
- CAPTCHA fix - Added Automatic fail over from TTF Fonts to GD Fonts if the PHP installation is configured without "--with-ttf".
  Some users were reporting there was no error indicating this TTF Fonts not supported condition and the captcha was not working.

= 1.7.4 =
- (28 Aug 2009) Updated Italian language (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 1.7.3 =
- (28 Aug 2009) Updated Italian language (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 1.7.2 =
- (28 Aug 2009) fix options permission bug introduced by last update, sorry

= 1.7.1 =
- (27 Aug 2009) added settings link to the plugin action links

= 1.7 =
- (26 Aug 2009) Added error code for when the user has cookies disabled (the CAPTCHA requires cookies)
- added setting to enable aria-required form tags for screen readers(disabled by default)
- added a donate button on the options page. If you find this plugin useful to you, please consider making a small donation to help contribute to further development. Thanks for your kind support! - Mike Challis

= 1.6.9 =
- (03 Aug 2009) Added Greek Language (el) - Translated by [Ioannis](http://www.jbaron.gr/)

= 1.6.8 =
- (29 Jul 2009) Added Polish Language (pl_PL) - Translated by [Tomasz](http://www.ziolczynski.pl/)

= 1.6.7 = 
- (12 Jun 2009) WP 2.8 Compatible

= 1.6.6 = 
- (10 Jun 2009) Updated Russian Language (ru_RU) - Translated by [Neponyatka](http://www.free-lance.ru/users/neponyatka)

= 1.6.5 = 
- (09 Jun 2009) Added Traditional Chinese, Taiwan Language (zh_TW) - Translated by [Cjh]

= 1.6.4 = 
- (15 May 2009) Added Swedish Language (sv_SE) - Translated by [Benct]

= 1.6.3 =
- (10 May 2009) Added Russian Language (ru_RU) - Translated by [Fat Cow](http://www.fatcow.com/)

= 1.6.2 =
- (05 May 2009) Added Spanish Language (en_ES) - Translated by [LoPsT](http://www.lopst.com/)

= 1.6.1 =
- (06 Apr 2009) Added Belorussian Language (by_BY) - Translated by [Marcis Gasuns](http://www.comfi.com/)
- Fixed audio CAPTCHA link URL, it did not work properly on Safari 3.2.1 (Mac OS X 10.5.6).
- Note: the proper way the audio CAPTCHA is supposed to work is like this: a dialog pops up, You have chosen to open:
secureimage.wav What should (Firefox, Safari, IE, etc.) do with this file? Open with: (Choose) OR Save File. Be sure to select open, then it will play in WMP, Quicktime, Itunes, etc.

= 1.6 =
- (23 Mar 2009) Added new option on configuration page: You can set a CSS class name for CAPTCHA input field on the comment form: 
(Enter a CSS class name only if your theme uses one for comment text inputs. Default is blank for none.)

= 1.5.4 =
- (19 Mar 2009) Updated Danish Language (da_DK) - Translated by [Parry](http://www.detheltnyestore.dk/)

= 1.5.3 =
- (12 Mar 2009) Added German Language (de_DE) - Translated by [Sebastian Kreideweiss](http://sebastian.kreideweiss.info/)
- Updated Danish Language (da_DK) - Translated by [Parry](http://www.detheltnyestore.dk/)

= 1.5.2 =
- (24 Feb 2009) Added Danish Language (da_DK) - Translated by [Parry](http://www.detheltnyestore.dk/)

= 1.5.1 =
- (11 Feb 2009) Added Portuguese_brazil Language (pt_BR) - Translated by [Newton Dan Faoro]

= 1.5 =
- (22 Jan 2009) Added fix for compatibility with WP Wall plugin. This does NOT add CAPTCHA to WP Wall plugin, it just prevents the "Error: You did not enter a Captcha phrase." when submitting a WP Wall comment.
- Added Norwegian language (nb_NO) - Translated by [Roger Sylte](http://roger.inro.net/)

= 1.4 = 
- (04 Jan 2009) Added Turkish language (tr_TR) - Translated by [Volkan](http://www.kirpininyeri.com/)

= 1.3.3 =
-  (02 Jan 2009) Fixed a missing "Refresh Image" language variable

= 1.3.2 =
-  (19 Dec 2008) Added WAI ARIA property aria-required to captcha input form for more accessibility

= 1.3.1 =
- (17 Dec 2008) Changed screenshots to WP 2.7
- Better detection of GD and a few misc. adjustments

= 1.3 =
- (14 Dec 2008) Added language translation to the permissions drop down select on the options admin page, thanks Pierre
- Added French language (fr_FR) - Translated by [Pierre Sudarovich](http://pierre.sudarovich.free.fr/)

= 1.2.1 =
- (23 Nov 2008) Fixed compatibility with custom `WP_PLUGIN_DIR` installations

= 1.2 =
- (23 Nov 2008) Fixed install path from `si-captcha` to `si-captcha-for-wordpress` so automatic update works correctly.

= 1.1.1 =
- (22 Nov 2008) Added Italian language (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 1.1 =
- (21 Nov 2008) Added I18n language translation feature

= 1.0 =
- (21 Aug 2008) Initial Release



