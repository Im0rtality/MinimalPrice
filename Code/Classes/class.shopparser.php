<?php
	class ShopParser{
		protected $Hostname;	
		
		public $LastError;				
		private $DBLink;
		
		function ShopParser($DB){
			$this->DBLink = $DB;
		}
		
		function GetContents($PageUrl) {
		
		}
?>