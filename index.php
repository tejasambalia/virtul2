
<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Coming Soon</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Proza+Libre:400,400i,500,500i" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/commingsoon.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="padding-top: 0">

    <section class="coming_soon">
        <div class="view_table">
            <div class="view_cell">
                <img src="img/logo.png" class="img-responsive center-block space20" style="height: 100px;">  
                <h3 class="coming_title space50"> we launching soon </h3>      
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <p class="coming_in">
                                Art lover can't wait to see what you build.
                            </p>
                        </div>
                        <div class="item">
                            <p class="coming_in">
                                first we feel then we fall
                            </p>      
                        </div>
                    </div>
                </div>
                <ul id="coming_soon" class="list-inline timer_box">
                  <li><span class="days">00</span><p class="days_text">Days</p></li>
                    <li><span class="hours">00</span><p class="hours_text">Hours</p></li>
                    <li><span class="minutes">00</span><p class="minutes_text">Minutes</p></li>
                    <li><span class="seconds">00</span><p class="seconds_text">Seconds</p></li>
                </ul>  
                <!-- <form class="mailchimp_form">
                    <div class="form-group">
                        <input type="email" name="mail_add" class="form-control" placeholder="Enter Email Address" required="true">
                        <button type="submit" class="btn"> get notified! </button>
                    </div>
                </form> -->
                <!-- Begin MailChimp Signup Form -->
                <div id="mc_embed_signup">
                <form action="//virtul.us15.list-manage.com/subscribe/post?u=a1e86c69c8dd82e5c252aade9&amp;id=c5b047ca96" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate mailchimp_form" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                <div class="mc-field-group form-group">
                    <input type="email" value="" name="EMAIL" placeholder="Enter Email Address" class="required email form-control" id="mce-EMAIL">
                    <input type="submit" value="get notified!" name="subscribe" id="mc-embedded-subscribe" class="button btn">
                </div>
                    <div id="mce-responses" class="clear">
                        <div class="response" id="mce-error-response" style="display:none"></div>
                        <div class="response" id="mce-success-response" style="display:none"></div>
                    </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_a1e86c69c8dd82e5c252aade9_c5b047ca96" tabindex="-1" value=""></div>
                    
                    </div>
                </form>
                </div>
                <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                <!--End mc_embed_signup-->
            </div>
        </div>
    </section>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.countdown.js"></script>

    <script type="text/javascript">
        $('#coming_soon').countdown({
            date: '8/06/2018 23:59:59',
            days: 'Days'
        }, function () {
            alert('Done!');
        });
    </script>
  </body>
</html>