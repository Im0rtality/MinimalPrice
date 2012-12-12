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
        	$code = '<table class="table table-condensed table-hover table-stripped" width="100%"><tr>';
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

        public static function TableRows($DataSet, $page, $idIndex = 0, $hidden, $add_buttons) {
        	$code = "";
        	foreach ($DataSet as $Row) {
        		$code .= "<tr>";
        		if ($add_buttons !== false) {
					$Row[] = Formatter::EditButton($page, $Row[$idIndex], []);
					$Row[] = Formatter::DeleteButton($page, $Row[$idIndex], []);
				}
        		$Keys = array_keys($Row);
        		for ($i = 0; $i < count($Row); $i++) {
        			if ($hidden[$i] === false) {
        				$code .= "<td>{$Row[$Keys[$i]]}</td>";
        			}
        		}
        		$code .= "</tr>";
        	}
			return $code;
        }

        public static function Table($Data, $Options) {
        	if (empty($Options['add_buttons'])) { 
        		$Options['add_buttons'] = true; 
        	}

        	if ($Options['add_buttons'] !== false) {
        		// Add 2 columns for Edit and Delete buttons in each row
        		$Options['column_names'][] = "";
        		$Options['column_names'][] = "";
        		$Options['column_widths'][] = "20px";
        		$Options['column_widths'][] = "20px";
            	$Options['column_hidden'][] = false; 
	        	$Options['column_hidden'][] = false; 
	    	}
			$code  = Formatter::TableHeader($Options['column_names'], $Options['column_widths']);
			$code .= Formatter::TableRows($Data, $Options['page'], $Options['id_col'], $Options['column_hidden'], $Options['add_buttons']);
			$code .= Formatter::TableFooter();
			return $code;        	
        }

        public static function FormText($Data) {
            $disabled = "";
            if ($Data["disabled"] === true) {
                $disabled = " disabled";
            }
            return "<input type='text' name='{$Data['id']}' id='{$Data['id']}' value='{$Data['value']}'{$disabled}>";
        }

        public static function Form($Data) {
            $code = "<form class='form-horizontal' method='POST' action='?page={$Data['page']}&action=save'>";
            foreach ($Data['fields'] as $value) {
                if (!isset($value['disabled'])) { 
                    $value['disabled'] = false; 
                }
               switch ($value['type']) {
                    case 'text':
                        $code .= '<div class="control-group">';
                        $code .= "<label class='control-label' for='{$value['id']}'>{$value['label']}</label>";
                        $code .= '<div class="controls">';
                        $code .= Formatter::FormText($value);
                        $code .= '</div>';
                        $code .= '</div>';
                }                
            }
            $code .= "<input type='hidden' name='id' value='{$Data['id']}'>";
            $code .= '<div class="form-actions">';
            $code .= '<button type="submit" class="btn btn-primary">Save changes</button>';
            $code .= '<button type="reset" class="btn">Cancel</button>';
            $code .= '</div>';
            $code .= "</form>";
            return $code;
        }

        public static function Redirect($Page, $Sleep, $Message = "") {
            $code = "";
            $code .= "<script type='text/javascript'>";
            $code .= "setInterval(function(){window.location.replace('?page={$Page}');},{$Sleep});";
            $code .= "</script>";
            if (!empty($Message)) {
                $code .= "<pre>{$Message}</pre>";
            }
            return $code;
        }

        public static function ArraySimpleDump($Array, $Caption) {
            $code = "";
            $code .= "<table class='table table-condensed table-hover table-stripped'>";
            $code .= "<tr><th>{$Caption}</th><th>&nbsp;</th></tr>";
            foreach ($Array as $key => $value) {
                $code .= "<tr><td>{$key}</td><td>{$value}</td></tr>";
            }
            $code .= "</table>";
            return $code;
        }

        public static function ArraySimpleDump2($Array, $Caption) {
            $code = "";
            $code .= "<blockquote><cite class='muted'>{$Caption}</cite><pre class='prettyprint linenums'>";
            foreach ($Array as $key => $value) {
                $code .= "[{$key}]\t\t {$value}\n";
            }
           // $code .= "hello world";
            $code .= "</pre></blockquote>";
            return $code;
        }
	}
?>