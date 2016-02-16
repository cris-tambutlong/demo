<?php $page_data = array('page_title' => 'Trash Bin - Development Solution') ?>
<?php $this->load->view('header', $page_data); ?>

<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <div>
		
		<?php 
			$form_attrib = array(
								'name' => 'frm_trash',
								'onsubmit' => 'return checkFormTrashedSubmit()'
								);
			
			echo form_open('categories/process_bulk_action', $form_attrib); ?>
		<?php 
			$hidden_attrib = array(
				'action' => ''
			);
			echo form_hidden($hidden_attrib); 
		?>
		<h2 class="text-center">Trash Bin - Development Test Solution</h2>
        <table width="90%" class="table table-bordered table-hover">
          <caption>
			<p class="text-center pad-bottom head-caption">
				To go back to the categories dashboard click <a href="<?php echo base_url() ?>">here</a>.
			</p>
			<?php
				$dropdown_option = array(
					'' => 'Bulk Category Actions',
					'restore' => 'Restore',
					'delete' => 'Delete Permanently'
				);
				
				$chk_action1 = form_dropdown('chk_action1', $dropdown_option, '', 'class="form-control auto-width"');
				$chk_action2 = form_dropdown('chk_action2', $dropdown_option, '', 'class="form-control auto-width"');
				$btn_apply = form_submit('btn_apply','Apply Action', 'class="btn btn-primary"');
			?>
			<div class="row">
				<div class="col-xs-8 text-left">
					<?php echo $chk_action1, $btn_apply ?>
				</div>
				<div class="col-xs-4 text-right"></div>
			</div>
		  </caption>
		  <colgroup>
            <col class="col-md-1">
            <col>
		  </colgroup>
		  <thead>
            <tr>
              <th class="text-center"><?php echo form_checkbox('select_all', '', FALSE); ?></th>
              <th>Category Name</th>
            </tr>
          </thead>
          <tbody>
			<?php 
				if ( ! empty($trashed_categories)) 
				{ 
					echo $trashed_categories;				
				}
				else
				{
			?>
					<tr>
					  <td colspan="2" class="text-center">There are currently no items in your Trash Bin.</td>
					</tr>
			<?php
				}
			?>
          </tbody>
        </table>
		<div class="footer-buttons">
			<div class="row">
				<div class="col-xs-8 text-left">
					<?php echo $chk_action2, $btn_apply ?>
				</div>
				<div class="col-xs-4 text-right"></div>
			</div>
		</div>
		<p class="text-center pad-bottom">
			<td colspan="5" class="text-center"></td>
		</p>
		
		</form>
		
      </div>   
    </div>
  </div>
</div>

<?php $this->load->view('footer'); ?>