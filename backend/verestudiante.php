<?php require_once('../Connections/pagodecuotaBD.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_Recordset1 = "-1";
if (isset($_GET['verestudiante'])) {
  $colname_Recordset1 = $_GET['verestudiante'];
}
mysql_select_db($database_pagodecuotaBD, $pagodecuotaBD);
$query_Recordset1 = sprintf("SELECT * FROM estudiante WHERE cedula = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $pagodecuotaBD) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_Recordset2 = "-1";
if (isset($_GET['verestudiante'])) {
  $colname_Recordset2 = $_GET['verestudiante'];
}
mysql_select_db($database_pagodecuotaBD, $pagodecuotaBD);
$query_Recordset2 = sprintf("SELECT * FROM cuota WHERE cedula = %s", GetSQLValueString($colname_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $pagodecuotaBD) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Datos del estudiante</title>
</head>
Cedula:<?php echo $row_Recordset1['cedula']; ?><br>
Nombre:<?php echo $row_Recordset1['nombre']; ?><br>
Apellido:<?php echo $row_Recordset1['apellido']; ?><br>
Direccion:<?php echo $row_Recordset1['direccion']; ?><br>
Telefono:<?php echo $row_Recordset1['telefono']; ?><br>
Correo:<?php echo $row_Recordset1['correo']; ?><br>
<br /><br /><br />
Cuota1:<?php echo $row_Recordset2['cuota1']; ?><br>
Cuota2:<?php echo $row_Recordset2['cuota2']; ?><br>
Cuota3:<?php echo $row_Recordset2['cuota3']; ?><br>
Cuota4:<?php echo $row_Recordset2['cuota4']; ?><br>
Cuota5:<?php echo $row_Recordset2['cuota5']; ?><br>
Cuota6:<?php echo $row_Recordset2['cuota6']; ?><br>
Fechas  y Hora de ultimo pago:<?php echo $row_Recordset2['fecha']; ?><br /><bR />

<?php
if (($row_Recordset2['cuota1']==('Pagado'))&&($row_Recordset2['cuota2']==('Pagado'))&&($row_Recordset2['cuota3']==('Pagado'))&&($row_Recordset2['cuota4']==('Pagado'))&&($row_Recordset2['cuota5']==('Pagado'))&&($row_Recordset2['cuota6']==('Pagado'))){
	$ocultar='disabled';
	}else{
	$ocultar=' ';
	}
?>
<?php
if (($row_Recordset2['cuota1']==(''))&&($row_Recordset2['cuota2']==(''))&&($row_Recordset2['cuota3']==(''))&&($row_Recordset2['']==(''))&&($row_Recordset2['cuota5']==(''))&&($row_Recordset2['cuota6']==(''))){
	$ocultar='disabled';
	}else{
	$ocultar=' ';
	}
?>

<a href="editarpago.php?verpago=<?php echo $row_Recordset1['cedula']; ?>"><button type="button" <?php echo $ocultar ?> >Actualizar Pago </button></a>
<br>
<br>


<body>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
