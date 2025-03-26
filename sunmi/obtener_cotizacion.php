<?php
    require_once '../conexiones/'.$_GET['codigo'].'.php';
    if($_SERVER['REQUEST_METHOD']=='GET')
    {
        $idfactura=$_GET['id_factura'];
        $pdo = new Conexion();
        $retorno='[';
        $sql = $pdo->prepare("select nombre_empresa,giro_empresa,direccion,concat(ciudad,', ',estado) as ciudad,regimen,fiscal_empresa  from perfil,
        facturas_cot,users,tbconfiguracion where id_vendedor=users.id_users and users.sucursal_users=perfil.id_perfil and numero_factura='".$idfactura."'");
        $sql ->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $retorno.='{"empresa":[{';
        foreach ($sql as $value) 
        {
            $retorno.='"razon":"'.$value['nombre_empresa'].'","empresa":"'.$value['giro_empresa'].'","nit":"'.$value['fiscal_empresa'].'",
            "direccion":"'.$value['direccion'].'","ciudad":"'.$value['ciudad'].'","regimen":"'.$value['regimen'].'","documento":"cotizcion"';
        }
        $retorno.='}],"factura":[{';
        $sql = $pdo->prepare("select cot_nombre_cliente,cot_nit_cliente,
            monto_factura from facturas_cot where numero_factura=:id");
        $sql->bindValue(':id',$idfactura);
        $sql ->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($sql as $value) 
        {
            $retorno.='"nombre":"'.$value['cot_nombre_cliente'].'","nit":"'.$value['cot_nit_cliente'].'",
            "monto":"'.$value['monto_factura'].'"';
        }
        $retorno.='}],"detalle":[';
        $sql = $pdo->prepare("select nombre_producto,precio_venta,cantidad,esgenerico from detalle_fact_cot,productos where 
        productos.id_producto=detalle_fact_cot.id_producto and numero_factura=:id");
        $sql->bindValue(':id',$idfactura);
        $sql ->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($sql as $value) 
        {
            $retorno.='{"producto":"'.trim(str_replace ( '"', '', $value['nombre_producto'])).'","precio":"'.$value['precio_venta'].'","cantidad":"'.$value['cantidad'].'","generico":"'.$value['esgenerico'].'"},';
        }
        $retorno=trim($retorno, ',').']}]';
        echo $retorno;
    }
?>