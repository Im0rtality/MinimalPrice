<?php
	include("../config.php");
	include("../Classes/class.mysql.php");
	include("../Classes/class.remotereader.php");
	include("../Classes/class.shopparser.php");
	
	$db = new MySql();
	$reader = new RemoteReader();
	$reader->SetUserAgent("Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120403211507 Firefox/12.0");
	$parser = new ShopParser($db, $reader);
	
	$parser->GetContent($_GET['url']);
	$parser->ExtractData();
	
	echo '<pre>' . print_r($parser->GetOutput(), true) . '</pre>';
?>