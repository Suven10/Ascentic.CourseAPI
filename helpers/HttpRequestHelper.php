<?php
class HttpRequestHelper {
	
	public static function Post($data_string, $url, $headers) {
		$ch = curl_init ($url);
		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data_string );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec ( $ch );
		return $result;
	}
	
	public static function Get($url, $headers) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec ( $ch );
		return $content;
	}
}

?>