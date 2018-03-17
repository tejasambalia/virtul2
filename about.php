<?php
session_cache_limiter('none');
session_start();
ob_start();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><!-- #BeginTemplate "/Templates/Main.dwt" -->
<head>
<!-- #BeginEditable "doctitle" --> 
<title>Dreamweaver template</title>
<!-- #EndEditable -->
<link rel="stylesheet" type="text/css" href="style.css"/>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<script type="text/javascript" language="javascript1.2" src="popouttext.js">
</script>
</head>
<body onload="MM_preloadImages('images/tabov.gif','images/menu/cartov.gif','images/menu/searchov.gif','images/menu/contactov.gif','images/menu/aboutov.gif','images/menu/affiliatesov.gif')">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" bgcolor="#D2D2CA" width="136"><img src="images/logo.gif" width="136" height="85" alt=""/></td>
    <td bgcolor="#D2D2CA"><img src="images/clearpixel.gif" width="17" height="1" alt=""/></td>
    <td bgcolor="#D2D2CA" width="100%" valign="bottom"><img src="images/name.gif" width="231" height="34" alt=""/></td>
    <td bgcolor="#D2D2CA" align="right" width="164"><img src="images/topphoto.gif" width="164" height="66" alt=""/></td>
  </tr>
  <tr> 
    <td width="26"><img src="images/topleft.gif" width="17" height="26" alt=""/></td>
    <td class="topbg" align="right" valign="bottom" colspan="2"><a href="index.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image26','','images/tabov.gif',1)"><img name="Image26" border="0" src="images/tab.gif" width="80" height="16" alt=""/></a><a href="about.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image53','','images/menu/aboutov.gif',1)"><img name="Image53" border="0" src="images/menu/about.gif" width="80" height="16" alt=""/></a><a href="affiliate.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image54','','images/menu/affiliatesov.gif',1)"><img name="Image54" border="0" src="images/menu/affiliates.gif" width="80" height="16" alt=""/></a><a href="cart.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image41','','images/menu/cartov.gif',1)"><img name="Image41" border="0" src="images/menu/cart.gif" width="80" height="16" alt=""/></a><a href="search.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image42','','images/menu/searchov.gif',1)"><img name="Image42" border="0" src="images/menu/search.gif" width="80" height="16" alt=""/></a><a href="index.php#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image43','','images/menu/contactov.gif',1)"><img name="Image43" border="0" src="images/menu/contact.gif" width="80" height="16" alt=""/></a></td>
  </tr>
  <tr> 
    <td colspan="4" bgcolor="#AAAAAF"><img src="images/clearpixel.gif" width="1" height="1" alt=""/></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="dark" align="center" bgcolor="#F7F7F4" valign="top" height="145"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="145" valign="top"> 
<?php include "vsadmin/db_conn_open.php" ?>
<?php include "vsadmin/inc/languagefile.php" ?>
<?php include "vsadmin/includes.php" ?>
<?php include "vsadmin/inc/incfunctions.php" ?>
            <script language="JavaScript" type="text/JavaScript">
mymenu = new POTMenu
// Defines the size of the main menu items, width and height
mymenu.mainmenuitemsize(135,16)
// Defines the size of the sub menu items, width and height
mymenu.submenuitemsize(152,16)
// Defines the position of the sub menus in relation to the parent
mymenu.submenuplacing(0,0)
// Images that make up the border of the main menu. Optional.
mymenu.mainborderimages("images/greypixel.gif","images/greypixel.gif","images/clearpixel.gif","images/greypixel.gif")
// Corners of the images that make up the main menu border.
mymenu.mainbordercorners("images/greypixel.gif","images/greypixel.gif","images/greypixel.gif","images/greypixel.gif")
// Left width, right width and height of the top and bottom of the border
mymenu.mainborderdimensions(1,1,1)
// These lines are for the sub menu borders
mymenu.subborderimages("images/greypixel.gif","images/greypixel.gif","images/greypixel.gif","images/greypixel.gif")
mymenu.subbordercorners("images/greypixel.gif","images/greypixel.gif","images/greypixel.gif","images/greypixel.gif")
mymenu.subborderdimensions(1,1,1)
// Main menu cell color
mymenu.mainmenucolor("#F7F7F4")
// Sub menu cell color
mymenu.submenucolor("#F7F7F4")
// Centers text for Netscape 4.7
mymenu.netscapeadjust(3,3)
// The image that is show between the main menu items
mymenu.definemainspacer("images/greypixel.gif",1)
// The image that is show between the sub menu items
mymenu.definesubspacer("images/greypixel.gif",1)
// Do you want to "hide" (SELECT menus, OBJECT tags) when in the menu
mymenu.hideobjects(true,false)
// This line is required here
mymenu.startMenu()
// Define the main menu.
mymenu.addMenu("home","O HOME", "index.php")
mymenu.addMenu("main","O MAIN", "#")

// Please note, the following line of code is used to automatically create the links for the product sections
// If you do not want to use this feature, please delete this line of code in your includes/menu.htm file
// along with the corresponding section below.
mymenu.addMenu("products","O PRODUCTS", "categories.php")

mymenu.addMenu("help","O HELP", "#")
mymenu.addMenu("affiliates","O AFFILIATES", "affiliate.php")
mymenu.addMenu("search","O SEARCH", "search.php")
mymenu.addMenu("checkout","O CHECKOUT", "cart.php")
// This line is required after the main menu is defined.
mymenu.showMainMenu()
// Define the sub menus
mymenu.addSubMenu("main", "", "O ABOUT US", "about.php")
mymenu.addSubMenu("main", "", "O HELP", "help.php")
mymenu.addSubMenu("main", "", "O SERVICES", "services.php")
mymenu.addSubMenu("main", "", "O CONTACT", "contact.php")
mymenu.addSubMenu("main", "", "O EMAIL", "mailto:#")

// Delete these 7 lines and the corresponding lines above if you 
// don't want an automatically generated popout menu.
<?php
$menuprestr = "O ";
$menupoststr = "";
include "vsadmin/inc/incmenu.php";
?>

mymenu.addSubMenu("help", "faq", "O FAQ", "#")
mymenu.addSubMenu("help", "tutorials", "O TUTORIALS", "#")

mymenu.addSubMenu("faq", "", "O COMPATIBILITY", "#")
mymenu.addSubMenu("faq", "", "O DHTML MENUS", "#")
mymenu.addSubMenu("faq", "", "O DHTML MENUS", "#")
mymenu.addSubMenu("faq", "", "O GRAPHICS", "#")
mymenu.addSubMenu("faq", "", "O LICENSE", "#")


mymenu.addSubMenu("tutorials", "", "O TEMPLATE SET UP", "#")
mymenu.addSubMenu("tutorials", "", "O INCLUDE PAGES", "#")
mymenu.addSubMenu("tutorials", "navigation", "O NAVIGATION", "#")

mymenu.addSubMenu("navigation", "", "O FP NAVIGATION", "#")
mymenu.addSubMenu("navigation", "", "O DW NAVIGATION", "#")
mymenu.addSubMenu("navigation", "", "O GL NAVIGATION", "#")

// This line is required after all menu definitions are finished
mymenu.showMenu()
</script>
          </td>
        </tr>
      </table>
      <br/>
      <p><img src="images/avocado.jpg" width="110" height="110" alt=""/></p>
      <p><img src="images/leftline.gif" width="136" height="1" alt=""/></p>
      <p>Company name<br/>
        Address<br/>
        Telephone nos.</p>
      <p><img src="images/leftline.gif" width="136" height="1" alt=""/></p>
    </td>
    <td valign="top" class="leftbg" width="26">&nbsp;</td>
    <td valign="top" width="100%"><br/>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="17">&nbsp;</td>
          <td valign="top" width="100%"> <!-- #BeginEditable "Body" --> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3"><img src="images/welcome.gif" width="120"  height="16" alt=""/></td>
              </tr>
              <tr bgcolor="#AAAAAF"> 
                <td colspan="3"><img src="images/clearpixel.gif" width="1" height="1" alt=""/></td>
              </tr>
              <tr> 
                <td class="leftbg"><img src="images/clearpixel.gif" width="23" height="8" alt=""/></td>
                <td width="100%" valign="top"> 
                  <table width="98%" border="0" cellspacing="2" cellpadding="2" align="center">
                    <tr> 
                      <td> 
                        <p><b><br/>
                          <img src="images/mainicon.jpg" width="200" height="130" alt=""/><br/>
                          </b></p>
                      </td>
                      <td> 
                        <p><b>Ecommerce Plus Template</b></p>
                  
            <p>This layout is designed for Dreaweaver 3+. It is also XHTML compliant. 
              There are full instructions for using our shopping cart software 
              in the <a href="http://www.ecommercetemplates.com/free_downloads.asp"><b>User 
              Manual</b></a>. We also have a lively and helpful support forum 
              available <b><a href="http://www.ecommercetemplates.com/support/">here</a></b>. 
             </p>
                        <hr/>
                      </td>
                    </tr>
                    <tr valign="top"> 
                      <td valign="top" colspan="2"> 
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                          <tr> 
                            <td width="40"><br />
                              <img src="images/icon.jpg" width="40" height="40" alt=""/></td>
                            <td><a href="index.php#"><br/>
                              Apples</a><br/>
                              This is where you can introduce a category or product. 
                              It's possible to add a buy button too from your 
                              home page...(<a href="index.php#">more</a>)</td>
                          </tr>
                          <tr> 
                            <td width="40"><img src="images/icon2.jpg" width="36" height="36" alt=""/></td>
                            <td><a href="index.php#">Bananas</a><br/>
                              This is where you can introduce a category or product. 
                              It's possible to add a buy button too from your 
                              home page...(<a href="index.php#">more</a>)</td>
                          </tr>
                          <tr> 
                            <td width="40"><img src="images/icon3.jpg" width="36" height="36" alt=""/></td>
                            <td><a href="index.php#">Kiwi</a><br/>
                              This is where you can introduce a category or product. 
                              It's possible to add a buy button too from your 
                              home page...(<a href="index.php#">more</a>)</td>
                          </tr>
                          <tr> 
                            <td width="40"><img src="images/icon4.jpg" width="36" height="36" alt=""/></td>
                            <td><a href="index.php#">Avocado</a><br/>
                              This is where you can introduce a category or product. 
                              It's possible to add a buy button too from your 
                              home page...(<a href="index.php#">more</a>)</td>
                          </tr>
                        </table>
                        <hr/>
                      <p><b>Ecommerce Plus Template</b></p>
                  <p> We've provided an example of the admin section online for 
                    you to try. Unfortunately we had to make it read-only as otherwise 
                    the database gets messed up, passwords get changed and nobody 
                    can gain access. This means that on the demo you won't be 
                    able to save your changes.<br/>
                    <b>Username</b>: mystore<br/>
                    <b>Password</b>: changeme <br/>
                    <a href="http://www.ecommercetemplates.com/admindemo/admin/admin.asp" target="_blank"><b>Take 
                    a tour of the admin section</b></a></p>
                      </td>
                    </tr>
                  </table>
                </td>
                <td width="23" class="rightbg"><img src="images/clearpixel.gif" width="23" height="1" alt=""/></td>
              </tr>
              <tr> 
                <td width="23" rowspan="2"><img src="images/innerbottomleft.gif" width="23" height="23" alt=""/></td>
                <td width="100%"><img src="images/clearpixel.gif" width="1" height="22" alt=""/></td>
                <td width="23" rowspan="2"><img src="images/br.gif" width="23" height="23" alt=""/></td>
              </tr>
              <tr> 
                <td width="1" bgcolor="#AAAAAF" height="1"><img src="images/clearpixel.gif" width="1" height="1" alt=""/></td>
              </tr>
            </table>
            <!-- #EndEditable --> <br/>
          </td>
          <td width="17">&nbsp;</td>
        </tr>
        <tr> 
          <td width="17">&nbsp;</td>
          <td class="smaller" align="center" width="100%"> 
            <p class="smaller" ><a href="index.php">home</a> | <a href="categories.php">products</a> 
              | <a href="about.php">about us</a> | <a href="affiliate.php">affiliates</a> 
              | <a href="search.php">search</a> | <a href="cart.php">checkout</a> 
              | <a href="mailto:#">e-mail</a></p>
            <p class="smaller"><a href="http://www.ecommercetemplates.com/" target="_blank">Shopping 
              cart software</a> Copyright 2005 ecommercetemplates.com</p>
          </td>
          <td width="17">&nbsp;</td>
        </tr>
      </table>
    </td>
    <!-- #BeginEditable "Right" --> 
    <td class="smaller" valign="top" width="135" align="center"><!-- #BeginLibraryItem "/Library/right.lbi" -->

      <br/>
        <br/>
      <img src="images/apples.jpg" width="110" height="110" alt=""/><br/>
This is where you can add some info about a product or service on your site<br/>
<img src="images/leftline.gif" width="136" height="1" alt=""/><br/>
      <br/>
      <img src="images/bananas.jpg" width="110" height="110" alt=""/> <br/>
This is where you can add some info about a product or service on your site<br/>
<img src="images/leftline.gif" width="136" height="1" alt=""/><br/>
      <br/>
      <img src="images/kiwis.jpg" width="110" height="110" alt=""/> <br/>
      This is where you can add some info about a product or service on your site<br/>&nbsp;

<!-- #EndLibraryItem --></td>
    <!-- #EndEditable --> </tr>
</table>
</body>
<!-- #EndTemplate --></html>
