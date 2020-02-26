<?php

namespace Core;

use Core\Interfaces\RepositoryInterface;

class ModelRepository implements RepositoryInterface
{
    protected $db;
    protected $table;

    /**
     * Model constructor.
     * @param string $tableName
     */
    public function __construct($db, $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    /**
     * Create entry
     * @param array $model
     * @return int
     */
    public function create($model)
    {
        try {
            $data = $this->prepareParameters($model);
            $query = "INSERT INTO " . $this->table . " (" . join(',', array_keys($model)) . ") VALUES (" . join(',', array_keys($data)) . ")";
            $stmt = $this->db->prepare($query);

            $insertedRowID = 0;
            if ($stmt->execute($data)) {
                $insertedRowID = $this->db->lastInsertId();
            }

            return $insertedRowID;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * Get entry by id
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
            $stmt = $this->db->query($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * Get all entries
     * @return mixed
     */
    public function getAll()
    {
        try {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->db->query($query);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $results;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * Update entry
     * @param $model
     * @return mixed|void
     */
    public function update($model)
    {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
            $stmt = $this->db->query($query);
            $stmt->bindParam(':id', $model['id'], \PDO::PARAM_INT);
            $result = $stmt->fetch();
            if (isset($result) && !empty($result)) {
                $updateStatementAliases = $this->aliases($model);
                $query = "UPDATE " . $this->table . " SET $updateStatementAliases WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $data = $this->prepareParameters($model);
                $stmt->execute($data);
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function delete($id)
    {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([ ':id' => $id]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param $parameters
     * @return array
     */
    private function prepareParameters($parameters)
    {
        try {
            $result = [];
            foreach ($parameters as $key => $value) {
                $result[':' . $key] = $value;
            }

            return $result;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param $parameters
     * @return string
     */
    private function aliases($parameters)
    {
        try {
            $result = [];
            foreach ($parameters as $key => $value) {
                $result[$key] = $key . '=:' . $key;
            }

            return join(',', $result);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}