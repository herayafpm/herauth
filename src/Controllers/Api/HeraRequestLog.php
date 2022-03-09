<?php

namespace Raydragneel\Herauth\Controllers\Api;

use Raydragneel\Herauth\Models\HerauthRequestLogModel;

class HeraRequestLog extends BaseHerauthAuthResourceApi
{
    protected $modelName = HerauthRequestLogModel::class;

    public function datatable()
    {
        herauth_grant("request_log.post_datatable");
        $data = $this->getDataRequest();
        $like = [
            'username' => $data['search']['value'] ?? '',
        ];
        $orLike = [
            'client' => $data['search']['value'] ?? '',
            'path' => $data['search']['value'] ?? '',
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.requestLog")]);
        return $this->respond($this->datatable_get(['like' => $like,'orLike' => $orLike]), 200);
    }
}