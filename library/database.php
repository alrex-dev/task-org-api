<?php
namespace Library;

/**
 * Database class
 * 
 * Manipulates database records 
 * Retrieve, update, insert records
 * Handles database connection
 *
 * ----------------------------------------------------
 * @Author: 		Alrex Consus
 * @Date Created:	November, 2009
 * @Version: 		1.0
 * ----------------------------------------------------
 */

class Database {

	/**
	 * Holds the SQL string that is going to be executed
	 * By default it is empty string
	 */
	var $sql = '';
	
	/**
	 * Holds the connection string to mysql
	 * By default it is NULL
	 */
	var $connection_string = NULL;
	
	/**
	 * Holds the resource of that last query executed
	 * By default it is NULL
	 */
	var $resource = NULL;
	
	/**
	 * Holds the error no when query execution encounters error
	 * By default it is 0
	 */
	var $error_no = 0;
	
	/**
	 * Holds the error message when query execution encounters error
	 * By default it is empty string
	 */
	var $error_msg = '';
	
	/**
	 * Holds the insert id of the last insert sql executed
	 * By default it is 0
	 */
	var $last_insert_id = 0;
	
	/**
	 * Holds the global error object
	 * By default it is NULL
	 */
	var $error_obj = NULL;
	
	var $error_descriptions = array(
		"NOMYSQLCONNECT" 		=> "Connection: Problem in php version!"
		,"CANNOTCONNECT" 		=> "Problem in database connection!"
		,"DBNAMEDOESNOTEXIST" 	=> "Problem in selecting database!"
		,"PROBLEMINQUERY" 		=> "Problem in query!"
		,"NOMYSQLCLOSE" 		=> "Disconnection: Problem in php version!"
		,"CANNOTDISCONNECT" 	=> "Problem in database disconnection!"
	);
	
	
	/**
	 * The Constructor class
	 * Initiate MySQL database connection and selection of database
	 *
	 * @Parameter: $params (Object)	- Database connection parameter, declared in configuration file. Default is NULL.
	 */
	function __construct( $params = NULL ) {
		if ( !function_exists( 'mysqli_connect' ) ) {
			//Showing error that no `mysqli_connect` function in the PHP version used
			$this->_raiseError( 'NOMYSQLCONNECT', true );
		}
		
		//if ( !( $this->connection_string = @mysqli_connect( $params->host, $params->username, $params->password ) ) ) {
        if (!($this->connection_string = @mysqli_connect( $params->host, $params->username, $params->password ))) {
			//Showing error that the system cannot create a connection to the database
			$this->_raiseError( 'CANNOTCONNECT', true );
		}
		
		//if ( $params->db != '' && !mysqli_select_db( $params->db, $this->connection_string ) ) {
        if ($params->db != '' && !mysqli_select_db( $this->connection_string, $params->db )) {
			//Showing error that the database name cannot be found with the connection string
			$this->_raiseError( 'DBNAMEDOESNOTEXIST', true );
		}
		
		mysqli_set_charset( $this->connection_string, 'utf8' );
	}
	
	/**
	 * Sets SQL string to be executed
	 *
	 * @Parameter: $sql (String)	- The SQL string to be exectued. Default is empty string.
	 */
	function _setSQL( $sql = '' ) {
		$this->sql = $sql;
	}
	
	/**
	 * Escapes qoutes found in sql string
	 *
	 * @Parameter: $sql (String)	- The SQL string to be escaped
	 *
	 * Returns an escaped SQL string
	 */
	function _escapeSQLString( $sql ) {
		// Use the appropriate escape string depending upon which version of php
		// you are running
		if ( version_compare( phpversion(), '4.3.0', '<' ) ) {
			$sql = mysqli_escape_string( $sql );
		} else 	{
			$sql = mysqli_real_escape_string( $this->connection_string, $sql );
		}
		
		return $sql;
	}
	
	/**
	 * Executes the SQL string set
	 * Saves the insert id to $last_insert_id if it is an INSERT SQL
	 *
	 * Returns the records or resource of the executed SQL String.
	 */
	function _executeSQL() {
		try {
			$this->resource = $this->connection_string->query( $this->sql );

			if ($this->resource === FALSE) {
				throw new Exception($this->connection_string->error);
			}

			if ( strpos( strtolower( $this->sql ), 'insert' ) !== false ) {
				$this->last_insert_id = mysqli_insert_id( $this->connection_string );
			} else {
				$this->last_insert_id = 0;
			}
			
			return $this->resource;
		} catch(Exception $e) {
			$this->error_no = mysqli_errno( $this->connection_string );
			$this->error_msg = mysqli_error( $this->connection_string );
			
			return false;
		}

		//$this->resource = @mysqli_query( $this->connection_string, $this->sql );

		/*
		if ( !$this->resource ) {
			$this->error_no = mysqli_errno( $this->connection_string );
			$this->error_msg = mysqli_error( $this->connection_string );
			
			return false;
		} else {
			
			if ( strpos( strtolower( $this->sql ), 'insert' ) !== false ) {
				$this->last_insert_id = mysqli_insert_id( $this->connection_string );
			} else {
				$this->last_insert_id = 0;
			}
			
			return $this->resource;
		}
		*/
	}
	
	/**
	 * Executes SQL string and gets the query results
	 *
	 * Returns array recordset. Each record is in object format.
	 */
	function _getQueryResults() {
		/**
		 * TO DO
		 * -------------
		 * Check here if the SQL string starts with SELECT statement
		 */
		 
		$result = $this->_executeSQL();
		
		if ( !$result ) {
			//Showing error that there's a problem in query
			$this->_raiseError( 'PROBLEMINQUERY', true, $this->error_msg );
		}
		
		$array = array();
		
		while ( $row = mysqli_fetch_object( $result ) ) {
			/*if ( $key ) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}*/
			$array[] = $row;
		}
		
		mysqli_free_result( $result );
		
		return $array;
	}
	
	/**
	 * Executes SQL string and gets only the first record of query results
	 *
	 * Returns only one row or record. Record is in object format.
	 */
	function _getQuerySingleResult() {
		/**
		 * TO DO
		 * -------------
		 * Check here if the SQL string starts with SELECT statement
		 */
		 
		$result = $this->_executeSQL();
		
		if ( !$result ) {
			//Showing error that there's a problem in query
			$this->_raiseError( 'PROBLEMINQUERY', true, $this->error_msg );
		}
		
        $data = null;
        
		while ( $row = mysqli_fetch_object( $result ) ) {
			$data = $row;
		}
		
		mysqli_free_result( $result );
		
		return $data;
	}
	
	
	/**
	 * Gets SQL string set
	 *
	 * Returns SQL string.
	 */
	function _getSQL() {
		return $this->sql;
	}
	
	/**
	 * Gets error details of the executed query
	 *
	 * Returns array of error details. Only Error No and Error Message
	 */
	function _getError() {
		$error = array(
			"no" 		=> $this->error_no,
			"message" 	=> $this->error_msg
		);
		
		return $error;
	}
	
	/**
	 * Gets last insert id of the last INSERT SQL executed
	 *
	 * Returns the last insert id.
	 */
	function _getLastInsertID() {
		return $this->last_insert_id;
	}
	
	/**
	 * Closes the database connection
	 */
	function _close() {
		if ( !function_exists( 'mysqli_close' ) ) {
			//Showing error that no `mysqli_close` function found in the PHP version used
			$this->_raiseError( 'NOMYSQLCLOSE', true );
		}
		
		if ( !@mysqli_close( $this->connection_string ) ) {
			//Showing error that the system cannot close the database connection
			$this->_raiseError( 'CANNOTDISCONNECT', true );
		}
	}
	
	function _createSELECTStatement() {
	}
	
	function _createINSERTStatement() {
	}
	
	function _createUPDATEStatement() {
	}
	
	function _createSQLFilters() {
	}
	
	function _getErrorDesc( $code = '' ) {
		foreach( $this->error_descriptions as $key => $desc ) {
			if ( $code == $key ) return $desc;
		}
		
		return '';
	}
	
	/**
	 * Raises error message using the error object
	 * 
	 * @Parameter: $err_code (String)	- Error Code
	 * @Parameter: $level (string)
	 */
	function _raiseError( $err_code, $level, $desc_append = '' ) {
		$error_desc = $this->_getErrorDesc( $err_code );
		
		if ( trim( $desc_append ) ) {
			$error_desc .= ' '.$desc_append;
		}
		//TO DO : Check routine here, something is wrong
		if ( $level == 'critical' ) {
			echo $error_desc;
			exit();
		} else {
			//add to debugger
		}
	}
}
?>