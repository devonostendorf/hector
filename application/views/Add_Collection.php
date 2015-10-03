		<dd class="accordion-navigation">
			<a href="#add-collection">Add Collection</a>
<?php	
	echo '<div id="add-collection" class="content '.($accordion_group_open == TRUE ? ' active' : '').'">';
?>
				<fieldset>
<?php
	echo '<legend>'.$page_description.'</legend>';
	echo Form::open('Collection/add', array('class' => 'custom'));
?>	
					<div class="row">
						<div class="large-3 columns end">
<?php
	echo Form::label('selCollectionType', 'Type:');
	echo Form::select('selCollectionType', $collection_type_sel_arr, $post ? $post['selCollectionType'] : 0);
?>	
						</div>
					</div>
					<div class="row">
						<div class="large-8 columns end">
<?php	
	echo Form::label('txtCollectionName', 'Name:');
	echo Form::input('txtCollectionName', $post ? $post['txtCollectionName']: '', array('id' => 'id_txtCollectionName', 'size' => '35', 'maxlength' => '75', 'required' => true));
	if (array_key_exists('txtCollectionName', $errors))
	{
		echo '<small class="error">'.$errors['txtCollectionName'].'</small>';
	}
?>
						</div>
					</div>
					<br />
<?php
	echo Form::button('btnAdd', 'Add', array('class' => 'small button radius submit'));
	echo Form::close();
?>
				</fieldset>
			</div>
		</dd>
