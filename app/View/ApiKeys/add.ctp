<div class="apiKeys form">
<?php echo $this->Form->create('ApiKey'); ?>
	<fieldset>
		<legend><?php echo __('Add Api Key'); ?></legend>
	<?php
		echo $this->Form->input('apikey');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Api Keys'), array('action' => 'index')); ?></li>
	</ul>
</div>
