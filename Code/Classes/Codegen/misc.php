<?php
	class CodegenMisc {
		
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
/*
            foreach ($Array as $key => $value) {
                $code .= "[{$key}]\t\t {$value}\n";
            }
*/
            $code .= htmlentities(print_r($Array, true));
            $code .= "</pre></blockquote>";
            return $code;
        }

	}
?>