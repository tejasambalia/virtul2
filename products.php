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

        <div class="products_page">
            <div class="container">
                <?php include "vsadmin/inc/incproducts.php" ?>
            </div>
        </div>       

        <?php include('core/footer.php') ?>
    </body>
</html>