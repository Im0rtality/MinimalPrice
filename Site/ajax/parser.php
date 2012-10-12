<?php
	require_once(dirname(__FILE__) . "/../../Code/Shared/utils.php");
	require_once(dirname(__FILE__) . "/../../Code/config.php");
	require_once(dirname(__FILE__) . "/../../Code/Classes/remote-reader.php");
	require_once(dirname(__FILE__) . "/../../Code/Parsers/parser.simple-shop-parser.php");

	function checkParameter($key) {
		if ((!isset($_GET[$key])) || (empty($_GET[$key]))) {
			die("Cannot parse: `{$key}` paremeter is empty or not set");
		}
	}
	
	// check if any parameter is missing
	checkParameter('url');
	checkParameter('shop');

	$parser = new SimpleShopParser($_GET['shop']);
	
	$parser->parsePage($_GET['url']);
	debug($parser->GetData());
?>