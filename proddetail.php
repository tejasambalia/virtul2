<?php
session_cache_limiter('none');
session_start();
ob_start();
?>

<?php include "vsadmin/db_conn_open.php" ?>
<?php include "vsadmin/inc/languagefile.php" ?>
<?php include "vsadmin/includes.php" ?>
<?php include "vsadmin/inc/incfunctions.php" ?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <?php include "core/head.php" ?>

        <title>Virtul</title>


    </head>
    <body>

        <?php include "core/header.php" ?>

        <div class="product-detail-page">
        	<div class="container">
        		<?php include "vsadmin/inc/incproddetail.php" ?>		
        	</div>
        </div>
        

        <?php include('core/footer.php') ?>
    </body>
</html>