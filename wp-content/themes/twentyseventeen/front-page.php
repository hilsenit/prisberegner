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
  endif; ?>
</div>
<hr>
<input id="ordTaeller" type="text" placeholder="Indtast antal ord.." /> 
<div class="price-cat-discount-wrapper">
  <?php
  $index = 0;
  if( have_rows('deadlines', 'option') ):
    while ( have_rows('deadlines', 'option') ) : the_row(); ?>
      <button class="price-cat-deadline-btn <?php echo ($index === 0) ? 'active' : '' ?>"
       data-discount="<?php the_sub_field('rabat'); ?>">
          <?php the_sub_field('periode'); ?>
      </button>
  <?php 
    $index++;
    endwhile;
  endif; ?>
</div>
<h2 id="priceCatPrice"></h2>

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

function changePrice(cons_name, data) {
  $("#priceCatPrice").html(window[cons_name].price_with_deadline(data.word_count, data.discount) + " kr. "); 
}

function getDiscountAndWordCount() { 
  let word_count = Number($("#ordTaeller").val()) || 0; // Returnere 0, hvis der ikke er indtastet noget endnu.
  let discount = Number($(".price-cat-discount-wrapper .active").data('discount'));
  return { word_count: word_count, discount: discount }
}

$(document).ready(function() {

  $(".price-cat-btn").each(function(i, price_cat) {
    window[price_cat.id] = new PriceCat(getData(price_cat.id));
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
    let active_price_cat_id = $(".price-cat-btn-wrapper .active").attr('id');
    changePrice(active_price_cat_id, getDiscountAndWordCount());
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
