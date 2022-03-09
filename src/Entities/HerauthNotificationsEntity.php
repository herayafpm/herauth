<?php

namespace Raydragneel\Herauth\Entities;

use Raydragneel\Herauth\Models\HerauthNotificationsModel;

class HerauthNotificationsEntity extends BaseHerauthEntity
{
    protected $notification_model = null;
    public function __construct(array $data = null)
    {
        parent::__construct($data);
        $this->notification_model = model(HerauthNotificationsModel::class);
    }
    protected $datamap = [];
    protected $dates   = [
        'notif_created_at',
        'notif_updated_at',
        'notif_deleted_at',
    ];
    protected $casts   = [];

}
