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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO cuota (cedula, cuota1, cuota2, cuota3, cuota4, cuota5, cuota6) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cedula'], "int"),
                       GetSQLValueString($_POST['cuota1'], "text"),
                       GetSQLValueString($_POST['cuota2'], "text"),
                       GetSQLValueString($_POST['cuota3'], "text"),
                       GetSQLValueString($_POST['cuota4'], "text"),
                       GetSQLValueString($_POST['cuota5'], "text"),
                       GetSQLValueString($_POST['cuota6'], "text"));

  mysql_select_db($database_pagodecuotaBD, $pagodecuotaBD);
  $Result1 = mysql_query($insertSQL, $pagodecuotaBD) or die(mysql_error());

  $insertGoTo = "confirmarp.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_pagodecuotaBD, $pagodecuotaBD);
$query_Recordset1 = "SELECT * FROM cuota";
$Recordset1 = mysql_query($query_Recordset1, $pagodecuotaBD) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_cedula = "-1";
if (isset($_GET['cedula'])) {
  $colname_cedula = $_GET['cedula'];
}
mysql_select_db($database_pagodecuotaBD, $pagodecuotaBD);
$query_cedula = sprintf("SELECT * FROM estudiante WHERE cedula = %s", GetSQLValueString($colname_cedula, "int"));
$cedula = mysql_query($query_cedula, $pagodecuotaBD) or die(mysql_error());
$row_cedula = mysql_fetch_assoc($cedula);
$totalRows_cedula = mysql_num_rows($cedula);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar Pago</title>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar navbar-default navbar-static-top">
  <div class="container">
  <a class="navbar-brand" href="index.php" >Panel de Administración | Control  de pago de cuota</a>
     <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active" ><a href="agregar.php"> Ingresar Nuevo </a></li>
        <li ><a href="listaestudiantes.php">Lista de estudiantes</a></li>
      </ul>
      
  </div>
</nav>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cedula:</td>
      <td><input type="text" name="cedula" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota1:</td>
      <td><select name="cuota1"/>
          <option value="No Pagado">No pagado</option>
     	  <option value="Pagado">Pagado</option> </select> 
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota2:</td>
      <td><select name="cuota2"/>
      <option value="No Pagado">No pagado</option>
     	  <option value="Pagado">Pagado</option></select>
         
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota3:</td>
      <td><select name="cuota3"/>
      <option value="No Pagado">No pagado</option>
     	  <option value="Pagado">Pagado</option></select>
          
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota4:</td>
      <td><select name="cuota4"/>
      <option value="No Pagado">No pagado</option>
     	  <option value="Pagado">Pagado</option></select>
         
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota5:</td>
      <td><select name="cuota5"/>
      <option value="No Pagado">No pagado</option>
     	  <option value="Pagado">Pagado</option></select>
          
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota6:</td>
      <td><select name="cuota6"/>
      <option value="No Pagado">No pagado</option>
     	  <option value="Pagado">Pagado</option></select>
        
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insertar registro" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($cedula);
?>
