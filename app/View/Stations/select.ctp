<div class="stations index">
	<h2><?php echo __('Stations'); ?></h2>

<?php echo $this->Form->create('Station', array('type' => 'post',
'action' => 'compare')); ?>

<?php echo $this->Form->input('station_0', array(
    'type' => 'select',
    'options' => $select0)); ?>

<?php echo $this->Form->input('station_1', array(
    'type' => 'select',
    'options' => $select1)); ?>

<?php echo $this->Form->end('submit'); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Station'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Places'), array('controller' => 'places', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Place'), array('controller' => 'places', 'action' => 'add')); ?> </li>
	</ul>
</div>