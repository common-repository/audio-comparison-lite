=== Audio Comparison Lite ===
Plugin Name: Audio Comparison Lite
Contributors: kaedinger
Donate link: https://audiocomparison.kaedinger.de/
Author URI: https://kaedinger.de
Plugin URI: https://audiocomparison.kaedinger.de/lite
Tags: audio, comparison, ab testing, mp3, mixing, mastering, engineering, synchronized audio, music, tracks, audio player
Requires at least: 4.0
Tested up to: 6.4.2
Stable tag: 3.1
Requires PHP: 7.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Time synchronized A/B comparison for audio files (mp3, wav...).


== Description ==

**[Click here for a live demo](https://audiocomparison.kaedinger.de/lite)**

This plugin is used to present audio files, typically two versions of the same audio (for instance, an unmixed, *raw* version  and the final *mixed* version of a song), in a time synchronized manner: **by switching seamlessly between files** when the user presses the respective button. (And since version 3 you can **compare up to three files**  in the full version!)


Finally a **direct A/B comparison is possible** on your website! You can showcase as many files on one page as you like,  completely independent from each other.

All this with a simple shortcode!

Control the site wide design and behaviour from central settings, but each Audio Comparison Lite instance can be configured independently!  And with the help of  special CSS classes you can easily adjust to your own visual style.

All about the shortcode usage and the CSS classes is in the Audio Comparison Lite settings, right where you need it.


### Full Version

If you want

* to **avoid playing two sounds at the same time**, and
* to **get rid of the play button** and/or the accompanying **text output**,
* **automatic styling of your buttons without manual CSS work** including themes,
* to **compare THREE files** time-synchronized,
* or just use Audio Comparison as a cool one button Audio Player

consider [buying the full version](https://audiocomparison.kaedinger.de/).  For the price of two beers (discounted license) you get all of the above and more, and perpetual licenses are also available!

Watch it in action [here](https://audiocomparison.kaedinger.de/live-demo/).

[And you can try it out for free!](https://audiocomparison.kaedinger.de/#shapely_home_parallax-5)




== Screenshots ==

1. One way to present your files with Audio Comparison Lite.
2. Another way to set your A/B page up.
3. Audio Comparison Lite's settings.
4. Available shortcode parameters.
5. Available CSS classes.
6. Style editor with theme 'Audio Comparison' (Pro Version shown with A/B/C comparison).
7. Style editor with theme 'studio kaedinger'.
8. Style editor with pro theme 'Sunny Sky'.
9. Style editor with pro theme 'Elegant Play'.


== Installation ==

If not installing directly from the Wordpress Plugin Directory:

1. Download and unzip to your wp-content/plugins directory
2. Alternatively, just upload the zip file via the Wordpress plugins UI

After uploading, do the following:

1. Activate the plugin via Wordpress Admin
2. Check and change Audio Comparison Lite's setting page
2. Include the shortcode `[audiocomparisonlite]` on your page or post (if using Blocks, use the Shortcode or HTML block).

The minimal shortcode looks like this:

    [audiocomparisonlite 
     file_a="PATH/TO/A.MP3" 
     file_b="PATH/TO/B.MP3"]

Replace `PATH/TO/A.MP3` and `PATH/TO/B.MP3` with your own files. We recommend WAV or MP3.

Central settings, more shortcode options, and CSS guidelines can be found in the settings.

If there are multiple instances of Audio Comparison Lite on the same page, they all act independently. If you want automatic interaction (i.e. stop if another instance starts playing) and more styling options (get rid of the Play/Stop button for instance), then the **full version** at https://audiocomparison.kaedinger.de/ is for you.


== Frequently Asked Questions ==

For the time being, please refer to our [demo/documentation page](https://audiocomparison.kaedinger.de/docs).


== Changelog ==

= 3.1 =

* Description and screenshot updates

= 3.0 =

* *Introducing 3-way comparison!* Compare up to three audio files time synchronized!
* Also introducing: play just one file - this way Audio Comparison Lite acts as the simplest possible WordPress Audio Player!

= 2.8 =

* Cosmetics ;-)

= 2.7 =

* Styling: new dynamic CSS class `.audio-comparison-lite-playing` assigned to the Play/Stop button while audio is being played
* Styling Editor: new theme _Elegant Play_ (full version)

= 2.6 =

* Styling: extended compatibility with builders (like Elementor, Beaver...) and some themes (like Astra etc.)
* (Note: Audio Comparison Lite was always compatible, this is only about the styling editor and the generated CSS code)
* Styling Editor: added Label Width
* Styling Editor: added preview of label and play/stop button position
* Handle license server contact problems gracefully

= 2.5 =

* Full version: JavaScript function AC_StopPlayingGroup() to stop any playing comparison on the page

= 2.4 =

* You can use HTML in button and label texts now!

= 2.3 =

* Apply styles safely for themes that mess with WP's core
* Reduce promo checker timeout

= 2.2 =

* Minor caching bugfix

= 2.1 =

* Minor bugfixes

= 2.0 =

* Introducing the new styling editor!
* ...including 2 fully functional themes (more in the full version!)
* New *-button-buffering and *-button-playing CSS classes

= 1.11 =

* Refine playback sync

= 1.10 =

* Documentation update
* Refine time display

= 1.9 =

* Fix the annoying iOS mobile bug AGAIN (d*** Apple!)
* New option play_default (A,B)
* New placeholder %T in playing text for time
* Documentation link in plugin row
* Promotional offer in plugin row

= 1.8 =

* Bugfixes
* Make sounds loop

= 1.7 =

* Minor bugfixes

= 1.6 =

* Cosmetics (subscription and perpetual license available)

= 1.5 =

* WP 6 compatibility

= 1.4 =

* Expanded design documentation in settings

= 1.3 =

* Increase input field lengths

= 1.2 =

* Public release

= 1.1 =

* Fix settings page

= 1.0 =

* Initial version



