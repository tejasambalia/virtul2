<?php
//This code is copyright (c) Internet Business Solutions SL, all rights reserved.
//The contents of this file are protected under law as the intellectual property of Internet
//Business Solutions SL. Any use, reproduction, disclosure or copying of any kind 
//without the express and written permission of Internet Business Solutions SL is forbidden.
//Author: Vince Reid, vince@virtualred.net
global $incfunctionsdefined,$alreadygotadmin,$forceloginonhttps,$storeurl,$thesessionid;
$path_parts = pathinfo(@$_SERVER['PHP_SELF']);
if($path_parts['dirname']=='/'||$path_parts['dirname']=='\\')$path_parts['dirname']='';
if(trim(@$_POST['sessionid'])!='')
	$thesessionid = trim(@$_POST['sessionid']);
else
	$thesessionid = getsessionid();
$thesessionid = str_replace("'",'',$thesessionid);
$useEuro=false;
$mcgndtot=0;
$totquant=0;
$shipping=0;
$mcdiscounts=0;
if(@$_SESSION['xscountrytax']!='') $xscountrytax=$_SESSION['xscountrytax']; else $xscountrytax=0;
$optPriceDiff=0;
$mcpdtxt='';
if(@$incfunctionsdefined==TRUE){
	$alreadygotadmin = getadminsettings();
	$pageurl=$storeurl;
}else{
	$sSQL = 'SELECT countryLCID,countryCurrency,adminStoreURL FROM admin INNER JOIN countries ON admin.adminCountry=countries.countryID WHERE adminID=1';
	$result=ect_query($sSQL) or ect_error();
	$rs=ect_fetch_assoc($result);
	$adminLocale = $rs['countryLCID'];
	$countryCurrency = $rs['countryCurrency'];
	if(@$orcurrencyisosymbol!='') $countryCurrency=$orcurrencyisosymbol;
	$useEuro = ($countryCurrency=='EUR');
	$pageurl = $rs['adminStoreURL'];
	if((substr(strtolower($pageurl),0,7) != 'http://') && (substr(strtolower($pageurl),0,8) != 'https://'))
		$pageurl = 'http://' . $pageurl;
	if(substr($pageurl,-1) != '/') $pageurl.='/';
	ect_free_result($result);
}
if(@$forceloginonhttps) $pageurl='';
if(@$_POST['mode']=='checkout'){
	if(@$_POST['checktmplogin']!=''){
		$sSQL = "SELECT tmploginname FROM tmplogin WHERE tmploginid='" . escape_string(@$_POST['sessionid']) . "' AND tmploginchk='" . escape_string(@$_POST['checktmplogin']) . "'";
		$result=ect_query($sSQL) or ect_error();
		if($rs=ect_fetch_assoc($result))
			$_SESSION['clientID']=$rs['tmploginname'];
	}else{
		$_SESSION['clientID']=NULL; unset($_SESSION['clientID']);
	}
}
$sSQL = 'SELECT cartID,cartProdID,cartProdName,cartProdPrice,cartQuantity FROM cart WHERE cartCompleted=0 AND ' . getsessionsql();
$result=ect_query($sSQL) or ect_error();
while($rs=ect_fetch_assoc($result)){
	$optPriceDiff=0;
	$mcpdtxt.='<div class="minicartcnt">' . $rs['cartQuantity'] . ' ' . $rs['cartProdName'] . '</div>';
	$sSQL = 'SELECT SUM(coPriceDiff) AS sumDiff FROM cartoptions WHERE coCartID=' . $rs['cartID'];
	$result2=ect_query($sSQL) or ect_error();
	$rs2=ect_fetch_assoc($result2);
	if(! is_null($rs2['sumDiff'])) $optPriceDiff=$rs2['sumDiff'];
	ect_free_result($result2);
	$subtot = (($rs['cartProdPrice']+$optPriceDiff)*(int)$rs['cartQuantity']);
	$totquant+=(int)$rs['cartQuantity'];
	$mcgndtot += $subtot;
}
ect_free_result($result);
?>
      <table class="mincart" width="130" bgcolor="#FFFFFF">
        <tr> 
          <td class="mincart" bgcolor="#F0F0F0" align="center"><span class="ion-ios-cart-outline"></span> 
            &nbsp;<a class="ectlink mincart" href="<?php print $pageurl?>cart.php"><?php print $GLOBALS['xxMCSC']?></a></td>
        </tr>
<?php		if(@$_POST['mode']=='update'){ ?>
		<tr><td class="mincart hidden" bgcolor="#F0F0F0" align="center"><?php print $GLOBALS['xxMainWn']?></td></tr>
<?php		}else{ ?>
        <tr><td class="mincart mincartTop" bgcolor="#F0F0F0" align="center"><?php print '<span class="ectMCquant">('.$totquant.'</span>)' /*. $GLOBALS['xxMCIIC']*/ ?></td></tr>
		<tr><td class="mincart hidden" bgcolor="#F0F0F0"><div class="mcLNitems"><?php print $mcpdtxt?></div></td></tr>
<?php			if($mcpdtxt!='' && @$_SESSION['discounts']!=''&&!@$GLOBALS['nopriceanywhere'])$mcdiscounts=(double)$_SESSION['discounts']; ?>
        <tr class="ecHidDsc"<?php if($mcdiscounts==0) print ' style="display:none"'?>><td class="mincart" bgcolor="#F0F0F0" align="center"><span style="color:#FF0000"><?php print $GLOBALS['xxDscnts'] . ' <span class="mcMCdsct">' . FormatEuroCurrency($mcdiscounts)?></span></span></td></tr>
<?php			if($mcpdtxt!='' && (string)@$_SESSION['xsshipping']!=''){
					$shipping = (double)$_SESSION['xsshipping'];
					if($shipping==0) $showshipping=' style="color:#FF0000;font-weight:bold">'.$GLOBALS['xxFree'].'</span>'; else $showshipping='>'.FormatEuroCurrency($shipping); ?>
        <tr><td class="mincart hidden" bgcolor="#F0F0F0" align="center"><?php print $GLOBALS['xxMCShpE'] . ' <span class="ectMCship"'.$showshipping.'</span>'?></td></tr>
<?php			}
				if($mcpdtxt=='') $xscountrytax=0;
				if(!@$GLOBALS['nopriceanywhere']){ ?>
        <tr><td class="mincart hidden" bgcolor="#F0F0F0" align="center"><?php print $GLOBALS['xxTotal'] . ' <span class="ectMCtot hidden">' . FormatEuroCurrency(($mcgndtot+$shipping+$xscountrytax)-$mcdiscounts)?></span></td></tr>
<?php			}
			} ?>
        <tr><td class="mincart hidden" bgcolor="#F0F0F0" align="center"><span style="font-family:Verdana">&raquo;</span> <a class="ectlink mincart" href="<?php print $pageurl?>cart.php"><strong><?php print $GLOBALS['xxMCCO']?></strong></a></td></tr>
      </table>