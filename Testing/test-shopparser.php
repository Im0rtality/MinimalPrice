<?php
	require_once(dirname(__FILE__) . "/../Testing/Framework/test-framework.php");
	require_once(dirname(__FILE__) . "/../Code/Parsers/parser.simple-shop-parser.php");
    $test = new TestFramework();

    
    $assets = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/Assets/';

    $shop = 1;
	$parser = new SimpleShopParser($shop);	
	$parser->parsePage($assets . "webpage-1.htm");
    $data = $parser->GetData();
    debug($data);

    $test->Assert(count($data) > 0, "Checking if parser got any results");
    $test->Assert(count($data) == 213, "Checking if parser got all results");
    $test->Assert($data[0]['href'] == '/sdh1250dwbox-amd-sempron-le1250-am2-45w-box-p-10524.html', "Checking if 1st element href field matches expected value");


    echo $test->getResults('html');
?>