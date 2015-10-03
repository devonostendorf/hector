				<br />
				<div class="row">
					<div class="small-5 medium-2 columns">
<?php
	echo '&nbsp;&nbsp;&nbsp;&nbsp;<strong>Collection:</strong> ';
?>
					</div>
					<div class="small-7 medium-10 columns">
<?php
	echo $coll_and_item_arr['Collection']['name']
		.' ('.$coll_and_item_arr['Collection']['type'].')'
	;
?>
					</div>
				</div>
				<fieldset>
<?php
	echo '<legend>'.$page_description.'</legend>';
	echo Form::open('Item/update/'.$collection_id.'/'.$seq, array('class' => 'custom'));
?>	
					<div class="row">
						<div class="large-2 columns end">
<?php
	echo Form::label('txtItemSeq', 'Sequence:');
	echo Form::input('txtItemSeq', $post ? $post['txtItemSeq']: $coll_and_item_arr['Item']['seq'], array('id' => 'id_txtItemSeq', 'size' => '35', 'maxlength' => '3', 'required' => true));
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
	echo Form::input('txtItemName', $post ? $post['txtItemName']: $coll_and_item_arr['Item']['descr'], array('id' => 'id_txtItemName', 'size' => '35', 'maxlength' => '75', 'required' => true));
	if (array_key_exists('txtItemName', $errors))
	{
		echo '<small class="error">'.$errors['txtItemName'].'</small>';
	}
?>	
						</div>
					</div>
					<div class="row">
						<div class="large-2 columns end">
<?php	
	echo Form::label('selItemStatus', 'Status:');
	echo Form::select('selItemStatus', $item_status_sel_arr, $post ? $post['selItemStatus'] : $coll_and_item_arr['Item']['status']);
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
	echo HTML::anchor('Collection/edit/'.$collection_id, '<< Back to Items');
?>
				</fieldset>
