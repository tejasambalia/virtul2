# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.5.38)
# Database: golfceramics_new
# Generation Time: 2017-08-09 09:42:09 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table address
# ------------------------------------------------------------

DROP TABLE IF EXISTS `address`;

CREATE TABLE `address` (
  `addID` int(11) NOT NULL AUTO_INCREMENT,
  `addCustID` int(11) DEFAULT '0',
  `addIsDefault` tinyint(4) DEFAULT '0',
  `addName` varchar(255) DEFAULT NULL,
  `addAddress` varchar(255) DEFAULT NULL,
  `addAddress2` varchar(255) DEFAULT NULL,
  `addCity` varchar(255) DEFAULT NULL,
  `addState` varchar(255) DEFAULT NULL,
  `addZip` varchar(255) DEFAULT NULL,
  `addCountry` varchar(255) DEFAULT NULL,
  `addPhone` varchar(255) DEFAULT NULL,
  `addShipFlags` tinyint(4) DEFAULT '0',
  `addExtra1` varchar(255) DEFAULT NULL,
  `addExtra2` varchar(255) DEFAULT NULL,
  `addLastName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`addID`),
  KEY `addCustID` (`addCustID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `adminID` int(11) NOT NULL,
  `adminVersion` varchar(100) DEFAULT NULL,
  `adminUser` varchar(50) DEFAULT NULL,
  `adminPassword` varchar(50) DEFAULT NULL,
  `adminEmail` varchar(255) NOT NULL,
  `adminStoreURL` varchar(255) DEFAULT NULL,
  `adminProdsPerPage` int(11) DEFAULT '0',
  `adminShipping` int(11) DEFAULT '0',
  `adminIntShipping` int(11) DEFAULT '0',
  `adminCountry` int(11) DEFAULT '0',
  `adminZipCode` varchar(50) DEFAULT NULL,
  `adminUSPSUser` varchar(255) DEFAULT NULL,
  `adminUSPSpw` varchar(255) DEFAULT NULL,
  `adminUPSUser` varchar(255) DEFAULT NULL,
  `adminUPSpw` varchar(255) DEFAULT NULL,
  `adminUPSAccess` varchar(255) DEFAULT NULL,
  `FedexAccountNo` varchar(255) DEFAULT NULL,
  `FedexMeter` varchar(255) DEFAULT NULL,
  `adminCanPostUser` varchar(255) DEFAULT NULL,
  `adminEmailConfirm` tinyint(4) DEFAULT '0',
  `adminPacking` tinyint(4) DEFAULT '0',
  `adminDelUncompleted` int(11) DEFAULT '0',
  `adminUSZones` tinyint(4) DEFAULT '0',
  `adminUnits` tinyint(4) DEFAULT '0',
  `adminStockManage` int(11) DEFAULT '0',
  `adminHandling` double DEFAULT '0',
  `adminTweaks` int(11) DEFAULT '0',
  `adminUPSLicense` text,
  `adminDelCC` int(11) DEFAULT '0',
  `adminlanguages` int(11) DEFAULT '0',
  `adminlangsettings` int(11) DEFAULT '0',
  `currRate1` double DEFAULT '0',
  `currSymbol1` varchar(50) DEFAULT NULL,
  `currRate2` double DEFAULT '0',
  `currSymbol2` varchar(50) DEFAULT NULL,
  `currRate3` double DEFAULT '0',
  `currSymbol3` varchar(50) DEFAULT NULL,
  `currConvUser` varchar(50) DEFAULT NULL,
  `currConvPw` varchar(50) DEFAULT NULL,
  `currLastUpdate` datetime DEFAULT NULL,
  `smtpserver` varchar(100) DEFAULT '',
  `emailUser` varchar(50) DEFAULT '',
  `emailPass` varchar(50) DEFAULT '',
  `adminClearCart` int(11) DEFAULT '0',
  `adminSecret` varchar(255) DEFAULT NULL,
  `adminHandlingPercent` double DEFAULT '0',
  `updLastCheck` date DEFAULT NULL,
  `updRecommended` varchar(255) DEFAULT NULL,
  `updSecurity` tinyint(1) DEFAULT '0',
  `updShouldUpd` tinyint(1) DEFAULT '0',
  `adminUPSAccount` varchar(255) DEFAULT NULL,
  `adminUPSNegotiated` tinyint(4) DEFAULT '0',
  `cardinalProcessor` varchar(255) DEFAULT NULL,
  `cardinalMerchant` varchar(255) DEFAULT NULL,
  `cardinalPwd` varchar(255) DEFAULT NULL,
  `catalogRoot` int(11) DEFAULT '0',
  `adminAltRates` int(11) DEFAULT '0',
  `smartPostHub` varchar(15) DEFAULT NULL,
  `prodFilter` int(11) DEFAULT '0',
  `prodFilterText` varchar(255) DEFAULT NULL,
  `prodFilterText2` varchar(255) DEFAULT NULL,
  `prodFilterText3` varchar(255) DEFAULT NULL,
  `sortOrder` int(11) DEFAULT '0',
  `sortOptions` int(11) DEFAULT '0',
  `adminPWLastChange` datetime DEFAULT NULL,
  `adminUserLock` int(11) DEFAULT '0',
  `FedexUserKey` varchar(50) DEFAULT NULL,
  `FedexUserPwd` varchar(50) DEFAULT NULL,
  `adminlang` varchar(10) DEFAULT '',
  `storelang` varchar(10) DEFAULT '',
  `DHLSiteID` varchar(50) DEFAULT '',
  `DHLSitePW` varchar(50) DEFAULT '',
  `DHLAccountNo` varchar(50) DEFAULT '',
  `adminCanPostLogin` varchar(100) DEFAULT '',
  `adminCanPostPass` varchar(100) DEFAULT '',
  `AusPostAPI` varchar(255) DEFAULT '',
  `prodFilterOrder` varchar(255) DEFAULT '',
  `sideFilter` int(11) DEFAULT '0',
  `sideFilterText` varchar(255) DEFAULT '',
  `sideFilterText2` varchar(255) DEFAULT '',
  `sideFilterText3` varchar(255) DEFAULT '',
  `sideFilterOrder` varchar(255) DEFAULT '',
  PRIMARY KEY (`adminID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;

INSERT INTO `admin` (`adminID`, `adminVersion`, `adminUser`, `adminPassword`, `adminEmail`, `adminStoreURL`, `adminProdsPerPage`, `adminShipping`, `adminIntShipping`, `adminCountry`, `adminZipCode`, `adminUSPSUser`, `adminUSPSpw`, `adminUPSUser`, `adminUPSpw`, `adminUPSAccess`, `FedexAccountNo`, `FedexMeter`, `adminCanPostUser`, `adminEmailConfirm`, `adminPacking`, `adminDelUncompleted`, `adminUSZones`, `adminUnits`, `adminStockManage`, `adminHandling`, `adminTweaks`, `adminUPSLicense`, `adminDelCC`, `adminlanguages`, `adminlangsettings`, `currRate1`, `currSymbol1`, `currRate2`, `currSymbol2`, `currRate3`, `currSymbol3`, `currConvUser`, `currConvPw`, `currLastUpdate`, `smtpserver`, `emailUser`, `emailPass`, `adminClearCart`, `adminSecret`, `adminHandlingPercent`, `updLastCheck`, `updRecommended`, `updSecurity`, `updShouldUpd`, `adminUPSAccount`, `adminUPSNegotiated`, `cardinalProcessor`, `cardinalMerchant`, `cardinalPwd`, `catalogRoot`, `adminAltRates`, `smartPostHub`, `prodFilter`, `prodFilterText`, `prodFilterText2`, `prodFilterText3`, `sortOrder`, `sortOptions`, `adminPWLastChange`, `adminUserLock`, `FedexUserKey`, `FedexUserPwd`, `adminlang`, `storelang`, `DHLSiteID`, `DHLSitePW`, `DHLAccountNo`, `adminCanPostLogin`, `adminCanPostPass`, `AusPostAPI`, `prodFilterOrder`, `sideFilter`, `sideFilterText`, `sideFilterText2`, `sideFilterText3`, `sideFilterOrder`)
VALUES
	(1,'Ecommerce Plus PHP v6.4.2','mystore','f922d0631b6400654f8ecd7fa6b36233','you@yoursite.com','http://git.local/golfceramics.in/shop/',8,2,0,1,'YOURZIP','','','','','',NULL,NULL,NULL,0,0,4,0,1,0,0,0,NULL,7,0,0,0,NULL,0,NULL,0,NULL,NULL,NULL,'2017-08-08 10:59:34','','','',364,'secret text 5900340',0,'2017-08-09','',0,0,NULL,0,NULL,NULL,NULL,0,0,NULL,0,'&&&&&','&&&&&','&&&&&',1,1,'2017-08-09 00:00:00',0,NULL,NULL,'','','','','','','','','',127,'&Attributes&Price&Sort Order&Per Page&Filter By','&Attributes&Price&Sort Order&Per Page&Filter By','&Attributes&Price&Sort Order&Per Page&Filter By','');

/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table adminlogin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `adminlogin`;

CREATE TABLE `adminlogin` (
  `adminloginid` int(11) NOT NULL AUTO_INCREMENT,
  `adminloginname` varchar(255) NOT NULL,
  `adminloginpassword` varchar(255) NOT NULL,
  `adminloginpermissions` varchar(255) NOT NULL,
  `adminLoginLastChange` datetime DEFAULT NULL,
  `adminLoginLock` int(11) DEFAULT '0',
  PRIMARY KEY (`adminloginid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table affiliates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `affiliates`;

CREATE TABLE `affiliates` (
  `affilID` varchar(32) NOT NULL,
  `affilPW` varchar(32) DEFAULT NULL,
  `affilEmail` varchar(128) DEFAULT NULL,
  `affilName` varchar(255) DEFAULT NULL,
  `affilAddress` varchar(255) DEFAULT NULL,
  `affilCity` varchar(255) DEFAULT NULL,
  `affilState` varchar(255) DEFAULT NULL,
  `affilZip` varchar(255) DEFAULT NULL,
  `affilCountry` varchar(255) DEFAULT NULL,
  `affilInform` tinyint(4) DEFAULT '0',
  `affilCommision` double DEFAULT '0',
  `affilDate` date DEFAULT NULL,
  PRIMARY KEY (`affilID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table alternaterates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `alternaterates`;

CREATE TABLE `alternaterates` (
  `altrateid` int(11) NOT NULL,
  `altratename` varchar(255) NOT NULL,
  `altratetext` varchar(255) DEFAULT NULL,
  `altratetext2` varchar(255) DEFAULT NULL,
  `altratetext3` varchar(255) DEFAULT NULL,
  `usealtmethod` int(11) DEFAULT '0',
  `usealtmethodintl` int(11) DEFAULT '0',
  `altrateorder` int(11) DEFAULT '0',
  PRIMARY KEY (`altrateid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `alternaterates` WRITE;
/*!40000 ALTER TABLE `alternaterates` DISABLE KEYS */;

INSERT INTO `alternaterates` (`altrateid`, `altratename`, `altratetext`, `altratetext2`, `altratetext3`, `usealtmethod`, `usealtmethodintl`, `altrateorder`)
VALUES
	(1,'Flat Rate Shipping','Flat Rate Shipping','Flat Rate Shipping','Flat Rate Shipping',0,0,0),
	(2,'Weight Based Shipping','Weight Based Shipping','Weight Based Shipping','Weight Based Shipping',0,0,0),
	(3,'U.S.P.S. Shipping','U.S.P.S. Shipping','U.S.P.S. Shipping','U.S.P.S. Shipping',0,0,0),
	(4,'UPS Shipping','UPS Shipping','UPS Shipping','UPS Shipping',0,0,0),
	(5,'Price Based Shipping','Price Based Shipping','Price Based Shipping','Price Based Shipping',0,0,0),
	(6,'Canada Post','Canada Post','Canada Post','Canada Post',0,0,0),
	(7,'FedEx Shipping','FedEx Shipping','FedEx Shipping','FedEx Shipping',0,0,0),
	(8,'FedEx SmartPost&reg;','FedEx SmartPost&reg;','FedEx SmartPost&reg;','FedEx SmartPost&reg;',0,0,0),
	(9,'DHL Shipping','DHL Shipping','DHL Shipping','DHL Shipping',0,0,0),
	(10,'Australia Post','Australia Post','Australia Post','Australia Post',0,0,0);

/*!40000 ALTER TABLE `alternaterates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table auditlog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auditlog`;

CREATE TABLE `auditlog` (
  `logID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` varchar(50) DEFAULT NULL,
  `eventType` varchar(50) DEFAULT NULL,
  `eventDate` datetime DEFAULT NULL,
  `eventSuccess` tinyint(4) DEFAULT '0',
  `eventOrigin` varchar(50) DEFAULT NULL,
  `areaAffected` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`logID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `auditlog` WRITE;
/*!40000 ALTER TABLE `auditlog` DISABLE KEYS */;

INSERT INTO `auditlog` (`logID`, `userID`, `eventType`, `eventDate`, `eventSuccess`, `eventOrigin`, `areaAffected`)
VALUES
	(1,'UPDATE','UPDATESTORE','2017-08-09 14:47:42',1,'UPDATER PHP v6.5.3','DBVERSION'),
	(2,'UPDATE','UPDATESTORE','2017-08-09 15:00:58',1,'UPDATER PHP v6.4.2','DBVERSION');

/*!40000 ALTER TABLE `auditlog` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cart
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cart`;

CREATE TABLE `cart` (
  `cartID` int(11) NOT NULL AUTO_INCREMENT,
  `cartSessionID` varchar(100) DEFAULT NULL,
  `cartProdID` varchar(255) DEFAULT NULL,
  `cartProdName` varchar(255) DEFAULT NULL,
  `cartProdPrice` double DEFAULT NULL,
  `cartDateAdded` datetime DEFAULT NULL,
  `cartQuantity` int(11) DEFAULT NULL,
  `cartOrderID` int(11) DEFAULT NULL,
  `cartCompleted` tinyint(4) DEFAULT NULL,
  `cartClientID` int(11) DEFAULT '0',
  `cartListID` int(11) DEFAULT '0',
  `cartGiftWrap` tinyint(1) DEFAULT '0',
  `cartGiftMessage` text,
  `cartOrigProdID` varchar(255) DEFAULT '',
  PRIMARY KEY (`cartID`),
  UNIQUE KEY `cartID` (`cartID`),
  KEY `cartClientID` (`cartClientID`),
  KEY `cartListID` (`cartListID`),
  KEY `cartCompleted` (`cartCompleted`),
  KEY `cartDateAdded` (`cartDateAdded`),
  KEY `cartOrderID` (`cartOrderID`),
  KEY `cartProdID` (`cartProdID`),
  KEY `cartSessionID` (`cartSessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;

INSERT INTO `cart` (`cartID`, `cartSessionID`, `cartProdID`, `cartProdName`, `cartProdPrice`, `cartDateAdded`, `cartQuantity`, `cartOrderID`, `cartCompleted`, `cartClientID`, `cartListID`, `cartGiftWrap`, `cartGiftMessage`, `cartOrigProdID`)
VALUES
	(1,'935000845','pc001','#1 PC multimedia package',1200,'2017-08-09 14:46:14',1,501,1,0,0,0,'',''),
	(2,'d24cfd1e3a482ed5ce1e5237cf32545b','testproduct','Cheap Test Product',0.01,'2017-08-09 14:48:49',2,0,0,0,0,0,NULL,'');

/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cartoptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cartoptions`;

CREATE TABLE `cartoptions` (
  `coID` int(11) NOT NULL AUTO_INCREMENT,
  `coCartID` int(11) DEFAULT NULL,
  `coOptID` int(11) DEFAULT NULL,
  `coOptGroup` varchar(255) DEFAULT NULL,
  `coCartOption` varchar(1024) DEFAULT NULL,
  `coPriceDiff` double DEFAULT '0',
  `coWeightDiff` double DEFAULT '0',
  `coMultiply` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`coID`),
  KEY `coCartID` (`coCartID`),
  KEY `coOptID` (`coOptID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `cartoptions` WRITE;
/*!40000 ALTER TABLE `cartoptions` DISABLE KEYS */;

INSERT INTO `cartoptions` (`coID`, `coCartID`, `coOptID`, `coOptGroup`, `coCartOption`, `coPriceDiff`, `coWeightDiff`, `coMultiply`)
VALUES
	(1,1,23,'Processor','Intel Pentium IV 1.5GHz',25.5,0,0),
	(2,1,28,'Hard Disk','60 Gigabytes',34,0,0),
	(3,1,30,'Monitor','15\" Standard',0,0,0),
	(4,1,35,'Network Card','Yes',15,0,0),
	(5,2,12,'Size','8',0,0,0);

/*!40000 ALTER TABLE `cartoptions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table contentregions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contentregions`;

CREATE TABLE `contentregions` (
  `contentID` int(11) NOT NULL AUTO_INCREMENT,
  `contentName` varchar(255) DEFAULT NULL,
  `contentX` int(11) DEFAULT '0',
  `contentY` int(11) DEFAULT '0',
  `contentData` text,
  `contentData2` text,
  `contentData3` text,
  PRIMARY KEY (`contentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table countries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `countryID` int(11) NOT NULL,
  `countryName` varchar(255) DEFAULT NULL,
  `countryName2` varchar(255) DEFAULT NULL,
  `countryName3` varchar(255) DEFAULT NULL,
  `countryEnabled` tinyint(4) DEFAULT '0',
  `countryTax` double DEFAULT '0',
  `countryOrder` int(11) DEFAULT '0',
  `countryZone` int(11) DEFAULT '0',
  `countryLCID` varchar(50) DEFAULT NULL,
  `countryCurrency` varchar(50) DEFAULT NULL,
  `countryCode` varchar(50) DEFAULT NULL,
  `countryFreeShip` tinyint(4) DEFAULT '0',
  `countryNumCurrency` int(11) DEFAULT '0',
  `loadStates` int(11) DEFAULT '0',
  PRIMARY KEY (`countryID`),
  KEY `countryName` (`countryName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;

INSERT INTO `countries` (`countryID`, `countryName`, `countryName2`, `countryName3`, `countryEnabled`, `countryTax`, `countryOrder`, `countryZone`, `countryLCID`, `countryCurrency`, `countryCode`, `countryFreeShip`, `countryNumCurrency`, `loadStates`)
VALUES
	(1,'United States of America','United States of America','United States of America',1,0,2,1,'en_US','USD','US',0,840,2),
	(2,'Canada','Canada','Canada',1,0,0,2,'en_CA','CAD','CA',0,124,2),
	(3,'Afghanistan','Afghanistan','Afghanistan',0,0,0,4,'','AFA','AF',0,0,2),
	(4,'Albania','Albania','Albania',0,0,0,4,'','ALL','AL',0,8,2),
	(5,'Algeria','Algeria','Algeria',0,0,0,4,'','DZD','DZ',0,12,2),
	(6,'Andorra','Andorra','Andorra',0,0,0,3,'','EUR','AD',0,978,2),
	(7,'Angola','Angola','Angola',0,0,0,4,'','AOA','AO',0,973,2),
	(8,'Anguilla','Anguilla','Anguilla',0,0,0,4,'','XCD','AI',0,951,2),
	(10,'Antigua and Barbuda','Antigua and Barbuda','Antigua and Barbuda',0,0,0,4,'','XCD','AG',0,951,2),
	(11,'Argentina','Argentina','Argentina',1,0,0,2,'es_AR','ARS','AR',0,32,2),
	(12,'Armenia','Armenia','Armenia',0,0,0,4,'','AMD','AM',0,51,2),
	(13,'Aruba','Aruba','Aruba',0,0,0,4,'','AWG','AW',0,533,2),
	(14,'Australia','Australia','Australia',1,0,0,4,'en_AU','AUD','AU',0,36,2),
	(15,'Austria','Austria','Austria',1,0,0,3,'de_AT','EUR','AT',0,978,2),
	(16,'Azerbaijan','Azerbaijan','Azerbaijan',0,0,0,4,'','AZM','AZ',0,0,2),
	(17,'Bahamas','Bahamas','Bahamas',1,0,0,4,'en_US','BSD','BS',0,44,2),
	(18,'Bahrain','Bahrain','Bahrain',0,0,0,4,'','BHD','BH',0,48,2),
	(19,'Bangladesh','Bangladesh','Bangladesh',0,0,0,4,'','BDT','BD',0,50,2),
	(20,'Barbados','Barbados','Barbados',0,0,0,4,'','BBD','BB',0,52,2),
	(21,'Belarus','Belarus','Belarus',0,0,0,4,'','BYR','BY',0,974,2),
	(22,'Belgium','Belgium','Belgium',1,0,0,3,'fr_BE','EUR','BE',0,978,2),
	(23,'Belize','Belize','Belize',0,0,0,4,'','BZD','BZ',0,84,2),
	(24,'Benin','Benin','Benin',0,0,0,4,'','XOF','BJ',0,952,2),
	(25,'Bermuda','Bermuda','Bermuda',0,0,0,4,'','BMD','BM',0,60,2),
	(26,'Bhutan','Bhutan','Bhutan',0,0,0,4,'','BTN','BT',0,64,2),
	(27,'Bolivia','Bolivia','Bolivia',0,0,0,2,'','BOB','BO',0,68,2),
	(28,'Bosnia-Herzegovina','Bosnia-Herzegovina','Bosnia-Herzegovina',0,0,0,4,'','BAM','BA',0,977,2),
	(29,'Botswana','Botswana','Botswana',0,0,0,4,'','BWP','BW',0,72,2),
	(30,'Brazil','Brazil','Brazil',1,0,0,2,'pt_BR','BRL','BR',0,986,2),
	(31,'Brunei Darussalam','Brunei Darussalam','Brunei Darussalam',0,0,0,4,'','BND','BN',0,96,2),
	(32,'Bulgaria','Bulgaria','Bulgaria',0,0,0,4,'','BGN','BG',0,975,2),
	(33,'Burkina Faso','Burkina Faso','Burkina Faso',0,0,0,4,'','XOF','BF',0,952,2),
	(34,'Burundi','Burundi','Burundi',0,0,0,4,'','BIF','BI',0,108,2),
	(35,'Cambodia','Cambodia','Cambodia',0,0,0,4,'','KHR','KH',0,116,2),
	(36,'Cameroon','Cameroon','Cameroon',0,0,0,4,'','XAF','CM',0,950,2),
	(37,'Cape Verde','Cape Verde','Cape Verde',0,0,0,4,'','CVE','CV',0,132,2),
	(38,'Cayman Islands','Cayman Islands','Cayman Islands',0,0,0,4,'','KYD','KY',0,136,2),
	(39,'Central African Republic','Central African Republic','Central African Republic',0,0,0,4,'','XAF','CF',0,950,2),
	(40,'Chad','Chad','Chad',0,0,0,4,'','XAF','TD',0,950,2),
	(41,'Chile','Chile','Chile',1,0,0,2,'es_CL','CLP','CL',0,152,2),
	(42,'China','China','China',0,0,0,4,'','CNY','CN',0,156,2),
	(43,'Colombia','Colombia','Colombia',0,0,0,2,'','COP','CO',0,170,2),
	(44,'Comoros','Comoros','Comoros',0,0,0,4,'','KMF','KM',0,174,2),
	(45,'Costa Rica','Costa Rica','Costa Rica',1,0,0,2,'es_CR','CRC','CR',0,188,2),
	(46,'Croatia','Croatia','Croatia',0,0,0,4,'','HRK','HR',0,191,2),
	(47,'Cuba','Cuba','Cuba',0,0,0,4,'','CUP','CU',0,192,2),
	(48,'Cyprus','Cyprus','Cyprus',0,0,0,4,'','EUR','CY',0,978,2),
	(49,'Czech Republic','Czech Republic','Czech Republic',0,0,0,4,'','CZK','CZ',0,203,2),
	(50,'Denmark','Denmark','Denmark',1,0,0,3,'da_DK','DKK','DK',0,208,2),
	(51,'Djibouti','Djibouti','Djibouti',0,0,0,4,'','DJF','DJ',0,262,2),
	(52,'Dominica','Dominica','Dominica',0,0,0,4,'','XCD','DM',0,951,2),
	(53,'Dominican Republic','Dominican Republic','Dominican Republic',1,0,0,4,'','DOP','DO',0,214,2),
	(54,'East Timor','East Timor','East Timor',0,0,0,4,'','IDR','TP',0,360,2),
	(55,'Ecuador','Ecuador','Ecuador',0,0,0,4,'','USD','EC',0,840,2),
	(56,'Egypt','Egypt','Egypt',0,0,0,4,'','EGP','EG',0,818,2),
	(57,'El Salvador','El Salvador','El Salvador',0,0,0,2,'','USD','SV',0,840,2),
	(58,'Equatorial Guinea','Equatorial Guinea','Equatorial Guinea',0,0,0,4,'','XAF','GQ',0,950,2),
	(59,'Estonia','Estonia','Estonia',0,0,0,4,'','EEK','EE',0,233,2),
	(60,'Ethiopia','Ethiopia','Ethiopia',0,0,0,4,'','ETB','ET',0,230,2),
	(61,'Falkland Islands','Falkland Islands','Falkland Islands',0,0,0,4,'','FKP','FK',0,238,2),
	(62,'Faroe Islands','Faroe Islands','Faroe Islands',0,0,0,4,'','DKK','FO',0,208,2),
	(63,'Fiji','Fiji','Fiji',0,0,0,4,'','FJD','FJ',0,242,2),
	(64,'Finland','Finland','Finland',1,0,0,3,'su_FI','EUR','FI',0,978,2),
	(65,'France','France','France',1,0,0,3,'fr_FR','EUR','FR',0,978,2),
	(66,'French Guiana','French Guiana','French Guiana',0,0,0,4,'','EUR','GF',0,978,2),
	(67,'French Polynesia','French Polynesia','French Polynesia',0,0,0,4,'','XPF','PF',0,953,2),
	(68,'Gabon','Gabon','Gabon',0,0,0,4,'','XAF','GA',0,950,2),
	(69,'Gambia','Gambia','Gambia',0,0,0,4,'','GMD','GM',0,270,2),
	(70,'Georgia, Republic of','Georgia, Republic of','Georgia, Republic of',0,0,0,4,'','GEL','GE',0,981,2),
	(71,'Germany','Germany','Germany',1,0,0,3,'de_DE','EUR','DE',0,978,2),
	(72,'Ghana','Ghana','Ghana',0,0,0,4,'','GHC','GH',0,0,2),
	(73,'Gibraltar','Gibraltar','Gibraltar',1,0,0,3,'','GBP','GI',0,826,2),
	(74,'Greece','Greece','Greece',1,0,0,3,'el_GR','EUR','GR',0,978,2),
	(75,'Greenland','Greenland','Greenland',1,0,0,3,'','DKK','GL',0,208,2),
	(76,'Grenada','Grenada','Grenada',0,0,0,4,'','XCD','GD',0,951,2),
	(77,'Guadeloupe','Guadeloupe','Guadeloupe',0,0,0,4,'','EUR','GP',0,978,2),
	(78,'Guam','Guam','Guam',0,0,0,1,'','USD','GU',0,840,2),
	(79,'Guatemala','Guatemala','Guatemala',1,0,0,2,'es_GT','GTQ','GT',0,320,2),
	(80,'Guinea','Guinea','Guinea',0,0,0,4,'','GNF','GN',0,324,2),
	(81,'Guinea-Bissau','Guinea-Bissau','Guinea-Bissau',0,0,0,4,'','XOF','GW',0,952,2),
	(82,'Guyana','Guyana','Guyana',0,0,0,2,'','GYD','GY',0,328,2),
	(83,'Haiti','Haiti','Haiti',0,0,0,4,'','USD','HT',0,840,2),
	(84,'Honduras','Honduras','Honduras',0,0,0,2,'','HNL','HN',0,340,2),
	(85,'Hong Kong','Hong Kong','Hong Kong',1,0,0,4,'','HKD','HK',0,344,2),
	(86,'Hungary','Hungary','Hungary',0,0,0,4,'','HUF','HU',0,348,2),
	(87,'Iceland','Iceland','Iceland',1,0,0,3,'','ISK','IS',0,352,2),
	(88,'India','India','India',0,0,0,4,'en_IN','INR','IN',0,356,2),
	(89,'Indonesia','Indonesia','Indonesia',0,0,0,4,'id_ID','IDR','ID',0,360,2),
	(90,'Iraq','Iraq','Iraq',0,0,0,4,'','IQD','IQ',0,368,2),
	(91,'Ireland','Ireland','Ireland',1,0,0,3,'en_IE','EUR','IE',0,978,2),
	(92,'Israel','Israel','Israel',1,0,0,4,'','ILS','IL',0,376,2),
	(93,'Italy','Italy','Italy',1,0,0,3,'it_IT','EUR','IT',0,978,2),
	(94,'Jamaica','Jamaica','Jamaica',0,0,0,4,'','JMD','JM',0,388,2),
	(95,'Japan','Japan','Japan',1,0,0,4,'jp_JP','JPY','JP',0,392,2),
	(96,'Jordan','Jordan','Jordan',0,0,0,4,'','JOD','JO',0,400,2),
	(97,'Kazakhstan','Kazakhstan','Kazakhstan',0,0,0,4,'','KZT','KZ',0,398,2),
	(98,'Kenya','Kenya','Kenya',0,0,0,4,'','KES','KE',0,404,2),
	(99,'Kiribati','Kiribati','Kiribati',0,0,0,4,'','AUD','KI',0,36,2),
	(100,'North Korea','North Korea','North Korea',0,0,0,4,'ko_KR','KPW','KP',0,408,2),
	(101,'South Korea','South Korea','South Korea',0,0,0,4,'','KRW','KR',0,410,2),
	(102,'Kuwait','Kuwait','Kuwait',0,0,0,4,'','KWD','KW',0,414,2),
	(103,'Latvia','Latvia','Latvia',0,0,0,4,'','LVL','LV',0,428,2),
	(104,'Lebanon','Lebanon','Lebanon',0,0,0,4,'','LBP','LB',0,422,2),
	(105,'Lesotho','Lesotho','Lesotho',0,0,0,4,'','LSL','LS',0,426,2),
	(106,'Liberia','Liberia','Liberia',0,0,0,4,'','LRD','LR',0,430,2),
	(107,'England','England','England',0,0,0,3,'','GBP','GB',0,826,2),
	(108,'Liechtenstein','Liechtenstein','Liechtenstein',0,0,0,4,'','CHF','LI',0,756,2),
	(109,'Lithuania','Lithuania','Lithuania',0,0,0,4,'','LTL','LT',0,440,2),
	(110,'Luxembourg','Luxembourg','Luxembourg',1,0,0,3,'','EUR','LU',0,978,2),
	(111,'Macao','Macao','Macao',0,0,0,4,'','MOP','MO',0,446,2),
	(112,'Macedonia, Republic of','Macedonia, Republic of','Macedonia, Republic of',0,0,0,4,'','MKD','MK',0,807,2),
	(113,'Madagascar','Madagascar','Madagascar',0,0,0,4,'','MGF','MG',0,0,2),
	(114,'Malawi','Malawi','Malawi',0,0,0,4,'','MWK','MW',0,454,2),
	(115,'Malaysia','Malaysia','Malaysia',1,0,0,4,'ms_MY','MYR','MY',0,458,2),
	(116,'Maldives','Maldives','Maldives',0,0,0,4,'','MVR','MV',0,462,2),
	(117,'Mali','Mali','Mali',0,0,0,4,'','XOF','ML',0,952,2),
	(118,'Malta','Malta','Malta',0,0,0,4,'','EUR','MT',0,978,2),
	(119,'Martinique','Martinique','Martinique',0,0,0,4,'','EUR','MQ',0,978,2),
	(120,'Mauritania','Mauritania','Mauritania',0,0,0,4,'','MRO','MR',0,478,2),
	(121,'Mauritius','Mauritius','Mauritius',0,0,0,4,'','MUR','MU',0,480,2),
	(122,'Mexico','Mexico','Mexico',1,0,0,2,'es_MX','MXN','MX',0,484,2),
	(123,'Moldova','Moldova','Moldova',0,0,0,4,'','MDL','MD',0,498,2),
	(124,'Monaco','Monaco','Monaco',1,0,0,3,'','EUR','MC',0,978,2),
	(125,'Mongolia','Mongolia','Mongolia',0,0,0,4,'','MNT','MN',0,496,2),
	(126,'Montserrat','Montserrat','Montserrat',0,0,0,4,'','XCD','MS',0,951,2),
	(127,'Morocco','Morocco','Morocco',0,0,0,4,'','MAD','MA',0,504,2),
	(128,'Mozambique','Mozambique','Mozambique',0,0,0,4,'','MZM','MZ',0,0,2),
	(129,'Myanmar','Myanmar','Myanmar',0,0,0,4,'','MMK','MM',0,104,2),
	(130,'Namibia','Namibia','Namibia',0,0,0,4,'','NAD','NA',0,516,2),
	(131,'Nauru','Nauru','Nauru',0,0,0,4,'','AUD','NR',0,36,2),
	(132,'Nepal','Nepal','Nepal',0,0,0,4,'','NPR','NP',0,524,2),
	(133,'Netherlands','Netherlands','Netherlands',1,0,0,3,'nl_NL','EUR','NL',0,978,2),
	(134,'Netherlands Antilles','Netherlands Antilles','Netherlands Antilles',0,0,0,4,'','ANG','AN',0,532,2),
	(135,'New Caledonia','New Caledonia','New Caledonia',0,0,0,4,'','XPF','NC',0,953,2),
	(136,'New Zealand','New Zealand','New Zealand',1,0,0,4,'en_NZ','NZD','NZ',0,554,2),
	(137,'Nicaragua','Nicaragua','Nicaragua',0,0,0,2,'','NIO','NI',0,558,2),
	(138,'Niger','Niger','Niger',0,0,0,4,'','XOF','NE',0,952,2),
	(139,'Nigeria','Nigeria','Nigeria',0,0,0,4,'','NGN','NG',0,566,2),
	(140,'Niue','Niue','Niue',0,0,0,4,'','NZD','NU',0,554,2),
	(141,'Norfolk Island','Norfolk Island','Norfolk Island',0,0,0,4,'','AUD','NF',0,36,2),
	(142,'Northern Ireland','Northern Ireland','Northern Ireland',1,0,0,3,'en_GB','GBP','GB',0,826,2),
	(143,'Norway','Norway','Norway',1,0,0,3,'no_NO','NOK','NO',0,578,2),
	(144,'Oman','Oman','Oman',0,0,0,4,'','OMR','OM',0,512,2),
	(145,'Pakistan','Pakistan','Pakistan',0,0,0,4,'','PKR','PK',0,586,2),
	(146,'Panama','Panama','Panama',1,0,0,2,'es_PA','PAB','PA',0,590,2),
	(147,'Papua New Guinea','Papua New Guinea','Papua New Guinea',0,0,0,4,'','PGK','PG',0,598,2),
	(148,'Paraguay','Paraguay','Paraguay',0,0,0,4,'','PYG','PY',0,600,2),
	(149,'Peru','Peru','Peru',0,0,0,2,'','PEN','PE',0,604,2),
	(150,'Philippines','Philippines','Philippines',0,0,0,4,'','PHP','PH',0,608,2),
	(151,'Pitcairn Island','Pitcairn Island','Pitcairn Island',0,0,0,4,'','NZD','PN',0,554,2),
	(152,'Poland','Poland','Poland',0,0,0,4,'','PLN','PL',0,985,2),
	(153,'Portugal','Portugal','Portugal',1,0,0,3,'pt_PT','EUR','PT',0,978,2),
	(154,'Qatar','Qatar','Qatar',0,0,0,4,'','QAR','QA',0,634,2),
	(155,'Reunion','Reunion','Reunion',0,0,0,4,'','EUR','RE',0,978,2),
	(156,'Romania','Romania','Romania',0,0,0,4,'ro_RO','RON','RO',0,946,2),
	(157,'Russia','Russia','Russia',0,0,0,4,'','RUB','RU',0,643,2),
	(158,'Rwanda','Rwanda','Rwanda',0,0,0,4,'','RWF','RW',0,646,2),
	(159,'Saint Kitts','Saint Kitts','Saint Kitts',0,0,0,4,'','XCD','KN',0,951,2),
	(160,'Saint Lucia','Saint Lucia','Saint Lucia',0,0,0,4,'','XCD','LC',0,951,2),
	(161,'Saint Vincent and the Grenadines','Saint Vincent and the Grenadines','Saint Vincent and the Grenadines',0,0,0,4,'','XCD','VC',0,951,2),
	(162,'Western Samoa','Western Samoa','Western Samoa',0,0,0,4,'','WST','WS',0,882,2),
	(163,'San Marino','San Marino','San Marino',0,0,0,4,'','EUR','SM',0,978,2),
	(164,'Sao Tome and Principe','Sao Tome and Principe','Sao Tome and Principe',0,0,0,4,'','STD','ST',0,678,2),
	(165,'Saudi Arabia','Saudi Arabia','Saudi Arabia',0,0,0,4,'','SAR','SA',0,682,2),
	(166,'Senegal','Senegal','Senegal',0,0,0,4,'','XOF','SN',0,952,2),
	(167,'Seychelles','Seychelles','Seychelles',0,0,0,4,'','SCR','SC',0,690,2),
	(168,'Sierra Leone','Sierra Leone','Sierra Leone',0,0,0,4,'','SLL','SL',0,694,2),
	(169,'Singapore','Singapore','Singapore',1,0,0,4,'','SGD','SG',0,702,2),
	(170,'Slovak Republic','Slovak Republic','Slovak Republic',0,0,0,4,'','SKK','SK',0,0,2),
	(171,'Slovenia','Slovenia','Slovenia',0,0,0,4,'','EUR','SI',0,978,2),
	(172,'Solomon Islands','Solomon Islands','Solomon Islands',0,0,0,4,'','SBD','SB',0,90,2),
	(173,'Somalia','Somalia','Somalia',0,0,0,4,'','SOS','SO',0,706,2),
	(174,'South Africa','South Africa','South Africa',0,0,0,4,'en_ZA','ZAR','ZA',0,710,2),
	(175,'Spain','Spain','Spain',1,0,0,3,'es_ES','EUR','ES',0,978,2),
	(176,'Sri Lanka','Sri Lanka','Sri Lanka',0,0,0,4,'','LKR','LK',0,144,2),
	(177,'Saint Helena','Saint Helena','Saint Helena',0,0,0,4,'','SHP','SH',0,654,2),
	(178,'Saint Pierre and Miquelon','Saint Pierre and Miquelon','Saint Pierre and Miquelon',0,0,0,4,'','EUR','PM',0,978,2),
	(179,'Sudan','Sudan','Sudan',0,0,0,4,'','SDD','SD',0,0,2),
	(180,'Suriname','Suriname','Suriname',0,0,0,4,'','SRG','SR',0,0,2),
	(181,'Swaziland','Swaziland','Swaziland',0,0,0,4,'','SZL','SZ',0,748,2),
	(182,'Sweden','Sweden','Sweden',1,0,0,3,'sv_SE','SEK','SE',0,752,2),
	(183,'Switzerland','Switzerland','Switzerland',1,0,0,3,'fr_CH','CHF','CH',0,756,2),
	(184,'Syrian Arab Republic','Syrian Arab Republic','Syrian Arab Republic',0,0,0,4,'','SYP','SY',0,760,2),
	(185,'Taiwan','Taiwan','Taiwan',1,0,0,4,'','TWD','TW',0,901,2),
	(186,'Tajikistan','Tajikistan','Tajikistan',0,0,0,4,'','TJS','TJ',0,972,2),
	(187,'Tanzania','Tanzania','Tanzania',0,0,0,4,'','TZS','TZ',0,834,2),
	(188,'Thailand','Thailand','Thailand',1,0,0,4,'','THB','TH',0,764,2),
	(189,'Togo','Togo','Togo',0,0,0,4,'','XOF','TG',0,952,2),
	(190,'Tokelau','Tokelau','Tokelau',0,0,0,4,'','NZD','TK',0,554,2),
	(191,'Tonga','Tonga','Tonga',0,0,0,4,'','TOP','TO',0,776,2),
	(192,'Trinidad and Tobago','Trinidad and Tobago','Trinidad and Tobago',0,0,0,4,'','TTD','TT',0,780,2),
	(193,'Tunisia','Tunisia','Tunisia',0,0,0,4,'','TND','TN',0,788,2),
	(194,'Turkey','Turkey','Turkey',0,0,0,4,'','TRY','TR',0,949,2),
	(195,'Turkmenistan','Turkmenistan','Turkmenistan',0,0,0,4,'','TMM','TM',0,0,2),
	(196,'Turks and Caicos Islands','Turks and Caicos Islands','Turks and Caicos Islands',0,0,0,4,'','USD','TC',0,840,2),
	(197,'Tuvalu','Tuvalu','Tuvalu',0,0,0,4,'','TVD','TV',0,0,2),
	(198,'Uganda','Uganda','Uganda',0,0,0,4,'','UGX','UG',0,800,2),
	(199,'Ukraine','Ukraine','Ukraine',0,0,0,4,'','UAH','UA',0,980,2),
	(200,'United Arab Emirates','United Arab Emirates','United Arab Emirates',0,0,0,4,'','AED','AE',0,784,2),
	(201,'Great Britain','Great Britain','Great Britain',1,0,1,3,'en_GB','GBP','GB',0,826,2),
	(202,'Uruguay','Uruguay','Uruguay',0,0,0,4,'','UYU','UY',0,858,2),
	(203,'Uzbekistan','Uzbekistan','Uzbekistan',0,0,0,4,'','UZS','UZ',0,860,2),
	(204,'Vanuatu','Vanuatu','Vanuatu',0,0,0,4,'','VUV','VU',0,548,2),
	(205,'Vatican City','Vatican City','Vatican City',1,0,0,3,'','EUR','VA',0,978,2),
	(206,'Venezuela','Venezuela','Venezuela',0,0,0,2,'es_VE','VEF','VE',0,937,2),
	(207,'Vietnam','Vietnam','Vietnam',0,0,0,4,'','VND','VN',0,704,2),
	(208,'British Virgin Islands','British Virgin Islands','British Virgin Islands',0,0,0,4,'','USD','VG',0,840,2),
	(209,'Wallis and Futuna Islands','Wallis and Futuna Islands','Wallis and Futuna Islands',0,0,0,4,'','XPF','WF',0,953,2),
	(210,'Yemen','Yemen','Yemen',0,0,0,4,'','YER','YE',0,886,2),
	(211,'Zambia','Zambia','Zambia',0,0,0,4,'','ZMK','ZM',0,894,2),
	(212,'Zimbabwe','Zimbabwe','Zimbabwe',0,0,0,4,'','ZWD','ZW',0,0,2),
	(213,'Iran','Iran','Iran',0,0,0,4,'','IRR','IR',0,364,2),
	(214,'Channel Islands','Channel Islands','Channel Islands',0,0,0,3,'','GBP','GB',0,826,2),
	(215,'Puerto Rico','Puerto Rico','Puerto Rico',0,0,0,3,'','USD','PR',0,840,2),
	(216,'Isle of Man','Isle of Man','Isle of Man',0,0,0,3,'','GBP','GB',0,826,2),
	(217,'Azores','Azores','Azores',0,0,0,3,'','EUR','PT',0,978,2),
	(218,'Corsica','Corsica','Corsica',0,0,0,3,'','EUR','FR',0,978,2),
	(219,'Balearic Islands','Balearic Islands','Balearic Islands',0,0,0,3,'','EUR','ES',0,978,2),
	(220,'US Virgin Islands','US Virgin Islands','US Virgin Islands',0,0,0,3,'','USD','VI',0,840,2),
	(221,'Serbia','Serbia','Serbia',0,0,0,3,'','SRD','SR',0,968,2),
	(222,'Ivory Coast','Ivory Coast','Ivory Coast',0,0,0,3,'','XOF','CI',0,952,2),
	(223,'Montenegro','Montenegro','Montenegro',0,0,0,3,'','EUR','ME',0,978,2),
	(224,'American Samoa','American Samoa','American Samoa',0,0,0,3,'','USD','AS',0,840,0);

/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table coupons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `coupons`;

CREATE TABLE `coupons` (
  `cpnID` int(11) NOT NULL AUTO_INCREMENT,
  `cpnName` varchar(255) DEFAULT NULL,
  `cpnName2` varchar(255) DEFAULT NULL,
  `cpnName3` varchar(255) DEFAULT NULL,
  `cpnWorkingName` varchar(255) DEFAULT NULL,
  `cpnNumber` varchar(255) DEFAULT NULL,
  `cpnType` int(11) DEFAULT '0',
  `cpnEndDate` datetime DEFAULT NULL,
  `cpnDiscount` double DEFAULT '0',
  `cpnThreshold` double DEFAULT '0',
  `cpnThresholdMax` double DEFAULT '0',
  `cpnThresholdRepeat` double DEFAULT '0',
  `cpnQuantity` int(11) DEFAULT '0',
  `cpnQuantityMax` int(11) DEFAULT '0',
  `cpnQuantityRepeat` int(11) DEFAULT '0',
  `cpnNumAvail` int(11) DEFAULT '0',
  `cpnCntry` tinyint(4) DEFAULT '0',
  `cpnIsCoupon` tinyint(4) DEFAULT '0',
  `cpnSitewide` tinyint(4) DEFAULT '0',
  `cpnHandling` tinyint(1) DEFAULT '0',
  `cpnLoginLevel` int(11) DEFAULT '0',
  PRIMARY KEY (`cpnID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table cpnassign
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cpnassign`;

CREATE TABLE `cpnassign` (
  `cpaID` int(11) NOT NULL AUTO_INCREMENT,
  `cpaCpnID` int(11) DEFAULT '0',
  `cpaType` tinyint(4) DEFAULT '0',
  `cpaAssignment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cpaID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table customerlists
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customerlists`;

CREATE TABLE `customerlists` (
  `listID` int(11) NOT NULL AUTO_INCREMENT,
  `listName` varchar(255) NOT NULL,
  `listOwner` int(11) NOT NULL DEFAULT '0',
  `listAccess` varchar(255) NOT NULL,
  PRIMARY KEY (`listID`),
  KEY `listOwner` (`listOwner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table customerlogin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customerlogin`;

CREATE TABLE `customerlogin` (
  `clID` int(11) NOT NULL AUTO_INCREMENT,
  `clUserName` varchar(255) DEFAULT NULL,
  `clPW` varchar(255) DEFAULT NULL,
  `clLoginLevel` tinyint(4) DEFAULT '0',
  `clActions` int(11) DEFAULT '0',
  `clPercentDiscount` double DEFAULT '0',
  `clEmail` varchar(255) DEFAULT NULL,
  `clDateCreated` datetime DEFAULT NULL,
  `loyaltyPoints` int(11) DEFAULT '0',
  `clientAdminNotes` text,
  `clientCustom1` varchar(255) DEFAULT '',
  `clientCustom2` varchar(255) DEFAULT '',
  PRIMARY KEY (`clID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table dropshipper
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dropshipper`;

CREATE TABLE `dropshipper` (
  `dsID` int(11) NOT NULL AUTO_INCREMENT,
  `dsName` varchar(255) DEFAULT NULL,
  `dsEmail` varchar(255) DEFAULT NULL,
  `dsAddress` varchar(255) DEFAULT NULL,
  `dsCity` varchar(255) DEFAULT NULL,
  `dsState` varchar(255) DEFAULT NULL,
  `dsZip` varchar(255) DEFAULT NULL,
  `dsCountry` varchar(255) DEFAULT NULL,
  `dsAction` int(11) DEFAULT '0',
  `dsEmailHeader` text,
  PRIMARY KEY (`dsID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table emailmessages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `emailmessages`;

CREATE TABLE `emailmessages` (
  `emailID` int(11) NOT NULL,
  `giftcertsubject` varchar(255) DEFAULT NULL,
  `giftcertsubject2` varchar(255) DEFAULT NULL,
  `giftcertsubject3` varchar(255) DEFAULT NULL,
  `giftcertemail` text,
  `giftcertemail2` text,
  `giftcertemail3` text,
  `giftcertsendersubject` varchar(255) DEFAULT NULL,
  `giftcertsendersubject2` varchar(255) DEFAULT NULL,
  `giftcertsendersubject3` varchar(255) DEFAULT NULL,
  `giftcertsender` text,
  `giftcertsender2` text,
  `giftcertsender3` text,
  `emailsubject` varchar(255) DEFAULT NULL,
  `emailsubject2` varchar(255) DEFAULT NULL,
  `emailsubject3` varchar(255) DEFAULT NULL,
  `emailheaders` text,
  `emailheaders2` text,
  `emailheaders3` text,
  `dropshipsubject` varchar(255) DEFAULT NULL,
  `dropshipsubject2` varchar(255) DEFAULT NULL,
  `dropshipsubject3` varchar(255) DEFAULT NULL,
  `dropshipheaders` text,
  `dropshipheaders2` text,
  `dropshipheaders3` text,
  `orderstatussubject` varchar(255) DEFAULT NULL,
  `orderstatussubject2` varchar(255) DEFAULT NULL,
  `orderstatussubject3` varchar(255) DEFAULT NULL,
  `orderstatusemail` text,
  `orderstatusemail2` text,
  `orderstatusemail3` text,
  `receiptheaders` text,
  `receiptheaders2` text,
  `receiptheaders3` text,
  `notifystocksubject` varchar(255) DEFAULT NULL,
  `notifystocksubject2` varchar(255) DEFAULT NULL,
  `notifystocksubject3` varchar(255) DEFAULT NULL,
  `notifystockemail` text,
  `notifystockemail2` text,
  `notifystockemail3` text,
  PRIMARY KEY (`emailID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `emailmessages` WRITE;
/*!40000 ALTER TABLE `emailmessages` DISABLE KEYS */;

INSERT INTO `emailmessages` (`emailID`, `giftcertsubject`, `giftcertsubject2`, `giftcertsubject3`, `giftcertemail`, `giftcertemail2`, `giftcertemail3`, `giftcertsendersubject`, `giftcertsendersubject2`, `giftcertsendersubject3`, `giftcertsender`, `giftcertsender2`, `giftcertsender3`, `emailsubject`, `emailsubject2`, `emailsubject3`, `emailheaders`, `emailheaders2`, `emailheaders3`, `dropshipsubject`, `dropshipsubject2`, `dropshipsubject3`, `dropshipheaders`, `dropshipheaders2`, `dropshipheaders3`, `orderstatussubject`, `orderstatussubject2`, `orderstatussubject3`, `orderstatusemail`, `orderstatusemail2`, `orderstatusemail3`, `receiptheaders`, `receiptheaders2`, `receiptheaders3`, `notifystocksubject`, `notifystocksubject2`, `notifystocksubject3`, `notifystockemail`, `notifystockemail2`, `notifystockemail3`)
VALUES
	(1,'You received a gift certificate from %fromname%','You received a gift certificate from %fromname%','You received a gift certificate from %fromname%','Hi %toname%, %fromname% has sent you a gift certificate to the value of %value%!<br />{Your friend left the following message: %message%}<br />To redeem your gift certificate, simply pop along to our online store at:<br />%storeurl%<br />Then select the goods you require and when checking out enter the gift certificate code below:<br />%certificateid%','Hi %toname%, %fromname% has sent you a gift certificate to the value of %value%!<br />{Your friend left the following message: %message%}<br />To redeem your gift certificate, simply pop along to our online store at:<br />%storeurl%<br />Then select the goods you require and when checking out enter the gift certificate code below:<br />%certificateid%','Hi %toname%, %fromname% has sent you a gift certificate to the value of %value%!<br />{Your friend left the following message: %message%}<br />To redeem your gift certificate, simply pop along to our online store at:<br />%storeurl%<br />Then select the goods you require and when checking out enter the gift certificate code below:<br />%certificateid%','You sent a gift certificate to %toname%','You sent a gift certificate to %toname%','You sent a gift certificate to %toname%','You sent a gift certificate to %toname%.<br />Below is a copy of the email they will receive. You may want to check it was delivered.','You sent a gift certificate to %toname%.<br />Below is a copy of the email they will receive. You may want to check it was delivered.','You sent a gift certificate to %toname%.<br />Below is a copy of the email they will receive. You may want to check it was delivered.','Thank you for your order','Thank you for your order','Thank you for your order','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','We have received the following order','We have received the following order','We have received the following order','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','Order status updated','Order status updated','Order status updated','','','','%messagebody%<br />','%messagebody%<br />','%messagebody%<br />','We now have stock for %pname%','We now have stock for %pname%','We now have stock for %pname%','The product %pid% / %pname% is now back in stock.%nl%%nl%You can find this in our store at the following location:%nl%%link%%nl%%nl%Many Thanks%nl%%nl%%storeurl%%nl%','The product %pid% / %pname% is now back in stock.%nl%%nl%You can find this in our store at the following location:%nl%%link%%nl%%nl%Many Thanks%nl%%nl%%storeurl%%nl%','The product %pid% / %pname% is now back in stock.%nl%%nl%You can find this in our store at the following location:%nl%%link%%nl%%nl%Many Thanks%nl%%nl%%storeurl%%nl%');

/*!40000 ALTER TABLE `emailmessages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table giftcertificate
# ------------------------------------------------------------

DROP TABLE IF EXISTS `giftcertificate`;

CREATE TABLE `giftcertificate` (
  `gcID` varchar(255) NOT NULL,
  `gcTo` varchar(255) DEFAULT NULL,
  `gcFrom` varchar(255) DEFAULT NULL,
  `gcEmail` varchar(255) DEFAULT NULL,
  `gcOrigAmount` double DEFAULT '0',
  `gcRemaining` double DEFAULT '0',
  `gcDateCreated` date DEFAULT NULL,
  `gcDateUsed` date DEFAULT NULL,
  `gcCartID` int(11) NOT NULL DEFAULT '0',
  `gcOrderID` int(11) NOT NULL DEFAULT '0',
  `gcAuthorized` tinyint(1) DEFAULT '0',
  `gcMessage` text,
  PRIMARY KEY (`gcID`),
  KEY `gcCartID` (`gcCartID`),
  KEY `gcOrderID` (`gcOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table giftcertsapplied
# ------------------------------------------------------------

DROP TABLE IF EXISTS `giftcertsapplied`;

CREATE TABLE `giftcertsapplied` (
  `gcaGCID` varchar(255) NOT NULL,
  `gcaOrdID` int(11) NOT NULL DEFAULT '0',
  `gcaAmount` double DEFAULT '0',
  PRIMARY KEY (`gcaGCID`,`gcaOrdID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table installedmods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `installedmods`;

CREATE TABLE `installedmods` (
  `modkey` varchar(255) NOT NULL,
  `modtitle` varchar(255) NOT NULL,
  `modauthor` varchar(255) DEFAULT NULL,
  `modauthorlink` varchar(255) DEFAULT NULL,
  `modversion` varchar(255) DEFAULT NULL,
  `modectversion` varchar(255) DEFAULT NULL,
  `modlink` varchar(255) DEFAULT NULL,
  `moddate` datetime NOT NULL,
  `modnotes` text,
  PRIMARY KEY (`modkey`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table ipblocking
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ipblocking`;

CREATE TABLE `ipblocking` (
  `dcid` int(11) NOT NULL AUTO_INCREMENT,
  `dcip1` int(11) DEFAULT '0',
  `dcip2` int(11) DEFAULT '0',
  PRIMARY KEY (`dcid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table mailinglist
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mailinglist`;

CREATE TABLE `mailinglist` (
  `email` varchar(255) NOT NULL,
  `emailFormat` tinyint(4) DEFAULT '0',
  `isconfirmed` tinyint(1) DEFAULT '0',
  `mlConfirmDate` date DEFAULT NULL,
  `mlIPAddress` varchar(255) DEFAULT NULL,
  `mlName` varchar(255) DEFAULT NULL,
  `emailsent` tinyint(1) DEFAULT '0',
  `selected` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table multibuyblock
# ------------------------------------------------------------

DROP TABLE IF EXISTS `multibuyblock`;

CREATE TABLE `multibuyblock` (
  `ssdenyid` int(11) NOT NULL AUTO_INCREMENT,
  `ssdenyip` varchar(255) NOT NULL,
  `sstimesaccess` int(11) DEFAULT '0',
  `lastaccess` datetime DEFAULT NULL,
  PRIMARY KEY (`ssdenyid`),
  UNIQUE KEY `ssdenyip_2` (`ssdenyip`),
  KEY `ssdenyip` (`ssdenyip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table multisearchcriteria
# ------------------------------------------------------------

DROP TABLE IF EXISTS `multisearchcriteria`;

CREATE TABLE `multisearchcriteria` (
  `mSCpID` varchar(128) NOT NULL,
  `mSCscID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mSCpID`,`mSCscID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table multisections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `multisections`;

CREATE TABLE `multisections` (
  `pID` varchar(128) NOT NULL,
  `pSection` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pID`,`pSection`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table notifyinstock
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifyinstock`;

CREATE TABLE `notifyinstock` (
  `nsProdID` varchar(150) NOT NULL,
  `nsOptID` int(11) DEFAULT '0',
  `nsTriggerProdID` varchar(255) NOT NULL,
  `nsEmail` varchar(75) NOT NULL,
  `nsDate` datetime DEFAULT NULL,
  PRIMARY KEY (`nsTriggerProdID`,`nsEmail`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table optiongroup
# ------------------------------------------------------------

DROP TABLE IF EXISTS `optiongroup`;

CREATE TABLE `optiongroup` (
  `optGrpID` int(11) NOT NULL AUTO_INCREMENT,
  `optGrpName` varchar(255) DEFAULT NULL,
  `optGrpName2` varchar(255) DEFAULT NULL,
  `optGrpName3` varchar(255) DEFAULT NULL,
  `optGrpWorkingName` varchar(255) DEFAULT NULL,
  `optType` int(11) DEFAULT '0',
  `optFlags` int(11) DEFAULT '0',
  `optGrpSelect` tinyint(1) DEFAULT '0',
  `optTxtMaxLen` int(11) DEFAULT '0',
  `optTxtCharge` double DEFAULT '0',
  `optMultiply` tinyint(1) DEFAULT '0',
  `optAcceptChars` varchar(255) DEFAULT NULL,
  `optTooltip` text,
  PRIMARY KEY (`optGrpID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `optiongroup` WRITE;
/*!40000 ALTER TABLE `optiongroup` DISABLE KEYS */;

INSERT INTO `optiongroup` (`optGrpID`, `optGrpName`, `optGrpName2`, `optGrpName3`, `optGrpWorkingName`, `optType`, `optFlags`, `optGrpSelect`, `optTxtMaxLen`, `optTxtCharge`, `optMultiply`, `optAcceptChars`, `optTooltip`)
VALUES
	(1,'Color',NULL,NULL,'Color',2,0,1,0,0,0,'',''),
	(2,'Size',NULL,NULL,'Size (Jackets)',2,0,1,0,0,0,'',''),
	(4,'Size',NULL,NULL,'Size (Socks)',2,0,1,0,0,0,'',''),
	(6,'Processor',NULL,NULL,'Processor (Multimedia)',2,0,1,0,0,0,'',''),
	(7,'Hard Disk',NULL,NULL,'Hard Disk',2,0,1,0,0,0,'',''),
	(8,'Monitor',NULL,NULL,'Monitor',2,0,1,0,0,0,'',''),
	(9,'Network Card',NULL,NULL,'Network Card',2,0,1,0,0,0,'',''),
	(10,'Processor',NULL,NULL,'Processor (Portables)',2,0,1,0,0,0,'','');

/*!40000 ALTER TABLE `optiongroup` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `options`;

CREATE TABLE `options` (
  `optID` int(11) NOT NULL AUTO_INCREMENT,
  `optGroup` int(11) DEFAULT NULL,
  `optName` varchar(255) DEFAULT NULL,
  `optName2` varchar(255) DEFAULT NULL,
  `optName3` varchar(255) DEFAULT NULL,
  `optPriceDiff` double DEFAULT '0',
  `optWholesalePriceDiff` double DEFAULT '0',
  `optWeightDiff` double DEFAULT '0',
  `optStock` int(11) DEFAULT '0',
  `optRegExp` varchar(255) DEFAULT NULL,
  `optDefault` tinyint(1) DEFAULT '0',
  `optAltImage` varchar(255) DEFAULT NULL,
  `optAltLargeImage` varchar(255) DEFAULT NULL,
  `optDependants` varchar(255) DEFAULT '',
  `optPlaceholder` varchar(255) DEFAULT '',
  `optPlaceholder2` varchar(255) DEFAULT '',
  `optPlaceholder3` varchar(255) DEFAULT '',
  PRIMARY KEY (`optID`),
  KEY `optGroup` (`optGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;

INSERT INTO `options` (`optID`, `optGroup`, `optName`, `optName2`, `optName3`, `optPriceDiff`, `optWholesalePriceDiff`, `optWeightDiff`, `optStock`, `optRegExp`, `optDefault`, `optAltImage`, `optAltLargeImage`, `optDependants`, `optPlaceholder`, `optPlaceholder2`, `optPlaceholder3`)
VALUES
	(1,1,'Blue',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(2,1,'Red',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(3,1,'Green',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(4,1,'Yellow',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(5,2,'Small',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(6,2,'Medium',NULL,NULL,1,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(7,2,'Large',NULL,NULL,1.5,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(8,2,'X-Large',NULL,NULL,2,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(9,2,'XX-Large',NULL,NULL,2.2,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(12,4,'8',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(13,4,'8 1/2',NULL,NULL,0.1,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(14,4,'9',NULL,NULL,0.15,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(15,4,'9 1/2',NULL,NULL,0.2,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(16,4,'10',NULL,NULL,0.25,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(21,6,'Intel Pentium III 1.3GHz',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(22,6,'Intel Pentium III 1.4GHz',NULL,NULL,15,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(23,6,'Intel Pentium IV 1.5GHz',NULL,NULL,25.5,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(24,6,'Intel Pentium IV 1.7GHz',NULL,NULL,45,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(25,6,'Intel Pentium IV 2.0GHz',NULL,NULL,65,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(26,7,'20 Gigabytes',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(27,7,'40 Gigabytes',NULL,NULL,10,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(28,7,'60 Gigabytes',NULL,NULL,34,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(29,7,'80 Gigabytes',NULL,NULL,44.5,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(30,8,'15\" Standard',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(31,8,'17\" Trinitron',NULL,NULL,22,0,5,0,NULL,0,NULL,NULL,'','','',''),
	(32,8,'19\" Flatron',NULL,NULL,75,0,10,0,NULL,0,NULL,NULL,'','','',''),
	(33,8,'21\" Supertron',NULL,NULL,185,0,20,0,NULL,0,NULL,NULL,'','','',''),
	(34,9,'No',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(35,9,'Yes',NULL,NULL,15,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(36,10,'Pentium III 1.0 GHz',NULL,NULL,0,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(37,10,'Pentium III 1.3 GHz',NULL,NULL,33,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(38,10,'Pentium IV 1.5 GHz',NULL,NULL,50,0,0,0,NULL,0,NULL,NULL,'','','',''),
	(39,10,'Pentium IV 1.7 GHz',NULL,NULL,75,0,0,0,NULL,0,NULL,NULL,'','','','');

/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `ordID` int(11) NOT NULL AUTO_INCREMENT,
  `ordSessionID` varchar(255) DEFAULT NULL,
  `ordName` varchar(255) DEFAULT NULL,
  `ordAddress` varchar(255) DEFAULT NULL,
  `ordAddress2` varchar(255) DEFAULT NULL,
  `ordCity` varchar(255) DEFAULT NULL,
  `ordState` varchar(255) DEFAULT NULL,
  `ordZip` varchar(255) DEFAULT NULL,
  `ordCountry` varchar(255) DEFAULT NULL,
  `ordEmail` varchar(255) DEFAULT NULL,
  `ordPhone` varchar(255) DEFAULT NULL,
  `ordShipName` varchar(255) DEFAULT NULL,
  `ordShipAddress` varchar(255) DEFAULT NULL,
  `ordShipAddress2` varchar(255) DEFAULT NULL,
  `ordShipCity` varchar(255) DEFAULT NULL,
  `ordShipState` varchar(255) DEFAULT NULL,
  `ordShipZip` varchar(255) DEFAULT NULL,
  `ordShipCountry` varchar(255) DEFAULT NULL,
  `ordAuthNumber` varchar(255) DEFAULT NULL,
  `ordAffiliate` varchar(255) DEFAULT NULL,
  `ordPayProvider` int(11) DEFAULT '0',
  `ordTransID` varchar(255) DEFAULT NULL,
  `ordShipping` double DEFAULT '0',
  `ordStateTax` double DEFAULT '0',
  `ordCountryTax` double DEFAULT '0',
  `ordHSTTax` double DEFAULT '0',
  `ordHandling` double DEFAULT '0',
  `ordShipType` varchar(255) DEFAULT NULL,
  `ordShipCarrier` int(11) DEFAULT '0',
  `ordTotal` double DEFAULT '0',
  `ordDate` datetime DEFAULT NULL,
  `ordIP` varchar(255) DEFAULT NULL,
  `ordDiscount` double DEFAULT '0',
  `ordDiscountText` varchar(255) DEFAULT NULL,
  `ordExtra1` varchar(255) DEFAULT NULL,
  `ordExtra2` varchar(255) DEFAULT NULL,
  `ordTrackNum` varchar(255) DEFAULT NULL,
  `ordAVS` varchar(255) DEFAULT NULL,
  `ordCVV` varchar(255) DEFAULT NULL,
  `ordAddInfo` text,
  `ordCNum` text,
  `ordComLoc` tinyint(4) DEFAULT '0',
  `ordStatus` tinyint(4) DEFAULT '0',
  `ordStatusDate` datetime DEFAULT NULL,
  `ordStatusInfo` text,
  `ordInvoice` varchar(255) DEFAULT NULL,
  `ordClientID` int(11) DEFAULT '0',
  `ordShipPhone` varchar(255) DEFAULT NULL,
  `ordShipExtra1` varchar(255) DEFAULT NULL,
  `ordShipExtra2` varchar(255) DEFAULT NULL,
  `ordCheckoutExtra1` varchar(255) DEFAULT NULL,
  `ordCheckoutExtra2` varchar(255) DEFAULT NULL,
  `ordAuthStatus` varchar(255) DEFAULT NULL,
  `ordReferer` varchar(255) DEFAULT NULL,
  `ordQuerystr` varchar(255) DEFAULT NULL,
  `ordLastName` varchar(255) DEFAULT NULL,
  `ordShipLastName` varchar(255) DEFAULT NULL,
  `ordLang` tinyint(4) DEFAULT '0',
  `loyaltyPoints` int(11) DEFAULT '0',
  `pointsRedeemed` int(11) DEFAULT '0',
  `ordPrivateStatus` text,
  PRIMARY KEY (`ordID`),
  KEY `ordClientID` (`ordClientID`),
  KEY `ordDate` (`ordDate`),
  KEY `ordSessionID` (`ordSessionID`),
  KEY `ordStatus` (`ordStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;

INSERT INTO `orders` (`ordID`, `ordSessionID`, `ordName`, `ordAddress`, `ordAddress2`, `ordCity`, `ordState`, `ordZip`, `ordCountry`, `ordEmail`, `ordPhone`, `ordShipName`, `ordShipAddress`, `ordShipAddress2`, `ordShipCity`, `ordShipState`, `ordShipZip`, `ordShipCountry`, `ordAuthNumber`, `ordAffiliate`, `ordPayProvider`, `ordTransID`, `ordShipping`, `ordStateTax`, `ordCountryTax`, `ordHSTTax`, `ordHandling`, `ordShipType`, `ordShipCarrier`, `ordTotal`, `ordDate`, `ordIP`, `ordDiscount`, `ordDiscountText`, `ordExtra1`, `ordExtra2`, `ordTrackNum`, `ordAVS`, `ordCVV`, `ordAddInfo`, `ordCNum`, `ordComLoc`, `ordStatus`, `ordStatusDate`, `ordStatusInfo`, `ordInvoice`, `ordClientID`, `ordShipPhone`, `ordShipExtra1`, `ordShipExtra2`, `ordCheckoutExtra1`, `ordCheckoutExtra2`, `ordAuthStatus`, `ordReferer`, `ordQuerystr`, `ordLastName`, `ordShipLastName`, `ordLang`, `loyaltyPoints`, `pointsRedeemed`, `ordPrivateStatus`)
VALUES
	(501,'935000845','A Customer','1212 The Street',NULL,'San Jose','California','90210','United States of America','info@ecommercetemplates.com','1121212121212','','',NULL,'','','','United States of America','Email Only',NULL,4,NULL,2.5,0,0,0,0,'',0,1274.5,'2017-08-09 14:46:15','192.168.0.1',0,NULL,NULL,NULL,NULL,NULL,NULL,'This is just an example order. It is also here to make sure your order numbers do not start at zero, which just doesn\'t look good.',NULL,0,3,'2017-08-09 14:46:15','',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,'');

/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table orderstatus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orderstatus`;

CREATE TABLE `orderstatus` (
  `statID` int(11) NOT NULL,
  `statPrivate` varchar(255) DEFAULT NULL,
  `statPublic` varchar(255) DEFAULT NULL,
  `statPublic2` varchar(255) DEFAULT NULL,
  `statPublic3` varchar(255) DEFAULT NULL,
  `emailstatus` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`statID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `orderstatus` WRITE;
/*!40000 ALTER TABLE `orderstatus` DISABLE KEYS */;

INSERT INTO `orderstatus` (`statID`, `statPrivate`, `statPublic`, `statPublic2`, `statPublic3`, `emailstatus`)
VALUES
	(0,'Cancelled','Order Cancelled','Order Cancelled','Order Cancelled',0),
	(1,'Deleted','Order Deleted','Order Deleted','Order Deleted',0),
	(2,'Unauthorized','Awaiting Payment','Awaiting Payment','Awaiting Payment',0),
	(3,'Authorized','Payment Received','Payment Received','Payment Received',0),
	(4,'Packing','In Packing','In Packing','In Packing',1),
	(5,'Shipping','In Shipping','In Shipping','In Shipping',1),
	(6,'Shipped','Order Shipped','Order Shipped','Order Shipped',1),
	(7,'Completed','Order Completed','Order Completed','Order Completed',1),
	(8,'','','','',1),
	(9,'','','','',1),
	(10,'','','','',1),
	(11,'','','','',1),
	(12,'','','','',1),
	(13,'','','','',1),
	(14,'','','','',1),
	(15,'','','','',1),
	(16,'','','','',1),
	(17,'','','','',1);

/*!40000 ALTER TABLE `orderstatus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table passwordhistory
# ------------------------------------------------------------

DROP TABLE IF EXISTS `passwordhistory`;

CREATE TABLE `passwordhistory` (
  `pwhID` int(11) NOT NULL AUTO_INCREMENT,
  `liID` int(11) DEFAULT '0',
  `pwhPwd` varchar(50) DEFAULT NULL,
  `datePWChanged` datetime DEFAULT NULL,
  PRIMARY KEY (`pwhID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `passwordhistory` WRITE;
/*!40000 ALTER TABLE `passwordhistory` DISABLE KEYS */;

INSERT INTO `passwordhistory` (`pwhID`, `liID`, `pwhPwd`, `datePWChanged`)
VALUES
	(1,0,'f922d0631b6400654f8ecd7fa6b36233','2017-08-09 15:01:39');

/*!40000 ALTER TABLE `passwordhistory` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table payprovider
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payprovider`;

CREATE TABLE `payprovider` (
  `payProvID` int(11) NOT NULL,
  `payProvName` varchar(255) DEFAULT NULL,
  `payProvShow` varchar(255) DEFAULT NULL,
  `payProvShow2` varchar(255) DEFAULT NULL,
  `payProvShow3` varchar(255) DEFAULT NULL,
  `payProvEnabled` tinyint(4) DEFAULT NULL,
  `payProvAvailable` tinyint(4) DEFAULT NULL,
  `payProvDemo` tinyint(4) DEFAULT NULL,
  `payProvData1` varchar(2048) DEFAULT NULL,
  `payProvData2` varchar(2048) DEFAULT NULL,
  `payProvData3` varchar(2048) DEFAULT NULL,
  `payProvOrder` int(11) DEFAULT '0',
  `payProvMethod` int(11) DEFAULT '0',
  `payProvLevel` int(11) DEFAULT '0',
  `ppHandlingCharge` double DEFAULT '0',
  `ppHandlingPercent` double DEFAULT '0',
  `pProvHeaders` text,
  `pProvHeaders2` text,
  `pProvHeaders3` text,
  `pProvDropShipHeaders` text,
  `pProvDropShipHeaders2` text,
  `pProvDropShipHeaders3` text,
  PRIMARY KEY (`payProvID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `payprovider` WRITE;
/*!40000 ALTER TABLE `payprovider` DISABLE KEYS */;

INSERT INTO `payprovider` (`payProvID`, `payProvName`, `payProvShow`, `payProvShow2`, `payProvShow3`, `payProvEnabled`, `payProvAvailable`, `payProvDemo`, `payProvData1`, `payProvData2`, `payProvData3`, `payProvOrder`, `payProvMethod`, `payProvLevel`, `ppHandlingCharge`, `ppHandlingPercent`, `pProvHeaders`, `pProvHeaders2`, `pProvHeaders3`, `pProvDropShipHeaders`, `pProvDropShipHeaders2`, `pProvDropShipHeaders3`)
VALUES
	(1,'PayPal','PayPal','PayPal','PayPal',0,1,0,'','',NULL,1,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(2,'2Checkout','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,2,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(3,'Auth.net SIM','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,3,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(4,'Email','Email','Email','Email',1,1,0,'','',NULL,4,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(5,'World Pay','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,5,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(6,'NOCHEX','NOCHEX','NOCHEX','NOCHEX',0,1,0,'','',NULL,6,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(7,'Payflow Pro','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,7,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(8,'Payflow Link','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,8,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(9,'PayPoint.net','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,9,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(10,'Capture Card','Credit Card','Credit Card','Credit Card',0,0,0,'XXXXXOOOOOOO','',NULL,10,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(11,'PSiGate','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,11,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(12,'PSiGate SSL','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,12,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(13,'Auth.net AIM','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,13,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(14,'Custom','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,14,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(15,'Netbanx','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,15,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(16,'Linkpoint','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,16,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(17,'Email 2','Email 2','Email 2','Email 2',0,1,0,'','',NULL,17,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(18,'PayPal Direct','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,18,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(19,'PayPal Express','PayPal Express','PayPal Express','PayPal Express',0,1,0,'','',NULL,19,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(20,'Google Checkout','Google Checkout','Google Checkout','Google Checkout',0,0,0,'','',NULL,20,0,0,0,0,'%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />','%emailmessage%<br />'),
	(21,'Amazon Pay','Amazon Pay',NULL,NULL,0,1,0,'','',NULL,21,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL),
	(22,'PayPal Advanced','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,22,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL),
	(23,'Stripe','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,23,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL),
	(24,'SagePay','Credit Card','Credit Card','Credit Card',0,1,0,'','',NULL,24,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `payprovider` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table postalzones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `postalzones`;

CREATE TABLE `postalzones` (
  `pzID` int(11) NOT NULL,
  `pzName` varchar(50) DEFAULT NULL,
  `pzMultiShipping` tinyint(4) DEFAULT '0',
  `pzMethodName1` varchar(255) DEFAULT NULL,
  `pzMethodName2` varchar(255) DEFAULT NULL,
  `pzMethodName3` varchar(255) DEFAULT NULL,
  `pzMethodName4` varchar(255) DEFAULT NULL,
  `pzMethodName5` varchar(255) DEFAULT NULL,
  `pzFSA` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`pzID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `postalzones` WRITE;
/*!40000 ALTER TABLE `postalzones` DISABLE KEYS */;

INSERT INTO `postalzones` (`pzID`, `pzName`, `pzMultiShipping`, `pzMethodName1`, `pzMethodName2`, `pzMethodName3`, `pzMethodName4`, `pzMethodName5`, `pzFSA`)
VALUES
	(1,'United States',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(2,'Zone 2',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(3,'Zone 3',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(4,'Zone 4',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(5,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(6,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(7,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(8,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(9,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(10,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(11,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(12,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(13,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(14,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(15,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(16,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(17,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(18,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(19,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(20,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(21,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(22,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(23,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(24,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(101,'All States',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(102,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(103,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(104,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(105,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(106,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(107,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(108,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(109,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(110,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(111,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(112,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(113,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(114,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(115,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(116,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(117,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(118,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(119,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(120,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(121,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(122,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(123,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1),
	(124,'',0,'Standard Shipping','Express Shipping',NULL,NULL,NULL,1);

/*!40000 ALTER TABLE `postalzones` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pricebreaks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pricebreaks`;

CREATE TABLE `pricebreaks` (
  `pbQuantity` int(11) NOT NULL,
  `pbProdID` varchar(255) NOT NULL,
  `pPrice` double DEFAULT '0',
  `pWholesalePrice` double DEFAULT '0',
  PRIMARY KEY (`pbProdID`,`pbQuantity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table prodoptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `prodoptions`;

CREATE TABLE `prodoptions` (
  `poID` int(11) NOT NULL AUTO_INCREMENT,
  `poProdID` varchar(128) DEFAULT NULL,
  `poOptionGroup` int(11) DEFAULT NULL,
  PRIMARY KEY (`poID`),
  KEY `poProdID` (`poProdID`),
  KEY `poOptionGroup` (`poOptionGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `prodoptions` WRITE;
/*!40000 ALTER TABLE `prodoptions` DISABLE KEYS */;

INSERT INTO `prodoptions` (`poID`, `poProdID`, `poOptionGroup`)
VALUES
	(9,'monitor001',8),
	(21,'palmtop001',6),
	(22,'palmtop001',7),
	(23,'mouse001',1),
	(25,'portable001',10),
	(26,'pc001',8),
	(27,'pc001',6),
	(28,'pc001',7),
	(29,'pc001',9),
	(30,'testproduct',4);

/*!40000 ALTER TABLE `prodoptions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table productimages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `productimages`;

CREATE TABLE `productimages` (
  `imageProduct` varchar(128) NOT NULL DEFAULT '',
  `imageSrc` varchar(255) NOT NULL,
  `imageNumber` int(11) NOT NULL DEFAULT '0',
  `imageType` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`imageProduct`,`imageType`,`imageNumber`),
  KEY `imageProduct` (`imageProduct`),
  KEY `imageType` (`imageType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `productimages` WRITE;
/*!40000 ALTER TABLE `productimages` DISABLE KEYS */;

INSERT INTO `productimages` (`imageProduct`, `imageSrc`, `imageNumber`, `imageType`)
VALUES
	('fscanner001','prodimages/scanner2.gif',0,0),
	('fscanner001','prodimages/lscanner2.gif',0,1),
	('inkjet001','prodimages/inkjetprinter.gif',0,0),
	('inkjet001','prodimages/linkjetprinter.gif',0,1),
	('lprinter001','prodimages/laserprinter.gif',0,0),
	('lprinter001','prodimages/llaserprinter.gif',0,1),
	('monitor001','prodimages/monitor.gif',0,0),
	('monitor001','prodimages/lmonitor.gif',0,1),
	('mouse001','prodimages/mouse.gif',0,0),
	('mouse001','prodimages/lmouse.gif',0,1),
	('palmtop001','prodimages/palmtop.gif',0,0),
	('palmtop001','prodimages/lpalmtop.gif',0,1),
	('pc001','prodimages/pc.gif',0,0),
	('pc001','prodimages/lpc.gif',0,1),
	('portable001','prodimages/portable.gif',0,0),
	('portable001','prodimages/lportable.gif',0,1),
	('scanner001','prodimages/scanner.gif',0,0),
	('scanner001','prodimages/lscanner.gif',0,1),
	('serialcab001','prodimages/computercable.gif',0,0),
	('serialcab001','prodimages/lcomputercable.gif',0,1),
	('testproduct','prodimages/computercable.gif',0,0),
	('testproduct','prodimages/lcomputercable.gif',0,1);

/*!40000 ALTER TABLE `productimages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table productpackages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `productpackages`;

CREATE TABLE `productpackages` (
  `packageID` varchar(128) NOT NULL,
  `pID` varchar(128) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`packageID`,`pID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `pID` varchar(128) NOT NULL,
  `pName` varchar(255) DEFAULT NULL,
  `pName2` varchar(255) DEFAULT NULL,
  `pName3` varchar(255) DEFAULT NULL,
  `pSection` int(11) DEFAULT NULL,
  `pDescription` text,
  `pDescription2` text,
  `pDescription3` text,
  `pLongdescription` text,
  `pLongdescription2` text,
  `pLongdescription3` text,
  `pDownload` varchar(255) DEFAULT NULL,
  `pPrice` double DEFAULT '0',
  `pListPrice` double DEFAULT '0',
  `pWholesalePrice` double DEFAULT '0',
  `pShipping` double DEFAULT '0',
  `pShipping2` double DEFAULT '0',
  `pWeight` double DEFAULT '0',
  `pDisplay` tinyint(1) DEFAULT '1',
  `pSell` tinyint(1) DEFAULT '1',
  `pStaticPage` tinyint(1) DEFAULT '0',
  `pStockByOpts` tinyint(1) DEFAULT '0',
  `pRecommend` tinyint(1) DEFAULT '0',
  `pExemptions` tinyint(4) DEFAULT '0',
  `pInStock` int(11) DEFAULT '0',
  `pDropship` int(11) DEFAULT '0',
  `pDims` varchar(255) DEFAULT NULL,
  `pTax` double DEFAULT NULL,
  `pOrder` int(11) DEFAULT '0',
  `pManufacturer` int(11) DEFAULT '0',
  `pSKU` varchar(255) DEFAULT NULL,
  `pDateAdded` date DEFAULT NULL,
  `pTotRating` int(11) DEFAULT '0',
  `pNumRatings` int(11) DEFAULT '0',
  `pSearchParams` text,
  `pGiftWrap` tinyint(1) DEFAULT '0',
  `pBackOrder` tinyint(1) DEFAULT '0',
  `pCustom1` varchar(2048) DEFAULT NULL,
  `pCustom2` varchar(2048) DEFAULT NULL,
  `pCustom3` varchar(2048) DEFAULT NULL,
  `pStaticURL` varchar(255) DEFAULT '',
  `pTitle` varchar(255) DEFAULT '',
  `pMetaDesc` varchar(255) DEFAULT '',
  PRIMARY KEY (`pID`),
  KEY `pOrder` (`pOrder`),
  KEY `pDateAdded` (`pDateAdded`),
  KEY `pDisplay` (`pDisplay`),
  KEY `pManufacturer` (`pManufacturer`),
  KEY `pName` (`pName`),
  KEY `pPrice` (`pPrice`),
  KEY `pSection` (`pSection`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` (`pID`, `pName`, `pName2`, `pName3`, `pSection`, `pDescription`, `pDescription2`, `pDescription3`, `pLongdescription`, `pLongdescription2`, `pLongdescription3`, `pDownload`, `pPrice`, `pListPrice`, `pWholesalePrice`, `pShipping`, `pShipping2`, `pWeight`, `pDisplay`, `pSell`, `pStaticPage`, `pStockByOpts`, `pRecommend`, `pExemptions`, `pInStock`, `pDropship`, `pDims`, `pTax`, `pOrder`, `pManufacturer`, `pSKU`, `pDateAdded`, `pTotRating`, `pNumRatings`, `pSearchParams`, `pGiftWrap`, `pBackOrder`, `pCustom1`, `pCustom2`, `pCustom3`, `pStaticURL`, `pTitle`, `pMetaDesc`)
VALUES
	('fscanner001','Professional Scanner',NULL,NULL,2,'600 dpi full color quality for professional quality scanning results. Twice the resolution and twice the quality for your scans, but at an incredible low price.',NULL,NULL,'600 dpi full color quality for professional quality scanning results. Twice the resolution and twice the quality for your scans, but at an incredible low price.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products.',NULL,NULL,NULL,120,0,0,5,0,4.04,1,1,0,0,0,0,4,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('inkjet001','Inkjet Printer',NULL,NULL,4,'This inkjet printer really packs a punch for the home user. Full color prints at photo quality. Perfect for everything from letters to the bank manager, to printing out your favourite digital family pictures.',NULL,NULL,'This inkjet printer really packs a punch for the home user. Full color prints at photo quality. Perfect for everything from letters to the bank manager, to printing out your favourite digital family pictures.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products.',NULL,NULL,NULL,95,0,0,4,0,2.02,1,1,0,0,0,0,0,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('lprinter001','Laser Printer',NULL,NULL,4,'For the small or home office, this laser printer is the perfect solution. Up to 15 black and white pages per minute, and a full 600dpi resolution for the quality your business demands.',NULL,NULL,'For the small or home office, this laser printer is the perfect solution. Up to 15 black and white pages per minute, and a full 600dpi resolution for the quality your business demands.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products.',NULL,NULL,NULL,499,0,0,5,0,2,1,1,0,0,0,0,0,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('monitor001','PC Monitor',NULL,NULL,3,'17\" full color flat screen monitor, with 0.25 dot resolution and 16.25\" viewable area.',NULL,NULL,'17\" full color flat screen monitor, with 0.25 dot resolution and 16.25\" viewable area.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products.',NULL,NULL,NULL,299,0,0,5,0,2.15,1,1,0,0,0,0,0,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('mouse001','PC Mouse',NULL,NULL,3,'Indispensible for using your PC, this mouse has easyglide action and simple connectivity to get your PC up and surfing the internet in no time.',NULL,NULL,'Indispensible for using your PC, this mouse has easyglide action and simple connectivity to get your PC up and surfing the internet in no time.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products.',NULL,NULL,NULL,7,0,0,1,0,0.15,1,1,0,0,0,0,0,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('palmtop001','Palmtop Computer',NULL,NULL,1,'The very latest in palmtop technology. All the power of a PC in a pocket sized system. Great for the mobile business person.',NULL,NULL,'The very latest in palmtop technology. All the power of a PC in a pocket sized system. Great for the mobile business person.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products.',NULL,NULL,NULL,199,0,0,5,0,4.12,1,1,0,0,0,0,0,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('pc001','#1 PC multimedia package',NULL,NULL,1,'This is an example of how you can use the product options to create advanced product descriptions with automatic price calculations.',NULL,NULL,'Internet ready PC package. Just choose your monitor, hard disk size, processor speed and network card.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products. You can also include HTML Markup in the short and long product descriptions.',NULL,NULL,NULL,1200,0,0,10,0,6,1,1,0,0,0,0,10,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('portable001','Portable PC',NULL,NULL,1,'For those on the go, this portable PC is just the thing. Your choice of processor, 256mb ram and 4gb harddisk make this the perfect solution for all types of applications. Buy now while stocks last.',NULL,NULL,'For those on the go, this portable PC is just the thing. Your choice of processor, 256mb ram and 4gb harddisk make this the perfect solution for all types of applications. Buy now while stocks last.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products.',NULL,NULL,NULL,1250,0,0,6,0,2,1,1,0,0,0,0,0,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('scanner001','Flatbed scanner',NULL,NULL,2,'Up to 300 dpi full color resolution and incredible speed make this a top choice for all your scanning needs. Scan professional quality photos, text or artwork in seconds.',NULL,NULL,'Up to 300 dpi full color resolution and incredible speed make this a top choice for all your scanning needs. Scan professional quality photos, text or artwork in seconds.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products.',NULL,NULL,NULL,89,0,0,6,0,5.1,1,1,0,0,0,0,0,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('serialcab001','PC Serial Cable',NULL,NULL,3,'Can be used for connecting PC systems to peripheral devices such as serial printers and scanners.',NULL,NULL,'Can be used for connecting PC systems to peripheral devices such as serial printers and scanners.<br>As well as a larger image, you can use this \"Long Description\" to add extra detail or information about your products.',NULL,NULL,NULL,2.5,0,0,0.2,0,0.1,1,1,0,0,0,0,0,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','',''),
	('testproduct','Cheap Test Product',NULL,NULL,3,'This is a cheap product for testing. Note how you can use HTML Markup in product descriptions.<br>Also note that as you change the product options, the price changes automatically.',NULL,NULL,'This is a cheap product for testing. Note how you can use HTML Markup in product descriptions.<br>In the long description you can go into more detail about products.',NULL,NULL,NULL,0.01,0,0,0,0,3,1,1,0,0,0,0,21,0,NULL,NULL,0,0,NULL,'2017-08-08',0,0,NULL,0,0,'','','','','','');

/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ratings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ratings`;

CREATE TABLE `ratings` (
  `rtID` int(11) NOT NULL AUTO_INCREMENT,
  `rtProdID` varchar(255) NOT NULL,
  `rtRating` tinyint(4) DEFAULT '0',
  `rtLanguage` tinyint(4) DEFAULT '0',
  `rtDate` date DEFAULT NULL,
  `rtApproved` tinyint(1) DEFAULT '0',
  `rtIPAddress` varchar(255) DEFAULT NULL,
  `rtPosterName` varchar(255) DEFAULT NULL,
  `rtPosterLoginID` int(11) DEFAULT '0',
  `rtPosterEmail` varchar(255) DEFAULT NULL,
  `rtHeader` varchar(255) DEFAULT NULL,
  `rtComments` text,
  PRIMARY KEY (`rtID`),
  KEY `rtProdID` (`rtProdID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table recentlyviewed
# ------------------------------------------------------------

DROP TABLE IF EXISTS `recentlyviewed`;

CREATE TABLE `recentlyviewed` (
  `rvID` int(11) NOT NULL AUTO_INCREMENT,
  `rvProdID` varchar(255) NOT NULL,
  `rvProdName` varchar(255) NOT NULL,
  `rvProdSection` int(11) NOT NULL DEFAULT '0',
  `rvProdURL` varchar(255) NOT NULL,
  `rvSessionID` varchar(255) NOT NULL,
  `rvCustomerID` int(11) NOT NULL DEFAULT '0',
  `rvDate` datetime NOT NULL,
  PRIMARY KEY (`rvID`),
  KEY `rvCustomerID` (`rvCustomerID`),
  KEY `rvDate` (`rvDate`),
  KEY `rvProdID` (`rvProdID`),
  KEY `rvProdSection` (`rvProdSection`),
  KEY `rvSessionID` (`rvSessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table relatedprods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `relatedprods`;

CREATE TABLE `relatedprods` (
  `rpProdID` varchar(128) NOT NULL,
  `rpRelProdID` varchar(128) NOT NULL,
  PRIMARY KEY (`rpProdID`,`rpRelProdID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table searchcriteria
# ------------------------------------------------------------

DROP TABLE IF EXISTS `searchcriteria`;

CREATE TABLE `searchcriteria` (
  `scID` int(11) NOT NULL,
  `scOrder` int(11) DEFAULT '0',
  `scGroup` int(11) DEFAULT '0',
  `scWorkingName` varchar(255) DEFAULT NULL,
  `scName` varchar(255) DEFAULT NULL,
  `scName2` varchar(255) DEFAULT NULL,
  `scName3` varchar(255) DEFAULT NULL,
  `scLogo` varchar(255) DEFAULT NULL,
  `scURL` varchar(255) DEFAULT NULL,
  `scURL2` varchar(255) DEFAULT NULL,
  `scURL3` varchar(255) DEFAULT NULL,
  `scEmail` varchar(255) DEFAULT NULL,
  `scDescription` text,
  `scDescription2` text,
  `scDescription3` text,
  `scNotes` text,
  PRIMARY KEY (`scID`),
  KEY `scOrder` (`scOrder`),
  KEY `scGroup` (`scGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table searchcriteriagroup
# ------------------------------------------------------------

DROP TABLE IF EXISTS `searchcriteriagroup`;

CREATE TABLE `searchcriteriagroup` (
  `scgID` int(11) NOT NULL,
  `scgOrder` int(11) DEFAULT '0',
  `scgTitle` varchar(128) NOT NULL,
  `scgTitle2` varchar(128) DEFAULT NULL,
  `scgTitle3` varchar(128) DEFAULT NULL,
  `scgWorkingName` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`scgID`),
  KEY `scgOrder` (`scgOrder`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `searchcriteriagroup` WRITE;
/*!40000 ALTER TABLE `searchcriteriagroup` DISABLE KEYS */;

INSERT INTO `searchcriteriagroup` (`scgID`, `scgOrder`, `scgTitle`, `scgTitle2`, `scgTitle3`, `scgWorkingName`)
VALUES
	(0,0,'Manufacturer','Manufacturer','Manufacturer','Manufacturer');

/*!40000 ALTER TABLE `searchcriteriagroup` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sections`;

CREATE TABLE `sections` (
  `sectionID` int(11) NOT NULL DEFAULT '0',
  `sectionName` varchar(255) NOT NULL,
  `sectionName2` varchar(255) NOT NULL,
  `sectionName3` varchar(255) NOT NULL,
  `sectionWorkingName` varchar(255) DEFAULT NULL,
  `sectionurl` varchar(255) NOT NULL,
  `sectionImage` varchar(255) DEFAULT NULL,
  `sectionDescription` text,
  `sectionDescription2` text,
  `sectionDescription3` text,
  `topSection` int(11) DEFAULT '0',
  `rootSection` int(11) DEFAULT '0',
  `sectionOrder` int(11) DEFAULT '0',
  `sectionDisabled` tinyint(4) DEFAULT '0',
  `sectionHeader` text,
  `sectionHeader2` text,
  `sectionHeader3` text,
  `sTitle` varchar(255) DEFAULT '',
  `sMetaDesc` varchar(255) DEFAULT '',
  `sectionurl2` varchar(255) NOT NULL,
  `sectionurl3` varchar(255) NOT NULL,
  PRIMARY KEY (`sectionID`),
  KEY `sectionDisabled` (`sectionDisabled`),
  KEY `sectionOrder` (`sectionOrder`),
  KEY `topSection` (`topSection`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;

INSERT INTO `sections` (`sectionID`, `sectionName`, `sectionName2`, `sectionName3`, `sectionWorkingName`, `sectionurl`, `sectionImage`, `sectionDescription`, `sectionDescription2`, `sectionDescription3`, `topSection`, `rootSection`, `sectionOrder`, `sectionDisabled`, `sectionHeader`, `sectionHeader2`, `sectionHeader3`, `sTitle`, `sMetaDesc`, `sectionurl2`, `sectionurl3`)
VALUES
	(1,'Systems','','','Systems','','','Complete PC systems including tower systems, laptops and palmtop computers. The very best in PC power.',NULL,NULL,5,1,3,0,'','','','','','',''),
	(2,'Scanners','','','Scanners','','','RGB color scanners and scanner based systems for everything from digital snaps to professional prints.',NULL,NULL,6,1,5,0,'','','','','','',''),
	(3,'Peripherals','','','Peripherals','','','Keyboards, mice, cables and mousemats and all your other PC peripheral needs.',NULL,NULL,5,1,2,0,'','','','','','',''),
	(4,'Printers','','','Printers','','','Inkjet and laser printers for the very best in home and small office printing systems.',NULL,NULL,6,1,6,0,'','','','','','',''),
	(5,'Computer Parts','','','Computer Parts','','','Bits and pieces for your computer',NULL,NULL,0,0,1,0,'','','','','','',''),
	(6,'Printers and Scanners','','','Printers and Scanners','','','Printers and scanners for your PC',NULL,NULL,0,0,4,0,'','','','','','','');

/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table shipoptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shipoptions`;

CREATE TABLE `shipoptions` (
  `soIndex` int(11) NOT NULL DEFAULT '0',
  `soOrderID` int(11) NOT NULL DEFAULT '0',
  `soMethodName` varchar(255) DEFAULT NULL,
  `soCost` double DEFAULT '0',
  `soFreeShip` tinyint(4) DEFAULT '0',
  `soShipType` int(11) DEFAULT '0',
  `soDeliveryTime` varchar(255) DEFAULT NULL,
  `soDateAdded` datetime NOT NULL,
  `soFreeShipExempt` int(11) DEFAULT '0',
  PRIMARY KEY (`soIndex`,`soOrderID`),
  KEY `soDateAdded` (`soDateAdded`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table states
# ------------------------------------------------------------

DROP TABLE IF EXISTS `states`;

CREATE TABLE `states` (
  `stateID` int(11) NOT NULL,
  `stateName` varchar(50) DEFAULT NULL,
  `stateAbbrev` varchar(50) DEFAULT NULL,
  `stateTax` double DEFAULT '0',
  `stateEnabled` tinyint(4) DEFAULT NULL,
  `stateZone` int(11) DEFAULT '0',
  `stateFreeShip` tinyint(4) DEFAULT '1',
  `stateCountryID` int(11) DEFAULT '0',
  `stateName2` varchar(50) DEFAULT '',
  `stateName3` varchar(50) DEFAULT '',
  PRIMARY KEY (`stateID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;

INSERT INTO `states` (`stateID`, `stateName`, `stateAbbrev`, `stateTax`, `stateEnabled`, `stateZone`, `stateFreeShip`, `stateCountryID`, `stateName2`, `stateName3`)
VALUES
	(1,'Alberta','AB',0,1,0,0,2,'Alberta','Alberta'),
	(2,'Alabama','AL',0,1,101,1,1,'Alabama','Alabama'),
	(3,'Alaska','AK',0,1,101,1,1,'Alaska','Alaska'),
	(4,'American Samoa','AS',0,1,101,1,1,'American Samoa','American Samoa'),
	(5,'Arizona','AZ',0,1,101,1,1,'Arizona','Arizona'),
	(6,'Arkansas','AR',0,1,101,1,1,'Arkansas','Arkansas'),
	(7,'California','CA',0,1,101,1,1,'California','California'),
	(8,'Colorado','CO',0,1,101,1,1,'Colorado','Colorado'),
	(9,'Connecticut','CT',0,1,101,1,1,'Connecticut','Connecticut'),
	(10,'Delaware','DE',0,1,101,1,1,'Delaware','Delaware'),
	(11,'District Of Columbia','DC',0,1,101,1,1,'District Of Columbia','District Of Columbia'),
	(12,'Fdr. States Of Micronesia','FM',0,1,101,1,1,'Fdr. States Of Micronesia','Fdr. States Of Micronesia'),
	(13,'Florida','FL',0,1,101,1,1,'Florida','Florida'),
	(14,'Georgia','GA',0,1,101,1,1,'Georgia','Georgia'),
	(15,'Guam','GU',0,1,101,1,1,'Guam','Guam'),
	(16,'Hawaii','HI',0,1,101,1,1,'Hawaii','Hawaii'),
	(17,'Idaho','ID',0,1,101,1,1,'Idaho','Idaho'),
	(18,'Illinois','IL',0,1,101,1,1,'Illinois','Illinois'),
	(19,'Indiana','IN',0,1,101,1,1,'Indiana','Indiana'),
	(20,'Iowa','IA',0,1,101,1,1,'Iowa','Iowa'),
	(21,'Kansas','KS',0,1,101,1,1,'Kansas','Kansas'),
	(22,'Kentucky','KY',0,1,101,1,1,'Kentucky','Kentucky'),
	(23,'Louisiana','LA',0,1,101,1,1,'Louisiana','Louisiana'),
	(24,'Maine','ME',0,1,101,1,1,'Maine','Maine'),
	(25,'Marshall Islands','MH',0,1,101,1,1,'Marshall Islands','Marshall Islands'),
	(26,'Maryland','MD',0,1,101,1,1,'Maryland','Maryland'),
	(27,'Massachusetts','MA',0,1,101,1,1,'Massachusetts','Massachusetts'),
	(28,'Michigan','MI',0,1,101,1,1,'Michigan','Michigan'),
	(29,'Minnesota','MN',0,1,101,1,1,'Minnesota','Minnesota'),
	(30,'Mississippi','MS',0,1,101,1,1,'Mississippi','Mississippi'),
	(31,'Missouri','MO',0,1,101,1,1,'Missouri','Missouri'),
	(32,'Montana','MT',0,1,101,1,1,'Montana','Montana'),
	(33,'Nebraska','NE',0,1,101,1,1,'Nebraska','Nebraska'),
	(34,'Nevada','NV',0,1,101,1,1,'Nevada','Nevada'),
	(35,'New Hampshire','NH',0,1,101,1,1,'New Hampshire','New Hampshire'),
	(36,'New Jersey','NJ',0,1,101,1,1,'New Jersey','New Jersey'),
	(37,'New Mexico','NM',0,1,101,1,1,'New Mexico','New Mexico'),
	(38,'New York','NY',0,1,101,1,1,'New York','New York'),
	(39,'North Carolina','NC',0,1,101,1,1,'North Carolina','North Carolina'),
	(40,'North Dakota','ND',0,1,101,1,1,'North Dakota','North Dakota'),
	(41,'Northern Mariana Islands','MP',0,1,101,1,1,'Northern Mariana Islands','Northern Mariana Islands'),
	(42,'Ohio','OH',0,1,101,1,1,'Ohio','Ohio'),
	(43,'Oklahoma','OK',0,1,101,1,1,'Oklahoma','Oklahoma'),
	(44,'Oregon','OR',0,1,101,1,1,'Oregon','Oregon'),
	(45,'Palau','PW',0,1,101,1,1,'Palau','Palau'),
	(46,'Pennsylvania','PA',0,1,101,1,1,'Pennsylvania','Pennsylvania'),
	(47,'Puerto Rico','PR',0,1,101,1,1,'Puerto Rico','Puerto Rico'),
	(48,'Rhode Island','RI',0,1,101,1,1,'Rhode Island','Rhode Island'),
	(49,'South Carolina','SC',0,1,101,1,1,'South Carolina','South Carolina'),
	(50,'South Dakota','SD',0,1,101,1,1,'South Dakota','South Dakota'),
	(51,'Tennessee','TN',0,1,101,1,1,'Tennessee','Tennessee'),
	(52,'Texas','TX',0,1,101,1,1,'Texas','Texas'),
	(53,'Utah','UT',0,1,101,1,1,'Utah','Utah'),
	(54,'Vermont','VT',0,1,101,1,1,'Vermont','Vermont'),
	(55,'Virgin Islands','VI',0,1,101,1,1,'Virgin Islands','Virgin Islands'),
	(56,'Virginia','VA',0,1,101,1,1,'Virginia','Virginia'),
	(57,'Washington','WA',0,1,101,1,1,'Washington','Washington'),
	(58,'West Virginia','WV',0,1,101,1,1,'West Virginia','West Virginia'),
	(59,'Wisconsin','WI',0,1,101,1,1,'Wisconsin','Wisconsin'),
	(60,'Wyoming','WY',0,1,101,1,1,'Wyoming','Wyoming'),
	(61,'Armed Forces Africa','AE',0,0,101,1,1,'Armed Forces Africa','Armed Forces Africa'),
	(62,'Armed Forces Americas','AA',0,0,101,1,1,'Armed Forces Americas','Armed Forces Americas'),
	(63,'Armed Forces Canada','AE',0,0,101,1,1,'Armed Forces Canada','Armed Forces Canada'),
	(64,'Armed Forces Europe','AE',0,0,101,1,1,'Armed Forces Europe','Armed Forces Europe'),
	(65,'Armed Forces Middle East','AE',0,0,101,1,1,'Armed Forces Middle East','Armed Forces Middle East'),
	(66,'Armed Forces Pacific','AP',0,0,101,1,1,'Armed Forces Pacific','Armed Forces Pacific'),
	(67,'British Columbia','BC',0,1,0,0,2,'British Columbia','British Columbia'),
	(68,'Manitoba','MB',0,1,0,0,2,'Manitoba','Manitoba'),
	(69,'New Brunswick','NB',0,1,0,0,2,'New Brunswick','New Brunswick'),
	(70,'Newfoundland','NF',0,1,0,0,2,'Newfoundland','Newfoundland'),
	(71,'North West Territories','NT',0,1,0,0,2,'North West Territories','North West Territories'),
	(72,'Nova Scotia','NS',0,1,0,0,2,'Nova Scotia','Nova Scotia'),
	(73,'Nunavut','NU',0,1,0,0,2,'Nunavut','Nunavut'),
	(74,'Ontario','ON',0,1,0,0,2,'Ontario','Ontario'),
	(75,'Prince Edward Island','PE',0,1,0,0,2,'Prince Edward Island','Prince Edward Island'),
	(76,'Quebec','QC',0,1,0,0,2,'Quebec','Quebec'),
	(77,'Saskatchewan','SK',0,1,0,0,2,'Saskatchewan','Saskatchewan'),
	(78,'Yukon Territory','YT',0,1,0,0,2,'Yukon Territory','Yukon Territory'),
	(79,'Australian Capital Territory','ACT',0,1,0,0,14,'Australian Capital Territory','Australian Capital Territory'),
	(80,'New South Wales','NSW',0,1,0,0,14,'New South Wales','New South Wales'),
	(81,'Northern Territory','NT',0,1,0,0,14,'Northern Territory','Northern Territory'),
	(82,'Queensland','QLD',0,1,0,0,14,'Queensland','Queensland'),
	(83,'South Australia','SA',0,1,0,0,14,'South Australia','South Australia'),
	(84,'Tasmania','TA',0,1,0,0,14,'Tasmania','Tasmania'),
	(85,'Victoria','VIC',0,1,0,0,14,'Victoria','Victoria'),
	(86,'Western Australia','WA',0,1,0,0,14,'Western Australia','Western Australia'),
	(87,'Carlow','CA',0,1,0,0,91,'Carlow','Carlow'),
	(88,'Cavan','CV',0,1,0,0,91,'Cavan','Cavan'),
	(89,'Clare','CL',0,1,0,0,91,'Clare','Clare'),
	(90,'Cork','CO',0,1,0,0,91,'Cork','Cork'),
	(91,'Donegal','DO',0,1,0,0,91,'Donegal','Donegal'),
	(92,'Dublin','DU',0,1,0,0,91,'Dublin','Dublin'),
	(93,'Galway','GA',0,1,0,0,91,'Galway','Galway'),
	(94,'Kerry','KE',0,1,0,0,91,'Kerry','Kerry'),
	(95,'Kildare','KI',0,1,0,0,91,'Kildare','Kildare'),
	(96,'Kilkenny','KL',0,1,0,0,91,'Kilkenny','Kilkenny'),
	(97,'Laois','LA',0,1,0,0,91,'Laois','Laois'),
	(98,'Leitrim','LE',0,1,0,0,91,'Leitrim','Leitrim'),
	(99,'Limerick','LI',0,1,0,0,91,'Limerick','Limerick'),
	(100,'Longford','LO',0,1,0,0,91,'Longford','Longford'),
	(101,'Louth','LU',0,1,0,0,91,'Louth','Louth'),
	(102,'Mayo','MA',0,1,0,0,91,'Mayo','Mayo'),
	(103,'Meath','ME',0,1,0,0,91,'Meath','Meath'),
	(104,'Monaghan','MO',0,1,0,0,91,'Monaghan','Monaghan'),
	(105,'Offaly','OF',0,1,0,0,91,'Offaly','Offaly'),
	(106,'Roscommon','RO',0,1,0,0,91,'Roscommon','Roscommon'),
	(107,'Sligo','SL',0,1,0,0,91,'Sligo','Sligo'),
	(108,'Tipperary','TI',0,1,0,0,91,'Tipperary','Tipperary'),
	(109,'Waterford','WA',0,1,0,0,91,'Waterford','Waterford'),
	(110,'Westmeath','WE',0,1,0,0,91,'Westmeath','Westmeath'),
	(111,'Wexford','WX',0,1,0,0,91,'Wexford','Wexford'),
	(112,'Wicklow','WI',0,1,0,0,91,'Wicklow','Wicklow'),
	(113,'Ashburton','AS',0,1,0,0,136,'Ashburton','Ashburton'),
	(114,'Auckland','AU',0,1,0,0,136,'Auckland','Auckland'),
	(115,'Bay of Plenty','BP',0,1,0,0,136,'Bay of Plenty','Bay of Plenty'),
	(116,'Buller','BU',0,1,0,0,136,'Buller','Buller'),
	(117,'Canterbury','CB',0,1,0,0,136,'Canterbury','Canterbury'),
	(118,'Carterton','CA',0,1,0,0,136,'Carterton','Carterton'),
	(119,'Central Otago','CO',0,1,0,0,136,'Central Otago','Central Otago'),
	(120,'Clutha','CL',0,1,0,0,136,'Clutha','Clutha'),
	(121,'Counties Manukau','CM',0,1,0,0,136,'Counties Manukau','Counties Manukau'),
	(122,'Dunedin City','DC',0,1,0,0,136,'Dunedin City','Dunedin City'),
	(123,'Far North','FN',0,1,0,0,136,'Far North','Far North'),
	(124,'Franklin','FR',0,1,0,0,136,'Franklin','Franklin'),
	(125,'Gisborne','GS',0,1,0,0,136,'Gisborne','Gisborne'),
	(126,'Gore','GO',0,1,0,0,136,'Gore','Gore'),
	(127,'Grey','GR',0,1,0,0,136,'Grey','Grey'),
	(128,'Hamilton City','HC',0,1,0,0,136,'Hamilton City','Hamilton City'),
	(129,'Hastings','HS',0,1,0,0,136,'Hastings','Hastings'),
	(130,'Hauraki','HI',0,1,0,0,136,'Hauraki','Hauraki'),
	(131,'Hawke\'s Bay','HB',0,1,0,0,136,'Hawke\'s Bay','Hawke\'s Bay'),
	(132,'Horowhenua','HW',0,1,0,0,136,'Horowhenua','Horowhenua'),
	(133,'Hurunui','HU',0,1,0,0,136,'Hurunui','Hurunui'),
	(134,'Hutt Valley','HV',0,1,0,0,136,'Hutt Valley','Hutt Valley'),
	(135,'Invercargill','IC',0,1,0,0,136,'Invercargill','Invercargill'),
	(136,'Kaikoura','KK',0,1,0,0,136,'Kaikoura','Kaikoura'),
	(137,'Kaipara','KP',0,1,0,0,136,'Kaipara','Kaipara'),
	(138,'Kapiti Coast','KC',0,1,0,0,136,'Kapiti Coast','Kapiti Coast'),
	(139,'Kawerau','KW',0,1,0,0,136,'Kawerau','Kawerau'),
	(140,'Manawatu','MW',0,1,0,0,136,'Manawatu','Manawatu'),
	(141,'Marlborough','MB',0,1,0,0,136,'Marlborough','Marlborough'),
	(142,'Masteron','MS',0,1,0,0,136,'Masteron','Masteron'),
	(143,'Matamata Piako','MP',0,1,0,0,136,'Matamata Piako','Matamata Piako'),
	(144,'New Plymouth','NP',0,1,0,0,136,'New Plymouth','New Plymouth'),
	(145,'North Shore City','NS',0,1,0,0,136,'North Shore City','North Shore City'),
	(146,'Otaki','OT',0,1,0,0,136,'Otaki','Otaki'),
	(147,'Otorohanga','OT',0,1,0,0,136,'Otorohanga','Otorohanga'),
	(148,'Palmerston North','PN',0,1,0,0,136,'Palmerston North','Palmerston North'),
	(149,'Papakura','PK',0,1,0,0,136,'Papakura','Papakura'),
	(150,'Porirua City','PC',0,1,0,0,136,'Porirua City','Porirua City'),
	(151,'Queenstown Lakes','QL',0,1,0,0,136,'Queenstown Lakes','Queenstown Lakes'),
	(152,'Rotorua','RT',0,1,0,0,136,'Rotorua','Rotorua'),
	(153,'Ruapehu','RU',0,1,0,0,136,'Ruapehu','Ruapehu'),
	(154,'Selwyn','SN',0,1,0,0,136,'Selwyn','Selwyn'),
	(155,'South Taranaki','ST',0,1,0,0,136,'South Taranaki','South Taranaki'),
	(156,'South Waikato','SW',0,1,0,0,136,'South Waikato','South Waikato'),
	(157,'South Wairarapa','SA',0,1,0,0,136,'South Wairarapa','South Wairarapa'),
	(158,'Southland','SL',0,1,0,0,136,'Southland','Southland'),
	(159,'Stratford','SF',0,1,0,0,136,'Stratford','Stratford'),
	(160,'Tasman','TM',0,1,0,0,136,'Tasman','Tasman'),
	(161,'Taupo','TP',0,1,0,0,136,'Taupo','Taupo'),
	(162,'Tauranga','TR',0,1,0,0,136,'Tauranga','Tauranga'),
	(163,'Thames Coromandel','TC',0,1,0,0,136,'Thames Coromandel','Thames Coromandel'),
	(164,'Timaru','TM',0,1,0,0,136,'Timaru','Timaru'),
	(165,'Waikato','WK',0,1,0,0,136,'Waikato','Waikato'),
	(166,'Waimakariri','WM',0,1,0,0,136,'Waimakariri','Waimakariri'),
	(167,'Waimate','WE',0,1,0,0,136,'Waimate','Waimate'),
	(168,'Waiora','WO',0,1,0,0,136,'Waiora','Waiora'),
	(169,'Waipa','WP',0,1,0,0,136,'Waipa','Waipa'),
	(170,'Waitakere','WT',0,1,0,0,136,'Waitakere','Waitakere'),
	(171,'Waitaki','WI',0,1,0,0,136,'Waitaki','Waitaki'),
	(172,'Waitomo','Wa',0,1,0,0,136,'Waitomo','Waitomo'),
	(173,'Wellington City','WC',0,1,0,0,136,'Wellington City','Wellington City'),
	(174,'Western Bay of Plenty','WB',0,1,0,0,136,'Western Bay of Plenty','Western Bay of Plenty'),
	(175,'Westland','WL',0,1,0,0,136,'Westland','Westland'),
	(176,'Whakatane','WH',0,1,0,0,136,'Whakatane','Whakatane'),
	(177,'Whanganui','WG',0,1,0,0,136,'Whanganui','Whanganui'),
	(178,'Whangarei','WE',0,1,0,0,136,'Whangarei','Whangarei'),
	(179,'Eastern Cape','EP',0,1,0,0,174,'Eastern Cape','Eastern Cape'),
	(180,'Free State','OFS',0,1,0,0,174,'Free State','Free State'),
	(181,'Gauteng','GA',0,1,0,0,174,'Gauteng','Gauteng'),
	(182,'Kwazulu-Natal','KZN',0,1,0,0,174,'Kwazulu-Natal','Kwazulu-Natal'),
	(183,'Mpumalanga','MP',0,1,0,0,174,'Mpumalanga','Mpumalanga'),
	(184,'Northern Cape','NC',0,1,0,0,174,'Northern Cape','Northern Cape'),
	(185,'Limpopo','LI',0,1,0,0,174,'Limpopo','Limpopo'),
	(186,'North West Province','NWP',0,1,0,0,174,'North West Province','North West Province'),
	(187,'Western Cape','WC',0,1,0,0,174,'Western Cape','Western Cape'),
	(188,'Aberdeenshire','AB',0,1,0,0,201,'Aberdeenshire','Aberdeenshire'),
	(189,'Angus','AG',0,1,0,0,201,'Angus','Angus'),
	(190,'Argyll','AR',0,1,0,0,201,'Argyll','Argyll'),
	(191,'Avon','AV',0,1,0,0,201,'Avon','Avon'),
	(192,'Ayrshire','AY',0,1,0,0,201,'Ayrshire','Ayrshire'),
	(193,'Banffshire','BF',0,1,0,0,201,'Banffshire','Banffshire'),
	(194,'Bedfordshire','Beds',0,1,0,0,201,'Bedfordshire','Bedfordshire'),
	(195,'Berkshire','Berks',0,1,0,0,201,'Berkshire','Berkshire'),
	(196,'Buckinghamshire','Bucks',0,1,0,0,201,'Buckinghamshire','Buckinghamshire'),
	(197,'Caithness','CN',0,1,0,0,201,'Caithness','Caithness'),
	(198,'Cambridgeshire','Cambs',0,1,0,0,201,'Cambridgeshire','Cambridgeshire'),
	(199,'Ceredigion','CE',0,1,0,0,201,'Ceredigion','Ceredigion'),
	(200,'Cheshire','CH',0,1,0,0,201,'Cheshire','Cheshire'),
	(201,'Clackmannanshire','CL',0,1,0,0,201,'Clackmannanshire','Clackmannanshire'),
	(202,'Cleveland','CV',0,1,0,0,201,'Cleveland','Cleveland'),
	(203,'Clwyd','CW',0,1,0,0,201,'Clwyd','Clwyd'),
	(204,'County Antrim','Co Antrim',0,1,0,0,201,'County Antrim','County Antrim'),
	(205,'County Armagh','Co Armagh',0,1,0,0,201,'County Armagh','County Armagh'),
	(206,'County Down','Co Down',0,1,0,0,201,'County Down','County Down'),
	(207,'Durham','Co Durham',0,1,0,0,201,'County Durham','County Durham'),
	(208,'County Fermanagh','Co Fermanagh',0,1,0,0,201,'County Fermanagh','County Fermanagh'),
	(209,'County Londonderry','Co Londonderry',0,1,0,0,201,'County Londonderry','County Londonderry'),
	(210,'County Tyrone','Co Tyrone',0,1,0,0,201,'County Tyrone','County Tyrone'),
	(211,'Cornwall','CO',0,1,0,0,201,'Cornwall','Cornwall'),
	(212,'Cumbria','CU',0,1,0,0,201,'Cumbria','Cumbria'),
	(213,'Derbyshire','DB',0,1,0,0,201,'Derbyshire','Derbyshire'),
	(214,'Devon','DV',0,1,0,0,201,'Devon','Devon'),
	(215,'Dorset','DO',0,1,0,0,201,'Dorset','Dorset'),
	(216,'Dumfriesshire','DF',0,1,0,0,201,'Dumfriesshire','Dumfriesshire'),
	(217,'Dunbartonshire','DU',0,1,0,0,201,'Dunbartonshire','Dunbartonshire'),
	(218,'Dyfed','DY',0,1,0,0,201,'Dyfed','Dyfed'),
	(219,'East Lothian','EL',0,1,0,0,201,'East Lothian','East Lothian'),
	(220,'East Sussex','E Sussex',0,1,0,0,201,'East Sussex','East Sussex'),
	(221,'Essex','EX',0,1,0,0,201,'Essex','Essex'),
	(222,'Fife','FI',0,1,0,0,201,'Fife','Fife'),
	(223,'Gloucestershire','Glos',0,1,0,0,201,'Gloucestershire','Gloucestershire'),
	(224,'Gwent','GW',0,1,0,0,201,'Gwent','Gwent'),
	(225,'Gwynedd','GY',0,1,0,0,201,'Gwynedd','Gwynedd'),
	(226,'Hampshire','Hants',0,1,0,0,201,'Hampshire','Hampshire'),
	(227,'Herefordshire','HE',0,1,0,0,201,'Herefordshire','Herefordshire'),
	(228,'Hertfordshire','Herts',0,1,0,0,201,'Hertfordshire','Hertfordshire'),
	(229,'Inverness-shire','IS',0,1,0,0,201,'Inverness-shire','Inverness-shire'),
	(230,'Isle of Mull','IsMu',0,1,0,0,201,'Isle of Mull','Isle of Mull'),
	(231,'Shetland','IsSh',0,1,0,0,201,'Isle of Shetland','Isle of Shetland'),
	(232,'Isle of Skye','IsSk',0,1,0,0,201,'Isle of Skye','Isle of Skye'),
	(233,'Isle of Wight','IsWi',0,1,0,0,201,'Isle of Wight','Isle of Wight'),
	(234,'Isles of Scilly','IsSc',0,1,0,0,201,'Isles of Scilly','Isles of Scilly'),
	(235,'Kent','KE',0,1,0,0,201,'Kent','Kent'),
	(236,'Kincardineshire','KI',0,1,0,0,201,'Kincardineshire','Kincardineshire'),
	(237,'Kinross-shire','KR',0,1,0,0,201,'Kinross-shire','Kinross-shire'),
	(238,'Kirkcudbrightshire','KK',0,1,0,0,201,'Kirkudbrightshire','Kirkudbrightshire'),
	(239,'Lanarkshire','LK',0,1,0,0,201,'Lanarkshire','Lanarkshire'),
	(240,'Lancashire','Lancs',0,1,0,0,201,'Lancashire','Lancashire'),
	(241,'Leicestershire','Leics',0,1,0,0,201,'Leicestershire','Leicestershire'),
	(242,'Lincolnshire','Lincs',0,1,0,0,201,'Lincolnshire','Lincolnshire'),
	(243,'London','LO',0,1,0,0,201,'London','London'),
	(244,'Merseyside','ME',0,1,0,0,201,'Merseyside','Merseyside'),
	(245,'Mid Glamorgan','M Glam',0,1,0,0,201,'Mid Glamorgan','Mid Glamorgan'),
	(246,'Midlothian','MI',0,1,0,0,201,'Midlothian','Midlothian'),
	(247,'Middlesex','Middx',0,1,0,0,201,'Middlesex','Middlesex'),
	(248,'Moray','MO',0,1,0,0,201,'Morayshire','Morayshire'),
	(249,'Nairnshire','NA',0,1,0,0,201,'Nairnshire','Nairnshire'),
	(250,'Norfolk','NO',0,1,0,0,201,'Norfolk','Norfolk'),
	(251,'North Humberside','N Humberside',0,1,0,0,201,'North Humberside','North Humberside'),
	(252,'North Yorkshire','N Yorkshire',0,1,0,0,201,'North Yorkshire','North Yorkshire'),
	(253,'Northamptonshire','Northants',0,1,0,0,201,'Northamptonshire','Northamptonshire'),
	(254,'Northumberland','Northd',0,1,0,0,201,'Northumberland','Northumberland'),
	(255,'Nottinghamshire','Notts',0,1,0,0,201,'Nottinghamshire','Nottinghamshire'),
	(256,'Oxfordshire','Oxon',0,1,0,0,201,'Oxfordshire','Oxfordshire'),
	(257,'Peebleshire','PE',0,1,0,0,201,'Peebleshire','Peebleshire'),
	(258,'Perthshire','PR',0,1,0,0,201,'Perthshire','Perthshire'),
	(259,'Powys','PO',0,1,0,0,201,'Powys','Powys'),
	(260,'Renfrewshire','RE',0,1,0,0,201,'Renfrewshire','Renfrewshire'),
	(261,'Ross-shire','RO',0,1,0,0,201,'Ross-shire','Ross-shire'),
	(262,'Roxburghshire','RX',0,1,0,0,201,'Roxburghshire','Roxburghshire'),
	(263,'Selkirkshire','SK',0,1,0,0,201,'Selkirkshire','Selkirkshire'),
	(264,'Shropshire','SR',0,1,0,0,201,'Shropshire','Shropshire'),
	(265,'Somerset','SO',0,1,0,0,201,'Somerset','Somerset'),
	(266,'South Glamorgan','S Glam',0,1,0,0,201,'South Glamorgan','South Glamorgan'),
	(267,'South Humberside','S Humberside',0,1,0,0,201,'South Humberside','South Humberside'),
	(268,'South Yorkshire','S Yorkshire',0,1,0,0,201,'South Yorkshire','South Yorkshire'),
	(269,'Staffordshire','Staffs',0,1,0,0,201,'Staffordshire','Staffordshire'),
	(270,'Stirlingshire','SS',0,1,0,0,201,'Stirlingshire','Stirlingshire'),
	(271,'Suffolk','SF',0,1,0,0,201,'Suffolk','Suffolk'),
	(272,'Surrey','SY',0,1,0,0,201,'Surrey','Surrey'),
	(273,'Sutherland','SU',0,1,0,0,201,'Sutherland','Sutherland'),
	(274,'Tyne and Wear','Tyne & Wear',0,1,0,0,201,'Tyne and Wear','Tyne and Wear'),
	(275,'Warwickshire','Warks',0,1,0,0,201,'Warwickshire','Warwickshire'),
	(276,'West Glamorgan','W Glam',0,1,0,0,201,'West Glamorgan','West Glamorgan'),
	(277,'West Lothian','WL',0,1,0,0,201,'West Lothian','West Lothian'),
	(278,'West Midlands','W Midlands',0,1,0,0,201,'West Midlands','West Midlands'),
	(279,'West Sussex','W Sussex',0,1,0,0,201,'West Sussex','West Sussex'),
	(280,'West Yorkshire','W Yorkshire',0,1,0,0,201,'West Yorkshire','West Yorkshire'),
	(281,'Wigtownshire','WT',0,1,0,0,201,'Wigtownshire','Wigtownshire'),
	(282,'Wiltshire','Wilts',0,1,0,0,201,'Wiltshire','Wiltshire'),
	(283,'Worcestershire','Worcs',0,1,0,0,201,'Worcestershire','Worcestershire'),
	(284,'Yorkshire','EY',0,1,0,0,201,'East Yorkshire','East Yorkshire'),
	(285,'Carmarthenshire','CS',0,1,0,0,201,'Carmarthenshire','Carmarthenshire'),
	(286,'Berwickshire','BS',0,1,0,0,201,'Berwickshire','Berwickshire'),
	(287,'Anglesey','AN',0,1,0,0,201,'Anglesey','Anglesey'),
	(288,'Pembrokeshire','PK',0,1,0,0,201,'Pembrokeshire','Pembrokeshire'),
	(289,'Flintshire','FS',0,1,0,0,201,'Flintshire','Flintshire'),
	(290,'Rutland','RD',0,1,0,0,201,'Rutland','Rutland'),
	(291,'Glamorgan','AA',0,1,0,0,201,'Glamorgan','Glamorgan'),
	(292,'Cardiff','AA',0,1,0,0,201,'Cardiff','Cardiff'),
	(293,'Bristol','AA',0,1,0,0,201,'Bristol','Bristol'),
	(294,'Manchester','AA',0,1,0,0,201,'Manchester','Manchester'),
	(295,'Birmingham','AA',0,1,0,0,201,'Birmingham','Birmingham'),
	(296,'Glasgow','AA',0,1,0,0,201,'Glasgow','Glasgow'),
	(297,'Edinburgh','AA',0,1,0,0,201,'Edinburgh','Edinburgh'),
	(298,'BFPO','FO',0,0,0,0,201,'BFPO','BFPO'),
	(299,'APO/FPO','AO',0,0,0,0,201,'APO/FPO','APO/FPO'),
	(300,'Bornholm','BH',0,1,0,0,50,'Bornholm','Bornholm'),
	(301,'Falster','FA',0,1,0,0,50,'Falster','Falster'),
	(302,'Fyn','FY',0,1,0,0,50,'Fyn','Fyn'),
	(303,'Jylland','JY',0,1,0,0,50,'Jylland','Jylland'),
	(304,'Sjaelland','SJ',0,1,0,0,50,'Sjaelland','Sjaelland'),
	(305,'Ain','01',0,1,0,0,65,'Ain','Ain'),
	(306,'Aisne','02',0,1,0,0,65,'Aisne','Aisne'),
	(307,'Allier','03',0,1,0,0,65,'Allier','Allier'),
	(308,'Alpes de Haute Provence','04',0,1,0,0,65,'Alpes de Haute Provence','Alpes de Haute Provence'),
	(309,'Hautes Alpes','05',0,1,0,0,65,'Hautes Alpes','Hautes Alpes'),
	(310,'Alpes Maritimes','06',0,1,0,0,65,'Alpes Maritimes','Alpes Maritimes'),
	(311,'Ard&egrave;che','07',0,1,0,0,65,'Ard&egrave;che','Ard&egrave;che'),
	(312,'Ardennes','08',0,1,0,0,65,'Ardennes','Ardennes'),
	(313,'Ari&egrave;ge','09',0,1,0,0,65,'Ari&egrave;ge','Ari&egrave;ge'),
	(314,'Aube','10',0,1,0,0,65,'Aube','Aube'),
	(315,'Aude','11',0,1,0,0,65,'Aude','Aude'),
	(316,'Averyon','12',0,1,0,0,65,'Averyon','Averyon'),
	(317,'Bouche du Rh&ocirc;ne','13',0,1,0,0,65,'Bouche du Rh&ocirc;ne','Bouche du Rh&ocirc;ne'),
	(318,'Calvados','14',0,1,0,0,65,'Calvados','Calvados'),
	(319,'Cantal','15',0,1,0,0,65,'Cantal','Cantal'),
	(320,'Charente','16',0,1,0,0,65,'Charente','Charente'),
	(321,'Charente Maritime','17',0,1,0,0,65,'Charente Maritime','Charente Maritime'),
	(322,'Cher','18',0,1,0,0,65,'Cher','Cher'),
	(323,'Corr&egrave;ze','19',0,1,0,0,65,'Corr&egrave;ze','Corr&egrave;ze'),
	(324,'Corse du Sud','2a',0,1,0,0,65,'Corse du Sud','Corse du Sud'),
	(325,'Haute Corse','2b',0,1,0,0,65,'Haute Corse','Haute Corse'),
	(326,'C&ocirc;te d\'Or','21',0,1,0,0,65,'C&ocirc;te d\'Or','C&ocirc;te d\'Or'),
	(327,'C&ocirc;tes d\'Armor','22',0,1,0,0,65,'C&ocirc;tes d\'Armor','C&ocirc;tes d\'Armor'),
	(328,'Creuse','23',0,1,0,0,65,'Creuse','Creuse'),
	(329,'Dordogne','24',0,1,0,0,65,'Dordogne','Dordogne'),
	(330,'Doubs','25',0,1,0,0,65,'Doubs','Doubs'),
	(331,'Dr&ocirc;me','26',0,1,0,0,65,'Dr&ocirc;me','Dr&ocirc;me'),
	(332,'Eure','27',0,1,0,0,65,'Eure','Eure'),
	(333,'Eure et Loire','28',0,1,0,0,65,'Eure et Loire','Eure et Loire'),
	(334,'Finist&egrave;re','29',0,1,0,0,65,'Finist&egrave;re','Finist&egrave;re'),
	(335,'Gard','30',0,1,0,0,65,'Gard','Gard'),
	(336,'Haute Garonne','31',0,1,0,0,65,'Haute Garonne','Haute Garonne'),
	(337,'Gers','32',0,1,0,0,65,'Gers','Gers'),
	(338,'Gironde','33',0,1,0,0,65,'Gironde','Gironde'),
	(339,'Herault','34',0,1,0,0,65,'Herault','Herault'),
	(340,'Ille et Vilaine','35',0,1,0,0,65,'Ille et Vilaine','Ille et Vilaine'),
	(341,'Indre','36',0,1,0,0,65,'Indre','Indre'),
	(342,'Indre et Loire','37',0,1,0,0,65,'Indre et Loire','Indre et Loire'),
	(343,'Is&egrave;re','38',0,1,0,0,65,'Is&egrave;re','Is&egrave;re'),
	(344,'Jura','39',0,1,0,0,65,'Jura','Jura'),
	(345,'Landes','40',0,1,0,0,65,'Landes','Landes'),
	(346,'Loir et Cher','41',0,1,0,0,65,'Loir et Cher','Loir et Cher'),
	(347,'Loire','42',0,1,0,0,65,'Loire','Loire'),
	(348,'Haute Loire','43',0,1,0,0,65,'Haute Loire','Haute Loire'),
	(349,'Loire Atlantique','44',0,1,0,0,65,'Loire Atlantique','Loire Atlantique'),
	(350,'Loiret','45',0,1,0,0,65,'Loiret','Loiret'),
	(351,'Lot','46',0,1,0,0,65,'Lot','Lot'),
	(352,'Lot et Garonne','47',0,1,0,0,65,'Lot et Garonne','Lot et Garonne'),
	(353,'Loz&egrave;re','48',0,1,0,0,65,'Loz&egrave;re','Loz&egrave;re'),
	(354,'Maine et Loire','49',0,1,0,0,65,'Maine et Loire','Maine et Loire'),
	(355,'Manche','50',0,1,0,0,65,'Manche','Manche'),
	(356,'Marne','51',0,1,0,0,65,'Marne','Marne'),
	(357,'Haute Marne','52',0,1,0,0,65,'Haute Marne','Haute Marne'),
	(358,'Mayenne','53',0,1,0,0,65,'Mayenne','Mayenne'),
	(359,'Meurthe et Moselle','54',0,1,0,0,65,'Meurthe et Moselle','Meurthe et Moselle'),
	(360,'Meuse','55',0,1,0,0,65,'Meuse','Meuse'),
	(361,'Morbihan','56',0,1,0,0,65,'Morbihan','Morbihan'),
	(362,'Moselle','57',0,1,0,0,65,'Moselle','Moselle'),
	(363,'Ni&egrave;vre','58',0,1,0,0,65,'Ni&egrave;vre','Ni&egrave;vre'),
	(364,'Nord','59',0,1,0,0,65,'Nord','Nord'),
	(365,'Oise','60',0,1,0,0,65,'Oise','Oise'),
	(366,'Orne','61',0,1,0,0,65,'Orne','Orne'),
	(367,'Pas de Calais','62',0,1,0,0,65,'Pas de Calais','Pas de Calais'),
	(368,'Puy de D&ocirc;me','63',0,1,0,0,65,'Puy de D&ocirc;me','Puy de D&ocirc;me'),
	(369,'Pyren&eacute;es Atlantiques','64',0,1,0,0,65,'Pyren&eacute;es Atlantiques','Pyren&eacute;es Atlantiques'),
	(370,'Haute Pyren&eacute;es','65',0,1,0,0,65,'Haute Pyren&eacute;es','Haute Pyren&eacute;es'),
	(371,'Pyren&eacute;es orientales','66',0,1,0,0,65,'Pyren&eacute;es orientales','Pyren&eacute;es orientales'),
	(372,'Bas Rhin','67',0,1,0,0,65,'Bas Rhin','Bas Rhin'),
	(373,'Haut Rhin','68',0,1,0,0,65,'Haut Rhin','Haut Rhin'),
	(374,'Rh&ocirc;ne','69',0,1,0,0,65,'Rh&ocirc;ne','Rh&ocirc;ne'),
	(375,'Haute Sa&ocirc;ne','70',0,1,0,0,65,'Haute Sa&ocirc;ne','Haute Sa&ocirc;ne'),
	(376,'Sa&ocirc;ne et Loire','71',0,1,0,0,65,'Sa&ocirc;ne et Loire','Sa&ocirc;ne et Loire'),
	(377,'Sarthe','72',0,1,0,0,65,'Sarthe','Sarthe'),
	(378,'Savoie','73',0,1,0,0,65,'Savoie','Savoie'),
	(379,'Haute Savoie','74',0,1,0,0,65,'Haute Savoie','Haute Savoie'),
	(380,'Paris','75',0,1,0,0,65,'Paris','Paris'),
	(381,'Seine Maritime','76',0,1,0,0,65,'Seine Maritime','Seine Maritime'),
	(382,'Seine et Marne','77',0,1,0,0,65,'Seine et Marne','Seine et Marne'),
	(383,'Yvelines','78',0,1,0,0,65,'Yvelines','Yvelines'),
	(384,'Deux S&egrave;vres','79',0,1,0,0,65,'Deux S&egrave;vres','Deux S&egrave;vres'),
	(385,'Somme','80',0,1,0,0,65,'Somme','Somme'),
	(386,'Tarn','81',0,1,0,0,65,'Tarn','Tarn'),
	(387,'Tarn et Garonne','82',0,1,0,0,65,'Tarn et Garonne','Tarn et Garonne'),
	(388,'Var','83',0,1,0,0,65,'Var','Var'),
	(389,'Vaucluse','84',0,1,0,0,65,'Vaucluse','Vaucluse'),
	(390,'Vend&eacute;e','85',0,1,0,0,65,'Vend&eacute;e','Vend&eacute;e'),
	(391,'Vienne','86',0,1,0,0,65,'Vienne','Vienne'),
	(392,'Haute Vienne','87',0,1,0,0,65,'Haute Vienne','Haute Vienne'),
	(393,'Vosges','88',0,1,0,0,65,'Vosges','Vosges'),
	(394,'Yonne','89',0,1,0,0,65,'Yonne','Yonne'),
	(395,'Territoire de Belfort','90',0,1,0,0,65,'Territoire de Belfort','Territoire de Belfort'),
	(396,'Essonne','91',0,1,0,0,65,'Essonne','Essonne'),
	(397,'Hauts de Seine','92',0,1,0,0,65,'Hauts de Seine','Hauts de Seine'),
	(398,'Seine Saint Denis','93',0,1,0,0,65,'Seine Saint Denis','Seine Saint Denis'),
	(399,'Val de Marne','94',0,1,0,0,65,'Val de Marne','Val de Marne'),
	(400,'Val d\'Oise','95',0,1,0,0,65,'Val d\'Oise','Val d\'Oise'),
	(401,'Baden-W&uuml;rttenberg','01',0,1,0,0,71,'Baden-W&uuml;rttenberg','Baden-W&uuml;rttenberg'),
	(402,'Bayern','02',0,1,0,0,71,'Bayern','Bayern'),
	(403,'Berlin','03',0,1,0,0,71,'Berlin','Berlin'),
	(404,'Brandenburg','04',0,1,0,0,71,'Brandenburg','Brandenburg'),
	(405,'Bremen','05',0,1,0,0,71,'Bremen','Bremen'),
	(406,'Hamburg','06',0,1,0,0,71,'Hamburg','Hamburg'),
	(407,'Hessen','07',0,1,0,0,71,'Hessen','Hessen'),
	(408,'Mecklenburg-Vorpommern','08',0,1,0,0,71,'Mecklenburg-Vorpommern','Mecklenburg-Vorpommern'),
	(409,'Niedersachsen','09',0,1,0,0,71,'Niedersachsen','Niedersachsen'),
	(410,'Nordrhein-Westfalen','10',0,1,0,0,71,'Nordrhein-Westfalen','Nordrhein-Westfalen'),
	(411,'Rheinland-Pfalz','11',0,1,0,0,71,'Rheinland-Pfalz','Rheinland-Pfalz'),
	(412,'Saarland','12',0,1,0,0,71,'Saarland','Saarland'),
	(413,'Sachsen','13',0,1,0,0,71,'Sachsen','Sachsen'),
	(414,'Sachsen Anhalt','14',0,1,0,0,71,'Sachsen Anhalt','Sachsen Anhalt'),
	(415,'Schleswig Holstein','15',0,1,0,0,71,'Schleswig Holstein','Schleswig Holstein'),
	(416,'Th&uuml;ringen','16',0,1,0,0,71,'Th&uuml;ringen','Th&uuml;ringen'),
	(417,'Aargau','AG',0,1,0,0,183,'Aargau','Aargau'),
	(418,'Appenzell Innerrhoden','AI',0,1,0,0,183,'Appenzell Innerrhoden','Appenzell Innerrhoden'),
	(419,'Appenzell Ausserrhoden','AR',0,1,0,0,183,'Appenzell Ausserrhoden','Appenzell Ausserrhoden'),
	(420,'Basel-Stadt','BS',0,1,0,0,183,'Basel-Stadt','Basel-Stadt'),
	(421,'Basel-Landschaft','BL',0,1,0,0,183,'Basel-Landschaft','Basel-Landschaft'),
	(422,'Bern','BE',0,1,0,0,183,'Bern','Bern'),
	(423,'Freiburg','FR',0,1,0,0,183,'Freiburg','Freiburg'),
	(424,'Genf','GE',0,1,0,0,183,'Genf','Genf'),
	(425,'Glarus','GL',0,1,0,0,183,'Glarus','Glarus'),
	(426,'Graub&uuml;nden','GR',0,1,0,0,183,'Graub&uuml;nden','Graub&uuml;nden'),
	(427,'Jura','JU',0,1,0,0,183,'Jura','Jura'),
	(428,'Luzern','LU',0,1,0,0,183,'Luzern','Luzern'),
	(429,'Neuenburg','NE',0,1,0,0,183,'Neuenburg','Neuenburg'),
	(430,'Nidwalden','NW',0,1,0,0,183,'Nidwalden','Nidwalden'),
	(431,'Obwalden','OW',0,1,0,0,183,'Obwalden','Obwalden'),
	(432,'Schaffhausen','SH',0,1,0,0,183,'Schaffhausen','Schaffhausen'),
	(433,'Schwyz','SZ',0,1,0,0,183,'Schwyz','Schwyz'),
	(434,'Solothurn','SO',0,1,0,0,183,'Solothurn','Solothurn'),
	(435,'St. Gallen','SG',0,1,0,0,183,'St. Gallen','St. Gallen'),
	(436,'Thurgau','TG',0,1,0,0,183,'Thurgau','Thurgau'),
	(437,'Tessin','TI',0,1,0,0,183,'Tessin','Tessin'),
	(438,'Uri','UR',0,1,0,0,183,'Uri','Uri'),
	(439,'Wallis','VS',0,1,0,0,183,'Wallis','Wallis'),
	(440,'Waadt','VD',0,1,0,0,183,'Waadt','Waadt'),
	(441,'Zug','ZG',0,1,0,0,183,'Zug','Zug'),
	(442,'Z&uuml;rich','ZH',0,1,0,0,183,'Z&uuml;rich','Z&uuml;rich'),
	(443,'Abruzzo','AL',0,1,0,0,93,'Abruzzo','Abruzzo'),
	(444,'Basilicata','AK',0,1,0,0,93,'Basilicata','Basilicata'),
	(445,'Calabria','AS',0,1,0,0,93,'Calabria','Calabria'),
	(446,'Campania','AZ',0,1,0,0,93,'Campania','Campania'),
	(447,'Emilia Romagna','AR',0,1,0,0,93,'Emilia Romagna','Emilia Romagna'),
	(448,'Friuli Venezia Giulia','CA',0,1,0,0,93,'Friuli Venezia Giulia','Friuli Venezia Giulia'),
	(449,'Lazio','CO',0,1,0,0,93,'Lazio','Lazio'),
	(450,'Liguria','CT',0,1,0,0,93,'Liguria','Liguria'),
	(451,'Lombardia','DE',0,1,0,0,93,'Lombardia','Lombardia'),
	(452,'Marche','DC',0,1,0,0,93,'Marche','Marche'),
	(453,'Piemonte','FM',0,1,0,0,93,'Piemonte','Piemonte'),
	(454,'Puglia','FL',0,1,0,0,93,'Puglia','Puglia'),
	(455,'Sardegna','GA',0,1,0,0,93,'Sardegna','Sardegna'),
	(456,'Sicilia','GU',0,1,0,0,93,'Sicilia','Sicilia'),
	(457,'Toscana','HI',0,1,0,0,93,'Toscana','Toscana'),
	(458,'Trentino Alto Adige','ID',0,1,0,0,93,'Trentino Alto Adige','Trentino Alto Adige'),
	(459,'Umbria','IL',0,1,0,0,93,'Umbria','Umbria'),
	(460,'Valle d\'Aosta','IN',0,1,0,0,93,'Valle d\'Aosta','Valle d\'Aosta'),
	(461,'Veneto','IA',0,1,0,0,93,'Veneto','Veneto'),
	(462,'Aveiro','AB',0,1,0,0,153,'Aveiro','Aveiro'),
	(463,'Beja','AG',0,1,0,0,153,'Beja','Beja'),
	(464,'Braga','AR',0,1,0,0,153,'Braga','Braga'),
	(465,'Braganca','AV',0,1,0,0,153,'Braganca','Braganca'),
	(466,'Castelo Branco','AY',0,1,0,0,153,'Castelo Branco','Castelo Branco'),
	(467,'Coimbra','BF',0,1,0,0,153,'Coimbra','Coimbra'),
	(468,'Evora','BE',0,1,0,0,153,'Evora','Evora'),
	(469,'Faro','BK',0,1,0,0,153,'Faro','Faro'),
	(470,'Guarda','BU',0,1,0,0,153,'Guarda','Guarda'),
	(471,'Leiria','CN',0,1,0,0,153,'Leiria','Leiria'),
	(472,'Lisboa','CB',0,1,0,0,153,'Lisboa','Lisboa'),
	(473,'Portalegre','CH',0,1,0,0,153,'Portalegre','Portalegre'),
	(474,'Porto','CL',0,1,0,0,153,'Porto','Porto'),
	(475,'Santarem','CV',0,1,0,0,153,'Santarem','Santarem'),
	(476,'Setubal','CW',0,1,0,0,153,'Setubal','Setubal'),
	(477,'Viana do Castelo','CAn',0,1,0,0,153,'Viana do Castelo','Viana do Castelo'),
	(478,'Vila Real','CL',0,1,0,0,153,'Vila Real','Vila Real'),
	(479,'Viseu','CL',0,1,0,0,153,'Viseu','Viseu'),
	(480,'Madeira','MA',0,1,0,0,153,'Madeira','Madeira'),
	(481,'A&ccedil;ores','AC',0,1,0,0,153,'A&ccedil;ores','A&ccedil;ores'),
	(482,'Alava','VI',0,1,0,0,175,'Alava','Alava'),
	(483,'Albacete','AB',0,1,0,0,175,'Albacete','Albacete'),
	(484,'Alicante','A',0,1,0,0,175,'Alicante','Alicante'),
	(485,'Almer&iacute;a','AL',0,1,0,0,175,'Almer&iacute;a','Almer&iacute;a'),
	(486,'Asturias','O',0,1,0,0,175,'Asturias','Asturias'),
	(487,'Avila','AV',0,1,0,0,175,'Avila','Avila'),
	(488,'Badajoz','BA',0,1,0,0,175,'Badajoz','Badajoz'),
	(489,'Barcelona','B',0,1,0,0,175,'Barcelona','Barcelona'),
	(490,'Burgos','BU',0,1,0,0,175,'Burgos','Burgos'),
	(491,'C&aacute;ceres','CC',0,1,0,0,175,'C&aacute;ceres','C&aacute;ceres'),
	(492,'C&aacute;diz','CA',0,1,0,0,175,'C&aacute;diz','C&aacute;diz'),
	(493,'Cantabria','S',0,1,0,0,175,'Cantabria','Cantabria'),
	(494,'Castell&oacute;n','CS',0,1,0,0,175,'Castell&oacute;n','Castell&oacute;n'),
	(495,'Ceuta','CE',0,1,0,0,175,'Ceuta','Ceuta'),
	(496,'Ciudad Real','CR',0,1,0,0,175,'Ciudad Real','Ciudad Real'),
	(497,'C&oacute;rdoba','CO',0,1,0,0,175,'C&oacute;rdoba','C&oacute;rdoba'),
	(498,'Cuenca','CU',0,1,0,0,175,'Cuenca','Cuenca'),
	(499,'Guip&uacute;zcoa','SS',0,1,0,0,175,'Guip&uacute;zcoa','Guip&uacute;zcoa'),
	(500,'Girona','GI',0,1,0,0,175,'Girona','Girona'),
	(501,'Granada','GR',0,1,0,0,175,'Granada','Granada'),
	(502,'Guadalajara','GU',0,1,0,0,175,'Guadalajara','Guadalajara'),
	(503,'Huelva','H',0,1,0,0,175,'Huelva','Huelva'),
	(504,'Huesca','HU',0,1,0,0,175,'Huesca','Huesca'),
	(505,'Islas Baleares','IB',0,1,0,0,175,'Islas Baleares','Islas Baleares'),
	(506,'Ja&eacute;n','J',0,1,0,0,175,'Ja&eacute;n','Ja&eacute;n'),
	(507,'La Coru&ntilde;a','C',0,1,0,0,175,'La Coru&ntilde;a','La Coru&ntilde;a'),
	(508,'La Rioja','LO',0,1,0,0,175,'La Rioja','La Rioja'),
	(509,'Las Palmas','GC',0,1,0,0,175,'Las Palmas','Las Palmas'),
	(510,'Le&oacute;n','LE',0,1,0,0,175,'Le&oacute;n','Le&oacute;n'),
	(511,'L&eacute;rida','LL',0,1,0,0,175,'L&eacute;rida','L&eacute;rida'),
	(512,'Lugo','LU',0,1,0,0,175,'Lugo','Lugo'),
	(513,'Madrid','M',0,1,0,0,175,'Madrid','Madrid'),
	(514,'M&aacute;laga','MA',0,1,0,0,175,'M&aacute;laga','M&aacute;laga'),
	(515,'Melilla','ML',0,1,0,0,175,'Melilla','Melilla'),
	(516,'Murcia','MU',0,1,0,0,175,'Murcia','Murcia'),
	(517,'Navarra','NA',0,1,0,0,175,'Navarra','Navarra'),
	(518,'Orense','OR',0,1,0,0,175,'Orense','Orense'),
	(519,'Palencia','P',0,1,0,0,175,'Palencia','Palencia'),
	(520,'Pontevedra','PO',0,1,0,0,175,'Pontevedra','Pontevedra'),
	(521,'Salamanca','SA',0,1,0,0,175,'Salamanca','Salamanca'),
	(522,'Tenerife','TF',0,1,0,0,175,'Tenerife','Tenerife'),
	(523,'Segovia','SG',0,1,0,0,175,'Segovia','Segovia'),
	(524,'Sevilla','SE',0,1,0,0,175,'Sevilla','Sevilla'),
	(525,'Soria','SO',0,1,0,0,175,'Soria','Soria'),
	(526,'Tarragona','T',0,1,0,0,175,'Tarragona','Tarragona'),
	(527,'Teruel','TE',0,1,0,0,175,'Teruel','Teruel'),
	(528,'Toledo','TO',0,1,0,0,175,'Toledo','Toledo'),
	(529,'Valencia','V',0,1,0,0,175,'Valencia','Valencia'),
	(530,'Valladolid','VA',0,1,0,0,175,'Valladolid','Valladolid'),
	(531,'Vizcaya','BI',0,1,0,0,175,'Vizcaya','Vizcaya'),
	(532,'Zamora','ZA',0,1,0,0,175,'Zamora','Zamora'),
	(533,'Zaragoza','Z',0,1,0,0,175,'Zaragoza','Zaragoza'),
	(534,'Orkney','ORK',0,1,0,0,201,'',''),
	(535,'Denbighshire','DEN',0,1,0,0,201,'',''),
	(536,'Monmouthshire','MON',0,1,0,0,201,'',''),
	(537,'Rhondda Cynon Taff','RON',0,1,0,0,201,'',''),
	(538,'Channel Islands','CHI',0,0,0,0,201,'',''),
	(539,'Isle of Man','ISM',0,0,0,0,201,'','');

/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tmplogin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tmplogin`;

CREATE TABLE `tmplogin` (
  `tmploginid` varchar(100) NOT NULL,
  `tmploginname` varchar(50) DEFAULT NULL,
  `tmplogindate` datetime DEFAULT NULL,
  `tmploginchk` double DEFAULT '0',
  PRIMARY KEY (`tmploginid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table uspsmethods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uspsmethods`;

CREATE TABLE `uspsmethods` (
  `uspsID` int(11) NOT NULL,
  `uspsMethod` varchar(150) NOT NULL,
  `uspsShowAs` varchar(150) NOT NULL,
  `uspsUseMethod` tinyint(4) DEFAULT '0',
  `uspsFSA` tinyint(4) DEFAULT '0',
  `uspsLocal` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`uspsID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `uspsmethods` WRITE;
/*!40000 ALTER TABLE `uspsmethods` DISABLE KEYS */;

INSERT INTO `uspsmethods` (`uspsID`, `uspsMethod`, `uspsShowAs`, `uspsUseMethod`, `uspsFSA`, `uspsLocal`)
VALUES
	(1,'EXPRESS','Express Mail',0,0,1),
	(2,'PRIORITY','Priority Mail',0,0,1),
	(3,'PARCEL','Parcel Post',1,1,1),
	(14,'Media','Media Mail',0,0,1),
	(15,'BPM','Bound Printed Matter',0,0,1),
	(16,'FIRST CLASS','First-Class Mail',0,0,1),
	(30,'4','Global Express Guaranteed',0,0,0),
	(31,'6','Global Express Guaranteed',0,0,0),
	(32,'7','Global Express Guaranteed',0,0,0),
	(33,'1','Express Mail International',0,0,0),
	(34,'10','Express Mail International',0,0,0),
	(35,'2','Priority Mail International',0,0,0),
	(36,'8','Priority Mail International',0,0,0),
	(37,'9','Priority Mail International',0,0,0),
	(38,'13','First-Class Mail',1,0,0),
	(39,'14','First-Class Mail',0,0,0),
	(40,'15','First-Class Mail',0,0,0),
	(41,'11','Priority Mail International',0,0,0),
	(42,'16','Priority Mail International',0,0,0),
	(43,'17','Express Mail International',0,0,0),
	(44,'20','Priority Mail International',0,0,0),
	(45,'24','Priority Mail International',0,0,0),
	(46,'26','Express Mail International',0,0,0),
	(101,'01','UPS Next Day Air&reg;',1,0,1),
	(102,'02','UPS 2nd Day Air&reg;',1,0,1),
	(103,'03','UPS Ground',1,1,1),
	(104,'07','UPS Worldwide Express',1,0,1),
	(105,'08','UPS Worldwide Expedited',1,0,1),
	(106,'11','UPS Standard',1,0,1),
	(107,'12','UPS 3 Day Select&reg;',1,0,1),
	(108,'13','UPS Next Day Air Saver&reg;',1,0,1),
	(109,'14','UPS Next Day Air&reg; Early A.M.&reg;',1,0,1),
	(110,'54','UPS Worldwide Express Plus',1,0,1),
	(111,'59','UPS 2nd Day Air A.M.&reg;',1,0,1),
	(112,'65','UPS Express Saver',1,0,1),
	(201,'DOM.RP','Regular Parcel',1,1,1),
	(202,'DOM.EP','Expedited Parcel',1,0,1),
	(203,'DOM.XP','Xpresspost',1,0,1),
	(204,'DOM.XP.CERT','Xpresspost Certified',1,0,1),
	(205,'DOM.PC','Priority',1,0,1),
	(206,'DOM.LIB','Library Books',1,0,1),
	(207,'USA.EP','Expedited Parcel USA',1,0,1),
	(208,'USA.PW.ENV','Priority Worldwide Envelope USA',1,0,1),
	(210,'USA.PW.PAK','Priority Worldwide pak USA',1,0,0),
	(211,'USA.PW.PARCEL','Priority Worldwide Parcel USA',1,0,0),
	(212,'USA.SP.AIR','Small Packet USA Air',1,0,0),
	(213,'USA.SP.SURF','Small Packet USA Surface',1,0,0),
	(214,'USA.XP','Xpresspost USA',1,0,0),
	(215,'INT.XP','Xpresspost International',1,0,0),
	(216,'INT.IP.AIR','International Parcel Air',1,0,0),
	(217,'INT.IP.SURF','International Parcel Surface',1,0,0),
	(218,'INT.PW.ENV','Priority Worldwide Envelope Int\'l',1,0,0),
	(221,'INT.PW.PAK','Priority Worldwide pak Int\'l',1,0,0),
	(222,'INT.PW.PARCEL','Priority Worldwide parcel Int\'l',1,0,0),
	(223,'INT.SP.AIR','Small Packet International Air',1,0,0),
	(224,'INT.SP.SURF','Small Packet International Surface',1,0,0),
	(225,'INT.TP','Tracked Packet - International',1,0,0),
	(301,'PRIORITYOVERNIGHT','FedEx Priority Overnight&reg;',1,0,1),
	(302,'STANDARDOVERNIGHT','FedEx Standard Overnight&reg;',1,0,1),
	(303,'FIRSTOVERNIGHT','FedEx First Overnight&reg;',1,0,1),
	(304,'FEDEX2DAY','FedEx 2Day&reg;',1,0,1),
	(305,'FEDEXEXPRESSSAVER','FedEx Express Saver&reg;',1,0,1),
	(306,'INTERNATIONALPRIORITY','FedEx International Priority&reg;',1,0,1),
	(307,'INTERNATIONALECONOMY','FedEx International Economy&reg;',1,0,1),
	(308,'INTERNATIONALFIRST','FedEx International Next Flight&reg;',1,0,1),
	(310,'FEDEX1DAYFREIGHT','FedEx 1Day Freight&reg;',1,0,0),
	(311,'FEDEX2DAYFREIGHT','FedEx 2Day Freight&reg;',1,0,0),
	(312,'FEDEX3DAYFREIGHT','FedEx 3Day Freight&reg;',1,0,0),
	(313,'FEDEXGROUND','FedEx Ground&reg;',1,1,0),
	(314,'GROUNDHOMEDELIVERY','FedEx Home Delivery&reg;',1,0,0),
	(315,'INTERNATIONALPRIORITYFREIGHT','FedEx International Priority Freight&reg;',1,0,0),
	(316,'INTERNATIONALECONOMYFREIGHT','FedEx International Economy Freight&reg;',1,0,0),
	(317,'EUROPEFIRSTINTERNATIONALPRIORITY','FedEx Europe First&reg; - Int\'l Priority',1,0,1),
	(401,'SMARTPOST','FedEx SmartPost&reg;',1,0,1),
	(501,'3','DHL Easy Shop',1,0,1),
	(502,'4','DHL Jetline',1,0,1),
	(503,'8','DHL Express Easy',1,0,1),
	(504,'E','DHL Express 9:00',1,0,1),
	(505,'F','DHL Freight Worldwide',1,0,1),
	(506,'H','DHL Economy Select',1,0,1),
	(507,'J','DHL Jumbo Box',1,0,1),
	(508,'M','DHL Express 10:30',1,0,1),
	(509,'P','DHL Express Worldwide',1,0,1),
	(510,'Q','DHL Medical Express',0,0,1),
	(511,'V','DHL Europack',1,0,1),
	(512,'Y','DHL Express 12:00',1,0,1),
	(513,'2','DHL Easy Shop',1,0,1),
	(514,'5','DHL Sprintline',1,0,1),
	(515,'6','DHL Secureline',1,0,1),
	(516,'7','DHL Express Easy',1,0,1),
	(517,'9','DHL Europack',1,0,1),
	(518,'B','DHL Break Bulk Express',1,0,1),
	(519,'C','DHL Medical Express',1,0,1),
	(520,'D','DHL Express Worldwide',1,0,1),
	(521,'G','DHL Domestic Economy Express',1,0,1),
	(522,'I','DHL Break Bulk Economy',1,0,1),
	(523,'K','DHL Express 9:00',1,0,1),
	(524,'L','DHL Express 10:30',1,0,1),
	(525,'N','DHL Domestic Express',1,0,1),
	(526,'R','DHL Global Mail Business',1,0,1),
	(527,'S','DHL Same Day',1,0,1),
	(528,'T','DHL Express 12:00',1,0,1),
	(529,'U','DHL Express Worldwide',1,0,1),
	(530,'W','DHL Economy Select',1,0,1),
	(531,'X','DHL Express Envelope',1,0,1),
	(601,'AUS_PARCEL_REGULAR','Parcel Post',1,1,1),
	(602,'AUS_PARCEL_REGULAR_SATCHEL_3KG','Parcel Post',1,0,1),
	(603,'AUS_PARCEL_EXPRESS','Express Post',1,0,1),
	(604,'AUS_PARCEL_EXPRESS_SATCHEL_3KG','Express Post',1,0,1),
	(605,'INTL_SERVICE_ECI_PLATINUM','Express Courier International',1,0,0),
	(606,'INTL_SERVICE_ECI_M','Express Courier International',1,0,0),
	(607,'INTL_SERVICE_ECI_D','Express Courier International',1,0,0),
	(608,'INTL_SERVICE_EPI','Express Post International',1,0,0),
	(609,'INTL_SERVICE_PTI','Pack and Track International',1,0,0),
	(610,'INTL_SERVICE_RPI','Registered Post International',1,0,0),
	(611,'INTL_SERVICE_AIR_MAIL','Air Mail',1,0,0),
	(612,'INTL_SERVICE_SEA_MAIL','Sea Mail',1,0,0),
	(613,'INTL_SERVICE_EPI_B4','Express Post International',0,0,0),
	(614,'INTL_SERVICE_RPI_DLE','Registered Post International',0,0,0),
	(615,'INTL_SERVICE_RPI_B4','Registered Post International',0,0,0),
	(616,'INTL_SERVICE_EPI_C5','Express Post International',0,0,0);

/*!40000 ALTER TABLE `uspsmethods` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table zonecharges
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zonecharges`;

CREATE TABLE `zonecharges` (
  `zcID` int(11) NOT NULL AUTO_INCREMENT,
  `zcZone` int(11) DEFAULT '0',
  `zcWeight` double DEFAULT '0',
  `zcRate` double DEFAULT '0',
  `zcRate2` double DEFAULT '0',
  `zcRate3` double DEFAULT '0',
  `zcRate4` double DEFAULT '0',
  `zcRate5` double DEFAULT '0',
  `zcRatePC` tinyint(1) DEFAULT '0',
  `zcRatePC2` tinyint(1) DEFAULT '0',
  `zcRatePC3` tinyint(1) DEFAULT '0',
  `zcRatePC4` tinyint(1) DEFAULT '0',
  `zcRatePC5` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`zcID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `zonecharges` WRITE;
/*!40000 ALTER TABLE `zonecharges` DISABLE KEYS */;

INSERT INTO `zonecharges` (`zcID`, `zcZone`, `zcWeight`, `zcRate`, `zcRate2`, `zcRate3`, `zcRate4`, `zcRate5`, `zcRatePC`, `zcRatePC2`, `zcRatePC3`, `zcRatePC4`, `zcRatePC5`)
VALUES
	(1,1,0.2,0.3,0.4,0,0,0,0,0,0,0,0),
	(2,1,0.5,0.5,0.6,0,0,0,0,0,0,0,0),
	(3,1,1,0.9,1,0,0,0,0,0,0,0,0),
	(4,1,1.5,1.3,1.4,0,0,0,0,0,0,0,0),
	(5,1,2,1.5,1.6,0,0,0,0,0,0,0,0),
	(6,1,5,2,2.1,0,0,0,0,0,0,0,0),
	(7,1,-1,0.5,0.6,0,0,0,0,0,0,0,0),
	(8,2,0.2,0.4,0.5,0,0,0,0,0,0,0,0),
	(9,2,0.5,0.7,0.8,0,0,0,0,0,0,0,0),
	(10,2,1,1.1,1.2,0,0,0,0,0,0,0,0),
	(11,2,1.5,1.6,1.7,0,0,0,0,0,0,0,0),
	(12,2,2,2,2.1,0,0,0,0,0,0,0,0),
	(13,2,5,3,3.1,0,0,0,0,0,0,0,0),
	(14,2,-1,0.7,0.8,0,0,0,0,0,0,0,0),
	(15,3,-1.1,0.8,0.9,0,0,0,0,0,0,0,0),
	(16,3,0.2,0.5,0.6,0,0,0,0,0,0,0,0),
	(17,3,0.5,0.8,0.9,0,0,0,0,0,0,0,0),
	(18,3,1,1.2,1.3,0,0,0,0,0,0,0,0),
	(19,3,1.5,1.7,1.8,0,0,0,0,0,0,0,0),
	(20,3,2,2.2,2.3,0,0,0,0,0,0,0,0),
	(21,3,5,3.2,3.3,0,0,0,0,0,0,0,0),
	(22,4,-1,1,1.1,0,0,0,0,0,0,0,0),
	(23,4,1,1.5,1.6,0,0,0,0,0,0,0,0),
	(24,4,2,2.8,2.9,0,0,0,0,0,0,0,0),
	(25,4,3,3.8,3.9,0,0,0,0,0,0,0,0),
	(26,4,4,4.8,4.9,0,0,0,0,0,0,0,0),
	(27,101,-1,1,1.1,0,0,0,0,0,0,0,0),
	(28,101,1,1,1.1,0,0,0,0,0,0,0,0);

/*!40000 ALTER TABLE `zonecharges` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
