<?php
if( ! class_exists( 'audioComparisonLitePromotionChecker' ) ) {
  class audioComparisonLitePromotionChecker {
public $cache_key;
public $cache_allowed;
public function __construct() {
  $this->cache_key = 'audio_comparison_lite_info';
  $this->cache_key_failed = 'audio_comparison_lite_info_failed';
  $this->cache_allowed = true;    
}
function request() {
  $failed_before = get_transient( $this->cache_key_failed );
  if( ! ( false === $failed_before ) ) {
    return false;
  }
	$remote = get_transient( $this->cache_key );
	if( false === $remote || ! $this->cache_allowed ) {
		$remote = wp_remote_get(
				'https://assets.kaedinger.de/static/ACL/infoACL.json', 
				array(
          'timeout' => 5,
          'headers' => array(
            'Accept' => 'application/json'
          )
				)
		);
    if(
        is_wp_error( $remote )
        || 200 !== wp_remote_retrieve_response_code( $remote )
        || empty( wp_remote_retrieve_body( $remote ) )
    ) {
      set_transient( $this->cache_key_failed, "failed", DAY_IN_SECONDS / 2 );
			return false;
		}
		set_transient( $this->cache_key, $remote, DAY_IN_SECONDS );
	}
	$remote = json_decode( wp_remote_retrieve_body( $remote ), true );
	return $remote;
}
public function get_links() {
  $res = [];
  $remote = $this->request();
  if( ! $remote ) {
    return $res;
  }
  if(array_key_exists('numlinks', $remote)) {
    for($i = 0; $i < $remote['numlinks']; $i++) {
      if(array_key_exists("link$i", $remote)) {
        $entry = $remote["link$i"];
        $now = new DateTime();
        $date = DateTime::createFromFormat('Y-m-d', $entry["from"]);
        if($date > $now) continue;
        $date = DateTime::createFromFormat('Y-m-d', $entry["to"]);
        if($date < $now) continue;
        $res[] = $entry["link"];
      }
    }
  }
	return $res;
}
}
}
