		<dd class="accordion-navigation">
			<a href="#collections">View Collections</a>
<?php	
	echo '<div id="collections" class="content ' . ($accordion_group_open == TRUE ? ' active' : '') . '">';
?>
				<fieldset>
<?php
	echo '<legend>'.$page_description.'</legend>';
	if (count($collection_arr))
	{
		foreach ($collection_arr as $collection)
		{	
?>
					<div class="row">
						<div class="small-6 columns">
<?php
			echo HTML::anchor('Collection/edit/'.$collection['collection_id'], $collection['name']);			
?>
						</div>
						<div class="small-6 columns">
<?php   					
			echo '('.$collection['type'].')';
?>					  
						</div>
					</div>   					
<?php
		}
	}
	else
	{
		echo 'No collections found.  Click on "Add Collection" above to get started!<br />';	 
	}
?>
				</fieldset>
			</div>
		</dd>
