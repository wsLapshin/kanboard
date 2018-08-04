<section class="accordion-section <?= empty($subtasks) ? 'accordion-collapsed' : '' ?>">
    <div class="accordion-title">
        <h3>
            <a href="#" class="fa accordion-toggle"></a> <?= t('Sub-Tasks') ?>
            <?php if ($this->projectRole->canUpdateTask($task)): ?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?= $this->modal->medium('clock-o', t('Add a sub-task long'), 'SubtaskController', 'create', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
            <?php endif ?>
        </h3>
    </div>
    <div class="accordion-content">
        <?= $this->render('subtask/table', array(
            'subtasks' => $subtasks,
            'task' => $task,
            'editable' => $editable
        )) ?>
    </div>
</section>
