<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageCategory extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'category';
			$this->options['name'] = 'Categories';
			parent::__construct();
		}

		public function generate() {
			$query = "SELECT category.*, category_image.url FROM category, category_image WHERE category.category_image_id = category_image.id";

			$code = "<h1>{$this->options['name']}</h1>";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

			$settings['column_names'] = ["Category Name", "Image Url"];
			$settings['column_widths'] = ["", ""];
			$settings['column_hidden'] = [true, false, true, true, false];
			$settings['id_col'] = "id";
			$settings['page'] = "editcategory";
			
			$code .= Formatter::Table($Data, $settings);

			return $code;
		}
	}

	new PageCategory();
?>