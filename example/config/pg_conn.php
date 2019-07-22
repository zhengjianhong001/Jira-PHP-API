<?php
	function get_pg_connect() {
		try {
			$dbh = new PDO("pgsql:dbname=" . PG_NAME . ";host=" . PG_HOST . ";port=" . PG_PORT, PG_USER, PG_PASSWD, array(PDO::ATTR_PERSISTENT => true));
	    }
	    catch (PDOException $e) {
	        die ( "postgresql connection failure:" . $e->getMessage () );
	    }
		return $dbh;
    }

    function jira_upload_pdoMultiInsertOnIgnore($tableName, $data) {
		$pdoObject = get_pg_connect();
	    //Will contain SQL snippets.
	    $rowsSQL = array();
	    //Will contain the values that we need to bind.
	    $toBind = array();
	    //Get a list of column names to use in the SQL statement.
	    $columnNames = array_keys($data[0]);
	    //Loop through our $data array.
	    foreach ($data as $arrayIndex => $row) {
	        $params = array();
	        foreach ($row as $columnName => $columnValue) {
	            $param = ":" . $columnName . $arrayIndex;
	            $params[] = $param;
	            $toBind[$param] = $columnValue;
	        }
	        $rowsSQL[] = "(" . implode(", ", $params) . ")";
	    }
		$sql = "INSERT  INTO $tableName  (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);
        //Prepare our PDO statement.
	    $pdoStatement = $pdoObject->prepare($sql);
	    //Bind our values.
	    foreach($toBind as $param => $val){
			$pdoStatement->bindValue($param, $val);
		}
	    //Execute our statement (i.e. insert the data).
	    // return $pdoStatement->execute();
		$pdoStatement->execute();
	    return $pdoStatement->rowCount();

    }
 