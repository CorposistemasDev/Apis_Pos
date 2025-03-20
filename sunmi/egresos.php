<?php
    require_once '../conexiones/'.$_GET['codigo'].'.php';
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        $referencia=$_GET['referencia'];    
        $pdo = new Conexion();
        $retorno='[';
        $sql = $pdo->prepare("select nombre_empresa,giro_empresa,direccion,ciudad,
            regimen,fiscal_empresa from perfil,tbconfiguracion where id_perfil=".$_GET['sucursal']);
        $sql ->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $retorno.='{"empresa":[{';
        foreach ($sql as $value) 
        {
            $retorno.='"razon":"'.$value['nombre_empresa'].'","empresa":"'.$value['giro_empresa'].'","nit":"'.$value['fiscal_empresa'].'","direccion":"'.$value['direccion'].'",
                "ciudad":"'.$value['ciudad'].'","regimen":"'.$value['regimen'].'","documento":"egreso"';
        }
        $retorno.='}],"egreso":[{';
        $sql = $pdo->prepare("select monto ,descripcion_egreso as descripcion, concat(nombre_users,' ',apellido_users) as nombre  
            from egresos,users where egresos.users =users.id_users and referencia_egreso =:id");
        $sql->bindValue(':id',$referencia);
        $sql ->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($sql as $value) 
        {
            $retorno.='"referencia":"'.$referencia.'","monto":"'.$value['monto'].'","descripcion":"'.$value['descripcion'].'",
                "nombre":"'.$value['nombre'].'"},';
        }
        $retorno=trim($retorno, ',').']}]';
        echo $retorno;
    }
?>