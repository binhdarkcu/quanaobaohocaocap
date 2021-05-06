<!DOCTYPE html>
<html lang="en">
<!-- Basic -->
<?php
  $eto_options = get_option('eto_settings');
  $bg_color = '#'.$eto_options['color_of_page'];
  $header_top_color = '#'.$eto_options['header_top_color'];
  $sub_header_top_color = '#'.$eto_options['sub_header_top_color'];
  $logo_header = eto_get_image('logo_header');
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo TEMPLATE_PATH;?>/">
    <?php 
        global $posts;
        $title = '';
        if(has_term('', 'category')) {
            $terms = get_the_terms( $posts[0]->ID, 'category' );
            $title = $terms[0]->name;
        } else {
            $title = $posts[0]->post_title;
        }
    ?>
    <!-- Site Metas -->
    <title><?php echo $title ?></title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH?>/css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH?>/css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH?>/css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH?>/css/custom.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    





    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>


<body>

    <!-- Start Main Top -->
    <div class="main-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="text-slid-box">
                        <div id="offer-box" class="carouselTicker">
                            <ul class="offer-box">
                                <li>
                                    <i class="fab fa-opencart"></i> Off 10%! Shop Now Man
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 50% - 80% off on Fashion
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 20% off Entire Purchase Promo code: offT20
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Off 50%! Shop Now
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Off 10%! Shop Now Man
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 50% - 80% off on Fashion
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 20% off Entire Purchase Promo code: offT20
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Off 50%! Shop Now
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="custom-select-box">
                        <select id="basic" class="selectpicker show-tick form-control" data-placeholder="$ USD">
						<option>¥ JPY</option>
						<option>$ USD</option>
						<option>€ EUR</option>
					</select>
                    </div>
                    <div class="right-phone-box">
                        <p>Call US :- <a href="#"> +11 900 800 100</a></p>
                    </div>
                    <div class="our-link">
                        <ul>
                            <li><a href="#">My Account</a></li>
                            <li><a href="#">Our location</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Top -->

    <!-- Start Main Top -->
    <header class="main-header">
        <!-- Start Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav">
            <div class="container">
                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                    <a class="navbar-brand" href="<?php echo home_url();?>"><img width="200" src="<?php echo $logo_header;?>" class="logo" alt=""></a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">

                    <?php
                        wp_nav_menu( array(
                            'menu'   => 'HeaderMenu',
                            'container' => 'div',
                            'container_id' => 'navbar-menu',
                            'container_class' => 'menu',
                            'menu_class'           => 'nav navbar-nav ml-auto',
                            'items_wrap'           => '<ul id="%1$s" data-in="fadeInDown" data-out="fadeOutUp" class="%2$s">%3$s</ul>'
                        ));
                    ?>
                    <ul class="dropdown-menu megamenu-content" role="menu" id="product-menu">
                        <li>
                            <div class="row">
                                <div class="col-menu col-md-3">
                                    <h6 class="title">Top</h6>
                                    <div class="content">
                                        <ul class="menu-col">
                                            <li><a href="shop.html">Jackets</a></li>
                                            <li><a href="shop.html">Shirts</a></li>
                                            <li><a href="shop.html">Sweaters & Cardigans</a></li>
                                            <li><a href="shop.html">T-shirts</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end col-3 -->
                                <div class="col-menu col-md-3">
                                    <h6 class="title">Bottom</h6>
                                    <div class="content">
                                        <ul class="menu-col">
                                            <li><a href="shop.html">Swimwear</a></li>
                                            <li><a href="shop.html">Skirts</a></li>
                                            <li><a href="shop.html">Jeans</a></li>
                                            <li><a href="shop.html">Trousers</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end col-3 -->
                                <div class="col-menu col-md-3">
                                    <h6 class="title">Clothing</h6>
                                    <div class="content">
                                        <ul class="menu-col">
                                            <li><a href="shop.html">Top Wear</a></li>
                                            <li><a href="shop.html">Party wear</a></li>
                                            <li><a href="shop.html">Bottom Wear</a></li>
                                            <li><a href="shop.html">Indian Wear</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-menu col-md-3">
                                    <h6 class="title">Accessories</h6>
                                    <div class="content">
                                        <ul class="menu-col">
                                            <li><a href="shop.html">Bags</a></li>
                                            <li><a href="shop.html">Sunglasses</a></li>
                                            <li><a href="shop.html">Fragrances</a></li>
                                            <li><a href="shop.html">Wallets</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end col-3 -->
                            </div>
                            <!-- end row -->
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->

                <!-- Start Atribute Navigation -->
                <div class="attr-nav">
                    <ul>
                        <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                        <li class="side-menu"><a href="#">
						<i class="fa fa-shopping-bag"></i>
                            <span class="badge">3</span>
					</a></li>
                    </ul>
                </div>
                <!-- End Atribute Navigation -->
            </div>
            <!-- Start Side Menu -->
            <div class="side">
                <a href="#" class="close-side"><i class="fa fa-times"></i></a>
                <li class="cart-box">
                    <ul class="cart-list">
                        <li>
                            <a href="#" class="photo"><img src="images/img-pro-01.jpg" class="cart-thumb" alt="" /></a>
                            <h6><a href="#">Delica omtantur </a></h6>
                            <p>1x - <span class="price">$80.00</span></p>
                        </li>
                        <li>
                            <a href="#" class="photo"><img src="images/img-pro-02.jpg" class="cart-thumb" alt="" /></a>
                            <h6><a href="#">Omnes ocurreret</a></h6>
                            <p>1x - <span class="price">$60.00</span></p>
                        </li>
                        <li>
                            <a href="#" class="photo"><img src="images/img-pro-03.jpg" class="cart-thumb" alt="" /></a>
                            <h6><a href="#">Agam facilisis</a></h6>
                            <p>1x - <span class="price">$40.00</span></p>
                        </li>
                        <li class="total">
                            <a href="#" class="btn btn-default hvr-hover btn-cart">VIEW CART</a>
                            <span class="float-right"><strong>Total</strong>: $180.00</span>
                        </li>
                    </ul>
                </li>
            </div>
            <!-- End Side Menu -->
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Main Top -->

    <!-- Start Top Search -->
    <div class="top-search">
        <div class="container">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search">
                <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
    <!-- End Top Search -->
