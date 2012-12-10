<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageParserTemplate extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'parser-templates';
			$this->options['name'] = 'Parser Templates';
			parent::__construct();
		}

		public function generate() {
			$query = "SELECT * FROM `parser_template`";

			$code = "<h1>{$this->options['name']}</h1>";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();
			//$code .= Formatter::DbDataToHtmlTable($Data);
			$code .= <<<EOT
<table class="table table-condensed table-hover table-stripped">
<tr>
	<th width="20px">ID</th>
	<th>Name</th>
	<th width="20px">&nbsp;</th>
	<th width="20px">&nbsp;</th>
</tr>
EOT;
			foreach ($Data as $Row) {
				$btnEdit = Formatter::EditButton('edittemplate', $Row["id"], []);
				$btnDelete = Formatter::DeleteButton('edittemplate', $Row["id"], []);
				$code .= <<<EOT
<tr>
	<td>{$Row['id']}</td>
	<td>{$Row['name']}</td>
	<td>{$btnEdit}</td>
	<td>{$btnDelete}</a></td>
</tr>
EOT;

			/*	$object = json_decode($Row['value'], true);
				$xpath = htmlentities($object['product-list']);
				$regex = htmlentities($object['product-list-item']);
				//debug(htmlentities($object['product-list-item']));
				$code .= <<< EOT
<form method="GET" action="" class="form-horizontal">
	<legend>{$Row['name']}</legend>
	<input value="{$xpath}" class="input-xlarge" name="product-list" id="product-list" type="text">
	<input value="{$regex}" class="input-xlarge" name="product-list-item" id="product-list" type="text">
	<input value="{$object['product-list-skip']}" class="input-mini" name="product-list-skip" id="product-list-skip" type="text">
	<input value="{$object['product-list-field-count']}" class="input-mini" name="product-list-field-count" id="product-list-field-count" type="text">
	<button type="button" class="btn">More</button>
	<button type="submit" class="btn btn-primary">Save</button>
</form>
EOT;*/
			}
			$code .= "</table>";

			return $code;
		}
	}

	new PageParserTemplate();
?>