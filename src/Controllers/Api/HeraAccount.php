<?php

namespace Raydragneel\Herauth\Controllers\Api;

class HeraAccount extends BaseHerauthResourceApi
{

    public function profil()
    {
        herauth_grant("user_account.get_profil");
        $herauth = service('Herauth');
        $account = $herauth->getAccount();
        $profil = $account->profil;
        if($profil){
            $profil = $profil->toArray();
        }
        return $this->respond(['status' => true,'message' => lang("Api.successRetrieveRequest",[lang("Label.account")]),'data' => $profil ?? []], 200);
    }
    public function notifications()
    {
        herauth_grant("user_account.get_notifications");
        $herauth = service('Herauth');
        $account = $herauth->getAccount();
        $data = $this->getDataRequest();
        $limit = -1;
        $offset = 0;
        $notif_in = '';
        if (isset($data['limit'])) {
            $limit = (int) $data['limit'];
        }
        if (isset($data['offset'])) {
            $offset = (int) $data['offset'];
        }
        if (isset($data['notif_in'])) {
            $notif_in = $data['notif_in'];
        }
        $notifications = $account->getNotifications($limit,$offset,$notif_in);
        return $this->respond(['status' => true,'message' => lang("Api.successRetrieveRequest",[lang("Label.notifications")]),'data' => $notifications ?? []], 200);
    }
}