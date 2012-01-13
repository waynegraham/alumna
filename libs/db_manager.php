<?php

class DatabaseManager
{
  var $conn   = null;
  var $error  = null;
  var $dbhost = null;
  var $dbname = null;
  var $dbuser = null;
  var $dbpass = null;

  function __construct($setup = array())
  {
    $this->dbname = $setup['name'];
    $this->dbhost = $setup['host'];
    $this->dbuser = $setup['username'];
    $this->dbpass = $setup['password'];

    if($setup['port']) {
      $this->dbhost .= ':' . $setup['port'];
    }

    $this->conn = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass, true);
    @mysql_select_db($this->dbname, $this->conn);

  }

  function query($sql)
  {
    return mysql_query($sql, $this->conn);
  }

  function to_s()
  {
    return $this->conn;
  }

  function close()
  {
    return mysql_close($this->conn);
  }

  function escape($value)
  {
    return mysql_real_escape_string($value);
  }
}
