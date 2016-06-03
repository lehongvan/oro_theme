<?php

get_header();

list_post_by_taxonomy( 'pressroom_post', 'pressroom_tax' );


echo '<ul>';
$args_list = array(
 'taxonomy' => 'pressroom_tax',
 'show_count' => true,
 'hierarchical' => true,
 'echo' => '0',
);
echo wp_list_categories($args_list);
echo '</ul>';

get_footer();

?>
