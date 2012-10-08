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
	}
?>