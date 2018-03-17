<?php
// This code is copyright Internet Business Solutions SL.
// Unauthorized copying, use or transmittal without the
// express permission of Internet Business Solutions SL
// is strictly prohibited.
// Author: Vince Reid, vince@virtualred.net
$sVersion='PHP v6.4.2';
ob_start();
ob_implicit_flush();
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Update Ecommerce Plus mySQL database to version <?php print $sVersion ?></title>
<style type="text/css">
<!--
p {  font: 11pt  Arial, Helvetica, sans-serif}
BODY {  font: 11pt Arial, Helvetica, sans-serif}
-->
</style>
<?php
include 'vsadmin/db_conn_open.php';
include 'vsadmin/includes.php';
ini_set('magic_quotes_runtime','0');
if(@$adminencoding=='') $adminencoding='iso-8859-1';
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print $adminencoding ?>" />
</head>
<body>
<div style="padding:24px;border: 1px solid #F63;width: 680px; margin: 0 auto;background:#ebf4fb;-moz-border-radius:10px;-webkit-border-radius:10px;margin-top:40px;">
<?php
$haserrors=FALSE;

$addcl = "ADD COLUMN";
$txtcl = "VARCHAR";
$idtxtcl = "VARCHAR";
$smallcl = "SMALLINT";
$bytecl = "TINYINT";
$dblcl = "DOUBLE";
$memocl = "TEXT";
$datecl = "DATETIME";
$smalldatecl = "DATE";
$autoinc = "INT NOT NULL AUTO_INCREMENT PRIMARY KEY";
$datedelim="'";
$bitfield="TINYINT(1)";
$altcl="MODIFY";
$txtcollen=1024;

function escape_string($estr){
	return(@$GLOBALS['ectdatabase']?$GLOBALS['ectdatabase']->real_escape_string($estr):mysql_real_escape_string($estr));
}
function ect_query($ectsql){
	return(@$GLOBALS['ectdatabase']?$GLOBALS['ectdatabase']->query($ectsql):mysql_query($ectsql));
}
function ect_fetch_assoc($ectres){
	return(@$GLOBALS['ectdatabase']?$ectres->fetch_assoc():mysql_fetch_assoc($ectres));
}
function ect_num_rows($ectres){
	return(@$GLOBALS['ectdatabase']?$ectres->num_rows:mysql_num_rows($ectres));
}
function ect_insert_id(){
	return(@$GLOBALS['ectdatabase']?$GLOBALS['ectdatabase']->insert_id:mysql_insert_id());
}
function ect_free_result($ectres){
	@$GLOBALS['ectdatabase']?$ectres->free_result():mysql_free_result($ectres);
}
function ect_error(){
	print(@$GLOBALS['ectdatabase']?$GLOBALS['ectdatabase']->error:mysql_error());
}
function dohashpw($thepw){
	if(trim($thepw)=='') return(''); else return(md5('ECT IS BEST'.trim($thepw)));
}
function print_sql_error(){
	global $haserrors;
	$haserrors=TRUE;
	print('<font color="#FF0000">' . ect_error() . '</font><br />');
}
function createanindex($dbtbl,$colname){
	$result = ect_query("SHOW INDEX FROM " . $dbtbl) or print_sql_error();
	$hasindex=FALSE;
	while($rs = ect_fetch_assoc($result))
		if(strtolower($rs['Column_name'])==strtolower($colname))
			$hasindex=TRUE;
	ect_free_result($result);
	if(!$hasindex) ect_query("ALTER TABLE " . $dbtbl . " ADD INDEX (" . $colname . ")") or print_sql_error();
}
function printtick($tstr){
	print '<script type="text/javascript">iqueue.push(\'B' . str_replace("'","\'",$tstr) . "');</script>\r\n";
	flush();
	ob_flush();
}
function printtickdiv($tstr){
	print '<script type="text/javascript">iqueue.push(\'A' . str_replace("'","\'",$tstr) . "');</script>\r\n";
	flush();
	ob_flush();
}
function checkaddcolumn($tblname,$colname,$notnull,$dtype,$dlen,$setdef){
	global $txtcl,$idtxtcl,$smallcl,$bytecl,$dblcl,$memocl,$datecl,$smalldatecl,$bitfield;
	printtickdiv('Checking for ' . $colname . ' in table ' . $tblname);
	$sSQL='SELECT ' . $colname . ' FROM ' . $tblname . ' LIMIT 0,1';
	$columnexists=TRUE;
	ect_query($sSQL) or $columnexists=FALSE;
	if($columnexists==FALSE){
		printtick('Adding ' . $colname . ' column to ' . $tblname . ' table.<br />');
		$defval="";
		if($dtype=="INT" || $dtype==$dblcl || $dtype==$smallcl || $dtype==$bytecl || $dtype==$bitfield){ $defval="DEFAULT 0"; $setdef=($setdef==''?'0':$setdef); }
		if($dtype==$txtcl || $dtype==$idtxtcl){ $defval="DEFAULT ''"; $setdef=($setdef==''?"''":$setdef); }
		if($dtype==$memocl){ $defval='x'; $setdef=($setdef==''?"''":$setdef); }
		if($dtype==$datecl || $dtype==$smalldatecl){
			$defval='x';
			$setdef="'" . date('Y-m-d H:i:s', time()-100000) . "'";
		}
		if($defval==''){ print '<font color="#FF0000">' . $dtype . ' not supported!!<br />'; exit; }
		if($defval=='x') $defval='';
		$sSQL = 'ALTER TABLE ' . $tblname . ' ADD COLUMN ' . $colname . ' ' . $dtype . $dlen . ' ' . ($notnull?'NOT NULL ':'') . $defval;
		// print $sSQL . '<br />';
		ect_query($sSQL) or print_sql_error();
		if($setdef!='') ect_query('UPDATE ' . $tblname . ' SET ' . $colname . '=' . $setdef) or print_sql_error();
	}
	flush();
	return(!$columnexists);
}

if(@$_POST["posted"]=="1"){

@set_time_limit(1800);
// ect_query("DROP TABLE address,admin,adminlogin,affiliates,alternaterates,cart,cartoptions,contentregions,countries,coupons,cpnassign,customerlists,customerlogin,dropshipper,emailmessages,giftcertificate,giftcertsapplied,installedmods,ipblocking,mailinglist,manufacturer,multibuyblock,multisections,optiongroup,options,orders,orderstatus,payprovider,postalzones,pricebreaks,prodoptions,products,ratings,recentlyviewed,relatedprods,sections,shipoptions,states,tmplogin,uspsmethods,zonecharges") or print_sql_error();

?>
<script type="text/javascript">
var iqueue = [];
function writetickitem(sitm){
	var msgtype=sitm.substr(0,1);
	if(msgtype=='A')
		document.getElementById('checkdiv').innerHTML=sitm.substr(1);
	else if(msgtype=='B'){
		var thetable = document.getElementById('resulttable');
		newrow = thetable.insertRow(-1);
		newcell = newrow.insertCell(0);
		newcell.innerHTML='<img src="http://www.ecommercetemplates.com/images/ecttick.gif"> ';
		newcell = newrow.insertCell(1);
		newcell.innerHTML=sitm.substr(1);
	}else if(msgtype=='C'){
		clearInterval(intid);
		setTimeout("document.location='updatestore.php?posted=2'",8000)
	}
}
function checkqueue(){
	if(iqueue.length>0){
		writetickitem(iqueue.shift());
	}
}
var intid=setInterval("checkqueue()", 30);
</script>
<table>
	<tr>
		<td width="20" align="right"><img src="http://www.ecommercetemplates.com/images/ecttick.gif"> </td>
		<td><div id="checkdiv">Checking for Ecommerce Plus Template...</div></td>
	</tr>
</table>
<table id="resulttable">
	<tr>
		<td width="20" align="right">&nbsp;</td><td>&nbsp;</td>
	</tr>
</table>
<?php
printtickdiv('Checking for Tmp Login upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM tmplogin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Tmp Login table');
	ect_query("CREATE TABLE tmplogin (tmploginid VARCHAR(100) PRIMARY KEY,tmploginname VARCHAR(50) NULL,tmplogindate DATETIME)") or print_sql_error();
}

printtickdiv('Checking for Order Status upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM orderstatus") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Order Status table');
	ect_query("CREATE TABLE orderstatus (statID INT PRIMARY KEY,statPrivate VARCHAR(255) NULL,statPublic VARCHAR(255) NULL)") or print_sql_error();
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (0,'Cancelled','Order Cancelled')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (1,'Deleted','Order Deleted')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (2,'Unauthorized','Awaiting Payment')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (3,'Authorized','Payment Received')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (4,'Packing','In Packing')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (5,'Shipping','In Shipping')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (6,'Shipped','Order Shipped')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (7,'Completed','Order Completed')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (8,'','')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (9,'','')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (10,'','')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (11,'','')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (12,'','')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (13,'','')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (14,'','')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (15,'','')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (16,'','')");
	ect_query("INSERT INTO orderstatus (statID,statPrivate,statPublic) VALUES (17,'','')");
}
flush();

checkaddcolumn("products","pWholesalePrice",FALSE,$dblcl,"","");
checkaddcolumn("orders","ordExtra1",FALSE,$txtcl,"(255)","");
checkaddcolumn("orders","ordExtra2",FALSE,$txtcl,"(255)","");
checkaddcolumn("orders","ordHSTTax",FALSE,$dblcl,"","");

checkaddcolumn("admin","smtpserver",FALSE,$txtcl,"(100)","");
checkaddcolumn("admin","emailUser",FALSE,$txtcl,"(50)","");
checkaddcolumn("admin","emailPass",FALSE,$txtcl,"(50)","");

printtickdiv('Checking for Order Status orders upgrade');
$columnexists=TRUE;
ect_query("SELECT ordStatus FROM orders") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Order Status orders columns');
	ect_query("ALTER TABLE orders ADD COLUMN ordStatus TINYINT DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordStatusDate DATETIME") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordStatusInfo TEXT NULL") or print_sql_error();
	ect_query("UPDATE orders SET ordStatus=2") or print_sql_error();
	ect_query("UPDATE orders SET ordStatus=3 WHERE ordAuthNumber<>''") or print_sql_error();
	ect_query("UPDATE orders SET ordStatusDate=ordDate") or print_sql_error();
}

printtickdiv('Checking for Options Percentage upgrade');
$columnexists=TRUE;
ect_query("SELECT optFlags FROM optiongroup") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Options Percentage columns');
	ect_query("ALTER TABLE optiongroup ADD COLUMN optFlags INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE optiongroup SET optFlags=0") or print_sql_error();
	// This change can only be done once and is necessary for the v3.6.5 upgrade
	ect_query("UPDATE products SET pExemptions=7 WHERE pExemptions=3") or print_sql_error();
	ect_query("UPDATE products SET pExemptions=4 WHERE pExemptions=2") or print_sql_error();
	ect_query("UPDATE products SET pExemptions=3 WHERE pExemptions=1") or print_sql_error();
}

printtickdiv('Checking for Currency Conversions upgrade');
$columnexists=TRUE;
ect_query("SELECT currRate1 FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Currency Conversions columns');
	ect_query("ALTER TABLE admin ADD COLUMN currRate1 DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN currRate2 DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN currRate3 DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN currSymbol1 VARCHAR(50) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN currSymbol2 VARCHAR(50) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN currSymbol3 VARCHAR(50) NULL") or print_sql_error();
	ect_query("UPDATE admin SET currRate1=0,currRate2=0,currRate3=0,currSymbol1='',currSymbol2='',currSymbol3=''") or print_sql_error();
}

checkaddcolumn("admin","currConvUser",FALSE,$txtcl,"(50)","");
checkaddcolumn("admin","currConvPw",FALSE,$txtcl,"(50)","");
checkaddcolumn("admin","currLastUpdate",FALSE,$datecl,"","");

printtickdiv('Checking for multisections upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM multisections") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding multisections table');
	$sSQL = "CREATE TABLE multisections (pID VARCHAR(128) NOT NULL,";
	$sSQL .= "pSection INT DEFAULT 0 NOT NULL,";
	$sSQL .= "PRIMARY KEY (pID, pSection))";
	ect_query($sSQL) or print_sql_error();
}
flush();

printtickdiv('Checking for pay provider method upgrade');
$columnexists=TRUE;
ect_query("SELECT payProvMethod FROM payprovider") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding pay provider method column');
	ect_query("ALTER TABLE payprovider ADD COLUMN payProvMethod INT DEFAULT 0") or print_sql_error();
	$sSQL = "SELECT payProvID,payProvData2 FROM payprovider WHERE payProvID=11 OR payProvID=12";
	$result = ect_query($sSQL) or print_sql_error();
	while($rs = ect_fetch_assoc($result)){
		if(trim($rs["payProvData2"]) != ""){
			$sSQL = "UPDATE payprovider SET payProvMethod=".$rs["payProvData2"]." WHERE payProvID=" . $rs["payProvID"];
			ect_query($sSQL) or print_sql_error();
		}
	}
	ect_free_result($result);
	ect_query("UPDATE payprovider SET payProvData2='' WHERE payProvID=11 OR payProvID=12") or print_sql_error();
}

checkaddcolumn("admin","adminUPSUser",FALSE,$txtcl,"(100)","");
checkaddcolumn("admin","adminUPSpw",FALSE,$txtcl,"(100)","");
checkaddcolumn("admin","adminUPSAccess",FALSE,$txtcl,"(100)","");
checkaddcolumn("admin","adminCanPostUser",FALSE,$txtcl,"(100)","");
// checkaddcolumn("admin","adminUPSLicense",FALSE,$memocl,"","");

checkaddcolumn("orders","ordComLoc",FALSE,$bytecl,"","");

printtickdiv('Checking for Admin Units upgrade');
$columnexists=TRUE;
ect_query("SELECT adminUnits FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Admin Units column');
	ect_query("ALTER TABLE admin ADD COLUMN adminUnits TINYINT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE admin SET adminUnits=1") or print_sql_error();
}

printtickdiv('Checking for Capture Card admin upgrade');
$columnexists=TRUE;
ect_query("SELECT adminCert FROM admin") or $columnexists=FALSE;
if($columnexists!=FALSE){
	printtick('Adding Capture Card admin columns');
	ect_query("UPDATE admin SET adminCert='01010101010101010101010101010101010101010101010101010101010101'") or print_sql_error();
	ect_query("UPDATE admin SET adminCert='10101010101010101010101010101010101010101010101010101010101010'") or print_sql_error();
	ect_query("UPDATE admin SET adminCert='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'") or print_sql_error();
	ect_query("ALTER TABLE admin DROP COLUMN adminCert") or print_sql_error();
}
flush();

$columnexists=TRUE;
ect_query("SELECT adminDelCC FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Capture Card admin columns');
	ect_query("ALTER TABLE admin ADD COLUMN adminDelCC INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE admin SET adminDelCC=7") or print_sql_error();
}

checkaddcolumn("orders","ordCNum",FALSE,$memocl,"","");
checkaddcolumn("admin","adminTweaks",FALSE,"INT","","");
checkaddcolumn("admin","adminHandling",FALSE,$dblcl,"","");
checkaddcolumn("orders","ordHandling",FALSE,$dblcl,"","");

printtickdiv('Checking for discounts upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM coupons") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding coupons table');
	$sSQL = "CREATE TABLE coupons (cpnID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$sSQL .= "cpnWorkingName VARCHAR(255),";
	$sSQL .= "cpnNumber VARCHAR(255),";
	$sSQL .= "cpnType INT DEFAULT 0,";
	$sSQL .= "cpnEndDate DATETIME,";
	$sSQL .= "cpnDiscount DOUBLE DEFAULT 0,";
	$sSQL .= "cpnThreshold DOUBLE DEFAULT 0,";
	$sSQL .= "cpnQuantity INT DEFAULT 0,";
	$sSQL .= "cpnNumAvail INT DEFAULT 0,";
	$sSQL .= "cpnCntry TINYINT DEFAULT 0,";
	$sSQL .= "cpnIsCoupon TINYINT DEFAULT 0,";
	$sSQL .= "cpnSitewide TINYINT DEFAULT 0)";
	ect_query($sSQL) or print_sql_error();
}

printtickdiv('Checking for discount max upgrade');
$columnexists=TRUE;
ect_query("SELECT cpnThresholdMax FROM coupons") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding discount max columns');
	ect_query("ALTER TABLE coupons ADD COLUMN cpnThresholdMax DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE coupons ADD COLUMN cpnQuantityMax INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE coupons SET cpnThresholdMax=0,cpnQuantityMax=0") or print_sql_error();
}
flush();

printtickdiv('Checking for discount assignment upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM cpnassign") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding discount assignment table');
	$sSQL = "CREATE TABLE cpnassign (cpaID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
	$sSQL .= "cpaCpnID INT DEFAULT 0,";
	$sSQL .= "cpaType TINYINT DEFAULT 0,";
	$sSQL .= "cpaAssignment VARCHAR(255))";
	ect_query($sSQL) or print_sql_error();
}

printtickdiv('Checking for order discounts upgrade');
$columnexists=TRUE;
ect_query("SELECT ordDiscount FROM orders") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding order discounts column');
	ect_query("ALTER TABLE orders ADD COLUMN ordDiscount DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordDiscountText VARCHAR(255) NULL") or print_sql_error();
	ect_query("UPDATE orders SET ordDiscount=0,ordDiscountText=''") or print_sql_error();
}

printtickdiv('Checking for countries fsa upgrade');
$columnexists=TRUE;
ect_query("SELECT countryFreeShip FROM countries") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding countries fsa column');
	ect_query("ALTER TABLE countries ADD COLUMN countryFreeShip TINYINT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE countries SET countryFreeShip=0") or print_sql_error();
}
flush();

printtickdiv('Checking for states fsa upgrade');
$columnexists=TRUE;
ect_query("SELECT stateFreeShip FROM states") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding states fsa column');
	ect_query("ALTER TABLE states ADD COLUMN stateFreeShip TINYINT DEFAULT 1") or print_sql_error();
	ect_query("UPDATE states SET stateFreeShip=1") or print_sql_error();
}

printtickdiv('Checking for USPS Methods upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM uspsmethods") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding USPS Methods table');
	$sSQL = "CREATE TABLE uspsmethods (uspsID INT PRIMARY KEY,";
	$sSQL .= "uspsMethod VARCHAR(150) NOT NULL,";
	$sSQL .= "uspsShowAs VARCHAR(150) NOT NULL,";
	$sSQL .= "uspsUseMethod TINYINT DEFAULT 0,";
	$sSQL .= "uspsLocal TINYINT DEFAULT 0)";
	ect_query($sSQL);
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (1,'EXPRESS','Express Mail',0,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (2,'PRIORITY','Priority Mail',0,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (3,'PARCEL','Parcel Post',1,1)");
}

printtickdiv('Checking for UPS Methods upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT uspsID FROM uspsmethods WHERE uspsID=101") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding UPS Methods info');
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (101,'01','UPS Next Day Air&reg;',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (102,'02','UPS 2nd Day Air&reg;',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (103,'03','UPS Ground',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (104,'07','UPS Worldwide Express',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (105,'08','UPS Worldwide Expedited',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (106,'11','UPS Standard',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (107,'12','UPS 3 Day Select&reg;',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (108,'13','UPS Next Day Air Saver&reg;',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (109,'14','UPS Next Day Air&reg; Early A.M.&reg;',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (110,'54','UPS Worldwide Express Plus',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (111,'59','UPS 2nd Day Air A.M.&reg;',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (112,'65','UPS Express Saver',1,1)");
}
ect_free_result($result);

@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (14,'Media','Media Mail',0,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (15,'BPM','Bound Printed Matter',0,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (16,'FIRST CLASS','First-Class Mail',0,1)");
flush();

printtickdiv('Checking for U(S)PS FSA upgrade');
$columnexists=TRUE;
ect_query("SELECT uspsFSA FROM uspsmethods") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding U(S)PS FSA columns');
	ect_query("ALTER TABLE uspsmethods ADD COLUMN uspsFSA TINYINT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE uspsmethods SET uspsFSA=0") or print_sql_error();
	ect_query("UPDATE uspsmethods SET uspsFSA=1 WHERE uspsID=103 OR uspsID=3") or print_sql_error();
	ect_query("ALTER TABLE postalzones ADD COLUMN pzFSA INT DEFAULT 1") or print_sql_error();
	ect_query("UPDATE postalzones SET pzFSA=1") or print_sql_error();
}

printtickdiv('Checking for List Price upgrade');
$columnexists=TRUE;
ect_query("SELECT COUNT(pListPrice) FROM products") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding List Price column');
	ect_query("ALTER TABLE products ADD COLUMN pListPrice DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("UPDATE products SET pListPrice=0") or print_sql_error();
}

// These are additions for template versions v2.5.0

printtickdiv('Checking for pay provider order upgrade');
$columnexists=TRUE;
ect_query("SELECT payProvOrder FROM payprovider") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding pay provider order column');
	ect_query("ALTER TABLE payprovider ADD COLUMN payProvOrder INT DEFAULT 0") or print_sql_error();
	$sSQL = "SELECT payProvID FROM payprovider";
	$index=0;
	$result = ect_query($sSQL) or print_sql_error();
	while($rs = ect_fetch_assoc($result)){
		$sSQL = "UPDATE payprovider SET payProvOrder=".$index." WHERE payProvID=" . $rs["payProvID"];
		ect_query($sSQL) or print_sql_error();
		$index++;
	}
	ect_free_result($result);
}

$columnexists=TRUE;
@ect_query("SELECT * FROM topsections") or $columnexists=FALSE;
if($columnexists){ // If it's there, have to add the column. If not then no worries as it is going to get deleted.
	printtickdiv('Checking for top category order upgrade');
	$columnexists=TRUE;
	ect_query("SELECT tsOrder FROM topsections") or $columnexists=FALSE;
	if($columnexists==FALSE){
		printtick('Adding top category order column');
		ect_query("ALTER TABLE topsections ADD COLUMN tsOrder INT DEFAULT 0") or print_sql_error();
		$sSQL = "SELECT tsID FROM topsections ORDER BY tsName";
		$index=0;
		$result = ect_query($sSQL) or print_sql_error();
		while($rs = ect_fetch_assoc($result)){
			$sSQL = "UPDATE topsections SET tsOrder=".$index." WHERE tsID=" . $rs["tsID"];
			ect_query($sSQL) or print_sql_error();
			$index++;
		}
		ect_free_result($result);
	}
}
flush();

printtickdiv('Checking for category order upgrade');
$columnexists=TRUE;
ect_query("SELECT sectionOrder FROM sections") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding category order column');
	ect_query("ALTER TABLE sections ADD COLUMN sectionOrder INT DEFAULT 0") or print_sql_error();
	$sSQL = "SELECT sectionID FROM sections ORDER BY sectionName";
	$index=0;
	$result = ect_query($sSQL) or print_sql_error();
	while($rs = ect_fetch_assoc($result)){
		$sSQL = "UPDATE sections SET sectionOrder=".$index." WHERE sectionID=" . $rs["sectionID"];
		ect_query($sSQL) or print_sql_error();
		$index++;
	}
	ect_free_result($result);
}

printtickdiv('Checking for disabled section upgrade');
$columnexists=TRUE;
ect_query("SELECT sectionDisabled FROM sections") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding disabled section column');
	ect_query("ALTER TABLE sections ADD COLUMN sectionDisabled TINYINT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE sections SET sectionDisabled=0") or print_sql_error();
}

printtickdiv('Checking for VeriSign PayFlow Link upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=8") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding VeriSign PayFlow Link info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (8,'Payflow Link','Credit Card',0,1,0,'','',8)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for PayPoint.net upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=9") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding PayPoint.net info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (9,'PayPoint.net','Credit Card',0,1,0,'','',9)") or print_sql_error();
}
ect_free_result($result);
ect_query("UPDATE payprovider SET payProvName='PayPoint.net' WHERE payProvID=9");
flush();

printtickdiv('Checking for Capture Card upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=10") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding Capture Card info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (10,'Capture Card','Credit Card',0,1,0,'XXXXXOOOOOOO','',10)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for PSiGate upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=11") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding PSiGate info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (11,'PSiGate','Credit Card',0,1,0,'','',11)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for PSiGate SSL upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=12") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding PSiGate SSL info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (12,'PSiGate SSL','Credit Card',0,1,0,'','',12)") or print_sql_error();
}

printtickdiv('Checking for Auth.NET AIM upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=13") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding Auth.NET AIM info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (13,'Auth.NET AIM','Credit Card',0,1,0,'','',13)") or print_sql_error();
}
ect_free_result($result);
flush();

printtickdiv('Checking for Custom Pay Provider upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=14") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding Custom Pay Provider info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (14,'Custom','Credit Card',0,1,0,'','',14)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for Netbanx upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=15") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding Netbanx info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (15,'Netbanx','Credit Card',0,1,0,'','',15)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for Linkpoint upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=16") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding Netbanx info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (16,'Linkpoint','Credit Card',0,1,0,'','',16)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for options weight difference upgrade');
$columnexists=TRUE;
ect_query("SELECT optWeightDiff FROM options") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding options weight difference column');
	ect_query("ALTER TABLE options ADD COLUMN optWeightDiff DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("UPDATE options SET optWeightDiff=0") or print_sql_error();
}

printtickdiv('Checking for options wholesale price difference upgrade');
$columnexists=TRUE;
ect_query("SELECT optWholesalePriceDiff FROM options") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding options wholesale price difference column');
	ect_query("ALTER TABLE options ADD COLUMN optWholesalePriceDiff DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("UPDATE options SET optWholesalePriceDiff=optPriceDiff") or print_sql_error();
}

printtickdiv('Checking for stock options upgrade');
$columnexists=TRUE;
ect_query("SELECT optStock FROM options") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding stock options column');
	ect_query("ALTER TABLE options ADD COLUMN optStock INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE options SET optStock=0") or print_sql_error();
}

printtickdiv('Checking for cartoptions weight difference upgrade');
$columnexists=TRUE;
ect_query("SELECT coWeightDiff FROM cartoptions") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding cartoptions weight difference column');
	ect_query("ALTER TABLE cartoptions ADD COLUMN coWeightDiff DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("UPDATE cartoptions SET coWeightDiff=0") or print_sql_error();
}
flush();

// These are additions for template versions v2.0.2

printtickdiv('Updating countries table data');
ect_query("UPDATE countries SET countryLCID='da_DK' WHERE countryID=50") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='su_FI' WHERE countryID=64") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='fr_FR' WHERE countryID=65") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='de_DE' WHERE countryID=71") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='el_GR' WHERE countryID=74") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='id_ID' WHERE countryID=89") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='it_IT' WHERE countryID=93") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='jp_JP' WHERE countryID=95") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='ms_MY' WHERE countryID=115") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='nl_NL' WHERE countryID=133") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='no_NO' WHERE countryID=143") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='pt_PT' WHERE countryID=153") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='ro_RO' WHERE countryID=156") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='en_ZA' WHERE countryID=174") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='es_ES' WHERE countryID=175") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='sv_SE' WHERE countryID=182") or print_sql_error();
ect_query("UPDATE countries SET countryLCID='es_VE' WHERE countryID=206") or print_sql_error();

printtickdiv('Checking for affilites upgrade');

$columnexists=TRUE;
ect_query("SELECT affilID FROM affiliates") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding affiliates table');
	$sSQL = "CREATE TABLE affiliates (affilID VARCHAR(32) NOT NULL PRIMARY KEY,";
	$sSQL .= "affilPW VARCHAR(32),";
	$sSQL .= "affilEmail VARCHAR(128),";
	$sSQL .= "affilName VARCHAR(255),";
	$sSQL .= "affilAddress VARCHAR(255),";
	$sSQL .= "affilCity VARCHAR(255),";
	$sSQL .= "affilState VARCHAR(255),";
	$sSQL .= "affilZip VARCHAR(255),";
	$sSQL .= "affilCountry VARCHAR(255),";
	$sSQL .= "affilInform TINYINT DEFAULT 0)";
	ect_query($sSQL) or print_sql_error();
}

printtickdiv('Checking for Affiliate Commission Column');
$columnexists=TRUE;
ect_query("SELECT affilCommision FROM affiliates") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Affiliate Commission Column');
	ect_query("ALTER TABLE affiliates ADD COLUMN affilCommision DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("UPDATE affiliates SET affilCommision=0") or print_sql_error();
}

printtickdiv('Checking for Affiliate order upgrade');
$columnexists=TRUE;
ect_query("SELECT ordAffiliate FROM orders") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding ordAffiliate column');
	ect_query("ALTER TABLE orders ADD COLUMN ordAffiliate VARCHAR(50)") or print_sql_error();
}

$columnexists=FALSE;
$result = ect_query('SHOW COLUMNS FROM products') or print_sql_error();
while($rs = ect_fetch_assoc($result)){
	if(strtolower($rs['Field'])=='pdescription' && strtolower(substr($rs['Type'], 0, 7))=='varchar')
		$columnexists=TRUE;
}
ect_free_result($result);

if($columnexists){
	printtick('Updating product description column');
	ect_query("ALTER TABLE products MODIFY pDescription TEXT NULL") or print_sql_error();
}

flush();

printtickdiv('Checking for IP address upgrade');
$columnexists=TRUE;
ect_query("SELECT ordIP FROM orders") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding IP Address column');
	ect_query("ALTER TABLE orders ADD COLUMN ordIP VARCHAR(50)") or print_sql_error();
}

printtickdiv('Checking for Multiple Shipping Method upgrade');
$columnexists=TRUE;
ect_query("SELECT pzMultiShipping FROM postalzones") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding pzMultiShipping column');
	ect_query("ALTER TABLE postalzones ADD COLUMN pzMultiShipping TINYINT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE postalzones SET pzMultiShipping=0") or print_sql_error();
}
// A previous version did not set a default value for pzMultiShipping
ect_query("ALTER TABLE postalzones MODIFY pzMultiShipping TINYINT DEFAULT 0") or print_sql_error();
ect_query("UPDATE postalzones SET pzMultiShipping=0 WHERE pzMultiShipping IS NULL") or print_sql_error();

printtickdiv('Checking for Extra Shipping Methods upgrade');
$columnexists=TRUE;
ect_query("SELECT pzMethodName1 FROM postalzones") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Extra Shipping Methods columns');
	ect_query("ALTER TABLE postalzones ADD COLUMN pzMethodName1 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE postalzones ADD COLUMN pzMethodName2 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE postalzones ADD COLUMN pzMethodName3 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE postalzones ADD COLUMN pzMethodName4 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE postalzones ADD COLUMN pzMethodName5 VARCHAR(255) NULL") or print_sql_error();
	ect_query("UPDATE postalzones SET pzMethodName1='Standard Shipping'") or print_sql_error();
	ect_query("UPDATE postalzones SET pzMethodName2='Express Shipping'") or print_sql_error();
	ect_query("ALTER TABLE zonecharges ADD COLUMN zcRate3 DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE zonecharges ADD COLUMN zcRate4 DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE zonecharges ADD COLUMN zcRate5 DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("UPDATE zonecharges SET zcRate3=0,zcRate4=0,zcRate5=0") or print_sql_error();
}

printtickdiv('Checking for Multiple Shipping Method Charges upgrade');
$columnexists=TRUE;
ect_query("SELECT zcRate2 FROM zonecharges") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding zcRate2 column');
	ect_query("ALTER TABLE zonecharges ADD COLUMN zcRate2 DOUBLE") or print_sql_error();
	ect_query("UPDATE zonecharges SET zonecharges.zcRate2=zonecharges.zcRate") or print_sql_error();
}

$columnexists=FALSE;
$result = ect_query('SHOW COLUMNS FROM sections') or print_sql_error();
while($rs = ect_fetch_assoc($result)){
	if(strtolower($rs['Field'])=='sectiondescription' && strtolower(substr($rs['Type'], 0, 7))=='varchar')
		$columnexists=TRUE;
}
ect_free_result($result);

if($columnexists){
	printtick('Updating section description column');
	ect_query("ALTER TABLE sections MODIFY sectionDescription TEXT NULL") or print_sql_error();
}

printtickdiv('Checking for Unlimited Top Categories upgrade');
$columnexists=TRUE;
ect_query("SELECT rootSection FROM sections") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Unlimited Top Categories column');
	ect_query("ALTER TABLE sections ADD COLUMN sectionWorkingName VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE sections ADD COLUMN rootSection TINYINT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE sections SET rootSection=1") or print_sql_error();
	$result = ect_query("SELECT adminSubCats FROM admin") or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$subCats=((int)$rs['adminSubCats']==1);
	ect_free_result($result);
	if($subCats){
		$addcomma = "";
		$tslist="";
		$result = ect_query("SELECT DISTINCT topSection FROM sections") or print_sql_error();
		while($rs = ect_fetch_assoc($result)){
			$tslist = $rs["topSection"] . $addcomma . $tslist;
			$addcomma = ",";
		}
		ect_free_result($result);
		if($tslist!=""){
			$result = ect_query("SELECT tsID,tsName,tsImage,tsOrder,tsDescription FROM topsections WHERE tsID IN (" . $tslist . ")") or print_sql_error();
			while($rs = ect_fetch_assoc($result)){
				ect_query("INSERT INTO sections (sectionName,sectionImage,sectionOrder,sectionDescription,rootSection,topSection) VALUES ('".escape_string($rs["tsName"])."','".$rs["tsImage"]."',".$rs["tsOrder"].",'".escape_string($rs["tsDescription"])."',0,0)") or print_sql_error();
				$iID = ect_insert_id();
				ect_query("UPDATE sections SET rootSection=2,topSection=" . $iID . " WHERE topSection=" . $rs["tsID"] . " AND rootSection<>2") or print_sql_error();
				ect_query("UPDATE cpnassign SET cpaType=1,cpaAssignment='" . $iID . "' WHERE cpaAssignment='" . $rs["tsID"] . "' AND cpaType=0") or print_sql_error();
			}
			ect_free_result($result);
			ect_query("UPDATE sections SET rootSection=1 WHERE rootSection=2") or print_sql_error();
		}
	}
	ect_query("DELETE FROM cpnassign WHERE cpaType=0") or print_sql_error();
	ect_query("DROP TABLE topsections") or print_sql_error();
	ect_query("UPDATE sections SET sectionWorkingName=sectionName") or print_sql_error();
	ect_query("ALTER TABLE admin DROP COLUMN adminSubCats") or print_sql_error();
}

// All updates for version v4.7.0 and above below here

printtickdiv('Checking for VeriSign PayFlow Pro upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=7") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding VeriSign PayFlow Pro info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (7,'Payflow Pro','Credit Card',0,1,1,'','',7)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for Price Break upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM pricebreaks") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Price Break table');
	ect_query("CREATE TABLE pricebreaks (pbQuantity INT NOT NULL,pbProdID VARCHAR(255) NOT NULL,pPrice DOUBLE DEFAULT 0,pWholesalePrice DOUBLE DEFAULT 0,PRIMARY KEY(pbProdID,pbQuantity))");
}

printtickdiv('Checking for product dimensions upgrade');
$columnexists=TRUE;
ect_query("SELECT pDims FROM products") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding product dimensions column');
	ect_query("ALTER TABLE products ADD COLUMN pDims VARCHAR(255) NULL") or print_sql_error();
}

printtickdiv('Checking for Canada Post Methods upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT uspsID FROM uspsmethods WHERE uspsID=201") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding Canada Post Methods info');
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal,uspsFSA) VALUES (201,'1010','Regular',1,1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (202,'1020','Expedited',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (203,'1030','Xpresspost',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (204,'1040','Priority Courier',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (205,'1120','Expedited Evening',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (206,'1130','XpressPost Evening',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (207,'1220','Expedited Saturday',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (208,'1230','XpressPost Saturday',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (210,'2005','Small Packets Surface',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (211,'2010','Surface USA',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (212,'2015','Small Packets Air USA',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (213,'2020','Air USA',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (214,'2025','Expedited USA Commercial',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (215,'2030','XPressPost USA',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (216,'2040','Purolator USA',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (217,'2050','PuroPak USA',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (218,'3005','Small Packets Surface International',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (221,'3010','Parcel Surface International',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (222,'3015','Small Packets Air International',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (223,'3020','Air International',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (224,'3025','XPressPost International',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (225,'3040','Purolator International',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (226,'3050','PuroPak International',1,0)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for Email 2 upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=17") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding Email 2 info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (17,'Email 2','Email 2',0,1,0,'','',17)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for IP Deny upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM multibuyblock") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding IP Deny table');
	ect_query("CREATE TABLE multibuyblock (ssdenyid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,ssdenyip VARCHAR(255) NOT NULL,sstimesaccess INT DEFAULT 0,lastaccess DATETIME, INDEX (ssdenyip), UNIQUE (ssdenyip))") or print_sql_error();
	ect_query("CREATE TABLE ipblocking (dcid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,dcip1 INT DEFAULT 0,dcip2 INT DEFAULT 0)") or print_sql_error();
	ect_query("UPDATE sections SET sectionDisabled=127 WHERE sectionDisabled=1")  or print_sql_error();
}

checkaddcolumn("admin","adminlanguages",FALSE,"INT","","");
checkaddcolumn("admin","adminlangsettings",FALSE,"INT","","");
checkaddcolumn("countries","countryName2",FALSE,$txtcl,"(255)","countryName");
checkaddcolumn("countries","countryName3",FALSE,$txtcl,"(255)","countryName");
checkaddcolumn("optiongroup","optGrpName2",FALSE,$txtcl,"(255)","");
checkaddcolumn("optiongroup","optGrpName3",FALSE,$txtcl,"(255)","");
checkaddcolumn("options","optName2",FALSE,$txtcl,"(255)","");
checkaddcolumn("options","optName3",FALSE,$txtcl,"(255)","");
checkaddcolumn("orderstatus","statPublic2",FALSE,$txtcl,"(255)","statPublic");
checkaddcolumn("orderstatus","statPublic3",FALSE,$txtcl,"(255)","statPublic");
checkaddcolumn("payprovider","payProvShow2",FALSE,$txtcl,"(255)","payProvShow");
checkaddcolumn("payprovider","payProvShow3",FALSE,$txtcl,"(255)","payProvShow");
checkaddcolumn("products","pName2",FALSE,$txtcl,"(255)","");
checkaddcolumn("products","pName3",FALSE,$txtcl,"(255)","");
checkaddcolumn("products","pDescription2",FALSE,$memocl,"","");
checkaddcolumn("products","pDescription3",FALSE,$memocl,"","");
checkaddcolumn("products","pLongDescription2",FALSE,$memocl,"","");
checkaddcolumn("products","pLongDescription3",FALSE,$memocl,"","");
checkaddcolumn("products","pTax",FALSE,$dblcl,"","");
checkaddcolumn("sections","sectionName2",FALSE,$txtcl,"(255)","");
checkaddcolumn("sections","sectionName3",FALSE,$txtcl,"(255)","");
checkaddcolumn("sections","sectionDescription2",FALSE,$memocl,"","");
checkaddcolumn("sections","sectionDescription3",FALSE,$memocl,"","");

printtickdiv('Checking for multi language upgrade');
$columnexists=TRUE;
ect_query("SELECT cpnName FROM coupons") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding multi language columns');
	ect_query("ALTER TABLE coupons ADD COLUMN cpnName VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE coupons ADD COLUMN cpnName2 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE coupons ADD COLUMN cpnName3 VARCHAR(255) NULL") or print_sql_error();
	ect_query("UPDATE coupons SET cpnName=cpnWorkingName") or print_sql_error();

	$sSQL = "SELECT adminShipping FROM admin WHERE adminID=1";
	$result = ect_query($sSQL) or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$shipType = (int)$rs["adminShipping"];
	ect_free_result($result);
	if($shipType==3){
		// Convert lbs + Oz to lbs.Oz
		$sSQL = "SELECT pID,pWeight FROM products";
		$result = ect_query($sSQL) or print_sql_error();
		while($rs = ect_fetch_assoc($result)){
			$pWeight = $rs["pWeight"];
			$pWeight = (int)$pWeight + (($pWeight - (int)$pWeight) / 0.16);
			ect_query("UPDATE products SET pWeight=" . $pWeight . " WHERE pID='" . escape_string($rs["pID"]) . "'") or print_sql_error();
		}
		ect_free_result($result);
		$sSQL = "SELECT optID,optWeightDiff FROM options INNER JOIN optiongroup ON options.optGroup=optiongroup.optGrpID WHERE (optType=2 OR optType=-2) AND optFlags<2";
		$result = ect_query($sSQL) or print_sql_error();
		while($rs = ect_fetch_assoc($result)){
			$iPounds=(int)$rs["optWeightDiff"];
			$iOunces = $iPounds*16+round(((double)$rs["optWeightDiff"]-(double)$iPounds)*100,2);
			ect_query("UPDATE options SET optWeightDiff=".($iOunces/16.0)." WHERE optID=" . $rs["optID"]);
		}
		ect_free_result($result);
	}
}

printtickdiv('Checking for dropshipper upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM dropshipper") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding dropshipper table');
	ect_query("CREATE TABLE dropshipper (dsID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,dsName VARCHAR(255) NULL,dsEmail VARCHAR(255) NULL,dsAddress VARCHAR(255) NULL,dsCity VARCHAR(255) NULL,dsState VARCHAR(255) NULL,dsZip VARCHAR(255) NULL,dsCountry VARCHAR(255) NULL,dsAction INT DEFAULT 0)");
}

printtickdiv('Checking for pDropship upgrade');
$columnexists=TRUE;
ect_query("SELECT pDropship FROM products WHERE pID='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding pDropship column');
	ect_query("ALTER TABLE products ADD COLUMN pDropship INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE products SET pDropship=0");
}

printtickdiv('Checking for Trans ID upgrade');
$columnexists=TRUE;
ect_query("SELECT ordTransID FROM orders WHERE ordID=1") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Trans ID column');
	ect_query("ALTER TABLE orders DROP COLUMN ordDemoMode") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordTransID VARCHAR(255) NULL") or print_sql_error();
}

ect_query("DELETE FROM admin WHERE adminID<>1") or print_sql_error();

printtickdiv('Checking for discount repeat upgrade');
$columnexists=TRUE;
ect_query("SELECT cpnThresholdRepeat FROM coupons") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding discount repeat columns');
	ect_query("ALTER TABLE coupons ADD COLUMN cpnThresholdRepeat DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE coupons ADD COLUMN cpnQuantityRepeat INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE coupons SET cpnThresholdRepeat=0,cpnQuantityRepeat=0") or print_sql_error();
}
flush();

printtickdiv('Checking for option modifyer upgrade');
$columnexists=TRUE;
ect_query("SELECT optRegExp FROM options") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding option modifyer column');
	ect_query("ALTER TABLE options ADD COLUMN optRegExp VARCHAR(255) NULL") or print_sql_error();
}

printtickdiv('Checking for Address line 2 upgrade');
$columnexists=TRUE;
ect_query("SELECT ordAddress2 FROM orders") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Address line 2 columns');
	ect_query("ALTER TABLE orders ADD COLUMN ordAddress2 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordShipAddress2 VARCHAR(255) NULL") or print_sql_error();
}

printtickdiv('Checking for pay provider login level upgrade');
$columnexists=TRUE;
ect_query("SELECT payProvLevel FROM payprovider") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding pay provider login level column');
	ect_query("ALTER TABLE payprovider ADD COLUMN payProvLevel INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE payprovider SET payProvLevel=0") or print_sql_error();
}

printtickdiv('Checking for PayPal Direct Payment upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=18") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding PayPal Direct Payment info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (18,'PayPal Direct','Credit Card',0,1,0,'','',18)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for PayPal Express Payment upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=19") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding PayPal Express Payment info');
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (19,'PayPal Express','PayPal Express',0,0,0,'','',19)") or print_sql_error();
}
ect_free_result($result);

ect_query("ALTER TABLE payprovider MODIFY COLUMN payProvData2 VARCHAR(255) NULL") or print_sql_error();

printtickdiv('Checking for FedEx admin upgrade');
$columnexists=TRUE;
ect_query("SELECT FedexAccountNo FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding FedEx admin columns');
	ect_query("ALTER TABLE admin ADD COLUMN FedexAccountNo VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN FedexMeter VARCHAR(255) NULL") or print_sql_error();
}

printtickdiv('Checking for FedEx Methods upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT uspsID FROM uspsmethods WHERE uspsID=301") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding FedEx Methods info');
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (301,'PRIORITYOVERNIGHT','FedEx Priority Overnight&reg;',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (302,'STANDARDOVERNIGHT','FedEx Standard Overnight&reg;',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (303,'FIRSTOVERNIGHT','FedEx First Overnight&reg;',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (304,'FEDEX2DAY','FedEx 2Day&reg;',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (305,'FEDEXEXPRESSSAVER','FedEx Express Saver&reg;',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (306,'INTERNATIONALPRIORITY','FedEx International Priority&reg;',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (307,'INTERNATIONALECONOMY','FedEx International Economy&reg;',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (308,'INTERNATIONALFIRST','FedEx International Next Flight&reg;',1,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (310,'FEDEX1DAYFREIGHT','FedEx 1Day Freight&reg;',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (311,'FEDEX2DAYFREIGHT','FedEx 2Day Freight&reg;',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (312,'FEDEX3DAYFREIGHT','FedEx 3Day Freight&reg;',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal,uspsFSA) VALUES (313,'FEDEXGROUND','FedEx Ground&reg;',1,0,1)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (314,'GROUNDHOMEDELIVERY','FedEx Home Delivery&reg;',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (315,'INTERNATIONALPRIORITYFREIGHT','FedEx International Priority Freight&reg;',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (316,'INTERNATIONALECONOMYFREIGHT','FedEx International Economy Freight&reg;',1,0)") or print_sql_error();
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (317,'EUROPEFIRSTINTERNATIONALPRIORITY','FedEx Europe First&reg; - Int''l Priority',1,1)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for bitfield upgrades');
$columnexists=TRUE;
ect_query("SELECT pStockByOpts FROM products WHERE pID='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding bitfield columns');
	ect_query("ALTER TABLE products ADD COLUMN pStaticPage TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE products ADD COLUMN pStockByOpts TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE products SET pStaticPage=0,pStockByOpts=0") or print_sql_error();
	ect_query("UPDATE products SET pStockByOpts=1 WHERE pSell=2 OR pSell=3 OR pSell=6 OR pSell=7") or print_sql_error();
	ect_query("UPDATE products SET pStaticPage=1 WHERE pSell=4 OR pSell=5 OR pSell=6 OR pSell=7") or print_sql_error();
	ect_query("UPDATE products SET pSell=1 WHERE pSell<>0") or print_sql_error();
}

printtickdiv('Checking for Order Tracking Number upgrade');
$columnexists=TRUE;
ect_query("SELECT ordTrackNum FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Order Tracking Number column');
	ect_query("ALTER TABLE orders ADD COLUMN ordTrackNum VARCHAR(255) NULL") or print_sql_error();
}

printtickdiv('Checking for Order AVS / CVV upgrade');
$columnexists=TRUE;
ect_query("SELECT ordAVS FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Order AVS / CVV column');
	ect_query("ALTER TABLE orders ADD COLUMN ordAVS VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordCVV VARCHAR(255) NULL") or print_sql_error();
}
ect_query("UPDATE uspsmethods SET uspsMethod='FIRST CLASS' WHERE uspsID=16") or print_sql_error();

printtickdiv('Checking for international shipping upgrade');
$columnexists=TRUE;
ect_query("SELECT adminIntShipping FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding international shipping columns');
	ect_query("ALTER TABLE admin ADD COLUMN adminIntShipping INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE admin SET adminIntShipping=0") or print_sql_error();
}

ect_query("UPDATE countries SET countryCode='GB' WHERE countryID=107 OR countryID=142") or print_sql_error();
ect_query("UPDATE countries SET countryName='Great Britain' WHERE countryName='Great Britain and Northern Ireland'") or print_sql_error();

@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryFreeShip,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode) VALUES (214,'Channel Islands',0,0,0,0,3,'','GBP','GB')");
@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryFreeShip,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode) VALUES (215,'Puerto Rico',0,0,0,0,3,'','USD','PR')");
@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode) VALUES (217,'Azores',0,0,0,3,'','EUR','PT')");
@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode) VALUES (218,'Corsica',0,0,0,3,'','EUR','FR')");
@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode) VALUES (219,'Balearic Islands',0,0,0,3,'','EUR','ES')");
@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode) VALUES (220,'US Virgin Islands',0,0,0,3,'','USD','VI')");
@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode) VALUES (221,'Serbia',0,0,0,3,'','SRD','SR')");
@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode) VALUES (222,'Ivory Coast',0,0,0,3,'','XOF','CI')");
@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode) VALUES (223,'Montenegro',0,0,0,3,'','EUR','ME')");
@ect_query("INSERT INTO countries (countryID,countryName,countryEnabled,countryTax,countryOrder,countryZone,countryLCID,countryCurrency,countryCode,countryNumCurrency) VALUES (224,'American Samoa',0,0,0,3,'','USD','AS',840)");

ect_query("UPDATE countries SET countryName2=countryName WHERE countryName2='' OR countryName2 IS NULL") or print_sql_error();
ect_query("UPDATE countries SET countryName3=countryName WHERE countryName3='' OR countryName3 IS NULL") or print_sql_error();

printtickdiv('Checking for product order upgrade');
$columnexists=TRUE;
ect_query("SELECT pOrder FROM products WHERE pID='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding product order column');
	ect_query("ALTER TABLE products ADD COLUMN pOrder INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE products SET pOrder=0");
	ect_query("ALTER TABLE products ADD INDEX (pOrder)") or print_sql_error();
}

printtickdiv('Checking for recommended products upgrade');
$columnexists=TRUE;
ect_query("SELECT pRecommend FROM products WHERE pID='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding recommended products columns');
	ect_query("ALTER TABLE products ADD COLUMN pRecommend TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE products SET pRecommend=0") or print_sql_error();
}

printtickdiv('Checking for related products upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM relatedprods WHERE rpProdID='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding related products column');
	$sSQL = "CREATE TABLE relatedprods (rpProdID VARCHAR(128) NOT NULL,rpRelProdID VARCHAR(128) NOT NULL,PRIMARY KEY (rpProdID, rpRelProdID))";
	ect_query($sSQL) or print_sql_error();
}

printtickdiv('Checking for payprovider data3 upgrade');
$columnexists=TRUE;
ect_query("SELECT payProvData3 FROM payprovider WHERE payProvID=1") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding payprovider data3 column');
	ect_query("ALTER TABLE payprovider ADD COLUMN payProvData3 VARCHAR(2048) NULL") or print_sql_error();
}

printtickdiv('Checking for Percentage Shipping Methods upgrade');
$columnexists=TRUE;
ect_query("SELECT zcRatePC FROM zonecharges") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Percentage Shipping Methods columns');
	ect_query("ALTER TABLE zonecharges ADD COLUMN zcRatePC TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE zonecharges ADD COLUMN zcRatePC2 TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE zonecharges ADD COLUMN zcRatePC3 TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE zonecharges ADD COLUMN zcRatePC4 TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE zonecharges ADD COLUMN zcRatePC5 TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE zonecharges SET zcRatePC=0,zcRatePC2=0,zcRatePC3=0,zcRatePC4=0,zcRatePC5=0") or print_sql_error();
}

printtickdiv('Checking for default option upgrade');
$columnexists=TRUE;
ect_query("SELECT optDefault FROM options") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding default option column');
	ect_query("ALTER TABLE options ADD COLUMN optDefault TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE options SET optDefault=0") or print_sql_error();
}

printtickdiv('Checking for option select upgrade');
$columnexists=TRUE;
ect_query("SELECT optGrpSelect FROM optiongroup") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding option select column');
	ect_query("ALTER TABLE optiongroup ADD COLUMN optGrpSelect TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE optiongroup SET optGrpSelect=0") or print_sql_error();
	ect_query("UPDATE optiongroup SET optGrpSelect=1 WHERE optType=2") or print_sql_error();
}

printtickdiv('Checking for Order Invoice Fields upgrade');
$columnexists=TRUE;
ect_query("SELECT ordInvoice FROM orders") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Order Invoice Fields columns');
	ect_query("ALTER TABLE orders ADD COLUMN ordInvoice VARCHAR(255) NULL") or print_sql_error();
}

//printtickdiv('Checking for Google Checkout upgrade');
//$columnexists=TRUE;
//$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=20") or print_sql_error();
//if(ect_num_rows($result)==0){
//	printtick('Adding Google Checkout info');
//	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (20,'Google Checkout','Google Checkout',0,1,0,'','',20)") or print_sql_error();
//}
//ect_free_result($result);

printtickdiv('Checking for PayPal Express Available upgrade');
$sSQL = "SELECT payProvAvailable FROM payprovider WHERE payProvID=19";
$result = ect_query($sSQL) or print_sql_error();
$rs = ect_fetch_assoc($result);
ect_free_result($result);
if($rs['payProvAvailable']==0){
	$sSQL = "SELECT payProvEnabled,payProvMethod,payProvLevel,payProvData1,payProvData2,payProvData3 FROM payprovider WHERE payProvID=18";
	$result2 = ect_query($sSQL) or print_sql_error();
	$rs2 = ect_fetch_assoc($result2);
	ect_free_result($result2);
	ect_query("UPDATE payprovider SET payProvAvailable=1,payProvEnabled='" . $rs2["payProvEnabled"] . "',payProvMethod='" . $rs2["payProvMethod"] . "',payProvLevel='" . $rs2["payProvLevel"] . "',payProvData1='" . $rs2["payProvData1"] . "',payProvData2='" . $rs2["payProvData2" ] . "',payProvData3='" . $rs2["payProvData3"] . "' WHERE payProvID=19");
}

printtickdiv('Checking for Shipping Carrier upgrade');
$columnexists=TRUE;
ect_query("SELECT ordShipCarrier FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Shipping Carrier column');
	ect_query("ALTER TABLE orders ADD COLUMN ordShipCarrier INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE orders SET ordShipCarrier=0") or print_sql_error();
}

ect_query("UPDATE countries SET countryCurrency='RUB' WHERE countryID=157") or print_sql_error();

printtickdiv('Checking for Customer Login upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM customerlogin WHERE clID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Customer Login table...');
	ect_query("CREATE TABLE customerlogin (clID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,clUserName VARCHAR(255) NULL,clPW VARCHAR(255) NULL,clLoginLevel TINYINT DEFAULT 0,clActions INT DEFAULT 0,clPercentDiscount DOUBLE DEFAULT 0,clEmail VARCHAR(255) NULL,clDateCreated DATETIME)") or print_sql_error();
	ect_query($sSQL) or print_sql_error();
	
	$hasclientlogin=TRUE;
	ect_query("SELECT * FROM clientlogin WHERE clientUser='xyxyxyxyx'") or $hasclientlogin=FALSE;
	$haspercentdiscount=TRUE;
	ect_query("SELECT clientPercentDiscount FROM clientlogin WHERE clientUser='xyxyxyxyx'") or $haspercentdiscount=FALSE;
	if($hasclientlogin){
		if($haspercentdiscount)
			$sSQL = "SELECT clientUser,clientPW,clientLoginLevel,clientActions,clientPercentDiscount,clientEmail FROM clientlogin";
		else
			$sSQL = "SELECT clientUser,clientPW,clientLoginLevel,clientActions,clientEmail FROM clientlogin";
		$result = ect_query($sSQL) or print_sql_error();
		while($rs = ect_fetch_assoc($result)){
			if($haspercentdiscount) $percentdisc=$rs['clientPercentDiscount']; else $percentdisc=0;
			ect_query("INSERT INTO customerlogin (clUserName,clPW,clLoginLevel,clActions,clPercentDiscount,clEmail,clDateCreated) VALUES ('" . escape_string($rs['clientUser']) . "','" . escape_string($rs['clientPW']) . "'," . $rs['clientLoginLevel'] . "," . $rs['clientActions'] . "," . $percentdisc . ",'','" . date("Y-m-d", time()) . "')");
		}
		ect_free_result($result);
		ect_query("DROP TABLE clientlogin") or print_sql_error();
	}
}

printtickdiv('Checking for Order Table Customer Login upgrade');
$columnexists=TRUE;
ect_query("SELECT ordClientID FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Order Table Customer Login ID column');
	ect_query("ALTER TABLE orders ADD COLUMN ordClientID INT DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE cart ADD COLUMN cartClientID INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE orders SET ordClientID=0") or print_sql_error();
	ect_query("UPDATE cart SET cartClientID=0") or print_sql_error();
	
	ect_query("ALTER TABLE orders ADD INDEX (ordClientID)") or print_sql_error();
	ect_query("ALTER TABLE cart ADD INDEX (cartClientID)") or print_sql_error();
}

printtickdiv('Checking for temporary login table upgrade');
$columnexists=TRUE;
ect_query("SELECT tmploginchk FROM tmplogin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding temporary login table check column');
	ect_query("ALTER TABLE tmplogin ADD COLUMN tmploginchk INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE tmplogin SET tmploginchk=0") or print_sql_error();
}

printtickdiv('Checking for customer address table');
$columnexists=TRUE;
ect_query("SELECT * FROM address WHERE addID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding customer address table');
	ect_query("CREATE TABLE address (addID INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
		"addCustID INT DEFAULT 0," .
		"addIsDefault TINYINT DEFAULT 0," .
		"addName VARCHAR(255) NULL," .
		"addAddress VARCHAR(255) NULL," .
		"addAddress2 VARCHAR(255) NULL," .
		"addCity VARCHAR(255) NULL," .
		"addState VARCHAR(255) NULL," .
		"addZip VARCHAR(255) NULL," .
		"addCountry VARCHAR(255) NULL," .
		"addPhone VARCHAR(255) NULL," .
		"addShipFlags TINYINT DEFAULT 0," .
		"addExtra1 VARCHAR(255) NULL," .
		"addExtra2 VARCHAR(255) NULL)") or print_sql_error();
	ect_query("ALTER TABLE address ADD INDEX (addCustID)") or print_sql_error();
}

printtickdiv('Checking for Order Shipping Phone upgrade');
$columnexists=TRUE;
ect_query("SELECT ordShipPhone FROM orders") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Order Shipping Phone column');
	ect_query("ALTER TABLE orders ADD COLUMN ordShipPhone VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordShipExtra1 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordShipExtra2 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordCheckoutExtra1 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordCheckoutExtra2 VARCHAR(255) NULL") or print_sql_error();
	ect_query("UPDATE orders SET ordShipExtra1=ordExtra3"); // No error, as ordExtra3 might not exist
	ect_query("ALTER TABLE orders DROP COLUMN ordExtra3");
}

printtickdiv('Checking for admin clear cart upgrade');
$columnexists=TRUE;
ect_query("SELECT adminClearCart FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding admin clear cart column');
	ect_query("ALTER TABLE admin ADD COLUMN adminClearCart int DEFAULT 0") or print_sql_error();
	ect_query("UPDATE admin SET adminClearCart=364") or print_sql_error();
}

printtickdiv('Checking for installedmods upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM installedmods") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding installedmods table');
	ect_query("CREATE TABLE installedmods (modkey VARCHAR(255) PRIMARY KEY,modtitle VARCHAR(255) NOT NULL, modauthor VARCHAR(255) NULL, modauthorlink VARCHAR(255) NULL, modversion VARCHAR(255) NULL, modectversion VARCHAR(255) NULL, modlink VARCHAR(255) NULL, moddate DATETIME NOT NULL, modnotes TEXT NULL)") or print_sql_error();
}

printtickdiv('Checking for mailing list upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM mailinglist WHERE email='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding mailing list table');
	ect_query("CREATE TABLE mailinglist (email VARCHAR(255) PRIMARY KEY,emailFormat TINYINT DEFAULT 0)") or print_sql_error();
}

printtickdiv('Checking for new UPS Methods upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT uspsID FROM uspsmethods WHERE uspsID=30") or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding new USPS Methods info');
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (30,'Global Express Guaranteed','Global Express Guaranteed',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (31,'Global Express Guaranteed Non-Document Rectangular','Global Express Guaranteed',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (32,'Global Express Guaranteed Non-Document Non-Rectangular','Global Express Guaranteed',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (33,'Express Mail International','Express Mail International',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (34,'Express Mail International Flat Rate Envelope','Express Mail International',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (35,'Priority Mail International','Priority Mail International',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (36,'Priority Mail International Flat Rate Envelope','Priority Mail International',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (37,'Priority Mail International Regular Flat-Rate Boxes','Priority Mail International',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (38,'First-Class Mail International','First-Class Mail',1,0)");
}
ect_free_result($result);

ect_query("DELETE FROM uspsmethods WHERE uspsID>=4 AND uspsID<=13") or print_sql_error();

printtickdiv('Checking for Order Authorization Status upgrade');
$columnexists=TRUE;
ect_query("SELECT ordAuthStatus FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Order Authorization Status column');
	ect_query("ALTER TABLE orders ADD COLUMN ordAuthStatus VARCHAR(255)") or print_sql_error();
}

printtickdiv('Checking for Admin Login upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM adminlogin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Admin Login table');
	ect_query("CREATE TABLE adminlogin (adminloginid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,adminloginname VARCHAR(255) NOT NULL,adminloginpassword VARCHAR(255) NOT NULL,adminloginpermissions VARCHAR(255) NOT NULL)") or print_sql_error();
	$_SESSION['loggedon']=''; // Force relogin
}

printtickdiv('Checking for mailinglist confirmation upgrade');
$columnexists=TRUE;
ect_query("SELECT isconfirmed FROM mailinglist") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding mailinglist confirmation column');
	ect_query("ALTER TABLE mailinglist ADD COLUMN isconfirmed TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE mailinglist SET isconfirmed=1") or print_sql_error();
}

//printtickdiv('Checking for manufacturer upgrade');
//$columnexists=TRUE;
//ect_query("SELECT * FROM manufacturer") or $columnexists=FALSE;
//if($columnexists==FALSE){
//	printtick('Adding manufacturer table');
//	ect_query("CREATE TABLE manufacturer (mfID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,mfName VARCHAR(255) NULL,mfEmail VARCHAR(255) NULL,mfAddress VARCHAR(255) NULL,mfCity VARCHAR(255) NULL,mfState VARCHAR(255) NULL,mfZip VARCHAR(255) NULL,mfCountry VARCHAR(255) NULL)") or print_sql_error();
//}

printtickdiv('Checking for manufacturer column upgrade');
$columnexists=TRUE;
ect_query("SELECT pManufacturer FROM products WHERE pID='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding manufacturer column');
	ect_query("ALTER TABLE products ADD COLUMN pManufacturer INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE products SET pManufacturer=0") or print_sql_error();
}

printtickdiv('Checking for Product SKU upgrade');
$columnexists=TRUE;
ect_query("SELECT pSKU FROM products") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Product SKU column');
	ect_query("ALTER TABLE products ADD COLUMN pSKU VARCHAR(255)") or print_sql_error();
}

printtickdiv('Checking for Product Date upgrade');
$columnexists=TRUE;
ect_query("SELECT pDateAdded FROM products") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Product SKU column');
	ect_query("ALTER TABLE products ADD COLUMN pDateAdded DATE") or print_sql_error();
	ect_query("UPDATE products SET pDateAdded='" . date('Y-m-d', time()-86400) . "'") or print_sql_error();
}

printtickdiv('Checking for option alternate image upgrade');
$columnexists=TRUE;
ect_query("SELECT optAltImage FROM options") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding option alternate image column');
	ect_query("ALTER TABLE options ADD COLUMN optAltImage VARCHAR(255)") or print_sql_error();
	ect_query("ALTER TABLE options ADD COLUMN optAltLargeImage VARCHAR(255)") or print_sql_error();
}

ect_query("ALTER TABLE cart MODIFY COLUMN cartProdID VARCHAR(255)") or print_sql_error();

printtickdiv('Checking for admin secret upgrade');
$columnexists=TRUE;
ect_query("SELECT adminSecret FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding admin secret columns');
	ect_query("ALTER TABLE admin ADD COLUMN adminSecret VARCHAR(255)") or print_sql_error();
	ect_query("UPDATE admin SET adminSecret='secret text ".rand(1000000,9999999)."'") or print_sql_error();
}

printtickdiv('Checking for mailing list confirmation date upgrade');
$columnexists=TRUE;
ect_query("SELECT mlIPAddress FROM mailinglist") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding mailing list confirmation date columns');
	ect_query("ALTER TABLE mailinglist ADD COLUMN mlConfirmDate DATE") or print_sql_error();
	ect_query("ALTER TABLE mailinglist ADD COLUMN mlIPAddress VARCHAR(255)") or print_sql_error();
	ect_query("UPDATE mailinglist SET mlConfirmDate='".date('Y-m-d', time())."'") or print_sql_error();
}

printtickdiv('Checking for Ratings upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM ratings") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Ratings table');
	ect_query("CREATE TABLE ratings (rtID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,rtProdID VARCHAR(255) NOT NULL,rtRating TINYINT DEFAULT 0,rtLanguage TINYINT DEFAULT 0,rtDate DATE,rtApproved TINYINT(1) DEFAULT 0,rtIPAddress VARCHAR(255) NULL,rtPosterName VARCHAR(255),rtPosterLoginID INT DEFAULT 0,rtPosterEmail VARCHAR(255) NULL,rtHeader VARCHAR(255) NULL,rtComments TEXT NULL, INDEX (rtProdID))") or print_sql_error();
}

printtickdiv('Checking for Multiple Image upgrade');
$columnexists=TRUE;
ect_query("SELECT imageProduct FROM productimages WHERE imageProduct='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Unlimited Multiple Image table');
	// ect_query("ALTER TABLE products ADD COLUMN pImage2 VARCHAR(255) NULL") or print_sql_error();
	ect_query("CREATE TABLE productimages (imageProduct VARCHAR(128),imageSrc VARCHAR(255) NOT NULL,imageNumber INT DEFAULT 0 NOT NULL,imageType SMALLINT DEFAULT 0 NOT NULL, PRIMARY KEY(imageProduct,imageType,imageNumber))") or print_sql_error();
	for($index=1; $index<=5; $index++){
		if($index==1) $tim=''; else $tim=$index;
		ect_query("INSERT INTO productimages (imageProduct,imageSrc,imageNumber,imageType) SELECT pId,pImage" . $tim . "," . ($index-1) . ",0 FROM products WHERE pImage" . $tim . "<>'' AND pImage" . $tim . "<>'prodimages/' AND NOT (pImage" . $tim . " IS NULL)");
		ect_query("INSERT INTO productimages (imageProduct,imageSrc,imageNumber,imageType) SELECT pId,pLargeImage" . $tim . "," . ($index-1) . ",1 FROM products WHERE pLargeImage" . $tim . "<>'' AND pLargeImage" . $tim . "<>'prodimages/' AND NOT (pLargeImage" . $tim . " IS NULL)");
		ect_query("INSERT INTO productimages (imageProduct,imageSrc,imageNumber,imageType) SELECT pId,pGiantImage" . $tim . "," . ($index-1) . ",2 FROM products WHERE pGiantImage" . $tim . "<>'' AND pGiantImage" . $tim . "<>'prodimages/' AND NOT (pGiantImage" . $tim . " IS NULL)");
		ect_query("ALTER TABLE products DROP COLUMN pImage" . $tim);
		ect_query("ALTER TABLE products DROP COLUMN pLargeImage" . $tim);
		ect_query("ALTER TABLE products DROP COLUMN pGiantImage" . $tim);
	}
	ect_query("ALTER TABLE productimages ADD INDEX (imageProduct)") or print_sql_error();
	ect_query("ALTER TABLE productimages ADD INDEX (imageType)") or print_sql_error();
}

printtickdiv('Checking for shipping discount handling upgrade');
$columnexists=TRUE;
ect_query("SELECT cpnHandling FROM coupons") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding shipping discount handling column');
	ect_query("ALTER TABLE coupons ADD COLUMN cpnHandling TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE coupons SET cpnHandling=0") or print_sql_error();
}

@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (39,'14','First-Class Mail',0,0)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (40,'15','First-Class Mail',0,0)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (41,'11','Priority Mail International',0,0)");

@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (42,'16','Priority Mail International',0,0)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (43,'17','Express Mail International',0,0)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (44,'20','Priority Mail International',0,0)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (45,'24','Priority Mail International',0,0)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (46,'26','Express Mail International',0,0)");
ect_query("UPDATE uspsmethods SET uspsShowAs='Priority Mail International' WHERE uspsID IN (41,42,44,45) AND uspsShowAs='Priority Mail'");
ect_query("UPDATE uspsmethods SET uspsShowAs='Express Mail International' WHERE uspsID IN (43,46) AND uspsShowAs='Express Mail'");

printtickdiv('Checking for Gift Certificate upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM giftcertificate") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Gift Certificate table');
	ect_query("CREATE TABLE giftcertificate (gcID VARCHAR(255) PRIMARY KEY,gcTo VARCHAR(255) NULL,gcFrom VARCHAR(255) NULL,gcEmail VARCHAR(255) NULL,gcOrigAmount DOUBLE DEFAULT 0,gcRemaining DOUBLE DEFAULT 0,gcDateCreated DATE,gcDateUsed DATE,gcCartID INT DEFAULT 0 NOT NULL,gcOrderID INT DEFAULT 0 NOT NULL,gcAuthorized TINYINT(1) DEFAULT 0,gcMessage TEXT NULL)") or print_sql_error();
	ect_query("ALTER TABLE giftcertificate ADD INDEX (gcCartID)") or print_sql_error();
	ect_query("ALTER TABLE giftcertificate ADD INDEX (gcOrderID)") or print_sql_error();
}

printtickdiv('Checking for Gift Cert Applied upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM giftcertsapplied") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Gift Cert Applied table');
	ect_query("CREATE TABLE giftcertsapplied (gcaGCID VARCHAR(255) NOT NULL,gcaOrdID INT DEFAULT 0 NOT NULL,gcaAmount DOUBLE DEFAULT 0, PRIMARY KEY(gcaGCID,gcaOrdID))") or print_sql_error();
}

printtickdiv('Checking for Email Messages upgrade');
ect_query("SELECT emailID FROM emailmessages") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Email Messages table');
	ect_query("CREATE TABLE emailmessages (emailID INT PRIMARY KEY,giftcertsubject VARCHAR(255) NULL,giftcertsubject2 VARCHAR(255) NULL,giftcertsubject3 VARCHAR(255) NULL, giftcertemail TEXT NULL, giftcertemail2 TEXT NULL, giftcertemail3 TEXT NULL,giftcertsendersubject VARCHAR(255) NULL,giftcertsendersubject2 VARCHAR(255) NULL,giftcertsendersubject3 VARCHAR(255) NULL,giftcertsender TEXT NULL,giftcertsender2 TEXT NULL,giftcertsender3 TEXT NULL,emailsubject VARCHAR(255) NULL,emailsubject2 VARCHAR(255) NULL,emailsubject3 VARCHAR(255) NULL,emailheaders TEXT NULL,emailheaders2 TEXT NULL,emailheaders3 TEXT NULL,dropshipsubject VARCHAR(255) NULL,dropshipsubject2 VARCHAR(255) NULL,dropshipsubject3 VARCHAR(255) NULL,dropshipheaders TEXT NULL,dropshipheaders2 TEXT NULL,dropshipheaders3 TEXT NULL,orderstatussubject VARCHAR(255) NULL,orderstatussubject2 VARCHAR(255) NULL,orderstatussubject3 VARCHAR(255) NULL,orderstatusemail TEXT NULL,orderstatusemail2 TEXT NULL,orderstatusemail3 TEXT NULL)") or print_sql_error();
	ect_query("INSERT INTO emailmessages (emailID) VALUES (1)") or print_sql_error();
	ect_query("UPDATE emailmessages SET giftcertsubject='You received a gift certificate from %fromname%'") or print_sql_error();
	ect_query("UPDATE emailmessages SET giftcertemail='Hi %toname%, %fromname% has sent you a gift certificate to the value of %value%!<br />{Your friend left the following message: %message%}<br />To redeem your gift certificate, simply pop along to our online store at:<br />%storeurl%<br />Then select the goods you require and when checking out enter the gift certificate code below:<br />%certificateid%'") or print_sql_error();
	ect_query("UPDATE emailmessages SET giftcertsendersubject='You sent a gift certificate to %toname%'") or print_sql_error();
	ect_query("UPDATE emailmessages SET giftcertsender='You sent a gift certificate to %toname%.<br />Below is a copy of the email they will receive. You may want to check it was delivered.'") or print_sql_error();
	
	ect_query("UPDATE emailmessages SET emailsubject='".escape_string('Thank you for your order')."'") or print_sql_error();
	$themessage = str_replace('<br/>', '<br />', str_replace('<br>', '<br />', @$emailheader.'%emailmessage%<br />'.@$emailfooter)) or print_sql_error();
	ect_query("UPDATE emailmessages SET emailheaders='".escape_string($themessage)."'") or print_sql_error();
	
	if(@$dropshipsubject=='') $dropshipsubject='We have received the following order';
	ect_query("UPDATE emailmessages SET dropshipsubject='".escape_string($dropshipsubject)."'") or print_sql_error();
	$themessage = str_replace('<br/>', '<br />', str_replace('<br>', '<br />', @$dropshipheader.'%emailmessage%<br />'.@$dropshipfooter));
	ect_query("UPDATE emailmessages SET dropshipheaders='".escape_string($themessage)."'") or print_sql_error();
	
	if(@$orderstatussubject=='') $orderstatussubject='Order status updated';
	ect_query("UPDATE emailmessages SET orderstatussubject='".escape_string($orderstatussubject)."'") or print_sql_error();
	
	if(@$trackingnumtext!='') $orderstatusemail=str_replace('{' . str_replace('%s', '%trackingnum%', $trackingnumtext) . '}', @$orderstatusemail, '%trackingnum%');
	$orderstatusemail = str_replace('%nl%', '<br />', str_replace('<br/>', '<br />', str_replace('<br>', '<br />', @$orderstatusemail)));
	ect_query("UPDATE emailmessages SET orderstatusemail='".escape_string($orderstatusemail)."'") or print_sql_error();
	
	ect_query("UPDATE emailmessages SET giftcertsubject3=giftcertsubject,giftcertsubject2=giftcertsubject") or print_sql_error();
	ect_query("UPDATE emailmessages SET giftcertemail3=giftcertemail,giftcertemail2=giftcertemail") or print_sql_error();
	ect_query("UPDATE emailmessages SET giftcertsendersubject3=giftcertsendersubject,giftcertsendersubject2=giftcertsendersubject") or print_sql_error();
	ect_query("UPDATE emailmessages SET giftcertsender3=giftcertsender,giftcertsender2=giftcertsender") or print_sql_error();
	ect_query("UPDATE emailmessages SET emailsubject3=emailsubject,emailsubject2=emailsubject") or print_sql_error();
	ect_query("UPDATE emailmessages SET emailheaders3=emailheaders,emailheaders2=emailheaders") or print_sql_error();
	ect_query("UPDATE emailmessages SET dropshipsubject3=dropshipsubject,dropshipsubject2=dropshipsubject") or print_sql_error();
	ect_query("UPDATE emailmessages SET dropshipheaders3=dropshipheaders,dropshipheaders2=dropshipheaders") or print_sql_error();
	ect_query("UPDATE emailmessages SET orderstatussubject3=orderstatussubject,orderstatussubject2=orderstatussubject") or print_sql_error();
	ect_query("UPDATE emailmessages SET orderstatusemail3=orderstatusemail,orderstatusemail2=orderstatusemail") or print_sql_error();
}

printtickdiv('Checking for Payment Provider Headers upgrade');
$columnexists=TRUE;
ect_query("SELECT pProvHeaders FROM payprovider") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Payment Provider Headers columns');
	ect_query("ALTER TABLE payprovider ADD COLUMN ppHandlingCharge DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE payprovider ADD COLUMN ppHandlingPercent DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE payprovider ADD COLUMN pProvHeaders TEXT NULL") or print_sql_error();
	ect_query("ALTER TABLE payprovider ADD COLUMN pProvHeaders2 TEXT NULL") or print_sql_error();
	ect_query("ALTER TABLE payprovider ADD COLUMN pProvHeaders3 TEXT NULL") or print_sql_error();
	ect_query("ALTER TABLE payprovider ADD COLUMN pProvDropShipHeaders TEXT NULL") or print_sql_error();
	ect_query("ALTER TABLE payprovider ADD COLUMN pProvDropShipHeaders2 TEXT NULL") or print_sql_error();
	ect_query("ALTER TABLE payprovider ADD COLUMN pProvDropShipHeaders3 TEXT NULL") or print_sql_error();
	for($index=1; $index<=20; $index++){
		eval('$handlingcharge = @$handlingcharge' . $index . ';$handlingchargepercent=@$handlingchargepercent' . $index . ';');
		if($handlingcharge=='') $handlingcharge=0;
		if($handlingchargepercent=='') $handlingchargepercent=0;
		eval('global $emailheaders;$emailheaders = @$emailheader' . $index . '."%emailmessage%<br />".@$emailfooter' . $index . ';');
		eval('global $dropshipheaders;$dropshipheaders = @$dropshipheader' . $index . '."%emailmessage%<br />".@$dropshipfooter' . $index . ';');
		ect_query("UPDATE payprovider SET ppHandlingCharge='" . escape_string($handlingcharge) . "',ppHandlingPercent='" . escape_string($handlingchargepercent) . "' WHERE payProvID=".$index) or print_sql_error();
		ect_query("UPDATE payprovider SET pProvHeaders='".escape_string($emailheaders)."',pProvDropShipHeaders='".escape_string($dropshipheaders)."' WHERE payProvID=".$index) or print_sql_error();
		ect_query("UPDATE payprovider SET pProvHeaders2='".escape_string($emailheaders)."',pProvDropShipHeaders2='".escape_string($dropshipheaders)."' WHERE payProvID=".$index) or print_sql_error();
		ect_query("UPDATE payprovider SET pProvHeaders3='".escape_string($emailheaders)."',pProvDropShipHeaders3='".escape_string($dropshipheaders)."' WHERE payProvID=".$index) or print_sql_error();
	}
}

printtickdiv('Checking for handling charge percentage upgrade');
$columnexists=TRUE;
ect_query("SELECT adminHandlingPercent FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding handling charge percentage column');
	if(@$handlingchargepercent=='') $handlingchargepercent=0;
	ect_query("ALTER TABLE admin ADD COLUMN adminHandlingPercent DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("UPDATE admin SET adminHandlingPercent=" . $handlingchargepercent) or print_sql_error();
}

printtickdiv('Checking for product ratings upgrade');
$columnexists=TRUE;
ect_query("SELECT pNumRatings FROM products WHERE pID='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding product ratings columns');
	ect_query("ALTER TABLE products ADD COLUMN pTotRating INT DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE products ADD COLUMN pNumRatings INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE products SET pTotRating=0,pNumRatings=0") or print_sql_error();
}

ect_query("ALTER TABLE manufacturer ADD COLUMN mfLogo VARCHAR(255) NULL");
ect_query("ALTER TABLE manufacturer ADD COLUMN mfURL VARCHAR(255) NULL");
ect_query("ALTER TABLE manufacturer ADD COLUMN mfURL2 VARCHAR(255) NULL");
ect_query("ALTER TABLE manufacturer ADD COLUMN mfURL3 VARCHAR(255) NULL");
ect_query("ALTER TABLE manufacturer ADD COLUMN mfDescription TEXT NULL");
ect_query("ALTER TABLE manufacturer ADD COLUMN mfDescription2 TEXT NULL");
ect_query("ALTER TABLE manufacturer ADD COLUMN mfDescription3 TEXT NULL");
ect_query("ALTER TABLE manufacturer ADD COLUMN mfOrder INT DEFAULT 0");
ect_query("UPDATE manufacturer SET mfOrder=0");

printtickdiv('Checking for Search Params upgrade');
$columnexists=TRUE;
ect_query("SELECT pSearchParams FROM products WHERE pID='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Search Params column');
	ect_query("ALTER TABLE products ADD COLUMN pSearchParams TEXT NULL") or print_sql_error();
}

printtickdiv('Checking for Referer upgrade');
$columnexists=TRUE;
ect_query("SELECT ordReferer FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Referer column');
	ect_query("ALTER TABLE orders ADD COLUMN ordReferer VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordQuerystr VARCHAR(255) NULL") or print_sql_error();
}

printtickdiv('Checking for Amazon Simple Pay upgrade');
$columnexists=TRUE;
$result = ect_query("SELECT payProvID FROM payprovider WHERE payProvID=21") or print_sql_error();
if(ect_num_rows($result)==0){
	ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (21,'Amazon Simple Pay','Amazon Payments',0,1,0,'','',21)") or print_sql_error();
}
ect_free_result($result);

printtickdiv('Checking for split order name upgrade');
$columnexists=TRUE;
ect_query("SELECT ordLastName FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding split order name columns');
	ect_query("ALTER TABLE orders ADD COLUMN ordLastName VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN ordShipLastName VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE address ADD COLUMN addLastName VARCHAR(255) NULL") or print_sql_error();
}

printtickdiv('Checking for drop shipper email header upgrade');
$columnexists=TRUE;
ect_query("SELECT dsEmailHeader FROM dropshipper") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding drop shipper email header column');
	ect_query("ALTER TABLE dropshipper ADD COLUMN dsEmailHeader TEXT NULL") or print_sql_error();
}

printtickdiv('Checking for Order Language upgrade');
$columnexists=TRUE;
ect_query("SELECT ordLang FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Order Language columns');
	ect_query("ALTER TABLE orders ADD COLUMN ordLang TINYINT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE orders SET ordLang=0") or print_sql_error();
}


printtickdiv('Checking for order status email upgrade');
$columnexists=TRUE;
ect_query("SELECT emailstatus FROM orderstatus") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding order status email column');
	ect_query("ALTER TABLE orderstatus ADD COLUMN emailstatus TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE orderstatus SET emailstatus=0") or print_sql_error();
	ect_query("UPDATE orderstatus SET emailstatus=1 WHERE statID>=4") or print_sql_error();
}

printtickdiv('Checking for mailinglist name upgrade');
$columnexists=TRUE;
ect_query("SELECT mlName FROM mailinglist") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding mailinglist name column');
	ect_query("ALTER TABLE mailinglist ADD COLUMN mlName VARCHAR(255) NULL") or print_sql_error();
	$sSQL = 'SELECT email,ordName FROM mailinglist INNER JOIN orders ON mailinglist.email=orders.ordEmail';
	$result = ect_query($sSQL) or print_sql_error();
	while($rs = ect_fetch_assoc($result)){
		ect_query("UPDATE mailinglist SET mlName='".escape_string(trim($rs['ordName']))."' WHERE email='".escape_string($rs['email'])."'") or print_sql_error();
	}
}

printtickdiv('Checking for Affiliate Date Column');
$columnexists=TRUE;
ect_query("SELECT affilDate FROM affiliates") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Affiliate Date Column');
	ect_query("ALTER TABLE affiliates ADD COLUMN affilDate DATE") or print_sql_error();
	ect_query("UPDATE affiliates SET affilDate='".date('Y-m-d', time()-(60*60*24*10))."'") or print_sql_error();
}

printtickdiv('Checking for Updates upgrade');
$columnexists=TRUE;
ect_query("SELECT updLastCheck FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Updates columns');
	ect_query("ALTER TABLE admin ADD COLUMN updLastCheck DATE") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN updRecommended VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN updSecurity TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN updShouldUpd TINYINT(1) DEFAULT 0") or print_sql_error();
}

ect_query("UPDATE uspsmethods SET uspsMethod='4' WHERE uspsID=30") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='6' WHERE uspsID=31") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='7' WHERE uspsID=32") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='1' WHERE uspsID=33") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='10' WHERE uspsID=34") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='2' WHERE uspsID=35") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='8' WHERE uspsID=36") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='9' WHERE uspsID=37") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='13' WHERE uspsID=38") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='14' WHERE uspsID=39") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='15' WHERE uspsID=40") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsMethod='11' WHERE uspsID=41") or print_sql_error();

printtickdiv('Checking for new receipt upgrade');
$columnexists=TRUE;
ect_query("SELECT receiptheaders FROM emailmessages") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Updates columns');
	ect_query("ALTER TABLE emailmessages ADD COLUMN receiptheaders TEXT NULL") or print_sql_error();
	ect_query("ALTER TABLE emailmessages ADD COLUMN receiptheaders2 TEXT NULL") or print_sql_error();
	ect_query("ALTER TABLE emailmessages ADD COLUMN receiptheaders3 TEXT NULL") or print_sql_error();
	$sSQL = "SELECT emailheaders,emailheaders2,emailheaders3 FROM emailmessages";
	$result = ect_query($sSQL) or print_sql_error();
	if($rs = ect_fetch_assoc($result)){
		$sSQL = "UPDATE emailmessages SET receiptheaders='".escape_string(str_replace("%emailmessage%","%messagebody%",$rs['emailheaders']))."',receiptheaders2='".escape_string(str_replace("%emailmessage%","%messagebody%",$rs['emailheaders2']))."',receiptheaders3='".escape_string(str_replace("%emailmessage%","%messagebody%",$rs['emailheaders3']))."'";
		ect_query($sSQL) or print_sql_error();
	}
	ect_free_result($result);
}

printtickdiv('Checking for UPS Negotiated Rates Column');
$columnexists=TRUE;
ect_query("SELECT adminUPSAccount FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding UPS Negotiated Rates Column');
	ect_query("ALTER TABLE admin ADD COLUMN adminUPSAccount VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN adminUPSNegotiated TINYINT DEFAULT 0") or print_sql_error();
	if(@$upsnegotiatedrates==TRUE)
		ect_query("UPDATE admin SET adminUPSAccount='".escape_string(@$upsaccountnumber)."',adminUPSUser='".escape_string(upsencode(@$upsnegotiateduser))."',adminUPSPw='".escape_string(upsencode(@$upsnegotiatedpw))."',adminUPSAccess='".escape_string(@$upsnegotiatedaccess)."',adminUPSNegotiated=1 WHERE adminID=1") or print_sql_error();
}

printtickdiv('Checking for Recently Viewed upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM recentlyviewed WHERE rvID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Recently Viewed table');
	ect_query("CREATE TABLE recentlyviewed (rvID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,rvProdID VARCHAR(255) NOT NULL,rvProdName VARCHAR(255) NOT NULL,rvProdSection INT NOT NULL DEFAULT 0,rvProdURL VARCHAR(255) NOT NULL,rvSessionID VARCHAR(255) NOT NULL,rvCustomerID INT NOT NULL DEFAULT 0, rvDate DATETIME NOT NULL)") or print_sql_error();
}

printtickdiv('Checking for Customer Lists upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM customerlists WHERE listID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Customer Lists table');
	ect_query("CREATE TABLE customerlists (listID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,listName VARCHAR(255) NOT NULL,listOwner INT NOT NULL DEFAULT 0,listAccess VARCHAR(255) NOT NULL)") or print_sql_error();
	ect_query("ALTER TABLE customerlists ADD INDEX (listOwner)") or print_sql_error();
}

printtickdiv('Checking for Customer List Cart Column');
$columnexists=TRUE;
ect_query("SELECT cartListID FROM cart WHERE cartID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Customer List Cart Column');
	ect_query("ALTER TABLE cart ADD COLUMN cartListID INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE cart SET cartListID=0") or print_sql_error();
	ect_query("ALTER TABLE cart ADD INDEX (cartListID)") or print_sql_error();
}

ect_query("UPDATE countries SET countryCurrency='EUR' WHERE countryID IN (48,118,124,171,205)") or print_sql_error();
ect_query("UPDATE countries SET countryCurrency='TRY' WHERE countryID=194") or print_sql_error();
ect_query("UPDATE countries SET countryCurrency='CLP' WHERE countryID=41") or print_sql_error();
ect_query("UPDATE countries SET countryCurrency='CRC' WHERE countryID=45") or print_sql_error();
ect_query("UPDATE countries SET countryCurrency='BGN' WHERE countryID=32") or print_sql_error();
ect_query("UPDATE countries SET countryCurrency='VEF' WHERE countryID=206") or print_sql_error();
ect_query("UPDATE countries SET countryCurrency='USD' WHERE countryID=57") or print_sql_error();

printtickdiv('Checking for ISO 4217 Column');
$columnexists=TRUE;
ect_query("SELECT countryNumCurrency FROM countries WHERE countryID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding ISO 4217 Column');
	ect_query("ALTER TABLE countries ADD COLUMN countryNumCurrency INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=0") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=784 WHERE countryCurrency='AED'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=971 WHERE countryCurrency='AFN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=008 WHERE countryCurrency='ALL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=051 WHERE countryCurrency='AMD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=532 WHERE countryCurrency='ANG'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=973 WHERE countryCurrency='AOA'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=032 WHERE countryCurrency='ARS'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=036 WHERE countryCurrency='AUD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=533 WHERE countryCurrency='AWG'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=944 WHERE countryCurrency='AZN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=977 WHERE countryCurrency='BAM'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=052 WHERE countryCurrency='BBD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=050 WHERE countryCurrency='BDT'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=975 WHERE countryCurrency='BGN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=048 WHERE countryCurrency='BHD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=108 WHERE countryCurrency='BIF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=060 WHERE countryCurrency='BMD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=096 WHERE countryCurrency='BND'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=068 WHERE countryCurrency='BOB'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=984 WHERE countryCurrency='BOV'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=986 WHERE countryCurrency='BRL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=044 WHERE countryCurrency='BSD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=064 WHERE countryCurrency='BTN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=072 WHERE countryCurrency='BWP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=974 WHERE countryCurrency='BYR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=084 WHERE countryCurrency='BZD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=124 WHERE countryCurrency='CAD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=976 WHERE countryCurrency='CDF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=947 WHERE countryCurrency='CHE'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=756 WHERE countryCurrency='CHF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=948 WHERE countryCurrency='CHW'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=990 WHERE countryCurrency='CLF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=152 WHERE countryCurrency='CLP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=156 WHERE countryCurrency='CNY'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=170 WHERE countryCurrency='COP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=970 WHERE countryCurrency='COU'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=188 WHERE countryCurrency='CRC'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=931 WHERE countryCurrency='CUC'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=192 WHERE countryCurrency='CUP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=132 WHERE countryCurrency='CVE'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=203 WHERE countryCurrency='CZK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=262 WHERE countryCurrency='DJF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=208 WHERE countryCurrency='DKK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=214 WHERE countryCurrency='DOP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=012 WHERE countryCurrency='DZD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=233 WHERE countryCurrency='EEK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=818 WHERE countryCurrency='EGP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=232 WHERE countryCurrency='ERN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=230 WHERE countryCurrency='ETB'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=978 WHERE countryCurrency='EUR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=242 WHERE countryCurrency='FJD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=238 WHERE countryCurrency='FKP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=826 WHERE countryCurrency='GBP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=981 WHERE countryCurrency='GEL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=936 WHERE countryCurrency='GHS'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=292 WHERE countryCurrency='GIP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=270 WHERE countryCurrency='GMD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=324 WHERE countryCurrency='GNF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=320 WHERE countryCurrency='GTQ'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=328 WHERE countryCurrency='GYD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=344 WHERE countryCurrency='HKD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=340 WHERE countryCurrency='HNL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=191 WHERE countryCurrency='HRK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=332 WHERE countryCurrency='HTG'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=348 WHERE countryCurrency='HUF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=360 WHERE countryCurrency='IDR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=376 WHERE countryCurrency='ILS'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=356 WHERE countryCurrency='INR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=368 WHERE countryCurrency='IQD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=364 WHERE countryCurrency='IRR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=352 WHERE countryCurrency='ISK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=388 WHERE countryCurrency='JMD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=400 WHERE countryCurrency='JOD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=392 WHERE countryCurrency='JPY'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=404 WHERE countryCurrency='KES'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=417 WHERE countryCurrency='KGS'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=116 WHERE countryCurrency='KHR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=174 WHERE countryCurrency='KMF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=408 WHERE countryCurrency='KPW'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=410 WHERE countryCurrency='KRW'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=414 WHERE countryCurrency='KWD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=136 WHERE countryCurrency='KYD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=398 WHERE countryCurrency='KZT'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=418 WHERE countryCurrency='LAK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=422 WHERE countryCurrency='LBP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=144 WHERE countryCurrency='LKR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=430 WHERE countryCurrency='LRD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=426 WHERE countryCurrency='LSL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=440 WHERE countryCurrency='LTL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=428 WHERE countryCurrency='LVL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=434 WHERE countryCurrency='LYD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=504 WHERE countryCurrency='MAD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=498 WHERE countryCurrency='MDL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=969 WHERE countryCurrency='MGA'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=807 WHERE countryCurrency='MKD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=104 WHERE countryCurrency='MMK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=496 WHERE countryCurrency='MNT'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=446 WHERE countryCurrency='MOP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=478 WHERE countryCurrency='MRO'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=480 WHERE countryCurrency='MUR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=462 WHERE countryCurrency='MVR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=454 WHERE countryCurrency='MWK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=484 WHERE countryCurrency='MXN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=979 WHERE countryCurrency='MXV'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=458 WHERE countryCurrency='MYR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=943 WHERE countryCurrency='MZN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=516 WHERE countryCurrency='NAD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=566 WHERE countryCurrency='NGN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=558 WHERE countryCurrency='NIO'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=578 WHERE countryCurrency='NOK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=524 WHERE countryCurrency='NPR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=554 WHERE countryCurrency='NZD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=512 WHERE countryCurrency='OMR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=590 WHERE countryCurrency='PAB'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=604 WHERE countryCurrency='PEN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=598 WHERE countryCurrency='PGK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=608 WHERE countryCurrency='PHP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=586 WHERE countryCurrency='PKR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=985 WHERE countryCurrency='PLN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=600 WHERE countryCurrency='PYG'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=634 WHERE countryCurrency='QAR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=946 WHERE countryCurrency='RON'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=941 WHERE countryCurrency='RSD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=643 WHERE countryCurrency='RUB'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=646 WHERE countryCurrency='RWF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=682 WHERE countryCurrency='SAR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=090 WHERE countryCurrency='SBD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=690 WHERE countryCurrency='SCR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=938 WHERE countryCurrency='SDG'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=752 WHERE countryCurrency='SEK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=702 WHERE countryCurrency='SGD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=654 WHERE countryCurrency='SHP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=694 WHERE countryCurrency='SLL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=706 WHERE countryCurrency='SOS'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=968 WHERE countryCurrency='SRD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=678 WHERE countryCurrency='STD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=760 WHERE countryCurrency='SYP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=748 WHERE countryCurrency='SZL'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=764 WHERE countryCurrency='THB'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=972 WHERE countryCurrency='TJS'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=934 WHERE countryCurrency='TMT'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=788 WHERE countryCurrency='TND'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=776 WHERE countryCurrency='TOP'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=949 WHERE countryCurrency='TRY'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=780 WHERE countryCurrency='TTD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=901 WHERE countryCurrency='TWD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=834 WHERE countryCurrency='TZS'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=980 WHERE countryCurrency='UAH'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=800 WHERE countryCurrency='UGX'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=840 WHERE countryCurrency='USD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=997 WHERE countryCurrency='USN'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=998 WHERE countryCurrency='USS'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=858 WHERE countryCurrency='UYU'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=860 WHERE countryCurrency='UZS'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=937 WHERE countryCurrency='VEF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=704 WHERE countryCurrency='VND'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=548 WHERE countryCurrency='VUV'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=882 WHERE countryCurrency='WST'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=950 WHERE countryCurrency='XAF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=961 WHERE countryCurrency='XAG'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=959 WHERE countryCurrency='XAU'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=955 WHERE countryCurrency='XBA'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=956 WHERE countryCurrency='XBB'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=957 WHERE countryCurrency='XBC'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=958 WHERE countryCurrency='XBD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=951 WHERE countryCurrency='XCD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=960 WHERE countryCurrency='XDR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=952 WHERE countryCurrency='XOF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=964 WHERE countryCurrency='XPD'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=953 WHERE countryCurrency='XPF'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=962 WHERE countryCurrency='XPT'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=886 WHERE countryCurrency='YER'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=710 WHERE countryCurrency='ZAR'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=894 WHERE countryCurrency='ZMK'") or print_sql_error();
	ect_query("UPDATE countries SET countryNumCurrency=932 WHERE countryCurrency='ZWL'") or print_sql_error();
}

printtickdiv('Checking for Cardinal Commerce authentication upgrade');
$columnexists=TRUE;
@ect_query("SELECT cardinalProcessor FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Cardinal Commerce authentication columns');
	ect_query("ALTER TABLE admin ADD COLUMN cardinalProcessor VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN cardinalMerchant VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN cardinalPwd VARCHAR(255) NULL") or print_sql_error();
}

printtickdiv('Checking for catalog root upgrade');
$columnexists=TRUE;
@ect_query("SELECT catalogRoot FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding catalog root column');
	ect_query("ALTER TABLE admin ADD COLUMN catalogRoot INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE admin SET catalogRoot=0") or print_sql_error();
}

printtickdiv('Checking for CMS Content Region upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM contentregions WHERE contentID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding CMS Content Region table');
	ect_query("CREATE TABLE contentregions (contentID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,contentName VARCHAR(255) NULL,contentX INT DEFAULT 0,contentY INT DEFAULT 0,contentData TEXT NULL,contentData2 TEXT NULL,contentData3 TEXT NULL)") or print_sql_error();
}

printtickdiv('Checking for mailinglist sent upgrade');
$columnexists=TRUE;
ect_query("SELECT emailsent FROM mailinglist") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding mailinglist sent column');
	ect_query("ALTER TABLE mailinglist ADD COLUMN emailsent TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE mailinglist SET emailsent=0") or print_sql_error();
}

ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx Priority Overnight&reg;' WHERE uspsID=301") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx Standard Overnight&reg;' WHERE uspsID=302") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx First Overnight&reg;' WHERE uspsID=303") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx 2Day&reg;' WHERE uspsID=304") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx Express Saver&reg;' WHERE uspsID=305") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx International Priority&reg;' WHERE uspsID=306") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx International Economy&reg;' WHERE uspsID=307") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx International Next Flight&reg;' WHERE uspsID=308") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx 1Day Freight&reg;' WHERE uspsID=310") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx 2Day Freight&reg;' WHERE uspsID=311") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx 3Day Freight&reg;' WHERE uspsID=312") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx Ground&reg;' WHERE uspsID=313") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx Home Delivery&reg;' WHERE uspsID=314") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx International Priority Freight&reg;' WHERE uspsID=315") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx International Economy Freight&reg;' WHERE uspsID=316") or print_sql_error();
ect_query("UPDATE uspsmethods SET uspsShowAs='FedEx Europe First&reg; - Int''l Priority' WHERE uspsID=317") or print_sql_error();

printtickdiv('Checking for Alternate Rates Admin upgrade');
$columnexists=TRUE;
ect_query("SELECT adminAltRates FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Alternate Rates Admin column');
	ect_query("ALTER TABLE admin ADD COLUMN adminAltRates INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE admin SET adminAltRates=0") or print_sql_error();
}

$yyFlatShp='Flat Rate Shipping';
$yyWghtShp='Weight Based Shipping';
$yyPriShp='Price Based Shipping';
$yyUSPS='U.S.P.S. Shipping';
$yyUPS='UPS Shipping';
$yyFedex='FedEx Shipping';
$yyCanPos='Canada Post';

printtickdiv('Checking for Alternate Rates upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM alternaterates") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Alternate Rates table');
	ect_query("CREATE TABLE alternaterates (altrateid INT PRIMARY KEY,altratename VARCHAR(255) NOT NULL,altratetext VARCHAR(255) NULL,altratetext2 VARCHAR(255) NULL,altratetext3 VARCHAR(255) NULL, usealtmethod INT DEFAULT 0, usealtmethodintl INT DEFAULT 0, altrateorder INT DEFAULT 0)") or print_sql_error();
	for($index=1; $index<=7; $index++){
		$sSQL = "INSERT INTO alternaterates (altrateid,altratename,altratetext,altratetext2,altratetext3,usealtmethod,usealtmethodintl) VALUES (";

		if($index==1) $altratetext='';
		if($index==2) $altratetext=@$alternateratesweightbased;
		if($index==3) $altratetext=@$alternateratesusps;
		if($index==4) $altratetext=@$alternateratesups;
		if($index==5) $altratetext=@$alternateratespricebased;
		if($index==6) $altratetext=@$alternateratescanadapost;
		if($index==7) $altratetext=@$alternateratesfedex;
		if($altratetext!=''){ $usealtmethod=1; ect_query("UPDATE admin SET adminAltRates=1"); } else $usealtmethod=0;

		if($index==1){ $altratename=$yyFlatShp; $altratetext=$yyFlatShp; }
		if($index==2){ $altratename=$yyWghtShp; $altratetext=(@$alternateratesweightbased!='' ? $alternateratesweightbased : $yyWghtShp); }
		if($index==3){ $altratename=$yyUSPS; $altratetext=(@$alternateratesusps!='' ? $alternateratesusps : $yyUSPS); }
		if($index==4){ $altratename=$yyUPS; $altratetext=(@$alternateratesups!='' ? $alternateratesups : $yyUPS); }
		if($index==5){ $altratename=$yyPriShp; $altratetext=(@$alternateratespricebased!='' ? $alternateratespricebased : $yyPriShp); }
		if($index==6){ $altratename=$yyCanPos; $altratetext=(@$alternateratescanadapost!='' ? $alternateratescanadapost : $yyCanPos); }
		if($index==7){ $altratename=$yyFedex; $altratetext=(@$alternateratesfedex!='' ? $alternateratesfedex : $yyFedex); }

		$sSQL .= $index . ",'" . escape_string($altratename) . "','" . escape_string($altratetext) . "','" . escape_string($altratetext) . "','" . escape_string($altratetext) . "'," . $usealtmethod . "," . $usealtmethod . ")";
		ect_query($sSQL) or print_sql_error();
	}
}

for($index=1; $index<=10; $index++){
	if($index==1) $altratename=$yyFlatShp;
	if($index==2) $altratename=$yyWghtShp;
	if($index==3) $altratename=$yyUSPS;
	if($index==4) $altratename=$yyUPS;
	if($index==5) $altratename=$yyPriShp;
	if($index==6) $altratename=$yyCanPos;
	if($index==7) $altratename=$yyFedex;
	if($index==8) $altratename='FedEx SmartPost&reg;';
	if($index==9) $altratename='DHL Shipping';
	if($index==10) $altratename='Australia Post';
	$sSQL = "UPDATE alternaterates SET altratename='" . escape_string($altratename) . "' WHERE altratename='' AND altrateid=" . $index;
	ect_query($sSQL) or print_sql_error();
}

$sSQL = "SELECT altrateid FROM alternaterates WHERE altrateid=8";
$result = ect_query($sSQL) or print_sql_error();
if(ect_num_rows($result)==0){
	$sSQL = "INSERT INTO alternaterates (altrateid,altratename,altratetext,altratetext2,altratetext3,usealtmethod,usealtmethodintl) VALUES (" .
		"8,'FedEx SmartPost&reg;','FedEx SmartPost&reg;','FedEx SmartPost&reg;','FedEx SmartPost&reg;',0,0)";
	ect_query($sSQL) or print_sql_error();
}

$sSQL = "SELECT altrateid FROM alternaterates WHERE altrateid=9";
$result = ect_query($sSQL) or print_sql_error();
if(ect_num_rows($result)==0){
	$sSQL = "INSERT INTO alternaterates (altrateid,altratename,altratetext,altratetext2,altratetext3,usealtmethod,usealtmethodintl) VALUES (" .
		"9,'DHL Shipping','DHL Shipping','DHL Shipping','DHL Shipping',0,0)";
	ect_query($sSQL) or print_sql_error();
}

$sSQL = "SELECT altrateid FROM alternaterates WHERE altrateid=10";
$result = ect_query($sSQL) or print_sql_error();
if(ect_num_rows($result)==0){
	$sSQL = "INSERT INTO alternaterates (altrateid,altratename,altratetext,altratetext2,altratetext3,usealtmethod,usealtmethodintl) VALUES (" .
		"10,'Australia Post','Australia Post','Australia Post','Australia Post',0,0)";
	ect_query($sSQL) or print_sql_error();
}

printtickdiv('Checking for FedEx SmartPost&reg; upgrade');
$columnexists=TRUE;
ect_query("SELECT smartPostHub FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding FedEx SmartPost&reg; column');
	ect_query("ALTER TABLE admin ADD COLUMN smartPostHub VARCHAR(15)") or print_sql_error();
}

printtickdiv('Checking for Shipping Options upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM shipoptions WHERE soOrderID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Shipping Options table');
	ect_query("CREATE TABLE shipoptions (soIndex INT NOT NULL DEFAULT 0,soOrderID INT NOT NULL DEFAULT 0,soMethodName VARCHAR(255) NULL,soCost DOUBLE DEFAULT 0,soFreeShip TINYINT DEFAULT 0,soShipType INT DEFAULT 0,soDeliveryTime VARCHAR(255) NULL,soDateAdded DATETIME NOT NULL, PRIMARY KEY(soIndex,soOrderID), INDEX(soDateAdded))") or print_sql_error();
}

printtickdiv('Checking for mailinglist selected upgrade');
$columnexists=TRUE;
ect_query("SELECT selected FROM mailinglist WHERE email='xyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding mailinglist selected column');
	ect_query("ALTER TABLE mailinglist ADD COLUMN selected TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("UPDATE mailinglist SET selected=0") or print_sql_error();
}

printtickdiv('Checking for discount login level upgrade');
$columnexists=TRUE;
ect_query("SELECT cpnLoginLevel FROM coupons") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding discount login level columns');
	ect_query("ALTER TABLE coupons ADD COLUMN cpnLoginLevel INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE coupons SET cpnLoginLevel=0") or print_sql_error();
}

printtickdiv('Checking for product filter upgrade');
$columnexists=TRUE;
ect_query("SELECT prodFilter FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding product filter columns');
	ect_query("ALTER TABLE admin ADD COLUMN prodFilter INT DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN prodFilterText VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN prodFilterText2 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN prodFilterText3 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN sortOrder INT DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN sortOptions INT DEFAULT 0") or print_sql_error();
	if(@$filterresults!='') $prodfilter=32; else $prodfilter=0;
	if(@$sortBy=='' || ! is_numeric(@$sortBy)) $sortBy=0;
	if(@$sortBy==0) $sortOptions=0; else $sortOptions=pow(2,($sortBy-1));
	ect_query("UPDATE admin SET sortOrder=".$sortBy.",sortOptions=".$sortOptions.",prodFilter=" . $prodfilter . ",prodFilterText='&&&&&".escape_string(str_replace("&","%26",@$filterresults))."',prodFilterText2='&&&&&".escape_string(str_replace("&","%26",@$filterresults))."',prodFilterText3='&&&&&".escape_string(str_replace("&","%26",@$filterresults))."'") or print_sql_error();
}

printtickdiv('Checking for searchcriteria table upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM searchcriteria WHERE scID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding searchcriteria table columns');
	ect_query("CREATE TABLE searchcriteria (scID INT PRIMARY KEY,scOrder INT DEFAULT 0,scGroup INT DEFAULT 0,scWorkingName VARCHAR(255) NULL,scName VARCHAR(255) NULL,scName2 VARCHAR(255) NULL,scName3 VARCHAR(255) NULL,scLogo VARCHAR(255) NULL,scURL VARCHAR(255) NULL,scURL2 VARCHAR(255) NULL,scURL3 VARCHAR(255) NULL,scEmail VARCHAR(255) NULL,scDescription TEXT NULL,scDescription2 TEXT NULL,scDescription3 TEXT NULL,scNotes TEXT NULL)") or print_sql_error();
}

checkaddcolumn('searchcriteria','scLogo',FALSE,$txtcl,'(255)','');
checkaddcolumn('searchcriteria','scURL',FALSE,$txtcl,'(255)','');
checkaddcolumn('searchcriteria','scURL2',FALSE,$txtcl,'(255)','');
checkaddcolumn('searchcriteria','scURL3',FALSE,$txtcl,'(255)','');
checkaddcolumn('searchcriteria','scEmail',FALSE,$txtcl,'(255)','');
checkaddcolumn('searchcriteria','scDescription',FALSE,$memocl,'','');
checkaddcolumn('searchcriteria','scDescription2',FALSE,$memocl,'','');
checkaddcolumn('searchcriteria','scDescription3',FALSE,$memocl,'','');
checkaddcolumn('searchcriteria','scNotes',FALSE,$memocl,'','');

//printtickdiv('Checking for Search Criteria upgrade');
//$columnexists=TRUE;
//ect_query("SELECT pSearchCriteria FROM products WHERE pID='xyxyx'") or $columnexists=FALSE;
//if($columnexists==FALSE){
//	printtick('Adding Search Criteria column');
//	ect_query("ALTER TABLE products ADD COLUMN pSearchCriteria INT DEFAULT 0") or print_sql_error();
//	ect_query("UPDATE products SET pSearchCriteria=0") or print_sql_error();
//}

if(TRUE){ // Hash passwords
	$sSQL = "SELECT adminPassword FROM admin WHERE adminID=1";
	$result = ect_query($sSQL) or print_sql_error();
	if($rs = ect_fetch_assoc($result)){
		if(strlen($rs['adminPassword'])!=32){
			$sSQL = "UPDATE admin SET adminPassword='".escape_string(dohashpw($rs['adminPassword']))."' WHERE adminID=1";
			ect_query($sSQL) or print_sql_error();
		}
	}
	ect_free_result($result);
	$sSQL = "SELECT adminloginid,adminLoginPassword FROM adminlogin";
	$result = ect_query($sSQL) or print_sql_error();
	while($rs = ect_fetch_assoc($result)){
		if(strlen($rs['adminLoginPassword'])!=32){
			$sSQL = "UPDATE adminlogin SET adminLoginPassword='".escape_string(dohashpw($rs['adminLoginPassword']))."' WHERE adminloginid=".$rs['adminloginid'];
			ect_query($sSQL) or print_sql_error();
		}
	}
	ect_free_result($result);
	$sSQL = "SELECT affilID,affilPW FROM affiliates";
	$result = ect_query($sSQL) or print_sql_error();
	while($rs = ect_fetch_assoc($result)){
		if(strlen($rs['affilPW'])!=32){
			$sSQL = "UPDATE affiliates SET affilPW='".escape_string(dohashpw($rs['affilPW']))."' WHERE affilID='".escape_string($rs['affilID'])."'";
			ect_query($sSQL) or print_sql_error();
		}
	}
	ect_free_result($result);
	$sSQL = "SELECT clID,clPW FROM customerlogin";
	$result = ect_query($sSQL) or print_sql_error();
	while($rs = ect_fetch_assoc($result)){
		if(strlen($rs['clPW'])!=32){
			$sSQL = "UPDATE customerlogin SET clPW='".escape_string(dohashpw($rs['clPW']))."' WHERE clID=".$rs['clID'];
			ect_query($sSQL) or print_sql_error();
		}
	}
	ect_free_result($result);
}

printtickdiv('Checking for notifyinstock table upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM notifyinstock WHERE nsProdID='xyxyxyxyx'") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding notifyinstock table table');
	ect_query("CREATE TABLE notifyinstock (nsProdID VARCHAR(150) NOT NULL,nsOptID INT DEFAULT 0,nsTriggerProdID VARCHAR(255) NOT NULL,nsEmail VARCHAR(75) NOT NULL,nsDate DATETIME, PRIMARY KEY(nsTriggerProdID,nsEmail))") or print_sql_error();
}

printtickdiv('Checking for new notify back in stock email upgrade');
$columnexists=TRUE;
ect_query("SELECT notifystocksubject FROM emailmessages") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding notify back in stock email columns');
	ect_query("ALTER TABLE emailmessages ADD COLUMN notifystocksubject VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE emailmessages ADD COLUMN notifystocksubject2 VARCHAR(255) NULL") or print_sql_error();
	ect_query("ALTER TABLE emailmessages ADD COLUMN notifystocksubject3 VARCHAR(255) NULL") or print_sql_error();
	
	ect_query("ALTER TABLE emailmessages ADD COLUMN notifystockemail TEXT NULL") or print_sql_error();
	ect_query("ALTER TABLE emailmessages ADD COLUMN notifystockemail2 TEXT NULL") or print_sql_error();
	ect_query("ALTER TABLE emailmessages ADD COLUMN notifystockemail3 TEXT NULL") or print_sql_error();
	
	ect_query("UPDATE emailmessages SET notifystocksubject='" . escape_string("We now have stock for %pname%") . "'") or print_sql_error();
	ect_query("UPDATE emailmessages SET notifystockemail='" . escape_string("The product %pid% / %pname% is now back in stock.%nl%%nl%You can find this in our store at the following location:%nl%%link%%nl%%nl%Many Thanks%nl%%nl%%storeurl%%nl%") . "'") or print_sql_error();
	ect_query("UPDATE emailmessages SET notifystocksubject2=notifystocksubject,notifystocksubject3=notifystocksubject,notifystockemail2=notifystockemail,notifystockemail3=notifystockemail") or print_sql_error();
}

printtickdiv('Checking for admin login password upgrade...');
$columnexists=TRUE;
ect_query("SELECT adminLoginLastChange FROM adminlogin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding admin login password columns');
	ect_query("ALTER TABLE adminlogin ADD COLUMN adminLoginLastChange DATETIME") or print_sql_error();
	ect_query("ALTER TABLE adminlogin ADD COLUMN adminLoginLock INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE adminlogin SET adminLoginLock=0,adminLoginLastChange='".date('Y-m-d', time())."'");
}

printtickdiv('Checking for order cnum downgrade');
$columnexists=TRUE;
ect_query("SELECT ordCNum FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==TRUE){ // Note checking to see if EXISTS
	printtickdiv('Applying order cnum downgrade');
	ect_query("UPDATE orders SET ordCNum='01010101010101010101010101010101010101010101010101010101010101' WHERE ordPayProvider=10") or print_sql_error();
	ect_query("UPDATE orders SET ordCNum='10101010101010101010101010101010101010101010101010101010101010' WHERE ordPayProvider=10") or print_sql_error();
	ect_query("UPDATE orders SET ordCNum='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890' WHERE ordPayProvider=10") or print_sql_error();
}

printtickdiv('Checking for admin password upgrade...');
$columnexists=TRUE;
ect_query("SELECT adminPWLastChange FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding admin password columns');
	ect_query("ALTER TABLE admin ADD COLUMN adminPWLastChange DATETIME") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN adminUserLock INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE admin SET adminUserLock=0,adminPWLastChange='".date('Y-m-d', time())."'");
}

printtickdiv('Checking for FedEx admin upgrade...');
$columnexists=TRUE;
ect_query("SELECT FedexUserPwd FROM admin") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding FedEx admin columns');
	ect_query("ALTER TABLE admin ADD COLUMN FedexUserKey VARCHAR(50) NULL") or print_sql_error();
	ect_query("ALTER TABLE admin ADD COLUMN FedexUserPwd VARCHAR(50) NULL") or print_sql_error();
}

printtickdiv('Checking for Password History upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM passwordhistory") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Password History table');
	ect_query("CREATE TABLE passwordhistory (pwhID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,liID INT DEFAULT 0,pwhPwd VARCHAR(50) NULL,datePWChanged DATETIME)") or print_sql_error();
}

printtickdiv('Checking for Audit Log upgrade');
$columnexists=TRUE;
ect_query("SELECT * FROM auditlog WHERE logID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Audit Log table');
	ect_query("CREATE TABLE auditlog (logID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,userID VARCHAR(50),eventType VARCHAR(50),eventDate DATETIME,eventSuccess TINYINT DEFAULT 0,eventOrigin VARCHAR(50),areaAffected VARCHAR(50))") or print_sql_error();
}

$sSQL = "UPDATE payprovider SET payProvEnabled=0,payProvAvailable=0 WHERE payProvID=10";
ect_query($sSQL) or print_sql_error();

printtickdiv('Checking for Option Group upgrade');
$columnexists=TRUE;
ect_query("SELECT optTxtCharge FROM optiongroup WHERE optGrpID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Option Group columns');
	ect_query("ALTER TABLE optiongroup ADD COLUMN optTxtMaxLen INT DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE optiongroup ADD COLUMN optTxtCharge DOUBLE DEFAULT 0") or print_sql_error();
	ect_query("UPDATE optiongroup SET optTxtMaxLen=0,optTxtCharge=0") or print_sql_error();
}

printtickdiv('Checking for Option Multiplier upgrade');
$columnexists=TRUE;
ect_query("SELECT optMultiply FROM optiongroup WHERE optGrpID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Option Multiplier columns');
	ect_query("ALTER TABLE optiongroup ADD COLUMN optMultiply TINYINT(1) DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE optiongroup ADD COLUMN optAcceptChars VARCHAR(255)") or print_sql_error();
	ect_query("UPDATE optiongroup SET optMultiply=0,optAcceptChars=''") or print_sql_error();
}

printtickdiv('Checking for cartoptions weight difference upgrade');
$columnexists=TRUE;
ect_query("SELECT coMultiply FROM cartoptions WHERE coID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding cartoptions multiplier column');
	ect_query("ALTER TABLE cartoptions ADD COLUMN coMultiply TINYINT(1) NOT NULL DEFAULT 0") or print_sql_error();
}

printtickdiv('Checking for Customer Login Loyalty Points upgrade');
$columnexists=TRUE;
ect_query("SELECT loyaltyPoints FROM customerlogin WHERE clID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Customer Login Loyalty Points columns');
	ect_query("ALTER TABLE customerlogin ADD COLUMN loyaltyPoints INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE customerlogin SET loyaltyPoints=0") or print_sql_error();
}

printtickdiv('Checking for Orders Loyalty Points upgrade');
$columnexists=TRUE;
ect_query("SELECT loyaltyPoints FROM orders WHERE ordID=0") or $columnexists=FALSE;
if($columnexists==FALSE){
	printtick('Adding Orders Loyalty Points columns');
	ect_query("ALTER TABLE orders ADD COLUMN loyaltyPoints INT DEFAULT 0") or print_sql_error();
	ect_query("ALTER TABLE orders ADD COLUMN pointsRedeemed INT DEFAULT 0") or print_sql_error();
	ect_query("UPDATE orders SET loyaltyPoints=0,pointsRedeemed=0") or print_sql_error();
}

$idlist="0";
for($index=1; $index<=10; $index++){
	$sSQL='SELECT sectionID,sectionDisabled,rootSection FROM sections WHERE rootSection=0 AND topSection IN (' . $idlist . ')';
	$idlist='';
	$result=ect_query($sSQL) or print_sql_error();
	while($rs = ect_fetch_assoc($result)){
		$sSQL='UPDATE sections SET sectionDisabled=' . $rs['sectionDisabled'] . ' WHERE topSection=' . $rs['sectionID'] . ' AND sectionDisabled<' . $rs['sectionDisabled'];
		ect_query($sSQL) or print_sql_error();
		$idlist.=$rs['sectionID'].',';
	}
	ect_free_result($result);
	if($idlist!='') $idlist=substr($idlist,0,-1); else break;
}

ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (401,'SMARTPOST','FedEx SmartPost&reg;',1,1)");

checkaddcolumn('shipoptions','soFreeShipExempt',FALSE,'INT','','');
checkaddcolumn('orders','ordPrivateStatus',FALSE,$memocl,'','');
checkaddcolumn('sections','sectionHeader',FALSE,$memocl,'','');
checkaddcolumn('sections','sectionHeader2',FALSE,$memocl,'','');
checkaddcolumn('sections','sectionHeader3',FALSE,$memocl,'','');
checkaddcolumn('products','pGiftWrap',FALSE,$bitfield,'','');
checkaddcolumn('products','pBackOrder',FALSE,$bitfield,'','');
checkaddcolumn('cart','cartGiftWrap',FALSE,$bitfield,'','');
checkaddcolumn('cart','cartGiftMessage',FALSE,$memocl,'','');
checkaddcolumn('admin','adminlang',FALSE,$txtcl,'(10)','');
checkaddcolumn('admin','storelang',FALSE,$txtcl,'(10)','');

if(checkaddcolumn('countries','loadStates',FALSE,'INT','',''))
	ect_query('UPDATE countries SET loadStates=2') or print_sql_error();

$nextfreeid=1;
function addstate($stateCountryID,$stateName,$stateAbbrev){
	doaddstate($stateCountryID,$stateName,$stateAbbrev,1);
}
function adddisabledstate($stateCountryID,$stateName,$stateAbbrev){
	doaddstate($stateCountryID,$stateName,$stateAbbrev,0);
}
function doaddstate($stateCountryID,$stateName,$stateAbbrev,$stateEnabled){
	global $nextfreeid;
	$gotstateid=FALSE;
	while(! $gotstateid){
		$result = ect_query("SELECT stateID FROM states WHERE stateID=" . $nextfreeid) or print_sql_error();
		if(ect_num_rows($result)==0) $gotstateid=TRUE; else $nextfreeid++;
		ect_free_result($result);
	}
	ect_query("INSERT INTO states (stateID,stateCountryID,stateName,stateAbbrev,stateTax,stateEnabled,stateZone,stateFreeShip) VALUES (" . $nextfreeid . "," . $stateCountryID . ",'" . escape_string($stateName) . "','" . escape_string($stateAbbrev) . "',0," . $stateEnabled . ",0,0)") or print_sql_error();
}
checkaddcolumn('states','stateCountryID',FALSE,'INT','','');
$result = ect_query('SELECT stateID FROM states WHERE stateCountryID=0') or print_sql_error();
$updatestates=(ect_num_rows($result)>0);
ect_free_result($result);
if($updatestates){
	// USA
	$statelist="'AL','AK','AS','AZ','AR','CA','CO','CT','DE','DC','FM','FL','GA','GU','HI','ID','IL','IN','IA','KS','KY','LA','ME','MH','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','MP','OH','OK','OR','PW','PA','PR','RI','SC','SD','TN','TX','UT','VT','VI','VA','WA','WV','WI','WY','AE','AA','AE','AE','AE','AP'";
	$result = ect_query("SELECT COUNT(*) AS numstates FROM states WHERE stateAbbrev IN (" . $statelist . ")") or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<60){
		addstate(1,"Alabama","AL");
		addstate(1,"Alaska","AK");
		addstate(1,"American Samoa","AS");
		addstate(1,"Arizona","AZ");
		addstate(1,"Arkansas","AR");
		addstate(1,"California","CA");
		addstate(1,"Colorado","CO");
		addstate(1,"Connecticut","CT");
		addstate(1,"Delaware","DE");
		addstate(1,"District Of Columbia","DC");
		addstate(1,"Fdr. States Of Micronesia","FM");
		addstate(1,"Florida","FL");
		addstate(1,"Georgia","GA");
		addstate(1,"Guam","GU");
		addstate(1,"Hawaii","HI");
		addstate(1,"Idaho","ID");
		addstate(1,"Illinois","IL");
		addstate(1,"Indiana","IN");
		addstate(1,"Iowa","IA");
		addstate(1,"Kansas","KS");
		addstate(1,"Kentucky","KY");
		addstate(1,"Louisiana","LA");
		addstate(1,"Maine","ME");
		addstate(1,"Marshall Islands","MH");
		addstate(1,"Maryland","MD");
		addstate(1,"Massachusetts","MA");
		addstate(1,"Michigan","MI");
		addstate(1,"Minnesota","MN");
		addstate(1,"Mississippi","MS");
		addstate(1,"Missouri","MO");
		addstate(1,"Montana","MT");
		addstate(1,"Nebraska","NE");
		addstate(1,"Nevada","NV");
		addstate(1,"New Hampshire","NH");
		addstate(1,"New Jersey","NJ");
		addstate(1,"New Mexico","NM");
		addstate(1,"New York","NY");
		addstate(1,"North Carolina","NC");
		addstate(1,"North Dakota","ND");
		addstate(1,"Northern Mariana Islands","MP");
		addstate(1,"Ohio","OH");
		addstate(1,"Oklahoma","OK");
		addstate(1,"Oregon","OR");
		addstate(1,"Palau","PW");
		addstate(1,"Pennsylvania","PA");
		addstate(1,"Puerto Rico","PR");
		addstate(1,"Rhode Island","RI");
		addstate(1,"South Carolina","SC");
		addstate(1,"South Dakota","SD");
		addstate(1,"Tennessee","TN");
		addstate(1,"Texas","TX");
		addstate(1,"Utah","UT");
		addstate(1,"Vermont","VT");
		addstate(1,"Virgin Islands","VI");
		addstate(1,"Virginia","VA");
		addstate(1,"Washington","WA");
		addstate(1,"West Virginia","WV");
		addstate(1,"Wisconsin","WI");
		addstate(1,"Wyoming","WY");
		adddisabledstate(1,"Armed Forces Africa","AE");
		adddisabledstate(1,"Armed Forces Americas","AA");
		adddisabledstate(1,"Armed Forces Canada","AE");
		adddisabledstate(1,"Armed Forces Europe","AE");
		adddisabledstate(1,"Armed Forces Middle East","AE");
		adddisabledstate(1,"Armed Forces Pacific","AP");
	}else
		ect_query("UPDATE states SET stateCountryID=1 WHERE stateCountryID=0 AND stateAbbrev IN (" . $statelist . ")") or print_sql_error();
	// Canada
	$statelist="'AB','BC','MB','NB','NF','NT','NS','NU','ON','PE','QC','SK','YT'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateAbbrev IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<12){
		addstate(2,"Alberta","AB");
		addstate(2,"British Columbia","BC");
		addstate(2,"Manitoba","MB");
		addstate(2,"New Brunswick","NB");
		addstate(2,"Newfoundland","NF");
		addstate(2,"North West Territories","NT");
		addstate(2,"Nova Scotia","NS");
		addstate(2,"Nunavut","NU");
		addstate(2,"Ontario","ON");
		addstate(2,"Prince Edward Island","PE");
		addstate(2,"Quebec","QC");
		addstate(2,"Saskatchewan","SK");
		addstate(2,"Yukon Territory","YT");
	}else
		ect_query("UPDATE states SET stateCountryID=2 WHERE stateCountryID=0 AND stateAbbrev IN (" . $statelist . ")") or print_sql_error();
	// Australia
	$statelist="'ACT','NSW','NT','QLD','SA','TA','VIC','WA'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateAbbrev IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<8){
		addstate(14,"Australian Capital Territory","ACT");
		addstate(14,"New South Wales","NSW");
		addstate(14,"Northern Territory","NT");
		addstate(14,"Queensland","QLD");
		addstate(14,"South Australia","SA");
		addstate(14,"Tasmania","TA");
		addstate(14,"Victoria","VIC");
		addstate(14,"Western Australia","WA");
	}else
		ect_query("UPDATE states SET stateCountryID=14 WHERE stateCountryID=0") or print_sql_error();
	// Ireland
	$statelist="'Carlow','Cavan','Clare','Cork','Donegal','Dublin','Galway','Kerry','Kildare','Kilkenny'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<10){
		addstate(91,"Carlow","CA");
		addstate(91,"Cavan","CV");
		addstate(91,"Clare","CL");
		addstate(91,"Cork","CO");
		addstate(91,"Donegal","DO");
		addstate(91,"Dublin","DU");
		addstate(91,"Galway","GA");
		addstate(91,"Kerry","KE");
		addstate(91,"Kildare","KI");
		addstate(91,"Kilkenny","KL");
		addstate(91,"Laois","LA");
		addstate(91,"Leitrim","LE");
		addstate(91,"Limerick","LI");
		addstate(91,"Longford","LO");
		addstate(91,"Louth","LU");
		addstate(91,"Mayo","MA");
		addstate(91,"Meath","ME");
		addstate(91,"Monaghan","MO");
		addstate(91,"Offaly","OF");
		addstate(91,"Roscommon","RO");
		addstate(91,"Sligo","SL");
		addstate(91,"Tipperary","TI");
		addstate(91,"Waterford","WA");
		addstate(91,"Westmeath","WE");
		addstate(91,"Wexford","WX");
		addstate(91,"Wicklow","WI");
	}else
		ect_query("UPDATE states SET stateCountryID=91 WHERE stateCountryID=0") or print_sql_error();
	// New Zealand
	$statelist="'Southland','Westland','Waikato','Marlborough','Canterbury'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(136,"Ashburton","AS");
		addstate(136,"Auckland","AU");
		addstate(136,"Bay of Plenty","BP");
		addstate(136,"Buller","BU");
		addstate(136,"Canterbury","CB");
		addstate(136,"Carterton","CA");
		addstate(136,"Central Otago","CO");
		addstate(136,"Clutha","CL");
		addstate(136,"Counties Manukau","CM");
		addstate(136,"Dunedin City","DC");
		addstate(136,"Far North","FN");
		addstate(136,"Franklin","FR");
		addstate(136,"Gisborne","GS");
		addstate(136,"Gore","GO");
		addstate(136,"Grey","GR");
		addstate(136,"Hamilton City","HC");
		addstate(136,"Hastings","HS");
		addstate(136,"Hauraki","HI");
		addstate(136,"Hawke's Bay","HB");
		addstate(136,"Horowhenua","HW");
		addstate(136,"Hurunui","HU");
		addstate(136,"Hutt Valley","HV");
		addstate(136,"Invercargill","IC");
		addstate(136,"Kaikoura","KK");
		addstate(136,"Kaipara","KP");
		addstate(136,"Kapiti Coast","KC");
		addstate(136,"Kawerau","KW");
		addstate(136,"Manawatu","MW");
		addstate(136,"Marlborough","MB");
		addstate(136,"Masteron","MS");
		addstate(136,"Matamata Piako","MP");
		addstate(136,"New Plymouth","NP");
		addstate(136,"North Shore City","NS");
		addstate(136,"Otaki","OT");
		addstate(136,"Otorohanga","OT");
		addstate(136,"Palmerston North","PN");
		addstate(136,"Papakura","PK");
		addstate(136,"Porirua City","PC");
		addstate(136,"Queenstown Lakes","QL");
		addstate(136,"Rotorua","RT");
		addstate(136,"Ruapehu","RU");
		addstate(136,"Selwyn","SN");
		addstate(136,"South Taranaki","ST");
		addstate(136,"South Waikato","SW");
		addstate(136,"South Wairarapa","SA");
		addstate(136,"Southland","SL");
		addstate(136,"Stratford","SF");
		addstate(136,"Tasman","TM");
		addstate(136,"Taupo","TP");
		addstate(136,"Tauranga","TR");
		addstate(136,"Thames Coromandel","TC");
		addstate(136,"Timaru","TM");
		addstate(136,"Waikato","WK");
		addstate(136,"Waimakariri","WM");
		addstate(136,"Waimate","WE");
		addstate(136,"Waiora","WO");
		addstate(136,"Waipa","WP");
		addstate(136,"Waitakere","WT");
		addstate(136,"Waitaki","WI");
		addstate(136,"Waitomo","Wa");
		addstate(136,"Wellington City","WC");
		addstate(136,"Western Bay of Plenty","WB");
		addstate(136,"Westland","WL");
		addstate(136,"Whakatane","WH");
		addstate(136,"Whanganui","WG");
		addstate(136,"Whangarei","WE");
	}else
		ect_query("UPDATE states SET stateCountryID=136 WHERE stateCountryID=0") or print_sql_error();
	// South Africa
	$statelist="'Eastern Cape','Free State','Gauteng','Kwazulu-Natal','Mpumalanga'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(174,"Eastern Cape","EP");
		addstate(174,"Free State","OFS");
		addstate(174,"Gauteng","GA");
		addstate(174,"Kwazulu-Natal","KZN");
		addstate(174,"Mpumalanga","MP");
		addstate(174,"Northern Cape","NC");
		addstate(174,"Limpopo","LI");
		addstate(174,"North West Province","NWP");
		addstate(174,"Western Cape","WC");
	}else
		ect_query("UPDATE states SET stateCountryID=174 WHERE stateCountryID=0") or print_sql_error();
	// UK
	$statelist="'Aberdeenshire','Angus','Argyll','Avon','Ayrshire'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(201,"Aberdeenshire","AB");
		addstate(201,"Angus","AG");
		addstate(201,"Argyll","AR");
		addstate(201,"Avon","AV");
		addstate(201,"Ayrshire","AY");
		addstate(201,"Banffshire","BF");
		addstate(201,"Bedfordshire","Beds");
		addstate(201,"Berkshire","Berks");
		addstate(201,"Buckinghamshire","Bucks");
		addstate(201,"Caithness","CN");
		addstate(201,"Cambridgeshire","Cambs");
		addstate(201,"Ceredigion","CE");
		addstate(201,"Cheshire","CH");
		addstate(201,"Clackmannanshire","CL");
		addstate(201,"Cleveland","CV");
		addstate(201,"Clwyd","CW");
		addstate(201,"County Antrim","Co Antrim");
		addstate(201,"County Armagh","Co Armagh");
		addstate(201,"County Down","Co Down");
		addstate(201,"County Durham","Co Durham");
		addstate(201,"County Fermanagh","Co Fermanagh");
		addstate(201,"County Londonderry","Co Londonderry");
		addstate(201,"County Tyrone","Co Tyrone");
		addstate(201,"Cornwall","CO");
		addstate(201,"Cumbria","CU");
		addstate(201,"Derbyshire","DB");
		addstate(201,"Devon","DV");
		addstate(201,"Dorset","DO");
		addstate(201,"Dumfriesshire","DF");
		addstate(201,"Dunbartonshire","DU");
		addstate(201,"Dyfed","DY");
		addstate(201,"East Lothian","EL");
		addstate(201,"East Sussex","E Sussex");
		addstate(201,"Essex","EX");
		addstate(201,"Fife","FI");
		addstate(201,"Gloucestershire","Glos");
		addstate(201,"Gwent","GW");
		addstate(201,"Gwynedd","GY");
		addstate(201,"Hampshire","Hants");
		addstate(201,"Herefordshire","HE");
		addstate(201,"Hertfordshire","Herts");
		addstate(201,"Inverness-shire","IS");
		addstate(201,"Isle of Mull","IsMu");
		addstate(201,"Isle of Shetland","IsSh");
		addstate(201,"Isle of Skye","IsSk");
		addstate(201,"Isle of Wight","IsWi");
		addstate(201,"Isles of Scilly","IsSc");
		addstate(201,"Kent","KE");
		addstate(201,"Kincardineshire","KI");
		addstate(201,"Kinross-shire","KR");
		addstate(201,"Kirkudbrightshire","KK");
		addstate(201,"Lanarkshire","LK");
		addstate(201,"Lancashire","Lancs");
		addstate(201,"Leicestershire","Leics");
		addstate(201,"Lincolnshire","Lincs");
		addstate(201,"London","LO");
		addstate(201,"Merseyside","ME");
		addstate(201,"Mid Glamorgan","M Glam");
		addstate(201,"Midlothian","MI");
		addstate(201,"Middlesex","Middx");
		addstate(201,"Morayshire","MO");
		addstate(201,"Nairnshire","NA");
		addstate(201,"Norfolk","NO");
		addstate(201,"North Humberside","N Humberside");
		addstate(201,"North Yorkshire","N Yorkshire");
		addstate(201,"Northamptonshire","Northants");
		addstate(201,"Northumberland","Northd");
		addstate(201,"Nottinghamshire","Notts");
		addstate(201,"Oxfordshire","Oxon");
		addstate(201,"Peebleshire","PE");
		addstate(201,"Perthshire","PR");
		addstate(201,"Powys","PO");
		addstate(201,"Renfrewshire","RE");
		addstate(201,"Ross-shire","RO");
		addstate(201,"Roxburghshire","RX");
		addstate(201,"Selkirkshire","SK");
		addstate(201,"Shropshire","SR");
		addstate(201,"Somerset","SO");
		addstate(201,"South Glamorgan","S Glam");
		addstate(201,"South Humberside","S Humberside");
		addstate(201,"South Yorkshire","S Yorkshire");
		addstate(201,"Staffordshire","Staffs");
		addstate(201,"Stirlingshire","SS");
		addstate(201,"Suffolk","SF");
		addstate(201,"Surrey","SY");
		addstate(201,"Sutherland","SU");
		addstate(201,"Tyne and Wear","Tyne & Wear");
		addstate(201,"Warwickshire","Warks");
		addstate(201,"West Glamorgan","W Glam");
		addstate(201,"West Lothian","WL");
		addstate(201,"West Midlands","W Midlands");
		addstate(201,"West Sussex","W Sussex");
		addstate(201,"West Yorkshire","W Yorkshire");
		addstate(201,"Wigtownshire","WT");
		addstate(201,"Wiltshire","Wilts");
		addstate(201,"Worcestershire","Worcs");
		addstate(201,"East Yorkshire","EY");
		addstate(201,"Carmarthenshire","CS");
		addstate(201,"Berwickshire","BS");
		addstate(201,"Anglesey","AN");
		addstate(201,"Pembrokeshire","PK");
		addstate(201,"Flintshire","FS");
		addstate(201,"Rutland","RD");
		addstate(201,"Glamorgan","AA");

		addstate(201,"Cardiff","AA");
		addstate(201,"Bristol","AA");
		addstate(201,"Manchester","AA");
		addstate(201,"Birmingham","AA");
		addstate(201,"Glasgow","AA");
		addstate(201,"Edinburgh","AA");
		
		adddisabledstate(201,"BFPO","FO");
		adddisabledstate(201,"APO/FPO","AO");
	}else
		ect_query("UPDATE states SET stateCountryID=201 WHERE stateCountryID=0") or print_sql_error();
	// Denmark
	$statelist="'Bornholm','Falster','Fyn','Jylland','Sjaelland'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(50,"Bornholm","BH");
		addstate(50,"Falster","FA");
		addstate(50,"Fyn","FY");
		addstate(50,"Jylland","JY");
		addstate(50,"Sjaelland","SJ");
	}else
		ect_query("UPDATE states SET stateCountryID=50 WHERE stateCountryID=0") or print_sql_error();
	// France
	$statelist="'Ain','Aisne','Allier','Ardennes','Averyon'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(65,"Ain","01");
		addstate(65,"Aisne","02");
		addstate(65,"Allier","03");
		addstate(65,"Alpes de Haute Provence","04");
		addstate(65,"Hautes Alpes","05");
		addstate(65,"Alpes Maritimes","06");
		addstate(65,"Ard&egrave;che","07");
		addstate(65,"Ardennes","08");
		addstate(65,"Ari&egrave;ge","09");
		addstate(65,"Aube","10");
		addstate(65,"Aude","11");
		addstate(65,"Averyon","12");
		addstate(65,"Bouche du Rh&ocirc;ne","13");
		addstate(65,"Calvados","14");
		addstate(65,"Cantal","15");
		addstate(65,"Charente","16");
		addstate(65,"Charente Maritime","17");
		addstate(65,"Cher","18");
		addstate(65,"Corr&egrave;ze","19");
		addstate(65,"Corse du Sud","2a");
		addstate(65,"Haute Corse","2b");
		addstate(65,"C&ocirc;te d'Or","21");
		addstate(65,"C&ocirc;tes d'Armor","22");
		addstate(65,"Creuse","23");
		addstate(65,"Dordogne","24");
		addstate(65,"Doubs","25");
		addstate(65,"Dr&ocirc;me","26");
		addstate(65,"Eure","27");
		addstate(65,"Eure et Loire","28");
		addstate(65,"Finist&egrave;re","29");
		addstate(65,"Gard","30");
		addstate(65,"Haute Garonne","31");
		addstate(65,"Gers","32");
		addstate(65,"Gironde","33");
		addstate(65,"Herault","34");
		addstate(65,"Ille et Vilaine","35");
		addstate(65,"Indre","36");
		addstate(65,"Indre et Loire","37");
		addstate(65,"Is&egrave;re","38");
		addstate(65,"Jura","39");
		addstate(65,"Landes","40");
		addstate(65,"Loir et Cher","41");
		addstate(65,"Loire","42");
		addstate(65,"Haute Loire","43");
		addstate(65,"Loire Atlantique","44");
		addstate(65,"Loiret","45");
		addstate(65,"Lot","46");
		addstate(65,"Lot et Garonne","47");
		addstate(65,"Loz&egrave;re","48");
		addstate(65,"Maine et Loire","49");
		addstate(65,"Manche","50");
		addstate(65,"Marne","51");
		addstate(65,"Haute Marne","52");
		addstate(65,"Mayenne","53");
		addstate(65,"Meurthe et Moselle","54");
		addstate(65,"Meuse","55");
		addstate(65,"Morbihan","56");
		addstate(65,"Moselle","57");
		addstate(65,"Ni&egrave;vre","58");
		addstate(65,"Nord","59");
		addstate(65,"Oise","60");
		addstate(65,"Orne","61");
		addstate(65,"Pas de Calais","62");
		addstate(65,"Puy de D&ocirc;me","63");
		addstate(65,"Pyren&eacute;es Atlantiques","64");
		addstate(65,"Haute Pyren&eacute;es","65");
		addstate(65,"Pyren&eacute;es orientales","66");
		addstate(65,"Bas Rhin","67");
		addstate(65,"Haut Rhin","68");
		addstate(65,"Rh&ocirc;ne","69");
		addstate(65,"Haute Sa&ocirc;ne","70");
		addstate(65,"Sa&ocirc;ne et Loire","71");
		addstate(65,"Sarthe","72");
		addstate(65,"Savoie","73");
		addstate(65,"Haute Savoie","74");
		addstate(65,"Paris","75");
		addstate(65,"Seine Maritime","76");
		addstate(65,"Seine et Marne","77");
		addstate(65,"Yvelines","78");
		addstate(65,"Deux S&egrave;vres","79");
		addstate(65,"Somme","80");
		addstate(65,"Tarn","81");
		addstate(65,"Tarn et Garonne","82");
		addstate(65,"Var","83");
		addstate(65,"Vaucluse","84");
		addstate(65,"Vend&eacute;e","85");
		addstate(65,"Vienne","86");
		addstate(65,"Haute Vienne","87");
		addstate(65,"Vosges","88");
		addstate(65,"Yonne","89");
		addstate(65,"Territoire de Belfort","90");
		addstate(65,"Essonne","91");
		addstate(65,"Hauts de Seine","92");
		addstate(65,"Seine Saint Denis","93");
		addstate(65,"Val de Marne","94");
		addstate(65,"Val d'Oise","95");
	}else
		ect_query("UPDATE states SET stateCountryID=65 WHERE stateCountryID=0") or print_sql_error();
	// Germany
	$statelist="'Bayern','Berlin','Brandenburg','Bremen','Hamburg'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(71,"Baden-W&uuml;rttenberg","01");
		addstate(71,"Bayern","02");
		addstate(71,"Berlin","03");
		addstate(71,"Brandenburg","04");
		addstate(71,"Bremen","05");
		addstate(71,"Hamburg","06");
		addstate(71,"Hessen","07");
		addstate(71,"Mecklenburg-Vorpommern","08");
		addstate(71,"Niedersachsen","09");
		addstate(71,"Nordrhein-Westfalen","10");
		addstate(71,"Rheinland-Pfalz","11");
		addstate(71,"Saarland","12");
		addstate(71,"Sachsen","13");
		addstate(71,"Sachsen Anhalt","14");
		addstate(71,"Schleswig Holstein","15");
		addstate(71,"Th&uuml;ringen","16");
	}else
		ect_query("UPDATE states SET stateCountryID=71 WHERE stateCountryID=0") or print_sql_error();
	// Switzerland
	$statelist="'Argovia','Ginevra','Glarona','Grigioni','Lucerna','Aargau','Bern','Luzern','Neuenburg','Nidwalden'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(183,"Aargau","AG");
		addstate(183,"Appenzell Innerrhoden","AI");
		addstate(183,"Appenzell Ausserrhoden","AR");
		addstate(183,"Basel-Stadt","BS");
		addstate(183,"Basel-Landschaft","BL");
		addstate(183,"Bern","BE");
		addstate(183,"Freiburg","FR");
		addstate(183,"Genf","GE");
		addstate(183,"Glarus","GL");
		addstate(183,"Graub&uuml;nden","GR");
		addstate(183,"Jura","JU");
		addstate(183,"Luzern","LU");
		addstate(183,"Neuenburg","NE");
		addstate(183,"Nidwalden","NW");
		addstate(183,"Obwalden","OW");
		addstate(183,"Schaffhausen","SH");
		addstate(183,"Schwyz","SZ");
		addstate(183,"Solothurn","SO");
		addstate(183,"St. Gallen","SG");
		addstate(183,"Thurgau","TG");
		addstate(183,"Tessin","TI");
		addstate(183,"Uri","UR");
		addstate(183,"Wallis","VS");
		addstate(183,"Waadt","VD");
		addstate(183,"Zug","ZG");
		addstate(183,"Z&uuml;rich","ZH");
	}else
		ect_query("UPDATE states SET stateCountryID=183 WHERE stateCountryID=0") or print_sql_error();
	// Italy
	$statelist="'Abruzzo','Basilicata','Calabria','Campania','Lombardia'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(93,"Abruzzo","AL");
		addstate(93,"Basilicata","AK");
		addstate(93,"Calabria","AS");
		addstate(93,"Campania","AZ");
		addstate(93,"Emilia Romagna","AR");
		addstate(93,"Friuli Venezia Giulia","CA");
		addstate(93,"Lazio","CO");
		addstate(93,"Liguria","CT");
		addstate(93,"Lombardia","DE");
		addstate(93,"Marche","DC");
		addstate(93,"Piemonte","FM");
		addstate(93,"Puglia","FL");
		addstate(93,"Sardegna","GA");
		addstate(93,"Sicilia","GU");
		addstate(93,"Toscana","HI");
		addstate(93,"Trentino Alto Adige","ID");
		addstate(93,"Umbria","IL");
		addstate(93,"Valle d'Aosta","IN");
		addstate(93,"Veneto","IA");
	}else
		ect_query("UPDATE states SET stateCountryID=93 WHERE stateCountryID=0") or print_sql_error();
	// Portugal
	$statelist="'Aveiro','Beja','Braganca','Coimbra','Lisboa'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(153,"Aveiro","AB");
		addstate(153,"Beja","AG");
		addstate(153,"Braga","AR");
		addstate(153,"Braganca","AV");
		addstate(153,"Castelo Branco","AY");
		addstate(153,"Coimbra","BF");
		addstate(153,"Evora","BE");
		addstate(153,"Faro","BK");
		addstate(153,"Guarda","BU");
		addstate(153,"Leiria","CN");
		addstate(153,"Lisboa","CB");
		addstate(153,"Portalegre","CH");
		addstate(153,"Porto","CL");
		addstate(153,"Santarem","CV");
		addstate(153,"Setubal","CW");
		addstate(153,"Viana do Castelo","CAn");
		addstate(153,"Vila Real","CL");
		addstate(153,"Viseu","CL");
		addstate(153,"Madeira","MA");
		addstate(153,"A&ccedil;ores","AC");
	}else
		ect_query("UPDATE states SET stateCountryID=153 WHERE stateCountryID=0") or print_sql_error();
	// Spain
	$statelist="'Albacete','Alicante','Barcelona','Burgos','Cantabria'";
	$result = ect_query('SELECT COUNT(*) AS numstates FROM states WHERE stateName IN (' . $statelist . ')') or print_sql_error();
	$rs = ect_fetch_assoc($result);
	$numstates=(int)$rs['numstates'];
	ect_free_result($result);
	if($numstates<5){
		addstate(175,"Alava","VI");
		addstate(175,"Albacete","AB");
		addstate(175,"Alicante","A");
		addstate(175,"Almer&iacute;a","AL");
		addstate(175,"Asturias","O");
		addstate(175,"Avila","AV");
		addstate(175,"Badajoz","BA");
		addstate(175,"Barcelona","B");
		addstate(175,"Burgos","BU");
		addstate(175,"C&aacute;ceres","CC");
		addstate(175,"C&aacute;diz","CA");
		addstate(175,"Cantabria","S");
		addstate(175,"Castell&oacute;n","CS");
		addstate(175,"Ceuta","CE");
		addstate(175,"Ciudad Real","CR");
		addstate(175,"C&oacute;rdoba","CO");
		addstate(175,"Cuenca","CU");
		addstate(175,"Guip&uacute;zcoa","SS");
		addstate(175,"Girona","GI");
		addstate(175,"Granada","GR");
		addstate(175,"Guadalajara","GU");
		addstate(175,"Huelva","H");
		addstate(175,"Huesca","HU");
		addstate(175,"Islas Baleares","IB");
		addstate(175,"Ja&eacute;n","J");
		addstate(175,"La Coru&ntilde;a","C");
		addstate(175,"La Rioja","LO");
		addstate(175,"Las Palmas","GC");
		addstate(175,"Le&oacute;n","LE");
		addstate(175,"L&eacute;rida","LL");
		addstate(175,"Lugo","LU");
		addstate(175,"Madrid","M");
		addstate(175,"M&aacute;laga","MA");
		addstate(175,"Melilla","ML");
		addstate(175,"Murcia","MU");
		addstate(175,"Navarra","NA");
		addstate(175,"Orense","OR");
		addstate(175,"Palencia","P");
		addstate(175,"Pontevedra","PO");
		addstate(175,"Salamanca","SA");
		addstate(175,"Tenerife","TF");
		addstate(175,"Segovia","SG");
		addstate(175,"Sevilla","SE");
		addstate(175,"Soria","SO");
		addstate(175,"Tarragona","T");
		addstate(175,"Teruel","TE");
		addstate(175,"Toledo","TO");
		addstate(175,"Valencia","V");
		addstate(175,"Valladolid","VA");
		addstate(175,"Vizcaya","BI");
		addstate(175,"Zamora","ZA");
		addstate(175,"Zaragoza","Z");
	}else
		ect_query("UPDATE states SET stateCountryID=175 WHERE stateCountryID=0") or print_sql_error();
}

if(checkaddcolumn("states","stateName2",FALSE,$txtcl,"(50)",""))
	ect_query("UPDATE states SET stateName2=stateName");

if(checkaddcolumn("states","stateName3",FALSE,$txtcl,"(50)",""))
	ect_query("UPDATE states SET stateName3=stateName");
	
checkaddcolumn("admin","DHLSiteID",FALSE,$txtcl,"(50)","");
checkaddcolumn("admin","DHLSitePW",FALSE,$txtcl,"(50)","");
checkaddcolumn("admin","DHLAccountNo",FALSE,$txtcl,"(50)","");

printtickdiv("Checking for DHL Methods upgrade");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (501,'3','DHL Easy Shop',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (502,'4','DHL Jetline',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (503,'8','DHL Express Easy',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (504,'E','DHL Express 9:00',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (505,'F','DHL Freight Worldwide',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (506,'H','DHL Economy Select',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (507,'J','DHL Jumbo Box',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (508,'M','DHL Express 10:30',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (509,'P','DHL Express Worldwide',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (510,'Q','DHL Medical Express',0,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (511,'V','DHL Europack',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (512,'Y','DHL Express 12:00',1,1)");
	// Document methods
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (513,'2','DHL Easy Shop',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (514,'5','DHL Sprintline',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (515,'6','DHL Secureline',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (516,'7','DHL Express Easy',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (517,'9','DHL Europack',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (518,'B','DHL Break Bulk Express',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (519,'C','DHL Medical Express',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (520,'D','DHL Express Worldwide',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (521,'G','DHL Domestic Economy Express',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (522,'I','DHL Break Bulk Economy',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (523,'K','DHL Express 9:00',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (524,'L','DHL Express 10:30',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (525,'N','DHL Domestic Express',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (526,'R','DHL Global Mail Business',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (527,'S','DHL Same Day',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (528,'T','DHL Express 12:00',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (529,'U','DHL Express Worldwide',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (530,'W','DHL Economy Select',1,1)");
@ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (531,'X','DHL Express Envelope',1,1)");

// New Canada Post Services
ect_query("UPDATE uspsmethods SET uspsMethod='DOM.RP',uspsShowAs='Regular Parcel' WHERE uspsID=201"); // 1010','Regular'
ect_query("UPDATE uspsmethods SET uspsMethod='DOM.EP',uspsShowAs='Expedited Parcel' WHERE uspsID=202"); // 1020','Expedited'
ect_query("UPDATE uspsmethods SET uspsMethod='DOM.XP',uspsShowAs='Xpresspost' WHERE uspsID=203"); // 1030','Xpresspost'
ect_query("UPDATE uspsmethods SET uspsMethod='DOM.XP.CERT',uspsShowAs='Xpresspost Certified' WHERE uspsID=204"); // 1040','Priority Courier'
ect_query("UPDATE uspsmethods SET uspsMethod='DOM.PC',uspsShowAs='Priority' WHERE uspsID=205"); // 1120','Expedited Evening'
ect_query("UPDATE uspsmethods SET uspsMethod='DOM.LIB',uspsShowAs='Library Books' WHERE uspsID=206"); // 1130','XpressPost Evening'
ect_query("UPDATE uspsmethods SET uspsMethod='USA.EP',uspsShowAs='Expedited Parcel USA' WHERE uspsID=207"); // 1220','Expedited Saturday'
ect_query("UPDATE uspsmethods SET uspsMethod='USA.PW.ENV',uspsShowAs='Priority Worldwide Envelope USA' WHERE uspsID=208"); // 1230','XpressPost Saturday'
ect_query("UPDATE uspsmethods SET uspsMethod='USA.PW.PAK',uspsShowAs='Priority Worldwide pak USA' WHERE uspsID=210"); // 2005','Small Packets Surface'
ect_query("UPDATE uspsmethods SET uspsMethod='USA.PW.PARCEL',uspsShowAs='Priority Worldwide Parcel USA' WHERE uspsID=211"); // 2010','Surface USA'
ect_query("UPDATE uspsmethods SET uspsMethod='USA.SP.AIR',uspsShowAs='Small Packet USA Air' WHERE uspsID=212"); // 2015','Small Packets Air USA'
ect_query("UPDATE uspsmethods SET uspsMethod='USA.SP.SURF',uspsShowAs='Small Packet USA Surface' WHERE uspsID=213"); // 2020','Air USA'
ect_query("UPDATE uspsmethods SET uspsMethod='USA.XP',uspsShowAs='Xpresspost USA' WHERE uspsID=214"); // 2025','Expedited USA Commercial'
ect_query("UPDATE uspsmethods SET uspsMethod='INT.XP',uspsShowAs='Xpresspost International' WHERE uspsID=215"); // 2030','XPressPost USA'
ect_query("UPDATE uspsmethods SET uspsMethod='INT.IP.AIR',uspsShowAs='International Parcel Air' WHERE uspsID=216"); // 2040','Purolator USA'
ect_query("UPDATE uspsmethods SET uspsMethod='INT.IP.SURF',uspsShowAs='International Parcel Surface' WHERE uspsID=217"); // 2050','PuroPak USA'
ect_query("UPDATE uspsmethods SET uspsMethod='INT.PW.ENV',uspsShowAs='Priority Worldwide Envelope Int\'l' WHERE uspsID=218"); // 3005','Small Packets Surface International'
ect_query("UPDATE uspsmethods SET uspsMethod='INT.PW.PAK',uspsShowAs='Priority Worldwide pak Int\'l' WHERE uspsID=221"); // 3010','Parcel Surface International'
ect_query("UPDATE uspsmethods SET uspsMethod='INT.PW.PARCEL',uspsShowAs='Priority Worldwide parcel Int\'l' WHERE uspsID=222"); // 3015','Small Packets Air International'
ect_query("UPDATE uspsmethods SET uspsMethod='INT.SP.AIR',uspsShowAs='Small Packet International Air' WHERE uspsID=223"); // 3020','Air International'
ect_query("UPDATE uspsmethods SET uspsMethod='INT.SP.SURF',uspsShowAs='Small Packet International Surface' WHERE uspsID=224"); // 3025','XPressPost International'
//ect_query("DELETE FROM uspsmethods WHERE uspsID=225"); // 3040','Purolator International'
ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (225,'INT.TP','Tracked Packet - International',0,0)");
ect_query("UPDATE uspsmethods SET uspsMethod='INT.TP',uspsShowAs='Tracked Packet - International' WHERE uspsID=225");
ect_query("DELETE FROM uspsmethods WHERE uspsID=226"); // 3050','PuroPak International'

ect_query("ALTER TABLE cartoptions MODIFY coCartOption VARCHAR(" . $txtcollen . ")") or print_sql_error();

printtickdiv("PayPal Advanced");
@ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvShow2,payProvShow3,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (22,'PayPal Advanced','Credit Card','Credit Card','Credit Card',0,1,0,'','',22)");
printtickdiv("Stripe");
@ect_query("INSERT INTO payprovider (payProvID,payProvName,payProvShow,payProvShow2,payProvShow3,payProvEnabled,payProvAvailable,payProvDemo,payProvData1,payProvData2,payProvOrder) VALUES (23,'Stripe','Credit Card','Credit Card','Credit Card',0,1,0,'','',23)");

checkaddcolumn('cart','cartOrigProdID',FALSE,$txtcl,'(255)','');

checkaddcolumn('products','pCustom1',FALSE,$txtcl,'(2048)','');
checkaddcolumn('products','pCustom2',FALSE,$txtcl,'(2048)','');
checkaddcolumn('products','pCustom3',FALSE,$txtcl,'(2048)','');

checkaddcolumn('customerlogin','clientAdminNotes',FALSE,$memocl,'','');
checkaddcolumn('customerlogin','clientCustom1',FALSE,$txtcl,'(255)','');
checkaddcolumn('customerlogin','clientCustom2',FALSE,$txtcl,'(255)','');

checkaddcolumn("admin","adminCanPostLogin",FALSE,$txtcl,"(100)","");
if(checkaddcolumn("admin","adminCanPostPass",FALSE,$txtcl,"(100)","")&&trim(@$canadapostusername)!=''&&trim(@$canadapostpassword)!='')
	ect_query("UPDATE admin SET adminCanPostLogin='".escape_string($canadapostusername)."',adminCanPostPass='".escape_string($canadapostpassword)."'") or print_sql_error();
	
ect_query("UPDATE states SET stateName='Yorkshire' WHERE stateCountryID=201 AND stateName='East Yorkshire'") or print_sql_error();
ect_query("UPDATE states SET stateName='Durham' WHERE stateCountryID=201 AND stateName='County Durham'") or print_sql_error();
ect_query("UPDATE states SET stateName='Moray' WHERE stateCountryID=201 AND stateName='Morayshire'") or print_sql_error();
ect_query("UPDATE states SET stateName='Shetland' WHERE stateCountryID=201 AND stateName='Isle of Shetland'") or print_sql_error();
ect_query("UPDATE states SET stateName='Kirkcudbrightshire' WHERE stateCountryID=201 AND stateName='Kirkudbrightshire'") or print_sql_error();

$result2=ect_query("SELECT stateID FROM states WHERE stateCountryID=201 AND stateName='Orkney'") or print_sql_error();
if(ect_num_rows($result2)==0)addstate(201,'Orkney','ORK');
ect_free_result($result2);
$result2=ect_query("SELECT stateID FROM states WHERE stateCountryID=201 AND stateName='Denbighshire'") or print_sql_error();
if(ect_num_rows($result2)==0)addstate(201,'Denbighshire','DEN');
ect_free_result($result2);
$result2=ect_query("SELECT stateID FROM states WHERE stateCountryID=201 AND stateName='Monmouthshire'") or print_sql_error();
if(ect_num_rows($result2)==0)addstate(201,'Monmouthshire','MON');
ect_free_result($result2);
$result2=ect_query("SELECT stateID FROM states WHERE stateCountryID=201 AND stateName='Rhondda Cynon Taff'") or print_sql_error();
if(ect_num_rows($result2)==0)addstate(201,'Rhondda Cynon Taff','RON');
ect_free_result($result2);
$result2=ect_query("SELECT stateID FROM states WHERE stateCountryID=201 AND stateName='Channel Islands'") or print_sql_error();
if(ect_num_rows($result2)==0)adddisabledstate(201,'Channel Islands','CHI');
ect_free_result($result2);
$result2=ect_query("SELECT stateID FROM states WHERE stateCountryID=201 AND stateName='Isle of Man'") or print_sql_error();
if(ect_num_rows($result2)==0)adddisabledstate(201,'Isle of Man','ISM');
ect_free_result($result2);

ect_query('UPDATE payprovider SET payProvAvailable=0,payProvEnabled=0 WHERE payProvID=20') or print_sql_error();

checkaddcolumn('products','pStaticURL',FALSE,$txtcl,'(255)','');
checkaddcolumn('admin','AusPostAPI',FALSE,$txtcl,'(255)','');

printtickdiv('Checking for Australia Post Methods upgrade');
$columnexists=TRUE;
$result = ect_query('SELECT uspsID FROM uspsmethods WHERE uspsID=601') or print_sql_error();
if(ect_num_rows($result)==0){
	printtick('Adding Australia Post Shipping Methods info');
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal,uspsFSA) VALUES (601,'AUS_PARCEL_REGULAR','Parcel Post',1,1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (602,'AUS_PARCEL_REGULAR_SATCHEL_3KG','Parcel Post',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (603,'AUS_PARCEL_EXPRESS','Express Post',1,1)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (604,'AUS_PARCEL_EXPRESS_SATCHEL_3KG','Express Post',1,1)");
	
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (605,'INTL_SERVICE_ECI_PLATINUM','Express Courier International',1,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (606,'INTL_SERVICE_ECI_M','Express Courier International',1,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (607,'INTL_SERVICE_ECI_D','Express Courier International',1,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (608,'INTL_SERVICE_EPI','Express Post International',1,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (609,'INTL_SERVICE_PTI','Pack and Track International',1,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (610,'INTL_SERVICE_RPI','Registered Post International',1,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (611,'INTL_SERVICE_AIR_MAIL','Air Mail',1,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (612,'INTL_SERVICE_SEA_MAIL','Sea Mail',1,0)");
	
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (613,'INTL_SERVICE_EPI_B4','Express Post International',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (614,'INTL_SERVICE_RPI_DLE','Registered Post International',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (615,'INTL_SERVICE_RPI_B4','Registered Post International',0,0)");
	ect_query("INSERT INTO uspsmethods (uspsID,uspsMethod,uspsShowAs,uspsUseMethod,uspsLocal) VALUES (616,'INTL_SERVICE_EPI_C5','Express Post International',0,0)");
}
ect_free_result($result);

function checktableexists($tablename){
	printtickdiv('Checking for '.$tablename.' table');
	$columnexists=TRUE;
	ect_query('SELECT * FROM '.$tablename.' LIMIT 0,1') or $columnexists=FALSE;
	return($columnexists);
}

if(!checktableexists('multisearchcriteria')){
	printtick("Adding multisearchcriteria table");
	ect_query("CREATE TABLE multisearchcriteria (mSCpID VARCHAR(128) NOT NULL,mSCscID INT DEFAULT 0 NOT NULL, PRIMARY KEY(mSCpID,mSCscID))") or print_sql_error();
	$columnexists=TRUE;
	ect_query("SELECT pSearchCriteria FROM products WHERE pID='xyxyx'") or $columnexists=FALSE;
	if($columnexists){
		$result=ect_query("SELECT pID,pSearchCriteria FROM products WHERE pSearchCriteria<>0")  or print_sql_error();
		while($rs=ect_fetch_assoc($result)){
			ect_query("INSERT INTO multisearchcriteria (mSCpID,mSCscID) VALUES ('".escape_string($rs['pID'])."',".$rs['pSearchCriteria'].")") or print_sql_error();
		}
		ect_query("ALTER TABLE products DROP COLUMN pSearchCriteria") or print_sql_error();
	}
}

if(!checktableexists('searchcriteriagroup')){
	printtick("Adding searchcriteriagroup table");
	if(@$GLOBALS['manufacturerfield']=='') $GLOBALS['manufacturerfield']='Manufacturer';
	ect_query("CREATE TABLE searchcriteriagroup (scgID INT PRIMARY KEY,scgOrder INT DEFAULT 0,scgTitle VARCHAR(128) NOT NULL,scgTitle2 VARCHAR(128),scgTitle3 VARCHAR(128),scgWorkingName VARCHAR(128))") or print_sql_error();
	ect_query("INSERT INTO searchcriteriagroup (scgID,scgTitle,scgWorkingName) VALUES (0,'".escape_string($GLOBALS['manufacturerfield'])."','".escape_string($GLOBALS['manufacturerfield'])."')") or print_sql_error();
	$result=ect_query("SELECT DISTINCT sc2.scID,sc2.scName,sc2.scWorkingName FROM searchcriteria sc1 INNER JOIN searchcriteria sc2 ON sc1.scGroup=sc2.scID") or print_sql_error();
	while($rs = ect_fetch_assoc($result)){
		ect_query("INSERT INTO searchcriteriagroup (scgID,scgTitle,scgWorkingName) VALUES (".$rs['scID'].",'".escape_string($rs['scName'])."','".escape_string($rs['scWorkingName'])."')") or print_sql_error();
	}
	ect_free_result($result);
	ect_query("UPDATE searchcriteriagroup SET scgTitle2=scgTitle,scgTitle3=scgTitle") or print_sql_error();
	ect_query("ALTER TABLE searchcriteria MODIFY scID INT NOT NULL") or print_sql_error();
	if(checktableexists('manufacturer')){
		$result=ect_query("SELECT mfID,mfName,mfOrder,mfLogo,mfURL,mfURL2,mfURL3,mfEmail,mfAddress,mfCity,mfState,mfZip,mfCountry FROM manufacturer ORDER BY mfOrder") or print_sql_error();
		$uniqueindex=1;
		while($rs=ect_fetch_assoc($result)){
			$result2=ect_query("SELECT scID FROM searchcriteria WHERE scid=".$rs['mfID']) or print_sql_error();
			if(ect_num_rows($result2)>0){
				$haveuniqueindex=FALSE;
				while(! $haveuniqueindex){
					$result3=ect_query("SELECT scID FROM searchcriteria WHERE scID=".$uniqueindex) or print_sql_error();
					if(ect_num_rows($result3)==0) $haveuniqueindex=TRUE; else $uniqueindex++;
					ect_free_result($result3);
				}
				ect_query("UPDATE searchcriteria SET scID=".$uniqueindex." WHERE scid=".$rs['mfID']) or print_sql_error();
				ect_query("UPDATE multisearchcriteria SET mSCscID=".$uniqueindex." WHERE mSCscID=".$rs['mfID']);
			}
			ect_free_result($result2);
			$taddress='';
			if(trim($rs['mfAddress'])!='') $taddress.=trim($rs['mfAddress'])."\r\n";
			if(trim($rs['mfCity'])!='') $taddress.=trim($rs['mfCity'])."\r\n";
			if(trim($rs['mfState'])!='') $taddress.=trim($rs['mfState'])."\r\n";
			if(trim($rs['mfZip'])!='') $taddress.=trim($rs['mfZip'])."\r\n";
			if(trim($rs['mfCountry'])!='') $taddress.=trim($rs['mfCountry'])."\r\n";
			if(trim($rs['mfEmail'])!='') $taddress.=trim($rs['mfEmail'])."\r\n";
			ect_query("INSERT INTO searchcriteria (scID,scGroup,scOrder,scWorkingName,scName,scName2,scName3,scLogo,scURL,scURL2,scURL3,scNotes) VALUES (".$rs['mfID'].",0,".$rs['mfOrder'].",'".escape_string($rs['mfName'])."','".escape_string($rs['mfName'])."','".escape_string($rs['mfName'])."','".escape_string($rs['mfName'])."','".escape_string($rs['mfLogo'])."','".escape_string($rs['mfURL'])."','".escape_string($rs['mfURL2'])."','".escape_string($rs['mfURL3'])."','".escape_string($taddress)."')") or print_sql_error();
			$result2=ect_query("SELECT mfDescription FROM manufacturer WHERE mfID=".$rs['mfID']) or print_sql_error();
			if($rs2=ect_fetch_assoc($result2)) ect_query("UPDATE searchcriteria SET scDescription='".escape_string($rs2['mfDescription'])."' WHERE scID=".$rs['mfID']) or print_sql_error();
			ect_free_result($result2);
			$result2=ect_query("SELECT mfDescription2 FROM manufacturer WHERE mfID=".$rs['mfID']) or print_sql_error();
			if($rs2=ect_fetch_assoc($result2)) ect_query("UPDATE searchcriteria SET scDescription2='".escape_string($rs2['mfDescription2'])."' WHERE scID=".$rs['mfID']) or print_sql_error();
			ect_free_result($result2);
			$result2=ect_query("SELECT mfDescription3 FROM manufacturer WHERE mfID=".$rs['mfID']) or print_sql_error();
			if($rs2=ect_fetch_assoc($result2)) ect_query("UPDATE searchcriteria SET scDescription3='".escape_string($rs2['mfDescription3'])."' WHERE scID=".$rs['mfID']) or print_sql_error();
			ect_free_result($result2);
			$result2=ect_query("SELECT pID FROM products WHERE pManufacturer=".$rs['mfID']) or print_sql_error();
			while($rs2=ect_fetch_assoc($result2)){
				ect_query("INSERT INTO multisearchcriteria (mSCpID,mSCscID) VALUES ('".escape_string($rs2['pID'])."',".$rs['mfID'].")");
			}
			ect_free_result($result2);
		}
		ect_free_result($result);
		ect_query("DROP TABLE manufacturer") or print_sql_error();
	}
	$result=ect_query("SELECT prodFilter FROM admin WHERE adminID=1") or print_sql_error();
	$rs=ect_fetch_assoc($result);
	$prodFilter=$rs['prodFilter'];
	ect_free_result($result);
	if(($prodFilter & 1)==1) ect_query("UPDATE admin SET prodFilter=".($prodFilter | 2)) or print_sql_error();
	ect_query("UPDATE sections SET sectionName='' WHERE sectionName IS NULL") or print_sql_error();
	ect_query("UPDATE sections SET sectionName2='' WHERE sectionName2 IS NULL") or print_sql_error();
	ect_query("UPDATE sections SET sectionName3='' WHERE sectionName3 IS NULL") or print_sql_error();
	ect_query("ALTER TABLE sections MODIFY sectionID INT") or print_sql_error();
	ect_query("ALTER TABLE sections MODIFY sectionName VARCHAR(255) NOT NULL") or print_sql_error();
	ect_query("ALTER TABLE sections MODIFY sectionName2 VARCHAR(255) NOT NULL") or print_sql_error();
	ect_query("ALTER TABLE sections MODIFY sectionName3 VARCHAR(255) NOT NULL") or print_sql_error();
}

checkaddcolumn('admin','prodFilterOrder',FALSE,$txtcl,'(255)','');

checkaddcolumn('admin','sideFilter',FALSE,'INT','','');
checkaddcolumn('admin','sideFilterText',FALSE,$txtcl,'(255)','');
checkaddcolumn('admin','sideFilterText2',FALSE,$txtcl,'(255)','');
checkaddcolumn('admin','sideFilterText3',FALSE,$txtcl,'(255)','');
if(checkaddcolumn('admin','sideFilterOrder',FALSE,$txtcl,'(255)',''))
	ect_query("UPDATE admin SET sideFilter=127,sideFilterText='&Attributes&Price&Sort Order&Per Page&Filter By',sideFilterText2='&Attributes&Price&Sort Order&Per Page&Filter By',sideFilterText3='&Attributes&Price&Sort Order&Per Page&Filter By'") or print_sql_error();
checkaddcolumn('options','optDependants',FALSE,$txtcl,'(255)','');
checkaddcolumn('products','pTitle',FALSE,$txtcl,'(255)','');
checkaddcolumn('products','pMetaDesc',FALSE,$txtcl,'(255)','');
checkaddcolumn('sections','sTitle',FALSE,$txtcl,'(255)','');
checkaddcolumn('sections','sMetaDesc',FALSE,$txtcl,'(255)','');

checkaddcolumn('sections','sectionurl',TRUE,$txtcl,'(255)','');
checkaddcolumn('sections','sectionurl2',TRUE,$txtcl,'(255)','');
checkaddcolumn('sections','sectionurl3',TRUE,$txtcl,'(255)','');

ect_query("UPDATE sections SET sectionurl='' WHERE sectionurl IS NULL") or print_sql_error();
ect_query("UPDATE sections SET sectionurl2='' WHERE sectionurl2 IS NULL") or print_sql_error();
ect_query("UPDATE sections SET sectionurl3='' WHERE sectionurl3 IS NULL") or print_sql_error();

ect_query("ALTER TABLE admin MODIFY adminEmail VARCHAR(255) NOT NULL") or print_sql_error();

ect_query("ALTER TABLE sections MODIFY sectionurl VARCHAR(255) NOT NULL") or print_sql_error();
ect_query("ALTER TABLE sections MODIFY sectionurl2 VARCHAR(255) NOT NULL") or print_sql_error();
ect_query("ALTER TABLE sections MODIFY sectionurl3 VARCHAR(255) NOT NULL") or print_sql_error();

ect_query("ALTER TABLE products MODIFY pCustom1 VARCHAR(2048) NULL") or print_sql_error();
ect_query("ALTER TABLE products MODIFY pCustom2 VARCHAR(2048) NULL") or print_sql_error();
ect_query("ALTER TABLE products MODIFY pCustom3 VARCHAR(2048) NULL") or print_sql_error();

$result=ect_query("SELECT stateID FROM states WHERE stateCountryID=2 AND stateTax=9.5 AND stateAbbrev='QC'") or print_sql_error();
if(ect_num_rows($result)>0){
	printtickdiv("Updating Quebec Tax Rate to 9.975%");
	ect_query("UPDATE states SET stateTax=9.975 WHERE stateCountryID=2 AND stateTax=9.5 AND stateAbbrev='QC'") or print_sql_error();
}
ect_free_result($result);

ect_query("ALTER TABLE payprovider MODIFY payProvData1 VARCHAR(2048) NULL") or print_sql_error();
ect_query("ALTER TABLE payprovider MODIFY payProvData2 VARCHAR(2048) NULL") or print_sql_error();
ect_query("ALTER TABLE payprovider MODIFY payProvData3 VARCHAR(2048) NULL") or print_sql_error();

createanindex('cart','cartClientId');
createanindex('cart','cartCompleted');
createanindex('cart','cartDateAdded');
createanindex('cart','cartOrderID');
createanindex('cart','cartProdID');
createanindex('cart','cartSessionID');

createanindex('cartoptions','coCartID');
createanindex('cartoptions','coOptID');

createanindex('countries','countryName');

createanindex('options','optGroup');

createanindex('orders','ordClientId');
createanindex('orders','ordDate');
createanindex('orders','ordSessionID');
createanindex('orders','ordStatus');

createanindex('prodoptions','poProdID');
createanindex('prodoptions','poOptionGroup');

createanindex('products','pDateAdded');
createanindex('products','pDisplay');
createanindex('products','pManufacturer');
createanindex('products','pName');
createanindex('products','pPrice');
createanindex('products','pSection');

createanindex('recentlyviewed','rvCustomerID');
createanindex('recentlyviewed','rvDate');
createanindex('recentlyviewed','rvProdId');
createanindex('recentlyviewed','rvProdSection');
createanindex('recentlyviewed','rvSessionID');

createanindex('searchcriteria','scOrder');
createanindex('searchcriteria','scGroup');

createanindex('searchcriteriagroup','scgOrder');

createanindex('sections','sectionDisabled');
createanindex('sections','sectionOrder');
createanindex('sections','topSection');

if($haserrors){
	printtick('<font color="#FF0000"><b>Terminated but with errors</b></font>');
}else{
	ect_query("UPDATE admin SET updLastCheck='".date('Y-m-d', time()-(60*60*24*100))."',updRecommended='',updSecurity=0,updShouldUpd=0") or print_sql_error();
	printtick('Updating version number to \'Ecommerce Plus ' . $sVersion . "'");
	ect_query("UPDATE admin SET adminVersion='Ecommerce Plus " . $sVersion . "'") or print_sql_error();
	printtick('<strong>Everything updated successfully ! ! !</strong>');
	print "<script type=\"text/javascript\">iqueue.push('C');</script>\r\n";
}

$sSQL = "INSERT INTO auditlog (userID,eventType,eventDate,eventSuccess,eventOrigin,areaAffected) VALUES ('UPDATE','UPDATESTORE','" . date('Y-m-d H:i:s') . "',".($haserrors?0:1).",'UPDATER " . $sVersion . "','DBVERSION')";
ect_query($sSQL);

}elseif(@$_GET["posted"]=="2"){
	$sSQL = "SELECT adminUSZones,adminCountry,adminShipping,adminIntShipping,adminAltRates FROM admin WHERE adminID=1";
	$result=ect_query($sSQL) or print_sql_error();
	$rs=ect_fetch_assoc($result);
	$splitUSZones=((int)$rs['adminUSZones']==1);
	$countryID=$rs['adminCountry'];
	$adminIntShipping=(int)$rs['adminIntShipping'];
	$shipType=(int)$rs['adminShipping'];
	$adminAltRates=$rs['adminAltRates'];
	ect_free_result($result);
	$alternateratesweightbased=FALSE;
	if($adminAltRates>0){
		$sSQL = "SELECT altrateid FROM alternaterates WHERE (altrateid=2 OR altrateid=5) AND (usealtmethod<>0 OR usealtmethodintl<>0)";
		$result=ect_query($sSQL) or print_sql_error();
		$alternateratesweightbased=(ect_num_rows($result)>0);
		ect_free_result($result);
	}
	$editzones = (($shipType==2 || $shipType==5 || $adminIntShipping==2 || $adminIntShipping==5 || $alternateratesweightbased) && $splitUSZones);
	if($editzones){
		$sSQL="SELECT stateID,stateName,pzName FROM states LEFT JOIN postalzones ON postalzones.pzID=states.stateZone WHERE stateCountryID=".$countryID." AND pzName IS NULL";
		$result=ect_query($sSQL) or print_sql_error();
		if(ect_num_rows($result)>0){
			print '<div style="color:#FF0000;font-weight:bold">IMPORTANT, some of your states do not have a postal zone assigned.<br />You should correct this in the states admin page!!</div>';
			while($rs=ect_fetch_assoc($result)){
				print '<div style="font-weight:bold">The following state does not have a postal zone: ' . $rs['stateName'] . '</div>';
			}
		}
		ect_free_result($result);
	}
?>
<table width="100%">
<tr><td align="center" width="100%">
<table width="100%">
<tr><td width="100%">
<p style="font-size: 20px;font-family : Arial,sans-serif;font-weight : normal;padding-top: 6px;color : #2F3D6F;margin-top:0px;">The database upgrade script has completed.</p>
<p>After updating, please check our updater checklist / troubleshooting section on this page.</p>
<p><a href="http://www.ecommercetemplates.com/updater_info.asp#checklist" target="_blank">http://www.ecommercetemplates.com/updater_info.asp#checklist</a></p>
<p>Please bookmark the above page so you can refer to it if you encounter any problems.</p>
<p>Please note that database script does not copy the updated scripts to your web. This must be done separately as detailed in the instructions.</p>
<p>&nbsp;</p>
<p>Please now delete this file from your web.</p>
</td></tr>
</table>
</td></tr>
</table>
<?php
}else{
	$capturecardenabled=FALSE;
	$warncanadapostupdated=FALSE;
	$sSQL = "SELECT payProvEnabled FROM payprovider WHERE payProvID=10 AND payProvEnabled<>0";
	$result = ect_query($sSQL) or print_sql_error();
	if(ect_num_rows($result)>0) $capturecardenabled=TRUE;
	ect_free_result($result);

	$columnexists=TRUE;
	$sSQL = "SELECT adminID FROM admin WHERE adminShipping=6 OR adminIntShipping=6";
	$result = ect_query($sSQL) or $columnexists=FALSE;
	if($columnexists){
		if(ect_num_rows($result)>0) $warncanadapostupdated = TRUE;
		ect_free_result($result);
	}

	$columnexists=TRUE;
	$sSQL = "SELECT altrateid FROM alternaterates WHERE altrateid=6 AND (usealtmethod<>0 OR usealtmethodintl<>0)";
	$result = ect_query($sSQL) or $columnexists=FALSE;
	if($columnexists){
		if(ect_num_rows($result)>0) $warncanadapostupdated = TRUE;
		ect_free_result($result);
	}
	
	$columnexists=TRUE;
	ect_query("SELECT adminCanPostPass FROM admin") or $columnexists=FALSE;
	if($columnexists) $warncanadapostupdated=FALSE;
?>
<script type="text/javascript">
/* <![CDATA[ */
function checkform(frm){
<?php	if($capturecardenabled){ ?>
	if(!document.getElementById("capturecardenabled").checked){
		alert("Support for Capture Card has now been disabled due to PA-DSS and PCI requirements. Proceeding will remove Capture Card functionality from your website. Please check the box to proceed if you are in agreement.");
		return(false);
	}
<?php	}
		if($warncanadapostupdated){ ?>
	if(!document.getElementById("warncanadapostupdated").checked){
		alert("The Canada Post registration system has changed. If you are using Canada Post shipping rates you must re-register with Canada Post in the Ecommerce Plus Shipping Methods admin page. Please check the box to indicate you understand this.");
		return(false);
	}
<?php	} ?>
	return(true);
}
/* ]]> */
</script>
<form action="updatestore.php" method="post" onsubmit="return checkform(this)">
<input type="hidden" name="posted" value="1">
<table width="100%">
<tr><td width="100%">
<table width="100%">
<tr><td width="100%">
<p style="font-size: 20px;font-family : Arial,sans-serif;font-weight : normal;padding-top: 6px;color : #2F3D6F;margin-top:0px;">Version <?php print $sVersion?> Ecommerce Templates PHP Updater</p>
<p>Please note that clicking the button below will update your database to the current version. However it will not copy the updated scripts to your web. This must be done separately as detailed in the instructions.</p>
<p>Please make sure you have backed up your site and database before proceeding.</p>
<p>After performing the upgrade, please delete this file from your web.</p>
<p>&nbsp;</p>
<?php	if($capturecardenabled){ ?>
<p><input type="checkbox" id="capturecardenabled" /> Support for Capture Card has now been disabled due to PA-DSS and PCI requirements. Proceeding will remove Capture Card functionality from your website. Please check the box to proceed if you are in agreement.</p>
<?php	}
		if($warncanadapostupdated){ ?>
<p><input type="checkbox" id="warncanadapostupdated" /> The Canada Post registration system has changed. If you are using Canada Post shipping rates you must re-register with Canada Post in the Ecommerce Plus Shipping Methods admin page. Please check the box to indicate you understand this.</p>
<?php	} ?>
<p>Please click below to start your upgrade.</p>
<p><input style="background:#399908; color:#fff;cursor:pointer;padding:5px 10px;-moz-border-radius:10px;-webkit-border-radius:10px" type="submit" value="Upgrade to version <?php print $sVersion?>" /></p>
</td></tr>
</table>
</td></tr>
</table>
</form>
<?php
}
?>
</div>
</body>
</html>