<?php

//Example Script, saves Hello World to the database.

require_once(dirname(__FILE__) . "/../../config.php");
//First, we need to include redbean
require_once('/RedBean/rb.php');

R::setup('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_NAME, MYSQL_USER, MYSQL_PASS);
R::freeze( true );

/*
		public function generate() {
			$query = "SELECT * FROM `country`";

			$code = "";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

			$settings['column_names'] = ["Name"];
			$settings['column_widths'] = [""];
			$settings['column_hidden'] = [true, false];
			$settings['id_col'] = "id";
			$settings['page'] = "editcountry";
			
			$code .= Formatter::Table($Data, $settings);
			return $code;
		}
*/
$bean = R::load('country',1);	
$aDullArray = $bean->export();
print_r( $aDullArray);

echo '<br>';

$countries = R::findAll('country');
echo $countries[1]->name . "<br />";
$beanTable = $bean->getMeta('type');
echo $beanTable;

echo "<br>";
$value = $bean->getProperties();
print_r( $value);
//Ready. Now insert a bean!
//$bean = R::dispense('leaflet');
//$bean->title = 'Hello World';
//$bean->chuj = 'Hello beach';

//Store the bean sktak
//$id = R::store($bean);

//Reload the bean
//$leaflet = R::load('leaflet',$id);

//Display the title
//echo $leaflet->title;

R::close();

?>




