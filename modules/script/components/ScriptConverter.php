<?php

namespace app\modules\script\components;

use app\modules\core\components\Publishable;
use app\modules\script\models\ar\Group;
use app\modules\script\models\ar\GroupVariant;
use app\modules\script\models\ar\Release;
use app\modules\script\models\ar\Script;
use app\modules\script\models\ar\Node;
use app\modules\script\models\ar\Variant;
use app\modules\script\models\Call;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidParamException;
use Yii;
use app\modules\core\helpers\UUID;

/**
 * Class ScriptConverter
 */
class ScriptConverter extends Component
{

    /**
     * @param $id
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public static function convert($id)
    {
        /** @var Script $old */
        $old = Script::findOne($id);

        if (!$old) {
            throw new InvalidParamException('Конвертируются только существующие скрипты.');
        }

        $make_release = false;

        if ($old->status_id == Publishable::STATUS_PUBLISHED) {
            $make_release = true;
        }

        Node::deleteAll('script_id=' . $old->id);
        Variant::deleteAll('script_id=' . $old->id);
        Group::deleteAll('script_id=' . $old->id);
        GroupVariant::deleteAll('script_id=' . $old->id);
        Release::deleteAll('script_id=' . $old->id);

        $old->v2converted = 1;
        $old->build = null;
        $old->latest_release = null;

        $data = json_decode($old->data_json, true);

        /** @var Variant[] $variants_map */
        $variants_map = [];

        /** @var Node[] $nodes_map */
        $nodes_map = [];

        if (isset($data['nodes']) && $nodes_count = count($data['nodes'])) {
            for ($i = 0; $i < $nodes_count; $i++) {

                $node_old = $data['nodes'][$i];

                $number = (int)$node_old["id"];

                if (isset($nodes_map[$number])) {
                    continue;
                }

                $node_new = new Node();
                $node_new->id = UUID::v4();
                $node_new->content = !empty($node_old["content_formatted"]) ? $node_old["content_formatted"] : $node_old["content"];
                $node_new->content = mb_substr($node_new->content, 0, 4000, 'utf-8');
                if (!$node_new->content) {
                    $node_new->content = 'узел';
                }
                $node_new->left = round($node_old["left"]);
                $node_new->top = round($node_old["top"]);
                $node_new->number = $number;
                $node_new->call_stage_id = !empty($node_old["call_stage_id"]) ? $node_old["call_stage_id"] : null;
                $node_new->is_goal = !empty($node_old["is_goal"]) ? $node_old["is_goal"] : 0;
                $node_new->normal_ending = !empty($node_old["normal_ending"]) ? $node_old["normal_ending"] : 0;
                $node_new->script_id = $old->id;

                if (!$node_new->save()) {
                    throw new Exception('Не удалось сохранить узел ' . $node_new->number . ': ' . implode(', ', $node_new->getFirstErrors()));
                }

                $nodes_map[$number] = $node_new;

                if ($old->start_node_id == $number) {
                    $old->start_node_uuid = $node_new->id;
                }

                if (isset($node_old['columns']) && $columns_count = count($node_old['columns'])) {

                    for ($j = 0; $j < $columns_count; $j++) {

                        $variant_old = $node_old['columns'][$j];
                        $variant_new = new Variant();
                        $variant_new->id = UUID::v4();
                        $variant_new->content = $variant_old['content'] ? $variant_old['content'] : 'ответ';
                        $variant_new->content = mb_substr($variant_new->content, 0, 100, 'utf-8');
                        $variant_new->old_id = $variant_old['id'];
                        $variant_new->node_id = $node_new->id;
                        $variant_new->script_id = $old->id;

                        if (!$variant_new->save()) {
                            throw new Exception('Не удалось сохранить вариант ответа ' . $variant_new->old_id . ': ' . implode(', ', $variant_new->getFirstErrors()));
                        }

                        $variants_map[$variant_new->old_id] = $variant_new;
                    }
                }
            }
        }

        if (isset($data['edges']) && $edges_count = count($data['edges'])) {
            foreach ($variants_map as $variant) {
                for ($i = 0; $i < $edges_count; $i++) {

                    $edge = $data['edges'][$i];

                    list(, $variant_id) = explode('.', $edge['source']);

                    $variant_target = trim(str_replace('.head', '', $edge['target']));

                    if ($variant_id == $variant->old_id) {

                        if (empty($nodes_map[$variant_target])) {
                            throw new Exception('Не удалось найти узел в скрипте ' . $variant->script_id . ' с номером ' . $variant_target);
                        }

                        $node = $nodes_map[$variant_target];

                        $variant->target_id = $node->id;

                        if (!$variant->save()) {
                            throw new Exception('Не удалось сохранить вариант ответа ' . $variant->old_id . ': ' . implode(', ', $variant->getFirstErrors()));
                        }

                        break;
                    }
                }
            }
        }


        if ($old->common_cases) {

            $stages = Call::getStages();

            $common_cases = json_decode($old->common_cases, true);

            /** @var GroupVariant[] $variants_with_no_stage */
            $variants_with_no_stage = [];

            $variants_sorted_by_stages = [];

            foreach ($common_cases as $case) {

                $target_id = null;

                if (!empty($case['target'])) {

                    if (empty($nodes_map[$case['target']])) {
                        throw new Exception('Не удалось найти узел в скрипте ' . $old->id . ' с номером ' . $case['target']);
                    }

                    $target_id = $nodes_map[$case['target']]->id;
                }

                $group_variant = Yii::createObject([
                    'class' => GroupVariant::className(),
                    'id' => UUID::v4(),
                    'script_id' => $old->id,
                    'group_id' => null,
                    'target_id' => $target_id,
                    'content' => $case['text'] ? substr($case['text'], 2, 120) : 'ответ',
                    'deleted_at' => null
                ]);

                if (!empty($case['stage'])) {
                    if (empty($variants_sorted_by_stages[$case['stage']])) {
                        $variants_sorted_by_stages[$case['stage']] = [];
                    }

                    $variants_sorted_by_stages[$case['stage']][] = $group_variant;
                } else {
                    $variants_with_no_stage[] = $group_variant;
                }

            }

            $top = 0;
            $left = 0;

            if (count($variants_with_no_stage)) {
                $common_group = Yii::createObject([
                    'class' => Group::className(),
                    'id' => UUID::v4(),
                    'script_id' => $old->id,
                    'top' => $top,
                    'left' => $left,
                    'name' => 'Универсальные ответы',
                    'deleted_at' => null
                ]);

                if (!$common_group->save()) {
                    throw new Exception(implode(', ', $common_group->getFirstErrors()));
                }

                // Прописываем группу во всех узлахз
                Node::updateAll(['groups' => $common_group->id], 'script_id=' . $old->id);

                foreach ($variants_with_no_stage as $v) {
                    $v->group_id = $common_group->id;

                    if (!$v->save()) {
                        throw new Exception(implode(', ', $v->getFirstErrors()));
                    }
                }
            }

            if (count($variants_sorted_by_stages)) {

                /** @var GroupVariant[] $stage_group_variants */

                foreach ($variants_sorted_by_stages as $stage_id => $stage_group_variants) {

                    if (count($stage_group_variants)) {
                        $top += 100;
                        $left += 100;

                        $stage_group = Yii::createObject([
                            'class' => Group::className(),
                            'id' => UUID::v4(),
                            'script_id' => $old->id,
                            'top' => $top,
                            'left' => $left,
                            'name' => mb_substr($stages[$stage_id], 0, 30, 'utf-8'),
                            'deleted_at' => null
                        ]);

                        if (!$stage_group->save()) {
                            throw new Exception(implode(', ', $stage_group->getFirstErrors()));
                        }

                        // Прописываем группу во всех узлах этого этапа
                        foreach (Node::find()->where('script_id=' . $old->id . ' AND call_stage_id=' . $stage_id)->all() as $n) {
                            if ($n->groups) {
                                $n->groups .= ',' . $stage_group->id;
                            } else {
                                $n->groups = $stage_group->id;
                            }
                            $n->update(false, ['groups']);
                        }


                        foreach ($stage_group_variants as $v) {
                            $v->group_id = $stage_group->id;

                            if (!$v->save()) {
                                throw new Exception(implode(', ', $v->getFirstErrors()));
                            }
                        }
                    }
                }
            }
        }


        $old->update(false, ['v2converted', 'start_node_uuid', 'build', 'latest_release']);

        /**
         * This is the model class for table "script_release".
         *
         * @property integer $id
         * @property integer $script_id
         * @property string $name
         * @property string $version
         * @property string $build
         * @property integer $created_at
         * @property integer $deleted_at
         *
         * @property Script $script
         */
        if ($make_release) {
            $r = new Release();
            $r->script_id = $old->id;
            $r->name = '';
            $r->version = 1;
            $r->build = $old->getBuild();
            $r->save(false);
        }
    }
}