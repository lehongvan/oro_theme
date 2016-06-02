<?php get_header(); ?>

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <?php the_content(); ?>
  sdafasgas
  <?php endwhile; endif; ?>

  echo "single";

<?php  get_footer(); ?>
