<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap wde-wrap">

<h1 class="wp-heading-inline"><?php echo __('Blocks used on published posts/pages/templates but missing','blocks-detector-finder'); ?></h1>
<br /><br />
<table class="wp-list-table widefat fixed striped table-view-list pages">
<thead>
	<tr>
		<th><?php echo __('Block key','blocks-detector-finder'); ?></th>
    </tr>
</thead>

<?php if(count($blocks_missing) == 0): ?>
    <tr class="no-items"><td class="colspanchange" colspan="1"><?php echo __('No data found.','blocks-detector-finder'); ?></td></tr>
<?php endif; ?>

<?php foreach ( $blocks_missing as $widget_key => $posts ): 

?>
<tr>


<td>
<?php echo esc_html($widget_key); ?>
</td>



</tr>
<?php endforeach; ?>
</table>

<br style="clear:both;" />

</div>

<script>
 
// Generate table
jQuery(document).ready(function($) {

});

</script>

<style>

a#sync-plugin-data::before {
    color: #f56e28;
    content: "\f463";
    display: inline-block;
    font: normal 20px/1 dashicons;
    margin: 3px 5px 0 -2px;
    speak: none;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    vertical-align: top;
}

a.animate#sync-plugin-data::before {
    content: "\f463";
    animation: rotation 2s infinite linear;
}

div#log_place
{
    padding:15px 0px;

    color: blue;
}

</style>

<?php $this->view('general/footer', $data); ?>
