<?php

namespace app\modules\integration\modules\amo\components;

use AmoCRM\Lead;
use AmoCRM\Contact;
use Yii;

class AmoLead
{
    /**
     * @param AmoApi $api
     * @param $name
     * @param $phone
     * @param $email
     * @throws \Exception
     */
    public static function create(AmoApi $api, $name, $phone, $email)
    {
        $lead = new Contact();
        $lead->setName($name)
            ->setCustomField('Телефон', $phone, 'MOB')
            ->setCustomField('Email', $email, 'WORK');

        $api->request(new AmoRequest(AmoRequest::SET, $lead));
    }
}