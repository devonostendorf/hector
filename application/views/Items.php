<dd class="accordion-navigation">
	<a href="#items">View Items</a>
<?php	
	echo '<div id="items" class="content '.($accordion_group_open == TRUE ? ' active' : '').'">';
?>
		<fieldset>
<?php
	echo '<legend>'.$page_description.'</legend>';
	if (count($items_arr))
	{
		foreach ($items_arr as $item)
		{	
?>
			<div class="row">
				<div class="small-6 columns">
<?php
			echo HTML::anchor('Item/edit/'.$item['collection_id'].'/'.$item['seq'], $item['descr']);			
?>
				</div>
				<div class="small-6 columns">
<?php   					
			echo $item_status_sel_arr[$item['status']];
?>					  
				</div>
			</div>   					
<?php
		}
	}
	else
	{
		echo 'No items found.  Click on "Add Item" above to get started adding to your collection!<br />';	 
	}
?>
		</fieldset>
	</div>
</dd>
