<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap wde-wrap">

<h1 class="wp-heading-inline"><?php echo __('Installed Blocks','blocks-detector-finder'); ?> (<?php echo count($existing_blocks); ?>)</h1>
<br /><br />
<table class="wp-list-table widefat fixed striped table-view-list pages">
<thead>
	<tr>
		<th><?php echo __('Block Name','blocks-detector-finder'); ?></th>
        <th><?php echo __('Block Title','blocks-detector-finder'); ?></th>
        <th><?php echo __('Block Category','blocks-detector-finder'); ?></th>
        <th><?php echo __('Plugin','blocks-detector-finder'); ?></th>
    </tr>
</thead>

<?php if(count($existing_blocks) == 0): ?>
    <tr class="no-items"><td class="colspanchange" colspan="4"><?php echo __('No data found.','blocks-detector-finder'); ?></td></tr>
<?php endif; ?>

<?php foreach ( $existing_blocks as $block_key => $block ): 

    $plugin_name = '';
    if(isset($block->plugin_name))
        $plugin_name = $block->plugin_name;

?>
<tr>

<td>
<?php if(isset($block->name)): ?>
    <?php echo esc_html($block->name); ?>
<?php endif; ?>
</td>

<td>
<?php echo esc_html($block->title); ?>
</td>

<td>
<?php echo esc_html($block->category); ?>
</td>

<td>
<?php echo esc_html($plugin_name); ?>
</td>

</tr>
<?php endforeach; ?>
</table>

</div>

<?php

wp_enqueue_script(array('react', 'react-dom', 'wp-api-fetch', 'wp-block-editor', 'wp-blocks', 'wp-block-directory', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-hooks', 'wp-i18n'));

wp_enqueue_script('wp-edit-post');
wp_enqueue_script('wp-block-library');
wp_enqueue_script('wp-block-editor');
wp_enqueue_script('wp-blocks');
wp_enqueue_script('wp-i18n');
wp_enqueue_script('wp-element');
wp_enqueue_script('wp-editor');

?>

<script>
 
// Generate table
jQuery(document).ready(function($) {


});

</script>


<style>
</style>

<?php $this->view('general/footer', $data); ?>
