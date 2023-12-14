
@extends('Frontend::layouts.index')
@section('script')
    <script type="text/javascript">

        var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
        NclLib.loadFileJsCss(arrJsCss);

        var JS_Home = new Home('{{ url('') }}', '');
        jQuery(document).ready(function($) {
            JS_Home.loadIndex();
        })
    </script>
@endsection
@section('body-client')
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
  <!-- <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div> -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="../img/home/parallax-bg.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="../img/home/parallax-bg.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="../img/home/parallax-bg.png" class="d-block w-100" alt="...">
    </div>
  </div>
  <!-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button> -->
</div>
<!-- ================ offer section start ================= 
<section class="offer" id="parallax-1" data-anchor-target="#parallax-1">
      <div class="container">
        <div class="row">
          <div class="col-xl-5">
            <div class="offer__content text-center">
              <h3>Giảm giá đến 50%</h3>
              <h4>Siêu ưu đãi</h4>
              <p style="color:#ffe51b;font-size: 20px;">Thả ga mua sắm , không lo về giá</p>
              <a class="button button--active mt-3 mt-xl-4" href="#">Shop Now</a>
            </div>
          </div>
        </div>
      </div>
    </section>-->
  <!--================Blog Categorie Area =================-->
    <section class="blog_categorie_area_nc">
      <div class="container">
      <!-- <div class="section-intro pb-30px">
          <h5> <span class="section-intro__style">Danh mục sản phẩm</span></h5>
        </div> -->
        <div class="row">
          <div class="col-33nc col-sm-6 col-lg-4 mb-4 mb-lg-0">
              <div class="categories_post">
                  <img class="card-img rounded-0" src="img/blog/cat-post/cat-post-3.jpg" alt="post">
                  <div class="categories_details">
                      <div class="categories_text-nc">
                          <a href="single-blog.html">
                              <span style="color: white;">Nam</span>
                          </a>
                          <!-- <div class="border_line"></div>
                          <p>Enjoy your social life together</p> -->
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-33nc col-sm-6 col-lg-4 mb-4 mb-lg-0">
            <div class="categories_post">
              <img class="card-img rounded-0" src="img/blog/cat-post/cat-post-2.jpg" alt="post">
              <div class="categories_details">
                <div class="categories_text-nc">
                  <a href="single-blog.html">
                      <span style="color: white;">Nữ</span>
                  </a>
                  <!-- <div class="border_line"></div>
                  <p>Be a part of politics</p> -->
                </div>
              </div>
            </div>
          </div>
          <div class="col-33nc col-sm-6 col-lg-4 mb-4 mb-lg-0">
              <div class="categories_post">
                  <img class="card-img rounded-0" src="img/blog/cat-post/cat-post-1.jpg" alt="post">
                  <div class="categories_details">
                      <div class="categories_text-nc">
                          <a href="single-blog.html">
                              <span style="color: white;">Trẻ em</span>
                          </a>
                          <!-- <div class="border_line"></div>
                          <p>Let the food be finished</p> -->
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-33nc col-sm-6 col-lg-4 mb-4 mb-lg-0">
              <div class="categories_post">
                  <img class="card-img rounded-0" src="img/blog/cat-post/cat-post-3.jpg" alt="post">
                  <div class="categories_details">
                      <div class="categories_text-nc">
                          <a href="single-blog.html">
                              <span style="color: white;">Giày dép</span>
                          </a>
                          <!-- <div class="border_line"></div>
                          <p>Enjoy your social life together</p> -->
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-33nc col-sm-6 col-lg-4 mb-4 mb-lg-0">
            <div class="categories_post">
              <img class="card-img rounded-0" src="img/blog/cat-post/cat-post-2.jpg" alt="post">
              <div class="categories_details">
                <div class="categories_text-nc">
                  <a href="single-blog.html">
                      <span style="color: white;">Mũ, đồ dùng</span>
                  </a>
                  <!-- <div class="border_line"></div>
                  <p>Be a part of politics</p> -->
                </div>
              </div>
            </div>
          </div>
          <div class="col-33nc col-sm-6 col-lg-4 mb-4 mb-lg-0">
              <div class="categories_post">
                  <img class="card-img rounded-0" src="img/blog/cat-post/cat-post-1.jpg" alt="post">
                  <div class="categories_details">
                      <div class="categories_text-nc">
                          <a href="single-blog.html">
                              <span style="color: white;">Sản phẩm giảm giá</span>
                          </a>
                          <!-- <div class="border_line"></div>
                          <p>Let the food be finished</p> -->
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </section>
  <!--================Blog Categorie Area =================-->
     <!-- ================ Best Selling item  carousel ================= --> 
     <section class="blog_categorie_area_nc">
      <div class="container">
        <div class="section-intro pb-60px">
          <p>Mặt hàng phổ biến trên thị trường</p>
          <h2> <span class="section-intro__style">Bán chạy nhất</span></h2>
        </div>
        <div class="row">
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product1.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Accessories</p>
                <h4 class="card-product__title"><a href="single-product.html">Quartz Belt Watch</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product2.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Beauty</p>
                <h4 class="card-product__title"><a href="single-product.html">Women Freshwash</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product3.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Decor</p>
                <h4 class="card-product__title"><a href="single-product.html">Room Flash Light</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product4.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Decor</p>
                <h4 class="card-product__title"><a href="single-product.html">Room Flash Light</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product5.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Accessories</p>
                <h4 class="card-product__title"><a href="single-product.html">Man Office Bag</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product6.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Kids Toy</p>
                <h4 class="card-product__title"><a href="single-product.html">Charging Car</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product7.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Accessories</p>
                <h4 class="card-product__title"><a href="single-product.html">Blutooth Speaker</a></h4>
                <p class="card-product__price">$150.00</p>
              </div> 
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product8.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Kids Toy</p>
                <h4 class="card-product__title"><a href="#">Charging Car</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <!--================Blog Categorie Area =================-->
    <!-- ================ trending product section start ================= -->  
    <section class="section-margin calc-60px">
      <div class="container">
        <div class="section-intro pb-60px">
          <p>Mặt hàng phổ biến trên thị trường</p>
          <h2>  <span class="section-intro__style">Sản phẩm thịnh hành</span></h2>
        </div>
        <div class="row">
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product1.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Accessories</p>
                <h4 class="card-product__title"><a href="single-product.html">Quartz Belt Watch</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product2.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Beauty</p>
                <h4 class="card-product__title"><a href="single-product.html">Women Freshwash</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product3.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Decor</p>
                <h4 class="card-product__title"><a href="single-product.html">Room Flash Light</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product4.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Decor</p>
                <h4 class="card-product__title"><a href="single-product.html">Room Flash Light</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product5.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Accessories</p>
                <h4 class="card-product__title"><a href="single-product.html">Man Office Bag</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product6.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Kids Toy</p>
                <h4 class="card-product__title"><a href="single-product.html">Charging Car</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product7.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Accessories</p>
                <h4 class="card-product__title"><a href="single-product.html">Blutooth Speaker</a></h4>
                <p class="card-product__price">$150.00</p>
              </div> 
            </div>
          </div>
          <div class="col-50nc col-md-6 col-lg-4 col-xl-3">
            <div class="card text-center card-product">
              <div class="card-product__img">
                <img class="card-img" src="../img/product/product8.png" alt="">
                <ul class="card-product__imgOverlay">
                  <li><button><i class="ti-search"></i></button></li>
                  <li><button><i class="ti-shopping-cart"></i></button></li>
                  <li><button><i class="ti-heart"></i></button></li>
                </ul>
              </div>
              <div class="card-body">
                <p>Kids Toy</p>
                <h4 class="card-product__title"><a href="#">Charging Car</a></h4>
                <p class="card-product__price">$150.00</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ================ trending product section end ================= -->  


    <!-- ================ offer section start ================= --> 
    <section class="offer" id="parallax-1" data-anchor-target="#parallax-1" data-300-top="background-position: 20px 30px" data-top-bottom="background-position: 0 20px">
      <div class="container">
        <div class="row">
          <div class="col-xl-5">
            <div class="offer__content text-center">
              <h3>Up To 50% Off</h3>
              <h4>Winter Sale</h4>
              <p>Him she'd let them sixth saw light</p>
              <a class="button button--active mt-3 mt-xl-4" href="#">Shop Now</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ================ offer section end ================= --> 

    <!-- ================ Blog section start ================= -->  
    <section class="blog">
      <div class="container">
        <div class="section-intro pb-60px">
          <p>Bài viết thời trang phổ biến</p>
          <h2>Tin tức mới nhất <span class="section-intro__style"></span></h2>
        </div>

        <div class="row">
          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="card card-blog">
              <div class="card-blog__img">
                <img class="card-img rounded-0" src="../img/blog/blog1.png" alt="">
              </div>
              <div class="card-body">
                <ul class="card-blog__info">
                  <li><a href="#">By Admin</a></li>
                  <li><a href="#"><i class="ti-comments-smiley"></i> 2 Comments</a></li>
                </ul>
                <h4 class="card-blog__title"><a href="single-blog.html">The Richland Center Shooping News and weekly shooper</a></h4>
                <p>Let one fifth i bring fly to divided face for bearing divide unto seed. Winged divided light Forth.</p>
                <a class="card-blog__link" href="#">Read More <i class="ti-arrow-right"></i></a>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="card card-blog">
              <div class="card-blog__img">
                <img class="card-img rounded-0" src="../img/blog/blog2.png" alt="">
              </div>
              <div class="card-body">
                <ul class="card-blog__info">
                  <li><a href="#">By Admin</a></li>
                  <li><a href="#"><i class="ti-comments-smiley"></i> 2 Comments</a></li>
                </ul>
                <h4 class="card-blog__title"><a href="single-blog.html">The Shopping News also offers top-quality printing services</a></h4>
                <p>Let one fifth i bring fly to divided face for bearing divide unto seed. Winged divided light Forth.</p>
                <a class="card-blog__link" href="#">Read More <i class="ti-arrow-right"></i></a>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="card card-blog">
              <div class="card-blog__img">
                <img class="card-img rounded-0" src="../img/blog/blog3.png" alt="">
              </div>
              <div class="card-body">
                <ul class="card-blog__info">
                  <li><a href="#">By Admin</a></li>
                  <li><a href="#"><i class="ti-comments-smiley"></i> 2 Comments</a></li>
                </ul>
                <h4 class="card-blog__title"><a href="single-blog.html">Professional design staff and efficient equipment you’ll find we offer</a></h4>
                <p>Let one fifth i bring fly to divided face for bearing divide unto seed. Winged divided light Forth.</p>
                <a class="card-blog__link" href="#">Read More <i class="ti-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ================ Blog section end ================= -->  

    <!-- ================ Subscribe section start ================= --> 
    <section class="subscribe-position">
      <div class="container">
        <div class="subscribe text-center">
          <h3 class="subscribe__title">Get Update From Anywhere</h3>
          <p>Bearing Void gathering light light his eavening unto dont afraid</p>
          <div id="mc_embed_signup">
            <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="subscribe-form form-inline mt-5 pt-1">
              <div class="form-group ml-sm-auto">
                <input class="form-control mb-1" type="email" name="EMAIL" placeholder="Enter your email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your Email Address '" >
                <div class="info"></div>
              </div>
              <button class="button button-subscribe mr-auto mb-1" type="submit">Subscribe Now</button>
              <div style="position: absolute; left: -5000px;">
                <input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
              </div>

            </form>
          </div>
          
        </div>
      </div>
    </section>
    <!-- ================ Subscribe section end ================= --> 
    @endsection