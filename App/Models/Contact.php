<?php

namespace App\Models;


use Core\BaseModel;

class Contact extends BaseModel
{
    const TABLE_NAME = 'contacts';

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
     * @return bool
     */
    public function importCsv($fileName, $userId)
    {
        $query = "LOAD DATA LOCAL INFILE '" . str_replace('\\', '/', $fileName) . " '
            INTO TABLE contacts
            COLUMNS TERMINATED BY ',' 
            (@first_name,@last_name,@phone,@email) 
            SET
            user_id=$userId,first_name=@first_name,last_name=@last_name,phone=@phone,email=@email";

        return $this->query($query);
    }
}