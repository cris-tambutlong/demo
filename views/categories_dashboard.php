<?php $page_data = array('page_title' => 'Categories Dashboard - Development Solution') ?>
<?php $this->load->view('header', $page_data); ?>

<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <div>
		
		<?php 
			$form_attrib = array(
								'name' => 'frm_dashboard',
								'onsubmit' => 'return checkSubmit()'
								);
			
			echo form_open('categories/process_bulk_action', $form_attrib); ?>
		<?php 
			$hidden_attrib = array(
				'action' => '',
				'activation_option' => ''
			);
			echo form_hidden($hidden_attrib); 
		?>
		<h2 class="text-center">Categories Dashboard - Development Test Solution</h2>
        <table class="table table-bordered table-hover">
          <caption>
			<p class="text-center pad-bottom head-caption">
				Download the files used in this solution from <a href="javascript://" onclick="comingSoon()">here</a>.
			</p>
			<?php
				$dropdown_option = array(
					'' => 'Bulk Category Actions',
					'toggle' => 'Enable / Disable (Toggle)',
					'move_to_trash' => 'Move to Trash'
				);
				
				$chk_action1 = form_dropdown('chk_action1', $dropdown_option, '', 'class="form-control auto-width"');
				$chk_action2 = form_dropdown('chk_action2', $dropdown_option, '', 'class="form-control auto-width"');
				$btn_apply = form_submit('btn_apply','Apply Action', 'class="btn btn-primary"');
				
				$link_option = array(
					'title' => 'Add Category',
					'class' => 'btn btn-primary',
				);

				$lnk_add = anchor(base_url().'categories/add_category', 'Add Category', $link_option);
			?>
			<div class="row">
				<div class="col-xs-8 text-left">
					<?php echo $chk_action1, $btn_apply ?>
					<span class="trash-bin-container">
						<i class="fa fa-trash-o trash-bin"></i> You have (<a href="<?php echo base_url() ?>categories/trash_bin"><?php echo $trashed_count ?></a>) items in your Trash Bin.
					</span>
				</div>
				<div class="col-xs-4 text-right">
					<?php echo $lnk_add ?>
				</div>
			</div>
		  </caption>
		  <colgroup>
            <col class="col-md-1">
            <col>
			<col class="col-md-2">
		  </colgroup>
		  <thead>
            <tr>
              <th class="text-center"><?php echo form_checkbox('select_all', '', FALSE); ?></th>
              <th>Category Name</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
			<?php 
				if (( ! empty($categories)) || ( ! empty($disabled_categories))) 
				{ 
					echo $categories . $disabled_categories;				
				}
				else
				{
			?>
					<tr>
					  <td colspan="3" class="text-center">There are currently no active or disabled categories found.</td>
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
					<span class="trash-bin-container">
						<i class="fa fa-trash-o trash-bin"></i> You have (<a href="<?php echo base_url() ?>categories/trash_bin"><?php echo $trashed_count ?></a>) items in your Trash Bin.
					</span>
				</div>
				<div class="col-xs-4 text-right">
					<?php echo $lnk_add ?>
				</div>
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