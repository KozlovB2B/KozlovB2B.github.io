<script type="jtk" id="tplNode">
    <div class="jp-table node" data-isgoal="${is_goal}">
        <div class="node-functions text-center">

        <div class="node-function delete pull-left" title="<?= Yii::t('script', 'Delete node') ?>">
                <i class="fa fa-times"/>
            </div>

            <div class="node-id">#<span class="node-id-number">${id}</span></div>



            <div class="node-function jp-table-edit pull-right" title="<?= Yii::t('script', 'Edit') ?>">
                <i class="fa fa-pencil"/>
            </div>

            <div title="<?= Yii::t('script', 'Try a call from this node') ?>" class="node-function jp-table-try-call pull-right">
                <i class="fa fa-phone"/>
            </div>

            <div title="<?= Yii::t('script', 'Copy node') ?>" class="node-function jp-table-copy pull-right">
                <i class="fa fa-copy"/>
            </div>
        </div>
        <div class="name node-content" data-port-id="head">
            <jtk-target port-id="head" port-type="target"></jtk-target>
            <span>${content}</span>

            <div class="node-call-stage" data-id="${call_stage_id}"></div>
        </div>

        <ul class="jp-table-columns answers_list">
            <r-each in="columns">
                <r-tmpl id="tplEdge"/>
            </r-each>
        </ul>

        <div class="script___edge__add_button_container script___edge__add_button" title="<?= Yii::t('script', 'Add a new answer') ?>"> <i class="fa fa-plus"/>  <?= Yii::t('script', 'Add a new answer') ?></div>
    </div>

</script>

<script type="jtk" id="tplEdge">
    <li class="jp-table-column jp-table-column-type-integer" data-port-id="${id}">
        <div class="jp-table-column-edit">
            <i class="fa fa-pencil jp-table-column-edit-icon"/>
        </div>
        <div class="jp-table-column-delete">
            <i class="fa fa-times jp-table-column-delete-icon"/>
        </div>
        <div>${content}</div>
        <jtk-source port-id="${id}" port-type="source" filter=".jp-table-column-delete, .jp-table-column-delete-icon, span, .jp-table-column-edit, .jp-table-column-edit-icon" filter-exclude="true"/>
    </li>

</script>