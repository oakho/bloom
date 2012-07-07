<?php

namespace Bloom\Database;

use Bloom\Database\Database;

class Query
{
  /**
   * Hold the queries stack
   * @var array
   */
  protected static $_queriesStack = [];

  /**
   * Hold the current request
   * @var string
   */
  protected $_query = null;

  /**
   * Hold the table
   * @var string
   */
  protected $_table = null;

  public function __construct($table)
  {
    $this->_table = (string) $table;
  }

  public function select($columns)
  {
    $columns = ($columns === "*") ? "*" : "`". implode("`, `", $columns) ."`";
    $this->_query = "SELECT {$columns} \nFROM `{$this->_table}` \n";

    return $this;
  }

  public function insert($columns, $values)
  {
    $columns = implode(", ", $columns);
    $values = "'". implode("', '", $values) ."'";

    $this->_query = "INSERT INTO `$this->_table` ($columns) \n\tVALUES ($values) \n";

    return $this;
  }

  public function update($columns)
  {
    $this->_query = "UPDATE `{$this->_table}` SET {$columns} \n";

    return $this;
  }

  public function delete()
  {
    $this->_query = "DELETE FROM `{$this->_table}` \n";

    return $this;
  }

  public function where($column, $operator = "=", $value)
  {
    $this->_query .= "WHERE ";
    $this->_query .= "`{$this->_table}`.`{$column}` {$operator} '{$value}' \n";

    return $this;
  }

  public function andWhere($column, $operator = "=", $value)
  {
    $this->_query .= " AND `{$this->_table}`.`{$column}` {$operator} '{$value}' \n";

    return $this;
  }

  public function orderby($columns)
  {
    $this->_query .= "ORDER BY {$columns} \n";

    return $this;
  }

  public function join($joinTable, $column1, $column2, $type = "INNER") {
    $this->_query .= "{$type} JOIN `{$joinTable}` \n  ON `{$this->_table}`.`{$column1}` = `{$joinTable}`.`{$column2}` \n";

    return $this;
  }

  public function exec()
  {
    array_push(static::$_queriesStack, $this->_query);

    $query = Database::$pdo->prepare($this->_query);

    if($query->execute()) {
      return $query;
    } else {
      throw new \Exception("SQL query failed : ". $this->_query, 1);
    }
  }

  public function lastInsertId()
  {
    return Database::$pdo->lastInsertId();
  }

  public static function getQueriesStack()
  {
    return static::$_queriesStack;
  }
}