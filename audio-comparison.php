<?php
/*
  Plugin Name: Audio Comparison Lite
  Plugin URI: https://audiocomparison.kaedinger.de/lite
  Description: Time synchronized A/B comparison of two audio files side by side. In style. For detailed documentation, please visit the plugin's <a href="https://audiocomparison.kaedinger.de/"> official page</a>.
  Version: 3.4
  Author: kaedinger
  Author URI: https://kaedinger.de
  License: GPLv2 or later 
*/
defined( 'ABSPATH' ) || exit;
require_once (dirname( __FILE__ ) . '/styling.php');
require_once (dirname( __FILE__ ) . '/promotion-checker.php');
if( ! class_exists( 'audioComparisonLite' ) ) {
  class audioComparisonLite {
    const SHORTCODE = 'audiocomparisonlite';
    const MAIN_DIV = 'audio-comparison-lite';
    const OPTIONS_KEY = 'audiocomparisonlite_options';
    const SETTINGS_TITLE = 'Audio Comparison Lite';
    const LITE = 'LITE ';
    const STYLER = 'audioComparisonLiteStyling';
    const PROMOTER = 'audioComparisonLitePromotionChecker';
    const MY_VERSION_NUMBER = '3.4';
    const NONCE_ACTION = 'settings_apply';
    const NONCE_FIELD = self::OPTIONS_KEY;
    private $Styling;
    private $Promoter;
    private function eh($out) { echo esc_html($out); }
    private function ea($out) { echo esc_attr($out); }
    public function __construct() {
      $styling_class = self::STYLER;
      $this->Styling = new $styling_class(self::MAIN_DIV);
      $promoter_class = self::PROMOTER;
      $this->Promoter = new $promoter_class();
      add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts' ) );
      add_action( 'init', array($this, 'init' ) );
      add_action('admin_menu', array($this, 'create_menu' ) );
      add_action('admin_init', array($this, 'admin_init' ));
    }
    public function enqueue_scripts() {
      wp_enqueue_script('jquery');
      wp_enqueue_script('howler-js', plugins_url('lib/howler.core.min.js', __FILE__), array(), self::MY_VERSION_NUMBER, array( 'strategy' => 'defer', 'in_footer' => 'true' ));
      wp_enqueue_script('audio-comparison-lite', plugins_url('audio-comparison.js', __FILE__), array('jquery', 'howler-js'), self::MY_VERSION_NUMBER, array( 'strategy' => 'defer', 'in_footer' => 'true' ));
    }
    public function init() {
      add_shortcode(self::SHORTCODE, array($this, 'shortcode' ) );
    }
    public function shortcode($attr = [], $content = null, $tag = '') {
      $plugin_data = $this->get_plugin_data();
      $opts = $this->get_options();
      $attr = array_change_key_case( (array) $attr, CASE_LOWER );
      $attr = shortcode_atts(
        array(
          'play_button' => $opts['play_button'],
          'text_button_play' => $opts['text_button_play'],
          'text_button_stop' => $opts['text_button_stop'],
          'text_a' => $opts['text_a'],
          'text_b' => $opts['text_b'],
          'text' => $opts['text'],
          'text_loading' => $opts['text_loading'],
          'text_ready' => $opts['text_ready'],
          'text_button_a' => $opts['text_button_a'],
          'text_button_b' => $opts['text_button_b'],
          'before' => '',
          'file_a' => '',
          'file_b' => '',
          'play_default' => $opts['play_default'],
        ), $attr, $tag
      );
      $endl = "\n";
      $playbuttonbuff = '';
      $playbuttontextsbuff = '';
      $playButton = esc_html($attr['play_button']);
        $playDefault = esc_html($attr['play_default']);
        $playbuttontextsbuff .= ' data-play-default="' . $playDefault . '"';
        $textPlay = esc_html($attr['text_button_play']);
        $textStop = esc_html($attr['text_button_stop']);
        $playbuttontextsbuff .= ' data-button-play-text="' . $textPlay . '"';
        $playbuttontextsbuff .= ' data-button-stop-text="' . $textStop . '"';
        $playbuttonbuff .= '<button class="' . self::MAIN_DIV . '-play-stop">' . html_entity_decode($textPlay) . '</button>' . $endl;
      $fileA = esc_html($attr['file_a']);
      $fileB = esc_html($attr['file_b']);
      $buff = '';
      $buff .= "\n<!-- {$plugin_data['name']} | {$plugin_data['url']} -->\n";
      $buff .= '<div class="' . self::MAIN_DIV . '"';
      $text = esc_html($attr['text']);
      {
        $textA = esc_html($attr['text_a']);
        if(!empty($textA)) $buff .= ' data-playing-a-text="' . $textA . '"';
        if(!empty($fileB)) {
          $textB = esc_html($attr['text_b']);
          if(!empty($textB)) $buff .= ' data-playing-b-text="' . $textB . '"';
        }
        $textLoading = esc_html($attr['text_loading']);
        if(!empty($textLoading)) $buff .= ' data-buffering-text="' . $textLoading . '"';
        $textReady = esc_html($attr['text_ready']);
        if(!empty($textReady)) $buff .= ' data-buffered-text="' . $textReady . '"';
      }
      if(!empty($fileA)) $buff .= ' data-file-a="' . $fileA . '"';
      if(!empty($fileB)) $buff .= ' data-file-b="' . $fileB . '"';
      $buff .= $playbuttontextsbuff . ">\n";
      $buff .= esc_html($attr['before']);
      $label = '<span class="' . self::MAIN_DIV . '-label"></span>' . $endl;
      if ($text == "before")
      {
        $buff .= $label;
        $label = '';
      }
      if($playButton == "left")
      {
        $buff .= $playbuttonbuff;
        $playbuttonbuff = '';
      }
      $buttonA = esc_html($attr['text_button_a']);
      $buff .= '<button class="' . self::MAIN_DIV . '-play-a">' . html_entity_decode($buttonA) . '</button>' . $endl;
      if(!empty($fileB)) {
        $buttonB = esc_html($attr['text_button_b']);
        $buff .= '<button class="' . self::MAIN_DIV . '-play-b">' . html_entity_decode($buttonB) . '</button>' . $endl;
      }
      $buff .= $playbuttonbuff;
      $buff .= $label;
      $buff .= "</div>";
      $buff .= "<!-- ^^^ {$plugin_data['name']} | {$plugin_data['url']} -->\n";
      return $buff;
    }
    public function get_plugin_data() {
      $default_headers = array(
          'Name' => 'Plugin Name',
          'PluginURI' => 'Plugin URI',
      );
      $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');
      $url = $plugin_data['PluginURI'];
      $name = $plugin_data['Name'];
      $data['name'] = $name;
      $data['url'] = $url;
      return $data;
    }
    public function get_defaults() {
      return array(
        'text' => 'before',            
        'play_button' => 'left',       
        'text_a' => 'Now playing A',
        'text_b' => 'Now playing B',
        'text_c' => 'Now playing C',
        'text_loading' => 'Buffering audio...',
        'text_ready' => 'Ready!',
        'text_button_play' => 'Play',
        'text_button_stop' => 'Stop',
        'text_button_a' => 'Version A',
        'text_button_b' => 'Version B',
        'text_button_c' => 'Version C',
        'play_default' => 'A',         
        'style_theme' => 'ac', 
        'style_color_a' => '#f0a07c',
        'style_color_b' => '#4a274f',
        'style_color_c' => '#ffffff',
        'style_width' => '140',
        'style_height' => '10',
        'style_border' => '2',
        'style_corner' => '0',
        'style_font_size' => '13',
        'style_label_color' => '#000000',
        'style_label_width' => '200',
        'apply_style' => '',
        'tryout_backgroundcolor' => '#f0f0f1',
      );
    }
    public function get_options() {
      $defaults = $this->get_defaults();
      $current_options = get_option( self::OPTIONS_KEY, $defaults);
      if ($current_options['text_ready'] === "reset") {
        $current_options = $defaults;
      } else {
        $current_options = array_merge($defaults, $current_options);
      }
      return $current_options;
    }
    public function set_options($opts) {
      foreach ($opts as $key => $value) {
          $value = wp_kses($value, array());
          $value = trim($value);
          $opts[$key] = $value;
      }
      update_option( self::OPTIONS_KEY , $opts);
      return $opts;
    }
    public function create_menu() {
      add_options_page(self::SETTINGS_TITLE, self::SETTINGS_TITLE, 'manage_options', self::OPTIONS_KEY, array($this, 'settings_page' ), 99 );
      add_filter('plugin_action_links', array($this, 'add_plugin_settings_link' ), 10, 2);
      add_filter( 'plugin_row_meta', [ $this, 'add_plugin_row_meta_link' ], 10, 2 );
    }
    public function add_plugin_row_meta_link($links, $file) {
      if ($file == plugin_basename(__FILE__)) {
        $doc_link = '<a href="https://audiocomparison.kaedinger.de/docs" target="_blank" rel="noopener noreferrer">Documentation</a>';
        $links[] = $doc_link;
        $mylinks = $this->Promoter->get_links();
        if(count($mylinks) == 0) {
            $pro_link = '<a href="https://audiocomparison.kaedinger.de" 
            target="_blank" rel="noopener noreferrer" 
            style="background-color: #f0a07c; color: #4a274f; font-weight: 700" 
            onmouseover="this.style.color=\'#f0a07c\'; this.style.backgroundColor=\'#4a274f\';" 
            onmouseout="this.style.color=\'#4a274f\'; this.style.backgroundColor=\'#f0a07c\';" 
            >&nbsp;Get Pro!&nbsp;</a>';
            $mylinks[] = $pro_link;
        }
        $links = array_merge($links, $mylinks);
      }
      return $links;
    }
    public function add_plugin_settings_link($links, $file) {
      if ($file == plugin_basename(__FILE__)) {
        $link = admin_url('options-general.php?page=' . self::OPTIONS_KEY);
        $dashboard_link = "<a href=\"{$link}\">Settings</a>";
        array_unshift($links, $dashboard_link);
      }
      return $links;
    }
    public function load_settings_assets() {
      wp_enqueue_script('jquery');
      $themes = $this->Styling->get_theme_templates();
      $localized = [
        'MAIN_DIV' => self::MAIN_DIV,
        'themes' => $themes,
      ];
      wp_enqueue_script('audiocomparisonlite_styler_js', plugins_url("styling.js", __FILE__), array('jquery'), self::MY_VERSION_NUMBER, array( 'strategy' => 'defer', 'in_footer' => 'true' ));
      wp_localize_script('audiocomparisonlite_styler_js', 'AUDIOCOMPARISONLITE', $localized);
    }
    public function settings_textinput($for, $disabled = false) {
      ?><input maxlength="60" id="<?php $this->ea($for)?>" name="<?php $this->ea($for)?>" autocomplete="off" value="<?php $this->ea($this->get_options()[$for])?>"<?php echo $disabled?" disabled":""?> /><?php 
    }
    public function settings_page() {
      $this->load_settings_assets();
      $saved = 0;
      $nonce_failed = 0;
      $current_options = $this->get_options();
      $themes = $this->Styling->get_theme_templates();
      $server_name = "";
      if (isset($_SERVER['SERVER_NAME'])) {
        $server_name = "%20on%20" . sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) );
      }
      $settings_hint = '';
      $settings_hint2 = '';
      $settings_hint3 = '';
      if (!empty($_POST)) 
      {
        if ( ! isset( $_POST[self::NONCE_FIELD] ) || ! wp_verify_nonce( sanitize_key($_POST[self::NONCE_FIELD]), self::NONCE_ACTION ) ) {
          $settings_hint = "<br>Input verification failed. It looks like you tried to change some things you're not suppoosed to.";
          $nonce_failed = 1;
        } else {
          $current_options_keys = array_keys($current_options);
          $_REQ = stripslashes_deep($_REQUEST);
          foreach ($_REQ as $key => $value) {
            if (in_array($key, $current_options_keys)) {
              $value = esc_attr(wp_kses_post($value));
              $value = trim($value);
              if(strpos($value, "*") !== false) {
                $settings_hint = '<br>* Options are exclusive to the full version of Audio Comparison. See <a href="https://audiocomparison.kaedinger.de">https://audiocomparison.kaedinger.de</a>';
              } else {
                if (empty($value) && strpos($key, "style") === false) {
                  $value = $this->get_defaults()[$key];
                  $settings_hint2 = '<br> - Empty texts are available in the full version of Audio Comparison. See <a href="https://audiocomparison.kaedinger.de">https://audiocomparison.kaedinger.de</a>';
                } elseif (
                  (strlen($value) == 2 && ord(substr($value, 0)) == 194 && ord(substr($value, 1)) == 160 ) ||
                  (strlen($value) == 6 && $value == "&nbsp;") || 
                  (strlen($value) == 9 && ord(substr($value, 0)) == 38 && ord(substr($value, 1)) == 97 && ord(substr($value, 2)) == 109 && ord(substr($value, 3)) == 112 && ord(substr($value, 4)) == 59 && ord(substr($value, 5)) == 110 && ord(substr($value, 6)) == 98 && ord(substr($value, 7)) == 115 && ord(substr($value, 8)) == 112 ) )
                {
                  $settings_hint2 = '<br>CONGRATULATIONS! You hacked the system with a non-breaking space ;-) <a href="https://audiocomparison.kaedinger.de">But there are sooo many more features in the full version of Audio Comparison!</a> Use code IAMAHACKER at checkout to get 5% off!';
                }
                $current_options[$key] = $value;
              }
            }
          }
          if (array_key_exists('apply_style', $_REQ)) {
            $settings_hint3 = '<br>* The automatic styling is only available in the <a href="https://audiocomparison.kaedinger.de">full version of Audio Comparison</a>, along with many more themes and features!';
            $current_options['apply_style'] = "";
          }
          $current_options = $this->set_options($current_options);
          $saved = 1;
        }
      }
    ?>
    <div class="wrap <?php $this->eh(self::SHORTCODE) ?>_container">
      <style>
      .<?php $this->eh(self::SHORTCODE)?>_settings th {
        text-align: right;
      }
      .<?php $this->eh(self::SHORTCODE)?>_settings select {
        width: 28ch;
      }
      .<?php $this->eh(self::SHORTCODE)?>_settings input[type=submit], input[type=color], input[type=range] {
        width: 20ch;
      }
      .<?php $this->eh(self::SHORTCODE)?>_settings img {
        width: 200px;
      }
      .AC_ROW { display: flex; }
      .AC_ROW a { text-decoration: none; }
      .AC_COL50 { flex: 50%; }
      @media screen and (orientation: portrait) { .AC_COL { flex: 100%; } }
      </style>
      <?php if (!empty($saved)) : ?>
        <h2></h2>
        <div class="updated">
          <p>Settings were saved.
            <?php echo wp_kses_post( $settings_hint . $settings_hint2 . $settings_hint3); ?>
          </p>
        </div>
      <?php endif; ?>
      <?php if (!empty($nonce_failed)) : ?>
        <h2></h2>
        <div class="updated">
          <p>Settings were NOT saved.
            <?php echo wp_kses_post( $settings_hint); ?>
          </p>
        </div>
      <?php endif; ?>
      <div class="AC_ROW">
        <div style="flex: 0 0 110px;">
          <img src="<?php echo esc_url(plugins_url('/logo.png', __FILE__))?>" style="width: 100px">
        </div>  
        <div style="flex: 1;">
          <p><span style="font-size: x-large"><?php $this->eh(self::SETTINGS_TITLE)?></span> <span style="font-size: small"> Version 3.4</span></p>
          <p>
            Time synchronized A/B comparison <?php $this->eh(self::LITE)?>of two audio files side by side. In style.<br>
            Developed by <a href="https://studio.kaedinger.de">studio kaedinger</a>
            &bull; <a href="https://audiocomparison.kaedinger.de">Full Version</a>
            &bull; <strong><a href="https://kaedinger.onfastspring.com/audiocomparison">Subscription and perpetual license available!</a></strong>
            &bull; <a href="https://audiocomparison.kaedinger.de/docs/">Documentation</a>
            &bull; <a href="mailto:support@kaedinger.de?subject=Audio%20Comparison%20Lite<?php $this->eh($server_name)?>">Questions?</a>
          </p>  
        </div>  
        <div style="flex: none; text-align: right;">
          <?php
            $mylinks = $this->Promoter->get_links();
            if(count($mylinks) > 0) {
              echo wp_kses_post(implode("<br/>", $mylinks));
            }
          ?>
        </div>  
      </div>  
      <form method="post">
        <div class="AC_ROW">
          <div class="AC_COL50">
            <h2>Settings</h2>
            <table class="<?php $this->eh(self::SHORTCODE)?>_settings">
              <tr>
                <th scope="row"><label for="text">Output:</label></th>
                <td><select id="text" name="text">
                  <option value="before"<?php $this->eh($current_options['text'] == "before" ? " selected" : "" )?>>Before the buttons</option>
                  <option value="after"<?php $this->eh($current_options['text'] == "after" ? " selected" : "" )?>>After the buttons</option>
                  <option value="*none">* No text output</option>
                </select></td>
              </tr>
              <tr>
                <th scope="row"><label for="text_a">Output for A<sup>1,2</sup>:</label></th>
                <td><?php $this->settings_textinput('text_a'); ?> e.g. 'Now playing A'</td>
              </tr>
              <tr>
                <th scope="row"><label for="text_b">Output for B<sup>1,2</sup>:</label></th>
                <td><?php $this->settings_textinput('text_b'); ?> e.g. 'Now playing B'</td>
              </tr>
              <tr>
                <th scope="row"><label for="text_c">Output for C<sup>1,2</sup>:</label></th>
                <td><?php $this->settings_textinput('text_c',true); ?> (3-way-comparison only available in the full version)</td>
              </tr>
              <tr>
                <th scope="row"><label for="text_loading">Output during file load<sup>2</sup>:</label></th>
                <td><?php $this->settings_textinput('text_loading'); ?> e.g. 'Buffering audio...'</td>
              </tr>
              <tr>
                <th scope="row"><label for="text_ready">Output after file load</label><sup>2</sup>:</label></th>
                <td><?php $this->settings_textinput('text_ready'); ?> e.g. 'Ready!'</td>
              </tr>
              <tr>
                <th scope="row"><label for="play_button">Play/Stop button:</label></th>
                <td><select id="play_button" name="play_button">
                      <option value="left"<?php echo wp_kses_post( $current_options['play_button'] == "left" ? " selected" : "" ); ?>>Left of the A/B buttons</option>
                      <option value="right"<?php echo wp_kses_post( $current_options['play_button'] == "right" ? " selected" : "" ); ?>>Right of the A/B buttons</option>
                      <option value="*none">* No play button</option>
                </select></td>
              </tr>
              <tr>
                <th scope="row"><label for="play_default">Play default:</label></th>
                <td><select id="play_default" name="play_default">
                      <option value="A"<?php echo wp_kses_post( $current_options['play_default'] == "A" ? " selected" : "" ); ?>>Play A on default</option>
                      <option value="B"<?php echo wp_kses_post( $current_options['play_default'] == "B" ? " selected" : "" ); ?>>Play B on default</option>
                      <option value="*C">* Play C on default</option>
                    </select>
                </td>
              </tr>
              <tr>
                <th scope="row"><label for="text_button_play">Label on Play/Stop button for Play:</label></th>
                <td><?php $this->settings_textinput('text_button_play'); ?> e.g. 'Play'</td>
              </tr>
              <tr>
                <th scope="row"><label for="text_button_stop">Label on Play/Stop button for Stop:</label></th>
                <td><?php $this->settings_textinput('text_button_stop'); ?> e.g. 'Stop'</td>
              </tr>
              <tr>
                <th scope="row"><label for="text_button_a">Label on A button:</label></th>
                <td><?php $this->settings_textinput('text_button_a'); ?> e.g. 'Version A'</td>
              </tr>
              <tr>
                <th scope="row"><label for="text_button_b">Label on B button:</label></th>
                <td><?php $this->settings_textinput('text_button_b'); ?> e.g. 'Version B'</td>
              </tr>
              <tr>
                <th scope="row"><label for="text_button_c">Label on C button:</label></th>
                <td>
                  <?php $this->settings_textinput('text_button_c',true); ?> (3-way-comparison only available in the full version)   
                </td>
              </tr>
              <tr>
                <td>
                  <?php wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD ); ?>
                  <br><input type="submit" name="save_settings" value="Save Changes" class="button-primary" />
                </td>
              </tr>
            </table>
          </div>
          <div class="AC_COL50">
            <style id="ACLSTYLE"></style>
            <h2>Styling Helper</h2>
            <table class="<?php $this->eh(self::SHORTCODE)?>_settings">
              <tr>
                <th scope="row"><label for="style_theme">Theme:</label></th>
                <td colspan="4">
                  <select id="style_theme" name="style_theme">
                    <?php
                    foreach (['ac','sk','ss','ep'] as $t) {
                      $d = $themes[$t . '_defaults'];
                      $n = $d['Name'];
                      $p = $d['Pro'];
                      if ($p == '1') $n = "* $n";
                      ?><option value="<?php $this->ea($t)?>" <?php $this->ea( $current_options['style_theme'] == $t ? " selected" : "" )?>><?php $this->ea($n)?></option><?php
                    }
                    ?>
                  </select>
                  <button type="button" id="reset_defaults" class="button-primary">Reset to defaults</button>
                  <span id="clicktoreset" style="visibility: hidden;">&nbsp;Kept your changes. Click to restore the theme defaults.</span>
                </td>
              </tr>
              <tr>
                <th scope="row"><label for="style_color_a">Color A:</label></th>
                <td><input type="color" id="style_color_a" name="style_color_a" value="<?php $this->ea($current_options['style_color_a'])?>"></td>
                <th scope="row"><label for="style_font_size">Font size:</label></th>
                <td><input type="range" id="style_font_size" name="style_font_size" min="10" max="40" value="<?php $this->ea($current_options['style_font_size'])?>"></td>
              </tr>
              <tr>
                <th scope="row"><label for="style_color_b">Color B:</label></th>
                <td><input type="color" id="style_color_b" name="style_color_b" value="<?php $this->ea($current_options['style_color_b'])?>"></td>
                <th scope="row"><label for="style_label_color">Label color:</label></th>
                <td><input type="color" id="style_label_color" name="style_label_color" value="<?php $this->ea($current_options['style_label_color'])?>"></td>
              </tr>
              <tr>
                <th scope="row"><label for="style_color_c">Color C:</label></th>
                <td><input type="color" id="style_color_c" name="style_color_c" value="<?php $this->ea($current_options['style_color_c'])?>"></td>
                <th scope="row"><label for="style_label_width">Label width:</label></th>
                <td><input type="range" id="style_label_width" name="style_label_width" min="30" max="300" value="<?php $this->ea($current_options['style_label_width'])?>"></td>
              </tr>
              <tr>
                <th scope="row"><label for="style_width">Width:</label></th>
                <td><input type="range" id="style_width" name="style_width" min="50" max="250" value="<?php $this->ea($current_options['style_width'])?>"></td>
              </tr>
              <tr>
                <th scope="row"><label for="style_height">Height:</label></th>
                <td><input type="range" id="style_height" name="style_height" min="5" max="100" value="<?php $this->ea($current_options['style_height'])?>"></td>
              </tr>
              <tr>
                <th scope="row"><label for="style_border">Border:</label></th>
                <td><input type="range" id="style_border" name="style_border" min="0" max="20" value="<?php $this->ea($current_options['style_border'])?>"></td>
              </tr>
              <tr>
                <th scope="row"><label for="style_corner">Corner:</label></th>
                <td><input type="range" id="style_corner" name="style_corner" min="0" max="50" value="<?php $this->ea($current_options['style_corner'])?>"></td>
              </tr>
              <tr>
                <td colspan="2">
                  <input type="checkbox" id="apply_style" name="apply_style"<?php $this->ea($current_options['apply_style'] == "on" ? " checked" : "")?>><label for="apply_style" style="font-weight: bold"> Apply style on whole site automatically</label><br>
                </td>
                <td colspan="2">
                  <button type="button" id="copy_to_clipboard" class="<?php $this->ea(self::SHORTCODE)?>_settings button-primary">Copy style to clipboard</button>
                </td>
              </tr>
            </table>
            <p class="<?php $this->ea(self::SHORTCODE)?>_settings">
              <input type="submit" name="save_settings" value="Save Changes" class="button-primary" />
            </p>
            <div class="tryoutarea <?php $this->ea(self::SHORTCODE)?>_settings">
              <p>
                <div class="<?php $this->ea(self::MAIN_DIV)?>">
                  <span class="<?php $this->ea(self::MAIN_DIV)?>-label <?php $this->ea(self::MAIN_DIV)?>-label--before <?php $this->ea(self::MAIN_DIV)?>-label-ready">Ready!</span>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-stop <?php $this->ea(self::MAIN_DIV)?>-play-stop--left" type="button">Play/Stop</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-a" type="button">Version A</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-b" type="button">Version B</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-stop <?php $this->ea(self::MAIN_DIV)?>-play-stop--right" type="button">Play/Stop</button>
                  <span class="<?php $this->ea(self::MAIN_DIV)?>-label <?php $this->ea(self::MAIN_DIV)?>-label--after <?php $this->ea(self::MAIN_DIV)?>-label-ready">Ready!</span>
                </div>
              <p>
                <div class="<?php $this->ea(self::MAIN_DIV)?>">
                  <span class="<?php $this->ea(self::MAIN_DIV)?>-label <?php $this->ea(self::MAIN_DIV)?>-label--before <?php $this->ea(self::MAIN_DIV)?>-output-buffering">Buffering...</span>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-stop <?php $this->ea(self::MAIN_DIV)?>-play-stop--left <?php $this->ea(self::MAIN_DIV)?>-button-buffering" disabled="disabled">Play/Stop</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-a <?php $this->ea(self::MAIN_DIV)?>-button-buffering" disabled="disabled">Version A</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-b <?php $this->ea(self::MAIN_DIV)?>-button-buffering" disabled="disabled">Version B</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-stop <?php $this->ea(self::MAIN_DIV)?>-play-stop--right <?php $this->ea(self::MAIN_DIV)?>-button-buffering" disabled="disabled">Play/Stop</button>
                  <span class="<?php $this->ea(self::MAIN_DIV)?>-label <?php $this->ea(self::MAIN_DIV)?>-label--after <?php $this->ea(self::MAIN_DIV)?>-output-buffering">Buffering...</span>
                </div>
              <p>
                <div class="<?php $this->ea(self::MAIN_DIV)?>">
                  <span class="<?php $this->ea(self::MAIN_DIV)?>-label <?php $this->ea(self::MAIN_DIV)?>-label--before <?php $this->ea(self::MAIN_DIV)?>-output-playing-a">Playing A</span>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-stop <?php $this->ea(self::MAIN_DIV)?>-play-stop--left <?php $this->ea(self::MAIN_DIV)?>-playing" type="button">Play/Stop</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-a <?php $this->ea(self::MAIN_DIV)?>-button-playing" disabled="disabled">Version A</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-b" type="button">Version B</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-stop <?php $this->ea(self::MAIN_DIV)?>-play-stop--right <?php $this->ea(self::MAIN_DIV)?>-playing" type="button">Play/Stop</button>
                  <span class="<?php $this->ea(self::MAIN_DIV)?>-label <?php $this->ea(self::MAIN_DIV)?>-label--after <?php $this->ea(self::MAIN_DIV)?>-output-playing-a">Playing A</span>
                </div>
              <p>
                <div class="<?php $this->ea(self::MAIN_DIV)?>">
                  <span class="<?php $this->ea(self::MAIN_DIV)?>-label <?php $this->ea(self::MAIN_DIV)?>-label--before <?php $this->ea(self::MAIN_DIV)?>-output-playing-b">Playing B</span>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-stop <?php $this->ea(self::MAIN_DIV)?>-play-stop--left <?php $this->ea(self::MAIN_DIV)?>-playing" type="button">Play/Stop</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-a" type="button">Version A</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-b <?php $this->ea(self::MAIN_DIV)?>-button-playing" disabled="disabled">Version B</button>
                  <button class="<?php $this->ea(self::MAIN_DIV)?>-play-stop <?php $this->ea(self::MAIN_DIV)?>-play-stop--right <?php $this->ea(self::MAIN_DIV)?>-playing" type="button">Play/Stop</button>
                  <span class="<?php $this->ea(self::MAIN_DIV)?>-label <?php $this->ea(self::MAIN_DIV)?>-label--after <?php $this->ea(self::MAIN_DIV)?>-output-playing-b">Playing B</span>
                </div>
                <p>
            </div>
            <label for="tryout_backgroundcolor">Tryout background color:&nbsp;</label><input type="color" id="tryout_backgroundcolor" name="tryout_backgroundcolor" style="margin-top: 5px;" value="<?php echo esc_attr($current_options['tryout_backgroundcolor']); ?>">
          </div>
        </div>
      </form>
<style>
  .doc_hide { display: none; }
  #doc_show:checked ~ label + .doc_hide { display: block; }
  #doc_show:checked ~ label > a { display: none; }
</style>
<input id="doc_show" type="checkbox" style="display: none">
<label for="doc_show"><a>Click to show documentation</a></label>
<div class="doc_hide">
      <h2>Shortcode</h2>
      <p>The base version of <?php $this->ea(self::SETTINGS_TITLE)?>'s shortcode is <code>[<?php $this->ea(self::SHORTCODE)?> file_a="PATH/TO/AUDIO.A" file_b="PATH/TO/AUDIO.B"]</code><br>
      This way, all settings from above are used. It's a great way to keep a consistent look throughout your website for all instances of <?php $this->ea(self::SETTINGS_TITLE)?>.<br>
      (Since version 3 you can add <code>file_c="PATH/TO/AUDIO.C"</code> to compare three files 
      - in the full version -
      and you can only use <code>file_a</code> if you want the simplest WordPress Audio Player ever!)</p>
      <p>However, you can give any instance an individual UI. Any option from above is also available as a shortcode attribute. 
        Any attribute not specified in the shortcode will use the site-wide setting from above.</p>
      <table>
      <tr>
          <td><code>text</code></td>
          <td><strong>Output</strong>. Sets the position of the output label. Options: <code>before</code> (before the buttons), <code>after</code> (after the buttons),
          * <code>none</code> (no output label; <a href="https://audiocomparison.kaedinger.de">only available in the full version of Audio Comparison</a>).
          Default: <code>before</code></td>
        </tr>
        <tr>
          <td><code>text_a</code></td>
          <td><strong>Output for A</strong><sup>1,2</sup>. 
          Contents of output label while playing audio A. Default: <code>Now playing A</code></td>
        </tr>
        <tr>
          <td><code>text_b</code></td>
          <td><strong>Output for B</strong><sup>1,2</sup>. 
          Contents of output label while playing audio B. Default: <code>Now playing B</code></td>
        </tr>
        <tr>
          <td><code>text_loading</code></td>
          <td><strong>Output during file load</strong><sup>2</sup>. 
          Contents of output label while buffering audio. Default: <code>Buffering audio...</code></td>
        </tr>
        <tr>
          <td><code>text_ready</code></td>
          <td><strong>Output after file load</strong><sup>2</sup>. 
          Contents of output label after audio has been loaded. Default: <code>Ready!</code></td>
        </tr>
        <tr>
          <td><code>play_button</code></td>
          <td><strong>Play/Stop button</strong>. Sets the position of the Play/Stop button. Options: <code>left</code> (left of the sound buttons), <code>right</code> (right of the sound buttons), 
          * <code>none</code> (no Play/Stop button; <a href="https://audiocomparison.kaedinger.de">only available in the full version of Audio Comparison</a>). 
          Default: <code>left</code></td>
        </tr>
        <tr>
          <td><code>play_default</code></td>
          <td><strong>Play on default</strong>. What sound to play after pressing the Play button. Options: <code>A</code> (sound A), 
          <code>B</code> (sound B).
          Default: <code>A</code></td>
        </tr>
        <tr>
          <td><code>text_button_play</code></td>
          <td><strong>Label on Play/Stop button for Play</strong>. Play/Stop button label while not playing audio. Default: <code>Play</code></td>
        </tr>
        <tr>
          <td><code>text_button_stop</code></td>
          <td><strong>Label on Play/Stop button for Stop</strong>. Play/Stop button label while playing audio. Default: <code>Stop</code></td>
        </tr>
        <tr>
          <td><code>text_button_a</code></td>
          <td><strong>Label on A button</strong>. Text on button A. Default: <code>Version A</code></td>
        </tr>
        <tr>
          <td><code>text_button_b</code></td>
          <td><strong>Label on B button</strong>. Text on button B. Default: <code>Version B</code></td>
        </tr>
      </table>
      <h2>Notes</h2>
      <p>You can use HTML in the label and button texts, like '&lt;i>Play&lt;/i>'.</p>
      <sup>1</sup> You can use the placeholder <code>%T</code> for putting the current position (format m:ss) in the text.<br>
      <sup>2</sup> Empty texts are available in <a href="https://audiocomparison.kaedinger.de">the full version of Audio Comparison</a>.
      <h2>Design</h2>
      <p>You can use the following CSS classes to fine tune the visual style of the label.</p>
      <h4>Static</h4>
      <table>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?></code></td>
          <td>The whole instance <code>div</code>.</td>
        </tr>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-play-stop</code></td>
          <td>Play/Stop button.</td>
        </tr>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-play-a</code></td>
          <td>Button A.</td>
        </tr>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-play-b</code></td>
          <td>Button B.</td>
        </tr>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-label</code></td>
          <td>Output label.</td>
        </tr>
      </table>
      <h4>Dynamic</h4>
      <table>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-output-buffering</code></td>
          <td>Assigned to the output label while audio is still loading.</td>
        </tr>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-output-playing-a</code></td>
          <td>Assigned to the output label while audio A is playing.</td>
        </tr>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-output-playing-b</code></td>
          <td>Assigned to the output label while audio B is playing.</td>
        </tr>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-button-buffering</code></td>
          <td>Assigned to all buttons during audio loading.</td>
        </tr>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-button-playing</code></td>
          <td>Assigned to the A or B button while the respective audio is playing.</td> 
        </tr>
        <tr>
          <td><code>.<?php $this->ea(self::MAIN_DIV)?>-playing</code></td>
          <td>Assigned to the Play/Stop button while audio is playing.</td>
        </tr>
        <tr>
          <td><code>disabled="disabled"</code></td>
          <td >Buttons that cannot be clicked<br>(e.g. Play/Stop 
            button during buffering, button A while playing A, etc.)<br>
            have the <code>disabled</code> attribute set.</td>
        </tr>
      </table>
      <a href="https://audiocomparison.kaedinger.de/docs/" target="ACDOCS">Full documentation</a>
</div>
    </div>
      <?php
    }
    public function admin_init() {
      $this->load_assets();
    }
    public function load_assets() {
    }
  }
  new audioComparisonLite();
}
