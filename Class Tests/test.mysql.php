<?php
	include("../Code/config.php");
	include("../Code/Classes/class.mysql.php");
	
	$db = new MySql();
	
	if (!$db->Insert(array('test', sha1('test'), 65535, 1), 'users', array('username', 'phash', 'rights', 'createdby'))) {
		echo $db->LastError;
	}
	
	echo "<pre>Database->GetLog():\n" . print_r($db->GetLog(), true) . "</pre>";
?>