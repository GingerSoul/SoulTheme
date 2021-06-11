<?php
/**
 * @package GinerSoul
 * @subpackage SoulThemes
 * @since 2.0
 */

get_header();
?>
<div class="wrap" role="document">
    <main class="main">
    <?php do_action('soultheme_before_content'); ?>

<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); global $post; ?>
        <article <?php post_class(); ?>>
            <?php if (is_singular()): ?>
                <?php the_content(); ?>
            <?php else: ?>
                <header><h2 class="heading--medium"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2></header>
                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
<?php else: ?>
    <div class="container">
        <div class="alert alert-warning">
            <?php _e(is_404() ?
                'Sorry, but the page you were trying to view does not exist.' :
                'Sorry, no results were found.', 'soultheme');
            ?>
        </div>
    </div>
<?php endif; ?>
    <?php do_action('soultheme_after_content'); ?>
<?php the_posts_navigation(); ?>
    <?php do_action('soultheme_after_navigation'); ?>

    </main>
</div>
<?php

get_footer();