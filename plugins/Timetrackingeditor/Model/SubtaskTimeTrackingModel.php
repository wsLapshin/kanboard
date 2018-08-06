<?php

namespace Kanboard\Plugin\TimetrackingEditor\Model;

use Kanboard\Model\SubtaskModel;
use Kanboard\Model\TaskModel;
use Kanboard\Model\UserModel;

/**
 * @author Thomas Stinner
 */
class SubtaskTimeTrackingModel extends \Kanboard\Model\SubtaskTimeTrackingModel
{

    const DEFAULT_START_COMMENT = 'No Subtask Time Entry Comment';

    const DEFAULT_FINISH_COMMENT = 'Auto finished Time Entry Comment';

    /**
     * Log start time
     *
     * @access public
     * @param  integer   $subtask_id
     * @param  integer   $user_id
     * @return boolean
     */
    public function logStartTimeExtended($subtask_id, $user_id, $comment, $is_billable)
    {
        if ($this->configModel->get('subtask_time_tracking') == 1) {
            $values = [
                'id' => $subtask_id,
                'status' => SubtaskModel::STATUS_INPROGRESS,
            ];
            $this->subtaskModel->update($values);
        }

        
        
        return
            !$this->hasTimer($subtask_id, $user_id) &&
                $this->db
                ->table(self::TABLE)
                ->insert(array('subtask_id' => $subtask_id,
                    'user_id' => $user_id,
                    'comment' => $comment,
                    'is_billable' => $is_billable,
                    'start' => time(),
                    'end' => 0));
    }

    /**
     * Сохранить дату начала по умолчанию 
     * @param type $subtask_id
     * @param type $user_id
     */
    public function logDefaultStartTimeExtended($subtask_id, $user_id)
    {
        $isBillable = 1;
        return $this->logStartTimeExtended(
            $subtask_id, 
            $user_id, 
            t(static::DEFAULT_START_COMMENT), 
            $isBillable
        );
    }

    /**
     * Log end time
     *
     * @access public
     * @param  integer   $subtask_id
     * @param  integer   $user_id
     * @return boolean
     */
    public function logEndTimeExtended($subtask_id, $user_id, $comment, $is_billable)
    {
        $time_spent = $this->getTimeSpent($subtask_id, $user_id);

        if ($time_spent > 0) {
            $this->updateSubtaskTimeSpent($subtask_id, $time_spent);
        }

        return $this->db
                ->table(self::TABLE)
                ->eq('subtask_id', $subtask_id)
                ->eq('user_id', $user_id)
                ->eq('end', 0)
                ->update(array(
                    'end' => time(),
                    'time_spent' => $time_spent,
                    'comment' => $comment,
                    'is_billable' => $is_billable
        ));
    }

    /**
     * Сохранить время завершения по-умолчанию 
     * @param type $subtask_id
     * @param type $user_id
     */
    public function logDefaultEndTimeExtended($subtask_id, $user_id)
    {
        $isBillable = 1;
        $this->subtaskTimeTrackingModel->logEndTimeExtended(
            $subtask_id, 
            $user_id, 
            t(static::DEFAULT_FINISH_COMMENT), 
            $isBillable
        );
    }

    /**
     * Start or stop timer according to subtask status
     *
     * @access public
     * @param  integer $subtask_id
     * @param  integer $user_id
     * @param  integer $status
     * @return boolean
     */
    public function toggleTimer($subtask_id, $user_id, $status)
    {
        if ($this->configModel->get('subtask_time_tracking') == 1) {
            if ($status == SubtaskModel::STATUS_INPROGRESS) {
                return $this->subtaskTimeTrackingModel->logDefaultStartTimeExtended($subtask_id, $user_id);
            } elseif ($status == SubtaskModel::STATUS_DONE) {
                return $this->subtaskTimeTrackingModel->logDefaultEndTimeExtended($subtask_id, $user_id);
            }
        }
        return false;
    }

    /**
     * Get query for task timesheet (pagination)
     *
     * @access public
     * @param  integer    $task_id    Task id
     * @return \PicoDb\Table
     */
    public function getTaskQuery($task_id)
    {
        return $this->db
                ->table(self::TABLE)
                ->columns(
                    self::TABLE . '.id', self::TABLE . '.subtask_id', self::TABLE . '.end', self::TABLE . '.start', self::TABLE . '.time_spent', self::TABLE . '.user_id', self::TABLE . '.comment', self::TABLE . '.is_billable', SubtaskModel::TABLE . '.task_id', SubtaskModel::TABLE . '.title AS subtask_title', TaskModel::TABLE . '.project_id', UserModel::TABLE . '.username', UserModel::TABLE . '.name AS user_fullname'
                )
                ->join(SubtaskModel::TABLE, 'id', 'subtask_id')
                ->join(TaskModel::TABLE, 'id', 'task_id', SubtaskModel::TABLE)
                ->join(UserModel::TABLE, 'id', 'user_id', self::TABLE)
                ->eq(TaskModel::TABLE . '.id', $task_id);
    }

    /**
     * Update subtask time billable
     *
     * @access public
     * @param  integer   $subtask_id
     * @param  float     $time_billable
     * @return bool
     */
    public function updateSubtaskTimeBillable($subtask_id, $time_billable)
    {
        $subtask = $this->subtaskModel->getById($subtask_id);

        return $this->subtaskModel->update(array(
                'id' => $subtask['id'],
                'time_billable' => $subtask['time_billable'] + $time_billable,
                'task_id' => $subtask['task_id'],
                ), false);
    }

    /**
     * get a Subtasktimetracking entry by Id
     *
     * @access public
     * @param $id the subtasktimetracking id
     * @return array
     */
    public function getById($id)
    {
        return $this->db
                ->table(SubtaskTimeTrackingModel::TABLE)
                ->eq('id', $id)
                ->findOne();
    }

}
