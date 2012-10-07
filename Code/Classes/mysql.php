<?php
	include("interface.database-engine.php");
	
	class MySQL implements DatabaseEngine{
	/*
		protected $Hostname = MYSQL_HOST;	
		protected $Username = MYSQL_USER;	
		protected $Password = MYSQL_PASS;	
		protected $Database = MYSQL_NAME;	
	*/
		protected $Prefix   = MYSQL_PREFIX;	

		public $LastError;				
		protected $History = array();
		
		private $DBLink;
		private $LastQuery = array();
		
		function MySQL(){
			$this->Connect();
		}
		
		public function Connect(){
			if($this->DBLink){
				mysql_close($this->DBLink);
			}
				
			$this->DBLink = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
			
			if (!$this->DBLink){
				$this->LastError = 'Could not connect to server: ' . mysql_error($this->DBLink);
				return false;
			}
			
			if(!mysql_select_db(MYSQL_NAME, $this->DBLink)){
				$this->LastError = 'Cannot select database: ' . mysql_error($this->DBLink);
				return false;
			}
			return true;
		}
		
		public function ExecuteSQL($SQLQuery){
			$this->LastQuery["Query"] = $SQLQuery;
			$start = microtime(true);
			if($this->Result 		= mysql_query($SQLQuery, $this->DBLink)){
				$this->Records 		= @mysql_num_rows($this->Result);
				$this->Affected		= @mysql_affected_rows($this->DBLink);
				$finish = microtime(true);
				$this->LastQuery["Status"]  = "OK";
				$this->LastQuery["Speed"]  = round(($finish - $start) * 1000, 2) . " us";
				$Ret = true;
			}else{
				$this->LastError 	= mysql_error($this->DBLink);
				$this->LastQuery["Status"]  = "Error: " . $this->LastError;
				$Ret = false;
			}
			$this->History[] = $this->LastQuery;
			return $Ret;
		}
		
		public function Insert($Values, $Table, $Vars= ''){
			$Query = "INSERT INTO " . $this->Prefix . $Table;
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
		
		public function Delete($Table, $Where='', $Limit=''){
			die("MySql->Delete() is not implemented");
			// Build query and fire ExecuteSQL
		}
		
		public function Select($From, $Where='', $OrderBy='', $Limit=''){
			die("MySql->Delete() is not implemented");
			// Build query and fire ExecuteSQL
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
			return $this->History;
		}
	}
?>