var AudioComparisonLiteNS = AudioComparisonLiteNS || {};
AudioComparisonLiteNS._construct = function() {
const DE = sessionStorage.getItem("ACL_DEBUG") == "true";
if(typeof BUG != 'function'){
  window.BUG = function(msg){console.debug(msg);};
}
var AC_audio_groups = [];
const Product = 'audio-comparison-lite'; 
const Prefix = '.' + Product;
const AC_SEL_GROUP    = Prefix;
const AC_SEL_FILE_A   = Prefix + '-file-a';
const AC_SEL_FILE_B   = Prefix + '-file-b';
const AC_SEL_BTN_PLAY = Prefix + '-play-stop';
const AC_SEL_BTN_A    = Prefix + '-play-a';
const AC_SEL_BTN_B    = Prefix + '-play-b';
const AC_SEL_OUTPUT   = Prefix + '-label';
const AC_CLS_LABEL_BUFFERING  = Product + '-output-buffering';
const AC_CLS_BTN_BUFFERING    = Product + '-button-buffering';
const AC_CLS_BTN_PLAYING      = Product + '-button-playing';
const AC_CLS_PLAYING          = Product + '-playing';
const AC_CLS_LABEL_PLAYING_A  = Product + '-output-playing-a';
const AC_CLS_LABEL_PLAYING_B  = Product + '-output-playing-b';
const AC_DATASET_BUFFERING_TEXT   = 'bufferingText';
const AC_DATASET_BUFFERED_TEXT    = 'bufferedText';
const AC_DATASET_PLAY_DEFAULT     = 'playDefault';
const AC_DATASET_BUTTON_PLAY_TEXT = 'buttonPlayText';
const AC_DATASET_BUTTON_STOP_TEXT = 'buttonStopText';
const AC_DATASET_PLAYING_A_TEXT   = 'playingAText';
const AC_DATASET_PLAYING_B_TEXT   = 'playingBText';
jQuery(document).ready(async function() {
  DE&&BUG('Audio Comparison Lite\n' +
          '---------------------\n' +
          'c.2023 kaedinger\n');
  var navigator = (typeof window !== 'undefined' && window.navigator) ? window.navigator : null;
  var iOS = (/iP(hone|od|ad)/.test(navigator && navigator.platform));
  var appVersion = navigator && navigator.appVersion.match(/OS (\d+)_(\d+)_?(\d+)?/);
  var version = appVersion ? parseInt(appVersion[1], 10) : null;
  var html5 = (iOS && version && version > 13);
  DE&&BUG("iOS " + iOS + ", appVersion " + appVersion + ", version " + version + ", html5 " + html5);
  var groups = jQuery(AC_SEL_GROUP);
  DE&&BUG('Comparison groups found: ' + groups.length);
  jQuery.each(groups, function(gid, group) {
    DE&&BUG('Loading group ' + gid);
    var btnPlay = AC_loadButton(group, AC_SEL_BTN_PLAY);
    var btnA = AC_loadButton(group, AC_SEL_BTN_A);
    var btnB = AC_loadButton(group, AC_SEL_BTN_B);
    if( btnA == null || btnPlay == null ) {
      DE&&BUG('Button A or Play missing. Bailing out.');
      return;
    }
    var soundA = AC_loadAudioFile(group, AC_SEL_FILE_A, html5);
    if( soundA == null ) return;
    var soundB = btnB != null ? AC_loadAudioFile(group, AC_SEL_FILE_B, html5) : null;
    var audio_group = {
      jgroup: group,
      btnPlay: btnPlay,
      btnA: btnA,
      btnB: btnB,
      audioA_sound: soundA,
      audioB_sound: soundB,
      lastSeekPos: 0,
      displayTimer: -1
    };
    AC_audio_groups.push(audio_group);
    AC_handleBufferingState(audio_group);
    soundA.once('load', function() {
      DE&&BUG('Group ' + gid + ' Sound A has loaded');
      AC_handleBufferingState(audio_group);
    });
    soundB?.once('load', function() {
      DE&&BUG('Group ' + gid + ' Sound B has loaded');
      AC_handleBufferingState(audio_group);
    });
  });
  if( AC_audio_groups === null || AC_audio_groups.length <= 0 ) {
    DE&&BUG('No usable comparison groups found!');
    return;
  }
  AudioComparison();
});
function AC_loadAudioFile(group, selector, useHtml5) {
  var selName = selector.substring(1);
  var audio = jQuery(group).find(selector);
  if( audio === null || audio.length <= 0 ) {
    DE&&BUG('No file ' + selName + ' found!');
    return null;
  } else if( audio.length > 1 ) {
    DE&&BUG('Too many files ' + selName + ' found!');
    return null;
  }
  var sound;
  if( audio.is('a') ) {
    sound = audio.attr('href');
    if( !sound ) {
      DE&&BUG('Missing href in link ' + selName);
      return null;
    }
  } else {
    DE&&BUG('No audio file found in ' + selName);
    return null;
  }
  DE&&BUG(selName + ': file ' + sound);
  DE&&BUG("Use html5: " + useHtml5); 
  sound = new Howl({
    src: [sound],
    volume: 1, 
    loop: true,
    html5: useHtml5,
    onload: function() { DE&&BUG(selName + ' onload'); },
    onloaderror: function(id, err) { DE&&BUG(selName + ' onloaderror, err=' + err); },
  });
  return sound;
}
function AC_loadButton(group, selector) {
  var btn = jQuery(group).find(selector);
  if( btn === null || btn.length <= 0 ) {
    DE&&BUG('No button ' + selector.substring(1) + 'found!');
    return null;
  }
  return btn;
}
function AC_handleBufferingState(audiogroup) {
  var soundA = audiogroup.audioA_sound;
  var soundB = audiogroup.audioB_sound;
  var buffered = (soundA.state() === 'loaded')
                 && (soundB != null ? (soundB.state() === 'loaded') : true)
                 ;
  var label = jQuery(audiogroup.jgroup).find(AC_SEL_OUTPUT);
  if( !buffered ) {
    label
      .html(audiogroup.jgroup.dataset[AC_DATASET_BUFFERING_TEXT] || '')
      .addClass(AC_CLS_LABEL_BUFFERING);
        audiogroup.btnPlay.addClass(AC_CLS_BTN_BUFFERING);
        audiogroup.btnPlay.attr('disabled', true);
      audiogroup.btnA.addClass(AC_CLS_BTN_BUFFERING);
      audiogroup.btnB?.addClass(AC_CLS_BTN_BUFFERING);
      audiogroup.btnA.attr('disabled', true);
      audiogroup.btnB?.attr('disabled', true);
  } else {
    label
      .html(audiogroup.jgroup.dataset[AC_DATASET_BUFFERED_TEXT] || '')
      .removeClass(AC_CLS_LABEL_BUFFERING);
      audiogroup.btnA.removeClass(AC_CLS_BTN_BUFFERING);
      audiogroup.btnB?.removeClass(AC_CLS_BTN_BUFFERING);
      audiogroup.btnPlay.removeClass(AC_CLS_BTN_BUFFERING);
      audiogroup.btnPlay.attr('disabled', false);
  }
}
function AudioComparison() {
  jQuery.each(AC_audio_groups, function(aid, audio) {
      DE&&BUG('Setting group ' + aid + ' play button click handlers');
      jQuery.each(audio.btnPlay, function(bid, btn) {
        jQuery(btn).click({group: aid, btn: bid}, AC_btnPlay_Clicked);
      });
      DE&&BUG('Setting group ' + aid + ' A button click handlers');
      jQuery.each(audio.btnA, function(bid, btn) {
        jQuery(btn).click({group: aid, btn: bid}, AC_btnA_Clicked);
      });
      DE&&BUG('Setting group ' + aid + ' B button click handlers');
      jQuery.each(audio.btnB, function(bid, btn) {
        jQuery(btn).click({group: aid, btn: bid}, AC_btnB_Clicked);
      });
  });
}
function AC_btnPlay_Clicked(event) {
  DE&&BUG('Group ' + event.data.group + ' play button ' + event.data.btn + ' was clicked');
  var gid = event.data.group;
  var group = AC_audio_groups[gid];
  let playTrack = group.jgroup.dataset[AC_DATASET_PLAY_DEFAULT].toLowerCase() || 'a';
  if (playTrack == 'b' && group.btnB == null) playTrack = 'a';
  if( AC_isGroupPlaying(group) ) {
    AC_handleStopGroup(group);
  } else {
      group.btnPlay.html(group.jgroup.dataset[AC_DATASET_BUTTON_STOP_TEXT] || 'Stop');
      group.btnPlay.addClass(AC_CLS_PLAYING);
    AC_switchTracks(group, playTrack);
  }
}
function AC_isGroupPlaying(group, except = '') {
  return( (group.audioA_sound.playing() && except != 'a')
       || (group.audioB_sound?.playing() && except != 'b')
  );
}
function AC_handleStopGroup(group) {
  AC_stopGroup(group);
  group.audioA_sound.off('end');
    group.btnPlay.html(group.jgroup.dataset[AC_DATASET_BUTTON_PLAY_TEXT] || 'Play');
    group.btnA.attr('disabled','true');
    group.btnB?.attr('disabled','true');
    group.btnPlay.removeClass(AC_CLS_PLAYING);
  group.btnA.removeClass(AC_CLS_BTN_PLAYING);
  group.btnB?.removeClass(AC_CLS_BTN_PLAYING);
  jQuery(group.jgroup).find(AC_SEL_OUTPUT)
    .text('').removeClass(AC_CLS_LABEL_PLAYING_A).removeClass(AC_CLS_LABEL_PLAYING_B)
  ;
}
function AC_switchTracks(group, playTrack) {
  DE&&BUG('Switch tracks, playTrack = ' + playTrack);
  AC_stopGroup(group);
  if( playTrack == 'a' ) {
    AC_playA(group);
  }
  if( playTrack == 'b' ) {
    AC_playB(group);
  }
}
function AC_btn_Clicked(event, button) {
  DE&&BUG('Group ' + event.data.group + ' button ' + button.toUpperCase() + ' ' + event.data.btn + ' was clicked');
  var group = AC_audio_groups[event.data.group];
    AC_switchTracks(group, button.toLowerCase());
}
function AC_btnA_Clicked(event) {
  AC_btn_Clicked(event, 'a');
}
function AC_btnB_Clicked(event) {
  AC_btn_Clicked(event, 'b');
}
function AC_playA(group) {
    group.btnA.attr('disabled','true');
    group.btnB?.removeAttr('disabled');
  group.btnB?.removeClass(AC_CLS_BTN_PLAYING);
  group.btnA.addClass(AC_CLS_BTN_PLAYING);
  jQuery(group.jgroup).find(AC_SEL_OUTPUT)
    .addClass(AC_CLS_LABEL_PLAYING_A).removeClass(AC_CLS_LABEL_PLAYING_B)
  ;
  group.audioA_sound.seek(group.lastSeekPos);
  group.audioA_sound.play();
   AC_displayPlayingTextHelper(group, group.jgroup.dataset[AC_DATASET_PLAYING_A_TEXT] || '', group.lastSeekPos);
   group.displayTimer = setInterval(AC_displayPlayingText, 100, group);
}
function AC_playB(group) {
    group.btnB.attr('disabled','true');
    group.btnA.removeAttr('disabled');
  group.btnA.removeClass(AC_CLS_BTN_PLAYING);
  group.btnB.addClass(AC_CLS_BTN_PLAYING);
  jQuery(group.jgroup).find(AC_SEL_OUTPUT)
    .addClass(AC_CLS_LABEL_PLAYING_B).removeClass(AC_CLS_LABEL_PLAYING_A)
  ;
  group.audioB_sound.seek(group.lastSeekPos);
  group.audioB_sound.play();
  AC_displayPlayingTextHelper(group, group.jgroup.dataset[AC_DATASET_PLAYING_B_TEXT] || '', group.lastSeekPos);
  group.displayTimer = setInterval(AC_displayPlayingText, 100, group);
}
function AC_stopGroup(group) {
  if( group.audioA_sound.playing() ) {
    group.lastSeekPos = group.audioA_sound.seek();
    group.audioA_sound.stop();
  } 
  if( group.audioB_sound != null && group.audioB_sound.playing() ) {
    group.lastSeekPos = group.audioB_sound.seek();
    group.audioB_sound.stop();
  }
  if(group.displayTimer > -1) {
    clearInterval(group.displayTimer);
    group.displayTimer = -1;
  }
}
function AC_displayPlayingText(group) {
  let text = '';
  let seek = 0;
  if( group.audioA_sound.playing() ) {
    seek = group.audioA_sound.seek();
    text = group.jgroup.dataset[AC_DATASET_PLAYING_A_TEXT] || '';
  } 
  if( group.audioB_sound != null && group.audioB_sound.playing() ) {
    seek = group.audioB_sound.seek();
    text = group.jgroup.dataset[AC_DATASET_PLAYING_B_TEXT] || '';
  }
  AC_displayPlayingTextHelper(group,text,seek);
}
function AC_displayPlayingTextHelper(group,text,seek) {
  let pos = (seek < 10*60) ? 4 : 3;
  let dtime = (new Date(0,0,0,0,0,seek)).toTimeString().substring(pos,8);
  jQuery(group.jgroup).find(AC_SEL_OUTPUT)
    .html(text.replace("%T", dtime));
}
}
AudioComparisonLiteNS._construct();
