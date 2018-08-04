<section class="accordion-section <?= empty($task['description']) ? 'accordion-collapsed' : '' ?>">
    <div class="accordion-title">
        <h3>
            <a href="#" class="fa accordion-toggle"></a> <?= t('Description') ?>
            <?php if ($this->projectRole->canUpdateTask($task)): ?>
            &nbsp;&nbsp;&nbsp;<?= $this->modal->large('edit', t('edt'), 'TaskModificationController', 'edit', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
            <?php endif ?>
        </h3>
    </div>
    <div class="accordion-content">
        <article class="markdown">
            <?= $this->text->markdown($task['description'], isset($is_public) && $is_public) ?>
        </article>
    </div>
</section>
