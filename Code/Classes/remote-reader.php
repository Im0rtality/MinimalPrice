<?php
    include("Interfaces/reader-engine.php");
	
	class RemoteReader implements ReaderEngine{
		private $UserAgent = "Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120403211507 Firefox/12.0";
		
		function RemoteReader() {

		}
		
		function Get($url) {
			// get the host name and url path
			$parsedUrl = parse_url($url);
			$host = $parsedUrl['host'];
			if (isset($parsedUrl['path'])) {
			   $path = $parsedUrl['path'];
			} else {
			   // the url is pointing to the host like http://www.mysite.com
			   $path = '/';
			}

			if (isset($parsedUrl['query'])) {
			   $path .= '?' . $parsedUrl['query'];
			} 

			if (isset($parsedUrl['port'])) {
			   $port = $parsedUrl['port'];
			} else {
			   // most sites use port 80
			   $port = '80';
			}

			$timeout = 10;
			$response = ''; 

			// connect to the remote server 
			$fp = @fsockopen($host, '80', $errno, $errstr, $timeout ); 

			if( !$fp ) { 
			   echo "Cannot retrieve $url";
			} else {
			   // send the necessary headers to get the file 
			   fputs($fp, "GET $path HTTP/1.0\r\n" .
						  "Host: $host\r\n" .
						  "User-Agent: {$this->UserAgent}\r\n" .
						  "Accept: */*\r\n" .
						  "Accept-Language: en-us,en;q=0.5\r\n" .
						  "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
						  "Keep-Alive: 300\r\n" .
						  "Connection: keep-alive\r\n" .
						  "Referer: http://$host\r\n\r\n");

			  // retrieve the response from the remote server 
			   while ( $line = fread( $fp, 4096 ) ) { 
				  $response .= $line;
			   } 

			   fclose( $fp );

			   // strip the headers
			   $pos      = strpos($response, "\r\n\r\n");
			   $response = substr($response, $pos + 4);
			}

			// return the file content 
			return $response;
		}
		
		function Post($Url) {

		}
		
		function SetUserAgent($Agent) {
			$this->UserAgent = $Agent;
		}

		function SetOptions($Options) {

		}
	}
?>