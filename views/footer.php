<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha256-KXn5puMvxCw+dAYznun+drMdG1IFl3agK0p/pqT9KAo= sha512-2e8qq0ETcfWRI4HJBzQiA3UoyFk6tbNyG+qSaIBZLyW9Xf3sWZHN/lxe9fTh1U45DpPf07yj94KsUHHWe4Yk1A==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
<script>
	jQuery(document).ready(function(){
		
		//Implementing the select all function by toggling the
		//category checkbox's checked state.
		
		jQuery('input[name="select_all"]').click(function() {
			var checkBoxes = jQuery("input.category-item");
			checkBoxes.prop("checked", !checkBoxes.prop("checked"));
		});
		
		//We're listening to the change event of the chk_action1 and chk_action1 dropdown elements
		//and basing on its current value we then update/set the action hidden field's value based
		//on the currently selected option.
		
		jQuery('select[name="chk_action1"], select[name="chk_action2"]').on('change', function() {
			var selected_value = jQuery(this).val();
			jQuery('input[name="action"]').val(selected_value);
		});
	});
	
	/**
	 * askDisable Function
	 *
	 * Displays a dialog box asking for confirmation for the disable action. 
	 *
	 * @param int
	 * @param string
	 */
	function askDisable(id, name)
	{
		//We're using BootstrapDialog plugin to display a confirmation box to the user
		//to confirm his or her decision for disabling a certain category or categories.
		//
		//The option fields in the box are self explanatory, so no need to discuss further.
		
		BootstrapDialog.confirm({
            title: 'WARNING',
            message: 'Are you sure you want to disable "' + name + '"?',
            type: BootstrapDialog.TYPE_PRIMARY,
            btnOKLabel: 'Please Proceed',
            callback: function(result) {
                if(result) {
					//If the user agrees, then we proceed with the process by calling the toggle_active_status
					//action of the categories controller to process the action.
                    
					document.location.href = '<?php echo base_url() ?>categories/toggle_active_status/'+id;
                }
            }
        });
	}
	
	/**
	 * askMove Function
	 *
	 * Displays a dialog box asking for confirmation for the move to trash bin action.
	 *
	 * @param int
	 * @param string
	 */
	function askMove(id, name)
	{
		//We're using BootstrapDialog plugin to display a confirmation box to the user
		//to confirm his or her decision for moving a certain category or categories to the trash bin.
		//
		//The option fields in the box are self explanatory, so no need to discuss further.
		
		BootstrapDialog.confirm({
            title: 'WARNING',
            message: 'Are you sure you want to move "' + name + '" to the Trash Bin?',
            type: BootstrapDialog.TYPE_PRIMARY,
            btnOKLabel: 'Please Proceed',
            callback: function(result) {
                if(result) {
					//If the user agrees, then we proceed with the process by calling the toggle_active_status
					//action of the categories controller to process the action.
					
                    document.location.href = '<?php echo base_url() ?>categories/move_category_to_trash/'+id;
                }
            }
        });
	}
	
	/**
	 * checkSubmit Function
	 *
	 * This function is use to validate and check for confirmation for a "toggle" action 
	 * if applicable. Otherwise, it will proceed with a normal form submission.
	 *
	 */
	function checkSubmit()
	{
		//Initialize check variable to zero(0).
		var checked = 0;
		
		//Checked whether the 1 or more disabled item's checkboxes were checked
		//and ready to process.
		jQuery('input.disabled-item').each(function(){
			if (jQuery(this).prop("checked")) checked++;
		});
		
		//Retrieve current field values for checking and validation.
		var option = jQuery('input[name="activation_option"]').val();
		var action = jQuery('input[name="action"]').val();
		
		//Checked certain values whether we need to display a dialog box
		//for confirmation.
		if (checked && (option.length === 0) && (action === "toggle"))
		{
			//We're using BootstrapDialog plugin to display a confirmation box to the user
			//to confirm his or her decision for enabling a previously disabled category or categories,
			//asking the user whether to retain it's current child level or start as a new first level entry.
			//
			//The option fields in the box are self explanatory, so no need to discuss further.
		
			BootstrapDialog.confirm({
				title: 'CONFIRM OPTION',
				message: 'It appears that you are trying to re-enable a previous disabled item(s). Do you wish to retain it\'s previous child level or start as new first level category?',
				type: BootstrapDialog.TYPE_PRIMARY,
				btnCancelLabel: 'Retain Level',
				btnOKLabel: 'Start As New First Level',
				callback: function(result) {
					
					//Depending on the user choice, we update the activation_option hidden field accordingly.
					if(result) {
						jQuery('input[name="activation_option"]').val('new');
					} 
					else 
					{
						jQuery('input[name="activation_option"]').val('retain');
					}
					
					//Here, we're forcing the form to re-submit using the newly updated field values.
					jQuery('form[name="frm_dashboard"]').get(0).submit();
				}
			});
		}
		else
		{
			//We proceed with the submission automatically,  
			//otherwise, we will ask for the user's option when re-activating previous disabled items/categories.
			return true;
		}
		
		//Defaults to stop the form submission unless either of the 
		//above condition is satisfied. 
		return false;
	}
	
	/**
	 * checkFormTrashedSubmit Function
	 *
	 * This function is use to validate and check for confirmation for a "toggle" action 
	 * if applicable. Otherwise, it will proceed with a normal form submission.
	 *
	 */
	function checkFormTrashedSubmit()
	{
		//Retrieve current field values for checking and validation.
		var value = jQuery('input[name="action"]').val();
		
		//Checked certain values whether we need to display a dialog box
		//for confirmation. Specifically, we're after for the "delete" action.
		if (value === "delete")
		{
			//We're using BootstrapDialog plugin to display a confirmation box to the user
			//to confirm his or her decision for deleting a category or categories,
			//
			//The option fields in the box are self explanatory, so no need to discuss further.
			
			BootstrapDialog.confirm({
				title: 'WARNING',
				message: 'Are you sure you want to delete the item(s) selected? Once deleted, the system can no longer restore these items. <br/><br/>Do you wish to proceed?',
				type: BootstrapDialog.TYPE_PRIMARY,
				btnOKLabel: 'Please Proceed',
				callback: function(result) {
					if(result) {
						
						//Changing the hidden action field value to "delete_confirmed" and 
						//proceed with the form submission with the new value.
						
						jQuery('input[name="action"]').val('delete_confirmed');
						jQuery('form[name="frm_trash"]').get(0).submit();
					}
				}
			});
		}
		else
		{
			//If option is other than "delete", we proceed with the submission automatically,  
			//otherwise, we will ask for delete confirmation.
			return true;
		}
		
		//Defaults to stop the form submission unless either of the 
		//above condition is satisfied. 
		return false;
	}
	
	/**
	 * comingSoon Function
	 *
	 * Temporary function that displays a COMING SOON message.
	 *
	 */
	function comingSoon()
	{
		BootstrapDialog.alert('COMING SOON...');
	}
</script>
</body>
</html>