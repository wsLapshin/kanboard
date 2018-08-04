<section class="accordion-section <?= empty($files) && empty($images) ? 'accordion-collapsed' : '' ?>">
    <div class="accordion-title">
        <h3><a href="#" class="fa accordion-toggle"></a> <?= t('Attachments') ?></h3>
        <?php if ($this->projectRole->canUpdateTask($task)): ?>
             &nbsp;&nbsp;&nbsp;
            <?= $this->modal->medium('plus', t('Attach a document'), 'TaskFileController', 'create', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
        <?php endif;?>
    </div>
    <div class="accordion-content">
        <?= $this->render('task_file/images', array('task' => $task, 'images' => $images)) ?>
        <?= $this->render('task_file/files', array('task' => $task, 'files' => $files)) ?>
    </div>
</section>
