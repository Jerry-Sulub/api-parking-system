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
        switch($_SERVER['REQUEST_METHOD'])
        {
            case 'GET' :
                if(isset($_GET['plate']))
                {
                    $this->getFee($_GET['plate'], $_GET['date'], $_GET['price']);
                }else{
                    $this->listFee();
                }
                break;
            case 'POST' :
                $this->addFee();
                break;
            case 'PUT' :
                $this->updateFee($_GET['id']);
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
    public function getFee($plate, $date, $price)
    {
        $fee = new FeeModel();
        $data = $fee->getFee($plate, $date, $price);
        header("HTTP/1.1 200 OK");
        echo json_encode($data);
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

        $msg = $status ? 'Guardado de manera satisfactoria': 'Error al guardar';
        header("HTTP/1.1 200 OK");
        echo json_encode(array('Message'=>$msg));   
    }

    /**
     * @requestMethod PUT
     * @param $id
     * @return void
     */
    public function updateFee($id)
    {
        $product = new FeeModel();
        $_PUT=json_decode(file_get_contents('php://input'),true);
        $status = $product->updateFee(
            $_PUT['departure_time'],
            $_PUT['elapset_time'],
            $_PUT['price'],
            $id
        );
        $msg = $status ? 'Actualización satisfactoria': 'Error al actualizar';
        header("HTTP/1.1 200 OK");
        echo json_encode(array('Message' => $msg));
    }
}