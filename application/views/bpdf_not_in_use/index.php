<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap wde-wrap">

<h1 class="wp-heading-inline"><?php echo __('Blocks not used on posts/pages/templates','blocks-detector-finder'); ?> (<?php echo count($blocks_not_used); ?>)</h1>
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

<?php if(count($blocks_not_used) == 0): ?>
    <tr class="no-items"><td class="colspanchange" colspan="4"><?php echo __('No data found.','blocks-detector-finder'); ?></td></tr>
<?php endif; ?>

<?php foreach ( $blocks_not_used as $block_key => $block ): 

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

<?php $this->view('general/footer', $data); ?>
