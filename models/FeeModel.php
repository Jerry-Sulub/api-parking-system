<?php

class FeeModel
{
    private $conexion;

    function __construct() 
    {
        $this->conexion = Connection::getConexion();
    }

    /**
     * 
     * Create a Fee
     * params : 
     *  plate, place, entry_time, departure_time, elapset_time and price
     * 
     * return : int
     *  0 : failed
     *  1 : success
     *  >1 : error
     * 
     */
    function insert(
        $plate, $place, $date_up, $entry_time, $departure_time, $elapset_time, $price
    ) : array
    {
        try {
            if($this->conexion!=null)
            {
                $place_obj = new PlaceModel();
                $e = $place_obj->getPlace($place);
                if ( isset( $e['status'])) {
                    if ( $e['status'] == 1) return array('Message'=>'El lugar ya esta ocupado!');
                }
                
                $statement = $this->conexion->prepare('INSERT INTO 
                fee( plate_car, place, date_up, entry_time, departure_time, elapset_time, price)
                VALUES(:plate, :place, :date_up ,:entry_time, :departure_time, :elapset_time, :price)'
                );

                $statement->bindParam(':plate', $plate);
                $statement->bindParam(':place', $place);
                $statement->bindParam(':date_up', $date_up);
                $statement->bindParam(':entry_time', $entry_time);
                $statement->bindParam(':departure_time', $departure_time);
                $statement->bindParam(':elapset_time', $elapset_time);
                $statement->bindParam(':price', $price);
                
                if($statement->execute() and $place_obj->setStatus($e['id_place'], true))
                {
                    return array('Message' => 'Auto registrado!');
                }
            }
        } catch (\Throwable $th) {
            return array('Message' => 'Error al registrar: '.$th->getMessage()." Code"+$th->getCode());
        }
    }

    /**
     * 
     * Get Fee
     * params : id_fee
     * 
     */
    function getFee( $plate_car, $place)
    {
        $sql_with_plate = 'SELECT * FROM fee 
        INNER JOIN place ON fee.place=place.id_place
        WHERE fee.plate_car=:plate AND place.status=1';

        $sql_with_place = 'SELECT * FROM fee 
        INNER JOIN place ON fee.place=place.id_place
        WHERE fee.place=:place AND place.status=1';

        $param = 0;

        if($plate_car=='None')
        {
            $sql = $sql_with_place;
            $param = 1;
        }elseif($place=='None'){
            $sql = $sql_with_plate;
            $param = 2;
        }else{
            $sql = 'SELECT * FROM `fee` 
            INNER JOIN place ON fee.place=place.id_place
            WHERE fee.plate_car=:plate AND fee.place=:place AND place.status=1';
            $param = 3;
        }

        try {
            if ($this->conexion != null)
            {
                $statement = $this->conexion->prepare(
                    $sql                    
                );

                switch ($param) {
                    case 1:
                        $statement->bindParam(':place', $place);        
                        break;
                    
                    case 2:
                        $statement->bindParam(':plate', $plate_car);
                        break;

                    case 3:
                        $statement->bindParam(':plate', $plate_car);
                        $statement->bindParam(':place', $place);
                        break;
                }

                $statement->execute();

                $statement->setFetchMode(PDO::FETCH_ASSOC);
                
                return $statement->fetchAll()[0];
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * 
     * Update_fee
     * 
     */
    public function updateFee(
        $plate_car, $departure_time, $elapset_time, $price
    ) : array
    {
        try {
            $place_obj = new PlaceModel();

            if ($this->conexion != null)
            {
                $statement = $this->conexion->prepare(
                    'UPDATE fee 
                    INNER JOIN place ON fee.place=place.id_place 
                    SET fee.departure_time=:departure_time, fee.elapset_time=:elapset_time, fee.price=:price, place.status=0 
                    WHERE fee.plate_car=:plate_car AND place.status=1'
                );
                $statement->bindParam(':departure_time', $departure_time);
                $statement->bindParam(':elapset_time', $elapset_time);
                $statement->bindParam(':price', $price);
                $statement->bindParam(':plate_car', $plate_car);

                if($statement->execute())
                    return array('Message'=>'Pago exitoso!');
                else
                    return array('Message'=>'Oops! hubo un inconveniente al actualizar');
            }
        } catch (\Throwable $th) {
            return array('Message'=>'Error: '.$th->getMessage().' Code '.$th->getCode());
        }
    }

    public function listFee ()
    {
        try {
            if ( $this->conexion != null)
            {
                $statement = $this->conexion->prepare(
                    'SELECT fee.plate_car, fee.place, fee.entry_time, fee.departure_time, fee.elapset_time, fee.price, place.status FROM fee INNER JOIN place ON fee.place=place.id_place;'
                );
                $statement->execute();

                $statement->setFetchMode(PDO::FETCH_ASSOC);
                return $statement->fetchAll();
            }
        } catch (\Throwable $th) {
            return array('Message'=>'Error code '.$th->getCode());
        }
    }
}
//$code->getFee('XLQ-231-8', '2022-09-11', 0);

//echo $code->updateFee('24:30:30', '00:40', 20, 8);
/*
echo $code->insert(
    'XLQ-231-8',
    'A5',
    '2022-10-11',
    '20:30:20',
    '00:00:00',
    '00:00:00',
    0
);
*/

/*


SELECT fee.plate_car, fee.place, fee.entry_time, fee.departure_time, fee.elapset_time, fee.price, place.status FROM fee INNER JOIN place ON fee.place=place.id_place AND place.status!=1;

SELECT * FROM `fee` WHERE plate_car = 'XLQ-231-8' AND date_up='2022-10-11' AND price=0;

SELECT fee.plate_car, fee.date_up, place.id_place FROM `fee` INNER JOIN place ON fee.place=place.id_place WHERE fee.plate_car='XLQ-231-8' AND fee.date_up='2022-10-11' AND place.status=1 AND fee.place='A4';

SELECT *, place.id_place FROM `fee` 
INNER JOIN place ON fee.place=place.id_place
WHERE fee.plate_car='XLQ-231-8' AND place.status=1

UPDATE fee INNER JOIN place ON fee.place=place.id_place SET fee.departure_time='20:00:00', fee.elapset_time='01:00:00', fee.price=4, place.status='0' WHERE fee.plate_car='ABC-DEF-1' AND place.status='1';
*/