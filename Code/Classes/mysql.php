<?php
	require_once(dirname(__FILE__) . "/../config.php");
	require_once(dirname(__FILE__) . "/Interfaces/database-engine.php");
	require_once(dirname(__FILE__) . "/singleton.php");
	
	class MySql implements DatabaseEngine{
		public 		$Debug = false;

		protected 	$fPrefix   = MYSQL_PREFIX;	
		
		private 	$DBLink;
		private 	$fLastQuery = array();
		public 	  	$fLastError;				
		protected 	$fHistory = array();
					
    	protected static $instance = null;
	    protected function __construct(){}
	    protected function __clone(){}
    	public static function getInstance() {
	        if (!isset(static::$instance)) {
	            static::$instance = new static;
	        }
	        return static::$instance;
	    }

		public function Connect(){
			if($this->DBLink){
				mysql_close($this->DBLink);
			}
				
			$this->DBLink = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
			
			if (!$this->DBLink){
				$this->fLastError = 'Could not connect to server: ' . mysql_error($this->DBLink);
				return false;
			}
			
			if(!mysql_select_db(MYSQL_NAME, $this->DBLink)){
				$this->fLastError = 'Cannot select database: ' . mysql_error($this->DBLink);
				return false;
			}
			return true;
		}

		private function FieldList($Fields, $Delimiter = ',', $Prefix = '`', $Suffix = '`') {
			$Query = '';
			if (is_array($Fields)) {
				foreach($Fields as $Field) {
					$Query .= "{$Prefix}{$Field}{$Suffix}{$Delimiter} ";
				}
				$Query = substr($Query, 0, strlen($Delimiter) + 1); // remove last comma
			} else {
				$Query .= "{$Prefix}{$Field}{$Suffix} ";
			}
			return $Query;
		}
		
		public function ExecuteSQL($SQLQuery){
			$this->ConnectIfNeeded();

			$this->fLastQuery["Query"] = $SQLQuery;
			$start = microtime(true);
			if($this->fResult = mysql_query($SQLQuery, $this->DBLink)){
				$this->fRecords = @mysql_num_rows($this->fResult);
				$this->fAffected = @mysql_affected_rows($this->DBLink);
				$finish = microtime(true);
				$this->fLastQuery["Status"]  = "OK";
				$this->fLastQuery["Speed"]  = round(($finish - $start) * 1000, 2) . " us";
				if ($this->Debug) {
					$this->fHistory[] = $this->fLastQuery;
				}
				$Ret = true;
			}else{
				$this->fLastError 	= mysql_error($this->DBLink);
				$this->fLastQuery["Status"]  = "Error: " . $this->fLastError;
				$this->fHistory[] = $this->fLastQuery;
				$Ret = false;
			}
			return $Ret;
		}
		
		public function Insert($Values, $Table, $Vars= ''){
			$Query = "INSERT INTO " . $this->fPrefix . $Table;
			if (!empty($Vars)){
				$Query .= " (";

				foreach($Vars as $Var){
					$Query .= "`" . $Var . "`, ";
				}
				$Query = substr($Query, 0, -2); // remove last comma
				$Query .= ")";
			}
			$Query .= " VALUES(";
			foreach($Values as $Val){
				$Query .= "'" . $Val . "', ";
			}
			$Query = substr($Query, 0, -2); // remove last comma

			$Query .= ")";

			return $this->ExecuteSQL($Query);
		}
		
		public function Delete($From, $Where='', $Limit=''){
		//	die("MySql->Delete() is not implemented");
			
			// Add field list to query. Might be array of fields or single field as string
			$From = "FROM {$From} ";

			if (!empty($Where)){
				$Where = "WHERE {$Where} ";
			} else {
				die("Cannot call MySql->Delete() with empty WHERE conditional!");
			}

			if (!empty($Limit)) {
				$Limit = "LIMIT {$Limit} ";
			}
			$Query = "DELETE {$From}{$Where}{$Limit}";

			return $this->ExecuteSQL($Query);
		}
		
		public function Select($From, $What='*', $Where='', $OrderBy='', $Limit=''){
			die("MySql->Delete() is not implemented");
			
			// Add field list to query. Might be array of fields or single field as string
			$From = "FROM {$this->FieldList($From)} ";
			$Fields = $this->FieldList($What);

			if (!empty($Where)){
				$Where = "WHERE {$Where} ";
			}

			if (!empty($OrderBy)) {
				$OrderBy = "ORDER BY {$OrderBy[0]} {$OrderBy[1]} ";
			}

			if (!empty($Limit)) {
				$Limit = "LIMIT {$Limit} ";
			}
			$Query = "SELECT {$Fields}{$From}{$Where}{$OrderBy}{$Limit}";

			$this->ExecuteSQL($Query);
			return $this->GetRecordSet();
		}
		
		public function UpdateOrInsert() {
			die("MySql->UpdateOrInsert() is not implemented");
			// INSERT INTO (field1, field2, field3, ...) VALUES ('value1', 'value2','value3', ...) ON DUPLICATE KEY UPDATE field1='value1', field2='value2', field3='value3', ...
		}

		public function Update($Table, $Set, $Where){
			die("MySql->Delete() is not implemented");
			// Build query and fire ExecuteSQL
		}
		
		public function EscapeData($Data){
			if (is_array($Data)){
				// escape each element of array...
				foreach($Data as &$D) {
					$D = mysql_real_escape_string($D);
				}
				
				return $Data;
			} else {
				// ... or just escape variable
				return mysql_real_escape_string($Data);
			}
		}
		
		public function GetLog(){
			return $this->fHistory;
		}

		public function GetRecordSet() {
			$data = array();
			while ($row = mysql_fetch_assoc($this->fResult)) {
			    $data[] = $row;
			}
			return $data;
		}

		private function ConnectIfNeeded() {
			if ($this->DBLink) {

			} else {
				$this->Connect();
			}
		}
	}
?>