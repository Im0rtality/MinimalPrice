<?php
interface DatabaseEngine{
    public function Connect();
    public function ExecuteSQL($SQLQuery);
	public function GetLog();	
}
?>