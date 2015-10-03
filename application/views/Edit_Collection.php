				<fieldset>
<?php
	echo '<legend>'.$page_description.'</legend>';
	echo Form::open('Collection/update/'.$collection_arr['collection_id'], array('class' => 'custom'));
?>	
					<div class="row">
						<div class="large-4 columns end">
<?php
	echo Form::label('selCollectionType', 'Type:');
	echo Form::select('selCollectionType', $collection_type_sel_arr, $post ? $post['selCollectionType'] : $collection_arr['type_id']);
?>	
						</div>
					</div>
					<div class="row">
						<div class="large-8 columns end">
<?php	
	echo Form::label('txtCollectionName', 'Name:');
	echo Form::input('txtCollectionName', $post ? $post['txtCollectionName'] : $collection_arr['name'], array('id' => 'id_txtCollectionName', 'size' => '35', 'maxlength' => '75', 'required' => true));
	if (array_key_exists('txtCollectionName', $errors))
	{
		echo '<small class="error">'.$errors['txtCollectionName'].'</small>';
	}
?>
						</div>
					</div>
					<br />
					<div class="row">
						<div class="small-6 medium-3 large-2 columns">
<?php
	echo Form::button('btnUpdate', 'Update', array('class' => 'small button radius submit'));
?>
						</div>
						<div class="small-6 medium-9 large-10 columns">
<?php
	echo Form::button('btnDelete', 'Delete', array('class' => 'small button radius secondary submit'));
?>
						</div>
					</div>
<?php	
	echo Form::close();
	echo HTML::anchor('Collection/view', '<< Back to Collections');
?>
				</fieldset>
