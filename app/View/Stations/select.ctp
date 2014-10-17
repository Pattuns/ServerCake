<div class="stations index">
	<h2><?php echo __('Stations'); ?></h2>

<?php echo $this->Form->create('Station', array('type' => 'get',
'action' => 'compareByFare.json')); ?>

<?php echo $this->Form->input('station_0', array(
    'type' => 'select',
    'options' => $select0)); ?>

<?php echo $this->Form->input('station_1', array(
    'type' => 'select',
    'options' => $select1)); ?>

<?php echo $this->Form->end('Fare'); ?>

<br />

<?php echo $this->Form->create('Station', array('type' => 'get',
'action' => 'compareByDistance.json')); ?>

<?php echo $this->Form->input('station_0', array(
    'type' => 'select',
    'options' => $select0)); ?>

<?php echo $this->Form->input('station_1', array(
    'type' => 'select',
    'options' => $select1)); ?>

<?php echo $this->Form->end('Distance'); ?>

<br />

<?php echo $this->Form->create('Station', array('type' => 'get',
'action' => 'viewInsideFacility.json')); ?>

<?php echo $this->Form->input('station', array(
    'type' => 'select',
    'options' => $select0)); ?>

<?php echo $this->Form->end('InsideFacility'); ?>

<br />

<?php echo $this->Form->create('Station', array('type' => 'get',
'action' => 'viewOutsideFacility.json')); ?>

<?php echo $this->Form->input('station', array(
    'type' => 'select',
    'options' => $select0)); ?>

<?php echo $this->Form->end('OutsideFacility'); ?>

<br />

<?php echo $this->Form->create('Station', array('type' => 'get',
'action' => 'viewCoordinateById.json')); ?>

<?php echo $this->Form->input('station', array(
    'type' => 'select',
    'options' => $select0)); ?>

<?php echo $this->Form->end('Coordinate By Id'); ?>

<br />

<?php echo $this->Form->create('Station', array('type' => 'get',
'action' => 'viewCoordinateByName.json')); ?>

<?php echo $this->Form->input('station', array(
    'type' => 'select',
    'options' => $name)); ?>

<?php echo $this->Form->end('Coordinate By Name'); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Station'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Places'), array('controller' => 'places', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Place'), array('controller' => 'places', 'action' => 'add')); ?> </li>
	</ul>
</div>
