<?php


namespace App\Repository;

use App\Service\EntityRepositoryService;


class TaskRepository extends EntityRepositoryService
{
    /**
     * @param array $criteria
     * @param array $sort
     * @return array|false
     */
    public function findByAndJoinTags(array $criteria,array $sort = []){
        $criteria_data = $this->createSqlConditions($criteria);
        $criteria = array_filter($criteria);
        if($sort){
            $property = array_keys($sort);
            $property = $property[0];
            $orderDirection = $sort[$property];
            $criteria_data .= " ORDER BY $property $orderDirection";
        }
        return $this->db->select('SELECT * FROM tag LEFT JOIN '.$this->table.' ON tag.id = '.$this->table.'.tag_id AND tag.user = '.$this->table.'.user_id  '.$criteria_data, $criteria);

    }
}