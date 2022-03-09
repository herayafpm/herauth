<?php

namespace Raydragneel\Herauth\Models;

use Raydragneel\Herauth\Entities\HerauthNotificationsEntity;

class HerauthNotificationsModel extends BaseHerauthModel
{
	protected $table                = 'herauth_notifications';
	protected $primaryKey           = 'notif_id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = HerauthNotificationsEntity::class;
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = ['account_id','notif_judul','notif_isi','notif_url','notif_read','notif_app','notif_deleted_at'];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'notif_created_at';
	protected $updatedField         = 'notif_updated_at';
	protected $deletedField         = 'notif_deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
}
