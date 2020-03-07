<?php

namespace App\Core;

use App\Core\Helpers\SqlHelper;
use App\Core\Interfaces\ModelInterface;

class Model implements ModelInterface
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
     * @param array $data
     * @return int
     */
    public function create($data)
    {
        try {
            $parametersAliases = $this->sqlHelper->prepareParameters($data);
            $query = "INSERT INTO " . $this->table . " (`" . join("`,`", array_keys($data)) . "`) VALUES (" . join(',', array_keys($parametersAliases)) . ")";
            $stmt = $this->db->prepare($query);
            foreach ($parametersAliases as $key => $value) {
                $stmt->bindParam($key, $value);
            }

            $insertedRowID = 0;
            if ($stmt->execute()) {
                $insertedRowID = $this->db->lastInsertId();
            }

            return $insertedRowID;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * Get entry by id
     * @param $data
     * @return mixed
     */
    public function get($data = [])
    {
        try {
            $query = "SELECT * FROM " . $this->table;
            $query .= count($data) > 0 ? " WHERE " . $this->sqlHelper->whereAnd($data) : '';
            $stmt = $this->db->prepare($query);
            $stmt->execute($this->sqlHelper->prepareParameters($data));
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * Update entry
     * @param $data
     * @return mixed|void
     */
    public function update($data)
    {
        try {
            $user = $this->get([ 'id' => $data['id'] ]);
            if ($user) {
                $query = "UPDATE " . $this->table . " SET " . $this->sqlHelper->prepareAliases($data) . " WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $data = $this->sqlHelper->prepareParameters($data);
                $stmt->execute($data);

                return $stmt->rowCount();
            }
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function delete($data)
    {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE " . $this->sqlHelper->prepareAliases($data);
            $stmt = $this->db->prepare($query);
            $stmt->execute($this->sqlHelper->prepareParameters($data));

            return $stmt->rowCount();
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}