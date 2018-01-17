<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> <!-- OGSÅ MIT -->

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

<!-- MIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIT -->

<style>
/* Se under TILPAS > CUSTOM CSS */
</style>
<div class="price-cat-wrapper">
  <div class="price-cat-btn-wrapper">
    <?php
    $index_for_cat = 0;
    if( have_rows('price_cat', 'option') ):
      while ( have_rows('price_cat', 'option') ) : the_row(); ?>
        <button class="price-cat-btn <?php echo ($index_for_cat == 0) ? 'active' : '' ?>" id="<?php the_sub_field('titel'); ?>"
         data-a="<?php the_sub_field('a'); ?>"
         data-b="<?php the_sub_field('b'); ?>"
         data-c="<?php the_sub_field('c'); ?>"
         data-d="<?php the_sub_field('d'); ?>">
            <?php the_sub_field('titel'); ?>
        </button>
    <?php 
      $index_for_cat++;
      endwhile;
    endif; # If have_rows?>
  </div>
  <hr>
  <div class="fejl-besked"></div>
  <div class="ord-taeller-wrapper">
    <input id="ordTaeller" type="text" placeholder="Indtast antal ord.." /> 
  </div>
  <div class="price-cat-discount-wrapper">
    <?php
    $index = 0;
    if( have_rows('deadlines', 'option') ):

      while ( have_rows('deadlines', 'option') ) : the_row(); ?>
        <button class="price-cat-deadline-btn <?php echo ($index === 0) ? 'active' : '' ?>"
         data-discount="<?php the_sub_field('rabat'); ?>"
         data-string="<?php the_sub_field('periode'); ?>">
            <?php the_sub_field('periode'); ?>
        </button>
    <?php 
      $index++;
      endwhile;
    endif; ?>
  </div>
  <h2 id="priceCatPrice"></h2>
  <div class="send-offer-wrapper">
    <input type="email" id="sendMailNow" placeholder="Indtast din mail..">
    <a href="#" target="_blank" id="sendOffer">Send mail med pris</a>
  </div>
</div><!-- price-cat-wrapper -->
<script>

function getData(id) {
  a = Number($("#" + id).data('a'));
  b = Number($("#" + id).data('b'));
  c = Number($("#" + id).data('c'));
  d = Number($("#" + id).data('d'));
  return { a: a, b: b, c: c, d: d }
}

function PriceCat(data) {
  this.a = data.a;
  this.b = data.b;
  this.c = data.c;
  this.d = data.d;
  this.price = function(number_of_words) {
    return (Math.round(number_of_words*this.a) - Math.pow(number_of_words*this.b, this.c)+1)+this.d;
  }
  this.price_with_deadline = function(word_count, discount) {
    if (word_count === 0) {
      return 0;
    } else {
      let discount_int = Number(discount) * 0.01; // For at procent skal blive et decimaltal: 10 * 0,01 = 0,1  
      return Math.round(this.price(word_count) * (1 - discount_int));
    }
  }
}

function getDiscountAndWordCount() { 
  let word_count = Number($("#ordTaeller").val()) || 0; // Returnere 0, hvis der ikke er indtastet noget endnu.
  let discount = Number($(".price-cat-discount-wrapper .active").data('discount'));
  return { word_count: word_count, discount: discount }
}

function changePrice(cons_name, data) {
  $("#priceCatPrice").html(window[cons_name].price_with_deadline(data.word_count, data.discount) + " kr. "); 
}

function validateEmail(Email) {
    var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return $.trim(Email).match(pattern) ? true : false;
}

$(document).ready(function() {

  $(".price-cat-btn").each(function(i, price_cat) {
    window[price_cat.id] = new PriceCat(getData(price_cat.id)); // Gemmes under en global variabel med samme navn som knappens id
    if (i === 0) { changePrice(price_cat.id, getDiscountAndWordCount()); } // Sæt prisen ved første priskategori.
  });

  $(".price-cat-btn").click(function() { // Skifter pris kategori via klik
    $(this).addClass('active').siblings().removeClass('active');
    changePrice(this.id, getDiscountAndWordCount());
  });

  $(".price-cat-deadline-btn").click(function() { // Skifter deadline via klik
    let active_price_cat_id = $(".price-cat-btn-wrapper .active").attr('id');
    $(this).addClass('active').siblings().removeClass('active');
    changePrice(active_price_cat_id, getDiscountAndWordCount());
  });

  $("#ordTaeller").on("input", function() { // Når der bliver tastet noget nyt ind i antal-ord feltet.
  let word_count = Number($("#ordTaeller").val()); 
    if ( word_count <= 200000 && $.isNumeric(word_count) ) { // Er det et tal, der bliver indtastet?
      $(".fejl-besked").hide();
      $(".send-offer-wrapper").slideDown(400); // Hvis mailfunktion, hvis antal ord er valid.
      let active_price_cat_id = $(".price-cat-btn-wrapper .active").attr('id');
      changePrice(active_price_cat_id, getDiscountAndWordCount());
    } else if (word_count > 200000) {
      $(".send-offer-wrapper").slideUp(400); // Fjern mailfunktion, hvis antal ord ikke er valid
      $(".fejl-besked").html("Det maksimale antal ord er 200.000").show();
    } else {
      $(".send-offer-wrapper").slideUp(400); // Fjern mailfunktion, hvis antal ord ikke er valid
      $(".fejl-besked").html("Det indtastede skal være et tal. Prøv igen.").show();
    }
  });

  $("#sendOffer").on('click', function(e) { // Indsaml data og send mail.
    e.preventDefault();
    var mail = $("#sendMailNow").val();
    var deadline = $(".price-cat-discount-wrapper .active").data('string');
    var email_validated = validateEmail(mail);
    var price_cat = $(".price-cat-btn-wrapper .active").attr('id');
    var num_object = getDiscountAndWordCount();
    var price = window[price_cat].price_with_deadline(num_object.word_count, num_object.discount);
    if (num_object.word_count && mail && price && price_cat && email_validated) { // Hvis alle værdier er tilstede
      window.location = 'mailto:' + mail + '?subject=' + price_cat + ' af A1Kommunikation' + 
        '&body=' + num_object.word_count + ' ords ' + price_cat + '. Pris: ' + price + ' kr.' + '%0D%0A' + 
        'Ønskes færdiggjort om ' + deadline + '.' + '%0D%0A%0D%0A' + 'Vedhæft venligst vedrørende fil/filer og indtast dit navn, adresse, CVR. nr. mm.';
    } else if (!email_validated) {
      $(".fejl-besked").html("Din email blev ikke godkendt. Ret den til og prøv igen.").show(); 
    } else {
      $(".fejl-besked").html("Indtast venligst et antal ord og din mail og prøv igen.").show(); 
    }
  });
});

</script>
<!-- MIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIT -->

<?php // Show the selected frontpage content.
if ( have_posts() ) :
  while ( have_posts() ) : the_post();
get_template_part( 'template-parts/page/content', 'front-page' );
endwhile;
else : // I'm not sure it's possible to have no posts when this page is shown, but WTH.
  get_template_part( 'template-parts/post/content', 'none' );
endif; ?>

<?php
// Get each of our panels and show the post data.
if ( 0 !== twentyseventeen_panel_count() || is_customize_preview() ) : // If we have pages to show.

  /**
   * Filter number of front page sections in Twenty Seventeen.
   *
   * @since Twenty Seventeen 1.0
   *
   * @param int $num_sections Number of front page sections.
   */
  $num_sections = apply_filters( 'twentyseventeen_front_page_sections', 4 );
global $twentyseventeencounter;

// Create a setting and control for each of the sections available in the theme.
for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
  $twentyseventeencounter = $i;
  twentyseventeen_front_page_section( null, $i );
}

endif; // The if ( 0 !== twentyseventeen_panel_count() ) ends here. ?>

  </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();
