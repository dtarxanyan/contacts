<?php
namespace App\Resources;

use App\Models\Contact as ContactModel;

class Contact
{
    /**
     * @param array $data
     * @return array
     */
    public static function getList($data = [])
    {
        $criteria = [];
        $criteria["select"] = "SELECT SQL_CALC_FOUND_ROWS *";
        $criteria["from"] = ContactModel::TABLE_NAME;

        $params = [];
        $criteria['where'][] = "1";

        if (isset($data['user_id'])) {
            $params['user_id'] = $data['user_id'];
            $criteria['where'][] = "AND user_id = :user_id";
        }

        if (isset($data['search'])) {
            $params['search'] = '%' . $data['search'] . '%';
            $criteria['where'][] = "AND ("
                . " first_name LIKE :search"
                . " OR last_name LIKE :search"
                . " OR email LIKE :search"
                . " OR phone LIKE :search"
                . " )";
        }

        if (isset($data['order_by']) && isset($data['order_type'])) {
            $criteria['order'] = $data['order_by'] . ' ' . $data['order_type'];
        }

        if (isset($data['limit'])) {
            $criteria['limit'] = $data['limit'];
        }

        if (isset($data['offset'])) {
            $criteria['offset'] = $data['offset'];
        }

        $model = new ContactModel();

        $result = $model->getList($criteria, $params);

        return [
            'data' => $result['data'],
            'recordsTotal' => $result['total'],
            'recordsFiltered' => $result['total'],
        ];
    }
}