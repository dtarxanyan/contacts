<?php

namespace App\Controllers;

use App\Models\Contact;
use App\Models\Process;
use Core\Response\HtmlResponse;
use App\Resources\Contact as ContactResource;
use Core\Response\JsonResponse;
use Core\Validation\File as FileValidation;

class Contacts extends Controller
{
    /**
     * Renders contacts list page
     * @return HtmlResponse
     */
    public function list()
    {
        $this->checkLoginStatus('list');
        return new HtmlResponse();
    }

    /**
     * Returns sorted and filtered data
     * @return JsonResponse
     */
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

    /**
     * Contacts CSV upload
     * @return JsonResponse
     */
    public function upload()
    {
        ini_set('max_execution_time', 0);
        $uploadDir = $this->getUploadsFolder();

        try {
            if (!is_writable($uploadDir)) {
                throw new \Exception("Uploads directory should be writable");
            }

            static $allowedTypes = ['application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv'];
            static $allowedExt = ['csv'];

            $ext = pathinfo($_FILES['contacts']['name'], PATHINFO_EXTENSION);

            if (in_array($ext, $allowedExt)) {
                $newFileName = uniqid() . '.' . $ext;
                $filePath = $_FILES['contacts']['tmp_name'];
                $newPath = $uploadDir . $newFileName;

                if (!move_uploaded_file($filePath, $newPath)) {
                    throw new \Exception('Something whent wrong');
                } else {
                    $validator = new FileValidation();
                    $options = [
                        FileValidation::OPTION_ALLOWED_EXT => $allowedExt,
                        FileValidation::OPTION_ALLOWED_TYPES => $allowedTypes,
                        FileValidation::OPTION_MAX_SIZE => 60,
                    ];

                    if ($validator->validate($newPath, $options)) {
                        $result = [
                            'status' => 1,
                            'file' => $newPath,
                        ];
                    } else {
                        unlink($newPath);
                        throw new \Exception($validator->getErrorMessage());
                    }
                }
            } else {
                throw new \Exception('only ' . implode(',', $allowedExt) . ' file extensions allowed');
            }
        } catch (\Exception $e) {
            $result = [
                'status' => 0,
                'message' => $e->getMessage(),
            ];
        }

        return new JsonResponse($result);
    }

    /**
     * Imports CSV file into database
     * @return JsonResponse
     */
    public function importCsv()
    {
        $this->checkLoginStatus('importCsv');
        $filePath = str_replace('\\', '/', $this->getPost('file'));
        $userId = $_SESSION['login'];
        $processId = Process::createId($userId, Process::TYPE_IMPORT_CONTACTS);
        $argString = $filePath . ' ' . $userId . ' ' . $processId;
        $scriptPath = str_replace('\\', '/', BASE_PATH . '/App/Commands/import_contacts.php');

        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen('start /B php.exe ' . $scriptPath . ' ' . $argString . ' 2>nul >nul', "r"));
        } else {
            exec($scriptPath . ' ' . $argString . ' 2>nul >nul');
        }

        return new JsonResponse(['status' => 1, 'process_id' => $processId]);
    }

    /**
     * Renders contacts CSV upload and import page
     * @return HtmlResponse
     */
    public function import()
    {
        $this->checkLoginStatus('import');
        return new HtmlResponse();
    }

    /**
     * Real time info about ongoing contacts importing
     * @return JsonResponse
     */
    public function processInfo()
    {
        $model = new Process();
        $processId = $_GET['process_id'];
        $info = $model->getOngoingProcess($processId);

        if ($info) {
            $result = [
                'status' => 1,
                'total_count' => $info['total_count'],
                'completed_count' => $info['completed_count']
            ];
        } else {
            $result = ['status' => 0];
        }

        return new JsonResponse($result);
    }
}