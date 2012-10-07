<?php
	interface ShopParser{
		public function __construct(); // args will be added later
		function SetOptions($Options);
		function GetOptions();
		private function ReadyCheck();
		function Parse();
	}
?>