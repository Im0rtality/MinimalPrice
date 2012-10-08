<?php
	// TODO: authetification and shits
	require_once(dirname(__FILE__) . "/../../Code/config.php");
	require_once(dirname(__FILE__) . "/../../Code/Shared/utils.php");
	require_once(dirname(__FILE__) . "/../../Code/Classes/mysql.php");
	require_once(dirname(__FILE__) . "/../../Code/Admin/admin-page-generator.php");

	$db = MySql::getInstance();

	$gen = AdminPageGenerator::init();

	// pass the parameters
	$gen->passQuery($_GET);
	// include all page-modules
	$gen->includePages();
	// generate output and show it
	$gen->generate();

	debug($db->GetLog());
?>