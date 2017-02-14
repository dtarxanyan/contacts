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

            $process = new Process();
            $process->start([
                'process_id' => $processId,
                'type' => Process::TYPE_IMPORT_CONTACTS,
                'total_count' => $rowsCount,
            ]);

            $completedCount = 0;

            while (($line = fgets($handle)) !== false) {
                $columns = explode(",", $line);

                $sql = "INSERT INTO contacts (user_id, first_name, last_name, phone, email)"
                    . " VALUES (:user_id, :first_name, :last_name, :phone, :email)";

                $this->query($sql, [
                    'user_id' => $userId,
                    'first_name' => $columns[0],
                    'last_name' => $columns[1],
                    'phone' => $columns[2],
                    'email' => $columns[3],
                ]);

                $completedCount++;

                $process->updateCompletedCount([
                    'process_id' => $processId,
                    'completed_count' => $completedCount

                ]);
            }

            $process->end($processId);
        }

        fclose($handle);
    }
}