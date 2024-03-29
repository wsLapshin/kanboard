<!-- column titles -->

<?= $this->hook->render('template:board:table:column:before-header-row', array('swimlane' => $swimlane)) ?>

<tr class="board-swimlane-columns-<?= $swimlane['id'] ?>">
    <?php foreach ($swimlane['columns'] as $column): ?>
    <th class="board-column-header board-column-header-<?= $column['id'] ?>" data-column-id="<?= $column['id'] ?>">

        <!-- column in collapsed mode -->
        <div class="board-column-collapsed">
            <small class="board-column-header-task-count" title="<?= t('Show this column') ?>">
                <span id="task-number-column-<?= $column['id'] ?>"><?= $column['nb_tasks'] ?></span>
            </small>
        </div>

        <!-- column in expanded mode -->
        <div class="board-column-expanded">
            <?php if (! $not_editable && $this->projectRole->canCreateTaskInColumn($column['project_id'], $column['id'])): ?>
                <?= $this->task->getNewBoardTaskButton($swimlane, $column) ?>
            <?php endif ?>

            <?php if ($column['nb_tasks'] > 0): ?>
            <?php 
                   /** @task В некоторых колонках нужно выводить время вместо задач. Пока привязываемся к имени колонки
                       Добавить свойсвто колонке в бд. Плюс перенести css */ 
                   $estimateColumns = ['Оценено', 'Согласовано', 'Сделать']; 
                   $spentColumns = ['В работе', 'Готово', 'Тестирование'];
                   $columnCounterEstimated = 0;
                   $columnCounterSpent = 0;
                   $columnCounter = null;
                   if( in_array($column['title'], $estimateColumns) || in_array($column['title'], $spentColumns)  ) {
                     foreach($column['tasks'] as $tsk) {
                        $columnCounterEstimated+=$tsk['time_estimated']; 
                     }
                     //$class = "blue";

                     foreach($column['tasks'] as $tsk) {
                        $columnCounterSpent+=$tsk['time_spent']; 
                     } 
                     //$class = "green";
                   } else {
                     $columnCounter = $column['nb_tasks'] . 'зад.'; 
                     $class = "";
                   }
            ?>
            <style>
                .board-column-stat_green {
                    color: green;
                }
                .board-column-stat_blue {
                    color: blue;
                }
                .board-column-stat_red {
                    color: red;
                }
            </style>
                <?php /*(<span id="task-number-column-<?= $column['id'] ?>"><?= $column['nb_tasks'] ?></span>)*/?>
                <span id="task-number-column-<?= $column['id'] ?>">
                    <?php if($columnCounter):?>
                        <span title="<?= t('Task count') ?>"><?= $columnCounter ?></span>
                    <?php else: ?>
                        <?php $class = ($columnCounterSpent > $columnCounterEstimated)?'red':'green';?>
                        <span title="<?= t('Task hours ready') ?>" class="board-column-stat_<?=$class?>"><?=$columnCounterSpent?> ч.</span>  
                        <span title="<?= t('Task hours estimated') ?>" class="board-column-stat_blue">(<?=$columnCounterEstimated?> ч.)</span> 
                    <?php endif;?>
                </span>
            <?php endif ?>

            <span class="board-column-title">
                <?php if ($not_editable): ?>
                    <?= $this->text->e($column['title']) ?>
                <?php else: ?>
                    <span class="dropdown">
                        <a href="#" class="dropdown-menu"><?= $this->text->e($column['title']) ?> <i class="fa fa-caret-down"></i></a>
                        <ul>
                            <li>
                                <i class="fa fa-minus-square fa-fw"></i>
                                <a href="#" class="board-toggle-column-view" data-column-id="<?= $column['id'] ?>"><?= t('Hide this column') ?></a>
                            </li>
                            <?php if ($this->projectRole->canCreateTaskInColumn($column['project_id'], $column['id'])): ?>
                                <li>
                                    <?= $this->modal->medium('align-justify', t('Create tasks in bulk'), 'TaskBulkController', 'show', array('project_id' => $column['project_id'], 'column_id' => $column['id'], 'swimlane_id' => $swimlane['id'])) ?>
                                </li>
                            <?php endif ?>

                            <?php if ($column['nb_tasks'] > 0 && $this->projectRole->canChangeTaskStatusInColumn($column['project_id'], $column['id'])): ?>
                                <li>
                                    <?= $this->modal->confirm('close', t('Close all tasks of this column'), 'BoardPopoverController', 'confirmCloseColumnTasks', array('project_id' => $column['project_id'], 'column_id' => $column['id'], 'swimlane_id' => $swimlane['id'])) ?>
                                </li>
                            <?php endif ?>

                            <?= $this->hook->render('template:board:column:dropdown', array('swimlane' => $swimlane, 'column' => $column)) ?>
                        </ul>
                    </span>
                <?php endif ?>
            </span>

            <span class="pull-right">
                <?php if ($swimlane['nb_swimlanes'] > 1 && ! empty($column['column_score'])): ?>
                    <span title="<?= t('Total score in this column across all swimlanes') ?>">
                        (<span><?= $column['column_score'] ?></span>)
                    </span>
                <?php endif ?>

                <?php if (! empty($column['score'])): ?>
                    <span title="<?= t('Score') ?>">
                        <?= $column['score'] ?>
                    </span>
                <?php endif ?>

                <?php if (! $not_editable && ! empty($column['description'])): ?>
                    <?= $this->app->tooltipMarkdown($column['description']) ?>
                <?php endif ?>
            </span>

            <?php if (! empty($column['column_nb_tasks'])): ?>
            <?php /*<span title="<?= t('Total number of tasks in this column across all swimlanes') ?>" class="board-column-header-task-count">
                <?php if ($column['task_limit'] > 0): ?>
                    (<span><?= $column['column_nb_tasks'] ?></span> / <span title="<?= t('Task limit') ?>"><?= $this->text->e($column['task_limit']) ?></span>)
                <?php else: ?>
                    (<span><?= $column['column_nb_tasks'] ?></span>)
                <?php endif ?>
            </span>*/ ?>
            <?php endif ?>
	    <?= $this->hook->render('template:board:column:header', array('swimlane' => $swimlane, 'column' => $column)) ?>
        </div>

    </th>
    <?php endforeach ?>
</tr>

<?= $this->hook->render('template:board:table:column:after-header-row', array('swimlane' => $swimlane)) ?>
