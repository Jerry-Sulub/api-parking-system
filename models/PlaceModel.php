<?php

class PlaceModel
{
    private $conexion;

    function __construct()
    {
        $this->conexion = Connection::getConexion();
    }

    /*
     *
     * Register a new place for car
     * params: $id
     * return: int : 0 -> failed, 1 -> success, 1 > error code
     * 
     */
    public function insert($id) : int
    {
        try {
            if($this->conexion!=null)
            {
                $statement = $this->conexion->prepare('INSERT INTO place(id_place) VALUES(:id_place)');
                $statement->bindParam(':id_place', $id);

                return $statement->execute();
            }
        } catch (\Throwable $th) {
            return $th->getCode();
        }
    }

    /**
     * 
     * List all places
     * return : array
     * 
     */
    public function listPlace() : array
    {
        try {
            $statement = $this->conexion->prepare('SELECT * FROM place');
            if($statement->execute())
            {
                $statement->setFetchMode(PDO::FETCH_ASSOC);
                return $statement->fetchAll();
            }
        } catch (\Throwable $th) {
            return array('status_code' => $th->getCode());
        }
    }

    /**
     * 
     * Get place
     * return : array
     * 
     */
    public function getPlace($id) : array
    {
        try {
            $statement = $this->conexion->prepare('SELECT * FROM place WHERE id_place=:id');
            $statement->bindParam(':id', $id);
            if($statement->execute())
            {
                $statement->setFetchMode(PDO::FETCH_ASSOC);
                $res = $statement->fetchAll();
                if(sizeof($res)==0) {
                    return array();
                }
                return $res[0];
            }
        } catch (\Throwable $th) {
            return array('status_code' => $th->getCode());
        }
    }

    /**
     * 
     * Set status of a place
     * params : $status
     * return :  int : 0 -> failed, 1->success, >1 -> Error
     * 
     */
    public function setStatus($id, $status) : int
    {
        try {
            if($this->conexion != null)
            {
                $statement = $this->conexion->prepare(
                    'UPDATE place SET status=:status WHERE id_place=:id'
                );
                $statement->bindParam(':id', $id);
                $statement->bindParam(':status', $status);
                return $statement->execute();
            }
        } catch (\Throwable $th) {
            return $th->getCode();
        }
    }

    /**
     * 
     * Delete an place with id
     * params : $id
     * return :  int : 0 -> failed, 1->success, >1 -> Error
     * 
     */
    public function delete($id) : int
    {
        try {
            $statement = $this->conexion->prepare('DELETE FROM place WHERE id_place = :id');
            $statement->bindParam(':id', $id);
            return $statement->execute();
        } catch (\Throwable $th) {
            return $th->getCode();
        }
    }   
}