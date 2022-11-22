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
    )
    {
        try {
            if($this->conexion!=null)
            {
                $place_obj = new PlaceModel();
                $e = $place_obj->getPlace($place);
                if ( isset( $e['status'])) {
                    if ( $e['status'] == 1) return 4;
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

                return $statement->execute() and $place_obj->setStatus($e['id_place'], true);
            }
        } catch (\Throwable $th) {
            return $th->getCode();
        }
    }

    /**
     * 
     * Get Fee
     * params : id_fee
     * 
     */
    function getFee( $plate_car, $date_up, $price)
    {
        try {
            if ($this->conexion != null)
            {
                $statement = $this->conexion->prepare(
                    'SELECT * FROM fee WHERE plate_car=:plate_car AND date_up=:date_up AND price=:price'
                );
                $statement->bindParam(':plate_car', $plate_car);
                $statement->bindParam(':date_up', $date_up);
                $statement->bindParam(':price', $price);

                $statement->execute();

                $statement->setFetchMode(PDO::FETCH_ASSOC);
                foreach ($statement->fetchAll() as $key ) {
                    echo var_dump($key);
                }
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
        $departure_time, $elapset_time, $price, $id
    ) : int
    {
        try {
            if ($this->conexion != null)
            {
                $statement = $this->conexion->prepare(
                    'UPDATE fee SET departure_time=:departure_time, elapset_time=:elapset_time, price=:price WHERE id_fee=:id'
                );
                $statement->bindParam(':departure_time', $departure_time);
                $statement->bindParam(':elapset_time', $elapset_time);
                $statement->bindParam(':price', $price);
                $statement->bindParam(':id', $id);

                return $statement->execute();
            }
        } catch (\Throwable $th) {
            return $th->getCode();
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
*/