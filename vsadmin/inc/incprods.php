<?php
//This code is copyright (c) Internet Business Solutions SL, all rights reserved.
//The contents of this file are protected under law as the intellectual property of Internet
//Business Solutions SL. Any use, reproduction, disclosure or copying of any kind 
//without the express and written permission of Internet Business Solutions SL is forbidden.
//Author: Vince Reid, vince@virtualred.net
if(@$storesessionvalue=="") $storesessionvalue="virtualstore".time();
if(@$_SESSION['loggedon']!=$storesessionvalue||@$disallowlogin==TRUE||!@$GLOBALS['incfunctionsdefined']) exit;
$allcoupon=$pidlist=$rid='';
if(@$dateadjust=='') $dateadjust=0;
if(@$dateformatstr=='') $dateformatstr='m/d/Y';
$admindatestr='Y-m-d';
if(@$admindateformat=='') $admindateformat=0;
if(@$maxprodoptions=='')$maxprodoptions=15;
if($admindateformat==1)
	$admindatestr='m/d/Y';
elseif($admindateformat==2)
	$admindatestr='d/m/Y';
$resultcounter=0;
$dynamicadminmenus=TRUE;
if(strtolower($adminencoding)=='iso-8859-1') $raquo='»'; else $raquo='>';
function writemenulevel($id,$itlevel){
	global $allcatsa,$numcats,$thecat,$raquo;
	if($itlevel<10){
		for($wmlindex=0; $wmlindex < $numcats; $wmlindex++){
			if($allcatsa[$wmlindex]['topSection']==$id){
				print "<option value='" . $allcatsa[$wmlindex]['sectionID'] . "'";
				if($thecat==$allcatsa[$wmlindex]['sectionID']) print ' selected="selected">'; else print ">";
				for($index=0; $index < $itlevel-1; $index++)
					print $raquo . ' ';
				print htmldisplay($allcatsa[$wmlindex]['sectionWorkingName']) . "</option>\n";
				if($allcatsa[$wmlindex]['rootSection']==0) writemenulevel($allcatsa[$wmlindex]['sectionID'],$itlevel+1);
			}
		}
	}
}
$success=TRUE;
$nprodoptions=0;
$nprodsections=0;
$nalloptions=0;
$nallsections=0;
$nalldropship=0;
$nallmanufacturer=0;
$nprodsections=$nprodsearchcriteria=$nprodoptions=0;
$alreadygotadmin=getadminsettings();
$dorefresh=FALSE;
if(@$maxprodsects=="") $maxprodsects=20;
// $usesshipweight=($shipType==2 || $shipType==3 || $shipType==4 || $shipType==6 || $shipType==7 || $adminIntShipping==2 || $adminIntShipping==3 || $adminIntShipping==4 || $adminIntShipping==6 || $adminIntShipping==7);
$usesshipweight=TRUE;
$usesflatrate=($shipType==1 || $adminIntShipping==1);
if(@$htmlemails==TRUE) $emlNl='<br />'; else $emlNl="\n";
function dodeleteprod($pid){
	$sSQL="DELETE FROM pricebreaks WHERE pbProdID='" . escape_string($pid) . "'";
	ect_query($sSQL) or ect_error();
	$sSQL="DELETE FROM cpnassign WHERE cpaType=2 AND cpaAssignment='" . escape_string($pid) . "'";
	ect_query($sSQL) or ect_error();
	$sSQL="DELETE FROM products WHERE pID='" . escape_string($pid) . "'";
	ect_query($sSQL) or ect_error();
	$sSQL="DELETE FROM prodoptions WHERE poProdID='" . escape_string($pid) . "'";
	ect_query($sSQL) or ect_error();
	$sSQL="DELETE FROM multisections WHERE pID='" . escape_string($pid) . "'";
	ect_query($sSQL) or ect_error();
	$sSQL="DELETE FROM multisearchcriteria WHERE mSCpID='" . escape_string($pid) . "'";
	ect_query($sSQL) or ect_error();
	$sSQL="DELETE FROM relatedprods WHERE rpProdID='" . escape_string($pid) . "' OR rpRelProdID='" . escape_string($pid) . "'";
	ect_query($sSQL) or ect_error();
	$sSQL="DELETE FROM notifyinstock WHERE nsProdID='" . escape_string($pid) . "' OR nsTriggerProdID='" . escape_string($pid) . "'";
	ect_query($sSQL) or ect_error();
	$sSQL="DELETE FROM productimages WHERE imageProduct='" . escape_string($pid) . "'";
	ect_query($sSQL) or ect_error();
}
function notifyallstock(){
	$allprods='';
	$sSQL='SELECT DISTINCT nsTriggerProdID FROM notifyinstock INNER JOIN products ON notifyinstock.nsTriggerProdID=products.pID WHERE pInStock>0 AND nsOptID=0';
	$resultna=ect_query($sSQL) or ect_error();
	while($rsna=ect_fetch_assoc($resultna)){
		checknotifystock($rsna['nsTriggerProdID']);
	}
	ect_free_result($resultna);
	$sSQL='SELECT DISTINCT nsOptID FROM notifyinstock INNER JOIN options ON notifyinstock.nsOptID=options.optID WHERE optStock>0 AND nsOptID<>0';
	$resultna=ect_query($sSQL) or ect_error();
	while($rsna=ect_fetch_assoc($resultna)){
		checknotifystockoption($rsna['nsOptID']);
	}
	ect_free_result($resultna);
}
function checknotifystockoption($theoid){
	global $notifybackinstock,$storeurl,$htmlemails,$emailAddr,$emlNl,$usepnamefordetaillinks,$detlinkspacechar,$seodetailurls;
	if($GLOBALS['useStockManagement'] && $notifybackinstock){
		$sSQL='SELECT '.getlangid('notifystocksubject',4096).','.getlangid('notifystockemail',4096).' FROM emailmessages WHERE emailID=1';
		$result=ect_query($sSQL) or ect_error();
		if($rs=ect_fetch_assoc($result)){
			$oemailsubject=trim($rs[getlangid('notifystocksubject',4096)]);
			$oemailmessage=$rs[getlangid('notifystockemail',4096)];
		}
		ect_free_result($result);
		
		$idlist='';
		$sSQL="SELECT DISTINCT nsProdID FROM notifyinstock INNER JOIN prodoptions ON notifyinstock.nsProdID=prodoptions.poProdID INNER JOIN options ON prodoptions.poOptionGroup=options.optGroup WHERE nsOptID=-1 AND optID=".$theoid;
		$result=ect_query($sSQL) or ect_error();
		while($rs=ect_fetch_assoc($result)){
			$gotall=TRUE;
			$sSQL="SELECT poOptionGroup FROM prodoptions INNER JOIN optiongroup ON prodoptions.poOptionGroup=optiongroup.optGrpID WHERE poProdID='".escape_string($rs['nsProdID'])."'";
			$result2=ect_query($sSQL) or ect_error();
			while($rs2=ect_fetch_assoc($result2)){
				$sSQL="SELECT optID FROM options WHERE optStock>0 AND optGroup=".$rs2['poOptionGroup'];
				$result3=ect_query($sSQL) or ect_error();
				if(ect_num_rows($result3)==0) $gotall=FALSE;
				ect_free_result($result3);
			}
			ect_free_result($result2);
			if($gotall) $idlist.="'".escape_string($rs['nsProdID'])."',";
		}
		ect_free_result($result);
		if($idlist!='') $idlist=substr($idlist,0,-1);
		
		$pStockByOpts=0;
		$sSQL="SELECT pId,pName,pStockByOpts,pStaticPage,pStaticURL,pInStock,nsEmail FROM products INNER JOIN notifyinstock ON products.pID=notifyinstock.nsProdID WHERE nsOptId=".$theoid;
		if($idlist!='') $sSQL.=' OR (nsOptID=-1 AND nsProdID IN ('.$idlist.'))';
		$result=ect_query($sSQL) or ect_error();
		while($rs=ect_fetch_assoc($result)){
			$nspid=$rs['pId'];
			$pName=trim($rs['pName']);
			$pStockByOpts=$rs['pStockByOpts'];
			$pStaticPage=$rs['pStaticPage'];
			$pStaticURL=$rs['pStaticURL'];
			$pInStock=$rs['pInStock'];
			$theemail=$rs['nsEmail'];
			$thelink=$storeurl . getdetailsurl($nspid,$pStaticPage,$pName,$pStaticURL,'','');
			if(@$htmlemails==TRUE && $thelink!='') $thelink='<a href="' . $thelink . '">' . $thelink . '</a>';
			$emailsubject=str_replace('%pid%',trim($nspid),$oemailsubject);
			$emailsubject=str_replace('%pname%',$pName,$emailsubject);
			$emailmessage=str_replace('%pid%',trim($nspid),$oemailmessage);
			$emailmessage=str_replace('%pname%',$pName,$emailmessage);
			$emailmessage=str_replace('%link%',$thelink,$emailmessage);
			$emailmessage=str_replace('%storeurl%',$storeurl,$emailmessage);
			$emailmessage=str_replace('<br />',$emlNl,$emailmessage);
			$emailmessage=str_replace('%nl%',$emlNl,$emailmessage);
			dosendemail($rs['nsEmail'],$emailAddr,'',$emailsubject,$emailmessage);
		}
		ect_free_result($result);
		$sSQL='DELETE FROM notifyinstock WHERE nsOptId='.$theoid;
		if($idlist!='') $sSQL.=' OR (nsOptID=-1 AND nsProdID IN ('.$idlist.'))';
		ect_query($sSQL) or ect_error();
	}
}
function checknotifystock($thepid){
	global $notifybackinstock,$storeurl,$htmlemails,$emailAddr,$emlNl,$usepnamefordetaillinks,$detlinkspacechar,$seodetailurls;
	if($GLOBALS['useStockManagement'] && $notifybackinstock){
		$pStockByOpts=1;
		$sSQL="SELECT pName,pStockByOpts,pStaticPage,pStaticURL,pInStock FROM products WHERE pID='".escape_string($thepid)."'";
		$result=ect_query($sSQL) or ect_error();
		if($rs=ect_fetch_assoc($result)){
			$pName=trim($rs['pName']);
			$pStockByOpts=$rs['pStockByOpts'];
			$pStaticPage=$rs['pStaticPage'];
			$pStaticURL=$rs['pStaticURL'];
			$pInStock=$rs['pInStock'];
		}
		ect_free_result($result);
		if($pStockByOpts==0&&$pInStock>0){
			$sSQL='SELECT '.getlangid('notifystocksubject',4096).','.getlangid('notifystockemail',4096).' FROM emailmessages WHERE emailID=1';
			$result=ect_query($sSQL) or ect_error();
			if($rs=ect_fetch_assoc($result)){
				$emailsubject=trim($rs[getlangid('notifystocksubject',4096)]);
				$emailmessage=$rs[getlangid('notifystockemail',4096)];
			}
			ect_free_result($result);
			$sSQL="SELECT nsEmail,nsProdId FROM notifyinstock WHERE nsTriggerProdID='".escape_string($thepid)."'";
			$result=ect_query($sSQL) or ect_error();
			if($rs=ect_fetch_assoc($result)){
				$nspid=$rs['nsProdId'];
				$thelink=$storeurl . getdetailsurl($nspid,$pStaticPage,$pName,$pStaticURL,'','');
				if(@$htmlemails==TRUE && $thelink!='') $thelink='<a href="' . $thelink . '">' . $thelink . '</a>';
				$emailsubject=str_replace('%pid%',trim($nspid),$emailsubject);
				$emailsubject=str_replace('%pname%',$pName,$emailsubject);
				$emailmessage=str_replace('%pid%',trim($nspid),$emailmessage);
				$emailmessage=str_replace('%pname%',$pName,$emailmessage);
				$emailmessage=str_replace('%link%',$thelink,$emailmessage);
				$emailmessage=str_replace('%storeurl%',$storeurl,$emailmessage);
				$emailmessage=str_replace('<br />',$emlNl,$emailmessage);
				$emailmessage=str_replace('%nl%',$emlNl,$emailmessage);
				do {
					dosendemail($rs['nsEmail'],$emailAddr,'',$emailsubject,$emailmessage);
				} while($rs=ect_fetch_assoc($result));
			}
			ect_free_result($result);
			ect_query("DELETE FROM notifyinstock WHERE nsTriggerProdID='".escape_string($thepid)."'") or ect_error();
		}
	}
}
function getstaticprodcurl($prodid,$prodname,$forcelower,$spacereplace,$removepunctuation,$addextension){
	if(getpost("addprodid")=="prepend") $prodname=$prodid." ".$prodname;
	if(getpost("addprodid")=="append") $prodname.=' '.$prodid;
	$prodname=replaceaccents($prodname);
	$prodname=strip_tags($prodname);
	if($forcelower) $prodname=strtolower($prodname);
	if($spacereplace=="remove") $spacereplace='';
	$prodname=replace($prodname," ",$spacereplace);
	if($removepunctuation){
		$prodname=preg_replace('/&(?:[a-z\d]+|#\d+|#x[a-f\d]+);/i','',$prodname);
		$prodname=preg_replace('/[$&+,\/:;=?@\'"<>#%{}|\\^~\[\]`]/','',$prodname);
	}
	if($spacereplace!=''){
		$prodname=preg_replace('/['.$spacereplace.']{2,}/',$spacereplace,$prodname);
	}
	if($addextension) $prodname.=".php";
	return($prodname);
}
if(@$defaultprodimages=='') $defaultprodimages='prodimages/';
if(getpost('posted')=='1'){
	$pExemptions=0;
	$newid=getpost('newid');
	if(is_array(@$_POST['pExemptions'])){
		foreach(@$_POST['pExemptions'] as $pExemptObj)
			$pExemptions += $pExemptObj;
	}
	if(getpost("act")=="dotablechecks"){
		if(getpost("subact")=="manattr" || getpost("subact")=="fixall"){
			$sSQL="SELECT mSCpID,scID FROM searchcriteria INNER JOIN multisearchcriteria ON searchcriteria.scid=multisearchcriteria.mSCscID INNER JOIN products on multisearchcriteria.mSCpID=products.pID WHERE scGroup=0 AND mSCscID<>pManufacturer";
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result)){
				$sSQL="DELETE FROM multisearchcriteria WHERE mSCscID=".$rs['scID']." AND mSCpID='".escape_string($rs['mSCpID'])."'";
				ect_query($sSQL) or ect_error();
			}
			ect_free_result($result);
		}
		if(getpost("subact")=="mannoexist" || getpost("subact")=="fixall"){
			$sSQL="SELECT pID FROM products LEFT JOIN searchcriteria ON products.pManufacturer=searchcriteria.scid WHERE pManufacturer<>0 AND scName IS NULL";
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result)){
				$sSQL="UPDATE products SET pManufacturer=0 WHERE pID='".escape_string($rs['pID'])."'";
				ect_query($sSQL) or ect_error();
			}
			ect_free_result($result);

			$sSQL="SELECT pID FROM products INNER JOIN searchcriteria ON products.pManufacturer=searchcriteria.scid WHERE scGroup<>0";
			$result=ect_query($sSQL) or ect_error();
			$rs=ect_fetch_assoc($result);
			$sSQL="UPDATE products SET pManufacturer=0 WHERE pID='".escape_string($rs['pID'])."'";
			ect_query($sSQL) or ect_error();
			ect_free_result($result);
		}
		$dorefresh=TRUE;
	}elseif(getpost('act')=='allstk'){
		notifyallstock();
		$dorefresh=TRUE;
	}elseif(getpost('act')=='delete'){
		dodeleteprod(getpost('id'));
		$dorefresh=TRUE;
	}elseif(getpost('act')=='updaterelations'){
		$rid=getpost('rid');
		foreach(@$_POST as $objItem => $objValue){
			if(substr($objItem,0,4)=='updq'){
				$theprodid=substr($objItem, 4);
				$sSQL="DELETE FROM relatedprods WHERE (rpProdID='" . escape_string($rid) . "' AND rpRelProdID='" . escape_string($objValue) . "')";
				if(@$relatedproductsbothways==TRUE) $sSQL.=" OR (rpRelProdID='" . escape_string($rid) . "' AND rpProdID='" . escape_string($objValue) . "')";
				ect_query($sSQL) or ect_error();
				if(@$_POST['updr' . $theprodid]=='1'){
					$sSQL="INSERT INTO relatedprods (rpProdID,rpRelProdID) VALUES ('" . escape_string($rid) . "','" . escape_string($objValue) . "')";
					ect_query($sSQL) or ect_error();
				}
			}
		}
		$dorefresh=TRUE;
	}elseif(getpost('act')=='quickupdate' && getpost("wholedb")!=''){
		if(getpost("wholedb")=="clear")
			ect_query("UPDATE products SET pStaticURL=''") or ect_error();
		else{
			$result=ect_query("SELECT pID,pName FROM products") or ect_error();
			while($rs=ect_fetch_assoc($result)){
				ect_query("UPDATE products SET pStaticURL='".escape_string(getstaticprodcurl($rs['pID'],$rs['pName'],getpost("lcase")=="yes",@$_POST['space'],getpost("punctuation")=="remove",getpost("extension")=="yes"))."' WHERE pID='".escape_string($rs['pID'])."'") or ect_error();
			}
			ect_free_result($result);
		}
		$dorefresh=TRUE;
	}elseif(getpost('act')=='quickupdate'){
		$attrgroup=-1;
		if(getpost('currentattribute')!=''){
			$result=ect_query("SELECT scGroup FROM searchcriteria WHERE scID=".getpost('currentattribute'));
			if($rs=ect_fetch_assoc($result)) $attrgroup=$rs['scGroup'];
			ect_free_result($result);
		}
		foreach(@$_POST as $objItem => $objValue){
			if(substr($objItem, 0, 4)=='pra_'){
				$origid=substr($objItem, 4);
				$theid=str_replace('ect_dot_xzq','.',$origid);
				$theval=trim(unstripslashes($objValue));
				$pract=getpost('pract');
				$sSQL='';
				if($pract=='prn'){
					if($theval!='') $sSQL="UPDATE products SET pName='" . escape_string($theval) . "'";
				}elseif($pract=='prn2'){
					if($theval!='') $sSQL="UPDATE products SET pName2='" . escape_string($theval) . "'";
				}elseif($pract=='prn3'){
					if($theval!='') $sSQL="UPDATE products SET pName3='" . escape_string($theval) . "'";
				}elseif($pract=='pra'&&getpost('currentattribute')!=''){
					if(getpost('prb_' . $origid)=='1'){
						ect_query("INSERT INTO multisearchcriteria (mSCpID,mSCscID) VALUES ('".escape_string($theid)."',".getpost('currentattribute').')');
						if($attrgroup==0) ect_query("UPDATE products SET pManufacturer=".getpost('currentattribute')." WHERE pID='".escape_string($theid)."'") or ect_error();
					}else{
						ect_query("DELETE FROM multisearchcriteria WHERE mSCpID='".escape_string($theid)."' AND mSCscID=".getpost('currentattribute')) or ect_error();
						if($attrgroup==0) ect_query("UPDATE products SET pManufacturer=0 WHERE pManufacturer=".getpost('currentattribute')." AND pID='".escape_string($theid)."'") or ect_error();
					}
				}elseif($pract=='psp'){
					if($theval!='') $sSQL="UPDATE products SET pSearchParams='" . escape_string($theval) . "'";
				}elseif($pract=='pri'){
					if(is_numeric($theval)) $sSQL='UPDATE products SET pPrice=' . $theval;
				}elseif($pract=='wpr'){
					if(is_numeric($theval)) $sSQL='UPDATE products SET pWholesalePrice=' . $theval;
				}elseif($pract=='lpr'){
					if(is_numeric($theval)) $sSQL='UPDATE products SET pListPrice=' . $theval;
				}elseif($pract=='stk'){
					if(is_numeric($theval)) $sSQL='UPDATE products SET pInStock=' . $theval;
				}elseif($pract=='sta'){
					if(is_numeric($theval)) $sSQL='UPDATE products SET pInStock=pInStock+' . $theval;
				}elseif($pract=='del'){
					if($theval=='del') dodeleteprod($theid);
					$sSQL='';
				}elseif($pract=='prw'){
					if(is_numeric($theval)) $sSQL='UPDATE products SET pWeight=' . $theval;
				}elseif($pract=='dip'){
					$sSQL='UPDATE products SET pDisplay=' . (@$_POST['prb_' . $origid]=='1'?'1':'0');
				}elseif($pract=='stp'){
					$sSQL='UPDATE products SET pStaticPage=' . (@$_POST['prb_' . $origid]=='1'?'1':'0');
				}elseif($pract=='stu'){
					$sSQL="UPDATE products SET pStaticURL='" . escape_string($theval) . "'";
				}elseif($pract=='rec'){
					$sSQL='UPDATE products SET pRecommend=' . (@$_POST['prb_' . $origid]=='1'?'1':'0');
				}elseif($pract=='gwr'){
					$sSQL='UPDATE products SET pGiftWrap=' . (@$_POST['prb_' . $origid]=='1'?'1':'0');
				}elseif($pract=='bak'){
					$sSQL='UPDATE products SET pBackOrder=' . (@$_POST['prb_' . $origid]=='1'?'1':'0');
				}elseif($pract=='sku'){
					$sSQL="UPDATE products SET pSKU='" . escape_string($theval) . "'";
				}elseif($pract=='pro'){
					if(is_numeric($theval)) $sSQL='UPDATE products SET pOrder=' . $theval;
				}elseif($pract=='ppt'){
					if(is_numeric($theval)) $sSQL='UPDATE products SET pTax=' . $theval;
				}elseif($pract=='sel'){
					$sSQL='UPDATE products SET pSell=' . (@$_POST['prb_' . $origid]=='1'?'1':'0');
				}elseif($pract=='frs'){
					$ship1=(is_numeric($theval) ? $theval : 0);
					$ship2=(is_numeric(@$_POST['prb_' . $origid]) ? @$_POST['prb_' . $origid] : 0);
					$sSQL='UPDATE products SET pShipping=' . $ship1 . ', pShipping2=' . $ship2;
				}elseif($pract=='ste' || $pract=='cte' || $pract=='she' || $pract=='hae' || $pract=='fse'){
					$fieldnum=1;
					if($pract=='cte') $fieldnum=2;
					if($pract=='she') $fieldnum=4;
					if($pract=='hae') $fieldnum=8;
					if($pract=='fse') $fieldnum=16;
					$result=ect_query("SELECT pExemptions FROM products WHERE pID='".escape_string($theid)."'") or ect_error();
					if($rs=ect_fetch_assoc($result))$theval=$rs['pExemptions']; else $theval=0;
					ect_free_result($result);
					if(@$_POST['prb_' . $origid]=="1")
						$theval |= $fieldnum;
					else{
						$theval &= ~$fieldnum;
					}
					$sSQL='UPDATE products SET pExemptions=' . $theval;
				}elseif($pract=='csu'){
					$result=ect_query("SELECT pID,pName FROM products WHERE pID='".escape_string($theid)."'") or ect_error();
					if($rs=ect_fetch_assoc($result))
						$sSQL="UPDATE products SET pStaticURL='".escape_string(getstaticprodcurl($rs['pID'],$rs['pName'],getpost("lcase")=="yes",@$_POST['space'],getpost("punctuation")=="remove",getpost("extension")=="yes"))."'";
					ect_free_result($result);
				}
				if($sSQL!=''){
					$sSQL.=" WHERE pID='".escape_string($theid)."'";
					ect_query($sSQL) or ect_error();
				}
				if($pract=='stk' || $pract=='sta'){
					if((int)$theval>0)
						checknotifystock($theid);
				}
			}
		}
		$dorefresh=TRUE;
	}elseif(getpost('act')=='domodify'){
		if($newid!=getpost('id')){
			if(strtolower($newid)==strtolower(getpost('id')))
				$success=TRUE;
			else{
				$sSQL="SELECT * FROM products WHERE pID='" . escape_string($newid) . "'";
				$result=ect_query($sSQL) or ect_error();
				$success=(ect_num_rows($result)==0);
				ect_free_result($result);
				if($success){
					ect_query("UPDATE pricebreaks SET pbProdID='" . escape_string($newid) . "' WHERE pbProdID='" . escape_string(getpost('id')) . "'") or ect_error();
					ect_query("UPDATE cpnassign SET cpaAssignment='" . escape_string($newid) . "' WHERE cpaType=2 AND cpaAssignment='" . escape_string(getpost('id')) . "'") or ect_error();
					ect_query("UPDATE relatedprods SET rpProdID='" . escape_string($newid) . "' WHERE rpProdID='" . escape_string(getpost('id')) . "'") or ect_error();
					ect_query("UPDATE relatedprods SET rpRelProdID='" . escape_string($newid) . "' WHERE rpRelProdID='" . escape_string(getpost('id')) . "'") or ect_error();
					ect_query("UPDATE ratings SET rtProdID='" . escape_string($newid) . "' WHERE rtProdID='" . escape_string(getpost('id')) . "'") or ect_error();
				}
			}
		}
		if($success){
			$pOrder=getpost('pOrder');
			if(! is_numeric($pOrder)) $pOrder=0;
			$sSQL='UPDATE products SET ' .
				"pID='" . escape_string($newid) . "', " .
				"pName='" . escape_string(getpost('pName')) . "', " .
				'pSection=' . getpost('psection') . ', ' .
				'pDropship=' . getpost('pDropship') . ', ' .
				'pManufacturer=' . getpost('pManufacturer') . ', ' .
				"pSKU='" . escape_string(getpost('pSKU')) . "', " .
				'pOrder=' . $pOrder . ', ' .
				'pExemptions=' . $pExemptions . ', ' .
				"pSearchParams='" . escape_string(getpost('pSearchParams')) . "', " .
				"pTitle='" . escape_string(getpost('pTitle')) . "', " .
				"pMetaDesc='" . escape_string(getpost('pMetaDesc')) . "', " .
				"pDescription='" . escape_string(getpost('pDescription')) . "', " .
				"pLongDescription='" . escape_string(getpost('pLongDescription')) . "', ";
			for($index=2; $index <= $adminlanguages+1; $index++){
				if(($adminlangsettings & 1)==1) $sSQL.="pName" . $index . "='" . escape_string(getpost('pName' . $index)) . "', ";
				if(($adminlangsettings & 2)==2) $sSQL.="pDescription" . $index . "='" . escape_string(getpost('pDescription' . $index)) . "', ";
				if(($adminlangsettings & 4)==4) $sSQL.="pLongDescription" . $index . "='" . escape_string(getpost('pLongDescription' . $index)) . "', ";
			}
			$sSQL.='pDisplay='.(getpost('pDisplay')=='ON'?1:0).',';
			if(@$perproducttaxrate==TRUE) $sSQL.='pTax=' . getpost('pTax') . ',';
			if(is_numeric(getpost('inStock')) && getpost('stocksetting')=='1') $sSQL.='pInStock=' . getpost('inStock') . ',';
			$sSQL.='pStockByOpts=' . (getpost('pStockByOpts')=='1' ? 1 : 0) . ',' .
				'pStaticPage=' . (getpost('pStaticPage')=='1' ? 1 : 0) . ',' .
				"pStaticURL='" . escape_string(getpost('pStaticURL')) . "'," .
				'pRecommend=' . (getpost('pRecommend')=='1' ? 1 : 0) . ',' .
				'pGiftWrap=' . (getpost('pGiftWrap')=='1' ? 1 : 0) . ',' .
				'pBackOrder=' . (getpost('pBackOrder')=='1' ? 1 : 0) . ',' .
				'pSell=' . (getpost('pSell')=='ON' ? 1 : 0) . ',';
			if(($adminUnits & 12) > 0) $sSQL.="pDims='" . getpost('plen') . 'x' . getpost('pwid') . 'x' . getpost('phei') . "',";
			if(@$digidownloads==TRUE) $sSQL.="pDownload='" . escape_string(getpost('pDownload')) . "',";
			$sSQL.='pShipping=' . (is_numeric(getpost('pShipping'))?getpost('pShipping'):0) . ',' .
				'pShipping2=' . (is_numeric(getpost('pShipping2'))?getpost('pShipping2'):0) . ',' .
				'pWeight=' . (is_numeric(getpost('pWeight'))?getpost('pWeight'):0) . ',' .
				'pWholesalePrice=' . (is_numeric(getpost('pWholesalePrice'))?getpost('pWholesalePrice'):0) . ',' .
				'pListPrice=' . (is_numeric(getpost('pListPrice'))?getpost('pListPrice'):0) . ',';
			if(strpos(@$detailpagelayout,'custom1')!==FALSE) $sSQL.="pCustom1='" . escape_string(getpost("pCustom1")) . "',";
			if(strpos(@$detailpagelayout,'custom2')!==FALSE) $sSQL.="pCustom2='" . escape_string(getpost("pCustom2")) . "',";
			if(strpos(@$detailpagelayout,'custom3')!==FALSE) $sSQL.="pCustom3='" . escape_string(getpost("pCustom3")) . "',";
			$sSQL.="pDateAdded='" . (getpost('pDateAdded')!=''?date('Y-m-d', parsedate(getpost('pDateAdded'))):date('Y-m-d', time()-86400)) . "',";
			$sSQL.='pPrice=' . getpost('pPrice') . " WHERE pID='" . escape_string(getpost('id')) . "'";
			ect_query($sSQL) or ect_error();
			if(is_numeric(getpost('inStock'))){
				if((int)getpost('inStock')>0) checknotifystock($newid);
			}
			$dorefresh=TRUE;
		}else
			$errmsg=$yyPrDup;
	}elseif(getpost('act')=='doaddnew'){
		$sSQL="SELECT * FROM products WHERE pID='" . escape_string($newid) . "'";
		$result=ect_query($sSQL) or ect_error();
		$success=(ect_num_rows($result)==0);
		ect_free_result($result);
		if($success){
			$pOrder=getpost('pOrder');
			if(! is_numeric($pOrder)) $pOrder=0;
			$sSQL="INSERT INTO products (pID,pName,pDateAdded,pSection,pDropship,pManufacturer,pSKU,pOrder,pExemptions,pSearchParams,pTitle,pMetaDesc,pCustom1,pCustom2,pCustom3,pDescription,pLongDescription,";
			for($index=2; $index <= $adminlanguages+1; $index++){
				if(($adminlangsettings & 1)==1) $sSQL.='pName' . $index . ',';
				if(($adminlangsettings & 2)==2) $sSQL.='pDescription' . $index . ',';
				if(($adminlangsettings & 4)==4) $sSQL.='pLongDescription' . $index . ',';
			}
			$sSQL.='pPrice,pWholesalePrice,pListPrice,pShipping,pShipping2,pDisplay,';
			if(@$perproducttaxrate==TRUE) $sSQL.='pTax,';
			if(is_numeric(getpost('inStock'))) $sSQL.='pInStock,';
			if(($adminUnits & 12) > 0) $sSQL.='pDims,';
			if(@$digidownloads==TRUE) $sSQL.='pDownload,';
			$sSQL.='pStockByOpts,pStaticPage,pStaticURL,pRecommend,pGiftWrap,pBackOrder,pSell,pWeight) VALUES (';
						$sSQL.="'" . escape_string($newid) . "',";
						$sSQL.="'" . escape_string(getpost('pName')) . "',";
						if(getpost('pDateAdded')!='')
							$sSQL.="'" . date('Y-m-d', parsedate(getpost('pDateAdded'))) . "',";
						else
							$sSQL.="'" . date('Y-m-d', time()) . "',";
						$sSQL.=getpost('psection') . ',';
						$sSQL.=getpost('pDropship') . ',';
						$sSQL.=getpost('pManufacturer') . ',';
						$sSQL.="'" . escape_string(getpost('pSKU')) . "',";
						$sSQL.=$pOrder . ",";
						$sSQL.=$pExemptions . ",";
						$sSQL.="'" . escape_string(getpost('pSearchParams')) . "',";
						$sSQL.="'" . escape_string(getpost('pTitle')) . "',";
						$sSQL.="'" . escape_string(getpost('pMetaDesc')) . "',";
						$sSQL.="'" . escape_string(getpost('pCustom1')) . "',";
						$sSQL.="'" . escape_string(getpost('pCustom2')) . "',";
						$sSQL.="'" . escape_string(getpost('pCustom3')) . "',";
						$sSQL.="'" . escape_string(getpost('pDescription')) . "',";
						$sSQL.="'" . escape_string(getpost('pLongDescription')) . "',";
						for($index=2; $index <= $adminlanguages+1; $index++){
							if(($adminlangsettings & 1)==1) $sSQL.="'" . escape_string(getpost('pName' . $index)) . "',";
							if(($adminlangsettings & 2)==2) $sSQL.="'" . escape_string(getpost('pDescription' . $index)) . "',";
							if(($adminlangsettings & 4)==4) $sSQL.="'" . escape_string(getpost('pLongDescription' . $index)) . "',";
						}
						$sSQL.=getpost('pPrice') . ',';
						if(getpost('pWholesalePrice')!='')
							$sSQL.=getpost('pWholesalePrice') . ',';
						else
							$sSQL.='0,';
						if(getpost('pListPrice')!='')
							$sSQL.=getpost('pListPrice') . ',';
						else
							$sSQL.='0,';
						if(! is_numeric(getpost('pShipping')))
							$sSQL.='0,';
						else
							$sSQL.=getpost('pShipping') . ',';
						if(! is_numeric(getpost('pShipping2')))
							$sSQL.='0,';
						else
							$sSQL.=getpost('pShipping2') . ',';
						if(getpost('pDisplay')=='ON')
							$sSQL.='1,';
						else
							$sSQL.='0,';
						if(@$perproducttaxrate==TRUE) $sSQL.="'" . getpost('pTax') . "',";
						if($GLOBALS['useStockManagement'] && is_numeric(getpost('inStock')))
							$sSQL.=getpost('inStock') . ',';
						if(($adminUnits & 12) > 0)
							$sSQL.="'" . getpost('plen') . 'x' . getpost('pwid') . 'x' . getpost('phei') . "',";
						if(@$digidownloads==TRUE)
							$sSQL.="'" . escape_string(getpost('pDownload')) . "',";
						$sSQL.=(getpost('pStockByOpts')=='1' ? 1 : 0) . ',';
						$sSQL.=(getpost('pStaticPage')=='1' ? 1 : 0) . ',' .
							"'" . escape_string(getpost('pStaticURL')) . "',";
						$sSQL.=(getpost('pRecommend')=='1' ? 1 : 0) . ',';
						$sSQL.=(getpost('pGiftWrap')=='1' ? 1 : 0) . ',';
						$sSQL.=(getpost('pBackOrder')=='1' ? 1 : 0) . ',';
						$sSQL.=(getpost('pSell')=='ON' ? 1 : 0) . ',';
						if(is_numeric(getpost('pWeight'))) $sSQL.=getpost('pWeight'); else $sSQL.='0';
						$sSQL.=')';
			ect_query($sSQL) or ect_error();
			$dorefresh=TRUE;
		}else
			$errmsg=$yyPrDup;
	}elseif(getpost('act')=="dodiscounts"){
		$sSQL="INSERT INTO cpnassign (cpaCpnID,cpaType,cpaAssignment) VALUES (" . getpost('assdisc') . ",2,'" . getpost('id') . "')";
		ect_query($sSQL) or ect_error();
		$dorefresh=TRUE;
	}elseif(getpost('act')=="deletedisc"){
		$sSQL="DELETE FROM cpnassign WHERE cpaID=" . getpost('id');
		ect_query($sSQL) or ect_error();
		$dorefresh=TRUE;
	}
	if($success && (getpost('act')=='domodify' || getpost('act')=='doaddnew')){
		$maximgindex=(int)getpost('maximgindex');
		if(getpost('act')=='domodify') ect_query("DELETE FROM productimages WHERE imageProduct='" . escape_string(getpost('id')) . "'") or ect_error();
		for($index=0; $index<=$maximgindex; $index++){
			if(@$_POST['smim' . $index]!='') ect_query("INSERT INTO productimages (imageProduct,imageSrc,imageNumber,imageType) VALUES ('" . escape_string($newid) . "','" . escape_string(@$_POST['smim' . $index]) . "'," . $index . ",0)") or ect_error();
			if(@$_POST['lgim' . $index]!='') ect_query("INSERT INTO productimages (imageProduct,imageSrc,imageNumber,imageType) VALUES ('" . escape_string($newid) . "','" . escape_string(@$_POST['lgim' . $index]) . "'," . $index . ",1)") or ect_error();
			if(@$_POST['gtim' . $index]!='') ect_query("INSERT INTO productimages (imageProduct,imageSrc,imageNumber,imageType) VALUES ('" . escape_string($newid) . "','" . escape_string(@$_POST['gtim' . $index]) . "'," . $index . ",2)") or ect_error();
		}
		ect_query("DELETE FROM prodoptions WHERE poProdID='".escape_string(getpost("id"))."' OR poProdID='".escape_string($newid)."'") or ect_error();
		ect_query("DELETE FROM multisections WHERE pID='".escape_string(getpost("id"))."' OR pID='".escape_string($newid)."'") or ect_error();
		ect_query("DELETE FROM multisearchcriteria WHERE mSCpID='".escape_string(getpost("id"))."' OR mSCpID='".escape_string($newid)."'") or ect_error();
		if(getpost("pManufacturer")!="0"){
			$sSQL="INSERT INTO multisearchcriteria (mSCpID,mSCscID) VALUES ('".escape_string($newid)."',".getpost("pManufacturer").")";
			ect_query($sSQL) or ect_error();
		}
		for($rowcounter=0;$rowcounter<100;$rowcounter++){
			if(getpost('poption'.$rowcounter)!='' && getpost('poption'.$rowcounter)!='0'){
				$sSQL="INSERT INTO prodoptions (poProdID,poOptionGroup) VALUES ('".escape_string($newid)."',".getpost('poption'.$rowcounter).')';
				ect_query($sSQL) or ect_error();
			}
			if(getpost('psection'.$rowcounter)!='' && getpost('psection'.$rowcounter)!='0' && getpost('psection')!=getpost('psection'.$rowcounter)){
				$sSQL="SELECT pID FROM multisections WHERE pID='" . escape_string($newid) . "' AND pSection=".getpost('psection'.$rowcounter);
				$result=ect_query($sSQL) or ect_error();
				if(ect_num_rows($result)==0){
					$sSQL="INSERT INTO multisections (pID,pSection) VALUES ('".escape_string($newid)."',".getpost('psection'.$rowcounter).')';
					ect_query($sSQL) or ect_error();
				}
				ect_free_result($result);
			}
			if(getpost('psearch'.$rowcounter)!='' && getpost('psearch'.$rowcounter)!='0'){
				$sSQL="SELECT mSCpID FROM multisearchcriteria WHERE mSCpID='" . escape_string($newid) . "' AND mSCscID=".getpost('psearch'.$rowcounter);
				$result=ect_query($sSQL) or ect_error();
				if(ect_num_rows($result)==0){
					$sSQL="INSERT INTO multisearchcriteria (mSCpID,mSCscID) VALUES ('".escape_string($newid)."',".getpost('psearch'.$rowcounter).')';
					ect_query($sSQL) or ect_error();
				}
				ect_free_result($result);
			}
		}
	}
	if(getpost('act')=='modify' || getpost('act')=='clone' || getpost('act')=='addnew'){
		if(getpost('act')=='modify' || getpost('act')=='clone'){
			$sSQL="SELECT poID,poOptionGroup,optGrpWorkingName FROM prodoptions INNER JOIN optiongroup ON prodoptions.poOptionGroup=optiongroup.optGrpID WHERE poProdID='" . escape_string(getpost('id')) . "' ORDER BY poID";
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result))
				$prodoptions[$nprodoptions++]=$rs;
			ect_free_result($result);
			$sSQL="SELECT pSection,sectionWorkingName FROM multisections INNER JOIN sections ON multisections.pSection=sections.sectionID WHERE pID='" . escape_string(getpost('id')) . "'";
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result))
				$prodsections[$nprodsections++]=$rs;
			ect_free_result($result);
			$sSQL="SELECT scID,scWorkingName FROM multisearchcriteria INNER JOIN searchcriteria ON multisearchcriteria.mSCscID=searchcriteria.scID WHERE scGroup<>0 AND mSCpID='".escape_string(getpost('id'))."' ORDER BY scGroup,scOrder";
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result))
				$prodsearchcriteria[$nprodsearchcriteria++]=$rs;
			ect_free_result($result);
		}
		$sSQL="SELECT dsID,dsName FROM dropshipper ORDER BY dsName";
		$result=ect_query($sSQL) or ect_error();
		while($rs=ect_fetch_assoc($result))
			$alldropship[$nalldropship++]=$rs;
		ect_free_result($result);
	}
}
if($dorefresh){
	print '<meta http-equiv="refresh" content="1; url=adminprods.php';
	print '?rid=' . getpost('rid') . '&disp=' . getpost('disp') . '&stext=' . urlencode(getpost('stext')) . '&sprice=' . urlencode(getpost('sprice')) . '&stype=' . getpost('stype') . '&scat=' . getpost('scat') . '&pg=' . getpost('pg');
	print '">';
}
if(getpost('posted')=="1" && getpost("act")=="tablechecks"){
		$sSQL="SELECT COUNT(*) AS tcount FROM searchcriteria INNER JOIN multisearchcriteria ON searchcriteria.scid=multisearchcriteria.mSCscID INNER JOIN products on multisearchcriteria.mSCpID=products.pID WHERE scGroup=0 AND mSCscID<>pManufacturer";
		$result=ect_query($sSQL) or ect_error();
		$rs=ect_fetch_assoc($result);
		$tcount1=$rs['tcount'];
		ect_free_result($result);
		
		$sSQL="SELECT COUNT(*) AS tcount FROM products LEFT JOIN searchcriteria ON products.pManufacturer=searchcriteria.scid WHERE pManufacturer<>0 AND scName IS NULL";
		$result=ect_query($sSQL) or ect_error();
		$rs=ect_fetch_assoc($result);
		$tcount2=$rs['tcount'];
		ect_free_result($result);
		
		$sSQL="SELECT COUNT(*) AS tcount FROM products INNER JOIN searchcriteria ON products.pManufacturer=searchcriteria.scid WHERE scGroup<>0";
		$result=ect_query($sSQL) or ect_error();
		$rs=ect_fetch_assoc($result);
		$tcount2+=$rs['tcount'];
		ect_free_result($result);
?>
		<form name="mainform" id="mainform" method="post" action="adminprods.php">
			<input type="hidden" name="posted" value="1" />
			<input type="hidden" name="act" value="dotablechecks" />
			<input type="hidden" id="subact" name="subact" value="" />
			<table id="producttable" border="" cellspacing="3" cellpadding="3" style="margin:0 auto;border:1px solid;border-collapse:collapse">
			<tr><td colspan="3" align="center" style="border:1px solid"><div style="font-weight:bold">Products Table Checks</div></td></tr>
			<tr><td style="border:1px solid">Products where manufacturer doesn't match attributes.</td><td style="border:1px solid" align="center"><?php print $tcount1?></td><td style="border:1px solid" align="center"><?php if($tcount1>0) print '<input type="button" value="Fix" onclick="document.getElementById(\'subact\').value=\'manattr\';document.getElementById(\'mainform\').submit()" />'; else print '-'; ?></td></tr>
			<tr><td style="border:1px solid">Products where manufacturer doesn't exist.</td><td style="border:1px solid" align="center"><?php print $tcount2?></td><td style="border:1px solid" align="center"><?php if($tcount2>0) print '<input type="button" value="Fix" onclick="document.getElementById(\'subact\').value=\'mannoexist\';document.getElementById(\'mainform\').submit()" />'; else print '-'; ?></td></tr>
			<tr><td style="border:1px solid" colspan="3" align="center"><input type="button" value="Fix All" onclick="document.getElementById('subact').value='fixall';document.getElementById('mainform').submit()" /> <input type="button" value="Back to Products" onclick="document.location='adminprods.php'" /></td></tr>
			</table>
		</form>
<?php
}elseif(getpost('posted')=="1" && (getpost('act')=="modify" || getpost('act')=="clone" || getpost('act')=="addnew")){
		if(@$htmleditor=='tinymce'){ ?>
<script type="text/javascript" src="tiny_mce.js"></script>
<script type="text/javascript">
/* <![CDATA[ */
	tinyMCE.init({
		theme : "simple",
		mode : "textareas",
		valid_elements : "*[*]",
		extended_valid_elements : "a[class|href|target|name|onclick]," +
			"embed[quality|type|pluginspage|width|height|src|align]," +
			"hr[class|width|size|noshade]," + 
			"img[class|src|border|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]," +
			"object[classid|codebase|width|height|align]," +
			"param[name|value]," +
			"input[checked|class|disabled|id|name|type|value|size|maxlength|src|width|height|readonly|tabindex|onfocus|onblur|onchange|onselect]",
		debug : false
	});
	tinyMCE.addToLang('',{
		plus_desc : 'Plus'
	});
/* ]]> */
</script>
<?php	}elseif(@$htmleditor=='ckeditor'){ ?>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<?php	}elseif(@$htmleditor=='fckeditor'){ ?>
<script type="text/javascript" src="fckeditor.js"></script>
<script type="text/javascript">
/* <![CDATA[ */
function FCKeditor_OnComplete(editorInstance){
	editorInstance.Events.AttachEvent('OnBlur', FCKeditor_OnBlur);
	editorInstance.Events.AttachEvent('OnFocus', FCKeditor_OnFocus);
	editorInstance.ToolbarSet.Collapse();
}
function FCKeditor_OnBlur(editorInstance){
	editorInstance.ToolbarSet.Collapse();
}
function FCKeditor_OnFocus(editorInstance){
	editorInstance.ToolbarSet.Expand();
}
var sBasePath=document.location.pathname.substring(0,document.location.pathname.lastIndexOf('adminprods.php'));
/* ]]> */
</script>
<?php	}
		$maximagenumber=-1;
		$imageindex=0;
		$smimgindx=0;
		$lgimgindx=0;
		$gtimgindx=0;
		$numsmimgs=0;
		$numlgimgs=0;
		$numgtimgs=0;
		function getnext3images(&$smimg,&$lgimg,&$gtimg){
			global $smimgindx,$lgimgindx,$gtimgindx,$numsmimgs,$numlgimgs,$numgtimgs,$allsmimgs,$alllgimgs,$allgtimgs;
			$smimg=''; $lgimg=''; $gtimg='';
			if($smimgindx<$numsmimgs){ $smimg=$allsmimgs[$smimgindx]['imageSrc']; $smimgindx++; }else $smimg='';
			if($lgimgindx>=$numlgimgs){
				if($gtimgindx>=$numgtimgs) $gtimg=''; else{ $gtimg=$allgtimgs[$gtimgindx]['imageSrc']; $gtimgindx++; }
			}elseif($gtimgindx>=$numgtimgs){
				if($lgimgindx>=$numlgimgs) $lgimg=''; else{ $lgimg=$alllgimgs[$lgimgindx]['imageSrc']; $lgimgindx++; }
			}elseif($alllgimgs[$lgimgindx]['imageNumber'] > $allgtimgs[$gtimgindx]['imageNumber']){
				$gtimg=$allgtimgs[$gtimgindx]['imageSrc']; $gtimgindx++;
			}elseif($alllgimgs[$lgimgindx]['imageNumber'] < $allgtimgs[$gtimgindx]['imageNumber']){
				$lgimg=$alllgimgs[$lgimgindx]['imageSrc']; $lgimgindx++;
			}else{
				$lgimg=$alllgimgs[$lgimgindx]['imageSrc']; $lgimgindx++;
				$gtimg=$allgtimgs[$gtimgindx]['imageSrc']; $gtimgindx++;
			}
		}
		function displayimagerow($imgrow,$smimg,$lgimg,$gtimg){
			print '<tr>';
			print '<td><input type="text" name="smim' . $imgrow . '" id="smim' . $imgrow . '" value="' . htmlspecials($smimg) . '" size="30" ' . ($imgrow==0 ? 'onchange="document.getElementById(\'pImage\').value=this.value"' : '') . '/>&nbsp;<input type="button" value="..." onclick="uploadimage(\'smim' . $imgrow . '\')" /></td>';
			print '<td><input type="text" name="lgim' . $imgrow . '" id="lgim' . $imgrow . '" value="' . htmlspecials($lgimg) . '" size="30" ' . ($imgrow==0 ? 'onchange="document.getElementById(\'pLargeImage\').value=this.value"' : '') . '/>&nbsp;<input type="button" value="..." onclick="uploadimage(\'lgim' . $imgrow . '\')" /></td>';
			print '<td><input type="text" name="gtim' . $imgrow . '" id="gtim' . $imgrow . '" value="' . htmlspecials($gtimg) . '" size="30" ' . ($imgrow==0 ? 'onchange="document.getElementById(\'pGiantImage\').value=this.value"' : '') . '/>&nbsp;<input type="button" value="..." onclick="uploadimage(\'gtim' . $imgrow . '\')" /></td>';
			print '</tr>';
		}
		$doaddnew=TRUE;
		if(getpost('act')=='modify' || getpost('act')=='clone'){
			$sSQL="SELECT imageSrc,imageNumber,imageType FROM productimages WHERE imageProduct='" . escape_string(getpost('id')) . "' AND imageType=0 ORDER BY imageNumber";
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result)){
				$allsmimgs[$numsmimgs++]=$rs;
			}
			ect_free_result($result);
			$sSQL="SELECT imageSrc,imageNumber,imageType FROM productimages WHERE imageProduct='" . escape_string(getpost('id')) . "' AND imageType=1 ORDER BY imageNumber";
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result)){
				$alllgimgs[$numlgimgs++]=$rs;
			}
			ect_free_result($result);
			$sSQL="SELECT imageSrc,imageNumber,imageType FROM productimages WHERE imageProduct='" . escape_string(getpost('id')) . "' AND imageType=2 ORDER BY imageNumber";
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result)){
				$allgtimgs[$numgtimgs++]=$rs;
			}
			ect_free_result($result);
			$maximagenumber=max(max($numsmimgs,$numlgimgs),$numgtimgs);
			$sSQL='SELECT pId,pName,pName2,pName3,pSection,pDescription,pDescription2,pDescription3,pPrice,pWholesalePrice,pListPrice,pDisplay,pStaticPage,pStaticURL,pRecommend,pStockByOpts,pSell,pShipping,pShipping2,pWeight,pLongDescription,pLongDescription2,pLongDescription3,pExemptions,pSearchParams,pTitle,pMetaDesc,pCustom1,pCustom2,pCustom3,pInStock,pDims,pTax,pDropship,pManufacturer,pSKU,pOrder,pDateAdded,pGiftWrap,pBackOrder';
			if(@$digidownloads==TRUE) $sSQL.=',pDownload';
			$sSQL.=" FROM products WHERE pId='" . escape_string(getpost('id')) . "'";
			$result=ect_query($sSQL) or ect_error();
			if($alldata=ect_fetch_assoc($result)){
				$doaddnew=FALSE;
				$pId=$alldata['pId'];
				$pName=$alldata['pName'];
				for($index=2; $index <= $adminlanguages+1; $index++){
					$pNames[$index]=$alldata['pName' . $index];
					$pDescriptions[$index]=$alldata['pDescription' . $index];
					$pLongDescriptions[$index]=$alldata['pLongDescription' . $index];
				}
				$pSection=$alldata['pSection'];
				$pDescription=$alldata['pDescription'];
				$pPrice=$alldata['pPrice'];
				$pWholesalePrice=$alldata['pWholesalePrice'];
				$pListPrice=$alldata['pListPrice'];
				$pDisplay=$alldata['pDisplay'];
				$pStaticPage=$alldata['pStaticPage'];
				$pStaticURL=$alldata['pStaticURL'];
				$pRecommend=$alldata['pRecommend'];
				$pStockByOpts=$alldata['pStockByOpts'];
				$pSell=$alldata['pSell'];
				$pShipping=$alldata['pShipping'];
				$pShipping2=$alldata['pShipping2'];
				$pWeight=$alldata['pWeight'];
				$pLongDescription=$alldata['pLongDescription'];
				$pExemptions=$alldata['pExemptions'];
				$pSearchParams=$alldata['pSearchParams'];
				$pTitle=$alldata['pTitle'];
				$pMetaDesc=$alldata['pMetaDesc'];
				$pCustom1=$alldata['pCustom1'];
				$pCustom2=$alldata['pCustom2'];
				$pCustom3=$alldata['pCustom3'];
				$pInStock=$alldata['pInStock'];
				$pDims=$alldata['pDims'];
				$pTax=$alldata['pTax'];
				$pDropship=$alldata['pDropship'];
				$pManufacturer=$alldata['pManufacturer'];
				$pSKU=$alldata['pSKU'];
				$pOrder=$alldata['pOrder'];
				$pDateAdded=$alldata['pDateAdded'];
				$pGiftWrap=$alldata['pGiftWrap'];
				$pBackOrder=$alldata['pBackOrder'];
				if(is_null($pDateAdded) || getpost('act')=='clone') $pDateAdded=date($admindatestr, time() + ($dateadjust*60*60)); else $pDateAdded=date($admindatestr, strtotime($pDateAdded));
				if(@$digidownloads==TRUE) $pDownload=$alldata['pDownload'];
			}
			ect_free_result($result);
		}
		if($doaddnew){
			$pId='';
			$pName='';
			for($index=2; $index <= $adminlanguages+1; $index++){
				$pNames[$index]='';
				$pDescriptions[$index]='';
				$pLongDescriptions[$index]='';
			}
			if(getpost('scat')!='') $pSection=(int)getpost('scat'); else $pSection=0;
			$pSearchParams='';
			$pTitle='';
			$pMetaDesc='';
			$pCustom1=$pCustom2=$pCustom3='';
			$pDescription='';
			$pImage=$defaultprodimages;
			$pPrice='';
			$pWholesalePrice='';
			$pListPrice=0;
			$pDisplay=1;
			$pStaticPage=0;
			$pStaticURL='';
			$pRecommend=0;
			$pStockByOpts=0;
			$pSell=1;
			$pShipping='';
			$pShipping2='';
			$pLargeImage=$defaultprodimages;
			$pGiantImage='';
			$pWeight='';
			$pLongDescription='';
			$pExemptions=0;
			$pInStock='';
			$pDims='';
			$pTax='';
			$pDropship=0;
			$pManufacturer=0;
			$pSKU='';
			$pOrder=0;
			$pDateAdded=date($admindatestr, time() + ($dateadjust*60*60));
			$pGiftWrap=0;
			$pBackOrder=0;
			$pDownload='';
		}
?>
<script type="text/javascript">
/* <![CDATA[ */
var oAR=new Array();
var sAR=new Array();
var cAR=new Array();
<?php
	$sSQL='SELECT optGrpID,optGrpWorkingName,optType FROM optiongroup ORDER BY optGrpWorkingName';
	$nallsearchcriteria=$nallsections=$nalloptions=0;
	$result=ect_query($sSQL) or ect_error();
	while($rs=ect_fetch_assoc($result))
		$alloptions[$nalloptions++]=$rs;
	ect_free_result($result);
	for($rowcounter=0;$rowcounter < $nalloptions;$rowcounter++){
		print 'oAR['.$rowcounter.']=['.$alloptions[$rowcounter]['optGrpID'].",'".jsescape($alloptions[$rowcounter]['optGrpWorkingName'])."',".$alloptions[$rowcounter]['optType']."];\r\n";
	}
	$sSQL='SELECT sectionID,sectionWorkingName FROM sections WHERE rootSection=1 ORDER BY sectionWorkingName';
	$result=ect_query($sSQL) or ect_error();
	while($rs=ect_fetch_assoc($result))
		$allsections[$nallsections++]=$rs;
	ect_free_result($result);
	for($rowcounter=0;$rowcounter < $nallsections;$rowcounter++){
		print 'sAR['.$rowcounter.']=['.jsescape($allsections[$rowcounter]['sectionID']).",'".jsescape($allsections[$rowcounter]['sectionWorkingName'])."'];\r\n";
	}
	$sSQL='SELECT scID,scWorkingName FROM searchcriteria WHERE scGroup<>0 ORDER BY scGroup,scOrder';
	$result=ect_query($sSQL) or ect_error();
	while($rs=ect_fetch_assoc($result))
		$allsearchcriteria[$nallsearchcriteria++]=$rs;
	ect_free_result($result);
	for($rowcounter=0;$rowcounter < $nallsearchcriteria;$rowcounter++){
		print 'cAR['.$rowcounter.']=['.$allsearchcriteria[$rowcounter]['scID'].",'".jsescape($allsearchcriteria[$rowcounter]['scWorkingName'])."'];\r\n";
	}
?>
function checkastring(thestr,validchars){
  for (i=0; i < thestr.length; i++){
    ch=thestr.charAt(i);
    for (j=0;  j < validchars.length;  j++)
      if(ch==validchars.charAt(j))
        break;
    if(j==validchars.length)
	  return(false);
  }
  return(true);
}
function formvalidator(theForm){
  if(theForm.newid.value==""){
    alert("<?php print jscheck($yyPlsEntr . ' "' . $yyPrRef)?>\".");
    theForm.newid.focus();
    return(false);
  }
  if(theForm.psection.options[theForm.psection.selectedIndex].value==""){
    alert("<?php print jscheck($yyPlsSel . ' "' . $yySection)?>\".");
    theForm.psection.focus();
    return(false);
  }
  if(theForm.pName.value==""){
    alert("<?php print jscheck($yyPlsEntr . ' "' . $yyPrNam)?>\".");
    theForm.pName.focus();
    return(false);
  }
<?php	for($index=2; $index <= $adminlanguages+1; $index++){
			if(($adminlangsettings & 1)==1){ ?>
  if(theForm.pName<?php print $index?>.value==""){
	displaymultilangname();
    alert("<?php print jscheck($yyPlsEntr . ' "' . $yyPrNam . ' ' . $index)?>\".");
    theForm.pName<?php print $index?>.focus();
    return(false);
  }
<?php		}
		} ?>
  if(theForm.pPrice.value==""){
    alert("<?php print jscheck($yyPlsEntr . ' "' . $yyPrPri)?>\".");
    theForm.pPrice.focus();
    return(false);
  }
  var checkOK="'\" ";
  var checkStr=theForm.newid.value;
  var allValid=true;
  for (i=0;  i < checkStr.length;  i++){
    ch=checkStr.charAt(i);
    for (j=0;  j < checkOK.length;  j++)
      if(ch==checkOK.charAt(j)){
	    allValid=false;
        break;
	  }
  }
  if(!allValid){
    alert("<?php print jscheck($yyQuoSpa . ' "' . $yyPrRef)?>\".");
    theForm.newid.focus();
    return(false);
  }
  if(!checkastring(theForm.pPrice.value,"0123456789.")){
    alert("<?php print jscheck($yyOnlyDec . ' "' . $yyPrPri)?>\".");
    theForm.pPrice.focus();
    return(false);
  }
  if(!checkastring(theForm.pWholesalePrice.value,"0123456789.")){
    alert("<?php print jscheck($yyOnlyDec . ' "' . $yyWhoPri)?>\".");
    theForm.pWholesalePrice.focus();
    return(false);
  }
  if(!checkastring(theForm.pListPrice.value,"0123456789.")){
    alert("<?php print jscheck($yyOnlyDec . ' "' . $yyListPr)?>\".");
    theForm.pListPrice.focus();
    return(false);
  }
<?php	if(($adminUnits & 12) > 0){ ?>
  var checkOK="0123456789.";
  if(!checkastring(theForm.plen.value,checkOK)){
	alert("<?php print jscheck($yyOnlyDec . ' "' . $yyDims)?>\".");
	theForm.plen.focus();
	return(false);
  }
  if(!checkastring(theForm.pwid.value,checkOK)){
	alert("<?php print jscheck($yyOnlyDec . ' "' . $yyDims)?>\".");
	theForm.pwid.focus();
	return(false);
  }
  if(!checkastring(theForm.phei.value,checkOK)){
	alert("<?php print jscheck($yyOnlyDec . ' "' . $yyDims)?>\".");
	theForm.phei.focus();
	return(false);
  }
<?php	}
		if($usesshipweight){ ?>
  var checkOK="0123456789.";
  if(!checkastring(theForm.pWeight.value,checkOK)){
    alert("<?php print jscheck($yyOnlyDec . ' "' . $yyPrWght)?>\".");
    theForm.pWeight.focus();
    return(false);
  }
<?php	}
		if($usesflatrate){ ?>
  var checkOK="0123456789.";
  if(!checkastring(theForm.pShipping.value,checkOK)){
    alert("<?php print jscheck($yyOnlyDec . ' "' . $yyFlatShp . ': ' . $yyFirShi)?>\".");
    theForm.pShipping.focus();
    return(false);
  }
  if(!checkastring(theForm.pShipping2.value,"0123456789.")){
    alert("<?php print jscheck($yyOnlyDec . ' "' . $yyFlatShp . ': ' . $yySubShi)?>\".");
    theForm.pShipping2.focus();
    return(false);
  }
<?php	}
		if($GLOBALS['useStockManagement']){ ?>
  if(!(theForm.pStockByOpts.selectedIndex==1) && theForm.inStock.value==""){
    alert("<?php print jscheck($yyPlsEntr . ' "' . $yyInStk)?>\".");
    theForm.inStock.focus();
    return(false);
  }
  if(!(theForm.pStockByOpts.selectedIndex==1) && !checkastring(theForm.inStock.value,"0123456789-")){
    alert("<?php print jscheck($yyOnlyNum . ' "' . $yyInStk)?>\".");
    theForm.inStock.focus();
    return(false);
  }
  if(theForm.pStockByOpts.selectedIndex==1 && theForm.pnumoptions.selectedIndex==0){
    alert("<?php print jscheck($yyStkWrn)?>");
    theForm.pStockByOpts.focus();
    return(false);
  }
<?php	}
		if(@$perproducttaxrate==TRUE){ ?>
  if(theForm.pTax.value==""){
	alert("<?php print jscheck($yyPlsEntr . ' "' . $yyTax)?>\".");
	theForm.pTax.focus();
	return(false);
  }
  if(!checkastring(theForm.pTax.value,"0123456789.")){
    alert("<?php print jscheck($yyOnlyDec . ' "' . $yyTax)?>\".");
    theForm.pTax.focus();
    return(false);
  }
<?php	} ?>
  if(!checkastring(theForm.pOrder.value,"0123456789")){
    alert("<?php print jscheck($yyOnlyNum . ' "' . $yyProdOr)?>\".");
    theForm.pOrder.focus();
    return(false);
  }
	nummultioptions=0;
	for(index=0;index<parseInt(document.getElementById('pnumoptions').value);index++){
		var thisOption=document.getElementById('poption'+index);
		if(parseInt(thisOption.selectedIndex)!=0){
			var optval=parseInt(thisOption[thisOption.selectedIndex].value);
			for(var i=0;i<oAR.length;i++){
				if(oAR[i][0]==optval&&Math.abs(oAR[i][2])==4)nummultioptions++;
			}
		}
	}
	if(nummultioptions>1){
		alert("<?php print jscheck($yyMBOUni)?>");
		theForm.poption0.focus();
		return(false);
	}
	if(document.getElementById('staticpage').selectedIndex==0)
		document.getElementById('pStaticURL').value='';
	return(true);
}
function populateoptionsselect(oSelect,optsect){
	var insbefore=oSelect.selectedIndex!=0;
	var existingitem=oSelect.options[oSelect.selectedIndex];
	var osarray;
	if(optsect=='option') osarray=oAR; else if(optsect=='search') osarray=cAR; else osarray=sAR;
	for(var i=0;i<osarray.length;i++){
		if(existingitem.value==osarray[i][0]){
			insbefore=false;
		}else{
			var y=document.createElement('option');
			y.innerHTML=osarray[i][1];
			y.value=osarray[i][0];
			if(insbefore){
				try{oSelect.add(y,existingitem);} // FF etc
				catch(ex){oSelect.add(y,oSelect.selectedIndex);} // IE
			}else{
				try{oSelect.add(y,null);} // FF etc
				catch(ex){oSelect.add(y);} // IE
			}
		}
	}
}
function addnewoption(thisindex,optsect){
	var pNumOpts=parseInt(document.getElementById("pnum"+optsect+"s").value);
	if(thisindex==pNumOpts){
		pNumOpts+=1;
		var stable=document.getElementById(optsect+'stable');
		newrow=stable.insertRow(-1);
		newcell=newrow.insertCell(-1);
		newcell.align='right';
		newcell.innerHTML=(pNumOpts+1)+":";
		newcell=newrow.insertCell(-1);
		newcell.innerHTML='<select style="width:240px" size="1" id="p'+optsect+pNumOpts+'" name="p'+optsect+pNumOpts+'" onchange="addnewoption('+pNumOpts+',\''+optsect+'\');"><option value="0"><?php print jsescape($yySelect)?></option></select>';
		document.getElementById("pnum"+optsect+"s").value=pNumOpts;
		populateoptionsselect(document.getElementById("p"+optsect+pNumOpts),optsect);
	}
}
function setprodoptions(optsect){
	var pNumOpts=document.getElementById("pnum"+optsect+"s").value;
	for(var numopts=0;numopts<=pNumOpts;numopts++){
		oSelect=document.getElementById("p"+optsect+numopts);
		populateoptionsselect(oSelect,optsect);
	}
}
function setstockcontrols(resctrl){
	if(document.forms.mainform.pStockByOpts.selectedIndex==1){
		document.getElementById('stocksetting').value='';
		document.getElementById('inStock').style.display='none';
		if(document.getElementById('stockbutton'))document.getElementById('stockbutton').style.display='none';
	}else if(resctrl){
		document.getElementById('stocksetting').value='';
		document.getElementById('inStock').style.display='none';
		if(document.getElementById('stockbutton'))document.getElementById('stockbutton').style.display='';
	}else{
		document.getElementById('stocksetting').value='1';
		document.getElementById('inStock').style.display='';
		if(document.getElementById('stockbutton'))document.getElementById('stockbutton').style.display='none';
		document.getElementById('inStock').focus();
	}
}
function setstocktype(){
var si=document.forms.mainform.pStockByOpts.selectedIndex;
document.forms.mainform.inStock.disabled=(si==1);
document.getElementById('setbyopts').style.display=(si==1?'':'none');
<?php	if(getpost('act')=='modify'){ ?>
setstockcontrols(true);
<?php	}else{ ?>
document.getElementById('inStock').style.display=(si==1?'none':'');
<?php	} ?>
}
function uploadimage(imfield){
	var addthumb=0;
	var winwid=400; var winhei=300;
	if(imfield.substring(0,2)=='pG' || imfield.substring(0,2)=='gt'){ addthumb=2; winhei=400; }
	if(imfield.substring(0,2)=='pL' || imfield.substring(0,2)=='lg'){ addthumb=1; winhei=370; }
	var prnttext='<html><head><link rel="stylesheet" type="text/css" href="adminstyle.css"/><script type="text/javascript">function getCookie(c_name){if(document.cookie.length>0){var c_start=document.cookie.indexOf(c_name + "=");if(c_start!=-1){c_start=c_start+c_name.length+1;var c_end=document.cookie.indexOf(";",c_start);if(c_end==-1)c_end=document.cookie.length;return unescape(document.cookie.substring(c_start,c_end));}}return "";}';
	prnttext += 'function checkcookies(){ for(var ind=0; ind<='+addthumb+'; ind++){\r\n';
	prnttext += 'document.getElementById("newdim"+ind).value=getCookie("newdim"+ind);\r\n';
	prnttext += 'if(getCookie("suffix"+ind)!="")document.getElementById("suffix"+ind).value=getCookie("suffix"+ind);\r\n';
	prnttext += 'if(getCookie("thumbdim"+ind)!="")document.getElementById("thumbdim"+ind).selectedIndex=getCookie("thumbdim"+ind);}\r\n';
	if(addthumb>0) prnttext += 'if(getCookie("populate")=="ON")document.getElementById("populate").checked=true;\r\n';
	prnttext += '}<'+'/script></head><body<?php if(extension_loaded('gd')) print ' onload="checkcookies()"'?>>\n';
	prnttext += '<form name="mainform" method="post" action="doupload.php?defimagepath=<?php print $defaultprodimages?>" enctype="multipart/form-data">';
	prnttext += '<input type="hidden" name="defimagepath" value="<?php print $defaultprodimages?>" />';
	prnttext += '<input type="hidden" name="imagefield" value="'+imfield+'" />';
	prnttext += '<table border="" cellspacing="1" cellpadding="1" width="100%">';
	prnttext += '<tr><td align="center" colspan="2">&nbsp;<br /><strong><?php print str_replace("'","\\'", $yyUplIma)?></strong><br />&nbsp;</td></tr>';
	prnttext += '<tr><td align="center" colspan="2"><?php print str_replace("'","\\'", $yyPlsSUp)?><br />&nbsp;</td></tr>';
	prnttext += '<tr><td align="center" colspan="2"><?php print str_replace("'","\\'", $yyLocIma)?>:<input type="file" name="imagefile" /></td></tr>';
<?php	if(extension_loaded('gd')){
			$winhei=260; ?>
	prnttext += '<tr><td colspan="2">&nbsp;</td></tr><tr><td align="right"><select size="1" name="thumbdim0" id="thumbdim0"><option value="">Don\'t Resize Image</option><option value="1">Resize to Width:</option><option value="2">Resize to Height:</option></select></td><td><input type="text" name="newdim0" id="newdim0" size="3" />:px&nbsp;&nbsp;</td></tr>';
	if(imfield.substring(0,2)=='pL' || imfield.substring(0,2)=='lg' || imfield.substring(0,2)=='pG' || imfield.substring(0,2)=='gt') prnttext += '<tr><td align="right"><input type="hidden" name="hasrow1" value="1" /><select size="1" name="thumbdim1" id="thumbdim1"><option value="">No <?php print $yyImage?></option><option value="1"><?php print $yyImage?> Width:</option><option value="2"><?php print $yyImage?> Height:</option></select></td><td><input type="text" name="newdim1" id="newdim1" size="3" />:px&nbsp;&nbsp;Suffix:<input type="text" name="suffix1" id="suffix1" size="6" value="_small" /></td></tr>';
	if(imfield.substring(0,2)=='pG' || imfield.substring(0,2)=='gt') prnttext += '<tr><td align="right"><input type="hidden" name="hasrow2" value="1" /><select size="1" name="thumbdim2" id="thumbdim2"><option value="">No <?php print $yyLgeImg?></option><option value="1"><?php print $yyLgeImg?> Width:</option><option value="2"><?php print $yyLgeImg?> Height:</option></select></td><td><input type="text" name="newdim2" id="newdim2" size="3" />:px&nbsp;&nbsp;Suffix:<input type="text" name="suffix2" id="suffix2" size="6" value="_medium" /></td></tr>';
	if(addthumb>0) prnttext += '<tr><td colspan="2" align="center">&nbsp;<br />Populate smaller image fields? <input type="checkbox" name="populate" id="populate" value="ON" /></td></tr>';
<?php	}else
			$winhei=200; ?>
	prnttext += '<tr><td colspan="2" align="center">&nbsp;<br /><input type="submit" value="<?php print str_replace("'","\\'", $yySubmit)?>" /></td></tr>';
	prnttext += '</table></form>';
	prnttext += '<p align="center"><a href="javascript:window.close()"><strong><?php print str_replace("'","\\'", $yyClsWin)?></strong></a></p>';
	prnttext += '</body></html>';
	var scrwid=screen.width; var scrhei=screen.height;
	var newwin=window.open("","uploadimage",'menubar=no,scrollbars=yes,width='+winwid+',height='+winhei+',left='+((scrwid-winwid)/2)+',top=100,directories=no,location=no,resizable=yes,status=no,toolbar=no');
	newwin.document.open();
	newwin.document.write(prnttext);
	newwin.document.close();
	newwin.focus();
}
function imagemanager(){
	if(document.getElementById('extraimages').style.display=='none'){
		document.getElementById('extraimages').style.display='';
		document.getElementById('lessimages').style.display='none';
		document.getElementById('lessimages2').style.display='none';
		document.getElementById('but_pImage').value="<?php print $yyClose.' '.$yyImgMgr?>";
		document.getElementById('pImage').disabled=true;
		document.getElementById('smallimup').style.display='none';
		document.getElementById('moreimages').style.display='';
	}else{
		document.getElementById('extraimages').style.display='none';
		document.getElementById('lessimages').style.display='';
		document.getElementById('lessimages2').style.display='';
		document.getElementById('but_pImage').value="<?php print $yyImgMgr?>";
		document.getElementById('pImage').disabled=false;
		document.getElementById('smallimup').style.display='';
		document.getElementById('moreimages').style.display='none';
	}
}
function moreimagefn(){
	var thetable=document.getElementById('extraimagetable');
	var currmax=parseInt(document.getElementById('maximgindex').value);
	for(imindx=currmax; imindx<currmax+5; imindx++){
		newrow=thetable.insertRow(-1);
		newcell=newrow.insertCell(0);
		newcell.innerHTML='<input type="text" name="smim' + imindx + '" id="smim' + imindx + '" value="" size="30" />&nbsp;<input type="button"" value="..." onclick="uploadimage(\'smim' + imindx + '\')" />';
		newcell=newrow.insertCell(1);
		newcell.innerHTML='<input type="text" name="lgim' + imindx + '" id="lgim' + imindx + '" value="" size="30" />&nbsp;<input type="button"" value="..." onclick="uploadimage(\'lgim' + imindx + '\')" />';
		newcell=newrow.insertCell(2);
		newcell.innerHTML='<input type="text" name="gtim' + imindx + '" id="gtim' + imindx + '" value="" size="30" />&nbsp;<input type="button"" value="..." onclick="uploadimage(\'gtim' + imindx + '\')" />';
	}
	document.getElementById('maximgindex').value=imindx;
}
function setstatic(setting){
	if(setting==0){
		document.getElementById('staticpagediv').style.display='';
		document.getElementById('staticurldiv').style.display='none';
	}else{
		document.getElementById('staticpagediv').style.display='none'
		document.getElementById('staticurldiv').style.display='';
	}
}
function displaymultilangname(){
	for(var index=2;index<=3;index++){
		if(document.getElementById('pName'+index))document.getElementById('pName'+index).style.display='block';
	}
}
function getectobj(objid){
	return(document.getElementById(objid));
}
function displaymultilangdescs(islongdesc,thisobj){
	var setobj;
	for(var index=2;index<=3;index++){
		if(document.getElementById('pDescription'+index))document.getElementById('pDescription'+index).style.display='block';
		if(document.getElementById('pLongDescription'+index))document.getElementById('pLongDescription'+index).style.display='block';
	}
	for(var index=1;index<=3;index++){
		if(!islongdesc){
			if(setobj=getectobj('pDescription'+(index==1?'':index)))setobj.style.width='500px';
			if(setobj=getectobj('pLongDescription'+(index==1?'':index)))setobj.style.width='300px';
			if(index==thisobj){
				if(setobj=getectobj('pDescription'+(index==1?'':index)))setobj.style.height='200px';
				if(setobj=getectobj('pLongDescription'+(index==1?'':index)))setobj.style.height='100px';
			}else{
				if(setobj=getectobj('pDescription'+(index==1?'':index)))setobj.style.height='100px';
				if(setobj=getectobj('pLongDescription'+(index==1?'':index)))setobj.style.height='100px';
			}
		}
		if(islongdesc){
			if(setobj=getectobj('pDescription'+(index==1?'':index)))setobj.style.width='300px';
			if(setobj=getectobj('pLongDescription'+(index==1?'':index)))setobj.style.width='500px';
			if(index==thisobj){
				if(setobj=getectobj('pDescription'+(index==1?'':index)))setobj.style.height='100px';
				if(setobj=getectobj('pLongDescription'+(index==1?'':index)))setobj.style.height='200px';
			}else{
				if(setobj=getectobj('pDescription'+(index==1?'':index)))setobj.style.height='100px';
				if(setobj=getectobj('pLongDescription'+(index==1?'':index)))setobj.style.height='100px';
			}
		}
	}
}
function checkrequiredfields(){
	document.getElementById('newid').style.borderColor=(document.getElementById('newid').value.replace(/ /g,'')==''?'red':'');
	document.getElementById('pName').style.borderColor=(document.getElementById('pName').value.replace(/ /g,'')==''?'red':'');
	document.getElementById('pPrice').style.borderColor=(document.getElementById('pPrice').value.replace(/ /g,'')==''?'red':'');
	document.getElementById('psection').style.borderColor=(document.getElementById('psection').selectedIndex==0?'red':'');
}
/* ]]> */
</script>
<script type="text/javascript" src="popcalendar.js">
</script>
	<form name="mainform" method="post" action="adminprods.php" onsubmit="return formvalidator(this)">
			<input type="hidden" name="posted" value="1" />
			<?php	if(getpost('act')=="modify" && !$doaddnew){ ?>
			<input type="hidden" name="act" value="domodify" />
			<input type="hidden" name="id" value="<?php print htmlspecials($pId)?>" />
			<?php	}else{ ?>
			<input type="hidden" name="act" value="doaddnew" />
			<?php	}
					writehiddenvar('disp', getpost('disp'));
					writehiddenvar('stext', getpost('stext'));
					writehiddenvar('sprice', getpost('sprice'));
					writehiddenvar('scat', getpost('scat'));
					writehiddenvar('stype', getpost('stype'));
					writehiddenvar('pg', getpost('pg'));
					if(!$usesflatrate){
						print '<input type="hidden" name="pShipping" value="'.$pShipping.'" />';
						print '<input type="hidden" name="pShipping2" value="'.$pShipping2.'" />';
					} ?>
            <table id="producttable" width="100%" border="" cellspacing="0" cellpadding="3">
			  <tr> 
                <td width="100%" colspan="4" align="center"><strong><?php
					if($doaddnew)
						print $yyPrUpd;
					elseif(getpost('act')=='modify')
						print $yyYouMod . ' &quot;' . $pName . '&quot;';
					else
						print $yyYouCln . ' &quot;' . $pName . '&quot;';
				?></strong><br />&nbsp;</td>
			  </tr>
			  <tr>
			    <td align="right"><?php print $redasterix.$yyPrRef?>:</td><td><input type="text" id="newid" name="newid" size="25" value="<?php print htmlspecials($pId)?>" onfocus="document.getElementById('pSKU').size=10;this.size=25" onkeyup="checkrequiredfields()" />
			    				 / <input type="text" id="pSKU" name="pSKU" size="10" value="<?php print htmlspecials($pSKU)?>" placeholder="SKU" title="SKU" onfocus="document.getElementById('newid').size=10;this.size=25" />
				</td>
			    <td align="right"><?php print $redasterix.$yySection?>:</td><td><select size="1" name="psection" id="psection" onchange="checkrequiredfields()"><option value=""><?php print $yySelect?></option><?php
					for($index=0;$index<$nallsections;$index++){
						if($allsections[$index]['sectionID']==$pSection) print '<option value="'.$allsections[$index]['sectionID'].'" selected="selected">' . htmldisplay($allsections[$index]['sectionWorkingName']) . "</option>\r\n";
					} ?></select></td>
			  </tr>
			  <tr>
			    <td align="right"><?php print $redasterix.$yyPrNam?>:</td><td><input type="text" name="pName" id="pName" size="40" value="<?php print htmlspecialsucode($pName)?>" onfocus="displaymultilangname()" onkeyup="checkrequiredfields()" />
<?php		for($index=2; $index <= $adminlanguages+1; $index++){
				if(($adminlangsettings & 1)==1){
			?><input type="text" style="display:none;margin-top:2px" name="pName<?php print $index?>" id="pName<?php print $index?>" size="40" placeholder="Product Name Language <?php print $index?>" value="<?php print htmlspecialsucode($pNames[$index])?>" /><?php
				}
			} ?>
				</td>
			    <td align="right"><?php print $redasterix.$yyPrPri?> / WS / List:</td><td><input type="text" name="pPrice" id="pPrice" size="10" value="<?php print $pPrice?>" placeholder="<?php print $yyPrPri?>" title="<?php print $yyPrPri?>" onfocus="document.getElementById('pWholesalePrice').size=5;document.getElementById('pListPrice').size=6;this.size=10" onkeyup="checkrequiredfields()" />
				/ <input type="text" id="pWholesalePrice" name="pWholesalePrice" size="5" value="<?php print $pWholesalePrice?>" placeholder="<?php print $yyWhoPri?>" title="<?php print $yyWhoPri?>" onfocus="document.getElementById('pPrice').size=5;document.getElementById('pListPrice').size=6;this.size=10" />
				/ <input type="text" id="pListPrice" name="pListPrice" size="6" value="<?php if((double)$pListPrice!=0) print $pListPrice ?>" placeholder="<?php print $yyListPr?>" title="<?php print $yyListPr?>" onfocus="document.getElementById('pPrice').size=5;document.getElementById('pWholesalePrice').size=5;this.size=10" />
				</td>
			  </tr>
			  <tr>
<?php			if($useStockManagement){ ?>
				<td align="right">
				<input type="hidden" name="stocksetting" id="stocksetting" value="" />
				<select name="pStockByOpts" size="1" onchange="setstocktype()">
				<option value="0">&nbsp;&nbsp;&nbsp;<?php print $yyInStk?>:</option>
				<option value="1"<?php if((int)$pStockByOpts!=0) print 'selected="selected"' ?>><?php print $yyByOpt?>:</option></select>
				</td><td><?php
					if(getpost('act')=='modify'){ ?>
				<input type="button" id="stockbutton" value="<?php print $pInStock?> (Click to Set)" onclick="setstockcontrols(false)" />
<?php				} ?>
				<span id="setbyopts" style="display:none">(Set By Product Options)</span><input type="text" name="inStock" id="inStock" size="10" value="<?php print $pInStock?>" /></td>
<?php			}else{ ?>
				<input type="hidden" name="pStockByOpts" value="<?php if((int)$pStockByOpts!=0) print "1" ?>" />
<?php			} ?>
			  </tr>
			  <tr>
			    <td align="right"><?php print $yyPrWght?></td>
                <td align="left"><input type="text" name="pWeight" size="9" value="<?php print $pWeight?>" /></td>
				<?php	if(($adminUnits & 12) > 0){
							$proddims=explode("x", $pDims) ?>
				<td align="right"><?php print $yyDims?>:</td>
				<td><input type="text" name="plen" size="4" value="<?php print @$proddims[0]?>" /> <strong>X</strong> 
				<input type="text" name="pwid" size="4" value="<?php print @$proddims[1]?>" /> <strong>X</strong> 
				<input type="text" name="phei" size="4" value="<?php print @$proddims[2]?>" /></td>
				<?php	}else{ ?>
			    <td align="center" colspan="2">&nbsp;</td>
				<?php	} ?>
			  </tr>
			  <tr>
                <td align="right"><span style="color:#BB0000"><?php print $yyImage?></span>:</td>
				<td style="white-space:nowrap"><input type="text" id="pImage" size="16" value="<?php if($numsmimgs>0) print htmlspecials($allsmimgs[0]['imageSrc']) ?>" onchange="document.getElementById('smim0').value=this.value" onfocus="this.size=30;document.getElementById('but_pImage').value='IM'" onblur="this.size=16;document.getElementById('but_pImage').value='<?php print jsescape($yyImgMgr)?>'" />&nbsp;<input type="button" id="smallimup" value="..." onclick="uploadimage('smim0')" />&nbsp;<input type="button" id="but_pImage" value="<?php print $yyImgMgr?>" onclick="imagemanager()" /></td>
<?php			$themask='yyyy-mm-dd';
				if($admindateformat==1)
					$themask='mm/dd/yyyy';
				elseif($admindateformat==2)
					$themask='dd/mm/yyyy'; ?>
				<td align="right"><?php print $yyDateAd?>:</td>
				<td align="left"><input type="text" size="14" name="pDateAdded" value="<?php if($pDateAdded!='') print $pDateAdded?>" /> <input type="button" onclick="popUpCalendar(this, document.forms.mainform.pDateAdded, '<?php print $themask?>', -200)" value='DP' /></td>
			  </tr>
			  <tr id="lessimages">
                <td align="right"><span style="color:#00BB00"><?php print $yyLgeImg?></span>:</td>
                <td align="left"><input type="text" id="pLargeImage" size="30" value="<?php if($numlgimgs>0) print htmlspecials($alllgimgs[0]['imageSrc']) ?>" onchange="document.getElementById('lgim0').value=this.value" /> <input type="button" value="..." onclick="uploadimage('lgim0')" /></td>
				<td align="right"><?php print $yyManuf?>:</td>
				<td align="left"><select name="pManufacturer" size="1">
				  <option value="0"><?php print $yyNone?></option><?php
					$gotmanufacturer=FALSE;
					$sSQL="SELECT scID,scWorkingName FROM searchcriteria WHERE scGroup=0 ORDER BY scWorkingName";
					$result=ect_query($sSQL) or ect_error();
					while($rs=ect_fetch_assoc($result)){
						print '<option value="'.$rs['scID'].'"';
						if($rs['scID']==$pManufacturer){ print ' selected="selected"'; $gotmanufacturer=TRUE; }
						print '>'.$rs['scWorkingName']."</option>\r\n";
					}
					ect_free_result($result);
					if($pManufacturer!=0 && ! $gotmanufacturer) print '<option value="0" selected="selected">** DELETED **</option>';
?>				  </select>
				</td>
			  </tr>
			  <tr id="lessimages2">
                <td align="right"><span style="color:#0000BB"><?php print $yyGiaImg?></span>:</td>
                <td align="left"><input type="text" id="pGiantImage" size="30" value="<?php if($numgtimgs>0) print htmlspecials($allgtimgs[0]['imageSrc']) ?>" onchange="document.getElementById('gtim0').value=this.value" /> <input type="button" value="..." onclick="uploadimage('gtim0')" /></td>
				<td align="right"><?php print $yyDrSppr?>:</td>
				<td align="left"><select name="pDropship" size="1">
				  <option value="0"><?php print $yyNone?></option><?php
						for($index=0;$index<$nalldropship;$index++){
							print "<option value='" . $alldropship[$index]['dsID'] . "'";
							if($alldropship[$index]['dsID']==$pDropship) print ' selected="selected"';
							print '>' . $alldropship[$index]['dsName'] . "</option>\n";
						} ?>
				  </select></td>
			  </tr>
			  <tr id="extraimages" style="display:none">
				<td colspan="4" align="center">
				  <table id="extraimagetable" style="border:1px;border-color:#555;border-style:solid;padding:3px">
					<tr><td align="left" height="30"><input type="button" id="moreimages" value="<?php print $yyMorImg?>" onclick="moreimagefn()" style="margin-right:5px" /><span style="color:#BB0000"><?php print $yyImage?></span></td><td align="center"><span style="color:#00BB00"><?php print $yyLgeImg?></span></td><td align="center"><span style="color:#0000BB"><?php print $yyGiaImg?></span></td></tr>
<?php			if(! $doaddnew){
					for($imageindex=0; $imageindex<$maximagenumber; $imageindex++){
						getnext3images($smallimg,$largeimg,$giantimg);
						displayimagerow($imageindex,$smallimg,$largeimg,$giantimg);
					}
				}
				for($maximgindex=$imageindex; $maximgindex<=max(5,$imageindex+2); $maximgindex++){
					displayimagerow($maximgindex,'','','');
				}
?>
				  </table>
				  <input type="hidden" name="maximgindex" id="maximgindex" value="<?php print $maximgindex?>" />
				</td>
			  </tr>
			  <tr>
				<td align="right"><select size="1" id="staticpage" onchange="setstatic(this.selectedIndex)"><option value=""><?php print $yyStatPg?></option><option value="1"<?php print ($pStaticURL!=''?' selected="selected"':'')?>>Has Static URL</option></select></td>
                <td><div id="staticpagediv"<?php print ($pStaticURL!=''?' style="display:none"':'')?>><input type="checkbox" name="pStaticPage" value="1"<?php if((int)$pStaticPage!=0) print ' checked="checked"' ?> /></div>
				<div id="staticurldiv"<?php print ($pStaticURL==''?' style="display:none"':'')?>><input type="text" name="pStaticURL" id="pStaticURL" size="40" value="<?php print htmlspecials($pStaticURL)?>" /></div></td>
				<td align="right"><?php print $yyProdOr?>:</td>
                <td><input type="text" name="pOrder" size="10" value="<?php print $pOrder?>" /></td>
			  </tr>
<?php			if($usesflatrate){ ?>
			  <tr>
                <td align="right"><?php print $yyFlatShp . ':<br />' . $yyFirShi?>:</td>
                <td align="left"><input type="text" name="pShipping" size="15" value="<?php print $pShipping?>" /></td>
                <td align="right"><?php print $yyFlatShp . ':<br />' . $yySubShi?></td>
                <td align="left"><input type="text" name="pShipping2" size="15" value="<?php print $pShipping2?>" /></td>
			  </tr>
<?php			} ?>
			  <tr>
				<td align="right"><?php print $yyExemp?>:<br /><span style="font-size:10px">&lt;Ctrl>+Click&nbsp;</span></td><td>
					<select name="pExemptions[]" size="5" multiple="multiple">
					<option value="1"<?php if(($pExemptions&1)==1) print ' selected="selected"'?>><?php print $yyExStat?></option>
					<option value="2"<?php if(($pExemptions&2)==2) print ' selected="selected"'?>><?php print $yyExCoun?></option>
					<option value="4"<?php if(($pExemptions&4)==4) print ' selected="selected"'?>><?php print $yyExShip?></option>
					<option value="8"<?php if(($pExemptions&8)==8) print ' selected="selected"'?>><?php print $yyExHand?></option>
					<option value="16"<?php if(($pExemptions&16)==16) print ' selected="selected"'?>>Free Shipping Exempt</option>
					</select><br />
<?php			if(@$perproducttaxrate==TRUE){ ?>
					&nbsp;<br /><?php print $yyTax?>: <input type="text" style="text-align:right" size="6" name="pTax" value="<?php print $pTax?>" />%
<?php			} ?>
				</td>
				<td colspan="2">
<?php			if($useStockManagement){ ?>
					<input type="hidden" name="pSell" value="<?php if((int)$pSell!=0) print "ON" ?>" />
<?php			} ?>
					<div class="separator">Flags</div>
					<div style="max-width:400px">
<?php			if(! $useStockManagement){ ?>
						<div style="float:left;padding:5px"><div style="float:left"><?php print $yySellBut?>:</div><div style="float:left"><input type="checkbox" name="pSell" value="ON"<?php if((int)$pSell!=0) print ' checked="checked"' ?> /></div></div>
<?php			} ?>
						<div style="float:left;padding:5px"><div style="float:left"><?php print $yyDisPro?>:</div><div style="float:left"><input type="checkbox" name="pDisplay" value="ON"<?php if((int)$pDisplay!=0) print ' checked="checked"' ?> /></div></div>
						<div style="float:left;padding:5px"><div style="float:left"><?php print $yyRecomd?>:</div><div style="float:left"><input type="checkbox" name="pRecommend" value="1"<?php if((int)$pRecommend!=0) print ' checked="checked"' ?> /></div></div>
						<div style="float:left;padding:5px"><div style="float:left"><?php print $yyGifWra?>:</div><div style="float:left"><input type="checkbox" name="pGiftWrap" value="1"<?php if((int)$pGiftWrap!=0) print ' checked="checked"' ?> /></div></div>
						<div style="float:left;padding:5px"><div style="float:left"><?php print $yyBakOrd?>:</div><div style="float:left"><input type="checkbox" name="pBackOrder" value="1"<?php if((int)$pBackOrder!=0) print ' checked="checked"' ?> /></div></div>
					</div>
				</td>
			  </tr>
			  <tr>
				<td align="right"><?php print $yyAddSrP?>:</td>
                <td align="left" colspan="3"><input type="text" name="pSearchParams" size="60" value="<?php print htmlspecials($pSearchParams)?>" maxlength="255" /></td>
			  </tr>
			  <tr>
				<td align="right">Page Title Tag:</td>
                <td align="left" colspan="3"><input type="text" name="pTitle" size="60" value="<?php print htmlspecials($pTitle)?>" maxlength="255" /></td>
			  </tr>
			  <tr>
				<td align="right">Meta Description:</td>
                <td align="left" colspan="3"><input type="text" name="pMetaDesc" size="60" value="<?php print htmlspecials($pMetaDesc)?>" maxlength="255" /></td>
			  </tr>
<?php	if(strpos(@$detailpagelayout,'custom1')!==FALSE){ ?>
			  <tr>
				<td align="right">Custom 1:</td>
                <td colspan="3"><input type="text" name="pCustom1" size="60" value="<?php print htmlspecials($pCustom1)?>" maxlength="2048" /></td>
			  </tr>
<?php	}
		if(strpos(@$detailpagelayout,'custom2')!==FALSE){ ?>
			  <tr>
				<td align="right">Custom 2:</td>
                <td colspan="3"><input type="text" name="pCustom2" size="60" value="<?php print htmlspecials($pCustom2)?>" maxlength="2048" /></td>
			  </tr>
<?php	}
		if(strpos(@$detailpagelayout,'custom3')!==FALSE){ ?>
			  <tr>
				<td align="right">Custom 3:</td>
                <td colspan="3"><input type="text" name="pCustom3" size="60" value="<?php print htmlspecials($pCustom3)?>" maxlength="2048" /></td>
			  </tr>
<?php	}
		if(@$digidownloads==TRUE){ ?>
			  <tr>
                <td align="right"><?php print $yyDownl?>:</td>
                <td align="left" colspan="3"><input type="text" size="30" name="pDownload" value="<?php print $pDownload?>" maxlength="255" /></td>
			  </tr>
<?php	} ?>
			  <tr>
				<td colspan="4">
			<table width="100%">
			  <tr>
				<td width="34%" align="center">Product Options</td><td width="33%" align="center"><?php print $yyAddSec?></td><td width="33%" align="center"><?php print $yySeaCri?></td>
			  </tr>
			  <tr>
				<td align="center" valign="top">
				  <table id="optionstable">
<?php	$rowcounter=0;
		if($nalloptions>0){
			for($rowcounter=0;$rowcounter<$nprodoptions;$rowcounter++){
				print '<tr><td>'.($rowcounter+1).':</td><td><select style="width:240px" size="1" id="poption'.$rowcounter.'" name="poption'.$rowcounter.'"><option value="0">'.$yyDelete.'...</option><option value="'.$prodoptions[$rowcounter]['poOptionGroup'].'" selected="selected">'.$prodoptions[$rowcounter]['optGrpWorkingName']."</option></select></td></tr>\r\n";
			}
		} ?>
					<tr><td><?php print ($rowcounter+1)?>:</td><td><select style="width:240px" size="1" id="poption<?php print $rowcounter?>" name="poption<?php print $rowcounter?>" onchange="addnewoption(<?php print $rowcounter?>,'option');"><option value="0"><?php print $yySelect?></option></select></td></tr>
				  </table>
				  <input type="hidden" id="pnumoptions" value="0" />
				</td>
				<td align="center" valign="top">
				  <table id="sectionstable">
<?php	$rowcounter=0;
		if($nallsections>0){
			for($rowcounter=0;$rowcounter<$nprodsections;$rowcounter++){
				print '<tr><td>'.($rowcounter+1).':</td><td><select style="width:240px" size="1" id="psection'.$rowcounter.'" name="psection'.$rowcounter.'"><option value="0">'.$yyDelete.'...</option><option value="'.$prodsections[$rowcounter]['pSection'].'" selected="selected">'.$prodsections[$rowcounter]['sectionWorkingName']."</option></select></td></tr>\r\n";
			}
		} ?>
					<tr><td><?php print ($rowcounter+1)?>:</td><td><select style="width:240px" size="1" id="psection<?php print $rowcounter?>" name="psection<?php print $rowcounter?>" onchange="addnewoption(<?php print $rowcounter?>,'section');"><option value="0"><?php print $yySelect?></option></select></td></tr>
				  </table>
				  <input type="hidden" id="pnumsections" value="0" />
				</td>
				<td align="center" valign="top">
				  <table id="searchstable">
<?php	$rowcounter=0;
		if($nallsearchcriteria>0){
			for($rowcounter=0;$rowcounter<$nprodsearchcriteria;$rowcounter++){
				print '<tr><td>'.($rowcounter+1).':</td><td><select style="width:240px" size="1" id="psearch'.$rowcounter.'" name="psearch'.$rowcounter.'"><option value="0">'.$yyDelete.'...</option><option value="'.$prodsearchcriteria[$rowcounter]['scID'].'" selected="selected">'.$prodsearchcriteria[$rowcounter]['scWorkingName']."</option></select></td></tr>\r\n";
			}
		} ?>
					<tr><td><?php print ($rowcounter+1)?>:</td><td><select style="width:240px" size="1" id="psearch<?php print $rowcounter?>" name="psearch<?php print $rowcounter?>" onchange="addnewoption(<?php print $rowcounter?>,'search');"><option value="0"><?php print $yySelect?></option></select></td></tr>
				  </table>
				  <input type="hidden" id="pnumsearchs" value="0" />
				</td>
			  </tr>
			</table>
				</td>
			  </tr>
			  <tr>
				<td colspan="4">
			<table width="100%">
			  <tr> 
                <td width="50%" align="center"><?php print $yyDesc?></td>
                <td width="50%" align="center"><?php print $yyLnDesc?></td>
			  </tr>
			  <tr> 
                <td align="center" valign="top">
			<textarea onfocus="displaymultilangdescs(false,1)" name="pDescription" id="pDescription" cols="45" rows="8" placeholder="Product Description"><?php print htmlspecialsucode($pDescription)?></textarea>
<?php			for($index=2;$index<=$adminlanguages+1;$index++){
					if(($adminlangsettings & 2)==2){ ?>
			<textarea onfocus="displaymultilangdescs(false,<?php print $index?>)" style="display:none" id="pDescription<?php print $index?>" name="pDescription<?php print $index?>" cols="45" rows="8" placeholder="Description for Language <?php print $index?>"><?php print htmlspecialsucode($pDescriptions[$index])?></textarea>
<?php				}
				} ?>
				</td>
                <td align="center">
			<textarea onfocus="displaymultilangdescs(true,1)" name="pLongDescription" id="pLongDescription" cols="55" rows="9" placeholder="Product Long Description"><?php print htmlspecialsucode($pLongDescription)?></textarea>
<?php			for($index=2;$index<=$adminlanguages+1;$index++){
					if(($adminlangsettings & 4)==4){ ?>
			<textarea onfocus="displaymultilangdescs(true,<?php print $index?>)" style="display:none" id="pLongDescription<?php print $index?>" name="pLongDescription<?php print $index?>" cols="55" rows="9" placeholder="Long Description for Language <?php print $index?>"><?php print htmlspecialsucode($pLongDescriptions[$index])?></textarea>
<?php				}
				} ?>
				</td>
			  </tr>
			</table>
				</td>
			  </tr>
			</table>
			<table width="100%" border="" cellspacing="0" cellpadding="3">
			  <tr> 
                <td width="100%" colspan="4" align="center">&nbsp;<br />
					<input type="submit" value="<?php print $yySubmit?>" />&nbsp;&nbsp;<input type="reset" value="<?php print $yyReset?>" />
                </td>
			  </tr>
            </table>
	</form>
<?php
	if(@$htmleditor=='fckeditor' || @$htmleditor=='ckeditor'){
		if(@$pathtossl!='' && (@$_SERVER['HTTPS']=='on' || @$_SERVER['SERVER_PORT']=='443')){
			if(substr($pathtossl,-1)!="/") $storeurl=$pathtossl . "/"; else $storeurl=$pathtossl;
		}
		$pathtovsadmin=dirname(@$_SERVER['PHP_SELF']);
		print '<script type=""text/javascript"">function loadeditors(){';
		if($htmleditor=='ckeditor'){
			$streditor="var pDescription=CKEDITOR.replace('pDescription',{extraPlugins : 'autogrow,stylesheetparser',autoGrow_maxHeight : 800,removePlugins : 'resize', toolbarStartupExpanded : false, toolbar : 'Basic', filebrowserBrowseUrl :'ckeditor/filemanager/browser/default/browser.html?Connector=".$pathtovsadmin."/ckeditor/filemanager/connectors/php/connector.php',filebrowserImageBrowseUrl : 'ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=".$pathtovsadmin."/ckeditor/filemanager/connectors/php/connector.php',filebrowserFlashBrowseUrl :'ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=".$pathtovsadmin."/ckeditor/filemanager/connectors/php/connector.php',filebrowserUploadUrl:'".$pathtovsadmin."/ckeditor/filemanager/connectors/php/upload.php?Type=File',filebrowserImageUploadUrl:'".$pathtovsadmin."/ckeditor/filemanager/connectors/php/upload.php?Type=Image',filebrowserFlashUploadUrl:'".$pathtovsadmin."/ckeditor/filemanager/connectors/php/upload.php?Type=Flash'});\r\n";
			$streditor.="pDescription.on('instanceReady',function(event){var myToolbar='Basic';event.editor.on( 'beforeMaximize', function(){if(event.editor.getCommand('maximize').state==CKEDITOR.TRISTATE_ON && myToolbar!='Basic'){pDescription.setToolbar('Basic');myToolbar='Basic';pDescription.execCommand('toolbarCollapse');}else if(event.editor.getCommand('maximize').state==CKEDITOR.TRISTATE_OFF && myToolbar!='Full'){pDescription.setToolbar('Full');myToolbar='Full';pDescription.execCommand('toolbarCollapse');}});event.editor.on('contentDom', function(e){event.editor.document.on('blur', function(){if(!pDescription.isToolbarCollapsed){pDescription.execCommand('toolbarCollapse');pDescription.isToolbarCollapsed=true;}});event.editor.document.on('focus',function(){if(pDescription.isToolbarCollapsed){pDescription.execCommand('toolbarCollapse');pDescription.isToolbarCollapsed=false;}});});pDescription.fire('contentDom');pDescription.isToolbarCollapsed=true;});\r\n";
		}else
			$streditor="var oFCKeditor=new FCKeditor('pDescription');oFCKeditor.BasePath=sBasePath;oFCKeditor.Config.BaseHref='".$storeurl."';oFCKeditor.ToolbarSet='Basic';oFCKeditor.ReplaceTextarea();\r\n";
		print $streditor;
		for($index=2; $index <= $adminlanguages+1; $index++){
			if(($adminlangsettings & 2)==2) print str_replace('pDescription', 'pDescription' . $index, $streditor);
		}
		print str_replace('pDescription', 'pLongDescription', $streditor);
		for($index=2; $index <= $adminlanguages+1; $index++){
			if(($adminlangsettings & 4)==4) print str_replace('pDescription', 'pLongDescription' . $index, $streditor);
		}
		print '}window.onload=function(){loadeditors();init();}</script>';
	}
?>
<script type="text/javascript">
/* <![CDATA[ */
checkrequiredfields();
document.getElementById("pnumoptions").value=<?php print $nprodoptions ?>;
document.getElementById("pnumsections").value=<?php print $nprodsections ?>;
document.getElementById("pnumsearchs").value=<?php print $nprodsearchcriteria ?>;
setprodoptions("option");
setprodoptions("section");
setprodoptions("search");
<?php 	if($useStockManagement){ ?>
setstocktype();
<?php	} ?>
populateoptionsselect(document.getElementById('psection'),'section');
/* ]]> */
</script>
<?php
}elseif(getpost('act')=='discounts'){
		$sSQL="SELECT pName FROM products WHERE pID='" . escape_string(getpost('id')) . "'";
		$result=ect_query($sSQL) or ect_error();
		$rs=ect_fetch_assoc($result);
		$thisname=$rs['pName'];
		ect_free_result($result);
		$numassigns=0;
		$sSQL="SELECT cpaID,cpaCpnID,cpnWorkingName,cpnSitewide,cpnEndDate,cpnType FROM cpnassign LEFT JOIN coupons ON cpnassign.cpaCpnID=coupons.cpnID WHERE cpaType=2 AND cpaAssignment='" . getpost('id') . "'";
		$result=ect_query($sSQL) or ect_error();
		while($rs=ect_fetch_assoc($result))
			$alldata[$numassigns++]=$rs;
		ect_free_result($result);
		$numcoupons=0;
		$sSQL="SELECT cpnID,cpnWorkingName,cpnSitewide FROM coupons WHERE cpnSitewide=0 AND cpnEndDate >='" . date("Y-m-d",time()) ."'";
		$result=ect_query($sSQL) or ect_error();
		while($rs=ect_fetch_assoc($result))
			$alldata2[$numcoupons++]=$rs;
		ect_free_result($result);
?>
<script type="text/javascript">
/* <![CDATA[ */
function drk(id){
if(confirm("<?php print jscheck($yyConAss)?>\n")){
	document.mainform.id.value=id;
	document.mainform.act.value="deletedisc";
	document.mainform.submit();
}
}
/* ]]> */
</script>
        <tr>
		<form name="mainform" method="post" action="adminprods.php">
		  <td width="100%">
			<input type="hidden" name="posted" value="1" />
			<input type="hidden" name="act" value="dodiscounts" />
			<input type="hidden" name="id" value="<?php print htmlspecials(getpost('id'))?>" />
<?php				writehiddenvar('disp', getpost('disp'));
					writehiddenvar('stext', getpost('stext'));
					writehiddenvar('sprice', getpost('sprice'));
					writehiddenvar('scat', getpost('scat'));
					writehiddenvar('stype', getpost('stype'));
					writehiddenvar('pg', getpost('pg')); ?>
            <table width="100%" border="" cellspacing="0" cellpadding="3">
			  <tr> 
                <td width="100%" colspan="4" align="center"><strong><?php print $yyAssPrd?> &quot;<?php print $thisname?>&quot;.</strong><br />&nbsp;</td>
			  </tr>
<?php
	$gotone=FALSE;
	if($numcoupons>0){
		$thestr='<tr><td colspan="4" align="center">' . $yyAsDsCp . ': <select name="assdisc" size="1">';
		for($index=0;$index < $numcoupons;$index++){
			$alreadyassign=FALSE;
			if($numassigns>0){
				for($index2=0;$index2<$numassigns;$index2++){
					if($alldata2[$index]["cpnID"]==$alldata[$index2]["cpaCpnID"]) $alreadyassign=TRUE;
				}
			}
			if(! $alreadyassign){
				$thestr.="<option value='" . $alldata2[$index]["cpnID"] . "'>" . $alldata2[$index]["cpnWorkingName"] . "</option>\n";
				$gotone=TRUE;
			}
		}
		$thestr.="</select> <input type='submit' value='Go' /></td></tr>";
	}
	if($gotone){
		print $thestr;
	}else{
?>			  <tr> 
                <td width="100%" colspan="4" align="center"><br /><strong><?php print $yyNoDis?></strong></td>
			  </tr>
<?php
	}
	if($numassigns>0){
?>			  <tr> 
                <td width="100%" colspan="4" align="center"><br /><strong><?php print $yyCurDis?> &quot;<?php print $thisname?>&quot;.</strong><br />&nbsp;</td>
			  </tr>
			  <tr> 
                <td><strong><?php print $yyWrkNam?></strong></td>
				<td><strong><?php print $yyDisTyp?></strong></td>
				<td><strong><?php print $yyExpire?></strong></td>
				<td align="center"><strong><?php print $yyDelete?></strong></td>
			  </tr>
<?php	for($index=0;$index<$numassigns;$index++){
			$prefont="";
			$postfont="";
			if((int)$alldata[$index]["cpnSitewide"]==1 || ($alldata[$index]["cpnEndDate"]!='3000-01-01 00:00:00' && strtotime($alldata[$index]["cpnEndDate"])-time() < 0)){
				$prefont='<span style="color:#FF0000">';
				$postfont='</span>';
			}
?>			  <tr> 
                <td><?php	print $prefont . $alldata[$index]["cpnWorkingName"] . $postfont ?></td>
				<td><?php	if($alldata[$index]["cpnType"]==0)
								print $prefont . $yyFrSShp . $postfont;
							elseif($alldata[$index]["cpnType"]==1)
								print $prefont . $yyFlatDs . $postfont;
							elseif($alldata[$index]["cpnType"]==2)
								print $prefont . $yyPerDis . $postfont; ?></td>
				<td><?php	if($alldata[$index]["cpnEndDate"]=='3000-01-01 00:00:00')
								print $yyNever;
							elseif(strtotime($alldata[$index]["cpnEndDate"])-time() < 0)
								print '<span style="color:#FF0000">' . $yyExpird . '</span>';
							else
								print $prefont . date("Y-m-d",strtotime($alldata[$index]["cpnEndDate"])) . $postfont?></td>
				<td align="center"><input type="button" name="discount" value="Delete Assignment" onclick="drk('<?php print $alldata[$index]["cpaID"]?>')" /></td>
			  </tr>
<?php	}
	}else{
?>			  <tr> 
                <td width="100%" colspan="4" align="center"><br /><strong><?php print $yyNoAss?></strong></td>
			  </tr>
<?php
	}
?>			  <tr><td width="100%" colspan="4" align="center"><br />&nbsp;</td></tr>
			  <tr> 
                <td width="100%" colspan="4" align="center"><br /><a href="admin.php"><strong><?php print $yyAdmHom?></strong></a><br />&nbsp;</td>
			  </tr>
            </table></td>
		  </form>
        </tr>
<?php
}elseif(getpost('posted')=='1' && $success){ ?>
      <table border="" cellspacing="0" cellpadding="0" width="100%" align="center">
        <tr>
          <td width="100%">
			<table width="100%" border="" cellspacing="0" cellpadding="3">
			  <tr> 
                <td width="100%" align="center"><br /><strong><?php print $yyUpdSuc?></strong><br /><br /><?php print $yyNowFrd?><br /><br />
                        <?php print $yyNoAuto?> <a href="adminprods.php<?php
							print '?rid=' . getpost('rid') . '&disp=' . getpost('disp') . '&stext=' . urlencode(getpost('stext')) . '&sprice=' . urlencode(getpost('sprice')) . '&stype=' . getpost('stype') . '&scat=' . getpost('scat') . '&pg=' . getpost('pg');
						?>"><strong><?php print $yyClkHer?></strong></a>.<br /><br />&nbsp;<br />&nbsp;
                </td>
			  </tr>
			</table>
		  </td>
        </tr>
      </table>
<?php
}elseif(getpost('posted')=="1"){ ?>
      <table border="" cellspacing="0" cellpadding="0" width="100%" align="center">
        <tr>
          <td width="100%">
			<table width="100%" border="" cellspacing="0" cellpadding="3">
			  <tr> 
                <td width="100%" align="center"><br /><span style="color:#FF0000;font-weight:bold"><?php print $yyOpFai?></span><br /><br /><?php print $errmsg?><br /><br />
				<a href="javascript:history.go(-1)"><strong><?php print $yyClkBac?></strong></a></td>
			  </tr>
			</table>
		  </td>
        </tr>
      </table>
<?php
}elseif(getget('act')=='stknot'){ ?>
	<form method="post" action="adminprods.php">
	<input type="hidden" name="posted" value="1" />
	<input type="hidden" name="act" value="allstk" />
      <table border="" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td align="center">
			<table class="admin-table-b" border="" cellspacing="3" cellpadding="3">
			<thead>
			  <tr> 
                <th scope="col" style="white-space:nowrap">&nbsp;<?php print $yyPrId?>&nbsp;</th>
				<th scope="col" style="white-space:nowrap">&nbsp;<?php print $yyPrName?>&nbsp;</th>
				<th scope="col" style="white-space:nowrap">&nbsp;<?php print $yyPOName?>&nbsp;</th>
				<th scope="col" style="white-space:nowrap">&nbsp;<?php print $yyQuant?>&nbsp;</th>
				<th scope="col" style="white-space:nowrap">&nbsp;<?php print $yyDelete?>&nbsp;</th>
			  </tr>
			</thead>
<?php	if(getget('pid')!='' && getget('oid')!=''){
			$sSQL="DELETE FROM notifyinstock WHERE nsProdID='".escape_string(getget('pid'))."' AND nsOptID=".getget('oid');
			ect_query($sSQL) or ect_error();
		}
		$sSQL="SELECT nsProdID,nsTriggerProdID,pName,nsOptID,COUNT(*) AS tcnt FROM notifyinstock LEFT JOIN products on notifyinstock.nsProdID=products.pID GROUP BY nsProdID,nsTriggerProdID,pName,nsOptID ORDER BY tcnt DESC";
		$result=ect_query($sSQL) or ect_error();
		while($rs=ect_fetch_assoc($result)){
			$optname='';
			if($rs['nsOptID']!=0){
				$sSQL="SELECT optName FROM options WHERE optID=".$rs['nsOptID'];
				$result2=ect_query($sSQL) or ect_error();
				if($rs2=ect_fetch_assoc($result2)) $optname=$rs2['optName'];
				ect_free_result($result2);
			}
			$pname=trim($rs['pName']);
			if($pname=='') $pname='**DELETED**';
			$prodid=trim($rs['nsProdID']);
			if(trim($rs['nsTriggerProdID'])!=$prodid) $prodid.=' / ' . $rs['nsTriggerProdID'];
			print '<tr><td style="white-space:nowrap">'.$prodid.'</td><td style="white-space:nowrap">'.$pname.'</td><td style="white-space:nowrap">'.($optname!=''?$optname:'-').'</td><td style="white-space:nowrap">'.$rs['tcnt'].'</td><td style=""white-space:nowrap""><input type="button" value="'.$yyDelete.'" onclick="document.location=\'adminprods.php?act=stknot&pid='.$rs['nsProdID'].'&oid='.$rs['nsOptID'].'\'" /></td></tr>';
		}
		ect_free_result($result);
?>			  <tr> 
                <td colspan="5" align="center">&nbsp;<br /><input type="submit" value="Send All Stock Notifications" /> <input type="button" onclick="document.location='adminprods.php'" value="<?php print $yyClkBac?>" /></td>
			  </tr>
			</table></td>
        </tr>
      </table>
	</form>
<?php
}else{
	$pract=@$_COOKIE['pract'];
	$modclone=@$_COOKIE['modclone'];
	$sortorder=@$_COOKIE['psort'];
	$catorman=@$_COOKIE['pcatorman']; ?>
<script type="text/javascript">
/* <![CDATA[ */
function setCookie(c_name,value,expiredays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}
function mr(id){
	document.mainform.action="adminprods.php";
	document.mainform.rid.value='';
	document.mainform.id.value=id;
	document.mainform.act.value="modify";
	document.mainform.posted.value="1";
	document.mainform.submit();
}
function cr(id){
	document.mainform.action="adminprods.php";
	document.mainform.rid.value='';
	document.mainform.id.value=id;
	document.mainform.act.value="clone";
	document.mainform.posted.value="1";
	document.mainform.submit();
}
function rel(id){
	document.mainform.action="adminprods.php?related=go";
	document.mainform.rid.value=id;
	document.mainform.act.value="search";
	document.mainform.posted.value="";
	document.mainform.submit();
}
function updaterelations(){
	document.mainform.action="adminprods.php";
	document.mainform.act.value="updaterelations";
	document.mainform.posted.value="1";
	document.mainform.submit();
}
function newrec(id){
	document.mainform.action="adminprods.php";
	document.mainform.rid.value='';
	document.mainform.id.value=id;
	document.mainform.act.value="addnew";
	document.mainform.posted.value="1";
	document.mainform.submit();
}
function quickupdate(){
	if(document.mainform.pract.value=="del"){
		if(!confirm("<?php print jscheck($yyConDel)?>\n"))
			return;
	}
	document.mainform.action="adminprods.php";
	document.mainform.act.value="quickupdate";
	document.mainform.posted.value="1";
	document.mainform.submit();
}
function dsc(id){
	document.mainform.action="adminprods.php";
	document.mainform.id.value=id;
	document.mainform.act.value="discounts";
	document.mainform.posted.value="1";
	document.mainform.submit();
}
function startsearch(tact){
	document.mainform.action="adminprods.php";
	document.mainform.act.value=tact;
	document.mainform.posted.value="";
	document.mainform.submit();
}
function inventorymenu(tmen){
	themenuitem=tmen.options[tmen.selectedIndex].value;
	if(themenuitem=="5"){
		document.mainform.action="adminprods.php";
		document.mainform.act.value="tablechecks";
		document.mainform.posted.value="1";
	}else{
		if(themenuitem=="") return;
		if(themenuitem=="1") document.mainform.act.value="stockinventory";
		if(themenuitem=="2") document.mainform.act.value="fullinventory";
		if(themenuitem=="3") document.mainform.act.value="dump2COinventory";
		if(themenuitem=="4") document.mainform.act.value="productimages";
		document.mainform.action="dumporders.php";
	}
	document.mainform.submit();
}
function dr(id){
if(confirm("<?php print jscheck($yyConDel)?>\n")){
	document.mainform.action="adminprods.php";
	document.mainform.id.value=id;
	document.mainform.act.value="delete";
	document.mainform.posted.value="1";
	document.mainform.submit();
}
}
function changepract(obj){
	setCookie('pract',obj[obj.selectedIndex].value,600);
	startsearch("search");
}
function switchcatorman(obj){
	setCookie('pcatorman',obj[obj.selectedIndex].value,600);
	startsearch("<?php	if(getpost('act')=='search' || getget('pg')!='')print 'search'?>");
}
function changesortorder(obj){
	setCookie('psort',obj[obj.selectedIndex].value,600);
	startsearch("<?php	if(getpost('act')=='search' || getget('pg')!='')print 'search'?>");
}
function addto(){
	maxitems=document.getElementById("resultcounter").value;
	amnt=document.getElementById("txtadd").value;
	if(amnt.indexOf("%") > 0) ispercent=true; else ispercent=false;
	amnt.replace(/%/g, "");
	amnt=parseFloat(amnt);
	if(! isNaN(amnt)){
		for(index=0;index<maxitems;index++){
			if(document.getElementById("chkbx"+index)){
				theval=parseFloat(document.getElementById("chkbx"+index).value);
				if(! isNaN(theval))
					document.getElementById("chkbx"+index).value=ispercent?theval+((amnt*theval)/100.0):theval+amnt;
				document.getElementById("chkbx"+index).onchange();
			}
		}
	}
}
function checkboxes(docheck){
	maxitems=document.getElementById("resultcounter").value;
	for(index=0;index<maxitems;index++){
		var thiselem=document.getElementById("chkbx"+index);
		if(thiselem.checked!=docheck){
			thiselem.checked=docheck;
			thiselem.onchange();
		}
	}
}
function changemodclone(modclone){
	setCookie('modclone',modclone[modclone.selectedIndex].value,600);
	startsearch("search");
}
/* ]]> */
</script>
<h2><?php print $yyPrdAdm?></h2>
      <table border="" cellspacing="0" cellpadding="0" width="100%" align="center">
<?php
	$rid=trim(@$_REQUEST['rid']);
	$ridarr='';
	$numrid=0;
	if($rid!=''){
		$sSQL="SELECT rpRelProdID FROM relatedprods WHERE rpProdID='" . escape_string($rid) . "'";
		if(@$relatedproductsbothways==TRUE) $sSQL.="UNION SELECT rpProdID FROM relatedprods WHERE rpRelProdID='" . escape_string($rid) . "'";
		$result=ect_query($sSQL) or ect_error();
		while($rs=ect_fetch_assoc($result))
			$ridarr[$numrid++]=$rs;
		ect_free_result($result);
	}
	if(getpost('disp')!=''){
		setcookie('pdisp', getpost('disp'), time()+31536000, '/', '', @$_SERVER['HTTPS']=='on');
	}
	if(@$_REQUEST['disp']!='') $productdisplay=$_REQUEST['disp']; else $productdisplay=@$_COOKIE['pdisp'];
	if(getget('related')=='go') $_SESSION['savesearch']='disp=' . getpost('disp') . '&stext=' . urlencode(getpost('stext')) . '&sprice=' . urlencode(getpost('sprice')) . '&stype=' . getpost('stype') . '&scat=' . getpost('scat') . '&pg=' . getpost('pg');
?>
        <tr>
		  <td width="100%">
		  <form name="mainform" method="post" action="adminprods.php">
			<input type="hidden" name="posted" value="1" />
			<input type="hidden" name="act" value="xxxxx" />
			<input type="hidden" name="id" value="xxxxx" />
			<input type="hidden" name="rid" value="<?php print $rid?>" />
			<input type="hidden" name="pg" value="<?php print (getpost('act')=='search' ? '1' : getget('pg')) ?>" />
<?php
	$numcats=0;
	$scat=getrequest('scat');
	$stext=getrequest('stext');
	$stype=getrequest('stype');
	$sprice=getrequest('sprice');
	if(! @is_numeric(getget('pg')))
		$CurPage=1;
	else
		$CurPage=(int)(getget('pg'));
	$thecat=getrequest('scat');
	if($thecat!='') $thecat=(int)$thecat;
	$sSQL="SELECT payProvEnabled,payProvData1 FROM payprovider WHERE payProvID=2";
	$result=ect_query($sSQL) or ect_error();
	$rs=ect_fetch_assoc($result);
	if($rs["payProvEnabled"]==1 AND trim($rs["payProvData1"])!='') $twocoinventory=TRUE; else $twocoinventory=FALSE;
	ect_free_result($result);
?>			<table class="cobtbl" width="100%" border="" cellspacing="1" cellpadding="3">
<?php		if($rid!=''){ ?>
				  <tr><td class="cobhl" align="center" colspan="4" height="22"><strong> Products related to <?php print $rid ?></strong> </td></tr>
<?php		} ?>
			  <tr> 
                <td class="cobhl" width="25%" align="right"><select name="disp" size="1">
					<option value="5"><?php print $yySearch?> Visible Prods</option>
					<option value="1"<?php if($productdisplay=='1') print ' selected="selected"'?>><?php print $yySearch?> All Prods</option>
					<option value="2"<?php if($productdisplay=='2') print ' selected="selected"'?>><?php print $yySearch?> Hidden Prods</option>
<?php				if($useStockManagement) print '<option value="3"'.($productdisplay=='3' ? ' selected="selected"' : '').'>'.$yySearch.' '.$yyOOStoc.'</option>' ?>
					<option value="4"<?php if($productdisplay=='4') print ' selected="selected"'?>><?php print $yySearch?> Orphan Prods</option>
				</select></td>
				<td class="cobll" width="25%"><input type="text" name="stext" size="20" value="<?php print str_replace("\"","&quot;",$stext)?>" /></td>
				<td class="cobhl" width="25%" align="right"><?php print $yySrchMx?>:</td>
				<td class="cobll" width="25%"><input type="text" name="sprice" size="10" value="<?php print $sprice?>" title="Eg. 50-100 ... -50 ... 50-" /></td>
			  </tr>
			  <tr>
			    <td class="cobhl" width="25%" align="right"><?php print $yySrchTp?>:</td>
				<td class="cobll" width="25%"><select name="stype" size="1">
					<option value=""><?php print $yySrchAl?></option>
					<option value="any"<?php if($stype=="any") print ' selected="selected"'?>><?php print $yySrchAn?></option>
					<option value="exact"<?php if($stype=="exact") print ' selected="selected"'?>><?php print $yySrchEx?></option>
					</select>
				</td>
				<td class="cobhl" width="25%" align="right"><select size="1" name="catorman" onchange="switchcatorman(this)">
						<option value="cat"><?php print $yySrchCt?></option>
						<option value="man"<?php if($catorman=='man') print ' selected="selected"'?>><?php print $yySeaCri?></option>
						<option value="non"<?php if($catorman=='non') print ' selected="selected"'?>><?php print $yyNone?></option>
						</select></td>
				<td class="cobll" width="25%">
<?php	if($catorman=='non')
			print '&nbsp;';
		else{ ?>
				  <select name="scat" size="1">
				  <option value=""><?php print ($catorman=='man'?$yySeaCri:$yySrchAC)?></option>
<?php
		$lasttsid=-1;
		if($catorman=='man'){
			$adminonlysubcats=TRUE;
			$currgroup=-1;
			$sSQL='SELECT scID,scName,scGroup,scgTitle FROM searchcriteria INNER JOIN searchcriteriagroup ON searchcriteria.scGroup=searchcriteriagroup.scgID ORDER BY scGroup,scName';
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result)){
				if($currgroup!=$rs['scGroup']){ $currgroup=$rs['scGroup']; print '<option value="'.$rs['scID'].'" style="font-weight:bold;color:#000" disabled="disabled">== ' . htmldisplay($rs['scgTitle']) . " ==</option>\r\n"; }
				print "<option value='".$rs['scID']."'";
				if($rs['scID']==$thecat) print ' selected="selected"';
				print '>' . htmldisplay($rs['scName']) . "</option>\r\n";
			}
		}elseif(@$noadmincategorysearch!=TRUE){
			$sSQL="SELECT sectionID,sectionWorkingName,topSection,rootSection FROM sections " . (@$adminonlysubcats==TRUE ? "WHERE rootSection=1 ORDER BY sectionWorkingName" : "ORDER BY sectionOrder");
			$allcats=ect_query($sSQL) or ect_error();
			while($row=ect_fetch_assoc($allcats))
				$allcatsa[$numcats++]=$row;
			ect_free_result($allcats);
			if($numcats > 0){
				if(@$adminonlysubcats==TRUE){
					for($index=0;$index<$numcats;$index++){
						print '<option value="' . $allcatsa[$index]['sectionID'] . '"';
						if($allcatsa[$index]['sectionID']==$thecat) print ' selected="selected"';
						print '>' . htmldisplay($allcatsa[$index]['sectionWorkingName']) . "</option>\n";
					}
				}else
					writemenulevel(0,1);
			}
		}
?>
				  </select>
<?php	} ?>
				</td>
              </tr>
			  <tr>
				<td class="cobhl" align="center"><?php
				if($rid==''){ ?>
					<select name="inventoryselect" size="1" onchange="inventorymenu(this)" style="margin-bottom:2px">
						<option value="">Select Action...</option>
					  <?php if($GLOBALS['useStockManagement']) print '<option value="1">' . $yyStkInv . '</option>'; ?>
						<option value="2"><?php print $yyFulInv?></option>
						<?php if($twocoinventory) print '<option value="3">2Checkout Inventory</option>' ?>
						<option value="4">Product Images</option>
						<option value="5">Table Checks</option>
					</select><br />
<?php			}
				if(getpost('act')=='search' || getget('pg')!=''){
					if($pract=='del' || $pract=='dip' || $pract=='stp' || $pract=='rec' || $pract=='gwr' || $pract=='bak' || $pract=='ste' || $pract=='cte' || $pract=='she' || $pract=='hae' || $pract=='fse' || $pract=='pra' || $pract=='csu'){ ?>
					<input type="button" value="<?php print $yyCheckA?>" onclick="checkboxes(true);" /> <input type="button" value="<?php print $yyUCheck?>" onclick="checkboxes(false);" />
<?php				}elseif($pract=='pri' || $pract=='wpr' || $pract=='lpr' || $pract=='stk' || $pract=='prw' || $pract=='pro'){ ?>
					<input type="text" name="txtadd" id="txtadd" size="5" value="0" /> <input type="button" value="Add" onclick="addto()" />
<?php				}
				}elseif($rid==''){
					if(@$notifybackinstock){
						$sSQL="SELECT COUNT(*) AS tcnt FROM notifyinstock";
						$result=ect_query($sSQL) or ect_error();
						if($rs=ect_fetch_assoc($result)){
							if($rs['tcnt']>0) print '<input type="button" value="'.$yyStkNot.' ('.$rs['tcnt'].')'.'" onclick="document.location=\'adminprods.php?act=stknot\'" style="margin-bottom:8px" /><br />';
						}
						ect_free_result($result);
					}
				} ?></td>
				<td class="cobll" colspan="3"><table width="100%" cellspacing="0" cellpadding="0" border="">
					<tr>
					  <td class="cobll" align="center" style="white-space:nowrap">
						<select name="sort" size="1" onchange="changesortorder(this)">
						<option value="ida"<?php if($sortorder=='ida') print ' selected="selected"'?>>Sort - ID ASC</option>
						<option value="idd"<?php if($sortorder=='idd') print ' selected="selected"'?>>Sort - ID DESC</option>
						<option value=""<?php if($sortorder=='') print ' selected="selected"'?>>Sort - Name ASC</option>
						<option value="nad"<?php if($sortorder=='nad') print ' selected="selected"'?>>Sort - Name DESC</option>
						<option value="pra"<?php if($sortorder=='pra') print ' selected="selected"'?>>Sort - Price ASC</option>
						<option value="prd"<?php if($sortorder=='prd') print ' selected="selected"'?>>Sort - Price DESC</option>
						<option value="daa"<?php if($sortorder=='daa') print ' selected="selected"'?>>Sort - Date ASC</option>
						<option value="dad"<?php if($sortorder=='dad') print ' selected="selected"'?>>Sort - Date DESC</option>
						<option value="poa"<?php if($sortorder=='poa') print ' selected="selected"'?>>Sort - pOrder ASC</option>
						<option value="pod"<?php if($sortorder=='pod') print ' selected="selected"'?>>Sort - pOrder DESC</option>
<?php				if($useStockManagement) print '<option value="sta"'.($sortorder=='sta'?' selected="selected"':'').'>Sort - Stock ASC</option><option value="std"'.($sortorder=='std'?' selected="selected"':'').'>Sort - Stock DESC</option>';
					for($index=2; $index<=$adminlanguages+1; $index++){
						if(($adminlangsettings & 1)==1){ ?>
						<option value="na<?php print $index?>"<?php if($sortorder=='na'.$index) print ' selected="selected"'?>>Sort - Name<?php print ' '.$index?></option>
<?php					}
					} ?>
						<option value="nsf"<?php if($sortorder=='nsf') print ' selected="selected"'?>>No Sort (Fastest)</option>
						</select>
						<input type="submit" value="<?php print $yyListPd?>" onclick="startsearch('search')" />
<?php				if($rid!=''){ ?>
						<strong>&raquo;</strong> <input type="button" value="<?php print $yyBckLis?>" onclick="document.location='adminprods.php?<?php print @$_SESSION['savesearch']?>'">
<?php				}else{ ?>
						<input type="button" value="<?php print $yyNewPr?>" onclick="newrec()" />
<?php				} ?>
					  </td>
					  <td class="cobll" height="26" width="20%" align="right" style="white-space:nowrap">
<?php				if($rid!=''){ ?>
						<input type="button" value="<?php print $yyUpdRel?>" onclick="updaterelations()">
<?php				} ?></td>
					</tr>
				  </table></td>
			  </tr>
			</table>
<br />
            <table width="100%" class="stackable admin-table-a sta-white" id="prodstable">
<?php
	$jscript=$qetype=$qesize='';
	function patch_pid($id){
		return str_replace('.','ect_dot_xzq',$id);
	}
	function displayprodrow($xrs,$rownum){
		global $yyAssign,$yyRelate,$numcoupons,$allcoupon,$rid,$numrid,$ridarr,$resultcounter,$useStockManagement,$stockbyoptions,$resultcounter,$pract,$redasterix,$modclone,$sortorder,$jscript,$qetype,$qesize,$currentattribute;
		$stockbyoptions=FALSE;
		$hascoupon='0';
		if($GLOBALS['useStockManagement']){
			if($xrs['pStockByOpts']!=0) $stockbyoptions=TRUE;
		}
		$jscript.='pa['.$resultcounter.']=[';
		?><tr id="tr<?php print $resultcounter?>"><td class="minicell"><?php
			print '-';
			if($rid!=''){
				$jscript.="''";
			}elseif($pract=='prn'){
				$jscript.="'".jsspecials($xrs['pName'])."'";
				$qetype='text';
				$qesize='18';
			}elseif($pract=='prn2'){
				$jscript.="'".jsspecials($xrs['pName2'])."'";
				$qetype='text';
				$qesize='18';
			}elseif($pract=='prn3'){
				$jscript.="'".jsspecials($xrs['pName3'])."'";
				$qetype='text';
				$qesize='18';
			}elseif($pract=='psp'){
				$jscript.="'".jsspecials($xrs['pSearchParams'])."'";
				$qetype='text';
				$qesize='18';
			}elseif($pract=='pri'){
				$jscript.="'".jsspecials($xrs['pPrice'])."'";
				$qetype='text';
				$qesize='5';
			}elseif($pract=='wpr'){
				$jscript.="'".jsspecials($xrs['pWholesalePrice'])."'";
				$qetype='text';
				$qesize='5';
			}elseif($pract=='lpr'){
				$jscript.="'".jsspecials($xrs['pListPrice'])."'";
				$qetype='text';
				$qesize='5';
			}elseif($pract=='stk'){
				$jscript.=($stockbyoptions?"false":"'".jsspecials($xrs['pInStock'])."'");
				$qetype='text';
				$qesize='5';
			}elseif($pract=='sta'){
				$jscript.=($stockbyoptions?"false":"''");
				$qetype='text';
				$qesize='5';
			}elseif($pract=='del'){
				$jscript.="'del'";
				$qetype='delbox';
			}elseif($pract=='prw'){
				$jscript.="'".jsspecials($xrs['pWeight'])."'";
				$qetype='text';
				$qesize='5';
			}elseif($pract=='pra'&&$currentattribute!=''){
				$sSQL="SELECT mSCscID FROM multisearchcriteria WHERE mSCpID='".escape_string($xrs['pID'])."' AND mSCscID=".$currentattribute;
				$result2=ect_query($sSQL) or ect_error();
				$jscript.=(ect_num_rows($result2)==0?0:1);
				ect_free_result($result2);
				$qetype='checkbox';
			}elseif($pract=='dip'){
				$jscript.=($xrs['pDisplay']!=0?1:0);
				$qetype='checkbox';
			}elseif($pract=='stp'){
				$jscript.=($xrs['pStaticPage']!=0?1:0);
				$qetype='checkbox';
			}elseif($pract=='stu'){
				$jscript.="'".jsspecials($xrs['pStaticURL'])."'";
				$qetype='text';
				$qesize='18';
			}elseif($pract=='rec'){
				$jscript.=($xrs['pRecommend']!=0?1:0);
				$qetype='checkbox';
			}elseif($pract=='gwr'){
				$jscript.=($xrs['pGiftWrap']!=0?1:0);
				$qetype='checkbox';
			}elseif($pract=='bak'){
				$jscript.=($xrs['pBackOrder']!=0?1:0);
				$qetype='checkbox';
			}elseif($pract=='sku'){
				$jscript.="'".jsspecials($xrs['pSKU'])."'";
				$qetype='text';
				$qesize='10';
			}elseif($pract=='pro'){
				$jscript.="'".jsspecials($xrs['pOrder'])."'";
				$qetype='text';
				$qesize='5';
			}elseif($pract=='ppt'){
				$jscript.="'".jsspecials($xrs['pTax'])."'";
				$qetype='text';
				$qesize='5';
			}elseif($pract=='sel'){
				$jscript.=($xrs['pSell']!=0?1:0);
				$qetype='checkbox';
			}elseif($pract=='ste' || $pract=='cte' || $pract=='she' || $pract=='hae' || $pract=='fse'){
				$fieldnum=1;
				if($pract=='cte') $fieldnum=2;
				if($pract=='she') $fieldnum=4;
				if($pract=='hae') $fieldnum=8;
				if($pract=='fse') $fieldnum=16;
				//print '<input type="hidden" name="pra_'.patch_pid($xrs['pID']).'" value="' . $xrs['pExemptions'] . '" /><input type="checkbox" id="chkbx'.$resultcounter.'" name="prb_'.patch_pid($xrs['pID']).'" value="1" ' . (($xrs['pExemptions'] & $fieldnum)!=0 ? 'checked="checked"' : '') . 'tabindex="'.($resultcounter+1).'"/>';
				$jscript.=(($xrs['pExemptions'] & $fieldnum)!=0?1:0);
				$qetype='checkbox';
			}elseif($pract=='frs'){
				$jscript.="'".jsspecials($xrs['pShipping'])."'";
				$qetype='text';
				$qesize='5';
			}elseif($pract=='csu'){
				$jscript.='0';
				$qetype='checkbox';
			}else
				print '-';
		?></td><td>-</td><td><?php
			if(@$noautocheckorphans==TRUE && @$_REQUEST['disp']!='4'){
				// nothing
			}elseif(is_null($xrs['rootSection']) || $xrs['rootSection']!=1){
				print $redasterix.' ';
				$haveerrprods=TRUE;
			}
			$hasstock=true;
			if((int)$xrs['pDisplay']==0 || ($GLOBALS['useStockManagement'] && $xrs['pInStock'] <= 0  && ! $stockbyoptions) || (!$GLOBALS['useStockManagement'] && $xrs['pSell']==0)) $hasstock=FALSE;
			if(! $hasstock) print '<span style="color:#FF0000;font-weight:bold">';
			if((int)$xrs['pDisplay']==0) print '<strike>';
			print $xrs['pName'.($sortorder=='na2'?2:($sortorder=='na3'?3:''))];
			if((int)$xrs['pDisplay']==0) print '</strike>';
			if(! $hasstock) print '</span>';
			if($GLOBALS['useStockManagement']) print ' (' . ($stockbyoptions?'-':$xrs['pInStock']) . ')'?></td><td>-</td><?php
			if($rid!=''){
		?><td><input type="hidden" name="updq<?php print $rownum?>" value="<?php print htmlspecials($xrs['pID'])?>" /><input type="checkbox" name="updr<?php print $rownum?>" value="1" <?php
				if($rid==$xrs['pID'])
					print 'disabled ';
				else{
					for($index=0; $index<$numrid; $index++)
						if($ridarr[$index]['rpRelProdID']==$xrs['pID']){ print 'checked="checked" '; break; }
				} ?>/></td><?php
			}else{
				$hascoupon='0';
				for($index=0;$index<$numcoupons;$index++){
					if($allcoupon[$index]['cpaAssignment']==$xrs['pID']){
						$hascoupon='1';
						break;
					}
				}
		?><td>-</td><?php
			}
		?><td>-</td></tr>
<?php	$jscript.=",'".jsspecials($xrs['pID'])."'," . $hascoupon . "];\r\n";
		$resultcounter++;
	}
	function displayheaderrow(){
		global $yyPrId,$yyPrName,$yyDiscnt,$yyModify,$yyClone,$yyRelate,$yyDelete,$yyStck,$useStockManagement,$pract,$adminlangsettings,$adminlanguages,$perproducttaxrate,$currentattribute,
		$yyPrPri,$yyWhoPri,$yyListPr,$yyStck,$yyPrWght,$yyDisPro,$yyStatPg,$yyRecomd,$yyProdOr,$yySellBut,$yyGifWra,$yyBakOrd,$yyTax,$yyAddSrP,$yySeaCri,$modclone,$detlinkspacechar; ?>
		<tr>
			<th class="small minicell">
				<select name="pract" id="pract" size="1" onchange="changepract(this)">
				<option value="none">Quick Entry...</option>
				<option value="prn"<?php if($pract=='prn') print ' selected="selected"'?>><?php print $yyPrName?></option>
<?php	for($index=2; $index <= $adminlanguages+1; $index++){
			if(($adminlangsettings & 1)==1) print '<option value="prn'.$index.'"' . ($pract==('prn'.$index)?' selected="selected"':'') . '>' . $yyPrName . ' ' . $index . '</option>';
		} ?>
				<option value="pra"<?php if($pract=='pra') print ' selected="selected"'?>><?php print $yySeaCri?></option>
				<option value="psp"<?php if($pract=='psp') print ' selected="selected"'?>><?php print $yyAddSrP?></option>
				<option value="pri"<?php if($pract=='pri') print ' selected="selected"'?>><?php print $yyPrPri?></option>
				<option value="wpr"<?php if($pract=='wpr') print ' selected="selected"'?>><?php print $yyWhoPri?></option>
				<option value="lpr"<?php if($pract=='lpr') print ' selected="selected"'?>><?php print $yyListPr?></option>
				<option value="stk"<?php if($pract=='stk') print ' selected="selected"'?>><?php print $yyStck?></option>
				<option value="sta"<?php if($pract=='sta') print ' selected="selected"'?>><?php print $yyStck?> Add</option>
				<option value="prw"<?php if($pract=='prw') print ' selected="selected"'?>><?php print $yyPrWght?></option>
				<option value="dip"<?php if($pract=='dip') print ' selected="selected"'?>><?php print $yyDisPro?></option>
				<option value="rec"<?php if($pract=='rec') print ' selected="selected"'?>><?php print $yyRecomd?></option>
				<option value="gwr"<?php if($pract=='gwr') print ' selected="selected"'?>><?php print $yyGifWra?></option>
				<option value="bak"<?php if($pract=='bak') print ' selected="selected"'?>><?php print $yyBakOrd?></option>
				<option value="sku"<?php if($pract=='sku') print ' selected="selected"'?>>SKU</option>
				<option value="frs"<?php if($pract=='frs') print ' selected="selected"'?>>Flat Rate Shipping</option>
				<option value="pro"<?php if($pract=='pro') print ' selected="selected"'?>><?php print $yyProdOr?></option>
<?php	if(@$perproducttaxrate){ ?>
				<option value="ppt"<?php if($pract=='ppt') print ' selected="selected"'?>><?php print $yyTax?></option>
<?php	} ?>
				<option value="" disabled="disabled">---------------------</option>
				<option value="ste"<?php if($pract=='ste') print ' selected="selected"'?>>State Tax Exempt</option>
				<option value="cte"<?php if($pract=='cte') print ' selected="selected"'?>>Country Tax Exempt</option>
				<option value="she"<?php if($pract=='she') print ' selected="selected"'?>>Shipping Exempt</option>
				<option value="hae"<?php if($pract=='hae') print ' selected="selected"'?>>Handling Exempt</option>
				<option value="fse"<?php if($pract=='fse') print ' selected="selected"'?>>Free Shipping Exempt</option>
<?php	if(! $useStockManagement){ ?>
				<option value="sel"<?php if($pract=='sel') print ' selected="selected"'?>><?php print $yySellBut?></option>
<?php	} ?>
				<option value="" disabled="disabled">---------------------</option>
				<option value="stp"<?php if($pract=='stp') print ' selected="selected"'?>><?php print $yyStatPg?></option>
				<option value="stu"<?php if($pract=='stu') print ' selected="selected"'?>>Static URL</option>
				<option value="csu"<?php if($pract=='csu') print ' selected="selected"'?>>Create Static URL</option>
				<option value="" disabled="disabled">---------------------</option>
				<option value="del"<?php if($pract=='del') print ' selected="selected"'?>><?php print $yyDelete?></option>
				</select><?php
			if($pract=='csu'){
				print '<div style="margin-top:6px;margin-left:6px;text-align:left">';
				print '<div style="text-align:center" id="staticurlshow"><input type="button" value="Show Options" onclick="document.getElementById(\'staticurlshow\').style.display=\'none\';document.getElementById(\'staticurloptions\').style.display=\'\';" /></div>';
				print '<div id="staticurloptions" style="display:none"><select size="1" name="extension" title="Has Extension (.php)" onchange="setCookie(\'incpextension\',this[this.selectedIndex].value,365)"><option value="yes">Extension (.php)</option><option value="no"'.(@$seodetailurls || @$_COOKIE['incpextension']=='no'?' selected="selected"':'').'>Extensionless</option></select>';
				print '<select size="1" name="space" title="Space Replacement" onchange="setCookie(\'incpspace\',this[this.selectedIndex].value,365)">' .
					'<option value=" ">No Space Replacement</option>' .
					'<option value="_"'.(@$_COOKIE['incpspace']=='_' && @$detlinkspacechar!='_'?' selected="selected"':'').(@$detlinkspacechar=='_'?' disabled="disabled"':'').'>Underscore'.(@$detlinkspacechar=='_'?' ($detlinkspacechar)':'').'</option>' .
					'<option value="-"'.(@$_COOKIE['incpspace']=='-' && @$detlinkspacechar!='-'?' selected="selected"':'').(@$detlinkspacechar=='-'?' disabled="disabled"':'').'>Dash'.(@$detlinkspacechar=='-'?' ($detlinkspacechar)':'').'</option>' .
					'<option value="remove"'.(@$_COOKIE['incpspace']=='remove'?' selected="selected"':'').'>Remove</option></select>';
				print '<select size="1" name="lcase" title="Lower Case" onchange="setCookie(\'incplcase\',this[this.selectedIndex].value,365)"><option value="no">Keep Original Case</option><option value="yes"'.(@$_COOKIE['incplcase']=='yes'?' selected="selected"':'').'>Force Lower Case</option></select>';
				print '<select size="1" name="punctuation" title="Remove Punctuation" onchange="setCookie(\'incpunctuation\',this[this.selectedIndex].value,365)"><option value="">Keep Punctuation</option><option value="remove"'.(@$_COOKIE['incpunctuation']=='remove'?' selected="selected"':'').'>Remove Punctuation</option></select>';
				print '<select size="1" name="wholedb" title="Create Static URL\'s for All Products"><option value="">Selected Items Only</option><option value="clear">Clear All Static URL\'s</option><option value="set">Create All Static URL\'s</option></select>';
				print '<select size="1" name="addprodid" title="Include Product ID"><option value="">Don\'t Include Product ID</option><option value="prepend">Prepend Product ID</option><option value="append">Append Product ID</option></select></div>';
				print '</div>';
			}elseif($pract=='pra'){
				if(is_numeric(@$_COOKIE['currattr'])){
					$currentattribute=(int)$_COOKIE['currattr'];
					$result2=ect_query('SELECT scID FROM searchcriteria WHERE scID='.$currentattribute) or ect_error();
					if(ect_num_rows($result2)==0) $currentattribute='';
					ect_free_result($result2);
				}else
					$currentattribute='';
				$currentgroupid=-1;
				print '<div style="margin-top:2px"><select name="currentattribute" size="1" onchange="setCookie(\'currattr\',this[this.selectedIndex].value,600);changepract(document.getElementById(\'pract\'))">';
				$sSQL='SELECT scID,scWorkingName,scgID,scgWorkingName FROM searchcriteria INNER JOIN searchcriteriagroup ON searchcriteria.scGroup=searchcriteriagroup.scgID ORDER BY scgWorkingName,scOrder';
				$result2=ect_query($sSQL) or ect_error();
				if(ect_num_rows($result2)==0) print '<option value="" disabled="disabled">== No Attributes Defined ==</option>' . "\r\n";
				while($rs2=ect_fetch_assoc($result2)){
					if($currentgroupid!=$rs2['scgID']){
						print '<option value="" disabled="disabled">== ' . $rs2['scgWorkingName'] . " ==</option>\r\n";
						$currentgroupid=$rs2['scgID'];
					}
					print '<option value="' . $rs2['scID'] . '"' . ($currentattribute==$rs2['scID']?' selected="selected"':'') . '>' . $rs2['scWorkingName'] . "</option>\r\n";
					if($currentattribute=='') $currentattribute=$rs2['scID'];
				}
				ect_free_result($result2);
				print '</select></div>';
			} ?></th>
			<th style="width:20%"><strong><?php print $yyPrId?></strong></th>
			<th style="width:30%"><strong><?php print $yyPrName?></strong></th>
			<th style="width:5%;text-align:center" class="small"><?php print $yyDiscnt?></th>
			<th style="width:5%;text-align:center" class="small"><?php print $yyRelate?></th>
			<th style="width:5%;text-align:center" class="small"><?php print $yyModify?></th>
		</tr>
<?php
	}
	$rowcounter=0;
	if(getpost('act')=='search' || getget('pg')!=''){
		$numcoupons=0;
		$sSQL="SELECT DISTINCT cpaAssignment FROM cpnassign WHERE cpaType=2";
		$result=ect_query($sSQL) or ect_error();
		while($rs=ect_fetch_assoc($result))
			$allcoupon[$numcoupons++]=$rs;
		ect_free_result($result);
		$columnlist='products.pID,pName,pName2,pName3,pDisplay,pSell,pExemptions,pShipping,pShipping2,pInStock,rootSection,pStockByOpts,pPrice,pWholesalePrice,pListPrice,pOrder,pRecommend,pGiftWrap,pBackOrder,pStaticPage,pStaticURL,pSKU,pWeight,pTax,pSearchParams';
		if(getget('related')=='go'){
			$sSQL="SELECT DISTINCT " . $columnlist . " FROM relatedprods INNER JOIN products ON products.pId=relatedprods.rpRelProdId LEFT OUTER JOIN sections ON products.pSection=sections.sectionID WHERE rpProdId='" . escape_string($rid) . "'";
			if(@$relatedproductsbothways==TRUE) $sSQL.="UNION SELECT DISTINCT " . $columnlist . " FROM relatedprods INNER JOIN products ON products.pId=relatedprods.rpProdId LEFT OUTER JOIN sections ON products.pSection=sections.sectionID WHERE rpRelProdId='" . escape_string($rid) . "'";
			$result=ect_query($sSQL) or ect_error();
			if(ect_num_rows($result)>0){
				displayheaderrow();
				while($rs=ect_fetch_assoc($result)){
					displayprodrow($rs,$rowcounter++);
				}
			}else
				print '<tr><td width="100%" colspan="6" align="center"><p>&nbsp;</p><p>' . $yyPrNoRe . '</p><p>' . $yyPrReSe . '</p><p>' . $yyPrReLs . '</p>&nbsp;</td></tr>';
			ect_free_result($result);
		}else{
			$whereand=' WHERE ';
			if($thecat=='' || $sortorder=='nsf')
				$sSQL=' FROM products LEFT OUTER JOIN sections ON products.pSection=sections.sectionID';
			else
				$sSQL=" FROM multisections RIGHT JOIN products ON products.pId=multisections.pId LEFT OUTER JOIN sections ON products.pSection=sections.sectionID";
			if($thecat!=''){
				if($catorman=='man'){
					$sSQL.=" INNER JOIN multisearchcriteria ON products.pID=multisearchcriteria.mSCpID" . $whereand . "multisearchcriteria.mSCscID=" . $thecat;
					$whereand=' AND ';
				}else{
					$sectionids=getsectionids($thecat, TRUE);
					if($sectionids!=''){
						if(@$sortorder=='nsf')
							$sSQL.=$whereand . " products.pSection IN (" . $sectionids . ") ";
						else
							$sSQL.=$whereand . " (products.pSection IN (" . $sectionids . ") OR multisections.pSection IN (" . $sectionids . ")) ";
						$whereand=' AND ';
					}
				}
			}
			if(@$noautocheckorphans==TRUE && @$_REQUEST['disp']!='4'){
				$sSQL=str_replace('LEFT OUTER JOIN sections ON products.pSection=sections.sectionID','',$sSQL);
			}
			if($sprice!=''){
				if(strpos($sprice, '-') !== FALSE){
					$pricearr=explode('-', $sprice);
					if(! is_numeric($pricearr[0])) $pricearr[0]=0;
					if(! is_numeric($pricearr[1])) $pricearr[1]=10000000;
					$sSQL.=$whereand . "pPrice BETWEEN " . $pricearr[0] . " AND " . $pricearr[1];
					$whereand=' AND ';
				}elseif(is_numeric($sprice)){
					$sSQL.=$whereand . "pPrice='" . escape_string($sprice) . "' ";
					$whereand=' AND ';
				}
			}
			if(trim($stext)!=''){
				$Xstext=escape_string($stext);
				$aText=explode(' ',$Xstext);
				if(@$nosearchadmindescription) $maxsearchindex=2; else $maxsearchindex=3;
				$aFields[0]='products.pId';
				$aFields[1]='pSKU';
				$aFields[2]=getlangid('pName',1);
				$aFields[3]=getlangid('pDescription',2);
				if($stype=='exact'){
					$sSQL.=$whereand . "(products.pId LIKE '%" . $Xstext . "%' OR ".getlangid("pName",1)." LIKE '%" . $Xstext . "%' OR ".getlangid("pDescription",2)." LIKE '%" . $Xstext . "%' OR ".getlangid("pLongDescription",4)." LIKE '%" . $Xstext . "%') ";
					$whereand=' AND ';
				}else{
					$sJoin='AND ';
					if($stype=='any') $sJoin='OR ';
					$sSQL.=$whereand . '(';
					$whereand=' AND ';
					for($index=0;$index<=$maxsearchindex;$index++){
						$sSQL.='(';
						$rowcounter=0;
						$arrelms=count($aText);
						foreach($aText as $theopt){
							if(is_array($theopt))$theopt=$theopt[0];
							$sSQL.=$aFields[$index] . " LIKE '%" . $theopt . "%' ";
							if(++$rowcounter < $arrelms) $sSQL.=$sJoin;
						}
						$sSQL.=') ';
						if($index < $maxsearchindex) $sSQL.='OR ';
					}
					$sSQL.=') ';
				}
			}
			if(@$_REQUEST['disp']=='4'){ $sSQL.=$whereand . '(rootSection IS NULL OR rootSection=0)'; $whereand=' AND '; }
			if(@$_REQUEST['disp']=='3'){ $sSQL.=$whereand . '(pInStock<=0 AND pStockByOpts=0)'; $whereand=' AND '; }
			if(@$_REQUEST['disp']=='' || @$_REQUEST['disp']=='5'){ $sSQL.=$whereand . 'pDisplay<>0'; $whereand=' AND '; }
			if(@$_REQUEST['disp']=='2'){ $sSQL.=$whereand . 'pDisplay=0'; $whereand=' AND '; }
			if($sortorder=='ida')
				$sSQL.=' ORDER BY products.pid';
			elseif($sortorder=='idd')
				$sSQL.=' ORDER BY products.pid DESC';
			elseif($sortorder=='')
				$sSQL.=' ORDER BY pName';
			elseif($sortorder=='na2')
				$sSQL.=' ORDER BY pName2';
			elseif($sortorder=='na3')
				$sSQL.=' ORDER BY pName3';
			elseif($sortorder=='nad')
				$sSQL.=' ORDER BY pName DESC';
			elseif($sortorder=='pra')
				$sSQL.=' ORDER BY pPrice';
			elseif($sortorder=='prd')
				$sSQL.=' ORDER BY pPrice DESC';
			elseif($sortorder=='daa')
				$sSQL.=' ORDER BY pDateAdded';
			elseif($sortorder=='dad')
				$sSQL.=' ORDER BY pDateAdded DESC';
			elseif($sortorder=='poa')
				$sSQL.=' ORDER BY pOrder';
			elseif($sortorder=='pod')
				$sSQL.=' ORDER BY pOrder DESC';
			elseif($sortorder=='sta')
				$sSQL.=' ORDER BY products.pInStock';
			elseif($sortorder=='std')
				$sSQL.=' ORDER BY products.pInStock DESC';
			if(@$adminproductsperpage=='') $adminproductsperpage=200;
			$tmpSQL='SELECT COUNT(DISTINCT products.pId) AS bar' . $sSQL;
			$sSQL='SELECT DISTINCT ' . $columnlist . $sSQL;
			if(@$noautocheckorphans==TRUE && @$_REQUEST['disp']!='4') $sSQL=str_replace('rootSection,','',$sSQL);
			$allprods=ect_query($tmpSQL) or ect_error();
			$rs=ect_fetch_assoc($allprods);
			$iNumOfPages=ceil($rs['bar']/$adminproductsperpage);
			ect_free_result($allprods);
			$sSQL.=' LIMIT ' . ($adminproductsperpage*($CurPage-1)) . ', ' . $adminproductsperpage;
			$result=ect_query($sSQL) or ect_error();
			$haveerrprods=FALSE;
			if(ect_num_rows($result)>0){
				$pblink='<a href="adminprods.php?rid=' . @$_REQUEST['rid'] . '&disp=' . @$_REQUEST['disp'] . '&scat=' . $scat . '&stext=' . urlencode($stext) . '&stype=' . $stype . '&sprice=' . urlencode($sprice) . '&pg=';
				if($iNumOfPages>1) print '<tr><td colspan="6" align="center">' . writepagebar($CurPage,$iNumOfPages,$yyPrev,$yyNext,$pblink,FALSE) . '</td></tr>';
				displayheaderrow();
				$addcomma='';
				while($rs=ect_fetch_assoc($result)){
					displayprodrow($rs,$rowcounter++);
					$pidlist.=$addcomma . "'" . $rs['pID'] . "'";
					$addcomma=',';
				}
				if($haveerrprods) print '<tr><td width="100%" colspan="6"><br />' . $redasterix . $yySeePr . '</td></tr>';
				if($iNumOfPages>1) print '<tr><td colspan="6" align="center">' . writepagebar($CurPage,$iNumOfPages,$yyPrev,$yyNext,$pblink,FALSE) . '</td></tr>';
			}else{
				print '<tr><td width="100%" colspan="6" align="center"><br />' . $yyPrNone . '<br />&nbsp;</td></tr>';
			}
			ect_free_result($result);
		}
	}else{
		if(@$seocategoryurls&&!@$noencodedslasheswarning){
			$sSQL="SELECT products.pID,pName,pName2,pName3,pDisplay,pSell,pExemptions,pShipping,pShipping2,pInStock,0 AS rootSection,pStockByOpts,pPrice,pWholesalePrice,pListPrice,pOrder,pRecommend,pGiftWrap,pBackOrder,pStaticPage,pStaticURL,pSKU,pWeight,pTax FROM products WHERE pName LIKE '%".escape_string('\\')."%' OR pName LIKE '%".escape_string('/')."%'";
			$result=ect_query($sSQL) or ect_error();
			if(ect_num_rows($result)>0){
				print '<tr><td colspan="6" style="color:#FF0000">You have the $seodetailurls parameter set but have products with slashes (&#47; or &#92;) in the product name and these will not display properly. Consider using the HTML entities &amp;#47; for &#47; and &amp;#92; for &#92;. Alternatively you can ask your host to set the &quot;AllowEncodedSlashes On&quot; Apache directive.<br /><span style="color:#000000;font-weight:bold">(If this Apache directive is already set, you can disable this warning with the parameter $noencodedslasheswarning=TRUE.)</span></td></tr>';
				displayheaderrow();
				while($rs=ect_fetch_assoc($result)){
					displayprodrow($rs,$rowcounter++);
				}
			}
		}
		if(trim(@$detlinkspacechar)!=''){
			$sSQL="SELECT products.pID,pName,pName2,pName3,pDisplay,pSell,pExemptions,pShipping,pShipping2,pInStock,0 AS rootSection,pStockByOpts,pPrice,pWholesalePrice,pListPrice,pOrder,pRecommend,pGiftWrap,pBackOrder,pStaticPage,pStaticURL,pSKU,pWeight,pTax FROM products WHERE pStaticURL LIKE '%".escape_string($detlinkspacechar)."%'";
			$result=ect_query($sSQL) or ect_error();
			if(ect_num_rows($result)>0){
				print '<tr><td colspan="6" style="color:#FF0000">You have the $detlinkspacechar parameter set as &quot;' . $detlinkspacechar. '&quot; but have products where the Static URL uses this character and these will not display properly. Consider removing the $detlinkspacechar parameter, or replacing it with a space in the Static URL for these products.</td></tr>';
				displayheaderrow();
				while($rs=ect_fetch_assoc($result)){
					displayprodrow($rs,$rowcounter++);
				}
			}
		}
	}	?>
			  <tr>
				<td align="center" style="white-space:nowrap"><?php if($resultcounter>0 && $pract!='' && $pract!='none' && $rid=='') print '<input type="hidden" name="resultcounter" id="resultcounter" value="'.$resultcounter.'" /><input type="button" value="'.$yyUpdate.'" onclick="quickupdate()" /> <input type="reset" value="'.$yyReset.'" />'; else print '&nbsp;'?></td>
                <td width="100%" colspan="5" align="center"><br /><a href="admin.php"><strong><?php print $yyAdmHom?></strong></a><br />&nbsp;<br /></td>
			  </tr>
            </table>
		  </form>
		  </td>
        </tr>
      </table>
<script type="text/javascript">
var pa=[];
<?php print $jscript?>
	function patch_pid(tid){
		return tid.replace('.','ect_dot_xzq');
	}
	for(var pidind in pa){
		var ttr=document.getElementById('tr'+pidind);
		ttr.cells[0].className='minicell';
		ttr.cells[3].style.textAlign='center';
		ttr.cells[4].style.textAlign='center';
		ttr.cells[5].style.textAlign='center';
		ttr.cells[5].style.whiteSpace='nowrap';
		ttr.cells[1].innerHTML=pa[pidind][1];
<?php	if($rid==''){ ?>
		ttr.cells[4].innerHTML='<input type="button" id="rel'+pa[pidind][1]+'" value="<?php print jsescape(htmlspecials($yyRelate))?>" onclick="rel(\''+pa[pidind][1]+'\')" />';
		ttr.cells[5].innerHTML='<input type="button" value="M" style="width:30px" onclick="mr(\''+pa[pidind][1]+'\')" title="<?php print jsescape(htmlspecials($yyModify))?>" />&nbsp;' +
			'<input type="button" value="C" style="width:30px" onclick="cr(\''+pa[pidind][1]+'\')" title="<?php print jsescape(htmlspecials($yyClone))?>" />&nbsp;' +
			'<input type="button" value="X" style="width:30px" onclick="dr(\''+pa[pidind][1]+'\')" title="<?php print jsescape(htmlspecials($yyDelete))?>" />';
		ttr.cells[0].innerHTML=
<?php		if($qetype=='text'){ ?>
	pa[pidind][0]===false?'-':'<input type="text" id="chkbx'+pidind+'" size="<?php print $qesize?>" onchange="this.name=\'pra_'+patch_pid(pa[pidind][1])+'\'" value="'+pa[pidind][0]+'" tabindex="'+(pidind+1)+'" />';
<?php		}elseif($qetype=="delbox"){ ?>
	'<input type="checkbox" id="chkbx'+pidind+'" onchange="this.name=\'pra_'+patch_pid(pa[pidind][1])+'\'" value="del" tabindex="'+(pidind+1)+'" />';
<?php		}elseif($qetype=="checkbox"){ ?>
	'<input type="hidden" id="pra_'+pa[pidind][1]+'" value="1" /><input type="checkbox" id="chkbx'+pidind+'" onchange="this.name=\'prb_'+patch_pid(pa[pidind][1])+'\';document.getElementById(\'pra_'+pa[pidind][1]+'\').name=\'pra_'+patch_pid(pa[pidind][1])+'\'" value="1" '+(pa[pidind][0]==1?'checked="checked" ':'')+'tabindex="'+(pidind+1)+'" />';
<?php		}else{ ?>
	'&nbsp;';
<?php		} ?>
	ttr.cells[3].innerHTML='<input type="button" '+(pa[pidind][2]?' style="color:#F4E64B"':'')+' value="<?php print jsescape(htmlspecials($yyAssign))?>" onclick="dsc(\''+pa[pidind][1]+'\')" />';
<?php	} ?>
	}
<?php
	if($pidlist!='' && $rid==''){
		print "\r\n" . 'function setcl(tid){document.getElementById(\'rel\'+tid).style.color=\'#F4E64B\';}' . "\r\n";
		$sSQL='SELECT DISTINCT rpProdId FROM relatedprods WHERE rpProdId IN (' . $pidlist . ')';
		if(@$relatedproductsbothways==TRUE) $sSQL.=' UNION SELECT DISTINCT rpRelProdId FROM relatedprods WHERE rpRelProdId IN (' . $pidlist . ')';
		$result=ect_query($sSQL) or ect_error();
		while($rs=ect_fetch_assoc($result))
			print "setcl('" . $rs['rpProdId'] . "');\r\n";
		ect_free_result($result);
		
	} ?>
</script>
<?php
}
?>
