<?php

namespace app\modules\script\components;

use app\modules\core\helpers\UUID;
use app\modules\script\models\ar\Group;
use app\modules\script\models\ar\GroupVariant;
use app\modules\script\models\ar\Node;
use app\modules\script\models\ar\Variant;
use Yii;
use yii\base\Exception;
use app\modules\script\models\ar\Script;
use app\modules\core\components\Publishable;
use app\modules\user\models\UserHeadManager;

/**
 * Class V2Importer
 * Импортер скриптов второй версии
 *
 * @package app\modules\script\components
 */
class V2Importer
{
    /**
     * Импортиует скрипт со структурой данных версии 2
     *
     * @param array $data
     * @return Script
     * @throws Exception
     */
    public static function import($data)
    {
        $groups_map = [];
        $nodes_map = [];
        $check_doubles_map = [];
        $head_manager = UserHeadManager::findHeadManagerByUser();

        /** @var Script $script */
        $script = Yii::createObject([
            'class' => Script::className(),
            'user_id' => $head_manager->id,
            'status_id' => Publishable::STATUS_DRAFT,
            'v2converted' => 1,
            'import_id' => $data['script']['id'],
            'original_id' => $data['script']['original_id'],
            'name' => $data['script']['name'],
            'performer_options' => !empty($data['script']['performer_options']) ? $data['script']['performer_options'] : null,
            'editor_options' => !empty($data['script']['editor_options']) ? $data['script']['editor_options'] : null,
            'max_node' => $data['script']['max_node']
        ]);

        $script->modifyNameAsCopy();

        $t = Yii::$app->getDb()->beginTransaction();

        try {
            if (!$script->save()) {
                throw new Exception(implode(',', $script->getFirstErrors()));
            }

            // Конвертируем группы ответов
            if (!empty($data['groups'])) {
                foreach ($data['groups'] as $g) {
                    $groups_map[trim($g['id'])] = UUID::v4();

                    $g['class'] = Group::className();
                    $g['id'] = $groups_map[trim($g['id'])];
                    $g['script_id'] = $script->id;

                    /** @var Group $group = */
                    $group = Yii::createObject($g);

                    if (!$group->save()) {
                        throw new Exception(implode(',', $group->getFirstErrors()));
                    }
                }
            }

            // Импортируем узлы. 
            if (!empty($data['nodes'])) {
                foreach ($data['nodes'] as $n) {
                    $nodes_map[$n['id']] = UUID::v4();

                    $n['class'] = Node::className();
                    $n['id'] = $nodes_map[$n['id']];
                    $n['script_id'] = $script->id;

                    // Идентификаторы групп нужно сконвертировать в новые
                    $groups = [];

                    if (!empty($n['groups'])) {
                        foreach (explode(',', $n['groups']) as $gid) {
                            if (!empty($groups_map[trim($gid)])) {
                                $groups[] = $groups_map[trim($gid)];
                            }
                        }
                    }

                    $n['groups'] = count($groups) ? implode(',', $groups) : null;

                    /** @var Node $node */
                    $node = Yii::createObject($n);

//                    if(isset($check_doubles_map[$node->number])){
//                        continue;
//                    }
//
//                    $check_doubles_map[$node->number] = $node;

                    if (!$node->save()) {
                        throw new Exception(implode(',', $node->getFirstErrors()));
                    }
                }
            }

            // Импортируем варианты групп 
            if (!empty($data['group_variants'])) {
                foreach ($data['group_variants'] as $gv) {
                    $gv['class'] = GroupVariant::className();
                    $gv['id'] = UUID::v4();
                    $gv['script_id'] = $script->id;
                    $gv['group_id'] = !empty($groups_map[trim($gv['group_id'])]) ? $groups_map[trim($gv['group_id'])] : null;
                    $gv['target_id'] = !empty($nodes_map[trim($gv['target_id'])]) ? $nodes_map[trim($gv['target_id'])] : null;

                    /** @var GroupVariant $group_variant */
                    $group_variant = Yii::createObject($gv);

                    if (!$group_variant->save()) {
                        throw new Exception(implode(',', $group_variant->getFirstErrors()));
                    }
                }
            }

            // Импортируем варианты групп 
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $v) {
                    $v['class'] = Variant::className();
                    $v['id'] = UUID::v4();
                    $v['script_id'] = $script->id;
                    $v['node_id'] = !empty($nodes_map[trim($v['node_id'])]) ? $nodes_map[trim($v['node_id'])] : null;
                    $v['target_id'] = !empty($nodes_map[trim($v['target_id'])]) ? $nodes_map[trim($v['target_id'])] : null;

                    /** @var Variant $variant */
                    $variant = Yii::createObject($v);

                    if (!$variant->save()) {
                        throw new Exception(implode(',', $variant->getFirstErrors()));
                    }
                }
            }

            $t->commit();
        } catch (\Exception $e) {
            $t->rollBack();
            throw new Exception($e->getMessage());
        }

        if (!empty($data['script']['start_node_uuid'])) {
            $script->start_node_uuid = !empty($nodes_map[trim($data['script']['start_node_uuid'])]) ? $nodes_map[trim($data['script']['start_node_uuid'])] : null;
            $script->update(false, ['start_node_uuid']);
        }

        return $script;
    }
}