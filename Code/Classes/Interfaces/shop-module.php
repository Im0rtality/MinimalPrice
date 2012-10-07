<?php
	include ("interface.module.php");
	
	interface ShopModule extends Module{
			function SetHomepage($Url);
			function GetHomepage();
			
			function SetParser($Parser);
			function GetParser();			
	}
?>