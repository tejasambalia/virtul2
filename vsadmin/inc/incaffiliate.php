<?php
//This code is copyright (c) Internet Business Solutions SL, all rights reserved.
//The contents of this file are protected under law as the intellectual property of Internet
//Business Solutions SL. Any use, reproduction, disclosure or copying of any kind 
//without the express and written permission of Internet Business Solutions SL is forbidden.
//Author: Vince Reid, vince@virtualred.net
if(!@$GLOBALS['incfunctionsdefined']){print 'No incfunctions.php file';exit;}
global $pathtossl,$alreadygotadmin,$forceloginonhttps;
if(@$_SERVER['CONTENT_LENGTH']!='' && $_SERVER['CONTENT_LENGTH'] > 10000) exit;
$addsuccess = TRUE;
$success = TRUE;
$showaccount = TRUE;
if(@$pathtossl!=''){
	if(substr($pathtossl,-1)!='/') $pathtossl.='/';
}else
	$pathtossl='';
if(@$forceloginonhttps && (@$_SERVER['HTTPS']!='on' && @$_SERVER['SERVER_PORT']!='443') && strpos(@$pathtossl,'https')!==FALSE){ header('Location: '.$pathtossl.basename($_SERVER['PHP_SELF']).(@$_SERVER['QUERY_STRING']!='' ? '?'.$_SERVER['QUERY_STRING'] : '')); exit; }
$theaffilid = preg_replace('/[\W]/', '', getpost('affilid'));
if(getpost('editaction')!=''){
	if($theaffilid==''){
		$addsuccess = FALSE;
	}elseif(getpost('editaction')=="modify"){
		$sSQL = "UPDATE affiliates SET ";
		if(getpost('affilpw')!='') $sSQL.="affilPW='" . escape_string(dohashpw(getpost('affilpw'))) . "',";
		$sSQL.="affilEmail='" . escape_string(getpost('email')) . "',";
		$sSQL.="affilName='" . escape_string(getpost('name')) . "',";
		$sSQL.="affilAddress='" . escape_string(getpost('address')) . "',";
		$sSQL.="affilCity='" . escape_string(getpost('city')) . "',";
		$sSQL.="affilState='" . escape_string(getpost('state')) . "',";
		$sSQL.="affilCountry='" . escape_string(getpost('country')) . "',";
		$sSQL.="affilZip='" . escape_string(getpost('zip')) . "',";
		if(getpost('inform')=="ON")
			$sSQL.="affilInform=1 ";
		else
			$sSQL.="affilInform=0 ";
		$sSQL.="WHERE affilID='" . escape_string($theaffilid) . "'";
		ect_query($sSQL) or ect_error();
	}elseif(getpost('editaction')=="new"){
		$sSQL = "SELECT affilID FROM affiliates WHERE affilID='" . escape_string($theaffilid) . "'";
		$result=ect_query($sSQL) or ect_error();
		if(ect_num_rows($result) > 0) $addsuccess = FALSE;
		ect_free_result($result);
		if($addsuccess){
			$sSQL = "INSERT INTO affiliates (affilID,affilPW,affilEmail,affilName,affilAddress,affilCity,affilState,affilCountry,affilZip,affilCommision,affilDate,affilInform) VALUES (";
			$sSQL.="'" . escape_string($theaffilid) . "',";
			$sSQL.="'" . escape_string(dohashpw(getpost('affilpw'))) . "',";
			$sSQL.="'" . escape_string(getpost('email')) . "',";
			$sSQL.="'" . escape_string(getpost('name')) . "',";
			$sSQL.="'" . escape_string(getpost('address')) . "',";
			$sSQL.="'" . escape_string(getpost('city')) . "',";
			$sSQL.="'" . escape_string(getpost('state')) . "',";
			$sSQL.="'" . escape_string(getpost('country')) . "',";
			$sSQL.="'" . escape_string(getpost('zip')) . "',";
			if(@$defaultcommission!=""){
				$sSQL.=$defaultcommission . ",";
				$_SESSION["affilCommision"]=(double)$defaultcommission;
			}else{
				$sSQL.="0,";
				$_SESSION["affilCommision"]=0;
			}
			$sSQL.="'" . date('Y-m-d') . "',";
			if(getpost('inform')=="ON")
				$sSQL.="1) ";
			else
				$sSQL.="0) ";
			ect_query($sSQL) or ect_error();
			print '<meta http-equiv="Refresh" content="0; URL=affiliate.php">';
		}
	}
	if($addsuccess){
		$_SESSION["xaffilid"] = $theaffilid;
		if(getpost('affilpw')!='') $_SESSION["xaffilpw"] = dohashpw(getpost('affilpw'));
		$_SESSION["xaffilName"] = getpost('name');
	}
}elseif(getpost('act')=='affillogin'){
	$sSQL = "SELECT affilID,affilName,affilCommision,affilPW FROM affiliates WHERE affilID='" . escape_string($theaffilid) . "' AND affilPW='" . escape_string(dohashpw(getpost('affilpw'))) . "'";
	$result=ect_query($sSQL) or ect_error();
	if(ect_num_rows($result)>0){
		$rs=ect_fetch_assoc($result);
		$_SESSION["xaffilid"] = $theaffilid;
		$_SESSION["xaffilpw"] = $rs['affilPW'];
		$_SESSION["xaffilName"] = $rs["affilName"];
		$_SESSION["affilCommision"] = (double)$rs["affilCommision"];
		$showaccount=FALSE;
	}else
		$success=FALSE;
	ect_free_result($result);
	if($success){
		print '<meta http-equiv="Refresh" content="3; URL=affiliate.php">';
?>
	  <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
        <tr> 
          <td width="100%">
		    <form method="post" action="affiliate.php">
			  <table width="100%" border="0" cellspacing="3" cellpadding="3">
				<tr>
				  <td width="100%" align="center" colspan="2"><strong><?php print $GLOBALS['xxAffPrg'] . " " . $GLOBALS['xxWelcom'] . " " . htmlspecials($_SESSION['xaffilName'])?>.</strong></td>
				</tr>
				<tr>
				  <td width="100%" align="center" colspan="2">&nbsp;</td>
				</tr>
				<tr>
				  <td width="100%" align="center" colspan="2"><p><?php print $GLOBALS['xxAffLog']?></p>
					<p><?php print $GLOBALS['xxForAut']?> <a class="ectlink" href="affiliate.php"><strong><?php print $GLOBALS['xxClkHere']?></strong></a>.</p></td>
				</tr>
			  </table>
			</form>
		  </td>
        </tr>
      </table>
<?php
	}
}elseif(getpost('act')=='logout'){
	$_SESSION['xaffilid'] = '';
	$_SESSION['xaffilpw'] = '';
	$_SESSION['xaffilName'] = '';
}
if(getpost('act')=='newaffil' || (getpost('act')=='editaffil' && trim(@$_SESSION['xaffilid'])!='') || ! $addsuccess){
	$showaccount=FALSE;
?>
<script type="text/javascript">
<!--
function checkform(frm){
if(frm.affilid.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxAffID'])?>\".");
	frm.affilid.focus();
	return (false);
}
var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
var checkStr = frm.affilid.value;
var allValid = true;
for (i = 0;  i < checkStr.length;  i++){
    ch = checkStr.charAt(i);
    for (j = 0;  j < checkOK.length;  j++)
      if (ch==checkOK.charAt(j))
        break;
    if (j==checkOK.length)
    {
      allValid = false;
      break;
    }
}
if (!allValid){
    alert("<?php print jscheck($GLOBALS['xxAlphaNu'] . ' "' . $GLOBALS['xxAffID'])?>\".");
    frm.affilid.focus();
    return (false);
}
<?php	if(getpost('act')!='editaffil'){ ?>
if(frm.affilpw.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxPwd'])?>\".");
	frm.affilpw.focus();
	return (false);
}
<?php	} ?>
if(frm.name.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxName'])?>\".");
	frm.name.focus();
	return (false);
}
if(frm.email.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxEmail'])?>\".");
	frm.email.focus();
	return (false);
}
if(frm.address.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxAddress'])?>\".");
	frm.address.focus();
	return (false);
}
if(frm.city.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxCity'])?>\".");
	frm.city.focus();
	return (false);
}
if(frm.state.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxAllSta'])?>\".");
	frm.state.focus();
	return (false);
}
if(frm.zip.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxZip'])?>\".");
	frm.zip.focus();
	return (false);
}
return (true);
}
//-->
</script>
<?php
	$sAffilName = "";
	$sAffilPW = "";
	$sAffilid = "";
	$sAffilAddress = "";
	$sAffilCity = "";
	$sAffilState = "";
	$sAffilZip = "";
	$sAffilCountry = "";
	$sAffilEmail = "";
	$sAffilInform = FALSE;
	if(! $addsuccess){
		$sAffilName = getpost('name');
		$sAffilPW = '';
		$sAffilid = getpost('affilid');
		$sAffilAddress = getpost('address');
		$sAffilCity = getpost('city');
		$sAffilState = getpost('state');
		$sAffilZip = getpost('zip');
		$sAffilCountry = getpost('country');
		$sAffilEmail = getpost('email');
		$sAffilInform = getpost('inform')=="ON";
	}elseif(getpost('act')=='editaffil' && trim(@$_SESSION["xaffilid"])!=''){
		$sSQL = "SELECT affilName,affilPW,affilAddress,affilCity,affilState,affilZip,affilCountry,affilEmail,affilInform FROM affiliates WHERE affilID='" . escape_string(@$_SESSION["xaffilid"]) . "' AND affilPW='" . escape_string(@$_SESSION["xaffilpw"]) . "'";
		$result=ect_query($sSQL) or ect_error();
		if($rs=ect_fetch_assoc($result)){
			$sAffilName = $rs["affilName"];
			$sAffilPW = $rs["affilPW"];
			$sAffilAddress = $rs["affilAddress"];
			$sAffilCity = $rs["affilCity"];
			$sAffilState = $rs["affilState"];
			$sAffilZip = $rs["affilZip"];
			$sAffilCountry = $rs["affilCountry"];
			$sAffilEmail = $rs["affilEmail"];
			$sAffilInform = ((int)$rs["affilInform"])==1;
		}
		ect_free_result($result);
	}
?>		    <form method="post" action="<?php if(@$forceloginonhttps) print $pathtossl?>affiliate.php" onsubmit="return checkform(this)">
			  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
				<tr>
				  <td class="cobhl cobhdr" height="34" width="100%" align="center" colspan="4"><strong><?php print $GLOBALS['xxAffDts']?></strong></td>
				</tr>
<?php if(! $addsuccess){ ?>
				<tr>
				  <td width="100%" align="center" colspan="4"><span style="color:#FF0000;font-weight:bold"><?php print $GLOBALS['xxAffUse']?></span></td>
				</tr>
<?php } ?>
				<tr>
				  <td class="cobhl" height="34" align="right"><strong><?php print $redasterix.$GLOBALS['xxAffID']?>:</strong></td>
				  <td class="cobll" height="34" align="left"><?php
					if(getpost('act')=='editaffil' && trim(@$_SESSION['xaffilid'])!=''){
						print htmlspecials(trim(@$_SESSION['xaffilid']));
						?><input type="hidden" name="affilid" size="20" value="<?php print htmlspecials(trim(@$_SESSION['xaffilid']))?>" />
						  <input type="hidden" name="editaction" value="modify" /><?php
					}else{
						?><input type="text" name="affilid" size="20" value="<?php print htmlspecials($sAffilid)?>" />
						  <input type="hidden" name="editaction" value="new" /><?php
					} ?></td>
				  <td class="cobhl" height="34" align="right"><strong><?php print (getpost('act')=='editaffil'?$GLOBALS['xxReset'].' '.$GLOBALS['xxPwd']:$redasterix.$GLOBALS['xxPwd'])?>:</strong></td>
				  <td class="cobll" height="34" align="left"><input type="password" name="affilpw" size="20" value="" autocomplete="off" /></td>
				</tr>
				<tr>
				  <td class="cobhl" height="34" align="right"><strong><?php print $redasterix.$GLOBALS['xxName']?>:</strong></td>
				  <td class="cobll" height="34" align="left"><input type="text" name="name" size="20" value="<?php print htmlspecials($sAffilName)?>" /></td>
				  <td class="cobhl" height="34" align="right"><strong><?php print $redasterix.$GLOBALS['xxEmail']?>:</strong></td>
				  <td class="cobll" height="34" align="left"><input type="text" name="email" size="25" value="<?php print htmlspecials($sAffilEmail)?>" /></td>
				</tr>
				<tr>
				  <td class="cobhl" height="34" align="right"><strong><?php print $redasterix.$GLOBALS['xxAddress']?>:</strong></td>
				  <td class="cobll" height="34" align="left"><input type="text" name="address" size="20" value="<?php print htmlspecials($sAffilAddress)?>" /></td>
				  <td class="cobhl" height="34" align="right"><strong><?php print $redasterix.$GLOBALS['xxCity']?>:</strong></td>
				  <td class="cobll" height="34" align="left"><input type="text" name="city" size="20" value="<?php print htmlspecials($sAffilCity)?>" /></td>
				</tr>
				<tr>
				  <td class="cobhl" height="34" align="right"><strong><?php print $redasterix.$GLOBALS['xxAllSta']?>:</strong></td>
				  <td class="cobll" height="34" align="left"><input type="text" name="state" size="20" value="<?php print htmlspecials($sAffilState)?>" /></td>
				  <td class="cobhl" height="34" align="right"><strong><?php print $redasterix.$GLOBALS['xxCountry']?>:</strong></td>
				  <td class="cobll" height="34" align="left"><select name="country" size="1"><?php
function show_countries($tcountry){
	$sSQL = 'SELECT countryName,countryOrder,'.getlangid('countryName',8).' AS countryName FROM countries ORDER BY countryOrder DESC,' . getlangid('countryName',8);
	$result=ect_query($sSQL) or ect_error();
	while($rs=ect_fetch_assoc($result)){
		print "<option value='" . htmlspecials($rs['countryName']) . "'";
		if($tcountry==$rs['countryName'])
			print ' selected';
		print '>' . $rs['countryName'] . "</option>\n";
	}
	ect_free_result($result);
}
show_countries(@$sAffilCountry)
?></select>
				  </td>
				</tr>
				<tr>
				  <td class="cobhl" height="34" align="right"><strong><?php print $redasterix.$GLOBALS['xxZip']?>:</strong></td>
				  <td class="cobll" height="34" align="left"><input type="text" name="zip" size="10" value="<?php print htmlspecials($sAffilZip)?>" /></td>
				  <td class="cobhl" height="34" align="right"><strong><?php print $GLOBALS['xxInfMe']?>:</strong></td>
				  <td class="cobll" height="34" align="left"><input type="checkbox" name="inform" value="ON" <?php if($sAffilInform) print "checked"?> /></td>
				</tr>
				<tr>
				  <td class="cobll" height="34" width="100%" colspan="4">
					<span style="font-size:10px"><ul><li><?php print $GLOBALS['xxInform']?></li></ul></span>
				  </td>
				</tr>
				<tr>
				  <td class="cobll" height="34" align="center" colspan="4"><?php
					print imageorsubmit(@$imgsubmit,$GLOBALS['xxSubmt'],'submit');
					if(getpost('act')=='editaffil' && trim(@$_SESSION['xaffilid'])!=''){
						print '<br /><br />' . imageorbutton(@$imgbackacct,$GLOBALS['xxBack'],'backacct','history.go(-1)',TRUE);
					} ?></td>
				</tr>
			  </table>
			</form>
<?php
}
if($showaccount){
	if(@$_SESSION['xaffilid']==''){
?>			<form method="post" name="mainform" action="<?php if(@$forceloginonhttps) print $pathtossl?>affiliate.php">
			<input type="hidden" name="act" id="act" value="xxx" />
			  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
				<tr>
				  <td class="cobhl cobhdr" height="34" width="100%" align="center" colspan="2"><strong><?php print $GLOBALS['xxAffPrg']?></strong></td>
				</tr>
<?php if(! $success){ ?>
				<tr>
				  <td class="cobhl" height="34" width="100%" align="center" colspan="2"><span style="color:#FF0000"><?php print $GLOBALS['xxAffNo']?></span></td>
				</tr>
<?php } ?>
				<tr>
				  <td class="cobhl" height="34" width="50%" align="right"><?php print $GLOBALS['xxAffID']?>:</td>
				  <td class="cobll" height="34"><input type="text" name="affilid" size="20" value="<?php print htmlspecials(getpost('affilid'))?>" /></td>
				</tr>
				<tr>
				  <td class="cobhl" height="34" width="50%" align="right"><?php print $GLOBALS['xxPwd']?>:</td>
				  <td class="cobll" height="34"><input type="password" name="affilpw" size="20" value="<?php print htmlspecials(getpost('affilpw'))?>" autocomplete="off" /></td>
				</tr>
				<tr>
				  <td class="cobll" height="34" width="100%" align="center" colspan="2"><?php print imageorbutton(@$imgnewaffiliate,$GLOBALS['xxNewAct'],'newaffiliate',"document.getElementById('act').value='newaffil';document.forms.mainform.submit();",TRUE) . ' ' . imageorsubmit(@$imgaffiliatelogin,$GLOBALS['xxAffLI'].'" onclick="document.getElementById(\'act\').value=\'affillogin\'','affiliatelogin')?></td>
				</tr>
			  </table>
			</form>
<?php
	}else{
		$lastmonth = mktime (0,0,0,date("m")-1,date("d"), date("Y"));
		$totalDay=0.0;
		$totalYesterday=0.0;
		$totalMonth=0.0;
		$totalLastMonth=0.0;
		
		$sSQL = "SELECT Sum(ordTotal-ordDiscount) as theCount FROM orders WHERE ordStatus>=3 AND ordAffiliate='" . escape_string(@$_SESSION["xaffilid"]) . "' AND ordDate BETWEEN '" . date("Y-m-d") . "' AND '" . date("Y-m-d") . " 23:59:59'";
		$result=ect_query($sSQL) or ect_error();
		if($rs=ect_fetch_assoc($result))
			$totalDay = $rs["theCount"];
		ect_free_result($result);
		$sSQL = "SELECT Sum(ordTotal-ordDiscount) as theCount FROM orders WHERE ordStatus>=3 AND ordAffiliate='" . escape_string(@$_SESSION["xaffilid"]) . "' AND ordDate BETWEEN '" . date("Y-m-d", time()-(60*60*24)) . "' AND '" . date("Y-m-d") . "'";
		$result=ect_query($sSQL) or ect_error();
		if($rs=ect_fetch_assoc($result))
			$totalYesterday = $rs["theCount"];
		ect_free_result($result);
		$sSQL = "SELECT Sum(ordTotal-ordDiscount) as theCount FROM orders WHERE ordStatus>=3 AND ordAffiliate='" . escape_string(@$_SESSION["xaffilid"]) . "' AND ordDate BETWEEN '" . date("Y-m-01") . "' AND '" . date("Y-m-d") . " 23:59:59'";
		$result=ect_query($sSQL) or ect_error();
		if($rs=ect_fetch_assoc($result))
			$totalMonth = $rs["theCount"];
		ect_free_result($result);
		$sSQL = "SELECT Sum(ordTotal-ordDiscount) as theCount FROM orders WHERE ordStatus>=3 AND ordAffiliate='" . escape_string(@$_SESSION["xaffilid"]) . "' AND ordDate BETWEEN '" . date("Y-m-01", $lastmonth) . "' AND '" . date("Y-m-01") . " 00:00:00'";
		$result=ect_query($sSQL) or ect_error();
		if($rs=ect_fetch_assoc($result))
			$totalLastMonth = $rs["theCount"];
		ect_free_result($result);
		if(is_null($totalDay)) $totalDay=0.0;
		if(is_null($totalYesterday)) $totalYesterday=0.0;
		if(is_null($totalMonth)) $totalMonth=0.0;
		if(is_null($totalLastMonth)) $totalLastMonth=0.0;
		$alreadygotadmin = getadminsettings();
?>		    <form method="post" name="mainform" action="affiliate.php">
			  <input type="hidden" name="act" value="" />
			  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
				<tr>
				  <td class="cobhl cobhdr" height="34" width="100%" align="center" colspan="2"><strong><?php print $GLOBALS['xxAffPrg'] . ' ' . $GLOBALS['xxWelcom'] . ' ' . htmlspecials(@$_SESSION['xaffilName'])?>.</strong></td>
				</tr>
				<tr>
				  <td class="cobhl" height="34" align="right" width="50%"><strong><?php print $GLOBALS['xxTotTod']?>:</strong></td>
				  <td class="cobll" height="34"><?php print FormatEuroCurrency($totalDay);
				  if($_SESSION["affilCommision"]!=0) print ' = ' . FormatEuroCurrency(($totalDay * $_SESSION["affilCommision"]) / 100.0) . ' <strong>' . $GLOBALS['xxCommis'] . "</strong>";?></td>
				</tr>
				<tr>
				  <td class="cobhl" height="34" align="right" ><strong><?php print $GLOBALS['xxTotYes']?>:</strong></td>
				  <td class="cobll" height="34"><?php print FormatEuroCurrency($totalYesterday);
				  if($_SESSION["affilCommision"]!=0) print ' = ' . FormatEuroCurrency(($totalYesterday * $_SESSION["affilCommision"]) / 100.0) . ' <strong>' . $GLOBALS['xxCommis'] . "</strong>";?></td>
				</tr>
				<tr>
				  <td class="cobhl" height="34" align="right" ><strong><?php print $GLOBALS['xxTotMTD']?>:</strong></td>
				  <td class="cobll" height="34"><?php print FormatEuroCurrency($totalMonth);
				  if($_SESSION["affilCommision"]!=0) print ' = ' . FormatEuroCurrency(($totalMonth * $_SESSION["affilCommision"]) / 100.0) . ' <strong>' . $GLOBALS['xxCommis'] . "</strong>";?></td>
				</tr>
				<tr>
				  <td class="cobhl" height="34" align="right" ><strong><?php print $GLOBALS['xxTotLM']?>:</strong></td>
				  <td class="cobll" height="34"><?php print FormatEuroCurrency($totalLastMonth);
				  if($_SESSION["affilCommision"]!=0) print ' = ' . FormatEuroCurrency(($totalLastMonth * $_SESSION["affilCommision"]) / 100.0) . ' <strong>' . $GLOBALS['xxCommis'] . "</strong>";?></td>
				</tr>
				<tr>
				  <td class="cobll" height="34"width="100%" align="center" colspan="2"><?php print imageorsubmit(@$imglogout,$GLOBALS['xxLogout'].'" onclick="document.forms.mainform.act.value=\'logout\'','logout') . ' ' . imageorsubmit(@$imgeditaffiliate,$GLOBALS['xxEdtAff'].'" onclick="document.forms.mainform.act.value=\'editaffil\'','editaffiliate')?></td>
				</tr>
				<tr>
				  <td class="cobll" width="100%" colspan="2"><span style="font-size:10px">
				    <ul>
					  <li><?php print $GLOBALS['xxAffLI1']?> <strong>products.php?PARTNER=<?php print htmlspecials(trim(@$_SESSION['xaffilid']))?></strong></li>
					  <li><?php print $GLOBALS['xxAffLI2']?></li>
					  <?php if($_SESSION["affilCommision"]==0){ ?>
					  <li><?php print $GLOBALS['xxAffLI3']?></li>
					  <?php } ?>
					</ul></span></td>
				</tr>
			  </table>
			</form>
<?php
	}
}
?>