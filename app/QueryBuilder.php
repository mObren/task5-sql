<?php
namespace App;

use App\Database;

class QueryBuilder
{

    public $fields = [];
    protected $conditions = [];
    protected $orderBy;
    protected $limit;
    protected $tableName;
    protected $groupBy;
    protected $join = [];
    protected $relation;



    public function __toString()
    {
        $where = $this->conditions === [] ? '' : ' WHERE ' . implode(' AND ', $this->conditions);
        $orderBy = $this->orderBy === null ? '' : ' ORDER BY ' . $this->orderBy;
        $limit = $this->limit === null ? '' : ' LIMIT ' . $this->limit;
        $groupBy = $this->groupBy === null ? '' : ' GROUP BY ' . $this->groupBy;
        $join = $this->join === [] ? '' : implode(" ", $this->join);
        $relation = $this->relation === null ? '' : $this->relation;
        $tableName = $this->tableName;
        
        $sql = 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . $tableName
            . $relation
            . $join
            . $where
            . $groupBy
            . $orderBy
            . $limit;

        $this->conditions = [];
        $this->orderBy = null;
        $this->limit = null;
        $this->groupBy = null;
        $this->join = [];
        $this->relation = null;
        $this->tableName = null;

        
        return $sql;
    }
     public function resetObject() 
    {
        foreach ($this as $key => $value) {
            $this->$key = null;
        }
    }

    public function from(string $tableName) 
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function columns(string ...$columns)
    {
        $this->fields = $columns;
        return $this;
    }

    public function where(string ...$where)
    {
        foreach ($where as $condition) {
            $this->conditions[] = $condition;
        }
        return $this;
    }

    public function orderBy($column, $order)
    {
        $this->orderBy = $column . " " . $order;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function groupBy($column)
    {
        $this->groupBy = $column;
        return $this;
    }

    public function join($type, $table, $relation)
    {
        $this->join[] = " " . $type . " JOIN $table on (" . $relation . ")";
        return $this;
    }

    public function whereRelation($table, $relation, $condition)
    {
        $this->relation = " INNER JOIN $table on ($relation) WHERE ($condition)";
        return $this;
    }
    public function get($data = [])
    {
        $db = new Database;
        return $db->read($this->__toString(), $data);
    }

}
