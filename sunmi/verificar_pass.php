<?php
require_once '../conexiones/admin.php';
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        if(isset($_GET ['pass']))
        {
            $pass=$_GET ['pass'];
            if(strcmp($pass,'')==0)
            {
                $campos="El campo de password no pude ir Vacio";
                echo json_encode(array("resultado"=>"false")); 
            }
            else
            {
                $pdo = new Conexion();
                $sql = $pdo->prepare("select * from tbcajas where password=:pass");
                $sql->bindValue(':pass',$pass );
                $sql ->execute();
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                $guid='';
                foreach ($sql as $registro) 
                {
                    $guid=$registro['password'];           
                } 
                if(strcmp($guid,'')==00)
                {
                    echo json_encode(array("resultado"=>"false"));
                } 
                else
                {
                    echo json_encode(array("resultado"=>"true")); 
                    $sql1 = $pdo->prepare("delete from tbcajas where password=:pass");
                    $sql1 ->bindValue(':pass',$pass );
                    $sql1 ->execute();
                }
            }    
        }
        else
        {
            $campos="Falta el campo de password ";
            echo json_encode(array("resultado"=>"false")); 
        }
    }
    else    
    {
        echo json_encode(array("resultado"=>"false")); 
    }
?>