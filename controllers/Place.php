<?php

class PlaceController
{

    public function __construct()
    {
        require_once 'models/PlaceModel.php';
    }

    public function index()
    {
        header('Content-Type: application/json');
        switch($_SERVER['REQUEST_METHOD'])
        {
            case 'GET' :
                if(isset($_GET['place']))
                {
                    $this->getPlace($_GET['place']);
                }else{
                    $this->list();
                }
                break;
            case 'POST' :
                $this->insert();
                break;
            case 'PUT' :
                $this->updatePlace($_GET['id']);
                break;
        }


    }

    /**
     *
     * @requestMethod GET
     * @return void
     */
    public function list()
    {
        $fee = new PlaceModel();
        $data = $fee->listPlace();
        header("HTTP/1.1 200 OK");
        echo json_encode($data);
    }

    /**
     *
     * @requestMethod GET
     * @param $id
     * @return void
    */
    public function getPlace($id)
    {
        $fee = new PlaceModel();
        $data = $fee->getPlace($id) ;
        header("HTTP/1.1 200 OK");
        echo json_encode($data);
    }

    /**
     * @requestMethod POST
     * @return void
     */
    public function insert()
    {
        $place = new PlaceModel();
        $_POST=json_decode(file_get_contents('php://input'),true);
        $status = $place->insert(
            $_POST['id']
        );

        $msg = $status ? 'Guardado de manera satisfactoria': 'Error al guardar';
        header("HTTP/1.1 200 OK");
        echo json_encode(array('Message'=>$msg));   
    }

    /**
     * @requestMethod PUT
     * @param $id
     * @return void
     */
    public function updatePlace($id)
    {
        $place = new PlaceModel();
        $_PUT=json_decode(file_get_contents('php://input'),true);
        $status = $place->setStatus(
            $id,
            $_PUT['status']
        );
        $msg = $status ? 'Actualización satisfactoria': 'Error al actualizar';
        header("HTTP/1.1 200 OK");
        echo json_encode(array('Message' => $msg));
    }
}