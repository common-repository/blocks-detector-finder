<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap wde-wrap">

    <h1 class="wp-heading-inline"><?php echo __('Used Images inside Elementor', 'blocks-detector-finder'); ?></h1>
    <br /><br />

    <form name="eli-filter" id="eli-filter" action="<?php echo get_admin_url() . "admin.php?page=bpdf_used_images"; ?>" method="get">
        <?php
        foreach ($_GET as $key => $value) {
            if (is_array($value)) continue;
            echo ("<input type='hidden' name='" . esc_attr(wmvc_xss_clean($key)) . "' value='" . esc_attr(wmvc_xss_clean($value)) . "'/>");
        }
        ?>
        <fieldset class="metabox-prefs">
            <legend><strong><?php echo __('Posts/Page text filter criteria or ID', 'blocks-detector-finder'); ?></strong></legend>
            <input type="text" id="eli-search-input" name="s" value="<?php echo esc_attr(wmvc_show_data('s', $_GET, '')); ?>">
        </fieldset>
        <p class="filter-submit"><input type="submit" class="button button-primary" value="<?php echo esc_attr(__('Filter', 'blocks-detector-finder')); ?>"></p>
    </form>

    <style>
        #eli-filter {
            background-color: #fff;
            border: 1px solid #ccd0d4;
            padding: 20px;
            margin: 0px;
            clear: both;
        }

        #eli-filter legend {
            padding: 5px 0px;
        }

        #eli-filter p.filter-submit {
            padding: 15px 0px 5px 0px;
            margin: 0px;
        }
    </style>
<br />
<p class="alert alert-info"><?php echo __('Some images if are not saved like urls in elementor structure will not be detected', 'blocks-detector-finder'); ?></p><br />
<p class="alert alert-info"><?php echo __('Unusual image filename means thats not lowercase, contain spacing, untitled, copy, screenshot words, what is not good for SEO and looks unprofesional', 'blocks-detector-finder'); ?></p><br />


    <?php foreach ($posts_list as $key => $post) :

        $page = $post['post_data'];
        $images_list = $post['images_list'];

    ?>

        <h2><?php echo esc_html($page->post_title); ?> #<?php echo esc_html($page->ID); ?>, 
        <?php echo esc_html($page->post_type); ?><?php if (count($images_list) == 0) : ?> - <?php echo __('Images not found', 'blocks-detector-finder'); ?><?php endif; ?></h2>

        <?php if (count($images_list) > 0) : 
            
            $position_url = get_permalink( $page->ID );

            if(strpos($position_url, '?') === FALSE)
            {
                $position_url = $position_url. "?bpdf_show";
            }
            else
            {
                $position_url = $position_url. "&amp;bpdf_show";
            }
            
            ?>
        <a class="button" href="<?php echo get_admin_url() . esc_url("post.php?post=$page->ID&action=edit"); ?>"> <?php echo __('Edit Page', 'blocks-detector-finder'); ?></a>
        <a class="button" href="<?php echo get_admin_url() . esc_url("post.php?post=$page->ID&action=elementor"); ?>"> <?php echo __('Edit in Elementor', 'blocks-detector-finder'); ?></a>
        <a class="button" href="<?php echo esc_url( $position_url ); ?>"> <?php echo __('View page and element positions', 'blocks-detector-finder'); ?></a>
        <?php endif; ?>
        <br /><br />

        <?php if (count($images_list) > 0) : ?>
            <table class="wp-list-table widefat fixed striped table-view-list pages">
                <thead>
                    <tr>
                        <th><?php echo __('Image', 'blocks-detector-finder'); ?></th>
                        <th><?php echo __('File name', 'blocks-detector-finder'); ?></th>
                        <th><?php echo __('Size', 'blocks-detector-finder'); ?></th>
                        <th><?php echo __('Resolution', 'blocks-detector-finder'); ?></th>
                        <th><?php echo __('Messages', 'blocks-detector-finder'); ?></th>
                    </tr>
                </thead>

                <?php
                foreach ($images_list as $image_key => $image) :


                        
                ?>
                    <tr class="<?php if (FALSE) echo 'red missing' ?>">

                        <td class="img-col">
                            <a href="<?php echo esc_url($image['url']); ?>" target="_blank"><img class="small_img" src="<?php echo esc_url($image['url']); ?>" alt="" /></a>
                        </td>

                        <td>
                            <?php echo esc_html($image['filename']); ?>
                        </td>

                        <td>
                            <?php echo esc_html($image['size']); ?>
                        </td>

                        <td>
                            <?php echo esc_html($image['resolution']); ?>
                        </td>

                        <td>
                        
                        <?php foreach($image['red_messages'] as $message): ?>
                            <span style="color:red;"><?php echo esc_html($message);?></span><br />                        
                        <?php endforeach; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>


        <?php endforeach; ?>

        <div class="tablenav bottom">
            <div class="alignleft actions">
            </div>
            <?php echo wp_kses_post($pagination_output); ?>
            <br class="clear">
        </div>

</div>


<?php

//wp_enqueue_style('bpdf_basic_wrapper');

?>

<script>
    // Generate table
    var bpdf_defined_limit = 20;

    jQuery(document).ready(function($) {
        $('.bpdf_show_more').each(function( index ) {
            var total_elements = $( this ).find('label').length;

            if(total_elements >= bpdf_defined_limit)
            {
                $(this).find('label:nth-child(n+'+Number(bpdf_defined_limit+2)+')').hide();
                $(this).append("<a class='bpdf_show_all' href='#'><?php echo wmvc_js(__('Show all...','blocks-detector-finder')); ?></a>");
                $(this).append("<a class='bpdf_hide_bit' href='#'><?php echo wmvc_js(__('Hide a bit...','blocks-detector-finder')); ?></a>");
                $(this).find('a.bpdf_hide_bit').hide();
                $(this).find('a.bpdf_show_all').on('click', function(){
                    $(this).parent().find('label').show();
                    $(this).parent().find('a.bpdf_show_all').hide();
                    $(this).parent().find('a.bpdf_hide_bit').show();
                    return false;
                });
                $(this).find('a.bpdf_hide_bit').on('click', function(){
                    $(this).parent().find('label:nth-child(n+'+Number(bpdf_defined_limit+2)+')').hide();
                    $(this).parent().find('a.bpdf_hide_bit').hide();
                    $(this).parent().find('a.bpdf_show_all').show();
                    return false;
                });

            }

        });
    });
</script>

<style>
    table.wp-list-table tr.red td {
        color: red;
    }

    .small_img
    {
        max-width: 100px;
        max-height: 100px;
    }

    table.wp-list-table td.img-col a
    {
       display: inline-block;
        padding: 0px;
        margin: 0px;
    }
</style>

<?php $this->view('general/footer', $data); ?>