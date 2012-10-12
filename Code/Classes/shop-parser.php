<?php
	require_once(dirname(__FILE__) . "/../Shared/utils.php");
	require_once(dirname(__FILE__) . "/module.php");
	require_once(dirname(__FILE__) . "/mysql.php");
	require_once(dirname(__FILE__) . "/remote-reader.php");
	require_once(dirname(__FILE__) . "/template-manager.php");

	class ShopParser extends Module{
		protected $Hostname;	
		
		protected $Reader;
		protected $DB;
		protected $Templates;

		protected $contents;
		
		public  $DebugMode = true;
		public  $LastError;				
		private $Buffer;
		private $DOM;
		private $XPath;
		private $PageUrl;
		protected $ShopUrl;
		private $CatUrl;
				
		function __construct($Reader){
			$this->DB = MySql::getInstance();
			$this->Reader = new RemoteReader();	
			$this->Templates = new TemplateManager($this->DB);
			$this->DOM = new DOMDocument();
		}
		
		function getContent($PageUrl) {
			$this->PageUrl = $PageUrl;
			$this->Buffer = $this->Reader->Get($PageUrl);
			return $this->Buffer;
		}
		
		function load() {
			if (!empty($this->Buffer)) {
				@$this->DOM->loadHTML($this->Buffer);
				$this->DOM->preserveWhiteSpace = false;
				$this->DOM->formatOutput = true;
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
					
					while ($docNode->nodeName != $tmplNode->nodeName) {
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
					
					if ($docNode->nodeName == $tmplNode->nodeName) {
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
									echo "<b>Warning: element doesnt match template</b><br/>";
									echo "Debug info (save to <a href='www.pastebin.com'>pastebin</a> and send to person who makes templates):<br/><br/>";
								
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
									echo "<hr/>";
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
		
		protected function getProductTechSpecByCategory($Product, $Category) {
			switch ($Category) {
				case "CPU":
					break;
				default:
					die("Unknown category ('{$Category}') in getProductTechSpecByCategory");
					break;
			}
		}
		
		protected function internalParseCategory() {
			$this->productList = array();
			
			$DOM2 = new DOMDocument();

			$list = $this->XPath->query($this->Templates->GetItem("product-list"));
			$DOM2->loadXML($this->Templates->GetItem("product-list-item"));
			$skip = $this->Templates->GetItem("product-list-skip");
			$fieldCount = $this->Templates->GetItem("product-list-field-count");

			$i = 0;
			foreach ($list as $product) {
				$i++;
				if ($i < $skip) {
					continue;
				}
				$item = array();
				$break = false;
				
				$tmplNode = $DOM2->getElementsByTagName('template')->item(0)->firstChild;
				$docNode = $product->getElementsByTagName($tmplNode->nodeName)->item(0);
								
				$this->internalParseWithTemplate($docNode, $tmplNode, $item, 0);
													
// 				Validate records: Valid ones will have defined number of fields. Skip bad ones.
				$cnt = 0;
				foreach ($item as $field) {
					if (!empty($field)) {
						$cnt++;
					}
				}
				if ($cnt == $fieldCount) {
					$this->productList[] = $item;
				}
			}
			
			if ($this->DebugMode) {
				if ($list->length != count($this->productList)) {
					echo "<b>Warning: Retrieved product list length doesnt match DOM elements number.</b><br/>";
					echo "Should those items be skipped?<br/>";
					echo "<hr/>";
				}
			}
		}
		
		public function parsePage($CatUrl) {
			$this->CatUrl = $CatUrl;
			$this->getContent($this->CatUrl);
			$this->load();
			$this->internalParseCategory();
		}
				
		protected function getOutput() {			
			return $this->productList;
		}
	}
?>