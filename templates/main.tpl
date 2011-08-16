<h1>Control panel</h1>
<table>
<tr>
	<td class="header" colspan="3">Your characters</td></tr>
<?php foreach  ($characters['result']['rowset']['row'] as $character) { ?>
<tr>
	<td><img src="http://image.eveonline.com/Character/<?php echo $character['@attributes']['characterID']; ?>_64.jpg"</td>
	<td><img src="http://image.eveonline.com/Corporation/<?php echo $character['@attributes']['corporationID']; ?>_64.png"</td>
	<td>
		<b>Name:</b> <?php echo $character['@attributes']['name']; ?><br/>
		<b>Corporation:</b> <?php echo $character['@attributes']['corporationName']; ?>
	</td>
</tr>
<?php } ?>
</table>