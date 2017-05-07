<?php

namespace app\modules\user\components;

use app\modules\integration\modules\amo\components\AmoApi;
use AmoCRM\Lead;
use app\modules\integration\modules\amo\models\AmoUser;
use app\modules\integration\modules\amo\components\AmoRequest;
use AmoCRM\Contact;

class AmoLeadCreator
{
    /**
     * Subscribe user to unisender
     */
    public static function create($id, $name, $phone, $email)
    {
        if ($phone == '123456') {
            return;
        }

        $credentials = new AmoUser();
        $credentials->user_id = 'system';
        $credentials->amouser = '77770516@mail.ru';
        $credentials->subdomain = 'iprofil';
        $credentials->amohash = 'c00fd6b545f8733326a589dcd05bd97a';
        $api = new AmoApi($credentials);
        $api->auth();

        $lead = new Lead();
        $lead->setName($email);
        $lead->setStatusId(8001556); // зарегистрирован
        $result_lead = $api->request(new AmoRequest(AmoRequest::SET, $lead));

        if (!empty($result_lead->result->leads->add[0]->id)) {
            $contact = new Contact();
            $contact->setName($name)
                ->setLinkedLeadsId($result_lead->result->leads->add[0]->id)
                ->setResponsibleUserId(703512)// Барчан
                ->setCustomField(1757634, $id)
                ->setCustomField(1300876, $phone, 'MOB')
                ->setCustomField(1300878, $email, 'WORK')
                ->setCustomField(1757636, date('Y.m.d H:i:s'));

            $api->request(new AmoRequest(AmoRequest::SET, $contact));
        }
    }
}