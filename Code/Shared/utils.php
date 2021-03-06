<?php
    function debug($data) {
		echo dump($data);
	}
	
   function dump($data) {
		if (empty($data)) {
			return;//$data = "/EMPTY/";
		}
		return "<pre>" . print_r($data, true) . "</pre>";
	}
	
	function isTemplateMarker($string) {
		return ($string == ("{" . trim($string, "{}") . "}"));
	}
	
	function innerHTML( $contentdiv, $self = false ) {
		$r = '';
		if ($self) {
			$r .= '<';
			$r .= $contentdiv->nodeName;
			if ( $contentdiv->hasAttributes() ) { 
				$attributes = $contentdiv->attributes;
				foreach ( $attributes as $attribute )
					$r .= " {$attribute->nodeName}='{$attribute->nodeValue}'" ;
			}	 
			$r .= '>';
		}
					$elements = $contentdiv->childNodes;
		if (!empty($elements)) {
			foreach( $elements as $element ) { 
				if ( $element->nodeType == XML_TEXT_NODE ) {
					$text = $element->nodeValue;
					// IIRC the next line was for working around a
					// WordPress bug
					//$text = str_replace( '<', '&lt;', $text );
					$r .= $text;
				}	 
				// FIXME we should return comments as well
				elseif ( $element->nodeType == XML_COMMENT_NODE ) {
					$r .= '';
				}	 
				else {
					$r .= '<';
					$r .= $element->nodeName;
					if ( $element->hasAttributes() ) { 
						$attributes = $element->attributes;
						foreach ( $attributes as $attribute )
							$r .= " {$attribute->nodeName}='{$attribute->nodeValue}'" ;
					}	 
					$r .= '>';
					$r .= innerHTML( $element );
					$r .= "</{$element->nodeName}>";
				}	 
			}	 
		}
		if ($self) {
			$r .= "</{$contentdiv->nodeName}>";
		}
		return $r;
	}
?>