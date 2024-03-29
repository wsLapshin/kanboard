<div class="page-header">
    <h2><?= t('Stop a Timer') ?></h2>
</div>
<form class="popover-form" 
      method="post" 
      action="<?= $this->url->href('SubtaskStatusController', 'timerStop', 
          array('plugin' => 'timetrackingeditor', 
                'project_id' => $values['project_id'], 
                'task_id' => $values['task_id'],
                'subtask_id'=>$values['subtask_id'])) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('project_id', $values) ?>
    <?= $this->form->hidden('task_id', $values) ?>
    <?= $this->form->hidden('subtask_id', $values) ?>
    <?php $values['is_form_request'] = 1 ?>
    <?= $this->form->hidden('is_form_request', $values) ?>

    <?= t('Subtask') ?>
    <?= $values['subtask']['title'] ?>

    <?= $this->form->label(t('Comment'), 'comment') ?>
    <?= $this->form->textarea('comment', $values, $errors, array(), 'markdown-editor') ?>

    <?= $this->form->checkbox('is_billable', t('Billable?'), 1, isset($values['is_billable']) && $values['is_billable'] == 1) ?>

    <div class="form-actions">
        <?= $this->modal->submitButtons() ?>
    </div>
</form>
