<?php
require_once '../conexiones/admin.php';
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['nit']) )
        {
            $nit=$_GET ['nit'];
            $pdo = new Conexion();
            date_default_timezone_set("America/Guatemala");

            $sql = $pdo->prepare("select link,nombre,codigo from tbsistema where nit=:nit");
            $sql->bindValue(':nit',$nit );
            $sql ->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            echo  json_encode($sql->fetchAll());  
        }
        else
        {
            $campos="Falta el campo de nit";
            echo json_encode(array("resultado"=>"false")); 
        }
    }
    else
    {
        echo json_encode(array("resultado"=>"false")); 
    }
?>

