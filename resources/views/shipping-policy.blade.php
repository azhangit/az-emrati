@extends('frontend.layouts.app')

@section ('content')


<style>
    /** Shopify CDN: Minification failed

Line 58:0 All "@import" rules must come first

**/


/* section.research__banner {
display: none;
}
section.events-area.training-area.catering-area {
display: none;
}

.main-content{
min-height:500px !important;
}





.swiper__cont a {
width: 207px !important;
padding: 0 15px;
}
*/

/* commented by mlveda @media only screen and (max-width: 768px)
.pick_currency {
margin-top: 30px;
margin-right: 153px;
} */










html[lang=ar] #translation-lab-language-switcher select {
  background-position: right 0 center!important;
  padding: 0 20px !important;
}
@media (min-width: 2400px){
  body {
    width: 2000px;
    margin: auto;
    position:relative;
  }
}



@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
a,h1,h2,h3,h4,h5,h6,div,span,button,p,label {
  font-family: 'Montserrat', sans-serif !important;
}
a{
  text-decoration: none !important;
  color: unset;
}
.fs14{
  font-size:14px;
}
.fs16{
  font-size: 16px;
}

.fscustom20{
  font-size: 20px;
}
.fs20 {
  font-size: 20px;
}
.fs22{
  font-size: 22px;
}
.fs30{
  font-size: 30px;
}
.fs36{
  font-size: 36px;
}
.fs40{
  font-size: 40px;
}
.fs60{
  font-size: 60px;
}
.bg-black{
  background-color: black !important;
}
.btn-discover{
  background-color: black !important;
  color: white !important;
  text-transform: uppercase;
  border-radius: 0 !important;
  font-weight: bold !important;
  letter-spacing: 1px;
}
a.content-title-link {
  font-size: 16px;
}
.top-header p{
  font-size: 13px;
}
header a {
  color: black !important;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 14px;
}
.header-icon i{
  font-size: 25px;
  margin: 10px;
}
.header-icon span {
  float: right;
}
.converters select {
  word-wrap: normal;
  border: 0;
  color: black;
}
header .navbar-light .navbar-toggler {
  color: rgba(0,0,0,.55);
  border-color: transparent;
  font-size: 26px;
}
header .navbar-toggler:focus {
  box-shadow: unset !important;
}

.homeslider .carousel-indicators {
  position: unset;

}
.homeslider .carousel-indicators [data-bs-target] {
  width: 15px !important;
  height: 15px !important;
  background-color: #c5c5c5 !important;
  border-radius: 50%;

}
.homeslider .carousel-indicators .active {
  background-color: black !important;
}
.suggestion .card{
  background:transparent !important;
  border-radius: 0 !important;
  border: unset !important;
}
.suggestion .card small{
  font-size: 10px;
}
.owl-nav{
  text-align: center;
  margin: 40px 0;
}
label.filters-toolbar__label.select-label {
  display: inline;
  margin: 8px;
}
.filters-toolbar {
  display: grid;
  grid-template-columns: 70% 30%;
}



.navbar-toggler.collapsed .navbar-toggler-icon {
  height: 26px;
  width: 26px;
}
.navbar-toggler .navbar-toggler-icon {

  background-image: url('/cdn/shop/files/wrong.png?v=1641655727') !important;
}
.navbar-toggler.collapsed .navbar-toggler-icon {
  background-image: url('/cdn/shop/files/hamburger.png?v=1641655727') !important;
}
html[lang="ar"] header .ms-auto{
  margin-right:auto !important;
}
html[lang="ar"] .curency-converter.desktop-version .ms-auto{
  margin-left:unset !important;
}
html[lang="ar"] .list--inline>li {
  text-align: right;

}
/* html[lang="ar"] h1, html[lang="ar"] h2, html[lang="ar"] h3, html[lang="ar"] h4, html[lang="ar"] h5, html[lang="ar"] h6{
  letter-spacing: 1px !important;
} */
/* html[lang="ar"]
.social-links span {
  float: left;

} */
html[lang="ar"]
.payment  {
  text-align: right !important;

}
html[lang="ar"]
.text-end  {
  text-align: left !important;

}
  .cart-icon{
  height:40px;
  width:40px;
  }
.textgreish{
color:#8b8b8b;
}
.pagination {
    align-items: center;
}
.pagination  .btn--tertiary {
    background-color: black !important;
    color: #3d4246;
    border-color: #3d4246;
    border-radius: 50%;
    padding: 0;
    height: 40px;
    width: 40px;
    display: flex;
    align-items: center;
    text-decoration: none !important;
  justify-content: center;
}
.pagination li {
    border: none !important;
}
.pagination path {
    fill: white !important;
}
.pagination .pagination__text {
    padding: 0 5.5px !important;
}
@media only screen and (max-width: 767px) {
.template-product .product-form {
    display: block;
}
 .template-product span.price-item.price-item--regular {
    font-size: 20px !important;
}
  .fs22{
  font-size: 13px !important;
}
  .fs30{
  font-size: 16px;
}
  .grid-view-item.product-card span {
    font-weight: 200 !important;
}
  .fscustom20{
  font-size: 11px;
}
  html[lang="ar"]    .fscustom20{
  font-size: 13px;
}
  .fs20 {
    font-size: 10px !important;
}
  .contact__hed h3 {
      font-size: 28px !important;
    }  
  .cart-icon{
  height:35px;
  width:30px;
  }
  
  .site-header__cart img, a.site-header__icon.site-header__account img, .header__insta img {
    margin-top: 0;
    filter: unset !important;
}

  .fs40{
    font-size: 35px;
  }
  
span.price-item.price-item--regular {
    font-size: 10px !important;
}


  div#shopify-section-collection-template {
    padding-top: 10px !important;
  }
  .fs16 {
    font-size: 12px;
  }
  .fs20 {
    font-size: 12px;
  }
  .fs36{
    font-size: 20px;
  }
  .fs60{
    font-size: 30px;
  }
  .list--inline>li {
    text-align: left;
    display:block !important;
    padding: 8px 10px;
    text-transform: uppercase !important;
  }
/*   select {
    padding: 0 !important;
  } */
  .site-nav li a span, .site-nav li button span {
    font-size: 12px!important;
    letter-spacing: 2px!important;
    text-transform: uppercase;
  }
  button.site-nav__link.site-nav__link--main.site-nav__link--button {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 10px 10px;
    text-align: left;
    justify-content: space-between;
  }

  .site-nav a {
    padding: 10px 10px;
  }
  button.site-nav__link.site-nav__link--main.site-nav__link--button svg{
    float: right;
  }
  .list--inline>li {
    text-align: left;
    display: block !important;
    padding: 0;
    text-transform: uppercase !important;
    border-bottom: 1px solid;
  }
  .site-nav__dropdown {
    position: unset;
    padding: 11px 0;
    text-align: left;
    border: unset;
    background: transparent;

  }

 
  .top-header p{
    font-size: 11px;
  }
  .dropdown-toggle::after {
    border: unset !important;
    content: "\f067" !important;
    font-family: 'FontAwesome';
    float: right;
    transition-duration: 0.5s;
  }
  .dropdown-toggle.show::after {
    content: "\f068" !important;

  }
  .service-text{
    font-size: 13px;
    text-align: center;
    padding: 10px 0;
  }


  .social-links{
    text-align: center;
  }
  a.social-icons-link {
    float: unset;
  }
  .payment{
    text-align: center;
  }
  .header-icon i {
    font-size: 22px;
    margin: 6px;
  }
  .converters select {
    font-size: 12px;
  }
  .converters select {
    word-wrap: normal;
    border: 0;
    background: transparent !important;
  }
  header li.nav-item > .nav-link {
    padding: 15px 25px;
    border-bottom: 1px solid;
  }
  header ul.dropdown-menu.show {
    margin: 0;
    border: 0;
    padding: 0;
  }
  ul.dropdown-menu a.dropdown-item {
    padding: 8px 25px;
    border-bottom: 1px solid;
  }
  ul.dropdown-menu {
    transition: all 1s !important;
  }
  a.content-title-link {
    font-size: 12px;
  }
  .payment{
    text-align:center !important;
  }
  
span.price-item.price-item--regular.money.fw-bold {
    font-size: 8px !important;
    font-weight: 500 !important;
}
}
.main-content{
  padding-top:0 !important;
}


.grid-view-item__title a {
    font-weight: bold !important;
}



.fw-600 {
    font-weight: 600!important;
}
span.site-nav__label {
    font-size: 24px !important;
    font-weight: 500 !important;
}

.list--inline>li {
    margin: 0 8px;
}

.thumbnails-wrapper .grid{
  display: flex;
    justify-content: start;
    padding-left: 0px;
    padding-right: 0px;
    margin-left: -22px !important;
    margin-right: 0px !important;
}
ul.list--inline.pagination {
    direction: initial !important;
}
    /*/gjgyjjgj*/
    table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #dee2e6;
    padding: 10px;
    text-align: center;
}

th {
    background-color: #343a40;
    color: white;
    font-weight: bold;
}

td {
    background-color: #f8f9fa;
}

@media (max-width: 992px) {
    table {
        font-size: 14px;
    }
}

@media (max-width: 768px) {
    table {
        font-size: 12px;
    }
    th, td {
        padding: 8px;
    }
}

@media (max-width: 576px) {
    table {
        font-size: 11px;
    }
    th, td {
        padding: 6px;
    }
}

    
</style>




<main class="main-content px-3 mx-md-auto w-md-50 js-focus-hidden" id="MainContent" role="main" tabindex="-1">
          <div class="shopify-policy__container" bis_skin_checked="1">
  <div class="shopify-policy__title text-uppercase text-center my-5" bis_skin_checked="1">
    <h1>Shipping policy</h1>
  </div>

  <div class="shopify-policy__body" bis_skin_checked="1">
    <div class="rte" bis_skin_checked="1">
        <h4>Shipping&nbsp;</h4>
<p><span>We deliver to the following locations within the&nbsp;GCC, Europe, Asia – Pacific and MENASA:</span></p>
<div class="scrollable-wrapper" bis_skin_checked="1">
    <table>
<tbody>
<tr>
<td>
<p><b>Europe</b></p>
</td>
<td>
<p><b>GCC</b></p>
</td>
<td>
<p><b>MENASA</b></p>
</td>
<td>
<p><b>ASIA - PACIFIC</b></p>
</td>
</tr>
<tr>
<td>
<p><span>UK</span></p>
</td>
<td>
<p><span>UAE</span></p>
</td>
<td>
<p><span>Lebanon</span></p>
</td>
<td>
<p><span>China</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Ireland</span></p>
</td>
<td>
<p><span>Oman</span></p>
</td>
<td>
<p><span>Egypt</span></p>
</td>
<td>
<p><span>Japan</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Spain</span></p>
</td>
<td>
<p><span>Saudi Arabia</span></p>
</td>
<td>
<p><span>Tunisia</span></p>
</td>
<td>
<p><span>South Korea</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Portugal</span></p>
</td>
<td>
<p><span>Kuwait</span></p>
</td>
<td>
<p><span>Algiers</span></p>
</td>
<td>
<p><span>Singapore</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Norway</span></p>
</td>
<td>
<p><span>Bahrain</span></p>
</td>
<td>
<p><span>Morocco</span></p>
</td>
<td>
<p><span>New Zealand</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Estonia</span></p>
</td>
<td>
<p><span>Qatar</span></p>
</td>
<td>
<p><span>Turkey</span></p>
</td>
<td>
<p><span>Australia</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Greece</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Jordan</span></p>
</td>
<td>
<p><span>Philippines</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Iceland</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Azerbaijan</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Denmark</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Pakistan</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Netherlands</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Turkmenistan</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Germany</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Iran</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Italy</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Sri Lanka</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Sweden</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Bangladesh</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Luxembourg</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>India</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Poland</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Thailand</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Switzerland</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Indonesia</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Austria</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>Malaysia</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Hungary</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Finland</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>Lithuania</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
<tr>
<td>
<p><span>France</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
<td>
<p><span>&nbsp;</span></p>
</td>
</tr>
</tbody>
</table>
</div>
<br> <span>Emirati Coffee LLC ship orders differently dependent on the delivery address. Please note deliveries may take longer to ship to various destinations; however shipments to countries within the GCC can be&nbsp;shipped within 3 days. <br></span>
<p><span>Shipment will take place within 24 hours of placing the order. The estimated delivery date will be confirmed on purchase and detailed in your confirmation email. Please note that customer is unable to select a carrier for shipment as we work collaboratively with our established courier partners.&nbsp;</span></p>
<span><br></span>
<p><span>Please allow time up&nbsp;until the specified date before contacting us about delivery status, and remember to track your order via the Emirati Coffee LLC online store. Once your specified date has been reached, please do&nbsp;not hesitate to contact us with any inquiries to&nbsp;</span><a href="mailto:info@emiraticoffee.com?subject=Hi%20Emirati%20Coffee!" aria-describedby="a11y-external-message"><span>info@emiraticoffee.com</span></a><span>&nbsp;or call us on +971 50 484 4624.</span></p>
<span><br></span>
<p><span>Emirati Coffee LLC is delighted to offer you a straightforward return and exchange policy. If you have questions about the items purchased from us, please get in touch with us at&nbsp;</span><a href="mailto:info@emiraticoffee.com?subject=Hi%20Emirati%20Coffee!" aria-describedby="a11y-external-message"><b>order@emiraticoffee.com</b></a><b>.</b></p>
<p>&nbsp;</p>
<p><span>Please also note that our roastery is closed on Fridays and Saturdays and we do not do deliveries on these days. Orders placed on Thursday will be delivered Sunday at earliest. Although we would like to accommodate all our clients’ emergency requests, this is not always possible.</span></p>
<p><span>We suggest getting your orders in as early in the week as possible. Orders placed after 3pm will only be processed on the next working day and shipped on the next delivery day.</span></p>
<p><span>Orders placed on Sunday will only be processed on Monday and sent on the following delivery day.</span></p>
<p><span></span><span>Please ensure to order 2 working days before your delivery day. Although we have a great turnaround time for orders and often deliver a day after ordering, our policy is 2 to 3 working days for delivery.</span></p>
<p>&nbsp;</p>
    </div>
  </div>
</div>

        </main>




@endsection