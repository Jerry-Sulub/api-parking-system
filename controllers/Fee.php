<?php

class FeeController
{

    public function __construct()
    {
        require_once 'models/FeeModel.php';
    }

    public function index()
    {
        header('Content-Type: application/json');
        echo $_SERVER['REQUEST_METHOD'];
        switch($_SERVER['REQUEST_METHOD'])
        {
            case 'GET' :
                if(isset($_GET['plate']))
                {
                    $this->getFee($_GET['plate'], $_GET['place']);
                }else{
                    $this->listFee();
                }
                break;
            case 'POST' :
                $this->addFee();
                break;
            case 'PATCH' :
                $this->updateFee();
                break;
        }


    }

    /**
     *
     * @requestMethod GET
     * @return void
     */
    public function listFee()
    {
        $fee = new FeeModel();
        $data = $fee->listFee();
        header("HTTP/1.1 200 OK");
        echo json_encode($data);
    }

    /**
     *
     * @requestMethod GET
     * @param $id
     * @return void
    */
    public function getFee($plate, $place)
    {
        $fee = new FeeModel();
        $data = $fee->getFee($plate, $place);
        header("HTTP/1.1 200 OK");
        echo json_encode(array('fee'=>$data));
    }

    /**
     * @requestMethod POST
     * @return void
     */
    public function addFee()
    {
        $product = new FeeModel();
        $_POST=json_decode(file_get_contents('php://input'),true);
        $status = $product->insert(
            $_POST['plate'],
            $_POST['place'],
            $_POST['date_up'],
            $_POST['entry_time'],
            $_POST['departure_time'],
            $_POST['elapset_time'],
            $_POST['price'],
        );
        header("HTTP/1.1 200 OK");
        echo json_encode($status);
    }

    /**
     * @requestMethod PUT
     * @param $id
     * @return void
     */
    public function updateFee()
    {
        
        $fee = new FeeModel();
        $_PATCH=json_decode(file_get_contents('php://input'),true);
        $status = $fee->updateFee(
            $_PATCH['plate'],
            $_PATCH['departure_time'],
            $_PATCH['elapset_time'],
            $_PATCH['price'],
        );
        $msg = $status ? 'Actualización satisfactoria': 'Error al actualizar';
        header("HTTP/1.1 200 OK");
        echo json_encode(array('Message' => $msg));
    }
}