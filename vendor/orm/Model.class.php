<?php


class Model {

    /**
     * @var $id
     */
    protected $id;

    /**
     * Find record in a database table by id
     * @param $id int
     * @return object
     */
    public static function find($id){
        $conn = ORM::getConnection();
        $model = get_called_class() . "s";
        $stmt = $conn->prepare("SELECT * FROM ".strtolower($model) . " WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        if($data == null) return null;
        return self::convertToModel($data);
    }

    /**
     * Find all records in a database table
     * @return array|null
     */
    public static function findAll(){
        $objects = [];
        $conn = ORM::getConnection();
        $model = get_called_class() . "s";
        $res = $conn->query("SELECT * FROM ".strtolower($model));
        while($row = $res->fetch_assoc()) $objects[] = self::convertToModel($row);
        return $objects;
    }

    /**
     * Insert or update a row in a table
     * @return object|null|boolean
     */
    public function save(){
        $conn = ORM::getConnection();
        $model = get_called_class() . "s";
        // in case we want to insert new record
        if($this->getId() == null){
            // prepare field, content and fields data type
            $pros = $data = $types = [];
            foreach (Reflect::getProps(get_called_class()) as $prop){
                if($prop != "id"){
                    $pros[] = $prop;
                    $getter = "get" . ucfirst($prop);
                    $data[] = $this->$getter();
                    $types[] = is_numeric($prop) ? "i" : "s";
                }
            }
            // prepare the question marks for mysql statement
            $qst = [];
            foreach ($pros as $p) $qst[] = "?";
            $qst = implode(", ", $qst);
            $pros = implode(", ", $pros);
            // execute mysql statement
            $stmt = $conn->prepare("INSERT INTO ".strtolower($model) . " ($pros) VALUES ($qst)");
            $types = implode('', $types);
            $stmt->bind_param($types, ...$data);
            return $stmt->execute();
        }
        return null;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public static function convertToModel($data){
        $class = get_called_class();
        $object = new $class();
        foreach ($data as $key=>$column){
            $setter = "set".ucfirst($key);
            $object->$setter($column);
        }
        return $object;
    }

}