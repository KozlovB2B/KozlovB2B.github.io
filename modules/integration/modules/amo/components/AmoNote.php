<?php

namespace app\modules\integration\modules\amo\components;

use app\modules\core\components\Url;
use app\modules\script\models\Call;
use AmoCRM\Note;
use Yii;

class AmoNote
{
    public $id;
    public $last_modified;
    public $type;

    public function setUpdate($id, $last_modified)
    {
        $this->id = $id;
        $this->last_modified = $last_modified;

        return $this;
    }

    /**
     * Creating note for Amo
     *
     * @param AmoApi $api
     * @param $contact_id
     * @param Call $call
     * @throws \Exception
     */
    public static function create(AmoApi $api, $contact_id, Call $call)
    {
        $note = new Note();
        $note->setElementId($contact_id)
            ->setElementType(Note::TYPE_CONTACT)
            ->setNoteType(Note::COMMON)
            ->setText(self::createNoteText($call));

        $api->request(new AmoRequest(AmoRequest::SET, $note));
    }


    /**
     * Generating note text
     *
     * @param Call $call
     * @return string
     */
    protected static function createNoteText(Call $call)
    {
        $result = Yii::t('amo', 'Call by script "{script_name}": duration - {duration}, result - {result}. More info: {url}', [
            'script_name' => $call->script->name,
            'date' => Yii::$app->getFormatter()->asDate($call->started_at),
            'duration' => Yii::$app->getFormatter()->asDuration($call->duration),
            'result' => $call->is_goal_reached ? Yii::t('amo', 'Aim reached') : Yii::t('amo', 'Aim not reached'),
            'url' => Url::to([
                '/script/call/view',
                'id' => $call->id
            ], true),
        ]);

        return $result;
    }
}