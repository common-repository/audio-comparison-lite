var AudioComparisonLiteStylerNS = AudioComparisonLiteStylerNS || {};
AudioComparisonLiteStylerNS._construct = function() {
const Lite = "-lite";
var Themes = window.AUDIOCOMPARISONLITE['themes'];
var MAIN_DIV = '.' + window.AUDIOCOMPARISONLITE['MAIN_DIV'];
const DE = sessionStorage.getItem("ACL_DEBUG") == "true";
if(typeof BUG != 'function'){
  window.BUG = function(msg){console.debug(msg);};
}
var Theme;
var ColA;
var ColB;
var ColC;
var Width;
var Height;
var Border;
var Corner;
var FontSize;
var LabelColor;
var LabelWidth;
var ContrastDefault = parseInt(Themes['ContrastDefault']);
var LightenDarkenAmmount = parseInt(Themes['LightenDarkenAmmount']);
var UserChanged = false;
jQuery(document).ready(async function($) {
  DE&&BUG("Styler init");
  $("#reset_defaults").click(function() { 
    udpate_defaults(Theme); 
    $("#clicktoreset").css('visibility', 'hidden');
  } );
  $("#style_theme").on("input", function(event) { 
    Theme = event.target.value; 
    if(UserChanged) {
      update(true); 
      $("#clicktoreset").css('visibility', 'visible');
    } else
      udpate_defaults(Theme);
  } );
  Theme = $("#style_theme").val();
  var defs = Themes[Theme + '_defaults'];
  $("#style_color_a").on("input", function(event) { ColA = event.target.value; update(true); } );
  ColA = $("#style_color_a").val();
  UserChanged = UserChanged || (ColA != defs['ColA']);
  $("#style_color_b").on("input", function(event) { ColB = event.target.value; update(true); } );
  ColB = $("#style_color_b").val();
  UserChanged = UserChanged || (ColB != defs['ColB']);
  $("#style_color_c").on("input", function(event) { ColC = event.target.value; update(true); } );
  ColC = $("#style_color_c").val();
  UserChanged = UserChanged || (ColC != defs['ColC']);
  $("#style_width").on("input", function(event) { Width = event.target.value; update(true); } );
  Width = $("#style_width").val();
  UserChanged = UserChanged || (Width != defs['Width']);
  $("#style_height").on("input", function(event) { Height = event.target.value; update(true); } );
  Height = $("#style_height").val();
  UserChanged = UserChanged || (Height != defs['Height']);
  $("#style_border").on("input", function(event) { Border = event.target.value; update(true); } );
  Border = $("#style_border").val();
  UserChanged = UserChanged || (Border != defs['Border']);
  $("#style_corner").on("input", function(event) { Corner = event.target.value; update(true); } );
  Corner = $("#style_corner").val();
  UserChanged = UserChanged || (Corner != defs['Corner']);
  $("#style_font_size").on("input", function(event) { FontSize = event.target.value; update(true); } );
  FontSize = $("#style_font_size").val();
  UserChanged = UserChanged || (FontSize != defs['FontSize']);
  $("#style_label_color").on("input", function(event) { LabelColor = event.target.value; update(true); } );
  LabelColor = $("#style_label_color").val();
  UserChanged = UserChanged || (LabelColor != defs['LabelColor']);
  $("#style_label_width").on("input", function(event) { LabelWidth = event.target.value; update(true); } );
  LabelWidth = $("#style_label_width").val();
  UserChanged = UserChanged || (LabelWidth != defs['LabelWidth']);
  $("#tryout_backgroundcolor").on("input", function(event) { Tryout_Background = event.target.value; update(UserChanged); } );
  Tryout_Background = $("#tryout_backgroundcolor").val();
  $("#copy_to_clipboard").on("click", function() { 
    if (Themes[Theme + '_defaults']['Pro'] == '1') {
      var hint = Themes[Theme + '_defaults']['Name'] + ' is a theme of the full version of Audio Comparison, available on https:\/\/audiocomparison.kaedinger.de, with many more themes and features!';
      navigator.clipboard.writeText(hint);
    } else
    navigator.clipboard.writeText(generate_css());
  });
  use_from_settings("#text_button_a", MAIN_DIV + "-play-a");
  use_from_settings("#text_button_b", MAIN_DIV + "-play-b");
  use_from_settings("#text_button_play", MAIN_DIV + "-play-stop:not(" + MAIN_DIV + "-playing)");
  use_from_settings("#text_button_stop", MAIN_DIV + "-playing");
  use_from_settings("#text_ready", MAIN_DIV + "-label-ready");
  use_from_settings("#text_loading", MAIN_DIV + "-output-buffering");
  use_from_settings("#text_a", MAIN_DIV + "-output-playing-a");
  use_from_settings("#text_b", MAIN_DIV + "-output-playing-b");
  use_from_settings_3state("#text", MAIN_DIV + "-label", "before", "after");
  use_from_settings_3state("#play_button", MAIN_DIV + "-play-stop", "left", "right");
  update(UserChanged);
});
function use_from_settings(id,target) {
  jQuery(id).on("input", function(event) { display_from_text(target,event.target.value); }); 
  display_from_text(target,jQuery(id).val());
}
function display_from_text(target,text) {
  jQuery(target).each(function() { 
    jQuery(this).html(text.replace("%T", "0:12"));
  });
}
function use_from_settings_3state(id,target,state_a,state_b) {
  jQuery(id).on("input", function(event) { display_only(target,state_a,state_b,event.target.value); });
  display_only(target,state_a,state_b,jQuery(id).val());
}
function display_only(target,state_a,state_b,state_select) {
  jQuery(target + "--" + state_a).each(function () { state_select == state_a ? jQuery(this).show() : jQuery(this).hide(); })
  jQuery(target + "--" + state_b).each(function () { state_select == state_b ? jQuery(this).show() : jQuery(this).hide(); })
}
function update(user) {
  UserChanged = user;
  var style = '.tryoutarea { ' + 
                  'background-color: ' + Tryout_Background + '; ' +
                  'border: 2px solid ' + ContrastTo(Tryout_Background, ContrastDefault) + '; ' +
                  'padding: 0px 10px; } ' +
              generate_css();
  jQuery("#tryout_notes").css('color', ContrastTo(Tryout_Background, ContrastDefault));
  jQuery("#ACLSTYLE").text(style);
}
function generate_css() {
  const ContrastBias = parseInt(Themes[Theme + '_defaults']['ContrastBias']);
  const Contrast = ContrastDefault + ContrastBias;
  const Lighter = LightenDarkenAmmount;
  const Darker = -LightenDarkenAmmount;
  var ContrastToA = ContrastTo(ColA, Contrast);
  var ContrastToB = ContrastTo(ColB, Contrast);
  var ContrastToC = ContrastTo(ColC, Contrast);
  var HoverColA = LightenDarkenColor(ColA, Brightness(ColA) > Contrast ? Darker : Lighter);
  var HoverColB = LightenDarkenColor(ColB, Brightness(ColB) > Contrast ? Darker : Lighter);
  var HoverColC = LightenDarkenColor(ColC, Brightness(ColC) > Contrast ? Darker : Lighter);
  var HoverContrastToA = LightenDarkenColor(ContrastToA, Brightness(ContrastToA) > Contrast ? Darker : Lighter);
  var HoverContrastToB = LightenDarkenColor(ContrastToB, Brightness(ContrastToB) > Contrast ? Darker : Lighter);
  var HoverContrastToC = LightenDarkenColor(ContrastToC, Brightness(ContrastToC) > Contrast ? Darker : Lighter);
  var MixAandB = MixColors(ColA, ColB);
  var ContrastToMixAandB = ContrastTo(MixAandB, Contrast);
  var HoverMixAandB = LightenDarkenColor(MixAandB, Brightness(MixAandB) > Contrast ? Darker : Lighter);
  var HoverContrastToMixAandB = LightenDarkenColor(ContrastToMixAandB, Brightness(ContrastToMixAandB) > Contrast ? Darker : Lighter);
  var style =
    Themes[Theme]
      .replaceAll("$HoverColA", HoverColA)
      .replaceAll("$HoverColB", HoverColB)
      .replaceAll("$HoverColC", HoverColC)
      .replaceAll("$HoverContrastToA", HoverContrastToA)
      .replaceAll("$HoverContrastToB", HoverContrastToB)
      .replaceAll("$HoverContrastToC", HoverContrastToC)
      .replaceAll("$ColA", ColA)
      .replaceAll("$ColB", ColB)
      .replaceAll("$ColC", ColC)
      .replaceAll("$ContrastToA", ContrastToA)
      .replaceAll("$ContrastToB", ContrastToB)
      .replaceAll("$ContrastToC", ContrastToC)
      .replaceAll("$Width", Width)
      .replaceAll("$Height", Height)
      .replaceAll("$Border", Border)
      .replaceAll("$Corner", Corner)
      .replaceAll("$FontSize", FontSize)
      .replaceAll("$LabelColor", LabelColor)
      .replaceAll("$LabelWidth", LabelWidth)
      .replaceAll("$MixAandB", MixAandB)
      .replaceAll("$ContrastToMixAandB", ContrastToMixAandB)
      .replaceAll("$HoverMixAandB", HoverMixAandB)
      .replaceAll("$HoverContrastToMixAandB", HoverContrastToMixAandB)
  ;
  return style;
}
function udpate_defaults(theme) {
  var defs = Themes[theme + '_defaults'];
  jQuery("#style_color_a").val(ColA = defs['ColA']);
  jQuery("#style_color_b").val(ColB = defs['ColB']);
  jQuery("#style_color_c").val(ColC = defs['ColC']);
  jQuery("#style_width").val(Width = defs['Width']);
  jQuery("#style_height").val(Height = defs['Height']);
  jQuery("#style_border").val(Border = defs['Border']);
  jQuery("#style_corner").val(Corner = defs['Corner']);
  jQuery("#style_font_size").val(FontSize = defs['FontSize']);
  jQuery("#style_label_color").val(LabelColor = defs['LabelColor']);
  jQuery("#style_label_width").val(LabelWidth = defs['LabelWidth']);
  update(false);
}
function LightenDarkenColor(col,amt) {
  const rgb = RGBfrom(col);
  var r = rgb[0] + amt;
  if ( r > 255 ) r = 255;
  else if  (r < 0) r = 0;
  var b = rgb[2] + amt;
  if ( b > 255 ) b = 255;
  else if  (b < 0) b = 0;
  var g = rgb[1] + amt;
  if ( g > 255 ) g = 255;
  else if  ( g < 0 ) g = 0;
  return ToRGB(col,r,g,b);
}
function ContrastTo(col,contrast) {
  const brightness = Brightness(col);
  var r = 0;
  var b = 0;
  var g = 0;
  if (brightness <= contrast) {
    r = g = b = 255;
  }
  return ToRGB(col,r,g,b);
}
function Brightness(col) {
  const rgb = RGBfrom(col);
  return Math.round(((rgb[0] * 299) + (rgb[1] * 587) + (rgb[2] * 114)) / 1000);
}
function MixColors(a,b) {
  const rgbA = RGBfrom(a);
  const rgbB = RGBfrom(b);
  return ToRGB(a,Math.floor((rgbA[0] + rgbB[0])/2),Math.floor((rgbA[1] + rgbB[1])/2),Math.floor((rgbA[2] + rgbB[2])/2));
}
function RGBfrom(col) {
  if ( col[0] == "#" ) {
      col = col.slice(1);
  }
  const num = parseInt(col,16);
  const r = (num >> 16);
  const g = ((num >> 8) & 0x00FF);
  const b = (num & 0x0000FF);
  return [ r,g,b ];
}
function ToRGB(col,r,g,b) {
  const s = ('00000' + (b | (g << 8) | (r << 16)).toString(16));
  return (( col[0] == "#" )?"#":"") + s.substring(s.length - 6);
}
}
AudioComparisonLiteStylerNS._construct();
