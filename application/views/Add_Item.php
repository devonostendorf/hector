		<dd class="accordion-navigation">
			<a href="#add-item">Add Item</a>
<?php	
	echo '<div id="add-item" class="content '.($accordion_group_open == TRUE ? ' active' : '').'">';
?>
				<fieldset>
<?php
	echo '<legend>'.$page_description.'</legend>';
	echo Form::open('Item/add/'.$collection_id, array('class' => 'custom'));
?>	
					<div class="row">
						<div class="large-2 columns end">
<?php
	echo Form::label('txtItemSeq', 'Sequence:');
	echo Form::input('txtItemSeq', $post ? $post['txtItemSeq']: '', array('id' => 'id_txtItemSeq', 'size' => '35', 'maxlength' => '3', 'required' => true));
	if (array_key_exists('txtItemSeq', $errors))
	{
		echo '<small class="error">'.$errors['txtItemSeq'].'</small>';
	}
?>	
						</div>
					</div>
					<div class="row">
						<div class="large-8 columns end">
<?php	
	echo Form::label('txtItemName', 'Name:');
	echo Form::input('txtItemName', $post ? $post['txtItemName']: '', array('id' => 'id_txtItemName', 'size' => '35', 'maxlength' => '75', 'required' => true));
	if (array_key_exists('txtItemName', $errors))
	{
		echo '<small class="error">'.$errors['txtItemName'].'</small>';
	}
?>	
						</div>
					</div>
					<div class="row">
						<div class="large-3 columns end">
<?php	
	echo Form::label('selItemStatus', 'Status:');
	echo Form::select('selItemStatus', $item_status_sel_arr, $post ? $post['selItemStatus'] : 0);
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
