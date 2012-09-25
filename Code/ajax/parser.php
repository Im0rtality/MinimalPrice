<?php
    require_once("../Shared/utils.php");
	require("../config.php");
	require("../Classes/class.mysql.php");
	require("../Classes/class.remote-reader.php");
	require("../Classes/class.template-manager.php");
	//require("../Classes/class.shop-parser.php");
	require("../Parsers/parser.skytechlt.php");
	
	$db = new MySql();

	$reader = new RemoteReader();
	$reader->SetUserAgent("Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120403211507 Firefox/12.0");

	$parser = new SimpleShopParser($db, $reader);
	
	$parser->GetContent($_GET['url']);
	$parser->Load();
	$parser->Parse();
	
	//debug($parser->GetData());
?>