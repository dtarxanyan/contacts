<?php

namespace App\Controllers;

use App\Models\Contact;
use Core\Response\HtmlResponse;
use App\Resources\Contact as ContactResource;
use Core\Response\JsonResponse;
use Core\Validation\File as FileValidation;

class Contacts extends Controller
{
    public function list()
    {
        $this->checkLoginStatus('list');
        return new HtmlResponse();
    }

    public function ListJson()
    {
        $this->checkLoginStatus('listJson');

        static $colsMap = ['id', 'first_name', 'last_name', 'phone', 'email'];
        $filters = [
            'user_id' => $_SESSION['login']
        ];

        if (isset($_POST['search']['value']) && $_POST['search']['value']) {
            $filters['search'] = $_POST['search']['value'];
        }

        if (isset($_POST['order'])) {
            $colIndex = (int)$_POST['order'][0]['column'];
            $filters['order_by'] = $colsMap[$colIndex];

            $orderType = strtoupper($_POST['order'][0]['dir']);

            if (in_array($orderType, ['ASC', 'DESC'])) {
                $filters['order_type'] = $orderType;
            } else {
                $filters['order_type'] = 'DESC';
            }
        } else {
            $filters['order_by'] = 'id';
            $filters['order_type'] = 'DESC';
        }

        $filters['offset'] = (int)$this->getPost('start');
        $filters['limit'] = (int)$this->getPost('length', 10);

        $data = ContactResource::getList($filters);

        return new JsonResponse($data);
    }

    public function upload()
    {
        ini_set('max_execution_time', 0);
        $uploadDir = $this->getUploadsFolder();

        if (!is_writable($uploadDir)) {
            return new JsonResponse([
                'status' => 0,
                'error' => 'CSV upload failed'
            ]);
        }

        $ext = pathinfo($_FILES['contacts']['name'], PATHINFO_EXTENSION);
        static $allowedExt = ['csv'];
        static $allowedTypes = ['application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv'];

        if (in_array($ext, $allowedExt)) {
            $newFileName = uniqid() . '.' . $ext;
            $filePath = $_FILES['contacts']['tmp_name'];
            $newPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($filePath, $newPath)) {

                return new JsonResponse([
                    'status' => 0,
                    'error' => 'Something went wrong'
                ]);
            } else {
                $validator = new FileValidation();
                $options = [
                    FileValidation::OPTION_ALLOWED_EXT => $allowedExt,
                    FileValidation::OPTION_ALLOWED_TYPES => $allowedTypes,
                    FileValidation::OPTION_MAX_SIZE => 2,
                ];

                if ($validator->validate($newPath, $options)) {

                    return new JsonResponse([
                        'status' => 1,
                        'file' => $newPath,
                    ]);
                } else {
                    unlink($newPath);

                    return new JsonResponse([
                        'status' => 0,
                        'error' => $validator->getErrorMessage(),
                    ]);
                }
            }
        } else {
            return new JsonResponse([
                'status' => 0,
                'error' => 'only ' . implode(',', $allowedExt) . 'allowed'
            ]);
        }
    }

    public function importCsv()
    {
        $this->checkLoginStatus('importCsv');

        $filePath = $this->getPost('file');

        $model = new Contact();
        $model->importCsv($filePath, $_SESSION['login'] /*  User id  */);

        return new JsonResponse([
            'success' => 1,
            'file' => $filePath
        ]);
    }

    public function import()
    {
        $this->checkLoginStatus('import');
        return new HtmlResponse();
    }
}