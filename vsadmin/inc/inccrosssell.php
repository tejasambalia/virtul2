<?php
//This code is copyright (c) Internet Business Solutions SL, all rights reserved.
//The contents of this file are protected under law as the intellectual property of Internet
//Business Solutions SL. Any use, reproduction, disclosure or copying of any kind 
//without the express and written permission of Internet Business Solutions SL is forbidden.
//Author: Vince Reid, vince@virtualred.net
global $alreadygotadmin,$thesessionid,$Count,$usecsslayout,$storeurl,$csalsoboughttitle,$useStockManagement,$noshowoutofstock,$csrecommendedtitle,$csrelatedtitle,$csbestsellerstitle,$crosssellnotsection,$forcedetailslink,$prodlist,$orsortby,$productcolumns,$csnobuyorcheckout,$csnoshowdiscounts,$csnoproductoptions,$prodseparator,$crosssellcolumns,$crosssellrows,$noshowdiscounts,$csrecommendedtitle,$crosssellaction,$csstyleprefix,$countryTaxRate,$defimagejs,$optjs;
if(@$_SERVER['CONTENT_LENGTH']!='' && $_SERVER['CONTENT_LENGTH'] > 10000) exit;
$WSP=$OWSP='';
$TWSP='pPrice';
$cs=@$csstyleprefix;
get_wholesaleprice_sql();
if(@$crosssellcolumns==''){ if(@$productcolumns=='') $crosssellcolumns=3; else $crosssellcolumns=$productcolumns; }
if(@$crosssellrows=='') $crosssellrows=1;
$numberofproducts = $crosssellcolumns * $crosssellrows;
$productcolumns=$crosssellcolumns;
if(@$csnobuyorcheckout==TRUE) $nobuyorcheckout=TRUE;
if(@$csnoshowdiscounts==TRUE) $noshowdiscounts=TRUE;
if(@$csnoproductoptions==TRUE) $noproductoptions=TRUE;
if(! @isset($forcedetailslink)) $forcedetailslink=TRUE;
$iNumOfPages=1;
$showcategories=FALSE;
$magictoolboxproducts='';
$isrootsection=TRUE;
if(! @isset($Count)) $Count=0; else $Count=($Count+$crosssellcolumns)-($Count % $crosssellcolumns);
$catid = '0';
if(@$_SESSION['sortby']!='') $sortBy=(int)($_SESSION['sortby']);
if(@$orsortby!='') $sortBy=$orsortby;
if(@$sortBy==2)
	$sSortBy = ' ORDER BY products.pId';
elseif(@$sortBy==3)
	$sSortBy = ' ORDER BY '.$TWSP;
elseif(@$sortBy==4)
	$sSortBy = ' ORDER BY '.$TWSP.' DESC';
elseif(@$sortBy==5)
	$sSortBy = '';
elseif(@$sortBy==6)
	$sSortBy = ' ORDER BY pOrder';
elseif(@$sortBy==7)
	$sSortBy = ' ORDER BY pOrder DESC';
elseif(@$sortBy==8)
	$sSortBy = ' ORDER BY pDateAdded';
elseif(@$sortBy==9)
	$sSortBy = ' ORDER BY pDateAdded DESC';
else
	$sSortBy = ' ORDER BY '.getlangid('pName',1);
if(@$prodlist=='') $prodlist='';
if(getpost('mode')!='checkout' && getpost('mode')!='add' && getpost('mode')!='go' && getpost('mode')!='paypalexpress1' && getpost('mode')!='authorize'){
	$alreadygotadmin = getadminsettings();
	$prodfilter=0;
	$thesessionid=getsessionid();
	if(@$_SESSION['clientID']!='' && @$_SESSION['clientLoginLevel']!='') $minloglevel=$_SESSION['clientLoginLevel']; else $minloglevel=0;
	$result2=ect_query('SELECT sectionID FROM sections WHERE sectionDisabled>'.$minloglevel) or ect_error();
	$addcomma='';
	if(@$crosssellnotsection=='') $crosssellnotsection=''; else $addcomma=',';
	while($rs2=ect_fetch_assoc($result2)){
		$crosssellnotsection.=$addcomma . $rs2['sectionID'];
		$addcomma=',';
	}
	ect_free_result($result2);
	$crosssellactionarr = explode(',', @$crosssellaction);
	for($csindex=0; $csindex < count($crosssellactionarr); $csindex++){
		$crosssellaction=trim($crosssellactionarr[$csindex]);
		$addcomma=''; $relatedlist='';
		if($crosssellaction=='alsobought'){ // Those who bought what's in your cart also bought.
			if(@$csalsoboughttitle=='') $crossselltitle='Customers who bought these products also bought.'; else $crossselltitle=$csalsoboughttitle;
			if($prodlist==''){
				$addcomma='';
				$sSQL = "SELECT cartProdID FROM cart WHERE cartCompleted=0 AND cartSessionID='" . escape_string($thesessionid) . "'";
				$result=ect_query($sSQL) or ect_error();
				while($rs=ect_fetch_assoc($result)){
					$prodlist.=$addcomma . "'" . escape_string($rs['cartProdID']) . "'";
					$addcomma=',';
				}
				ect_free_result($result);
			}
			$addcomma=''; $sessionlist='';
			if($prodlist!=''){
				$sSQL = 'SELECT cartSessionID,COUNT(cartSessionID),MAX(cartDateAdded) as maxdateadded FROM cart WHERE cartProdID IN (' . $prodlist . ') GROUP BY cartSessionID HAVING COUNT(cartSessionID) > 1 ORDER BY maxdateadded DESC LIMIT 0,100';
				// print $sSQL . '<br>';
				$result=ect_query($sSQL) or ect_error();
				while($rs=ect_fetch_assoc($result)){
					$sessionlist.=$addcomma . "'" . escape_string($rs['cartSessionID']) . "'";
					$addcomma=',';
				}
				ect_free_result($result);
			}
			if($prodlist!='' && $sessionlist!=''){
				$sSQL = 'SELECT cartProdID FROM cart INNER JOIN products ON cart.cartProdId=products.pID WHERE pDisplay<>0 AND cartSessionID IN (' . $sessionlist . ') AND NOT (cartProdID IN (' . $prodlist . '))' . (@$crosssellnotsection!='' ? ' AND NOT (pSection IN (' . $crosssellnotsection . '))' : '') . ' ORDER BY cartDateAdded DESC LIMIT 0,' . $numberofproducts;
				// print $sSQL . '<br>';
				$addcomma='';
				$relatedlist='';
				$result=ect_query($sSQL) or ect_error();
				while($rs=ect_fetch_assoc($result)){
					$relatedlist.=$addcomma . "'" . escape_string($rs['cartProdID']) . "'";
					$addcomma=',';
				}
				ect_free_result($result);
			}
		}elseif($crosssellaction=='recommended'){ // Recommended products (Needs v5.1)
			if(@$csrecommendedtitle=='') $crossselltitle='These products are our current recommendations for you.'; else $crossselltitle=$csrecommendedtitle;
			if($prodlist==''){
				$sSQL = "SELECT cartProdID FROM cart WHERE cartCompleted=0 AND cartSessionID='" . escape_string($thesessionid) . "'";
				$result=ect_query($sSQL) or ect_error();
				$addcomma='';
				while($rs=ect_fetch_assoc($result)){
					$prodlist.=$addcomma . "'" . escape_string($rs['cartProdID']) . "'";
					$addcomma=',';
				}
				ect_free_result($result);
			}
			$sSQL = 'SELECT pID FROM products WHERE pDisplay<>0 AND pRecommend<>0';
			if($prodlist!='') $sSQL.=' AND NOT (pID IN (' . $prodlist . '))';
			if($crosssellnotsection!='') $sSQL.=' AND NOT (pSection IN (' . $crosssellnotsection . '))';
			$addcomma=''; $relatedlist='';
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result)){
				$relatedlist.=$addcomma . "'" . escape_string($rs['pID']) . "'";
				$addcomma=',';
			}
			ect_free_result($result);
		}elseif($crosssellaction=='related'){ // Products recommended with this product (Would need v5.1)
			if(@$csrelatedtitle=='') $crossselltitle='These products are recommended with items in your cart.'; else $crossselltitle=$csrelatedtitle;
			if($prodlist==''){
				$addcomma='';
				$sSQL = "SELECT cartProdID FROM cart WHERE cartCompleted=0 AND cartSessionID='" . escape_string($thesessionid) . "'";
				$result=ect_query($sSQL) or ect_error();
				while($rs=ect_fetch_assoc($result)){
					$prodlist.=$addcomma . "'" . escape_string($rs['cartProdID']) . "'";
					$addcomma=',';
				}
				ect_free_result($result);
			}
			if($prodlist!=''){
				$sSQL = 'SELECT rpRelProdID FROM relatedprods WHERE (rpProdID IN (' . $prodlist . ') AND NOT (rpRelProdID IN (' . $prodlist . ')))';
				if(@$relatedproductsbothways==TRUE) $sSQL.=' UNION SELECT rpProdID FROM relatedprods WHERE (rpRelProdID IN (' . $prodlist . ') AND NOT (rpProdID IN (' . $prodlist . ')))';
				$addcomma=''; $relatedlist='';
				$result=ect_query($sSQL) or ect_error();
				while($rs=ect_fetch_assoc($result)){
						$relatedlist.=$addcomma . "'" . escape_string($rs['rpRelProdID']) . "'";
						$addcomma=',';
				}
				ect_free_result($result);
			}
		}elseif($crosssellaction=='bestsellers'){ // Top X best sellers
			if(@$csbestsellerstitle=='') $crossselltitle='These are our current best sellers.'; else $crossselltitle=$csbestsellerstitle;
			$sSQL = 'SELECT cartProdID,COUNT(cartProdID) AS pidcount FROM cart INNER JOIN products ON cart.cartProdID=products.pID WHERE cartCompleted<>0 AND pDisplay<>0 ' . (@$crosssellsection!='' ? ' AND pSection IN (' . $crosssellsection . ')' : '') . (@$crosssellnotsection!='' ? ' AND NOT (pSection IN (' . $crosssellnotsection . '))' : '');
			if(@$bestsellerlimit!='') $sSQL.=" AND cartDateAdded>'".date('Y-m-d H:i:s', time()-($bestsellerlimit*60*60*24))."'";
			$sSQL.=' GROUP BY cartProdID ORDER BY pidcount DESC LIMIT 0,' . $numberofproducts;
			$relatedlist='';
			$result=ect_query($sSQL) or ect_error();
			while($rs=ect_fetch_assoc($result)){
				$relatedlist.=$addcomma . "'" . escape_string($rs['cartProdID']) . "'";
				$addcomma=',';
			}
			ect_free_result($result);
		}else
			if($crosssellaction!='') print '<p>Unrecognized crosssell action ' . $crosssellaction . '</p>';
		if($relatedlist!=''){
			$saveprodlist=$prodlist;
			$prodlist=$relatedlist;
			$sSQL = 'SELECT pId,pSKU,' . getlangid('pName',1) . ',' . $WSP . 'pPrice,pListPrice,pSection,pSell,pStockByOpts,pStaticPage,pStaticURL,pInStock,pExemptions,pTax,pTotRating,pNumRatings,pBackOrder,'.(@$manufacturerfield!=''?getlangid('scName',131072).',':'')."'' AS " . getlangid('pDescription',2) . ',' . getlangid('pLongDescription',4) . ' FROM products '.(@$manufacturerfield!=''?'LEFT OUTER JOIN searchcriteria on products.pManufacturer=searchcriteria.scID ':'').'WHERE pDisplay<>0 AND pId IN (' . $prodlist . ')';
			$sSQL.=(@$crosssellnotsection!='' && $crosssellaction=='related' ? ' AND NOT (pSection IN (' . $crosssellnotsection . '))' : '');
			if($useStockManagement && @$noshowoutofstock==TRUE) $sSQL.=' AND (pInStock>0 OR pStockByOpts<>0)';
			$sSQL.=$sSortBy;
			$allprods=ect_query($sSQL) or ect_error();
			$adminProdsPerPage=ect_num_rows($allprods);
			if(ect_num_rows($allprods) > 0){
				print '<p class="cstitle"><strong>' . $crossselltitle . '</strong></p>';
				include './vsadmin/inc/incproductbody2.php';
			}
			ect_free_result($allprods);
			$prodlist=$saveprodlist;
		}
	}
}
?>