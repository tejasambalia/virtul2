<?php
//This code is copyright (c) Internet Business Solutions SL, all rights reserved.
//The contents of this file are protected under law as the intellectual property of Internet
//Business Solutions SL. Any use, reproduction, disclosure or copying of any kind 
//without the express and written permission of Internet Business Solutions SL is forbidden.
//Author: Vince Reid, vince@virtualred.net
if(!@$GLOBALS['incfunctionsdefined']){print 'No incfunctions.php file';exit;}
global $alreadygotadmin,$pathtossl,$forceloginonhttps;
include './vsadmin/inc/incemail.php';
$cartisincluded=TRUE;
include './vsadmin/inc/inccart.php';
if(@$_SERVER['CONTENT_LENGTH']!='' && $_SERVER['CONTENT_LENGTH'] > 10000) exit;
$success=TRUE;
if(@$dateformatstr=='') $dateformatstr = 'm/d/Y';
$ordGrandTotal = $ordTotal = $ordStateTax = $ordHSTTax = $ordCountryTax = $ordShipping = $ordHandling = $ordDiscount = 0;
$ordID = $affilID = $ordCity = $ordState = $ordCountry = $ordDiscountText = $ordEmail = '';
$digidownloads=FALSE;
$allcountries='';
$warncheckspamfolder = FALSE;
if(@$enableclientlogin!=TRUE && @$forceclientlogin!=TRUE){
	$success=FALSE;
	$errmsg="Client login not enabled";
}
if(@$pathtossl!=''){
	if(substr($pathtossl,-1)!='/') $pathtossl.='/';
}else
	$pathtossl='';
$pagename = htmlentities(basename($_SERVER['PHP_SELF']));
if(@$forceloginonhttps) $thisaction = $pathtossl . basename(@$_SERVER['PHP_SELF']); else $thisaction = @$_SERVER['PHP_SELF'];
$alreadygotadmin = getadminsettings();
?>
<script type="text/javascript">
/* <![CDATA[ */
function vieworder(theid){
	document.forms.mainform.action.value="vieworder";
	document.forms.mainform.theid.value=theid;
	document.forms.mainform.submit();
}
function editaddress(theid){
	document.forms.mainform.action.value="editaddress";
	document.forms.mainform.theid.value=theid;
	document.forms.mainform.submit();
}
function newaddress(){
	document.forms.mainform.action.value="newaddress";
	document.forms.mainform.submit();
}
function editaccount(){
	document.forms.mainform.action.value="editaccount";
	document.forms.mainform.submit();
}
function deleteaddress(theid){
	if(confirm("<?php print jscheck($GLOBALS['xxDelAdd'])?>")){
		document.forms.mainform.action.value="deleteaddress";
		document.forms.mainform.theid.value=theid;
		document.forms.mainform.submit();
	}
}
function createlist(){
if(document.forms.mainform.listname.value==''){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxLisNam'])?>\".");
	document.forms.mainform.listname.focus();
	return(false);
}else{
	document.forms.mainform.action.value="createlist";
	document.forms.mainform.submit();
}
}
function deletelist(theid){
	if(confirm("<?php print jscheck($GLOBALS['xxDelLis'])?>")){
		document.forms.mainform.action.value="deletelist";
		document.forms.mainform.theid.value=theid;
		document.forms.mainform.submit();
	}
}
/* ]]> */
</script>
<?php
	if(getpost('doresetpw')=="1"){
		$sSQL = "SELECT clID FROM customerlogin WHERE clEmail='".escape_string(getpost('rst'))."' AND clPw='".escape_string(getpost('rsk'))."'";
		$result=ect_query($sSQL) or ect_error();
		if($rs=ect_fetch_assoc($result)) $clid=$rs['clID']; else $clid='';
		if(getpost('newpw')=='') $clid='';
		ect_free_result($result);
		if($clid!='') ect_query("UPDATE customerlogin SET clPw='".escape_string(dohashpw(getpost('newpw')))."' WHERE clID=" . $clid) or ect_error();
?>	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr>
		  <td class="cobhl cobhdr" align="center" height="38" colspan="2"><strong><?php print $GLOBALS['xxCusAcc']?></strong></td>
		</tr>
		  <tr>
			<td class="cobhl" align="right" height="38" width="40%"><strong><?php print $GLOBALS['xxForPas']?></strong></td>
			<td class="cobll" align="left" height="38"><?php print ($clid==''?$GLOBALS['xxEmNtFn']:$GLOBALS['xxPasRsS']) ?></td>
		  </tr>
		  <tr>
			<td class="cobll" align="center" height="38" colspan="2"><?php
		if($clid!='')
			print imageorbutton(@$imglogin,$GLOBALS['xxLogin'],'login',(@$forceloginonhttps?$pathtossl:'').'cart.php?mode=login',FALSE);
		else
			print imageorbutton(@$imggoback,$GLOBALS['xxGoBack'],'goback','history.go(-1)',TRUE); ?></p></td>
		  </tr>
      </table>
<?php
	}elseif(getget('rst')!='' && getget('rsk')!=''){
		$sSQL = "SELECT clID FROM customerlogin WHERE clEmail='".escape_string(getget('rst'))."' AND clPw='".escape_string(getget('rsk'))."'";
		$result=ect_query($sSQL) or ect_error();
		if(ect_num_rows($result)>0) $success=TRUE; else $success=FALSE;
		ect_free_result($result);
		if(! $success){ ?>
	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr>
		  <td class="cobhl cobhdr" align="center" height="38" colspan="2"><strong><?php print $GLOBALS['xxCusAcc']?></strong></td>
		</tr>
		  <tr>
			<td class="cobhl" align="right" height="38" width="40%"><strong><?php print $GLOBALS['xxForPas']?></strong></td>
			<td class="cobll" align="left" height="38"><?php print $GLOBALS['xxSorRes']?></td>
		  </tr>
		  <tr>
			<td class="cobll" align="center" height="38" colspan="2"><?php print imageorbutton(@$imgcancel,$GLOBALS['xxCancel'],'cancel',$storeurl,FALSE) ?></td>
		  </tr>
      </table>
<?php	}else{ ?>
<script type="text/javascript">
/* <![CDATA[ */
function checknewpw(frm){
if(frm.newpw.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxNewPwd'])?>\".");
	frm.newpw.focus();
	return(false);
}
var newpw = frm.newpw.value;
var newpw2 = frm.newpw2.value;
if(newpw!=newpw2){
	alert("<?php print jscheck($GLOBALS['xxPwdMat'])?>");
	frm.newpw.focus();
	return(false);
}
return true;
}
/* ]]> */
</script>
	<form method="post" name="mainform" action="<?php print $thisaction?>" onsubmit="return checknewpw(this)">
	<input type="hidden" name="doresetpw" value="1" />
	<input type="hidden" name="rst" value="<?php print str_replace('"','',getget('rst'))?>" />
	<input type="hidden" name="rsk" value="<?php print str_replace('"','',getget('rsk'))?>" />
	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr>
		  <td class="cobhl cobhdr" align="center" height="38" colspan="2"><strong><?php print $GLOBALS['xxCusAcc'] . ' ' . $GLOBALS['xxForPas']?></strong></td>
		</tr>
		  <tr>
			<td class="cobhl" align="right" height="38" width="40%"><strong><?php print $GLOBALS['xxNewPwd']?></strong></td>
			<td class="cobll" align="left" height="38"><input type="password" size="20" name="newpw" value="" autocomplete="off" /></td>
		  </tr>
		  <tr>
			<td class="cobhl" align="right" height="38" width="40%"><strong><?php print $GLOBALS['xxRptPwd']?></strong></td>
			<td class="cobll" align="left" height="38"><input type="password" size="20" name="newpw2" value="" autocomplete="off" /></td>
		  </tr>
		  <tr>
			<td class="cobll" align="center" height="38" colspan="2">
		<?php print imageorsubmit(@$imgsubmit,$GLOBALS['xxSubmt'],'submit').' '.imageorbutton(@$imgcancel,$GLOBALS['xxCancel'],'cancel',$storeurl,FALSE)?></td>
		  </tr>
      </table>
	</form>
<?php	}
	}elseif(getget('action')=='logout'){
		$_SESSION['clientID']=NULL; unset($_SESSION['clientID']);
		$_SESSION['clientUser']=NULL; unset($_SESSION['clientUser']);
		$_SESSION['clientActions']=NULL; unset($_SESSION['clientActions']);
		$_SESSION['clientLoginLevel']=NULL; unset($_SESSION['clientLoginLevel']);
		$_SESSION['clientPercentDiscount']=NULL; unset($_SESSION['clientPercentDiscount']);
		ectsetcookie('WRITECLL', 'x', 100, '/', '');
		ectsetcookie('WRITECLP', 'y', 100, '/', '');
		if(@$clientlogoutref!='')
			$refURL = $clientlogoutref;
		else
			$refURL = $GLOBALS['xxHomeURL'];
		print '<meta http-equiv="refresh" content="3; url=' . $refURL . '">';
?>
		<table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		  <tr>
			<td class="cobll" align="center">
			  <p>&nbsp;</p>
			  <br /><strong><?php print $GLOBALS['xxLOSuc']?></strong><br /><br /><?php print $GLOBALS['xxAutFo']?><br /><br />
				<?php print $GLOBALS['xxForAut']?> <a class="ectlink" href="<?php print $refURL?>"><strong><?php print $GLOBALS['xxClkHere']?></strong></a>.<br />
				<br />&nbsp;
			</td>
		  </tr>
		</table>
<?php	
	}elseif(getpost('action')=='dolostpassword'){
		$theemail = cleanupemail(getpost('email'));
		$sSQL = "SELECT clPW FROM customerlogin WHERE clEmail<>'' AND clEmail='" . escape_string($theemail) . "'";
		$result=ect_query($sSQL) or ect_error();
		if(ect_num_rows($result) > 0){
			$rs=ect_fetch_assoc($result);
			if(@$htmlemails==TRUE) $emlNl = '<br />'; else $emlNl="\n";
			$tlink = $storeurl . $pagename . "?rst=" . $theemail . "&rsk=" . $rs['clPW'];
			if(@$htmlemails==TRUE) $tlink = '<a href="' . $tlink . '">' . $tlink . '</a>';
			dosendemail($theemail, $emailAddr, '', $GLOBALS['xxForPas'], $GLOBALS['xxLosPw1'] . $emlNl . $storeurl . $emlNl . $emlNl . $GLOBALS['xxResPas'] . $emlNl . $tlink . $emlNl . $emlNl . $GLOBALS['xxLosPw3'] . $emlNl);
			$success=TRUE;
		}else{
			$success=FALSE;
		}
		ect_free_result($result); ?>
	  <form method="post" name="mainform" action="<?php print $thisaction?>">
	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr>
		  <td class="cobhl cobhdr" align="center" height="38" colspan="2"><strong><?php print $GLOBALS['xxCusAcc']?></strong></td>
		</tr>
		  <tr>
			<td class="cobhl" align="right" height="38" width="40%"><strong><?php print $GLOBALS['xxForPas']?></strong></td>
			<td class="cobll" align="left" height="38"><?php if($success) print $GLOBALS['xxSenPw']; else print $GLOBALS['xxSorPw']; ?></td>
		  </tr>
		  <tr>
			<td class="cobll" align="center" height="38" colspan="2"><?php
		if($success)
			print imageorbutton(@$imglogin,$GLOBALS['xxLogin'],'login',(@$forceloginonhttps?$pathtossl:'') . 'cart.php?mode=login',FALSE);
		else
			print imageorbutton(@$imggoback,$GLOBALS['xxGoBack'],'goback','history.go(-1)',TRUE);
		?></td>
		  </tr>
	  </table>
	  </form>
<?php
	}elseif(getget('mode')=='lostpassword'){ ?>
	  <form method="post" name="mainform" action="<?php print $thisaction?>">
	  <input type="hidden" name="action" value="dolostpassword" />
	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr>
		  <td class="cobhl cobhdr" align="center" height="32" colspan="2"><strong><?php print $GLOBALS['xxCusAcc']?></strong></td>
		</tr>
		<tr>
		  <td class="cobhl" align="right" height="26"><strong><?php print $GLOBALS['xxForPas']?></strong></td>
		  <td class="cobll" align="left" height="26"><span style="font-size:10px"><?php print $GLOBALS['xxEntEm']?></span></td>
		</tr>
		<tr>
		  <td class="cobhl" align="right" height="26"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print $GLOBALS['xxEmail']?>: </strong></td>
		  <td class="cobll" align="left" height="26"><input type="text" name="email" size="31" /></td>
		</tr>
		<tr>
		  <td class="cobhl" align="center" height="26" colspan="2"><?php print imageorsubmit(@$imgsubmit,$GLOBALS['xxSubmt'],'submit')?></td>
		</tr>
      </table>
	  </form>
<?php
	}elseif(@$_SESSION['clientID']==''){ ?>
	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr>
		  <td class="cobhl cobhdr" align="center" height="32" colspan="2"><strong><?php print $GLOBALS['xxCusAcc']?></strong></td>
		</tr>
		<tr>
		  <td class="cobll" align="center" height="32" colspan="2"><p>&nbsp;</p><p><?php print $GLOBALS['xxMusLog']?></p>
		  <p><?php print imageorbutton(@$imglogin,$GLOBALS['xxLogin'],'login',(@$forceloginonhttps?$pathtossl:'')."cart.php?mode=login&amp;refurl=".urlencode(@$_SERVER['PHP_SELF']),FALSE)?></p>
		  <p>&nbsp;</p>
		  </td>
		</tr>
      </table>
<?php
	}else{ // is logged in
		if(getpost('action')=='vieworder'){ ?>
	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr>
		  <td width="100%" class="cobll"><?php
			$ordID = str_replace("'",'',getpost('theid'));
			if(is_numeric($ordID)) $success=TRUE; else $success=FALSE;
			if($success){
				$sSQL = "SELECT ordID FROM orders WHERE ordID=" . $ordID . " AND ordClientID=" . $_SESSION['clientID'];
				$result=ect_query($sSQL) or ect_error();
				if(ect_num_rows($result)==0) $success=FALSE;
				ect_free_result($result);
			}
			if($success){
				$GLOBALS['xxThkYou']=imageorbutton(@$imgbackacct,$GLOBALS['xxBack'],'backacct','history.go(-1)',TRUE);
				$GLOBALS['xxRecEml']='';
				$thankspagecontinue='javascript:history.go(-1)';
				$GLOBALS['xxCntShp']=$GLOBALS['xxBack'];
				$imgcontinueshopping=@$imgbackacct;
				do_order_success($ordID,$emailAddr,FALSE,TRUE,FALSE,FALSE,FALSE);
			}else{
				$errtext = "Sorry, could not find a matching order.";
				order_failed();
			} ?>
		  </td>
		</tr>
	  </table>
<?php	}elseif(getpost('action')=='doeditaccount'){
			$oldpw = dohashpw(getpost('oldpw'));
			$newpw = getpost('newpw');
			$newpw2 = getpost('newpw2');
			$clientuser = getpost('name');
			$clientemail = cleanupemail(getpost('email'));
			$allowemail = getpost('allowemail');
			$sSQL = "SELECT clPW,clEmail FROM customerlogin WHERE clID=" . $_SESSION['clientID'];
			$result=ect_query($sSQL) or ect_error();
			$rs=ect_fetch_assoc($result);
			ect_free_result($result);
			$oldpassword=$rs['clPW'];
			$oldemail=$rs['clEmail'];
			$success=TRUE;
			if($newpw!='' || $newpw2!=''){
				if($oldpw!=$oldpassword){
					$success=FALSE;
					$errmsg=$GLOBALS['xxExNoMa'];
				}
			}
			if($oldemail != $clientemail){
				$sSQL = "SELECT clID FROM customerlogin WHERE clEmail='" . escape_string($clientemail) . "'";
				$result=ect_query($sSQL) or ect_error();
				if(ect_num_rows($result) > 0){
					$success=FALSE;
					$errmsg=$GLOBALS['xxEmExi'];
				}
				ect_free_result($result);
			}
			if($success){
				$sSQL = 'UPDATE customerlogin SET ';
				$sSQL.="clUserName='" . escape_string($clientuser) . "',";
				$sSQL.="clEmail='" . escape_string($clientemail) . "'";
				if($newpw!='') $sSQL.=",clPW='" . escape_string(dohashpw($newpw)) . "'";
				$sSQL.=" WHERE clID=" . $_SESSION['clientID'];
				ect_query($sSQL) or ect_error();
				if($allowemail=='ON'){
					addtomailinglist($clientemail,$clientuser);
					if($oldemail != $clientemail) ect_query("DELETE FROM mailinglist WHERE email='" . escape_string($oldemail) . "'");
				}else{
					ect_query("DELETE FROM mailinglist WHERE email='" . escape_string($clientemail) . "'");
					ect_query("DELETE FROM mailinglist WHERE email='" . escape_string($oldemail) . "'");
				}
				$_SESSION['clientUser']=$clientuser;
				print '<meta http-equiv="Refresh" content="2; URL=' . $_SERVER['PHP_SELF'] . '">';
			}
?>
	<form method="post" name="mainform" action="<?php print $thisaction?>">
	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr>
		  <td class="cobhl cobhdr" align="center" height="38" colspan="2"><strong><?php print $GLOBALS['xxCusAcc']?></strong></td>
		</tr>
		<tr>
		  <td class="cobll" align="center" height="38"><?php if($success) print $GLOBALS['xxUpdSuc']; else print $errmsg ?></td>
		</tr>
		<tr>
		  <td class="cobll" align="center" height="38" colspan="2"><?php
		if($success)
			print imageorsubmit(@$imgcustomeracct,$GLOBALS['xxCusAcc'],'customeracct');
		else
			print imageorbutton(@$imggoback,$GLOBALS['xxGoBack'],'goback','history.go(-1)',TRUE);
		?></td>
		</tr>
	  </table>
	</form>
<?php	}elseif(getpost('action')=='editaccount'){
			if(@$forceloginonhttps && (@$_SERVER['HTTPS']!='on' && @$_SERVER['SERVER_PORT']!='443') && strpos(@$pathtossl,'https')!==FALSE){ header('Location: '.$pathtossl.basename($_SERVER['PHP_SELF']).(@$_SERVER['QUERY_STRING']!='' ? '?'.$_SERVER['QUERY_STRING'] : '')); exit; }
?>
<script type="text/javascript">
/* <![CDATA[ */
var checkedfullname=false;
function checknewaccount(){
frm=document.forms.mainform;
if(frm.name.value==""||frm.name.value=="<?php print $GLOBALS['xxFirNam']?>"){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . @$usefirstlastname ? $GLOBALS['xxFirNam'] : $GLOBALS['xxName'])?>\".");
	frm.name.focus();
	return (false);
}
gotspace=false;
var checkStr = frm.name.value;
for (i = 0; i < checkStr.length; i++){
	if(checkStr.charAt(i)==" ")
		gotspace=true;
}
if(!checkedfullname && !gotspace){
	alert("<?php print jscheck($GLOBALS['xxFulNam'] . ' "' . $GLOBALS['xxName'])?>\".");
	frm.name.focus();
	checkedfullname=true;
	return (false);
}
if(frm.email.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxEmail'])?>\".");
	frm.email.focus();
	return (false);
}
var regex = /[^@]+@[^@]+\.[a-z]{2,}$/i;
if(!regex.test(frm.email.value)){
	alert("<?php print jscheck($GLOBALS['xxValEm'])?>");
	frm.email.focus();
	return (false);
}
var newpw = frm.newpw.value;
var newpw2 = frm.newpw2.value;
if(newpw!='' && newpw!=newpw2){
	alert("<?php print jscheck($GLOBALS['xxPwdMat'])?>");
	frm.newpw.focus();
	return(false);
}
return true;
}
/* ]]> */
</script>
		<form method="post" name="mainform" action="<?php print $thisaction?>" onsubmit="return checknewaccount()">
		<input type="hidden" name="action" value="doeditaccount" />
		<table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		  <tr>
            <td class="cobhl cobhdr" align="center" height="34"><strong><?php print $GLOBALS['xxAccDet']?></strong></td>
		  </tr>
		  <tr>
            <td class="cobll" align="center">
				  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
<?php		$sSQL = "SELECT clID,clUserName,clActions,clLoginLevel,clPercentDiscount,clEmail,loyaltyPoints FROM customerlogin WHERE clID=" . $_SESSION['clientID'];
			$result=ect_query($sSQL) or ect_error();
			if($rs=ect_fetch_assoc($result)) $theemail=$rs['clEmail']; else $_SESSION['clientID']='';
			ect_free_result($result);
			$sSQL = "SELECT email FROM mailinglist WHERE email='" . escape_string(@$theemail) . "'";
			$result=ect_query($sSQL) or ect_error();
			if(ect_num_rows($result)>0) $allowemail=1; else $allowemail=0;
			ect_free_result($result);
?>
					<tr><td class="cobhl" align="right" width="<?php print (@$nounsubscribe?'50':'20')?>%"><strong><?php print $GLOBALS['xxName']?>:</strong></td>
					<td class="cobll" align="left" width="<?php print (@$nounsubscribe?'50':'30')?>%"><input type="text" size="30" name="name" value="<?php print htmlspecials($_SESSION['clientUser'])?>" /></td>
<?php		if(@$nounsubscribe!=TRUE){ ?>
					<td class="cobll" align="right" width="8%" rowspan="2"><input type="checkbox" name="allowemail" value="ON"<?php if($allowemail!=0) print ' checked="checked"'?> /></td>
					<td class="cobhl" align="left" rowspan="2"><strong><?php print $GLOBALS['xxAlPrEm']?></strong><br />
					<span style="font-size:10px"><?php print $GLOBALS['xxNevDiv']?></span></td>
<?php		} ?>
					</tr><tr><td class="cobhl" align="right"><strong><?php print $GLOBALS['xxEmail']?>:</strong></td>
					<td class="cobll" align="left"><input type="text" size="30" name="email" value="<?php print $theemail?>" /></td>
					</tr><tr><td class="cobhl" align="center" colspan="<?php print (@$nounsubscribe?'2':'4')?>" height="34"><strong><?php print $GLOBALS['xxPwdChg']?></strong></td></tr>
					
					<tr><td class="cobhl" align="right" <?php print (@$nounsubscribe?'':'colspan="2"')?>><strong><?php print $GLOBALS['xxOldPwd']?>:</strong></td>
					<td class="cobll" align="left" <?php print (@$nounsubscribe?'':'colspan="2"')?>><input type="password" size="20" name="oldpw" value="" autocomplete="off" /></td></tr>
					<tr><td class="cobhl" align="right" <?php print (@$nounsubscribe?'':'colspan="2"')?>><strong><?php print $GLOBALS['xxNewPwd']?>:</strong></td>
					<td class="cobll" align="left" <?php print (@$nounsubscribe?'':'colspan="2"')?>><input type="password" size="20" name="newpw" value="" autocomplete="off" /></td></tr>
					<tr><td class="cobhl" align="right" <?php print (@$nounsubscribe?'':'colspan="2"')?>><strong><?php print $GLOBALS['xxRptPwd']?>:</strong></td>
					<td class="cobll" align="left" <?php print (@$nounsubscribe?'':'colspan="2"')?>><input type="password" size="20" name="newpw2" value="" autocomplete="off" /></td></tr>
	
					<tr><td class="cobll" align="center" colspan="4" height="34"><?php print imageorsubmit(@$imgsubmit,$GLOBALS['xxSubmt'],'submit').' '.imageorbutton(@$imgcancel,$GLOBALS['xxCancel'],'cancel','history.go(-1)',TRUE)?></td></tr>
				  </table>
			</td>
		  </tr>
		</table>
		</form>
<?php	}elseif(getpost('action')=='editaddress' || getpost('action')=='newaddress'){
			$addID = str_replace("'",'',getpost('theid'));
			if(!is_numeric($addID))$addID=0;
			$addIsDefault='';
			$addName='';
			$addLastName='';
			$addAddress='';
			$addAddress2='';
			$addState='';
			$addCity='';
			$addZip='';
			$addPhone='';
			$addCountry='';
			$addExtra1='';
			$addExtra2='';
			$sSQL = 'SELECT stateID FROM states INNER JOIN countries ON states.stateCountryID=countries.countryID WHERE countryEnabled<>0 AND stateEnabled<>0 AND (loadStates=2 OR countryID=' . $origCountryID . ') ORDER BY stateCountryID,stateName';
			$result=ect_query($sSQL) or ect_error();
			$hasstates = (ect_num_rows($result)>0);
			ect_free_result($result);
			$sSQL = "SELECT countryName,countryOrder,".getlangid("countryName",8).",countryID,loadStates FROM countries WHERE countryEnabled=1 ORDER BY countryOrder DESC," . getlangid("countryName",8);
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result)){
				$allcountries[$numallcountries++]=$rs;
			}
			ect_free_result($result);
			for($index=0;$index<$numallcountries;$index++){
				if($allcountries[$index]['loadStates']==0){ $nonhomecountries=TRUE; break; }
			}
			if(! $nonhomecountries){
				for($index=0;$index<$numallcountries;$index++){
					if($allcountries[$index]['loadStates']>0){
						$sSQL = "SELECT stateID FROM states WHERE stateEnabled<>0 AND stateCountryID=" . $allcountries[$index]['countryID'];
						$result=ect_query($sSQL) or ect_error();
						if(ect_num_rows($result)==0) $nonhomecountries=TRUE;
						ect_free_result($result);
						if($nonhomecountries) break;
					}
				}
			}
			if(getpost('action')=='editaddress'){
				$sSQL = "SELECT addID,addIsDefault,addName,addLastName,addAddress,addAddress2,addState,addCity,addZip,addPhone,addCountry,addExtra1,addExtra2 FROM address WHERE addID=" . $addID . " AND addCustID='" . $_SESSION['clientID'] . "' ORDER BY addIsDefault";
				$result=ect_query($sSQL) or ect_error();
				if($rs=ect_fetch_assoc($result)){
					$addIsDefault=$rs['addIsDefault'];
					$addName=$rs['addName'];
					$addLastName=$rs['addLastName'];
					$addAddress=$rs['addAddress'];
					$addAddress2=$rs['addAddress2'];
					$addState=$rs['addState'];
					$ordState=$addState;
					$addCity=$rs['addCity'];
					$addZip=$rs['addZip'];
					$addPhone=$rs['addPhone'];
					$addCountry=$rs['addCountry'];
					$addExtra1=$rs['addExtra1'];
					$addExtra2=$rs['addExtra2'];
				}
				ect_free_result($result);
			} ?>
	<form method="post" name="mainform" action="<?php print $thisaction?>" onsubmit="return checkform(this)">
	<input type="hidden" name="action" value="<?php if(getpost('action')=='editaddress') print "doeditaddress"; else print "donewaddress" ?>" />
	<input type="hidden" name="theid" value="<?php print $addID?>" />
	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr><td align="center" class="cobhl cobhdr" colspan="2" height="32"><strong><?php print $GLOBALS['xxEdAdd']?></strong></td></tr>
		<?php	if(trim(@$extraorderfield1)!=''){ ?>
		<tr><td align="right" class="cobhl"><strong><?php print (@$extraorderfield1required==TRUE ? $redstar : '') . $extraorderfield1 ?>:</strong></td><td class="cobll"><?php if(@$extraorderfield1html!='') print $extraorderfield1html; else print '<input type="text" name="ordextra1" id="ordextra1" size="20" value="' . htmlspecials($addExtra1) . '" />'?></td></tr>
		<?php	} ?>
		<tr><td align="right" width="40%" class="cobhl"><strong><?php print $redstar . $GLOBALS['xxName']?>:</strong></td><td class="cobll"><?php
		if(@$usefirstlastname){
			$thestyle='';
			if($addName=='' && $addLastName==''){ $addName=$GLOBALS['xxFirNam']; $addLastName=$GLOBALS['xxLasNam']; $thestyle='style="color:#BBBBBB" '; }
			print '<input type="text" name="name" size="11" value="'.htmlspecials($addName).'" alt="'.$GLOBALS['xxFirNam'].'" onfocus="if(this.value==\''.$GLOBALS['xxFirNam'].'\'){this.value=\'\';this.style.color=\'\';}" '.$thestyle.'/> <input type="text" name="lastname" size="11" value="'.htmlspecials($addLastName).'" alt="'.$GLOBALS['xxLasNam'].'" onfocus="if(this.value==\''.$GLOBALS['xxLasNam'].'\'){this.value=\'\';this.style.color=\'\';}" '.$thestyle.'/>';
		}else
			print '<input type="text" name="name" id="name" size="20" value="'.htmlspecials($addName).'" />';
		?></td></tr>
		<tr><td align="right" class="cobhl"><strong><?php print $redstar . $GLOBALS['xxAddress']?>:</strong></td><td class="cobll"><input type="text" name="address" id="address" size="25" value="<?php print htmlspecials($addAddress)?>" /></td></tr>
		<?php	if(@$useaddressline2==TRUE){ ?>
		<tr><td align="right" class="cobhl"><strong><?php print $GLOBALS['xxAddress2']?>:</strong></td><td class="cobll"><input type="text" name="address2" id="address2" size="25" value="<?php print htmlspecials($addAddress2)?>" /></td></tr>
		<?php	} ?>
		<tr><td align="right" class="cobhl"><strong><?php print $redstar . $GLOBALS['xxCity']?>:</strong></td><td class="cobll"><input type="text" name="city" id="city" size="20" value="<?php print htmlspecials($addCity)?>" /></td></tr>
		<?php	if($hasstates || $nonhomecountries){ ?>
		<tr><td align="right" class="cobhl"><strong><?php print replace($redstar,'<span','<span id="statestar"')?><span id="statetxt"><?php print $GLOBALS['xxState']?></span>:</strong></td><td class="cobll"><select name="state" id="state" size="1" onchange="dosavestate('')"><?php $havestate = show_states($addState) ?></select><input type="text" name="state2" id="state2" size="20" value="<?php if(! $havestate) print htmlspecials($addState)?>" /></td></tr>
		<?php	} ?>
		<tr><td align="right" class="cobhl"><strong><?php print $redstar . $GLOBALS['xxCountry']?>:</strong></td><td class="cobll"><select name="country" id="country" size="1" onchange="checkoutspan('')" ><?php show_countries($addCountry,FALSE) ?></select></td></tr>
		<tr><td align="right" class="cobhl"><strong><?php print replace($redstar,'<span','<span id="zipstar"') . '<span id="ziptxt">' . $GLOBALS['xxZip'] . '</span>'?>:</strong></td><td class="cobll"><input type="text" name="zip" id="zip" size="10" value="<?php print htmlspecials($addZip)?>" /></td></tr>
		<tr><td align="right" class="cobhl"><strong><?php print $redstar . $GLOBALS['xxPhone']?>:</strong></td><td class="cobll"><input type="text" name="phone" id="phone" size="20" value="<?php print htmlspecials($addPhone)?>" /></td></tr>
		<?php	if(trim(@$extraorderfield2)!=''){ ?>
		<tr><td align="right" class="cobhl"><strong><?php print (@$extraorderfield2required==true ? $redstar : '') . $extraorderfield2 ?>:</strong></td><td class="cobll"><?php if(@$extraorderfield2html!='') print $extraorderfield2html; else print '<input type="text" name="ordextra2" id="ordextra2" size="20" value="' . htmlspecials($addExtra2) . '" />'?></td></tr>
		<?php	} ?>
		<tr><td align="center" colspan="2" class="cobll"><?php print imageorsubmit(@$imgsubmit,$GLOBALS['xxSubmt'],'submit').' '.imageorbutton(@$imgcancel,$GLOBALS['xxCancel'],'cancel','history.go(-1)',TRUE)?></td></tr>
	  </table>
	</form>
<script type="text/javascript">
/* <![CDATA[ */
var checkedfullname=false;
function zipoptional(cntobj){
var cntid=cntobj[cntobj.selectedIndex].value;
if(cntid==85 || cntid==91 || cntid==154 || cntid==200)return true; else return false;
}
function stateoptional(cntobj){
var cntid=cntobj[cntobj.selectedIndex].value;
if(false<?php
$result=ect_query('SELECT countryID FROM countries WHERE countryEnabled<>0 AND loadStates<0') or ect_error();
while($rs=ect_fetch_assoc($result)) print '||cntid==' . $rs['countryID'];
ect_free_result($result);
?>)return true; else return false;
}
function checkform(frm)
{
<?php if(trim(@$extraorderfield1)!='' && @$extraorderfield1required==true){ ?>
if(frm.ordextra1.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $extraorderfield1)?>\".");
	frm.ordextra1.focus();
	return (false);
}
<?php } ?>
if(frm.name.value==""||frm.name.value=="<?php print $GLOBALS['xxFirNam']?>"){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . @$usefirstlastname ? $GLOBALS['xxFirNam'] : $GLOBALS['xxName'])?>\".");
	frm.name.focus();
	return (false);
}
<?php	if(@$usefirstlastname){ ?>
if(frm.lastname.value==""||frm.lastname.value=="<?php print $GLOBALS['xxLasNam']?>"){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxLasNam'])?>\".");
	frm.lastname.focus();
	return (false);
}
<?php	}else{ ?>
gotspace=false;
var checkStr = frm.name.value;
for (i = 0; i < checkStr.length; i++){
	if(checkStr.charAt(i)==" ")
		gotspace=true;
}
if(!checkedfullname && !gotspace){
	alert("<?php print jscheck($GLOBALS['xxFulNam'] . ' "' . $GLOBALS['xxName'])?>\".");
	frm.name.focus();
	checkedfullname=true;
	return (false);
}
<?php	} ?>
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
if(stateoptional(document.getElementById('country'))){
	}else if(stateselectordisabled[0]==false){
<?php
	if($hasstates){ ?>
	if(frm.state.selectedIndex==0){
		alert("<?php print jscheck($GLOBALS['xxPlsSlct']) . ' '?>" + document.getElementById('statetxt').innerHTML);
		frm.state.focus();
		return(false);
	}
<?php	} ?>
	}else{
<?php	if($nonhomecountries){ ?>
	if(frm.state2.value==""){
		alert("<?php print jscheck($GLOBALS['xxPlsEntr'])?> \"" + document.getElementById('statetxt').innerHTML + "\".");
		frm.state2.focus();
		return(false);
	}
<?php	} ?>}
if(frm.zip.value=="" && ! zipoptional(document.getElementById('country'))){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxZip'])?>\".");
	frm.zip.focus();
	return(false);
}
if(frm.phone.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $GLOBALS['xxPhone'])?>\".");
	frm.phone.focus();
	return (false);
}
<?php if(trim(@$extraorderfield2)!='' && @$extraorderfield2required==TRUE){ ?>
if(frm.ordextra2.value==""){
	alert("<?php print jscheck($GLOBALS['xxPlsEntr'] . ' "' . $extraorderfield2)?>\".");
	frm.ordextra2.focus();
	return (false);
}
<?php } ?>
return (true);
}
<?php if(@$termsandconditions==TRUE){ ?>
function showtermsandconds(){
newwin=window.open("termsandconditions.php","Terms","menubar=no, scrollbars=yes, width=420, height=380, directories=no,location=no,resizable=yes,status=no,toolbar=no");
}
<?php } ?>
var savestate=0;
var ssavestate=0;
function dosavestate(shp){
	thestate = eval('document.forms.mainform.'+shp+'state');
	eval(shp+'savestate = thestate.selectedIndex');
}
function checkoutspan(shp){
	document.getElementById(shp+'zipstar').style.display=(zipoptional(document.getElementById(shp+'country'))?'none':'');
	document.getElementById(shp+'statestar').style.display=(stateoptional(document.getElementById(shp+'country'))?'none':'');<?php
	if($hasstates){
		print "thestate=document.getElementById(shp+'state');\r\n";
		print "dynamiccountries(document.getElementById(shp+'country'),shp);\r\n";
	}
	print "if(stateselectordisabled[shp=='s'?1:0]==false&&!stateoptional(document.getElementById(shp+'country'))){\r\n";
	print "if(document.getElementById(shp+'state2'))document.getElementById(shp+'state2').style.display='none';\r\n";
	if($hasstates){
		print "thestate.disabled=false;\r\n";
		print "eval('thestate.selectedIndex='+shp+'savestate');\r\n";
		print "document.getElementById(shp+'state').style.display='';\r\n";
	} ?>
}else{<?php
	print "if(document.getElementById(shp+'state2'))document.getElementById(shp+'state2').style.display='';\r\n";
	if($hasstates){ ?>
		document.getElementById(shp+'state').style.display='none';
		if(thestate.disabled==false){
		thestate.disabled=true;
		eval(shp+'savestate = thestate.selectedIndex');
		thestate.selectedIndex=0;}
<?php
	} ?>
}}
<?php
	createdynamicstates('SELECT stateAbbrev,stateName,stateName2,stateName3,stateCountryID,countryName FROM states INNER JOIN countries ON states.stateCountryID=countries.countryID WHERE countryEnabled<>0 AND stateEnabled<>0 AND (loadStates=2 OR countryID=' . $origCountryID . ') ORDER BY stateCountryID,' . getlangid('stateName',1048576));
	print "checkoutspan('');setinitialstate('');\r\n";
?>/* ]]> */
</script>
<?php	}elseif((getpost('action')=='createlist' && getpost('listname')!='') || getpost('action')=='deletelist' || getpost('action')=='deleteaddress' || getpost('action')=='doeditaddress' || getpost('action')=='donewaddress'){
			$addID=str_replace("'",'',getpost('theid'));
			if(!is_numeric($addID))$addID=0;
			$ordName=getpost('name');
			$ordLastName=getpost('lastname');
			$ordAddress=getpost('address');
			$ordAddress2=getpost('address2');
			$ordState=getpost('state2');
			if(getpost('state')!='')
				$ordState = getpost('state');
			$ordCity=getpost('city');
			$ordZip=getpost('zip');
			$ordPhone=getpost('phone');
			$ordCountry=getcountryfromid(getpost('country'));
			$ordExtra1=getpost('ordextra1');
			$ordExtra2=getpost('ordextra2');
			$headertext='';
			if(getpost('action')=='createlist' && @$enablewishlists==TRUE){
				$headertext=$GLOBALS['xxLisMan'];
				$listaccess = md5(time() . getpost('listname') . $adminSecret);
				$sSQL = "INSERT INTO customerlists (listName,listOwner,listAccess) VALUES ('" . escape_string(getpost('listname')) . "'," . $_SESSION['clientID'] . ",'" . escape_string($listaccess) . "')";
				ect_query($sSQL) or ect_error();
			}elseif(getpost('action')=='deletelist' && @$enablewishlists==TRUE){
				$headertext=$GLOBALS['xxLisMan'];
				$sSQL = "DELETE FROM customerlists WHERE listID=" . $addID . " AND listOwner=" . $_SESSION['clientID'];
				ect_query($sSQL) or ect_error();
				$sSQL = "DELETE FROM cart WHERE cartListID=" . $addID . " AND cartClientID=" . $_SESSION['clientID'];
				ect_query($sSQL) or ect_error();
			}elseif(getpost('action')=='deleteaddress'){
				$headertext=$GLOBALS['xxAddMan'];
				$sSQL = "DELETE FROM address WHERE addID=" . $addID . " AND addCustID=" . $_SESSION['clientID'];
				ect_query($sSQL) or ect_error();
			}elseif(getpost('action')=='donewaddress'){
				$headertext=$GLOBALS['xxAddMan'];
				$sSQL = "INSERT INTO address (addCustID,addIsDefault,addName,addLastName,addAddress,addAddress2,addCity,addState,addZip,addCountry,addPhone,addExtra1,addExtra2) VALUES (" . $_SESSION['clientID'] . ",0,'" . escape_string($ordName) . "','" . escape_string($ordLastName) . "','" . escape_string($ordAddress) . "','" . escape_string($ordAddress2) . "','" . escape_string($ordCity) . "','" . escape_string($ordState) . "','" . escape_string($ordZip) . "','" . escape_string($ordCountry) . "','" . escape_string($ordPhone) . "','" . escape_string($ordExtra1) . "','" . escape_string($ordExtra2) . "')";
				ect_query($sSQL) or ect_error();
			}elseif(getpost('action')=='doeditaddress'){
				$headertext=$GLOBALS['xxAddMan'];
				$sSQL = "UPDATE address SET addName='" . escape_string($ordName) . "',addLastName='" . escape_string($ordLastName) . "',addAddress='" . escape_string($ordAddress) . "',addAddress2='" . escape_string($ordAddress2) . "',addCity='" . escape_string($ordCity) . "',addState='" . escape_string($ordState) . "',addZip='" . escape_string($ordZip) . "',addCountry='" . escape_string($ordCountry) . "',addPhone='" . escape_string($ordPhone) . "',addExtra1='" . escape_string($ordExtra1) . "',addExtra2='" . escape_string($ordExtra2) . "' WHERE addCustID=" . $_SESSION['clientID'] . " AND addID=" . $addID;
				ect_query($sSQL) or ect_error();
			}
			print '<meta http-equiv="Refresh" content="2; URL=' . $_SERVER['PHP_SELF'] . '">';
?>	  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
		<tr>
          <td class="cobll" width="100%" align="center">
			<p><br /><br /><strong><?php print $headertext?></strong><br /><br /></p>
			<p><br /><?php print $GLOBALS['xxUpdSuc']?><br /><br /><br /><br /></p>
		  </td>
        </tr>
	  </table>
<?php	}else{ ?>
<script type="text/javascript">
/* <![CDATA[ */
var currstate=[];
currstate['ad']='none';
currstate['am']='none';
currstate['gr']='none';
currstate['om']='none';
function showhidesection(sect){
	var elem=document.getElementsByTagName('tr');
	currstate[sect]=currstate[sect]=='none'?'':'none';
	for(var i=0; i<elem.length; i++){
		var classes=elem[i].className;
		if(classes.indexOf(sect+'formrow')!=-1) elem[i].style.display=currstate[sect];
	}
	document.getElementById('sectimage'+sect).src=currstate[sect]=='none'?'images/arrow-down.png':'images/arrow-up.png';
	return false;
}
/* ]]> */</script>
		  <form method="post" name="mainform" action="<?php print $thisaction?>">
			<input type="hidden" name="posted" value="1" />
			<input type="hidden" name="action" value="none" />
			<input type="hidden" name="theid" value="" />
			<table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td class="cobhl cobhdr" align="center" height="34" onclick="showhidesection('ad')"><a href="#"><strong><?php print $GLOBALS['xxAccDet']?></strong></a><a href="#" onclick="return false"><img id="sectimagead" src="images/arrow-down.png" style="float:right;margin-right:15px" /></a></td>
			  </tr>
			  <tr class="adformrow" style="display:none"> 
                <td class="cobll" height="34" align="center">
				  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
<?php		$sSQL = "SELECT clID,clUserName,clActions,clLoginLevel,clPercentDiscount,clEmail,loyaltyPoints FROM customerlogin WHERE clID=" . $_SESSION['clientID'];
			$result=ect_query($sSQL) or ect_error();
			if($rs=ect_fetch_assoc($result)){ $theemail=$rs['clEmail']; $loyaltypointtotal=$rs['loyaltyPoints']; } else $theemail='ACCOUNT DELETED';
			ect_free_result($result);
			$sSQL = "SELECT email,isconfirmed FROM mailinglist WHERE email='" . escape_string($theemail) . "'";
			$result=ect_query($sSQL) or ect_error();
			if($rs=ect_fetch_assoc($result)){ $allowemail=1; $isconfirmed=$rs['isconfirmed']; }else{ $allowemail=0; $isconfirmed=FALSE; }
			ect_free_result($result);
?>
					<tr><td class="cobhl" align="right" width="<?php print (@$nounsubscribe?'50':'20')?>%"><strong><?php print $GLOBALS['xxName']?>:</strong></td>
					<td class="cobll" align="left" width="<?php print (@$nounsubscribe?'50':'30')?>%"><?php print htmlspecials($_SESSION['clientUser'])?></td>
<?php		if(@$nounsubscribe!=TRUE){
				if($mobilebrowser)
					print '</tr><tr>';
				else{ ?>
					<td class="cobll" align="right" width="8%" rowspan="<?php print (@$loyaltypoints!=''?3:2)?>"><?php if(@$noconfirmationemail!=TRUE && $allowemail!=0 && $isconfirmed==0) print $GLOBALS['xxWaiCon']; else print '<input type="checkbox" name="allowemail" value="ON"' . ($allowemail!=0 ? ' checked="checked"' : '') . ' disabled="disabled" />'; ?></td>
<?php			} ?>
					<td class="cobhl" <?php print $mobilebrowser?'align="right"':'align="left" rowspan="'.(@$loyaltypoints!=''?3:2).'"'?>><strong><?php print $GLOBALS['xxAlPrEm']?></strong><br />
					<span style="font-size:10px"><?php print $GLOBALS['xxNevDiv']?></span></td>
<?php			if($mobilebrowser){ ?>
					<td class="cobll" align="left"><?php if(@$noconfirmationemail!=TRUE && $allowemail!=0 && $isconfirmed==0) print $GLOBALS['xxWaiCon']; else print '<input type="checkbox" name="allowemail" value="ON"' . ($allowemail!=0 ? ' checked="checked"' : '') . ' disabled="disabled" />'; ?></td>
<?php			}
			} ?>
					</tr><tr><td class="cobhl" align="right"><strong><?php print $GLOBALS['xxEmail']?>:</strong></td>
					<td class="cobll" align="left"><?php print $theemail?></td>
					</tr>
<?php		if(@$loyaltypoints!=''){ ?>
					<tr><td class="cobhl" align="right"><strong><?php print $GLOBALS['xxLoyPoi']?>:</strong></td>
					<td class="cobll" align="left"><?php print $loyaltypointtotal?></td>
					</tr>
<?php		} ?>
					<tr><td class="cobll" align="left" colspan="<?php print (@$nounsubscribe?'2':'4')?>"><br /><ul><li><?php print $GLOBALS['xxChaAcc']?> <a class="ectlink" href="javascript:editaccount()"><strong><?php print $GLOBALS['xxClkHere']?></strong></a>.</li></ul></td>
					</tr>
				  </table>
				</td>
			  </tr>
<?php		// Address Management
?>              <tr> 
                <td class="cobhl cobhdr" align="center" height="34" onclick="showhidesection('am')"><a href="#"><strong><?php print $GLOBALS['xxAddMan']?></strong></a><a href="#" onclick="return false"><img id="sectimageam" src="images/arrow-down.png" style="float:right;margin-right:15px" /></a></td>
			  </tr>
			  <tr class="amformrow" style="display:none"> 
                <td class="cobll" height="34" align="center">
				  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
<?php		$sSQL = "SELECT addID,addIsDefault,addName,addLastName,addAddress,addAddress2,addState,addCity,addZip,addPhone,addCountry FROM address WHERE addCustID=" . $_SESSION['clientID'] . " ORDER BY addIsDefault";
			$result=ect_query($sSQL) or ect_error();
			if(ect_num_rows($result)>0){
				while($rs=ect_fetch_assoc($result)){
					print '<tr><td width="50%" class="cobll" align="left">' . htmlspecials(trim($rs['addName'].' '.$rs['addLastName'])) . "<br />" . htmlspecials($rs['addAddress']) . (trim($rs['addAddress2'])!='' ? '<br />' . htmlspecials($rs['addAddress2']) : '') . "<br /> " . htmlspecials($rs['addCity']) . ", " . htmlspecials($rs['addState']) . ($rs['addZip']!='' ? '<br />' . htmlspecials($rs['addZip']) : '') . '<br />' . htmlspecials($rs['addCountry']) . '</td>';
					print '<td class="cobhl" align="left"><ul><li><a class="ectlink" href="javascript:editaddress(' . $rs['addID'] . ')">' . $GLOBALS['xxEdAdd'] . '</a><br /><br /></li><li><a class="ectlink" href="javascript:deleteaddress(' . $rs['addID'] . ')">' . $GLOBALS['xxDeAdd'] . '</a></li></ul></td></tr>';
				}
			}else{
				print '<tr><td class="cobll" align="center" colspan="2" height="34">' . $GLOBALS['xxNoAdd'] . '</td></tr>';
			}
			ect_free_result($result);
?>
					<tr><td class="cobhl" colspan="2" align="left"><br /><ul><li><?php print $GLOBALS['xxPCAdd']?> <a class="ectlink" href="javascript:newaddress()"><strong><?php print $GLOBALS['xxClkHere']?></strong></a>.</li></ul></td></tr>
				  </table>
				</td>
			  </tr>
<?php		// Gift Registry Management
			if(@$enablewishlists==TRUE){
?>			  <tr>
                <td class="cobhl cobhdr" align="center" height="34" onclick="showhidesection('gr')"><a href="#"><strong><?php print $GLOBALS['xxLisMan']?></strong></a><a href="#" onclick="return false"><img id="sectimagegr" src="images/arrow-down.png" style="float:right;margin-right:15px" /></a></td>
			  </tr>
			  <tr class="grformrow" style="display:none"> 
                <td class="cobll" height="34" align="center">
				  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
<?php			$sSQL = "SELECT listID,listName,listAccess FROM customerlists WHERE listOwner=" . $_SESSION['clientID'] . " ORDER BY listName";
				$result=ect_query($sSQL) or ect_error();
				if(ect_num_rows($result)>0){
					while($rs=ect_fetch_assoc($result)){
						$numitems=0;
						$sSQL = "SELECT COUNT(*) AS numitems FROM cart WHERE cartListID=" . $rs['listID'];
						$result2=ect_query($sSQL) or ect_error();
						if($rs2=ect_fetch_assoc($result2))
							if(! is_null($rs2['numitems'])) $numitems=$rs2['numitems'];
						ect_free_result($result2);
						print '<tr><td width="50%" class="cobll" align="left">' . htmlspecials(trim($rs['listName'])) . ' (' . $numitems . ')<br /></td>';
						print '<td class="cobhl" align="left"><ul><li><a class="ectlink" href="javascript:deletelist(' . $rs['listID'] . ')">' . $GLOBALS['xxDelGRe'] . '</a></li>';
						if($numitems>0) print '<li><a href="cart.php?mode=sc&lid=' . $rs['listID'] . '">' . $GLOBALS['xxVieGRe'] . '</a></li>';
						print '</ul></td></tr>';
						print '<tr><td colspan="2" class="cobll" align="left">' . $GLOBALS['xxPubAcc'] . ':<br />' . $storeurl . 'cart.php?pli=' . $rs['listID'] . '&pla=' . $rs['listAccess'] . '</td></tr>';
					}
				}else
					print '<tr><td class="cobll" align="center" colspan="2" height="34">' . $GLOBALS['xxNoGRe'] . '</td></tr>';
				ect_free_result($result);
?>					<tr><td class="cobhl" align="right" width="50%"><input type="text" name="listname" size="40" maxlength="50" /></td><td class="cobhl"><?php print imageorbutton(@$imgcreatelist,'Create New List','createlist','createlist()',TRUE)?></td></tr>
				  </table>
				</td>
			  </tr>
<?php		}
			// Order Management
?>			  <tr> 
                <td class="cobhl cobhdr" align="center" height="34" onclick="showhidesection('om')"><a href="#"><strong><?php print $GLOBALS['xxOrdMan']?></strong></a><a href="#" onclick="return false"><img id="sectimageom" src="images/arrow-down.png" style="float:right;margin-right:15px" /></a></td>
			  </tr>
			  <tr class="omformrow" style="display:none"> 
                <td class="cobll" height="34" align="center">
				  <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
<?php		$hastracknum=FALSE;
			$sSQL = "SELECT ordID FROM orders WHERE ordClientID=" . $_SESSION['clientID'] . " AND ordTrackNum<>''";
			$result=ect_query($sSQL) or ect_error();
			if(ect_num_rows($result)>0) $hastracknum=TRUE;
			ect_free_result($result); ?>
					<tr><td class="cobhl"><?php print $GLOBALS['xxOrdId']?></td>
					<td class="cobhl"><?php print $GLOBALS['xxDate']?></td>
					<td class="cobhl"><?php print $GLOBALS['xxStatus']?></td>
<?php		if($hastracknum) print '<td class="cobhl">' . $GLOBALS['xxTraNum'] . '</td>'; ?>
					<td class="cobhl"><?php print $GLOBALS['xxGndTot']?></td>
					<td class="cobhl"><?php print $GLOBALS['xxCODets']?></td></tr>			
<?php
			$sSQL = "SELECT ordID,ordDate,ordTrackNum,ordTotal,ordStateTax,ordCountryTax,ordShipping,ordHSTTax,ordHandling,ordDiscount," . getlangid('statPublic',64) . " FROM orders LEFT OUTER JOIN orderstatus ON orders.ordStatus=orderstatus.statID WHERE ordClientID=" . $_SESSION['clientID'] . " ORDER BY ordDate";
			$result=ect_query($sSQL) or ect_error();
			if(ect_num_rows($result)>0){
				while($rs=ect_fetch_assoc($result)){
					print '<tr><td class="cobll">' . $rs['ordID'] . '</td>';
					print '<td class="cobll">' . date($dateformatstr, strtotime($rs['ordDate'])) . '</td>';
					print '<td class="cobll">' . $rs[getlangid("statPublic",64)] . '</td>';
					if($hastracknum) print '<td class="cobll">' . ($rs['ordTrackNum']!=''?$rs['ordTrackNum']:'&nbsp;') . '</td>';
					print '<td class="cobll">' . FormatEuroCurrency(($rs['ordTotal']+$rs['ordStateTax']+$rs['ordCountryTax']+$rs['ordShipping']+$rs['ordHSTTax']+$rs['ordHandling'])-$rs['ordDiscount']) . '</td>';
					print '<td class="cobll"><a class="ectlink" href="javascript:vieworder(' . $rs['ordID'] . ')">' . $GLOBALS['xxClkHere'] . '</a></td></tr>';
				}
			}else{
				print '<tr><td class="cobll" colspan="5" height="34" align="center">' . $GLOBALS['xxNoOrd'] . '</td></tr>';
			}
			ect_free_result($result);
?>
				  </table>
				</td>
			  </tr>
			</table>
		  </form>
<script type="text/javascript">
/* <![CDATA[ */
if(document.location.hash=='#ord')showhidesection('om');
else if(document.location.hash=='#list')showhidesection('gr');
else if(document.location.hash=='#add')showhidesection('am');
else if(document.location.hash=='#acct')showhidesection('ad');
/* ]]> */</script>
<?php	}
	} ?>
