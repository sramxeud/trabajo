<?php
session_start();
$cod = $_SESSION['cod'];
require("../conecta.php");
$conectar = conectar("sistemas");

$ctto = $_POST['ctto_b'];
$nodo = $_POST['nodo'];
$slot = $_POST['slot'];
$shelf = $_POST['shelf'];
$motivo = $_POST['motivo'];

$onu = $_POST['asig_onu'];
$gpon = $_POST['asig_gpon'];
$index = $_POST['index'];
$serie = $_POST['serie'];
$marca = $_POST['marca'];
$estadon = $_POST['estadon'];
$gpon = substr($gpon, 0, -1);
//echo $index.' '. $gpon.' '.$onu;

$no2 = '0/0';
if ($nodo=='MLL-TAI') {
    require_once("telnet_class3.php");
    $server = '10.224.60.67';
    $username = 'root';
    $password = 'T3lc021';
    $telnet = new Telnet($server, $username, $password);
}
if ($nodo=='OLT-VTT' || $nodo=='OLT-ROS' || $nodo=='OLT-GCE') {
    require_once("telnet_class2.php");
    $server = '10.224.60.60';
    $username = 'root';
    $password = 'admin';
    $telnet = new Telnet($server, $username, $password);
}

if (isset($_POST['HAB'])) {
    if ($nodo=='OLT-MRF') {
        $posicion=$nodo.' '.$index; 
        exec('expect /var/www/html/norah/cortes/scripts/sinotel_hab.exp '.$posicion);  
        header("Location: deshabilitaPuerto.php");      
    }else{ 
    $telnet->write_cmd("config");
    $telnet->write_cmd("interface mgmt");
    $telnet->write_cmd("description \042NEW OLT TEST \042");
    $telnet->write_cmd("exit");
    $telnet->write_cmd("timezone gmt- 04:00");
    $telnet->write_cmd("interface gpon " . $no2);
    $salida = $telnet->write_cmd("ont activate " . $gpon . " " . $onu);
    $telnet->write("logout");

    echo $_POST['HAB'];
    $dia = date("d-m-Y");
    $hora = date("H:i:s");
    $fechareg = $dia . " " . $hora; //fecha de servicio
    $ip_pc = $_SERVER["HTTP_X_FORWARDED_FOR"]; //Ip de la PC
    //$ip_pc = '172.21.22.13';
    //echo $ctto."--".$nodo."--".$slot."--".$port."--".$motivo."--".$fechareg."--".$ip_pc."--".'H';
    $query = "INSERT INTO `crud_estado_port`(`id`, `ctto`, `nodo`,`shelf`, `slot`, `port`,`plan`, `hab`, `des`, `fecha_reg`, `ip_pc_reg`, `cod_emp`, `motivo`, `estado`, `estRepor`) VALUES('','$ctto','$nodo','$index','$gpon','$onu','','H','','$fechareg','$ip_pc','$cod','$motivo','2','A')";
    $add = mysqli_query($conectar, $query);
    header("Location: habilitaPuerto.php"); }
}

if (isset($_POST['DES'])) {
    if ($nodo=='OLT-MRF') {
        $posicion=$nodo.' '.$index; 
        exec('expect /var/www/html/norah/cortes/scripts/sinotel_bloq.exp '.$posicion);  
        header("Location: deshabilitaPuerto.php");      
    }else{ 
    $telnet->write_cmd("config");
    $telnet->write_cmd("interface mgmt");
    $telnet->write_cmd("description \042NEW OLT TEST \042");
    $telnet->write_cmd("exit");
    $telnet->write_cmd("timezone gmt- 04:00");
    $telnet->write_cmd("interface gpon " . $no2);
    $gpon = 2;
    $telnet->write_cmd("ont deactivate " . $gpon . " " . $onu);
    $telnet->write("logout");

    echo $_POST['DES'];
    $dia = date("d-m-Y");
    $hora = date("H:i:s");
    $fechareg = $dia . " " . $hora; //fecha de servicio
    $ip_pc = $_SERVER["HTTP_X_FORWARDED_FOR"]; //Ip de la PC
    //$ip_pc = '172.21.22.13';
    //echo $ctto."--".$nodo."--".$slot."--".$port."--".$motivo."--".$fechareg."--".$ip_pc."--".'H';
    $query = "INSERT INTO `crud_estado_port`(`id`, `ctto`, `nodo`,`shelf`, `slot`, `port`,`plan`, `hab`, `des`, `fecha_reg`, `ip_pc_reg`, `cod_emp`, `motivo`, `estado`, `estRepor`) VALUES('','$ctto','$nodo','$index','$gpon','$onu','','','D','$fechareg','$ip_pc','$cod','$motivo','1','A')";
    $add = mysqli_query($conectar, $query);
    header("Location: deshabilitaPuerto.php");}
}

if (isset($_POST['REI'])) {

    $telnet->write_cmd("config");
    $telnet->write_cmd("interface mgmt");
    $telnet->write_cmd("description \042NEW OLT TEST \042");
    $telnet->write_cmd("exit");
    $telnet->write_cmd("timezone gmt- 04:00");
    $telnet->write_cmd("interface gpon " . $no2);
    $telnet->write_cmd("ont deactivate " . $gpon . " " . $onu);
    sleep(10);
    $telnet->write_cmd("ont activate " . $gpon . " " . $onu);
    $telnet->write("logout");

    echo $_POST['HAB'];
    $dia = date("d-m-Y");
    $hora = date("H:i:s");
    $fechareg = $dia . " " . $hora; //fecha de servicio
    $ip_pc = $_SERVER["HTTP_X_FORWARDED_FOR"]; //Ip de la PC
    //$ip_pc = '172.21.22.13';
    //echo $ctto."--".$nodo."--".$slot."--".$port."--".$motivo."--".$fechareg."--".$ip_pc."--".'H';
    $query = "INSERT INTO `crud_estado_port`(`id`, `ctto`, `nodo`,`shelf`, `slot`, `port`,`plan`, `hab`, `des`, `fecha_reg`, `ip_pc_reg`, `cod_emp`, `motivo`, `estado`, `estRepor`) VALUES('','$ctto','$nodo','$index','$gpon','$onu','','H','','$fechareg','$ip_pc','$cod','$motivo','2','A')";
    $add = mysqli_query($conectar, $query);
    header("Location: reiniciaPuerto.php");
}

if (isset($_POST['ACT'])) {
    $up = $_POST['plan_up'];
    $down = $_POST['plan_dw'];
    $dia = date("d-m-Y");
    $hora = date("H:i:s");
    echo $serie.' '.$down.' '.$up.' '.$index.' '.$gpon." ".$onu;

    $telnet->write_cmd("config");
    $telnet->write_cmd("interface mgmt");
    $telnet->write_cmd("description \042NEW OLT TEST \042");
    $telnet->write_cmd("exit");
    $telnet->write_cmd("timezone gmt- 04:00");

    $telnet->write_cmd("no service-port " . $index);
    $telnet->write_cmd("interface gpon " . $no2);
    $telnet->write_cmd("ont delete " . $gpon . ' ' . $onu);

    $telnet->write_cmd('ont add ' . $gpon . ' ' . $onu.' sn-auth '.$serie.' ont-lineprofile-id 0 ont-srvprofile-id 0');
    $telnet->write_cmd("exit");
    $telnet->write_cmd('service-port '.$index.' vlan 76 gpon '.$no2.' port '.$gpon.' ont '.$onu.' gemport 1 multi-service user-vlan 76 tag-action transparent inbound name '.$up.' outbound name ' . $down);
    $telnet->write("logout");

    $fechareg = $dia . " " . $hora; //fecha de servicio
    $ip_pc = $_SERVER["HTTP_X_FORWARDED_FOR"]; //Ip de la PC
    //$ip_pc = '172.21.22.13';
    //echo $ctto."--".$nodo."--".$slot."--".$port."--".$motivo."--".$fechareg."--".$ip_pc."--".'H';
    $query= "INSERT INTO `crud_estado_port`(`id`, `ctto`, `nodo`,`shelf`, `slot`, `port`,`plan`, `hab`, `des`, `fecha_reg`, `ip_pc_reg`, `cod_emp`, `motivo`, `estado`, `estRepor`) VALUES('','$ctto','$nodo','$index','$gpon','$onu','$plan','','','$fechareg','$ip_pc','$cod','$motivo','3','A')";
    $add = mysqli_query($conectar, $query);
    header("Location: actualizaPlan.php");
}
//service-port 11 vlan 76 gpon 0/0 port 1 ont 11 gemport 1 multi-service user-vlan 76 tag-action transparent inbound name 40M outbound name 30M



if (isset($_POST['ACT_S'])) {
    $up = $_POST['up'].'M';
    $down = $_POST['down'].'M';
    $n_serie=$_POST['n_serie'];
    $dia = date("d-m-Y");
    $hora = date("H:i:s");
   //echo $n_serie.' '.$down.' '.$up;
    echo 'Tegnologia Sinotelcom en Desarrollo';
    
    $telnet->write_cmd("config");
    $telnet->write_cmd("interface mgmt");
    $telnet->write_cmd("description \042NEW OLT TEST \042");
    $telnet->write_cmd("exit");
    $telnet->write_cmd("timezone gmt- 04:00");

    $telnet->write_cmd("no service-port " . $index);
    $telnet->write_cmd("interface gpon " . $no2);
    $telnet->write_cmd("ont delete " . $gpon . ' ' . $onu);

    $telnet->write_cmd('ont add ' . $gpon . ' ' . $onu.' sn-auth '.$n_serie.' ont-lineprofile-id 0 ont-srvprofile-id 0');
    $telnet->write_cmd("exit");
    $telnet->write_cmd('service-port '.$index.' vlan 76 gpon '.$no2.' port '.$gpon.' ont '.$onu.' gemport 1 multi-service user-vlan 76 tag-action transparent inbound name '.$up.' outbound name ' . $down);
    $telnet->write("logout");

    $fechareg = $dia . " " . $hora; //fecha de servicio
    $ip_pc = $_SERVER["HTTP_X_FORWARDED_FOR"]; //Ip de la PC
    //$ip_pc = '172.21.22.13';
    //echo $ctto."--".$nodo."--".$slot."--".$port."--".$motivo."--".$fechareg."--".$ip_pc."--".'H';
    $query= "INSERT INTO `crud_estado_port`(`id`, `ctto`, `nodo`,`shelf`, `slot`, `port`,`plan`, `hab`, `des`, `fecha_reg`, `ip_pc_reg`, `cod_emp`, `motivo`, `estado`, `estRepor`) VALUES('','$ctto','$nodo','$index','$gpon','$onu','$plan','','','$fechareg','$ip_pc','$cod','$motivo','3','A')";
    $add = mysqli_query($conectar, $query);
    header("Location: actualizaSerieOp.php");
    //STGUBC064090
}
if (isset($_POST['APRO'])) {    
    if ($nodo=='OLT-MRF') {
        //$plan='20480'; 
        $plan=$_POST['plan_dw'];
        $plan=substr($plan, 0, -1);
        $plan=$plan * 1024;
        if ($marca='STG-402 ') {$idMarca=1;}    
        if ($marca='ISK') {$idMarca=2;}  
        if ($marca='SOFT-2GF ') {$idMarca=3;}     
        $posicion=$nodo.' '.$index.' '.$idMarca.' '.$marca.' '.$serie.' '.$plan; 
        exec('expect /var/www/html/norah/cortes/scripts/aprovisionaSinoTel.exp '.$posicion);  
        header("Location: aprovisionaSerie.php");   
    }elseif($nodo=='OLT-OCT'){
        $up = $_POST['plan_up'];
        $down = $_POST['plan_dw'];
        echo $nodo.' '.$index.' '.$gpon.' '.$serie.' '.$up.' '.$down;
    }else{ 
    $up = $_POST['plan_up'];
    $down = $_POST['plan_dw'];
    $dia = date("d-m-Y");
    $hora = date("H:i:s");
    echo $serie.' '.$down.' '.$up;
    
    $telnet->write_cmd("config");
    $telnet->write_cmd("interface mgmt");
    $telnet->write_cmd("description \042NEW OLT TEST \042");
    $telnet->write_cmd("exit");
    $telnet->write_cmd("timezone gmt- 04:00");

    $telnet->write_cmd("interface gpon " . $no2);
    $telnet->write_cmd('ont add ' . $gpon . ' ' . $onu.' sn-auth "'.$serie.'" ont-lineprofile-id 0 ont-srvprofile-id 0');
    $telnet->write_cmd("exit");
    $telnet->write_cmd('service-port '.$index.' vlan 76 gpon '.$no2.' port '.$gpon.' ont '.$onu.' gemport 1 multi-service user-vlan 76 tag-action transparent inbound name '.$up.' outbound name ' . $down);
    $telnet->write("logout");

    $fechareg = $dia . " " . $hora; //fecha de servicio
    $ip_pc = $_SERVER["HTTP_X_FORWARDED_FOR"]; //Ip de la PC
    //$ip_pc = '172.21.22.13';
    //echo $ctto."--".$nodo."--".$slot."--".$port."--".$motivo."--".$fechareg."--".$ip_pc."--".'H';
    $query= "INSERT INTO `crud_estado_port`(`id`, `ctto`, `nodo`,`shelf`, `slot`, `port`,`plan`, `hab`, `des`, `fecha_reg`, `ip_pc_reg`, `cod_emp`, `motivo`, `estado`, `estRepor`) VALUES('','$ctto','$nodo','$index','$gpon','$onu','$plan','','','$fechareg','$ip_pc','$cod','$motivo','3','A')";
   $add = mysqli_query($conectar, $query);
    header("Location: aprovisionaSerie.php");  }
}
if (isset($_POST['DES_APRO'])) {
    if ($nodo=='OLT-MRF') {        
        $posicion=$nodo.' '.$index; 
        exec('expect /var/www/html/norah/cortes/scripts/desAprovisionaSinoTel.exp '.$posicion);  
        header("Location: DesaprovisionaSerie.php");   
    }else{
    $up = $_POST['plan_up'];
    $down = $_POST['plan_dw'];
    $dia = date("d-m-Y");
    $hora = date("H:i:s");
    echo $serie.' '.$down.' '.$up;
    
    $telnet->write_cmd("config");
    $telnet->write_cmd("interface mgmt");
    $telnet->write_cmd("description \042NEW OLT TEST \042");
    $telnet->write_cmd("exit");
    $telnet->write_cmd("timezone gmt- 04:00");
    
    $telnet->write_cmd("no service-port " . $index);
    $telnet->write_cmd("interface gpon " . $no2);
    $telnet->write_cmd("ont delete " . $gpon . ' ' . $onu);

    $telnet->write_cmd("exit");
    $telnet->write("logout");

    $fechareg = $dia . " " . $hora; //fecha de servicio
    $ip_pc = $_SERVER["HTTP_X_FORWARDED_FOR"]; //Ip de la PC
    //$ip_pc = '172.21.22.13';
    //echo $ctto."--".$nodo."--".$slot."--".$port."--".$motivo."--".$fechareg."--".$ip_pc."--".'H';
    $query= "INSERT INTO `crud_estado_port`(`id`, `ctto`, `nodo`,`shelf`, `slot`, `port`,`plan`, `hab`, `des`, `fecha_reg`, `ip_pc_reg`, `cod_emp`, `motivo`, `estado`, `estRepor`) VALUES('','$ctto','$nodo','$index','$gpon','$onu','$plan','','','$fechareg','$ip_pc','$cod','$motivo','3','A')";
    $add = mysqli_query($conectar, $query);
    header("Location: DesaprovisionaSerie.php"); }
}
//ont add 3 125 sn-auth 333333333333 ont-lineprofile-id 0 ont-srvprofile-id 0
//service-port 381 vlan 76 gpon 0/0 port 3 ont 125 gemport 1 multi-service user-vlan 76 tag-action transparent inbound name 20 outbound name 20
/*
if (isset($_POST['LIB'])) {
    $dia = date("d-m-Y");
    $hora = date("H:i:s");
    $fechareg = $dia . " " . $hora; //fecha de servicio
    $fechaact = date("Y-m-d h:i:sa"); //fecha actual
    $ip_pc = $_SERVER["HTTP_X_FORWARDED_FOR"]; //Ip de la PC
    //$ip_pc = '172.21.22.13';
    //echo $ctto."--".$nodo."--".$slot."--".$port."--".$motivo."--".$fechareg."--".$ip_pc."--".'H';
    $query = "INSERT INTO `crud_estado_port`(`id`, `ctto`, `nodo`,`shelf`, `slot`, `port`,`plan`, `hab`, `des`, `fecha_reg`, `ip_pc_reg`, `cod_emp`, `motivo`, `estado`, `estRepor`) VALUES('','','$nodo','$shelf','$slot','$port','','','','$fechareg','$ip_pc','$cod','Puerto Liberado','3','A')";
    $query2 = "UPDATE `activos_ports` SET `ctto`='', `estado`='L', `fecha_reg_ports`='$fechareg', `ip_reg_pc`='$ip_pc', `cod_emp`='$cod', `port_obs`='$motivo' WHERE `nodo`='$nodo' AND `shelf`='$shelf' AND `slot`='$slot' AND `port`='$port'";
    $query3aux = "SELECT * FROM `activos_ports` WHERE `nodo`= '$nodo' AND `slot`='$slot' AND `port`='$port' ORDER BY `id_act`";
    $consulta = mysqli_query($conectar, $query3aux);
    while ($reg = mysqli_fetch_array($consulta)) {
        $motant = $reg['port_obs'];
        $query3 = "UPDATE `report_puertos` SET `estado_act`='$estadon', `motivo_act`='$motivo', `fecha_reg_ports`='$fechaact', `ip_reg_pc`='$ip_pc', `cod_emp`='$cod', `est`='0' WHERE `nodo`='$nodo' AND `shelf`='$shelf' AND `slot`='$slot' AND `port`='$port'";
        $add3 = mysqli_query($conectar, $query3);
    }
    $add = mysqli_query($conectar, $query);
    $add2 = mysqli_query($conectar, $query2);
    //include_once("recibeActualizaPlan.php");
    header("Location: liberaPuerto.php");
}
*/
?>
