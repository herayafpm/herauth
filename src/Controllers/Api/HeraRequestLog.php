<?php

namespace Raydragneel\Herauth\Controllers\Api;

use Raydragneel\Herauth\Models\HerauthRequestLogModel;

class HeraRequestLog extends BaseHerauthAuthResourceApi
{
    protected $modelName = HerauthRequestLogModel::class;

    public function datatable()
    {
        $this->herauth_grant("request_log.post_datatable");
        $data = $this->getDataRequest();
        $like = [];
        $orLike = [];
        if(!empty($data['search']['value'])){
            $like['username'] = $data['search']['value'];
            $orLike['client'] = $data['search']['value'];
            $orLike['path'] = $data['search']['value'];
        }
        $where = [];
        if(isset($data['today'])){
            $where['created_at >='] = date("Y-m-d")." 00:00:00";
            $where['created_at <='] = date("Y-m-d")." 23:59:59";
        }
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Label.requestLog")]);
        return $this->respond($this->datatable_get(['like' => $like,'orLike' => $orLike,'where' => $where]), 200);
    }
}