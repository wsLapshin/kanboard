<?php

namespace Kanboard\Plugin\TimerOverview\Model;

use Kanboard\Core\Base;
use Kanboard\Model\SubtaskTimeTrackingModel;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\TaskModel;
use Kanboard\Model\ProjectModel;

/**
 * TimerOverview Model
 *
 * @package  Model
 * @author   Martin Middeke
 */
class TimerOverviewModel extends Base
{
    /**
     * Count running timers
     *
     * @access public
     * @return integer
     */
    public function countRunningTimers()
    {
        $user_id = $this->userSession->getId();

        return $this->db
            ->table(SubtaskTimeTrackingModel::TABLE)
            ->eq('user_id', $user_id)
            ->eq('end', 0)
            ->count();
    }

    /**
     * get list of ids from subtasks with running timers
     *
     * @access public
     * @return array
     */
    public function getIDs($query)
    {
        if ($this->router->getController() === 'TimerOverviewController'){
            $array = array(0);
            foreach ($this->getRunningTimers() as $subtask) {
                $array[] = $subtask['subtask_id'];
            }

            $user_id = $this->userSession->getId();
            /*$query = $this->subtaskModel->getUserQuery($user_id, array(SubtaskModel::STATUS_TODO, SubtaskModel::STATUS_INPROGRESS, SubtaskModel::STATUS_DONE));*/
            $query = $this->taskFinderModel->getExtendedQuery()
                    ->beginOr()
                    ->eq(TaskModel::TABLE.'.owner_id', $user_id)
                    ->addCondition(TaskModel::TABLE.".id IN (SELECT task_id FROM ".SubtaskModel::TABLE." WHERE ".SubtaskModel::TABLE.".user_id='$user_id')")
                    ->closeOr()
                    ->eq(TaskModel::TABLE.'.is_active', TaskModel::STATUS_OPEN)
                    ->eq(ProjectModel::TABLE.'.is_active', ProjectModel::ACTIVE)
                    ->join(SubtaskModel::TABLE, 'task_id', 'id', TaskModel::TABLE)
                    ->in(SubtaskModel::TABLE.'.id', $array);

        }
        return $query;
    }

    /**
     * get list of subtasks with running timers
     *
     * @access public
     * @return integer
     */
    public function getRunningTimers()
    {
        $user_id = $this->userSession->getId();
        
        return $this->db
            ->table(SubtaskTimeTrackingModel::TABLE)
            ->columns('subtask_id')
            ->eq('user_id', $user_id)
            ->eq('end', 0)
            ->findAll();
    }
}