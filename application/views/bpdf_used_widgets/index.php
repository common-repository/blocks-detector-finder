<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap wde-wrap">

    <h1 class="wp-heading-inline"><?php echo __('Used Blocks', 'blocks-detector-finder'); ?></h1>
    <br /><br />

    <?php if(FALSE): ?>
    <ul class="subsubsub">
        <li><a class="current" href="<?php echo get_admin_url() . "admin.php?page=bpdf_used_widgets"; ?>"><?php echo __('Per Page', 'blocks-detector-finder'); ?></a> |</li>
        <li><a href="<?php echo get_admin_url() . "admin.php?page=bpdf_used_widgets&function=general"; ?>"><?php echo __('All in General', 'blocks-detector-finder'); ?></a></li>
    </ul>
    <br /><br />
    
    <form name="eli-filter" id="eli-filter" action="<?php echo get_admin_url() . "admin.php?page=bpdf_used_widgets"; ?>" method="get">
        <?php
        foreach ($_GET as $key => $value) {
            if (is_array($value)) continue;
            echo ("<input type='hidden' name='" . esc_attr(wmvc_xss_clean($key)) . "' value='" . esc_attr(wmvc_xss_clean($value)) . "'/>");
        }
        ?>

        <fieldset class="metabox-prefs bpdf_show_more">
            <legend><strong><?php echo __('EL Widget Categories', 'blocks-detector-finder'); ?></strong></legend>
            <?php foreach ($widget_categories as $category_key => $category_name) : ?>
                <label style="<?php echo isset($category_colors[$category_key])?'color:'.esc_html($category_colors[$category_key]).';':'';  ?>"><input class="hide-column-tog" name="show_categories[]" type="checkbox" id="cat_<?php echo esc_attr($category_key); ?>" value="<?php echo esc_html($category_key); ?>" <?php echo in_array($category_key, $show_categories) ? 'checked' : ''; ?>><?php echo esc_html($category_name); ?></label>
            <?php endforeach; ?>
        </fieldset>
        <fieldset class="metabox-prefs bpdf_show_more">
            <legend><strong><?php echo __('EL Widgets', 'blocks-detector-finder'); ?></strong></legend>
            <?php foreach ($widgets_exists_title as $widget_key_ar => $widget) : ?>
                <?php if (!empty($widget_key_ar)) :
                    $widget_key = $block->get_name();
                    $widget_title = $block->get_title();
                    if (empty($widget_title)) $widget_title = $widget_key;

                    $categories = $block->get_categories();
                    $category_key = '';
                    if(isset($categories[0]))
                        $category_key = $categories[0];

                ?>
                    <label style="<?php echo isset($category_colors[$category_key])?'color:'.$category_colors[$category_key].';':'';  ?>"><input class="hide-column-tog" name="show_widgets[]" type="checkbox" id="wid_<?php echo esc_attr($widget_key); ?>" value="<?php echo esc_attr($widget_key); ?>" <?php echo in_array($widget_key, $show_widgets) ? 'checked' : ''; ?>><?php echo esc_html($widget_title); ?></label>
                <?php endif; ?>
            <?php endforeach; ?>
        </fieldset>
        <fieldset class="metabox-prefs bpdf_show_more">
            <legend><strong><?php echo __('Post types', 'blocks-detector-finder'); ?></strong></legend>
            <?php foreach ($post_types_available as $post_type_name => $post_type_label) : ?>
                <?php if (!empty($post_type_name)) :


                ?>
                    <label style=""><input class="hide-column-tog" name="show_post_types[]" type="checkbox" id="pt_<?php echo esc_attr($post_type_name); ?>" value="<?php echo esc_attr($post_type_name); ?>" <?php echo in_array($post_type_name, $show_post_types) ? 'checked' : ''; ?>><?php echo esc_html($post_type_label).', '.esc_html($post_type_name); ?></label>
                <?php endif; ?>
            <?php endforeach; ?>
        </fieldset>
        <fieldset class="metabox-prefs">
            <legend><strong><?php echo __('Text criteria', 'blocks-detector-finder'); ?></strong></legend>
            <input type="text" id="eli-search-input" name="s" value="<?php echo esc_attr(wmvc_show_data('s', $_GET, '')); ?>">
        </fieldset>
        <p class="filter-submit"><input type="submit" name="screen-options-apply" id="screen-options-apply" class="button button-primary" value="<?php echo esc_attr(__('Filter', 'blocks-detector-finder')); ?>"></p>
    </form>
    <?php endif; ?>

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



    <?php foreach ($posts_list as $key => $post) :

        $page = $post['post_data'];
        $blocks_list = $post['blocks_list'];

    ?>

        <h2><?php echo esc_html($page->post_title); ?> #<?php echo esc_html($page->ID); ?>, 
        <?php echo esc_html($page->post_type); ?><?php if (count($blocks_list) == 0) : ?> - <?php echo __('Blocks not found', 'blocks-detector-finder'); ?><?php endif; ?></h2>

        <?php if (count($blocks_list) > 0) : 
            

            $page_edit_url = get_admin_url() . "post.php?post=$page->ID&action=edit";

            $position_url = get_permalink( $page->ID );

            if(strpos($position_url, '?') === FALSE)
            {
                $position_url = $position_url. "?bpdf_show";
            }
            else
            {
                $position_url = $position_url. "&amp;bpdf_show";
            }

            $current_theme = get_stylesheet();

            if($page->post_type == 'wp_template')
            {
                $page_edit_url = get_admin_url() . "site-editor.php?postType=$page->post_type&postId=$current_theme%2F%2F$page->post_name";
                $position_url = NULL;
            }
            elseif($page->post_type == 'wp_template_part')
            {
                $page_edit_url = get_admin_url() . "site-editor.php?postType=$page->post_type&postId=$current_theme%2F%2F$page->post_name";
                $position_url = NULL;
            }

            ?>
            <a class="button" href="<?php echo esc_url($page_edit_url); ?>"> <?php echo __('Edit Page', 'blocks-detector-finder'); ?></a>
            <?php if(!empty($position_url)): ?>
            <a class="button" href="<?php echo esc_url( $position_url ); ?>"> <?php echo __('View page', 'blocks-detector-finder'); ?></a>
            <?php endif; ?>
        <?php endif; ?>
        <br /><br />

        <?php if (count($blocks_list) > 0) : ?>
            <table class="wp-list-table widefat fixed striped table-view-list pages">
                <thead>
                    <tr>
                    <th><?php echo __('Block Name','blocks-detector-finder'); ?></th>
                    <th><?php echo __('Block Title','blocks-detector-finder'); ?></th>
                    <th><?php echo __('Block Category','blocks-detector-finder'); ?></th>
                    <th><?php echo __('Plugin','blocks-detector-finder'); ?></th>
                    </tr>
                </thead>

                <?php
                foreach ($blocks_list as $widget_key => $block) :

                    if(isset($block))
                    {
                        $category = $block->category;

                        $plugin_name = '';
                        if(isset($plugins_list[$block->name]))
                        {
                            $plugin_name = $plugins_list[$block->name];
                        }
                    }

                        
                ?>
                    <tr class="<?php if (!isset( $block) || !is_object($block)) echo 'red missing' ?>">

                        <td>
                        <?php if(isset($block->name)): ?>
                            <?php echo esc_html($block->name); ?>
                        <?php else: ?>
                            <?php echo esc_html($widget_key); ?>
                        <?php endif; ?>
                        </td>

                        <td>
                        <?php if(isset($block->title)): ?>
                            <?php echo esc_html($block->title); ?>
                        <?php endif; ?>
                        </td>

                        <td>
                        <?php if(isset($block->category)): ?>
                            <?php echo esc_html($block->category); ?>
                        <?php endif; ?>
                        </td>

                        <td>
                        <?php if(isset($plugin_name)): ?>
                            <?php echo esc_html($plugin_name); ?>
                            <?php endif; ?>
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
</style>

<?php $this->view('general/footer', $data); ?>