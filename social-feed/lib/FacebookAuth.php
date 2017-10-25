<?php

class FacebookAuth extends Extension {

	public $profile_id;
	public $app_id;
	public $app_secret;
	
	function __construct() {}
	
	function fetchUrl($url){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

		$feedData = curl_exec($ch);
		curl_close($ch); 

		return $feedData;
	}  

	function get() {
		
		// load site config (contains API keys etc)
		$siteConfig = SiteConfig::current_site_config();

		$url = 'https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id='.$siteConfig->SocialFeedFacebookAppID.'&client_secret='.$siteConfig->SocialFeedFacebookAppSecret;
		//Retrieve auth token
		$authTokenResp = $this->fetchUrl($url);
		
		$authTokenJson = json_decode( $authTokenResp, true );
		
		// check if feed getter failed
		if( isset( $authTokenJson['error'] ) ){
			return $authTokenResp;
		}
		
		$authToken = $authTokenJson['access_token'];
		$url = 'https://graph.facebook.com/'.$siteConfig->SocialFeedFacebookPageID.'/feed?access_token='.$authToken;
		
		$json_object = $this->fetchUrl($url);
				
		return $json_object;
	}
	
}
