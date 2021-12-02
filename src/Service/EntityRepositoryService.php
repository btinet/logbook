<?php


namespace App\Service;


use App\Database;
use \ReflectionClass;

abstract class EntityRepositoryService
{
    protected Database $db;
    protected $entity;
    protected $table;

    function __construct(ReflectionClass $entity)
    {
        $this->db = new Database();
        $this->entity = $entity;
        $this->table = strtolower($entity->getShortName());
    }

    /**
     * @param array $criteria
     * @param array $falseCriteria
     * @return string|null
     */
    protected function createSqlConditions(array $criteria, array $falseCriteria = array()): ?string
    {

        if (!empty($criteria)){
            $first = true;
            $criteria_data = null;
            foreach ($criteria as $property => $value){
                $word = ($first) ? ' WHERE' : ' AND';
                if(!$value){
                    $criteria_data .="$word $property IS NULL";
                } else {
                    $criteria_data .= "$word $property = :$property";
                }

                $first = false;
            }
        }
        /** @var string $criteria_data */
        return $criteria_data;
    }

    public function findAll(){
        return $this->db->select('SELECT * FROM '.$this->table.' ');
    }

    /**
     * @param array $criteria
     * @param array $sort
     * @return array|false
     */
    public function findBy(array $criteria,array $sort = []){
        $merged_criteria = [];
        $criteria_data = $this->createSqlConditions($criteria);
        $criteria = array_filter($criteria);
        if($sort){
            $property = array_keys($sort);
            $property = $property[0];
            $orderDirection = $sort[$property];
            $criteria_data .= " ORDER BY $property $orderDirection";
        }
        return $this->db->select('SELECT * FROM '.$this->table.' '.$criteria_data, $criteria);

    }

    public function findOneBy(array $criteria = array(), array $sort = array())
    {

        $criteria_data = $this->createSqlConditions($criteria);
        $result = $this->db->select(' SELECT * FROM '.$this->table.' '.$criteria_data.' LIMIT 1 ', $criteria);

        if($result){
            return array_pop($result);
        } else {
            return false;
        }

    }

    public function getPrevious($id)
    {
        $result = $this->db->select('SELECT * FROM '.$this->table. ' WHERE id < :id ORDER BY id DESC LIMIT 1',['id' => $id]);
        return array_pop($result);
    }

    public function getNext($id)
    {
        $result = $this->db->select('SELECT * FROM '.$this->table. ' WHERE id > :id ORDER BY id ASC LIMIT 1',['id' => $id]);
        return array_pop($result);
    }

}
