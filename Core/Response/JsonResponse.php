<?php
namespace Core\Response;

class JsonResponse extends  AbstractResponse
{
    public function render()
    {
        echo json_encode($this->getContent());
    }

}