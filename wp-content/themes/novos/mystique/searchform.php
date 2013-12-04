<?php /* Mystique/digitalnature */ ?>

<?php
 $searchquery = wp_specialchars(get_search_query(),1);
 if (!is_search() || (is_search() && have_posts() && ($searchquery && $searchquery!=__("Search","mystique")))):
  mystique_search_form();
 else: ?>
  <p class="aligncenter">
  <?php
   if(($searchquery) && ($searchquery!=__('Search',"mystique"))):
    printf(__("You have searched the archives for %s","mystique"), '<span class="altText"><strong>'.wp_specialchars(get_search_query(),1).'</strong></span>');
   else:
    _e("Type a valid search term...","mystique");
   endif;

  ?>
  </p>
<?php endif; ?>
