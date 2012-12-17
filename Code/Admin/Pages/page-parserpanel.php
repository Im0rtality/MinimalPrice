<?php
	require_once(dirname(__FILE__) . "/../../Classes/mysql.php");
	require_once(dirname(__FILE__) . '/../interface.page-generator.php');

	class PageParserPanel extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'parser-panel';
			$this->options['name'] = 'Parsers Control Panel';
			parent::__construct();
		}

		public function generate() {
			$query = "SELECT shop.*, parser.name as pname FROM `shop`, `country`, `parser` WHERE (shop.country_id = country.id) AND (shop.parser_id = parser.id)";

			$code = "";

			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

            $settings['column_names'] = ["Name", "URL", "Parser"];
            $settings['column_widths'] = ["", "100px", ""];
            $settings['column_hidden'] = [true, true, true, false, false, true, false];
            $settings['id_col'] = "id";
            $settings['page'] = "edit{$this->options['link']}";


			$settings['column_hidden'][] = false;
			$settings['column_names'][] = "&nbsp;";
			$settings['column_widths'][] = "40px";
			$code  = CodegenTable::TableHeader($settings['column_names'], $settings['column_widths']);

        	foreach ($Data as $Row) {
        		//debug($row);
        		$code .= "<tr>";
				$params = array("parser_id" => $Row['parser_id'], "shop" => $Row['id'], "url" => $Row['url']);
				$Row[] = CodegenTable::Button("!../ajax/parser.php?", $params, "download", "mini", "warning", "Parse");
        		$Keys = array_keys($Row);
        		for ($i = 0; $i < count($Row); $i++) {
        			if ($settings['column_hidden'][$i] === false) {
        				$code .= "<td style='overflow:hidden; white-space:nowrap; text-overflow:ellipsis;'>{$Row[$Keys[$i]]}</td>";
        			}
        		}
        		$code .= "</tr>";
        	}

			$code .= CodegenTable::TableFooter();
            return $code;
		}
	}

	new PageParserPanel();
?>