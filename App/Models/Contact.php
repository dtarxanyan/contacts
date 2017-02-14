<?php

namespace App\Models;


use Core\BaseModel;
use App\Models\Process;

class Contact extends BaseModel
{
    const TABLE_NAME = 'contacts';

    /**
     * @param array $criteria
     * @param array $params
     * @return array
     */
    public function getList($criteria, $params = [])
    {
        $sql = $this->createQueryFromCriteria($criteria);
        $data = $this->findAll($sql, $params);
        $totalCount = $this->find("SELECT FOUND_ROWS()", [], \PDO::FETCH_COLUMN);

        return [
            'data' => $data,
            'total' => $totalCount,
        ];
    }


    /**
     * @param string $fileName
     * @param int $userId
     * @param string $processId
     */
    public function importCsv($fileName, $userId, $processId)
    {
        set_time_limit(0);
        $fp = file($fileName);
        $rowsCount = count($fp);
        $handle = fopen($fileName, 'r');

        if ($handle) {

            $completedCount = 0;
            $failedCount = 0;
            $success = true;
            $process = new Process();

            try {
                $process->start([
                    'process_id' => $processId,
                    'type' => Process::TYPE_IMPORT_CONTACTS,
                    'total_count' => $rowsCount,
                ]);

                while (($line = fgets($handle)) !== false) {

                    $params = $this->createParamsFromCsvLine($userId, $line);

                    try {
                        $this->insertContact($params);
                        $completedCount++;
                    } catch (\PDOException $e) {
                        $failedCount++;
                        error_log($e->getMessage(), 3, 'log.txt');
                    }

                    $process->updateCompletedCount([
                        'process_id' => $processId,
                        'completed_count' => $completedCount,
                        'failed_count' => $failedCount,
                    ]);
                }

            } catch (\PDOException $e) {
                error_log($e->getMessage(), 3, 'log.txt');
                $success = false;
            }

            $process->end($processId, $success);
        }

        fclose($handle);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function insertContact($params)
    {
        $sql = "INSERT INTO contacts (user_id, first_name, last_name, phone, email)"
            . " VALUES" .
            " (:user_id, :first_name, :last_name, :phone, :email)";

        return $this->query($sql, $params);
    }

    /**
     * @param int $userId
     * @param string $line
     * @return array
     */
    private function createParamsFromCsvLine($userId, $line)
    {
        $columns = explode(",", $line);
        static $colsMap = ['first_name' => 0, 'last_name' => 1, 'phone' => 2, 'email' => 3];

        return [
            'user_id' => $userId,
            'first_name' => $columns[$colsMap['first_name']],
            'last_name' => $columns[$colsMap['last_name']],
            'phone' => $columns[$colsMap['phone']],
            'email' => $columns[$colsMap['email']],
        ];
    }
}