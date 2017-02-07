<?php

// manages mysql connection
class SQL {

	private $conn, $lastq;
	public $query_count = 0;

	// constructor. connects upon class formation.
	function SQL ($server, $user, $pw, $db, $persist) {

		// connect
		$this->conn = (
			$persist ? @mysql_pconnect($server, $user, $pw) :
						@mysql_connect($server, $user, $pw))
			or exit ("Error Connecting to MySQL: ".mysql_error()."\n");

		// select db
		@mysql_select_db($db, $this->conn) or
			exit ("Error Selecting MySQL DB: ".mysql_error() . "\n");

		return true;

	}

	// run a query
	function query($q) {
		// run it
		$this->lastq = @mysql_query($q, $this->conn) or
			$this->error_message ("Query Error: ".mysql_error()."\n");

		// increment query count
		$this->query_count++;

		// return result
		return $this->lastq;
	}

	// run an unbuffered query. (used only a few times)
	function query_unbuff($q)
	{
		// run it
		$this->lastq = @mysql_unbuffered_query($q, $this->conn) or
			$this->error_message ("Query Error: ".mysql_error()."\n");

		// increment query count
		$this->query_count++;

		// return result
		return $this->lastq;

	}

	// free a result
	function free($r) {
		@mysql_free_result($r);
	}

	// free last query result
	function freelast() {
		if (is_resource($this->conn) && is_resource($this->lastq))
			@mysql_free_result($this->lastq);
	}

	// get result as a numerical array
	function fetch_row($r = null) {
		$r = @$r ? $r : $this->lastq;
		return @mysql_fetch_row($r);
	}

	// get result as a associative array
	function fetch_assoc($r = null) {
		$r = @$r ? $r : $this->lastq;
		return @mysql_fetch_assoc($r);
	}
	
	// get result as array
	function fetch_array($r = null) {
		$r = @$r ? $r : $this->lastq;
		return @mysql_fetch_array($r);
	}

	// close mysql connection
	function close() {
		if (is_resource($this->conn))
			mysql_close($this->conn);
	}

	// escape
	function prot($s) {
		return ctype_digit($s) ? $s : mysql_real_escape_string($s, $this->conn);
	}

	// last id
	function lastid() {
		return mysql_insert_id($this->conn);
	}

	// rows affected by last update/replace/delete/insert
	function affected_rows() {
		return mysql_affected_rows($this->conn);
	}

	// show error message
	function error_message() {

		// halt whilst showing the message
		exit ("MySQL Error: \n".mysql_error($this->conn)."\n");

	}

	// are we connected?
	function is_connected() {
		return is_resource($this->conn);
	}

	// return number of results
	function num($r) {
		return mysql_num_rows($r);
	}
}

?>