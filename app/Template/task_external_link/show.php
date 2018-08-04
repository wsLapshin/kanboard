<section class="accordion-section <?= empty($links) ? 'accordion-collapsed' : '' ?>">
    <div class="accordion-title">
        <h3>
            <a href="#" class="fa accordion-toggle"></a> <?= t('External links') ?>
            <?php if ($this->projectRole->canUpdateTask($task)): ?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?= $this->modal->medium('plus', t('Add external link'), 'TaskExternalLinkController', 'find', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
            <?php endif ?> 
        </h3>
    </div>
    <div class="accordion-content">
        <?= $this->render('task_external_link/table', array(
            'links' => $links,
            'task' => $task,
            'project' => $project,
        )) ?>
    </div>
</section>
