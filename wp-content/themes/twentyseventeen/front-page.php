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
        <button class="price-cat-btn js-price-cat-btn <?php echo ($index_for_cat == 0) ? 'active' : '' ?>" id="<?php the_sub_field('titel'); ?>"
         data-a="<?php the_sub_field('a'); ?>"
         data-b="<?php the_sub_field('b'); ?>"
         data-c="<?php the_sub_field('c'); ?>"
				 data-d="<?php the_sub_field('d'); ?>"
				 data-incl-feedback="<?php the_sub_field('incl_feedback'); ?>"
				 data-feedback-price="<?php the_sub_field('feedback_price'); ?>"
				 data-incl-info="<?php the_sub_field('incl_info'); ?>">
            <?php the_sub_field('titel'); ?>
        </button>
    <?php 
      $index_for_cat++;
      endwhile;
			endif; # price_cat have rows?
		?>
		

		<!-- Sprogkurser -->
		<?php if( have_rows('lang_courses', 'option') ): ?>
			<button class="price-cat-btn js-lang-courses" id="lang-courses-wrapper">Sprogkurser</button>
 			<div class="lang-courses-wrapper">
			<?php while ( have_rows('lang_courses', 'option' ) ): the_row(); ?>
				<div class="lang-course">
					<h4 class="text-center"><?php the_sub_field('course_title'); ?></h4>
					<p><?php the_sub_field('course_content'); ?></p>
					<h5 class="text-center">Pris: <?php the_sub_field('course_price'); ?> kr.</h5>
					<a class="text-center" href="<?php the_sub_field('course_link'); ?>">Læs mere</a>
				</div>
			<?php endwhile; ?>
			</div><!-- lang-course-wrapper -->
			<?php endif; ?>
  </div> <!-- price-cat-btn-wrapper -->
<hr>



	<!-- FEJLBESKED -->
  <div class="fejl-besked"></div>

	<div class="price-wrapper price-cat-output">
	<!--  INFO -->
	<div class="incl-info"></div>
	<h3 class="price-header text-center">Antal ord:</h3>
  <div class="ord-taeller-wrapper">
    <input id="ordTaeller" type="text" placeholder="Indtast.." /> 
  </div>

		<!-- DEADLINES -->
    <?php if( have_rows('price_cat', 'option') ): ?>
			<?php while ( have_rows('price_cat', 'option') ) : the_row(); ?>
				<div class="price-cat-discount-wrapper vis-ikke-dette <?php the_sub_field('titel'); ?>">
				<?php $index = 0; $parent = get_sub_field('titel'); 
				$deadline_type = get_sub_field('deadline_type'); 
				if( have_rows('deadlines', 'option') ):
					while ( have_rows('deadlines', 'option') ) : the_row(); ?>
				<button data-parent="<?php echo $parent ?>" 
							class="price-cat-deadline-btn js-price-cat-deadline-btn <?php echo ($index === 0) ? 'active' : '' ?>"
						 data-discount="<?php echo get_sub_field('rabat'); ?>"
						 data-deadline-type="<?php echo $deadline_type ?>"
						 data-string="<?php echo get_sub_field('periode'); ?>">
								<?php echo get_sub_field('periode'); ?>
								
						</button>
				<?php $index++; ?>
				<?php endwhile;  
			  endif; # deadlines_enabled && deadlines_val rows ? 
				?>
				</div><!-- price-cat-discount-wrapper -->
			<?php endwhile; ?>
		<?php endif; # price_cat have rows?
		?>
		<!-- DEADLINES END -->
		
	<div class="incl-feedback vis-ikke-dette">
			Inkl. en skriftlig feedback på teksten 

		<input class="js-activate-feedback" type="checkbox" id="inclFeedback" unchecked></input>
		Ja tak!
	</div>

	</div><!-- price-cat-output -->
	<div class="price-wrapper price-mail-output">
		<h2 id="priceCatPrice" data-price="0">Altid hurtig levering</h2>
		<div class="send-offer-wrapper">
			<input type="email" id="sendMailNow" placeholder="Indtast din mail..">
			<a href="#" id="sendOffer">Send mail med pris</a>
		</div>
	</div><!-- price-mail-output -->

</div><!-- price-cat-wrapper -->
</div><!-- grant-wrapper -->
<script>

function getData(id) {
  a = Number($("#" + id).data('a'));
  b = Number($("#" + id).data('b'));
  c = Number($("#" + id).data('c'));
  d = Number($("#" + id).data('d'));
	incl_feedback = $("#" + id).data('incl-feedback');
	feedback_price = $("#" + id).data('feedback-price');
  incl_info = $("#" + id).data('incl-info');

	return { 
		a: a, b: b, c: c, d: d,
	  incl_feedback: incl_feedback, incl_info: incl_info, feedback_price: feedback_price
	}
}

function PriceCat(data) {
	this.old_price = 0;
	this.old_word_count = 0;
	this.incl_feedback = data.incl_feedback;
	this.feedback_price = data.feedback_price;
	this.feedback_activated = false;
  this.incl_info = data.incl_info;
  this.a = data.a; this.b = data.b; this.c = data.c; this.d = data.d;
  this.price = function(number_of_words) {
    return (Math.round(number_of_words*this.a) - Math.pow(number_of_words*this.b, this.c)+1)+this.d;
  }
  this.price_with_deadline = function(word_count, discount) {
    if (word_count === 0) {
      return 0;
    } else {
      let discount_int = Number(discount) * 0.01; // For at procent skal blive et decimaltal: 10 * 0,01 = 0,1  
			if (this.feedback_activated) { // Inklusiv feedback ekstra pris
				return Math.round((this.price(word_count) * (1 - discount_int)) * (Number(this.feedback_price) * 0.01 + 1));
			} else {
				return Math.round(this.price(word_count) * (1 - discount_int));
			}
    }
  }
}

function getDiscountAndWordCount(parent) { 
  let word_count = Number($("#ordTaeller").val()) || 0; // Returnere 0, hvis der ikke er indtastet noget endnu.
  let discount = Number($("." + parent + " .active").data('discount') || 0);
  return { word_count: word_count, discount: discount }
}

function catChange(cons_name) { // Change back to the saved values, if there are any
	var constructor = window[cons_name];
	var new_price = constructor.old_price, new_word_count = constructor.old_word_count;
	$("#priceCatPrice").html(new_price + " kr. "); 
	$("#priceCatPrice").data('price', new_price);
	$("#ordTaeller").val(new_word_count);
	$("#sendOffer").data('active', cons_name);

	if (constructor.incl_feedback) { 
		$("#inclFeedback").data('parent', cons_name); // Needed for feedback discount
		$("#inclFeedback").removeAttr("checked");
		$(".incl-feedback").show(); 
	} else { 
		$(".incl-feedback").hide(); 
	}

	if (constructor.incl_info) {
		$(".incl-info").html(constructor.incl_info);
		$(".incl-info").show();
	} else { 
		$(".incl-info").hide(); 
	}
}

function changePrice(cons_name, data) {
	let new_price = window[cons_name].price_with_deadline(data.word_count, data.discount);
  $("#priceCatPrice").html(new_price + " kr. "); 
	$("#priceCatPrice").data('price', new_price);
}

function validateEmail(Email) {
    var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return $.trim(Email).match(pattern) ? true : false;
}

$(document).ready(function() {

  $(".js-price-cat-btn").each(function(i, price_cat) {
    window[price_cat.id] = new PriceCat(getData(price_cat.id)); // Gemmes under en global variabel med samme navn som knappens id
		if (i === 0) { 
			// changePrice(price_cat.id, getDiscountAndWordCount(price_cat.id)); // Sæt prisen ved første priskategori.
			$("." + price_cat.id).show(); // Vis deadlines, hvis der er nogle
		} 
  });

	$(".js-lang-courses").on("click", function() {
		$(".price-cat-output").hide();
		$(".lang-courses-wrapper").show(); 
    $(this).addClass('active').siblings().removeClass('active');
	});

	$(".js-activate-feedback").on("change", function() {
		let parent = $(this).data("parent");
		window[parent].feedback_activated = (this.checked) ? true : false // Setting constructor variable so the price will be with extra
		changePrice(parent, getDiscountAndWordCount(parent));
	});

  $(".js-price-cat-btn").click(function() { // Skifter pris kategori via klik
		$(".lang-courses-wrapper").hide();
		$(".price-cat-output").show();
		let old_category_id = $(".price-cat-btn-wrapper .active").attr('id');
		window[old_category_id].old_price = Number($("#priceCatPrice").data('price')) || 0; // Save old price
		window[old_category_id].old_word_count = Number($("#ordTaeller").val()) || 0; // Save old word count
    $(this).addClass('active').siblings().removeClass('active');
		$(".price-cat-discount-wrapper").hide(); // HIde all deadlines
		$("." + this.id).show(); // Show deadline if one has the same class as the category id
		catChange(this.id);
  });

  $(".js-price-cat-deadline-btn").click(function() { // Skifter deadline via klik
	 	let parent = $(this).data('parent');
    $(this).addClass('active').siblings().removeClass('active');
    changePrice(parent, getDiscountAndWordCount(parent));
  });

  $("#ordTaeller").on("input", function() { // Når der bliver tastet noget nyt ind i antal-ord feltet.
  let word_count = Number($("#ordTaeller").val()); 
    if ( word_count <= 200000 && $.isNumeric(word_count) ) { // Er det et tal, der bliver indtastet?
      $(".fejl-besked").hide();
      $(".send-offer-wrapper").slideDown(400); // Hvis mailfunktion, hvis antal ord er valid.
      let active_price_cat_id = $(".price-cat-btn-wrapper .active").attr('id');
      changePrice(active_price_cat_id, getDiscountAndWordCount(active_price_cat_id));
    } else if (word_count > 200000) {
      $(".send-offer-wrapper").slideUp(400); // Fjern mailfunktion, hvis antal ord ikke er valid
      $(".fejl-besked").html("Det maksimale antal ord er 200.000").show();
    } else {
      $(".send-offer-wrapper").slideUp(400); // Fjern mailfunktion, hvis antal ord ikke er valid
      $(".fejl-besked").html("Det indtastede skal være et tal. Prøv igen.").show();
    }
  });

	function = rabatType(type, val) {
		return switch type {
			case "deadline":
				"Afleveres tilbage om " + val;
				break;
			case "format":
				"Dokumentet er en " + val + " fil.";
				break;
			case "sprog":
				"Dokumentet skal oversættes til " + val;
				break;
		}
	}
  $("#sendOffer").on('click', function(e) { // Indsaml data og send mail.
    e.preventDefault();
		let active_cat = $(this).data("active"); // Take the data for the active category!
    var mail = $("#sendMailNow").val();
    var deadline_text = $("." + active_cat + " .active").data('string') || false;
		var deadline_type = $("." + active_cat +  " .active").data('deadline-type') || false;
    var email_validated = validateEmail(mail);
    var num_object = getDiscountAndWordCount(active_cat);
		if (deadline_type && deadline) {
			var rabat_text = rabatType(deadline_type, deadline_text);
		}
    var price = window[active_cat].price_with_deadline(num_object.word_count, num_object.discount);

    if (num_object.word_count && mail && price && email_validated) { // Hvis alle værdier er iorden
      window.location = 'mailto:' + mail + '?subject=' + active_cat + ' af A1Kommunikation' + 
        '&body=' + num_object.word_count + ' ords ' + price_cat + '. %0D%0A%0D%0A Pris: ' + price + ' kr.' + '%0D%0A' + 
         '.' + '%0D%0A%0D%0A' + 'Vedhæft venligst vedrørende fil/filer og indtast dit navn, adresse, CVR. nr. mm.';
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
