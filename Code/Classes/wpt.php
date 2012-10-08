<?php
	require_once(dirname(__FILE__) . "/../Shared/utils.php");
	require_once(dirname(__FILE__) . "/template-manager.php");

	class WebsiteParseTemplate {
		protected $Hostname;	
		
		protected $Reader;
		protected $DB;
		protected $Template;

		protected $contents;
		
		public  $DebugMode = true;
		public  $LastError;				
		private $Buffer;
		private $DOM;
		private $XPath;
		private $Url;
				
		function __construct($DB, $Reader){
			$this->DB = $DB;
			$this->Reader = $Reader;	
			$this->DOM = new DOMDocument();
		}
		
		protected function getContent($PageUrl) {
			$this->PageUrl = $PageUrl;
			$this->Buffer = $this->Reader->Get($PageUrl);
			return $this->Buffer;
		}
		
		protected function load() {
			if (!empty($this->Buffer)) {
				@$this->DOM->loadHTML($this->Buffer);
				$this->DOM->preserveWhiteSpace = false;
				$this->XPath = new DomXPath($this->DOM);
				return true;
			} else {
				$this->LastError = "Error: Buffer is empty";
				return false;
			}
		}
		
		private function internalParseSingle($docNode, $tmplNode, &$item){
			if ($docNode->nodeName == $tmplNode->nodeName) {
				if (isTemplateMarker($tmplNode->nodeValue)) {
					$item[trim($tmplNode->nodeValue, "{}")] = $docNode->nodeValue;							
				}
				
				$tmplAttrs = $tmplNode->attributes;
				if (!empty($tmplAttrs)) {
					$docAttrs = $docNode->attributes;
					foreach ($tmplAttrs as $tmplAttr) {						
						if (isTemplateMarker($tmplAttr->nodeValue)) {
							$item[trim($tmplAttr->nodeValue, "{}")] = $docNode->getAttribute($tmplAttr->nodeName);
						} else {
							debug('"' . $tmplAttr->nodeValue . '" is not valid template marker');
						}
					}
				}
			}
			return true;
		}
		
		private function internalParseWithTemplate($docNode, $tmplNode, &$contents, $depth){			
//			debug("internalParseWithTemplate(\"".htmlentities(innerHTML($docNode, true))."\", \"".htmlentities(innerHTML($tmplNode, true))."\", [...], {$depth});");
			$break = false;
			$i = 0;
			do {
				$i++;
				if ($tmplNode->nodeType == XML_TEXT_NODE) {
//					echo "<b>Depth: {$depth} Loop: {$i}</b> skipped, XML_TEXT_NODE<br/>";					
				} else {
//					echo "<b>Depth: {$depth} Loop: {$i}</b><br/> searching for: " . htmlentities(innerHTML($tmplNode, true)) . '<br/>';
					
					while ((!empty($docNode)) && ($docNode->nodeName != $tmplNode->nodeName)) {
						$docNode = $docNode->nextSibling;
						if ($docNode == NULL) {
							$break = true;
							break;
						}
					}
					if ($break) {
//						debug("BREAKING!");
						break;
					}

//					echo " docNode: " . htmlentities(innerHTML($docNode, true)) .'<br/>';
					
					if ((!empty($docNode)) && ($docNode->nodeName == $tmplNode->nodeName)) {
						if ($this->internalParseSingle($docNode, $tmplNode, $item)) {
							if (is_array($item)) {
								$contents = array_merge($contents, $item);
							}
						}

						if ($tmplNode->hasChildNodes() && $docNode->hasChildNodes()) {
							$tmplChildren = $tmplNode->childNodes;
							$docChildren = $docNode->childNodes;
							
							if ($tmplChildren->length == $docChildren->length) {
								for ($c = 0; $c < $tmplChildren->length; $c++) {
									switch ($tmplChildren->item($c)->nodeType) {
										case XML_TEXT_NODE:
										//	debug("child type XML_TEXT_NODE");
											break;
										case XML_COMMENT_NODE:
										//	debug("child type XML_COMMENT_NODE");
											break;
										default:
											$this->internalParseWithTemplate($docChildren->item($c), $tmplChildren->item($c), $contents, $depth + 1);
											break;
									}
								}
							} else {
								if ($this->DebugMode) {
									echo "<b>Parsing error: element doesnt match template</b><br/>";
									echo "Debug info (save to <a href='www.pastebin.com'>pastebin</a> and send to one who makes templates):<br/><br/>";
								
									echo "### DEBUG INFORMATION ###<br/>";
									echo "website url:<br/>";
									echo "{$this->PageUrl}<br/><br/>";

									echo "internalParseWithTemplate arguments:<br/>";
									echo "<ol><li>".htmlentities(innerHTML($docNode, true))."</li>";
									echo 	 "<li>".htmlentities(innerHTML($tmplNode, true))."</li>";
									echo 	 "<li>[...]</li>";
									echo 	 "<li>{$depth}</li></ol><br/>";
									
									echo "Current element (" . htmlentities(innerHTML($docNode, true)) . ") doesnt match template. <br/><br/>";

									echo "doc child nodes(count={$docChildren->length}):<ul>";
									foreach ($docChildren as $n) {
										echo "<li>" . htmlentities(innerHTML($n, true)) . "</li>";
									}
									echo "</ul><br/>";

									echo "tmpl child nodes(count={$tmplChildren->length}):<ul>";
									foreach ($tmplChildren as $n) {
										echo "<li>" . htmlentities(innerHTML($n, true)) . "</li>";
									}
									echo "</ul><br/>";
									echo "### END OF DEBUG INFORMATION ###<br/>";
								}
							}
						}
						$docNode = $docNode->nextSibling;
					}
				}
				
				$tmplNode = $tmplNode->nextSibling;
			} while (($tmplNode != NULL) && ($docNode != NULL));
			//if ($tmplNode == NULL) debug('$tmplNode == NULL');
			//if ($docNode == NULL) debug('$docNode == NULL');
		}
		
		private function internalParsePage($Tmpl){
			//debug($Tmpl);
			$DOM2 = new DOMDocument();

			$List = $this->XPath->query($Tmpl->XPath);
//			if (!empty($field)) {
				@$DOM2->loadXML($Tmpl->Tmpl);
//			}
			$skip = $Tmpl->Skip;
			$fieldCount = $Tmpl->FieldCount;


			$i = 0;
			foreach ($List as $product) {
				//debug(htmlentities(innerHTML($product)));
				//echo "<hr/>";
				$i++;
				if ($i < $skip) {
					continue;
				}
				$item = array();
				$break = false;
				
				if (!empty($Tmpl->Tmpl)) {
					$tmplNode = $DOM2->getElementsByTagName('template')->item(0)->firstChild;
					$docNode = $product->getElementsByTagName($tmplNode->nodeName)->item(0);
									
					$this->internalParseWithTemplate($docNode, $tmplNode, $item, 0);
				} else {
					$item['value'] = innerHTML($product);
				}
// 				Validate records: Valid ones will have defined number of fields. Skip bad ones.
				$cnt = 0;
				foreach ($item as $field) {
					if (!empty($field)) {
						$cnt++;
					}
				}
				if ($cnt == $fieldCount) {
					$this->contents[] = $item;
				}
			}
		}
		
		public function parsePage($Url, $Tmpl){
			$this->contents = array();
			
			$this->Url = $Url;
			$this->getContent($this->Url);
			$this->load();
			
			$this->internalParsePage($Tmpl);
			
			return $this->contents;
		}
	}
?>