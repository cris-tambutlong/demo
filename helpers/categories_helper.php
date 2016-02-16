<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('render_categories_row'))
{
	/**
	 * Render Categories Row
	 *
	 * Generates an HTML table row for the categories table for the "categories_dashboard" view.
	 *
	 * @param	array
	 * @return	string
	 */
	function render_categories_row($categories = array())
	{
		//Get the current CI instance and load the form helper 
		//to make use of the "form_checkbox" function.
		
		$ci =& get_instance();
		$ci->load->helper('form');
		
		//Set the default row to empty string.
		$row = '';
		
		//If array is empty, return the default empty row.
		if (empty($categories))
		{
			return $row;
		}
		
		//Extract and render all underlying category under the categories array.
		foreach($categories as $key => $value)
		{
			//Extract category information and assign to $info variable for later reference.
			$info = $value['info'];
			
			//Extract level information for the this current category.
			$level = intVal($value['level']);
			
			
			//We're decrementing the level as not to indent the parent/main category.
			//Then, we're calling the "str_repeat" function to apply the appropriate
			//indentation basing on the depth/level of the category.
			
			$level -= 1;
			$spacer = str_repeat('<i class="indent-spacer"></i>', $level);
			
			//Adding hyphen formatting to the sub categories information.
			if ($level > 0) $spacer .= '- <i class="hyphen-spacer"></i>';	
			
			
			//Build category row based from the following row template for our categories table.
			//We're using the form_checkbox function of the "form" helper here to render 
			//the checkbox element.
			
			$attrib = array(
				'name' => 'categories[]',
				'class' => 'category-item',
				'value' => $info->category_id,
				'checked' => FALSE
			);
			
			//Row template string.
			$row .= '<tr>
						<td class="text-center">' . form_checkbox($attrib) . '</td>
						<td>' . $spacer . '<a href="' . base_url() . 'categories/edit_category/' . $info->category_id . '">' . $info->category_name . ' (' . $info->slug . ')</a></td>
						<td class="text-center">
							<i class="fa fa-edit icon-cursor" onclick="document.location.href=\'' . base_url() . 'categories/edit_category/' . $info->category_id . '\'"></i>
							<i class="icon-spacer"></i>
							<i class="fa fa-check-square-o icon-cursor" onclick="askDisable(' . $info->category_id . ', \'' . $info->category_name . '\')"></i>
							<i class="icon-spacer"></i>
							<i class="fa fa-trash-o icon-cursor" onclick="askMove(' . $info->category_id . ', \'' . $info->category_name . '\')"></i>
						</td>
					</tr>';
					
					
			//Extract and render the category rows of the sub categories of this current category.
			//This will run through all the sub categories under this category recursively.
			
			$sub_categories = render_categories_row($value['sub_categories']);
			
			
			//Append the rendered sub categories to the current $row variable to accumulate all
			//the rendered rows so far.
			
			$row .= $sub_categories;
		} 

		return $row;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('render_trashed_categories_row'))
{
	/**
	 * Render Trashed Categories Row
	 *
	 * Generates an HTML table row for the trashed categories table for the "categories_trash_bin" view.
	 *
	 * @param	array
	 * @return	string
	 */
	function render_trashed_categories_row($categories = array())
	{
		//Get the current CI instance and load the form helper 
		//to make use of the "form_checkbox" function.
		
		$ci =& get_instance();
		$ci->load->helper('form');
		
		//Set the default row to empty string.
		$row = '';
		
		//If array is empty, return the default empty row.
		if (empty($categories))
		{
			return $row;
		}
		
		//Extract and render all underlying category under the categories array.
		foreach($categories as $info)
		{
			//Build category row based from the following row template for our trashed categories table.
			//We're using the form_checkbox function of the "form" helper here to render 
			//the checkbox element.
			
			$attrib = array(
				'name' => 'categories[]',
				'class' => 'category-item',
				'value' => $info->category_id,
				'checked' => FALSE
			);
			
			//Row template in action.
			$row .= '<tr>
						<td class="text-center">' . form_checkbox($attrib) . '</td>
						<td>' . $info->category_name . ' (' . $info->slug . ')</td>
					</tr>';
		} 
		
		return $row;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('render_disabled_categories_row'))
{
	/**
	 * Render Disabled Categories Row
	 *
	 * Generates an HTML table row for the disabled categories table for the "categories_dashboard" view.
	 *
	 * @param	array
	 * @return	string
	 */
	function render_disabled_categories_row($categories = array())
	{
		//Get the current CI instance and load the form helper 
		//to make use of the "form_checkbox" function.
		
		$ci =& get_instance();
		$ci->load->helper('form');
		
		//Set the default row to empty string.
		$row = '';
		
		//If array is empty, return the default empty row.
		if (empty($categories))
		{
			return $row;
		}
		
		//Extract and render all underlying category under the categories array.
		foreach($categories as $info)
		{
			//Build category row based from the following row template for our disabled categories rows.
			//We're using the form_checkbox function of the "form" helper here to render 
			//the checkbox element.
			
			$attrib = array(
				'name' => 'disabled_categories[]',
				'class' => 'category-item disabled-item',
				'value' => $info->category_id,
				'checked' => FALSE
			);
			
			//Row template in action.
			$row .= '<tr>
						<td class="text-center disabled">' . form_checkbox($attrib) . '</td>
						<td colspan="2" class="disabled">' . $info->category_name . ' (' . $info->slug . ')</td>
					</tr>';
		} 
		
		return $row;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('render_parent_categories_option'))
{
	/**
	 * Render Parent Categories Option
	 *
	 * Generates an HTML dropdown options for the "Parent Category" select element found
	 * inside the "categories_form" view.
	 *
	 * @param	array
	 * @param	mixed
	 * @param	mixed
	 * @return	string
	 */
	function render_parent_categories_option($categories, $filter = '', $selected_category = '')
	{
		//Set the default option to empty string.
		$option = '';
		
		//If array is empty, return the default empty option.
		if (empty($categories))
		{
			return $option;
		}
		
		//Extract and render all underlying category under the categories array.
		foreach($categories as $key => $value)
		{
			//Extract category information and assign to $info variable for later reference.
			$info = $value['info'];
			
			//Remove the filtered category from the option/choice as well as it's children
			//therefore, we bypass the process if the filter variable matched the current 
			//category_id.
			
			if (intVal($info->category_id) === intVal($filter))
			{
				continue;
			}
			
			
			//Extract level information for the this current category.
			$level = intVal($value['level']);
			
			
			//We're calling the "str_repeat" function to apply the appropriate
			//indentation basing on the depth/level of the category.
			
			$spacer = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level);
			
			
			//Assign default selected attribute to empty string.
			$selected = '';
			
			//Check if selected_category value is equal to the current category_id, if so,
			//set the current category as the currently selected item/option.
			if (intVal($info->category_id) === intVal($selected_category))
			{
				$selected = 'selected';
			}
			
			
			//Build options based from the following option template for our category form.
			$option .= '<option value="' . $info->category_id . '" ' . $selected . '>' . $spacer . $info->category_name . ' (' . $info->slug . ')</option>';
					
					
			//Extract and render the options from the sub categories of this current category.
			//This will run through all the sub categories under this category recursively.
			
			$sub_option = render_parent_categories_option($value['sub_categories'], $filter, $selected_category);
			
			
			//Append the rendered sub categories options to the current $option variable to accumulate all
			//the rendered options so far.
			
			$option .= $sub_option;
		} 
		
		return $option;
	}
}