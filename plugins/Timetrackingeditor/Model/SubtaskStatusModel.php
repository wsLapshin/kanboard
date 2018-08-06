<?php
/**
 * SubtaskStatusModel Description 
 * 
 * @version    0.1, Aug 5, 2018  12:07:40 PM 
 * @author     CDEV 
 */
namespace Kanboard\Plugin\TimetrackingEditor\Model;

use Kanboard\Model\SubtaskStatusModel as OriginSubtaskStatusModel;

class SubtaskStatusModel extends OriginSubtaskStatusModel
{
     /**
     * Return true if the user have a subtask in progress
     *
     * @access public
     * @param  integer   $user_id
     * @return boolean
     */
    public function hasSubtaskInProgress($user_id)
    {
        return $this->configModel->get('subtask_restriction') == 1 &&
            $this->db->table(SubtaskModel::TABLE)
                ->eq('status', SubtaskModel::STATUS_INPROGRESS)
                ->eq('user_id', $user_id)
                ->exists();
    }
}

