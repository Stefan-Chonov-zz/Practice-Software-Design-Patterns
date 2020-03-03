<?php

namespace Core;

use Core\Helpers\SqlHelper;
use Core\Interfaces\RepositoryInterface;

class Model implements RepositoryInterface
{
    private $log;
    private $sqlHelper;

    protected $db;
    protected $table;

    /**
     * Model constructor.
     * @param \PDO $db
     * @param string $tableName
     */
    public function __construct($db, $table)
    {
        $this->log = new Log(env('LOG_PATH'));
        $this->sqlHelper = new SqlHelper();
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
            $data = $this->sqlHelper->prepareParameters($model);
            $query = "INSERT INTO " . $this->table . " (" . join(',', array_keys($model)) . ") VALUES (" . join(',', array_keys($data)) . ")";
            $stmt = $this->db->prepare($query);

            $insertedRowID = 0;
            if ($stmt->execute($data)) {
                $insertedRowID = $this->db->lastInsertId();
            }

            return $insertedRowID;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * Get entry by id
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
            $stmt = $this->db->query($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
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
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
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
                $updateStatementAliases = $this->sqlHelper->prepareAliases($model);
                $query = "UPDATE " . $this->table . " SET $updateStatementAliases WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $data = $this->sqlHelper->prepareParameters($model);
                $stmt->execute($data);
            }
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
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
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}