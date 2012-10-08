<?php
    require_once(dirname(__FILE__) . "/../../Code/Shared/utils.php");
	require_once(dirname(__FILE__) . "/../../Code/config.php");
	require_once(dirname(__FILE__) . "/../../Code/Classes/remote-reader.php");
	require_once(dirname(__FILE__) . "/../../Code/Parsers/parser.skytechlt.php");
	
	$reader = new RemoteReader();
	$reader->SetUserAgent("Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120403211507 Firefox/12.0");

	// SimpleShopParser class comes from Code/Parsers/parser.skytechlt.php
	$parser = new SimpleShopParser($reader);
	
	if ((isset($_GET['url'])) && (!empty($_GET['url']))) {
		$parser->parseCategory($_GET['url']);
		debug($parser->GetData());
	} else {
		die("Cannot parse: URL paremeter is empty or not set");
	}
?>