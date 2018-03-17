<?php
//This code is copyright (c) Internet Business Solutions SL, all rights reserved.
//The contents of this file are protected under law as the intellectual property of Internet
//Business Solutions SL. Any use, reproduction, disclosure or copying of any kind 
//without the express and written permission of Internet Business Solutions SL is forbidden.
//Author: Vince Reid, vince@virtualred.net
if(!@$GLOBALS['incfunctionsdefined']){print 'No incfunctions.php file';exit;}
global $alreadygotadmin;
if(@$_SERVER['CONTENT_LENGTH']!='' && $_SERVER['CONTENT_LENGTH'] > 10000) exit;
$nosearchrelevance=FALSE;
$iNumOfPages=0;
$showcategories=FALSE;
$gotcriteria=FALSE;
$numcats=0;
$catid=0;
$nobox='';
$isrootsection=FALSE;
$topsectionids='0';
if(! is_numeric(getget('pg')) || strlen(getget('pg'))>8)
	$CurPage=1;
else
	$CurPage=max(1, (int)getget('pg'));
if(getget('nobox')=='true' || getpost('nobox')=='true')
	$nobox='true';
$WSP=$OWSP='';
$TWSP='pPrice';
get_wholesaleprice_sql();
$tsID='';
$scat=preg_replace('/[^,\d]/','',@$_REQUEST['scat']);
$scat=preg_replace('/,+/',',',$scat);
$scat=preg_replace('/^,|,$/','',$scat);
$sman=preg_replace('/[^,\d]/','',@$_REQUEST['sman']);
$sman=preg_replace('/,+/',',',$sman);
$sman=preg_replace('/^,|,$/','',$sman);
$stext=getrequest('stext');
$stype=getrequest('stype');
if($stype!='any' && $stype!='exact')$stype='';
$sprice=strip_tags(getrequest('sprice'));
if(!is_numeric($sprice))$sprice='';
$minprice=strip_tags(getrequest('sminprice'));
if(!is_numeric($minprice))$minprice='';
if(substr($scat,0,2)=='ms') $thecat=substr($scat,2); else $thecat=$scat;
$thecat=str_replace("'",'',$thecat);
$catarr=explode(',', $thecat);
$manarr=explode(',', $sman);
$Count=0;
if(strtolower($adminencoding)=='iso-8859-1') $raquo='»'; else $raquo='&raquo;';
if(@$magictoolboxproducts!=''){
	print '<script src="' . ($magictoolboxproducts=='MagicTouch' ? 'http://www.magictoolbox.com/mt/' . $magictouchid . '/magictouch.js' : strtolower($magictoolboxproducts) . '/' . strtolower($magictoolboxproducts) . '.js') . '" type="text/javascript"></script>' . @$magictooloptionsjsproducts;
	$magictool=$magictoolboxproducts;
}
function writemenulevel($id,$itlevel){
	global $allcatsa,$numcats,$thecat,$catarr,$raquo;
	if($itlevel<10){
		for($wmlindex=0; $wmlindex < $numcats; $wmlindex++){
			if($allcatsa[$wmlindex]['topSection']==$id){
				print "<option value='" . $allcatsa[$wmlindex]['sectionID'] . "'";
				if($catarr[0]==$allcatsa[$wmlindex]['sectionID']) print ' selected="selected">'; else print '>';
				for($index=0; $index < $itlevel-1; $index++)
					print $raquo . ' ';
				print $allcatsa[$wmlindex][getlangid('sectionName',256)] . "</option>\n";
				if($allcatsa[$wmlindex]['rootSection']==0) writemenulevel($allcatsa[$wmlindex]['sectionID'],$itlevel+1);
			}
		}
	}
}
$pblink='<a class="ectlink" href="'.htmlentities(@$_SERVER['PHP_SELF']).'?nobox=' . $nobox . '&amp;scat=' . urlencode($scat) . '&amp;stext=' . urlencode($stext) . '&amp;stype=' . $stype . '&amp;sprice=' . urlencode($sprice) . ($minprice!=''?"&amp;sminprice=".$minprice:'') . ($sman!=''?'&amp;sman='.$sman:'') . '&amp;pg=';
$nofirstpg=FALSE;
function getlike($fie,$t,$tjn){
	global $sNOTSQL;
	if(substr($t, 0, 1)=='-'){ // pSKU excluded to work around NULL problems
		if($fie!='pSKU' && $fie!='pSearchParams') $sNOTSQL.=$fie." LIKE '%".substr($t, 1)."%' OR ";
	}else
		return $fie . " LIKE '%".$t."%' ".$tjn;
}
$alreadygotadmin=getadminsettings();
if(@$orprodsperpage!='') $adminProdsPerPage=$orprodsperpage;
checkCurrencyRates($currConvUser,$currConvPw,$currLastUpdate,$currRate1,$currSymbol1,$currRate2,$currSymbol2,$currRate3,$currSymbol3);
if(@$_SESSION['clientLoginLevel']!='') $minloglevel=$_SESSION['clientLoginLevel']; else $minloglevel=0;
if(@$nosearchdescription && @$nosearchlongdescription) $nosearchrelevance=TRUE;
if(getpost('posted')=='1' || getget('pg')!=''){
	if(getpost('sortby')!='') $_SESSION['sortby']=(int)getpost('sortby');
	if(@$_SESSION['sortby']!='') $dosortby=(int)($_SESSION['sortby']);
	if(@$orsortby!='') $dosortby=$orsortby;
	if($dosortby==5) $nosearchrelevance=TRUE;
	if($thecat!=''){
		$sSQL='SELECT DISTINCT '.(! @$nosearchrelevance?'0 AS relevanceorder,':'').'products.pId,'.getlangid('pName',1).','.$WSP.'pPrice,pOrder,pDateAdded FROM multisections RIGHT JOIN products ON products.pId=multisections.pId INNER JOIN sections on products.pSection=sections.sectionID WHERE sectionDisabled<='.$minloglevel.' AND pDisplay<>0 ';
		$gotcriteria=TRUE;
		$sectionids=getsectionids($thecat, FALSE);
		if($sectionids!='') $sSQL.="AND (products.pSection IN (" . $sectionids . ") OR multisections.pSection IN (" . $sectionids . ")) ";
	}else
		$sSQL='SELECT DISTINCT '.(! @$nosearchrelevance?'0 AS relevanceorder,':'').'products.pId,'.getlangid('pName',1).','.$WSP.'pPrice,pOrder,pDateAdded FROM products INNER JOIN sections on products.pSection=sections.sectionID WHERE sectionDisabled<='.$minloglevel.' AND pDisplay<>0 ';
	if(is_numeric($sprice)){
		$gotcriteria=TRUE;
		$sSQL.="AND ".$TWSP."<='" . escape_string($sprice) . "' ";
	}
	if(is_numeric($minprice)){
		$gotcriteria=TRUE;
		$sSQL.="AND ".$TWSP.">='" . escape_string($minprice) . "' ";
	}
	if($sman!=''){
		$gotcriteria=TRUE;
		$sSQL.="AND pManufacturer IN (" . escape_string($sman) . ") ";
	}
	if(trim($stext)!=''){
		$gotcriteria=TRUE;
		$Xstext=escape_string(substr($stext, 0, 1024));
		$aText=explode(' ',$Xstext);
		$aFields[0]='products.pId';
		$aFields[1]=getlangid('pName',1);
		$aFields[2]=getlangid('pDescription',2);
		$aFields[3]=getlangid('pLongDescription',4);
		$aFields[4]='pSKU';
		$aFields[5]='pSearchParams';
		if(! @$nosearchrelevance) $sSQL.="''||SPBLOCKMARKER||''";
		for($relindex=0;$relindex<=(@$nosearchrelevance?0:1);$relindex++){
			if($stype=='exact'){
				$relsql[$relindex]='AND ';
				if(substr($Xstext, 0, 1)=='-'){ $relsql[$relindex].='NOT '; $Xstext=substr($Xstext, 1); $isnot=TRUE; }else $isnot=FALSE;
				if($relindex==0 || @$nosearchrelevance)
					$relsql[$relindex].="(products.pId LIKE '%".$Xstext."%' OR ".getlangid('pName',1)." LIKE '%".$Xstext."%'".(@$nosearchparams?'':" OR pSearchParams LIKE '%".$Xstext."%'").($isnot||@$nosearchsku? '' : " OR pSKU LIKE '%".$Xstext."%'") . (!@$nosearchrelevance?') ':'');
				if($relindex==1 || @$nosearchrelevance)
					$relsql[$relindex].=(@$nosearchrelevance?' OR ':'(').(@$nosearchdescription?'':getlangid('pDescription',2)." LIKE '%".$Xstext."%'").(@$nosearchlongdescription?'':(@$nosearchdescription?'':' OR ').getlangid('pLongDescription',2)." LIKE '%".$Xstext."%'") . ') ';
			}elseif(count($aText) < 24){
				$sNOTSQL=''; $sYESSQL='';
				if($stype=='any'){
					for($index=0;$index<=5;$index++){
						$tmpSQL='';
						$arrelms=count($aText);
						foreach($aText as $theopt){
							if(is_array($theopt))$theopt=$theopt[0];
							if(! ((@$nosearchdescription==TRUE && $index==2) || (@$nosearchlongdescription==TRUE && $index==3) || (@$nosearchsku==TRUE && $index==4) || (@$nosearchparams==TRUE && $index==5)))
								if((($index==0 || $index==1 || $index==4 || $index==5) && $relindex==0) || (($index==2 || $index==3) && $relindex==1) || @$nosearchrelevance) $tmpSQL.=getlike($aFields[$index], $theopt, 'OR ');
						}
						if($tmpSQL!='') $sYESSQL.= '(' . substr($tmpSQL, 0, strlen($tmpSQL)-3) . ') ';
						if($tmpSQL!='') $sYESSQL.='OR ';
					}
					$sYESSQL=substr($sYESSQL, 0, -3);
				}else{
					foreach($aText as $theopt){
						$tmpSQL='';
						$arrelms=count($aText);
						for($index=0;$index<=5;$index++){
							if(is_array($theopt))$theopt=$theopt[0];
							if(! ((@$nosearchdescription==TRUE && $index==2) || (@$nosearchlongdescription==TRUE && $index==3) || (@$nosearchsku==TRUE && $index==4) || (@$nosearchparams==TRUE && $index==5)))
								if((($index==0 || $index==1 || $index==4 || $index==5) && $relindex==0) || (($index==2 || $index==3) && $relindex==1) || @$nosearchrelevance) $tmpSQL.=getlike($aFields[$index], $theopt, 'OR ');
						}
						if($tmpSQL!='') $sYESSQL.= '(' . substr($tmpSQL, 0, strlen($tmpSQL)-3) . ') ';
						if($tmpSQL!='') $sYESSQL.='AND ';
					}
					$sYESSQL=substr($sYESSQL, 0, -4);
				}
				$relsql[$relindex]='';
				if($sYESSQL!='') $relsql[$relindex].='AND (' . $sYESSQL . ') ';
				if($sNOTSQL!='') $relsql[$relindex].='AND NOT (' . substr($sNOTSQL, 0, strlen($sNOTSQL)-4) . ')';
			}
		}
		if(@$nosearchrelevance) $sSQL.=$relsql[0];
	}else
		$nosearchrelevance=TRUE;
	if(! $gotcriteria) $nosearchrelevance=TRUE;
	if(@$dosortby==2 || @$dosortby==12)
		$sSortBy=' ORDER BY '.(! @$nosearchrelevance?'1,':'').'pId'.(@$dosortby==12?' DESC':'');
	elseif(@$dosortby==3||@$dosortby==4)
		$sSortBy=' ORDER BY '.(! @$nosearchrelevance?'1,':'').'pPrice'.(@$dosortby==4?' DESC,pId':',pId');
	elseif(@$dosortby==5)
		$sSortBy=(! @$nosearchrelevance?'1,':'');
	elseif(@$dosortby==6||@$dosortby==7)
		$sSortBy=' ORDER BY '.(! @$nosearchrelevance?'1,':'').'pOrder'.(@$dosortby==7?' DESC,pId':',pId');
	elseif(@$dosortby==8||@$dosortby==9)
		$sSortBy=' ORDER BY '.(! @$nosearchrelevance?'1,':'').'pDateAdded'.(@$dosortby==9?' DESC,pId':',pId');
	else
		$sSortBy=' ORDER BY '.(! @$nosearchrelevance?'1,':'').getlangid('pName',1);
	if($useStockManagement && @$noshowoutofstock==TRUE) $sSQL.=' AND (pInStock>0 OR pStockByOpts<>0)';
	$relevantmatches='';
	$userelevantmatches=TRUE;
	$numrelevantmatches=0;
	if(!$nosearchrelevance){
		$allprods=ect_query(str_replace("''||SPBLOCKMARKER||''",$relsql[0],$sSQL)) or ect_error();
		while($rs=ect_fetch_assoc($allprods)){
			$relevantmatches.="'".escape_string($rs['pId'])."',";
			$numrelevantmatches++;
			if($numrelevantmatches>100){ $userelevantmatches=FALSE; break; }
		}
		ect_free_result($allprods);
	}
	if($relevantmatches!='') $relevantmatches=substr($relevantmatches,0,-1); else $userelevantmatches=FALSE;
	if($gotcriteria){
		if($numrelevantmatches>=100||$nosearchrelevance)
			$sSQL=str_replace("''||SPBLOCKMARKER||''",$relsql[0],$sSQL);
		else
			$sSQL=($relevantmatches!=''?str_replace("''||SPBLOCKMARKER||''",$relsql[0],$sSQL) . ' UNION ALL ':'') . str_replace("''||SPBLOCKMARKER||''",$relsql[1].($userelevantmatches?" AND NOT products.pId IN (".$relevantmatches.')':''),str_replace('0 AS relevanceorder,','1 AS relevanceorder,',$sSQL));
	}
	$sSQL=preg_replace('/SELECT/','SELECT SQL_CALC_FOUND_ROWS',$sSQL,1) . $sSortBy . ' LIMIT ' . ($adminProdsPerPage*($CurPage-1)) . ', ' . $adminProdsPerPage;
	//print $sSQL . "<br><br>";
	$allprods=ect_query($sSQL) or ect_error();
	if(ect_num_rows($allprods)==0)
		$success=FALSE;
	else{
		$success=TRUE;
		$prodlist='';
		$addcomma='';
		while($rs=ect_fetch_assoc($allprods)){
			//print "RO: " . $rs['relevanceorder'] . " : " . $rs['pId'] . "<br>";
			$prodlist.=$addcomma . "'" . $rs['pId'] . "'";
			$addcomma=',';
		}
		ect_free_result($allprods);
		$allprods=ect_query('SELECT FOUND_ROWS() AS bar') or ect_error();
		$rs=ect_fetch_assoc($allprods);
		$iNumOfPages=ceil($rs['bar']/$adminProdsPerPage);
		ect_free_result($allprods);
		$wantmanufacturer=(@$manufacturerfield!='' || (@$usedetailbodyformat==3 && strpos(@$cpdcolumns, 'manufacturer')!==FALSE));
		$sSQL='SELECT '.(! @$nosearchrelevance?'0 AS relevanceorder,':'').'pId,pSKU,'.getlangid('pName',1).','.$WSP.'pPrice,pListPrice,pSection,pSell,pStockByOpts,pStaticPage,pStaticURL,pInStock,pExemptions,pTax,pTotRating,pNumRatings,pBackOrder,'.($wantmanufacturer?getlangid('scName',131072).',':'').(@$shortdescriptionlimit===0?"'' AS ":'').getlangid('pDescription',2).','.getlangid('pLongDescription',4).' FROM products '.($wantmanufacturer?'LEFT OUTER JOIN searchcriteria on products.pManufacturer=searchcriteria.scID ':'').'WHERE pId IN (' . $prodlist . ')' . $sSortBy;
		//print $sSQL . "<br><br>";
		$allprods=ect_query($sSQL) or ect_error();
	}
}
if($nobox==''){
?>
	  <form method="get" action="search.php">
		  <input type="hidden" name="pg" value="1" />
            <table class="cobtbl" width="100%" border="0" cellspacing="1" cellpadding="3">
			  <tr>
                <td class="cobhl cobhdr" align="center" colspan="4" height="30">
                  <strong><?php print $GLOBALS['xxSrchPr']?></strong>
                </td>
              </tr>
			  <tr>
				<td class="cobhl" align="right"><?php print $GLOBALS['xxSrchFr']?>:</td>
				<td class="cobll"<?php print @$GLOBALS['nopriceanywhere']?'colspan="3"':''?>><input type="text" name="stext" size="20" maxlength="1024" value="<?php print htmlspecials($stext)?>" /></td>
<?php	if(!@$GLOBALS['nopriceanywhere']){
			if($mobilebrowser) print '</tr><tr>' ?>
				<td class="cobhl" align="right"><?php print $GLOBALS['xxSrchMx']?>:</td>
				<td class="cobll"><input type="text" name="sprice" size="10" maxlength="64" value="<?php print htmlspecials($sprice)?>" /></td>
<?php	} ?>
			  </tr>
			  <tr>
				<td class="cobhl" align="right"><?php print $GLOBALS['xxSrchTp']?>:</td>
				<td class="cobll"><select name="stype" size="1">
					<option value=""><?php print $GLOBALS['xxSrchAl']?></option>
					<option value="any" <?php if($stype=="any") print 'selected="selected"'?>><?php print $GLOBALS['xxSrchAn']?></option>
					<option value="exact" <?php if($stype=="exact") print 'selected="selected"'?>><?php print $GLOBALS['xxSrchEx']?></option>
					</select></td>
<?php	if($mobilebrowser) print '</tr><tr>' ?>
				<td class="cobhl" align="right"><?php print $GLOBALS['xxSrchCt']?>:</td>
				<td class="cobll"><select name="scat" size="1">
					<option value=""><?php print $GLOBALS['xxSrchAC']?></option>
<?php	$lasttsid=-1;
		if(@$nocategorysearch!=TRUE){
			$sSQL='SELECT sectionID,'.getlangid('sectionName',256).',topSection,rootSection FROM sections WHERE sectionDisabled<=' . $minloglevel . ' ';
			if(@$onlysubcats==TRUE) $sSQL.='AND rootSection=1 ORDER BY '.getlangid('sectionName',256); else $sSQL.='ORDER BY '.(@$sortcategoriesalphabetically?getlangid('sectionName',256):'sectionOrder');
			$allcats=ect_query($sSQL) or ect_error();
			while($row=ect_fetch_assoc($allcats)){
				$allcatsa[$numcats++]=$row;
			}
			ect_free_result($allcats);
		}
		if($numcats > 0) writemenulevel($catalogroot,1);
?>					</select></td>
              </tr>
<?php
	if(@$searchbymanufacturer!=''){ ?>
			  <tr>
			    <td class="cobhl" align="right"><?php print $searchbymanufacturer?>:</td>
				<td class="cobll"><select name="sman" size="1"><option value=""><?php print $GLOBALS['xxSeaAll']?></option><?php
		$sSQL='SELECT scID,'.getlangid('scName',131072).' FROM searchcriteria WHERE scGroup=0 ORDER BY '.getlangid('scName',131072);
		$result2=ect_query($sSQL) or ect_error();
		while($rs2=ect_fetch_assoc($result2)){
			print '<option value="' . $rs2['scID'] . '"';
			if($manarr[0]==$rs2['scID']) print ' selected="selected"';
			print '>' . $rs2[getlangid('scName',131072)] . "</option>\r\n";
		}
		ect_free_result($result2); ?></select></td>
<?php	if($mobilebrowser) print '</tr><tr>' ?>
			    <td class="cobll" colspan="2" align="center"><?php print imageorsubmit(@$imgsearch,$GLOBALS['xxSearch'],'search')?></td>
			  </tr>
<?php
	}else{ ?>
			  <tr>
<?php	if(! $mobilebrowser) print '<td class="cobhl">&nbsp;</td>' ?>
			    <td class="cobll" colspan="<?php print $mobilebrowser?'2':'3'?>" align="center"><?php print imageorsubmit(@$imgsearch,$GLOBALS['xxSearch'],'search')?></td>
			  </tr>
<?php
	} ?>
			</table>
		</form>
<?php
}
if(getpost('posted')=='1' || getget('pg')!=''){
	if(! @$usecsslayout){
		print '<table border="0" cellspacing="0" cellpadding="0" width="98%" align="center">';
		if(! $success)
			print '<tr><td align="center"><p>&nbsp;</p><p><strong>' . $GLOBALS['xxSrchNM'] . '</strong></p><p>&nbsp;</p></td></tr>';
		else
			print '<tr><td width="100%">';
	}else{
		print '<br /><div>';
		if(! $success)print '<div class="nosearchresults">' . $GLOBALS['xxSrchNM'] . '</div>';
	}
	if($success){
		if($usesearchbodyformat==3)
			include './vsadmin/inc/incproductbody3.php';
		elseif($usesearchbodyformat==2)
			include './vsadmin/inc/incproductbody2.php';
		else
			include './vsadmin/inc/incproductbody.php';
		if(! @$usecsslayout) print '</td></tr>';
	}
	if(@$usecsslayout) print '</div>'; else print '</table>';
	ect_free_result($allprods);
}
?>
