<?php

namespace Inc\Database;

use PDO;

abstract class QueryBuilder
{
    /**
     * The PDO instance.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * The table name.
     *
     * @var string
     */
    protected $table;

    /**
     * The where clause.
     *
     * @var string
     */
    protected $where;

    /**
     * The order by clause.
     *
     * @var string
     */
    protected $orderBy;

    /**
     * The limitation clause.
     *
     * @var string
     */
    protected $limit;

    /**
     * Create a new QueryBuilder instance.
     *
     * @param PDO $pdo
     *
     * @throws \ReflectionException
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->table = $this->table ?? strtolower((new \ReflectionClass($this))->getShortName()).'s';
    }

    /**
     * Adding set of conditions as a string.
     *
     * @param array $conditions
     *
     * @return $this
     */
    public function where($conditions)
    {
        $this->where = ' where '.implode(', ', $conditions);

        return $this;
    }

    /**
     * Adding order query string.
     *
     * @param string $column
     * @param string $order
     *
     * @return $this
     */
    public function orderBy($column, $order = 'ASC')
    {
        $this->orderBy = " order by $column $order";

        return $this;
    }

    /**
     * Adding the limitation clause.
     *
     * @param int $start
     * @param int $end
     *
     * @return $this
     */
    public function limit($start, $end)
    {
        $this->limit = " limit {$start},{$end}";

        return $this;
    }

    /**
     * Select all records from a database table.
     *
     * @param array $columns
     *
     * @return array
     */
    public function selectAll($columns = [])
    {
        $fields = $columns ? implode(', ', $columns) : '*';
        $sql = "select $fields from {$this->table}";
        $statement = $this->executeStatement($sql);

        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select all records from a database table.
     *
     * @param array $columns
     *
     * @return array
     */
    public function select($columns = [])
    {
        $fields = $columns ? implode(', ', $columns) : '*';
        $sql = $this->attachClauses("select $fields from {$this->table}");
        $statement = $this->executeStatement($sql);

        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Select single record from a database table.
     *
     * @param array $columns
     *
     * @return array
     */
    public function selectOne($columns = [])
    {
        $this->limit(0, 1);
        $fields = $columns ? implode(', ', $columns) : '*';
        $sql = $this->attachClauses("select {$fields} from {$this->table}");
        $statement = $this->executeStatement($sql);

        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * Insert a record into a table.
     *
     * @param array $parameters
     *
     * @return bool|\PDOStatement
     */
    public function insert($parameters)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $this->table,
            implode(', ', array_keys($parameters)),
            ':'.implode(', :', array_keys($parameters))
        );

        try {
            $this->executeStatement($sql, $parameters);
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * Update specific record(s).
     *
     * @param array $parameters
     *
     * @return bool|\PDOStatement
     */
    public function update($parameters)
    {
        $values = array_map(function ($key, $value) {
            return "{$key} = {$value}";
        }, array_keys($parameters), $parameters);
        $sql = $this->attachClauses(sprintf('update %s set %s', $this->table, implode(', ', $values)));

        try {
            return $this->executeStatement($sql);
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * Delete record(s) from table.
     *
     * @return bool|\PDOStatement
     */
    public function delete()
    {
        $sql = $this->attachClauses("delete from {$this->table}");

        try {
            return $this->executeStatement($sql);
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * Prepare and execute statement.
     *
     * @param string $sql
     * @param array  $parameters
     *
     * @return bool|\PDOStatement
     */
    protected function executeStatement($sql, $parameters = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);

        return $statement;
    }

    /**
     * Attach the extra clauses to the query.
     *
     * @param string $sql
     *
     * @return string
     */
    protected function attachClauses($sql)
    {
        if ($this->where) {
            $sql .= $this->where;
        }
        if ($this->orderBy) {
            $sql .= $this->orderBy;
        }
        if ($this->limit) {
            $sql .= $this->limit;
        }

        return $sql;
    }
}
