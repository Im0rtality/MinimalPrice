<?php
	require_once("../Shared/utils.php");
	require_once("class.module.php");
	require_once("class.template-manager.php");

	class ShopParser extends Module{
		protected $Hostname;	
		
		protected $Reader;
		protected $DB;
		protected $Templates;

		protected $contents;
		
		public  $LastError;				
		private $Buffer;
		private $DOM;
		private $XPath;
		private $PageUrl;
				
		function __construct($DB, $Reader){
			$this->DB = $DB;
			$this->Reader = $Reader;	
			$this->Templates = new TemplateManager($this->DB);
			$this->DOM = new DOMDocument();
		}
		
		function GetContent($PageUrl) {
			$this->PageUrl = $PageUrl;
			$this->Buffer = $this->Reader->Get($PageUrl);
			return $this->Buffer;
		}
		
		function Load() {
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
						$docNode = $docNode->nextSibling;
					}
				}
				
				$tmplNode = $tmplNode->nextSibling;
			} while (($tmplNode != NULL) && ($docNode != NULL));
			//if ($tmplNode == NULL) debug('$tmplNode == NULL');
			//if ($docNode == NULL) debug('$docNode == NULL');
		}
		
		protected function ExtractData() {
			$this->contents = array();
			
			$tbody = $this->XPath->query($this->Templates->GetItem("product-list"));
			$DOM2 = new DOMDocument();
			$DOM2->loadXML($this->Templates->GetItem("product-list-item"));
			$DOM2->formatOutput = true;
			
			$skip = $this->Templates->GetItem("product-list-skip");


			$i = 0;
			//$tr = $tbody->item(0);
			foreach ($tbody as $tr) {
				$i++;
				if ($i < $skip) {
					continue;
				}
				$item = array();
				$break = false;
				
				// <td>{serial}</td>
				$tmplNode = $DOM2->getElementsByTagName('template')->item(0)->firstChild;
				//debug("template");
				//debug(htmlentities(innerHTML($DOM2->getElementsByTagName('template')->item(0), true)));
				
				$docNode = $tr->getElementsByTagName($tmplNode->nodeName)->item(0);
				//debug("document");
				//debug(htmlentities(innerHTML($docNode->parentNode, true)));
				
				
				$this->internalParseWithTemplate($docNode, $tmplNode, $item, 0);
													
				$this->contents[] = $item;
				
//				if ($i++ >= 3) {
//					break;
//				}
			}
		}
		
		protected function ExtractData1() {
			$this->contents = array();
			
			$tbody = $this->XPath->query($this->Templates->GetItem("product-list"));
			$DOM2 = new DOMDocument();
			$DOM2->loadXML($this->Templates->GetItem("product-list-item"));


			foreach ($tbody as $tr) {
				$item = array();
				$break = false;
				
				// <td>{serial}</td>
				$tmplNode = $DOM2->getElementsByTagName('template')->item(0)->firstChild;
				$docNode = $tr->getElementsByTagName($tmplNode->nodeName)->item(0);
				
				$i = 0;
				do {
					$i++;
					debug("Loop: {$i} searching for: {$tmplNode->nodeName}");// docNode: {$docNode->nodeName}");
					
					while ($docNode->nodeName != $tmplNode->nodeName) {
						$docNode = $docNode->nextSibling;
						if ($docNode == NULL) {
							$break = true;
							break;
						}
					}
					if ($break) {
						debug("BREAKING!");
						break;
					}

					debug("Loop: {$i} docNode: {$docNode->nodeName} #" . $docNode->getLineNo() . " code: " . htmlentities(innerHTML($docNode, true)));
					
					if ($docNode->nodeName == $tmplNode->nodeName) {
						/*
						if (!empty($tmplNode->nodeValue)) {
							$item[trim($tmplNode->nodeValue, "{}")] = $docNode->nodeValue;							
						}
						*/
						$this->internalParseSingle($docNode, $tmplNode, $item);
						$docNode = $docNode->nextSibling;
					}
					$tmplNode = $tmplNode->nextSibling;
				} while (($tmplNode != NULL) && ($docNode != NULL));
				if ($tmplNode == NULL) debug('$tmplNode == NULL');
				if ($docNode == NULL) debug('$docNode == NULL');
				debug("==================================================");
									
				$this->contents[] = $item;
				break;
			}
		}
		protected function ExtractData2() {
			$this->contents = array();
			//$classname = "productListing";
			$tbody = $this->XPath->query($this->Templates->GetItem("product-list"));
			$DOM2 = new DOMDocument();
			$DOM2->loadHTML($this->Templates->GetItem("product-list-item"));


			foreach ($tbody as $tr) {
				$item = array();
				$td = $this->XPath->Query('td', $tr);
				
				if ($td->length == 7) {
					$attr = $this->XPath->Query('a', $td->item(2))->item(0);
					if ($attr != null) {
						$subject  = innerHTML($tr);
						echo $subject;						
						
/*
non condensed regex:

<td (?:.*?)>\s*(?P<serial>.*?)\s*<\/td>\s*
<td (?:.*?)>\s*(?:.*)\s*?<\/td>\s*
<td (?:.*?)><a href='(?P<href>.*)'>(?:.*)<\/a>\s*?<\/td>\s*
<td (?:.*?)>\s*?(?:.*)\s*?<\/td>\s*
<td (?:.*?)>\s*(?:.*)\s*<\/td>\s*
<td (?:.*?)>\s*(?P<price>.*?)\s*<\/td>\s*
*/
/*					
<td>{serial}</td>
<td></td>
<td><a href='{href}'></a><td>
<td></td>
<td>{price}</td>
						*/
//						backing up pattern extracting HREF
//						$pattern = "<td (?:.*?)>\s*(?P<serial>.*?)\s*<\/td>\s*<td (?:.*?)>\s*(?:.*)\s*?<\/td>\s*<td (?:.*?)><a href='(?<href>.*?)'>(?:.*?)<\/a>";
						$pattern = "<td (?:.*?)>\s*(?P<serial>.*?)\s*<\/td>\s*<td (?:.*?)>\s*(?:.*)\s*?<\/td>\s*<td (?:.*?)><a href='(?<href>.*?)'>(?:.*?)<\/a> <\/td>\s*";
						
						preg_match('!' . $pattern . '!', $subject, $matches);
						
						debug($matches);
							
							
							
							
							
							
							
							
							
							
						$this->contents[] = $item;
						break;
					}
				}
			}
		}
		
		protected function GetOutput() {			
			return $this->contents;
		}
	}
?>