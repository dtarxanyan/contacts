<?php

namespace App\Models;

use Core\BaseModel;

class Process extends BaseModel
{
    const TABLE_NAME = 'processes';
    const TYPE_IMPORT_CONTACTS = 'import_contacts';

    /**
     * @param int $userId
     * @param string $type
     * @return string
     */
    public static function createId($userId, $type)
    {
        return md5($userId . $type . time());
    }

    /**
     * @param array $params
     * @return bool
     */
    public function start($params)
    {
        $sql = "INSERT INTO " . self::TABLE_NAME
            . " (process_id, type, total_count, status)"
            . " VALUES "
            . " (:process_id, :type, :total_count, 0)";

        return $this->query($sql, $params);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function updateCompletedCount($params)
    {
        $sql = "UPDATE " . self::TABLE_NAME . " SET completed_count = :completed_count, failed_count = :failed_count"
            . " WHERE process_id = :process_id AND status = 0";

        return $this->query($sql, $params);
    }

    /**
     * @param string $processId
     * @param bool $success
     * @return bool
     */
    public function end($processId, $success = true)
    {
        $sql = "UPDATE " . self::TABLE_NAME . " SET status=1, success=:success WHERE status=0 AND process_id = :id";

        return $this->query($sql, [
            'id' => $processId,
            'success' => (int)$success,
        ]);
    }

    /**
     * @param string $procId
     * @return bool
     */
    public function getOngoingProcess($procId)
    {
        $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE process_id = :id";
        return $this->find($sql, ['id' => $procId]);
    }
}