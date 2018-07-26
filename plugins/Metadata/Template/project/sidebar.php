<?php if ($this->user->hasProjectAccess('metadata', 'index', $project['id'])): ?>
    <li>
        <?= $this->url->link(t('Metadata'), 'MetadataController', 'project', ['plugin' => 'metadata', 'project_id' => $project['id']]) ?>
    </li>
<?php endif ?>