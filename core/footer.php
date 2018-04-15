

        <div  class="newsletter-sec">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <h3 class="title">Get on the list</h3>
                    </div>
                    <div class="col-md-7">
                        <form method="post">
                            <input type="email" class="form-control" placeholder="Email Address" name="email">
                            <button type="submit" class="btn btn-default btn-lg">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-xs-3">
                        <h3 class="footer_title">
                            Top Searches
                        </h3>
                        <ul class="list-unstyled footer_menu">
                            <li><a href="#">Art</a></li>
                            <li><a href="#">Creative</a></li>
                            <li><a href="#">Paintings</a></li>
                            <li><a href="#">Photoes</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-3">
                        <h3 class="footer_title">
                            virtul.com
                        </h3>
                        <ul class="list-unstyled footer_menu">
                            <li><a href="#">Partner with us</a></li>
                            <li><a href="#">About us</a></li>
                            <li><a href="#">FAQs</a></li>
                            <li><a href="#">Contact us</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-3">
                        <h3 class="footer_title">
                            Legal
                        </h3>
                        <ul class="list-unstyled footer_menu">
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms & Conditions</a></li>
                            <li><a href="#">Disclaimer</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-3">
                        <h3 class="footer_title">
                            Contact
                        </h3>
                        <ul class="list-unstyled footer_contact">
                            <li class="call"><a href="#"><span class="ion-ios-telephone ion-icons"></span> +91 940 938 8600</a></li>
                            <li class="mail"><a href="#"><span class="ion-android-mail ion-icons"></span> art@virtul.in</a></li>
                        </ul>
                        <ul class="list-inline footer_social">
                            <li><a href="#"><span class="ion-icons ion-social-facebook"></span></a></li>
                            <li><a href="#"><span class="ion-icons ion-social-instagram"></span></a></li>
                            <li><a href="#"><span class="ion-icons ion-social-pinterest"></span></a></li>
                            <li><a href="#"><span class="ion-icons ion-social-twitter"></span></a></li>
                            <li><a href="#"><span class="ion-icons ion-social-googleplus"></span></a></li>
                            <li><a href="#"><span class="ion-icons ion-social-youtube"></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <p> &copy;  copyright 2018 @ Virtul.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>

        <script type="text/javascript">
            /* Thanks to CSS Tricks for pointing out this bit of jQuery
            https://css-tricks.com/equal-height-blocks-in-rows/
            It's been modified into a function called at page load and then each time the page is resized. One large modification was to remove the set height before each new calculation. */

            equalheight = function(container){

            var currentTallest = 0,
                 currentRowStart = 0,
                 rowDivs = new Array(),
                 $el,
                 topPosition = 0;
             $(container).each(function() {

               $el = $(this);
               $($el).height('auto')
               topPostion = $el.position().top;

               if (currentRowStart != topPostion) {
                 for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                   rowDivs[currentDiv].height(currentTallest);
                 }
                 rowDivs.length = 0; // empty the array
                 currentRowStart = topPostion;
                 currentTallest = $el.height();
                 rowDivs.push($el);
               } else {
                 rowDivs.push($el);
                 currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
              }
               for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                 rowDivs[currentDiv].height(currentTallest);
               }
             });
            }

            $(window).load(function() {
              equalheight('.eq_height');
            });


            $(window).resize(function(){
              equalheight('.eq_height');
            });

        </script>