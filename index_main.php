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

        <!-- syntaxHilighter style sheet -->
        <link rel="stylesheet" type="text/css" href="assets/syntax-highlighter/styles/shCore.css" media="all">
        <link rel="stylesheet" type="text/css" href="assets/syntax-highlighter/styles/shThemeDefault.css" media="all">
        
        <!-- main style -->
        <link rel="stylesheet" type="text/css" href="assets/css/style.css" media="all">

        <!-- Base MasterSlider style sheet -->
        <link rel="stylesheet" href="css/masterslider.css" />
        
        <!-- Master Slider Skin -->
        <link href="css/style1.css" rel='stylesheet' type='text/css'>
         
        <!-- MasterSlider Template Style -->
        <link href='css/ms-fullscreen.css' rel='stylesheet' type='text/css'>

        <!-- Base MasterSlider style sheet -->
        <link rel="stylesheet" href="css/masterslider.css" />
        
        <!-- Master Slider Skin -->
        <link href="css/style1.css" rel='stylesheet' type='text/css'>
         
        <!-- MasterSlider Template Style -->
        <link href='css/ms-fullscreen.css' rel='stylesheet' type='text/css'>

        <style type="text/css">
            .master-slider{
                height: 100%;
                font-family: Lato, arial, sans-serif;
            }
        </style>

    </head>
    <body>

        <?php include "core/header.php" ?>
        
        <div class="ms-fullscreen-template" id="slider1-wrapper">
            <!-- masterslider -->
            <div class="master-slider ms-skin-default" id="masterslider">
                <div class="ms-slide slide-1">
                    
                    <img src="images/blank.gif" data-src="img/banners/pexels-photo-886521.jpeg" alt="lorem ipsum dolor sit"/>  
                    <h3 class="ms-layer bold-text-white bigtext"
                        data-type="text"
                        data-effect="rotate3dtop(70,0,0,180)"
                        data-duration="2000"
                        data-ease="easeInOutQuint"
                    >
                        FOR ART LOVERS
                    </h3>
                    <h4 class="ms-layer bold-text-white bigtext-2"
                        data-type="text"
                        data-effect="rotate3dbottom(-70,0,0,180)"
                        data-duration="2000"
                        data-ease="easeInOutQuint"
                    >
                        BY ART LOVERS
                    </h4>
                </div>
                <div class="ms-slide slide-2">
                  
                   <h3 class="ms-layer thin-text-white blacktext"
                        data-type="text"
                        data-effect="rotate3dleft(50,0,0,480)"
                        data-duration="2000"
                        data-ease="easeInOutQuint"
                    >
                        MODERN
                    </h3>
                    <h3 class="ms-layer thin-text-black whitetext"
                        data-type="text"
                        data-effect="rotate3dright(-50,0,0,480)"
                        data-duration="2000"
                        data-ease="easeInOutQuint"
                    >
                        DESIGN
                    </h3>
                    <img src="images/blank.gif" data-src="img/banners/painting-pencil-paint-pencils.jpg" alt="lorem ipsum dolor sit"/>     
                </div>
            </div>
            <!-- end of masterslider -->
        </div>

        <div class="home-categories-sec">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="title">Top art categories</h2>
                        <h2 class="title_text">Lorem ipsum donor sit amet</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="home-cate-box">
                            <img src="img/banners/painting-black-paint-roller.jpg" class="img-responsive wid100">
                            <h3>Painting</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="home-cate-box">
                            <img src="img/banners/pexels-photo-136133.jpeg" class="img-responsive wid100">
                            <h3>Drawing</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="home-cate-box">
                            <img src="img/banners/wood-night-camera-vintage.jpg" class="img-responsive wid100">
                            <h3>Photography</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="home-who-we">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">

                        <h3 class="title">Our story</h3>
                        <h4 class="title_text">Lorem ipsum donor sit amet</h4>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        </p>
                        <a href="#" class="btn btn-default btn-lg">Read More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="home-creatives">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="title">crafted for creatives</h1>
                        <h2 class="title_text">Lorem ipsum donor sit ametrt</h2>
                    </div>
                </div>
                <div class="row creative_pic_grid">
                    <div class="col-md-4">
                        <div class="creative_grid">
                            <img src="img/banners/pexels-photo-262034.jpeg" class="img-responsive wid100">
                        </div>
                        <div class="creative_grid space30">
                            <img src="img/banners/pexels-photo-198327.jpeg" class="img-responsive wid100">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="creative_grid">
                            <img src="img/banners/pexels-photo-133170.jpeg" class="img-responsive wid100">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="creative_grid">
                            <img src="img/banners/pexels-photo-312420.jpeg" class="img-responsive wid100">
                        </div>
                        <div class="creative_grid space30">
                            <img src="img/banners/pexels-photo-220421.jpeg" class="img-responsive wid100">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('core/footer.php') ?>
        
        <script src="js/jquery.easing.min.js"></script>
        <!-- Master Slider -->
        <script src="js/masterslider.min.js"></script>

        <!-- Tabs -->
        <script src="assets/js/tab.js"></script>
        <script src="assets/syntax-highlighter/scripts/shCore.js"></script>
        <script src="assets/syntax-highlighter/scripts/shBrushXml.js"></script>
        <script src="assets/syntax-highlighter/scripts/shBrushCss.js"></script>
        <script src="assets/syntax-highlighter/scripts/shBrushJScript.js"></script>
        <script type="text/javascript">     
    
            var slider = new MasterSlider();
            slider.setup('masterslider' , {
                width:1024,
                height:768,
                space:3,
                view:'basic',
                autofill:true,
                speed:5,
                autoplay:true,
                loop:true
            });
            
            slider.control('arrows' ,{insertTo:'#masterslider'});   
            slider.control('bullets');  

            var wrapper = $('#slider1-wrapper');
            wrapper.height(window.innerHeight - 0);
            $(window).resize(function(event) {
                wrapper.height(window.innerHeight - 0);
            });


            $('#myTab a').click(function (e) {
              e.preventDefault()
              $(this).tab('show')
            });

            SyntaxHighlighter.all();
            
        </script>
    </body>
</html>
