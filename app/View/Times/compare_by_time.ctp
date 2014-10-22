<div class="times index">
	<h2><?php echo __($pointStationInfo[0] . ' <=> ' . $pointStationInfo[1]); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>待ち合せ駅</th>
			<th>待ち合せ予定時間</th>
	</tr>
    <?php foreach($meetupTimes as $meetupTime){ ?>
    <tr>
        <td><?php echo $this->Html->link($meetupTime['station'],
            'viewMeetup?stationTime_0=' . $meetupTime['station0']['id']
             . '&stationTime_1=' . $meetupTime['station1']['id']); ?></td>
        <td><?php echo $meetupTime['meetupTime']; ?></td>
    </tr>
    <?php } ?>
	</table>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Time'), array('action' => 'add')); ?></li>
	</ul>
</div>
