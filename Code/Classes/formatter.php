<?php
	class Formatter {
		
		public static function DbDataToHtmlTable($Data, $Width = "100%") {
			$code = '';
		    $count = count($Data);
		    if($count > 0){
		        reset($Data);
		        $num = count(current($Data));
		        $code .= "<table class=\"table table-condensed table-hover table-stripped\" width=\"$Width\">\n";
		        $code .= "<tr>\n";
		        foreach(current($Data) as $key => $value){
		            $code .= "<th>";
		            $code .= $key."&nbsp;";
		            $code .= "</th>\n";   
		            }   
		        $code .= "</tr>\n";
		        while ($curr_row = current($Data)) {
		            $code .= "<tr>\n";
		            $col = 1;
		            while (false !== ($curr_field = current($curr_row))) {
		                $code .= "<td>";
		                $code .= $curr_field."&nbsp;";
		                $code .= "</td>\n";
		                next($curr_row);
		                $col++;
		                }
		            while($col <= $num){
		                $code .= "<td>&nbsp;</td>\n";
		                $col++;       
		            }
		            $code .= "</tr>\n";
		            next($Data);
		            }
		        $code .= "</table>\n";
		    }
		    return $code;
        }

        public static function EditButton($page, $id, $params) {
        	$params["id"] = $id;
        	$params["action"] = "edit";
        	return Formatter::Button($page, $params, "pencil");
        }
	
        public static function DeleteButton($page, $id, $params) {
        	$params["id"] = $id;
        	$params["action"] = "delete";
        	return Formatter::Button($page, $params, "remove-sign", "mini", "danger");
        }

        public static function Button($page, $params, $icon, $size = "mini", $style = "") {
        	$href = $page == "#" ? "#" : "?page={$page}";
        	foreach ($params as $key => $value) {
        		$href .= "&{$key}={$value}";
        	}
        	$code = "<a class='btn btn-{$size} btn-{$style}' href='{$href}'><i class='icon-{$icon}'></i></a>";
        	return $code;
        }

        public static function TableHeader($columns, $widths) {
        	$code = '<table class="table table-condensed table-hover table-stripped"><tr>';
        	for ($i=0; $i < count($columns); $i++) { 
        		if (empty($columns[$i])) { $columns[$i] = '&nbsp;'; }
        		$width = "";
        		if (!empty($widths[$i])) { $width = " width='{$widths[$i]}'"; }
        		$code .= "<th{$width}>{$columns[$i]}</th>";
        	}
			$code .= '</tr>';
			return $code;
        }

        public static function TableFooter() {
        	$code = '</table>';
			return $code;
        }

        public static function TableRows($DataSet, $page, $idIndex = 0) {
        	$code = "";
        	foreach ($DataSet as $Row) {
        		$code .= "<tr>";
        		$Row[] = Formatter::EditButton($page, $Row[$idIndex], []);
        		$Row[] = Formatter::DeleteButton($page, $Row[$idIndex], []);
        		foreach ($Row as $Field) {
        			$code .= "<td>{$Field}</td>";
        		}
        		$code .= "</tr>";
        	}
			return $code;
        }

        public static function Table($Data, $Options) {
			$code  = Formatter::TableHeader($Options['column_names'], $Options['column_widths']);
			$code .= Formatter::TableRows($Data, $Options['page'], $Options['id_col']);
			$code .= Formatter::TableFooter();
			return $code;        	
        }
	}
?>