<?php
/*
PDO SQLite Klasse
Florian Gerhardt
02.10.2014
*/

class SQLite 
{
	static protected $DB;
	static protected $Result;
	
	/*	
		Opened the Database, Create it if not exists
		Params: Databasename
		Returned: Nothing
	*/
	public function __construct($dbname)
	{
		$this->DB = new SQLite3($dbname.'.db');
		
		if(!file_exists($dbname.'.db'))
		{
			die('[SQLite] Database is not Created');
		}
	}
	
	/*	
		Close the Connection to Database
		Params: Noting
		Returned: Nothing
	*/
	public function __destruct()
	{
		$this->DB->close();
	}
	
	/*	
		Send a Query to the Database
		Params: SQL Query
		Returned: Output of Query
	*/
	public function Query($Query)
	{
		$type = strpos($Query,'SELECT');
		if($type === false) 
		{
			$this->Result = $this->DB->exec($Query);
		} 
		else
		{
			$type = strpos($Query,'SELECT * ');
			if($type === false)
			{
				$this->Result = $this->DB->querySingle($Query);
			}
			else
			{
				$this->Result = $this->DB->query($Query);
			}
		}
		return $this->Result;
	}
	
	/*
		Count the Data Sets
		Params: The name of the SQLite Table
		Returned: The Row Count
	*/
	public function RowCount($table)
	{
		$this->Result = $this->Query("SELECT COUNT(*) as count FROM ".$table);
		return $this->Result;
	}

	/*
		Get the ID of the Last Dataset
		Params: Nothing
		Returned: The id of the last Dataset
	*/
	public function LastInsertID()
	{
		return $this->DB->lastInsertRowID();
	}
	
	/*
		Select all Data from Table
		Params: SQL Query
		Returned: An Array with the Data
	*/
	public function FetchArray($query)
	{
		if($query !== null)
			$this->Query($query);
		
		$res = $this->Result->fetchArray(SQLITE3_ASSOC);
		return $res;
	}
	
	/*
		Escape any Value
		Params: The Value that have to Escape
		Returnes: The escaped Value
	*/
	public function EscapeString($string)
	{
		return $this->DB->escapeString($string);
	}
	
	/*
		Get the Error Code of the last failure Query
		Params: Nothing
		Returnes: Errorcode, 0 if success
	*/
	public function GetErrorCode()
	{
		return $this->DB->lastErrorCode();
	}
	
	/*
		Get the Error Message of the last failure Query
		Params: Nothing
		Returnes: Errormessage
	*/
	public function GetErrorMessage()
	{
		return $this->DB->lastErrorMsg ();
	}
}
?>
