<?php
	class CodegenQuery {
		
        public static function QueryDeleteEntry($table, $id) {
            $query = "DELETE FROM {$table} WHERE (id = {$id}) LIMIT 1";
            return $query;
        }

        public static function QuerySaveEditor($data, $table) {
            if (empty($_POST["id"])) {
                $fields = "";
                $values = "";
                foreach($data as $key => $value) {
                    if ($key != 'id') {
                        $fields .= $key . ", ";
                        $values .= "'" . mysql_real_escape_string($value) . "', ";
                    }
                }
                $fields = substr($fields, 0, -2);
                $values = substr($values, 0, -2);
                $query = "INSERT INTO `{$table}` ({$fields}) VALUES ({$values})";
                return $query;
            } else {
                $query = "UPDATE `{$table}` SET ";
                foreach($data as $key => $value) {
                    if ($key != 'id') {
                        $query .= "{$key}='" . mysql_real_escape_string($value) . "', ";
                    }
                }
                $query = substr($query, 0, -2);
                $query .= " WHERE (id = {$_POST["id"]}) LIMIT 1";
                return $query;
            }
        }
 
        public static function QueryLoadEditor($table, $id) {
            $query = "SELECT * FROM `{$table}` WHERE id = {$id} LIMIT 1";
            return $query;
        }

        public static function QueryForSelect($table, $label) {
            return "SELECT id, $label FROM $table ORDER BY $label ASC";
        }
        
        public static function GetDataForSelect($table, $label, $addNullValue = false) {
            $query = CodegenQuery::QueryForSelect($table, $label);
            $DB = MySql::getInstance();
            $DB->ExecuteSQL($query);
            $data = $DB->GetRecordSet();
            if ($addNullValue === true) {
                array_unshift($data, array("id" => 0, $label => "NULL"));
            }
            return $data;
        }
	}
?>