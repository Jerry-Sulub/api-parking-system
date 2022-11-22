<?php

class Connection 
{
    public static function getConexion() : PDO
    {
        try {
            $conexion = new PDO("mysql:host=localhost;dbname=parking", "root", "");
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            echo 'Conexion failed! ' . $e->getMessage();
            return null;
        }
    }
}