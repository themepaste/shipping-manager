<?php
defined('ABSPATH') || exit;

$add_url = home_url(add_query_arg(NULL, NULL)) . '&sub_page=add';
?>
<div class="tsm-row">
    <?php
    // WP_Query arguments
    $args = array(
        'post_type'              => array('tsm_rule'),
        'post_status'            => array('publish'),
        'post_per_page'          => 3
    );

    $tcp_query = new WP_Query($args);

    if ($tcp_query->have_posts()) {
        while ($tcp_query->have_posts()) {
            $tcp_query->the_post();
            // do something
    ?>
            <div class="tsm-col-3">
                <div class="tsm-first-box tsm-card">
                    <h2><?php the_title(); ?></h2>
                    <p><?php the_excerpt(); ?></p>
                    <a href="">Edit</a>
                </div>
            </div>
    <?php }
    } else {
        // no posts found
    }
    // Restore original Post Data
    wp_reset_postdata(); ?>
    
    <div class="tsm-col-3">
        <div class="tsm-add-box ">
            <a class='tsm-py-100' href="<?php echo $add_url ?>">ADD</a>
        </div>
    </div>
</div>