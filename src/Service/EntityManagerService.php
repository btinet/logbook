<?php


namespace App\Service;

use App\Database;
use \ReflectionClass;
use \ReflectionProperty;
use \ReflectionException;
use \Exception;

class EntityManagerService
{
    protected $db;
    protected $entity;

    function __construct()
    {
        $this->db = new Database();
    }

    /**
     * @throws ReflectionException
     */
    public function persist($entity, $id = false){
        self::generateReflectionClass($entity);
        $class_name = strtolower($this->entity->getShortName());
        foreach($this->entity->getProperties() as $property){
            foreach ($property as $key => $value){
                if($key == 'name'){
                    $rp = new ReflectionProperty($entity, $value);
                    if($rp->isInitialized($entity)){
                        $mvalue = ucfirst($value);
                        $method = "get$mvalue";
                        $entityProperty = $entity->$method();
                        if ($entityProperty instanceof \DateTime){
                            $entityProperty = $entityProperty->format('Y-m-d H:i:s');
                        }
                        $data[$value] = $entityProperty;
                    }
                }
            }
        };
        if ($id){
            $row = $this->db->select("SELECT * FROM $class_name WHERE id = :id", ['id' => $id]);
            if ($row){
                return $this->db->update($class_name, $data, ['id' => $id]);
            }
        } else {
            return $this->db->insert($class_name, $data);
        }
        return false;
    }

    public function remove($entity, $id)
    {
        self::generateReflectionClass($entity);
        $class_name = strtolower($this->entity->getShortName());
        if ($id) {
            $row = $this->db->select("SELECT * FROM $class_name WHERE id = :id", ['id' => $id]);
            if ($row) {
                return $this->db->delete($class_name, ['id' => $id]);
            }
        }
        return false;
    }

    public function truncate($entity)
    {
        self::generateReflectionClass($entity);
        $class_name = strtolower($this->entity->getShortName());
        try {
            return $this->db->truncate($class_name);
        } catch (Exception $e){
            return 'Exception abgefangen: '. $e->getMessage() . "\n";
        }
    }

    protected function generateReflectionClass($entity){
        try {
            return $this->entity = new ReflectionClass($entity);
        } catch (ReflectionException $reflectionException){
            return 'Exception abgefangen: '. $reflectionException->getMessage() . "\n";
        }
    }

}

