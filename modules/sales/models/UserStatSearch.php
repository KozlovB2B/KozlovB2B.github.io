<?php

namespace app\modules\sales\models;

use app\modules\user\components\OldProfile;
use app\modules\user\models\profile\Head;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use app\modules\core\components\ExcelExport;
use juffin_halli\dataProviderIterator\DataProviderIterator;

/**
 * UserStatSearch represents the model behind the search form about `app\modules\sales\models\UserStat`.
 */
class UserStatSearch extends UserStat
{

    /** @var string */
    public $username;

    /** @var string */
    public $phone;

    /** @var string */
    public $email;

    /** @var integer */
    public $registered_at;

    /** @var string */
    public $division;

    /** @inheritdoc */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'username' => 'Логин',
            'phone' => 'Телефон',
            'email' => 'Email',
            'division' => 'Дивизион',
            'registered_at' => 'Дата регистрации'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['id', 'current_balance', 'scripts_created', 'current_scripts_count', 'current_nodes_count', 'logins_today', 'logins_yesterday', 'logins_week', 'executions_today', 'executions_yesterday', 'executions_week'],
                'number',
                'numberPattern' => '/^(>|<|>=|<=|=|<>|)\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/',
            ],
            [['username', 'phone', 'email', 'registered_at', 'last_login', 'division'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserStat::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->last_login) {
            $date = strtotime($this->last_login);
            $query->andFilterWhere(['between', UserStat::tableName() . '.last_login', $date, $date + 3600 * 24]);
        }

        $query->andFilterCompare(UserStat::tableName() . '.id', $this->id);
        $query->andFilterCompare(UserStat::tableName() . '.current_balance', $this->current_balance);
        $query->andFilterCompare(UserStat::tableName() . '.scripts_created', $this->scripts_created);
        $query->andFilterCompare(UserStat::tableName() . '.current_scripts_count', $this->current_scripts_count);
        $query->andFilterCompare(UserStat::tableName() . '.current_nodes_count', $this->current_nodes_count);
        $query->andFilterCompare(UserStat::tableName() . '.logins_today', $this->logins_today);
        $query->andFilterCompare(UserStat::tableName() . '.logins_yesterday', $this->logins_yesterday);
        $query->andFilterCompare(UserStat::tableName() . '.logins_yesterday', $this->logins_yesterday);
        $query->andFilterCompare(UserStat::tableName() . '.logins_week', $this->logins_week);
        $query->andFilterCompare(UserStat::tableName() . '.executions_today', $this->executions_today);
        $query->andFilterCompare(UserStat::tableName() . '.executions_yesterday', $this->executions_yesterday);
        $query->andFilterCompare(UserStat::tableName() . '.executions_week', $this->executions_week);

        $query->joinWith(['user' => function (ActiveQuery $q) {
            $q->andFilterWhere(['like', 'user.username', $this->username]);
            $q->andFilterWhere(['like', 'user.email', $this->email]);

            if ($this->registered_at) {
                $date = strtotime($this->registered_at);
                $q->andFilterWhere(['between', 'user.created_at', $date, $date + 3600 * 24]);
            }
        }]);

        $query->joinWith(['userHeadManager' => function (ActiveQuery $q) {
            $q->andFilterWhere(['like', 'SiteUserHeadManager.phone', $this->phone]);
            $q->andFilterWhere(['=', 'SiteUserHeadManager.division', $this->division]);
        }]);

        return $dataProvider;
    }

    /**
     * As excel
     *
     * @param ActiveDataProvider $dataProvider
     * @throws \yii\db\Exception
     */
    public function asExcel(ActiveDataProvider $dataProvider)
    {
        $filename = 'customers_' . date('d.m.Y');

        $excel = new ExcelExport();

        Yii::$app->getDb()->createCommand("SET NAMES cp1251")->execute();

        $data = new DataProviderIterator($dataProvider, 1000);

        $excel->totalCol = 17;
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Id'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Логин'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Имя'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Телефон'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Email'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Дата регистрации'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Текущий баланс'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Всего скриптов'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Сейчас скриптов'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Сейчас узлов'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Логинов сегодня'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Логинов вчера'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Логинов за неделю'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Прогонов сегодня'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Прогонов вчера'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Прогонов за неделю'));
        $excel->InsertText(iconv('UTF-8', 'CP1251', 'Последний вход'));

        $excel->GoNewLine();

        /** @var UserStat $model */
        foreach ($data as $model) {
            $excel->InsertText($model->id);
            $excel->InsertText($model->user ? $model->user->username : null);

            $profile = OldProfile::findOne($model->id);
            if ($profile) {
                $excel->InsertText($profile->name);
            } else {
                $head = Head::findOne($model->id);
                if ($profile) {
                    $excel->InsertText($head->first_name);
                } else {
                    $excel->InsertText(null);
                }
            }

            $excel->InsertText($model->userHeadManager ? $model->userHeadManager->phone : null);
            $excel->InsertText($model->user ? $model->user->email : null);
            $excel->InsertText(!empty($model->user->created_at) ? date('d.m.Y H:i:s', $model->user->created_at) : null);
            $excel->InsertText($model->current_balance);
            $excel->InsertText($model->scripts_created);
            $excel->InsertText($model->current_scripts_count);
            $excel->InsertText($model->current_nodes_count);
            $excel->InsertText($model->logins_today);
            $excel->InsertText($model->logins_yesterday);
            $excel->InsertText($model->logins_week);
            $excel->InsertText($model->executions_today);
            $excel->InsertText($model->executions_yesterday);
            $excel->InsertText($model->executions_week);
            $excel->InsertText(!empty($model->last_login) ? date('d.m.Y H:i:s', $model->last_login) : null);

            $excel->GoNewLine();
        }

        $excel->SaveFile($filename);

    }
}
