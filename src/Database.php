<?php

namespace App;

use \PDO;
use PDOException;

class Database extends PDO {

    function __construct() {
        try {

            parent::__construct(
                $_ENV['DB_TYPE'].':host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'].';charset=utf8',
                $_ENV['DB_USER'],
                $_ENV['PASS']
            );
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo 'Exception abgefangen: '. $e->getMessage() . "\n";
        }
    }


    /**
     * @param $prepared_sql
     * @param array $data
     * @return array|false
     */
    public function select($prepared_sql, array $data = array())
    {
        $statement = $this->prepare($prepared_sql);
        foreach($data as $key => $value) {
            $value = $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : $value;
            $statement->bindValue(':'.$key, $value);
        }
        $this->execute($statement);
        return $statement->fetchAll();
    }

    /**
     * insert method
     * @param string $table table name
     * @param array $data  array of columns and values
     */
    public function insert(string $table, array $data): string
    {
        ksort($data);

        $table = strtolower($table);
        $fieldNames = implode(', ', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $stmt = $this->prepare(" INSERT INTO $table ($fieldNames) VALUES ($fieldValues) ");

        foreach ($data as $key => $value) {
            if(is_array($value)){
                $value = json_encode($value);
            }
            $stmt->bindValue(":$key", $value);
        }

        $this->execute($stmt);
        return $this->lastInsertId();
    }

    /**
     * update method
     * @param string $table table name
     * @param array $data  array of columns and values
     * @param array $where array of columns and values
     */
    public function update(string $table, array $data, array $where): int
    {
        ksort($data);

        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :field_$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :where_$key";
            } else {
                $whereDetails .= " AND $key = :where_$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        $stmt = $this->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":field_$key", $value);
        }

        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }

        $this->execute($stmt);
        return $stmt->rowCount();
    }

    /**
     * Delete method
     * @param string $table table name
     * @param array $where array of columns and values
     * @param integer $limit limit number of records
     * @return int
     */
    public function delete(string $table, array $where, int $limit = 1): int
    {
        ksort($where);

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $uselimit = "LIMIT $limit";
        }

        $stmt = $this->prepare("DELETE FROM $table WHERE $whereDetails $uselimit");

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $this->execute($stmt);
        return $stmt->rowCount();
    }

    public function truncate($table) {
        try {
            $this->exec("TRUNCATE TABLE $table");
            return $table;
        } catch (PDOException $e){
            return 'Exception abgefangen: '. $e->getMessage() . "\n";
        }
    }

    private function execute($statement){
        try {
            return $statement->execute();
        } catch(PDOException $e) {
            return 'Exception abgefangen: '. $e->getMessage() . "\n";
        }
    }

}

