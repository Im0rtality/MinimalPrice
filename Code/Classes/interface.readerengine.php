<?php
	interface ReaderEngine{
		public function Get($Url);
		public function Post($Url);
		public function SetUserAgent($Agent);	
		public function SetOptions($Options);	
	}
?>