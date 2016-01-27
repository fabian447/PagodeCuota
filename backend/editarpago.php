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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE cuota SET cedula=%s, cuota1=%s, cuota2=%s, cuota3=%s, cuota4=%s, cuota5=%s, cuota6=%s WHERE id=%s",
                       GetSQLValueString($_POST['cedula'], "int"),
                       GetSQLValueString($_POST['cuota1'], "text"),
                       GetSQLValueString($_POST['cuota2'], "text"),
                       GetSQLValueString($_POST['cuota3'], "text"),
                       GetSQLValueString($_POST['cuota4'], "text"),
                       GetSQLValueString($_POST['cuota5'], "text"),
                       GetSQLValueString($_POST['cuota6'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_pagodecuotaBD, $pagodecuotaBD);
  $Result1 = mysql_query($updateSQL, $pagodecuotaBD) or die(mysql_error());

  $updateGoTo = "confirmarp.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['verpago'])) {
  $colname_Recordset1 = $_GET['verpago'];
}
mysql_select_db($database_pagodecuotaBD, $pagodecuotaBD);
$query_Recordset1 = sprintf("SELECT * FROM cuota WHERE cedula = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $pagodecuotaBD) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Actualizar Pago</title>
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
<?php
if ($row_Recordset1['cuota1']==('Pagado')){
	$estado='hidden';
	$mensaje='Pagado';
}else{
	$estado=' ';
	$mensaje=' ';
}

if ($row_Recordset1['cuota2']==('Pagado')){
	$estado2='hidden';
	$mensaje2='Pagado';
}else{
	$estado2=' ';
	$mensaje2=' ';
}

if ($row_Recordset1['cuota3']==('Pagado')){
	$estado3='hidden';
	$mensaje3='Pagado';
}else{
	$estado3=' ';
	$mensaje3=' ';
}

if ($row_Recordset1['cuota4']==('Pagado')){
	$estado4='hidden';
	$mensaje4='Pagado';
}else{
	$estado4=' ';
	$mensaje4=' ';
}

if ($row_Recordset1['cuota5']==('Pagado')){
	$estado5='hidden';
	$mensaje5='Pagado';
}else{
	$estado5=' ';
	$mensaje5=' ';
}

if ($row_Recordset1['cuota6']==('Pagado')){
	$estado6='hidden';
	$mensaje6='Pagado';
}else{
	$estado6=' ';
	$mensaje6=' ';
}


?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td><input type="hidden" name="cedula" value="<?php echo htmlentities($row_Recordset1['cedula'], ENT_COMPAT, 'utf-8'); ?>" size="32"  /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota1:</td>
      <td><select name="cuota1" <?php echo $estado ?> />
      	 <option value="<?php echo htmlentities($row_Recordset1['cuota1'], ENT_COMPAT, 'utf-8'); ?>"><?php echo htmlentities($row_Recordset1['cuota1'], ENT_COMPAT, 'utf-8'); ?></option>
          <option value="Pagado">Pagado</option>
      		</select><?php echo $mensaje; ?>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota2:</td>
      <td><select name="cuota2" <?php echo $estado2 ?>/>
       <option value="<?php echo htmlentities($row_Recordset1['cuota2'], ENT_COMPAT, 'utf-8'); ?>"><?php echo htmlentities($row_Recordset1['cuota2'], ENT_COMPAT, 'utf-8'); ?></option>
          <option value="Pagado">Pagado</option>
          
      		</select><?php echo $mensaje2; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota3:</td>
      <td><select name="cuota3" <?php echo $estado3 ?> />
      <option value="<?php echo htmlentities($row_Recordset1['cuota3'], ENT_COMPAT, 'utf-8'); ?>"><?php echo htmlentities($row_Recordset1['cuota3'], ENT_COMPAT, 'utf-8'); ?></option>
          <option value="Pagado">Pagado</option>
      		</select><?php echo $mensaje3; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota4:</td>
      <td><select name="cuota4" <?php echo $estado4 ?> />
       <option value="<?php echo htmlentities($row_Recordset1['cuota4'], ENT_COMPAT, 'utf-8'); ?>"><?php echo htmlentities($row_Recordset1['cuota4'], ENT_COMPAT, 'utf-8'); ?></option>
          <option value="Pagado">Pagado</option>
      		</select><?php echo $mensaje4; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota5:</td>
      <td><select name="cuota5" <?php echo $estado5 ?> />
       <option value="<?php echo htmlentities($row_Recordset1['cuota5'], ENT_COMPAT, 'utf-8'); ?>"><?php echo htmlentities($row_Recordset1['cuota5'], ENT_COMPAT, 'utf-8'); ?></option>
          <option value="Pagado">Pagado</option>
          
      		</select><?php echo $mensaje5; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuota6:</td>
      <td><select name="cuota6" <?php echo $estado6 ?> />
       <option value="<?php echo htmlentities($row_Recordset1['cuota6'], ENT_COMPAT, 'utf-8'); ?>"><?php echo htmlentities($row_Recordset1['cuota6'], ENT_COMPAT, 'utf-8'); ?></option>
          <option value="Pagado">Pagado</option>
      		</select><?php echo $mensaje6; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Actualizar registro" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_Recordset1['id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
