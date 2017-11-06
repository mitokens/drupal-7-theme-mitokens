
<?php if ($page['header']): ?>
	<?=render($page['header']);?>
<?php endif; ?>

<?php if ($messages): ?>
	<div id="page-messages"><?=$messages;?></div>
<?php endif; ?>

<?php if ($page['column_first'] || $page['column_content'] || $page['column_last']): ?>
	<div id="page-columns" class="region region-columns <?=$column_count;?> <?=$column_order;?> clearfix">
		<?php if ($page['column_first']): ?>
			<?=render($page['column_first']);?>
		<?php endif; ?>
		<?php if ($page['column_content']): ?>
			<?=render($page['column_content']);?>
		<?php endif; ?>
		<?php if ($page['column_last']): ?>
			<?=render($page['column_last']);?>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ($page['footer']): ?>
	<?=render($page['footer']);?>
<?php endif; ?>
