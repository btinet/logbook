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
        return $this->db->select('SELECT * FROM '.$this->table.' LEFT JOIN tag ON '.$this->table.'.tag_id = tag.id AND '.$this->table.'.user_id = tag.user '.$criteria_data, $criteria);

    }
}