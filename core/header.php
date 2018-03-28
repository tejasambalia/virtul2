
        <header>
            <div class="topbar">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-inline top_contact">
                                <li><a href="tel:+91940938 8600"><span class="material-icons">call</span> +91 940 938 8600</a></li>
                                <li><a href="mailto:art@virtul.in"><span class="material-icons">email</span> art@virtul.in</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-inline top_cart">
                                <li class="topminicart"><?php include "vsadmin/inc/incminicart.php" ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <nav class="navbar">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="img/logo.png" class="img-responsive"></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav mid-nav">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Paintings</a></li>
                        <li><a href="#">Photography</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Drawings <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Designs</a></li>
                    </ul>
                    
                    <form class="navbar-form navbar-right">
                        <div class="form-group cursor-blink">
                            <input type="text" class="form-control" placeholder="Search" autofocus="">
                        </div>
                        <button type="submit" class="btn btn-default"><span class="material-icons">search</span></button>
                    </form>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>