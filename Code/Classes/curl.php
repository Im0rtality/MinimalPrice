<?php
    include("Interfaces/reader-engine.php");
	
	class cURL implements ReaderEngine{
		private $h;
		
		function cURL() {
			$h = curl_init();
			curl_setopt($h, CURLOPT_RETURNTRANSFER, true);
		}
		
		function Get($Url) {
			curl_setopt($h, 'CURLOPT_URL', $Url);
			return curl_exec($ch);
		}
		
		function Post($Url) {
			curl_setopt($h, 'CURLOPT_POST', 1);
			curl_setopt($h, 'CURLOPT_URL', $Url);
			return curl_exec($ch);
		}
		
		function SetUserAgent($Agent) {
			curl_setopt($h, 'USERAGENT', $Agent);
		}

		function SetOptions($Options) {
			foreach ($Options as $O) {
				curl_setopt($h, $O[0], $O[1]);
			}
		}
	}
?>