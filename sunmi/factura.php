<?php
    require_once '../conexiones/'.$_GET['codigo'].'.php';
    $idfactura=$_GET['id_factura'];
    $pdo = new Conexion();
    $retorno='[';
    $sql = $pdo->prepare("select nombre_empresa,giro_empresa,
    direccion,concat(ciudad,', ',estado) as ciudad,regimen,fiscal_empresa from perfil,facturas_ventas,users,tbconfiguracion 
    where id_vendedor=users.id_users and users.sucursal_users=perfil.id_perfil and 
    id_factura=".$idfactura);
    $sql ->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $retorno.='{"empresa":[{';
    foreach ($sql as $value) 
    {
        $retorno.='"razon":"'.$value['nombre_empresa'].'","empresa":"'.$value['giro_empresa'].'","nit":"'.$value['fiscal_empresa'].'","direccion":"'.
        $value['direccion'].'","ciudad":"'.$value['ciudad'].'","regimen":"'.$value['regimen'].'","documento":"factura"';
    }
    $retorno.='}],"factura":[{';
    $sql = $pdo->prepare("select guid_factura,serie_factura,numero_certificacion as numero_factura,fechacertificacion,factura_nombre_cliente,factura_nit_cliente,
    monto_factura from facturas_ventas where id_factura=:id");
    $sql->bindValue(':id',$idfactura);
    $sql ->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($sql as $value) 
    {
        $retorno.='"guid":"'.$value['guid_factura'].'","serie":"'.$value['serie_factura'].'","numero":"'.$value['numero_factura'].'",
        "fecha":"'.$value['fechacertificacion'].'","nombre":"'.$value['factura_nombre_cliente'].'","nit":"'.$value['factura_nit_cliente'].'",
        "monto":"'.$value['monto_factura'].'"';
    }
    $retorno.='}],"detalle":[';
    $sql = $pdo->prepare("select nombre_producto,precio_venta,cantidad,esgenerico from detalle_fact_ventas,productos where productos.id_producto=detalle_fact_ventas.id_producto and id_factura=:id");
    $sql->bindValue(':id',$idfactura);
    $sql ->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($sql as $value) 
    {
        $retorno.='{"producto":"'. trim(str_replace ( '"', '', $value['nombre_producto'])).'","precio":"'.$value['precio_venta'].'","cantidad":"'.$value['cantidad'].'","generico":"'.$value['esgenerico'].'"},';
    }
    $retorno=trim($retorno, ',').']}]';
   echo $retorno;
?>