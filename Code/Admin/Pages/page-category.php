<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageCategory extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'category';
			$this->options['name'] = 'Categories';
			parent::__construct();
        	$this->buttons[] = array(
        			"name" => "Add", 
        			"href" => "?page=edit{$this->options['link']}&action=add", 
        			"icon" => "plus");
		}

		public function generate() {
			$query = "SELECT category.*, cimage.url FROM category, cimage WHERE category.cimage_id = cimage.id";

			$code = "";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

			$settings['column_names'] = ["Category Name", "Image Url"];
			$settings['column_widths'] = ["", ""];
			$settings['column_hidden'] = [true, true, false, true, false];
			$settings['id_col'] = "id";
			$settings['page'] = "edit{$this->options['link']}";
			
			$code .= Formatter::Table($Data, $settings);
			return $code;
		}
	}

	new PageCategory();
?>