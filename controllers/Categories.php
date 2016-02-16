<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

	/**
	 * Index Action for this controller.
	 *
	 * Serves as the default action for our categories controller that
	 * renders our Categories Dashboard Page.
	 */
	public function index()
	{		
		//Load CI's form helper. We're using some form element renderer from this helper
		//so we need to load this helper. 
		$this->load->helper('form');
		
		//Load custom categories helper.
		$this->load->helper('categories');
		
		//Load custom categories model.
		$this->load->model('categories_model');
		
		//Retrieve active, disabled and trashed categories and prepare each 
		//category information for display through each respective renderer  
		//helper functions and store it in the params variable.
		
		$categories = $this->categories_model->get_categories();
		$params['categories'] = render_categories_row($categories);
		
		$disabled_categories = $this->categories_model->get_disabled_categories();
		$params['disabled_categories'] = render_disabled_categories_row($disabled_categories);
		
		$trashed_categories = $this->categories_model->get_trashed_categories();
		$params['trashed_count'] = $trashed_categories['count'];
		
		//Load and display the categories dashboard view. This is where we pass the params
		//variable to display those rendered information.
		
		$this->load->view('categories_dashboard', $params);
	}
	
	
	/**
	 * Process Bulk Action for this controller.
	 *
	 * This action serves as a bootstrap action for the solution's bulk process such as
	 * the enabling/disabling, move to trash bin, restore and delete categories.
	 */
	public function process_bulk_action()
	{		
		//Load custom categories model.
		$this->load->model('categories_model');
		
		//Store posted/submitted information from a POST request to  
		//the following local variables for easy access.
		
		$action = $this->input->post('action');
		$items = $this->input->post('categories');
		$disabled_items = $this->input->post('disabled_categories');
		$activation_option = $this->input->post('activation_option');
		
		//Checking the action submitted and execute the appropriate
		//process 
		switch ($action)
		{
			case 'toggle':
			
				//Toggle enable/disable status of an active category
				//by looping through an array of category ids submitted.
				if ( ! empty($items))
				{
					foreach($items as $key => $category_id)
					{
						$this->categories_model->toggle_active_status($category_id);
					}
				}
				
				//Toggle enable/disable status of a currently disabled category
				//by looping through an array of disabled category ids submitted.
				if ( ! empty($disabled_items))
				{
					foreach($disabled_items as $key => $category_id)
					{
						$this->categories_model->toggle_active_status($category_id, $activation_option);
					}
				}
				
				//After the toggle process redirect to the Category Dashboard (Index Action)
				redirect('categories');
				break;
			case 'move_to_trash':
			
				//Move categories to trash bin by looping through an array of category ids submitted.
				if ( ! empty($items))
				{
					foreach($items as $key => $category_id)
					{
						$this->categories_model->move_category_to_trash($category_id);
					}
				}
				
				//Move categories to trash bin by looping through an array of disabled category ids submitted.
				if ( ! empty($disabled_items))
				{
					foreach($disabled_items as $key => $category_id)
					{
						$this->categories_model->move_category_to_trash($category_id);
					}
				}
				
				//After moving to trash bin process, redirect to the Trash Bin page 
				//to display the categories being moved to the trash bin.
				redirect('categories/trash_bin');
				break;
			case 'restore':
			
				//Restore previously trashed categories by looping through an array of category ids submitted.
				if ( ! empty($items))
				{
					foreach($items as $key => $category_id)
					{
						$this->categories_model->restore_category($category_id);
					}
				}
				
				//After restoring redirect to the Categories Dashboard (Index Action)
				//to check the restored items/categories.
				redirect('categories');
				break;
			case 'delete_confirmed':		
				
				//Permanently delete categories by looping through an array of category ids submitted.
				if ( ! empty($items))
				{
					foreach($items as $key => $category_id)
					{
						$this->categories_model->delete_category($category_id);
					}
				}
				
				//Redirect to Trash Bin Page to refresh the list.
				redirect('categories/trash_bin');
				break;
			default:
				//Redirect to the Categories Dashboard for any request submitted to this 
				//Process Bulk action that we currently don't have a process to execute.
				redirect('categories');
				break;
		}

		
	}
	
	/**
	 * Trash Bin Action for this controller.
	 *
	 * Loads and display the Trash Bin Page which displays all active and disabled categories
	 * that were previously moved to the trash bin.
	 */
	public function trash_bin()
	{		
		//Load CI's form helper. We're using some form element renderer from this helper
		//so we need to load this helper. 
		$this->load->helper('form');
		
		//Load custom categories helper.
		$this->load->helper('categories');
		
		//Load custom categories model.
		$this->load->model('categories_model');
		
		//Pull all trashed categories that were move to the trash bin previously.
		$trashed_categories = $this->categories_model->get_trashed_categories();
		
		//Render the retrieved trashed categories and prepare them for display.
		$params['trashed_categories'] = render_trashed_categories_row($trashed_categories['result']);
		
		//Load and display the Trash Bin page. We're passing the params variable here 
		//to display the trashed categories/items.
		$this->load->view('categories_trash_bin', $params);
	}
	
	/**
	 * Add Category Action for this controller.
	 *
	 * This action serves as a renderer of the Add Category (New) Form
	 * and a handler to create new category.
	 */
	public function add_category()
	{
		//Load custom categories model.
		$this->load->model('categories_model');
		
		//Load custom categories helper.
		$this->load->helper('categories');
		
		//Load CI's form helper.
		$this->load->helper('form');
		
		//Load CI's form validation helper.
		$this->load->library('form_validation');
		
		//Pull active categories information to be use to render
		//the parent_category dropdown option.
		
		$categories = $this->categories_model->get_categories();
		
		//Set params variable with default values. These values will be
		//use when the form is not yet submitted.
		
		$params = array('form_handler' => 'categories/add_category',
						'category_name' => '',
						'slug' => '',
						'parent_category' => '',
						'parent_categories_option' => render_parent_categories_option($categories),
						'has_process_error' => FALSE);
		
		//Check if the form is posted/submitted.
		if ($this->input->post('posted'))
		{
			//Render the parent_category dropdown option.
			$params['parent_categories_option'] = render_parent_categories_option($categories, '', $this->input->post('parent_category'));
			
			//Set form validation delimiter and rules for our 
			//form submission data validation.
			
			$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
			$this->form_validation->set_rules('category_name', 'Category Name', 'required');
			$this->form_validation->set_rules('slug', 'Slug', 'required');
			$this->form_validation->set_rules('parent_category', 'Parent Category', 'required');

			//Prepare the data variable based from the submitted form data.
			$data = array('category_name' => $this->input->post('category_name'),
						  'slug' => $this->input->post('slug'),
						  'parent_category' => $this->input->post('parent_category'));
							  
			//Validate the form data submitted.
			if ($this->form_validation->run() === TRUE)
			{
				//Create the new category.
				$result = $this->categories_model->create_category($data);
				if ( ! $result)
				{
					//Set the process error flag if we encounter
					//an error during the process. 
					$params['has_process_error'] = TRUE; 
				}
				else
				{
					//If the creation is successful redirect to the Categories Dashboard.
					redirect("categories");
				}
			}
			
			//Merge params and data variable/array. This will be used to display the 
			//current state/data on the category form.
			$params = array_merge($params, $data);
		}
		
		//Load and display the category form.
		$this->load->view('category_form', $params);
	}
	
	/**
	 * Edit Category Action for this controller.
	 *
	 * This action serves as a renderer of the Edit Category Form
	 * and a handler to update an existing category.
	 */
	public function edit_category()
	{
		//Load custom categories model.
		$this->load->model('categories_model');
		
		//Load custom categories helper.
		$this->load->helper('categories');
		
		//Load CI's form helper.
		$this->load->helper('form');
		
		//Load CI's form validation helper.
		$this->load->library('form_validation');

		//Pulling the category_id fromt he 3rd segment of the URL.
		$category_id = $this->uri->segment(3);
		
		//Retrieve or pull the category information based from the submitted category_id
		$category = $this->categories_model->get_category($category_id);
		
		//Check if we were able to pull a category successfully.
		if ($category)
		{
			//Pull active categories information to be use to render
			//the parent_category dropdown option.
			$categories = $this->categories_model->get_categories();
			
			//Set params variable with default values. These values will be
			//use when the form is not yet submitted. We're adding the information
			//from the category we recently pulled.
			
			$params = array('form_handler' => 'categories/edit_category/' . $category_id,
							'category_name' => $category->category_name,
							'slug' => $category->slug,
							'parent_category' => $category->parent_category,
							'parent_categories_option' => render_parent_categories_option($categories, $category_id, $category->parent_category),
							'has_process_error' => FALSE);
	
			//Check if the form is posted/submitted.
			if ($this->input->post('posted'))
			{
				//Set form validation delimiter and rules for our 
				//form submission data validation.
			
				$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
				$this->form_validation->set_rules('category_name', 'Category Name', 'required');
				$this->form_validation->set_rules('slug', 'Slug', 'required');
				$this->form_validation->set_rules('parent_category', 'Parent Category', 'required');

				//Prepare the data variable based from the submitted form data.
				$data = array('category_name' => $this->input->post('category_name'),
							  'slug' => $this->input->post('slug'),
							  'parent_category' => $this->input->post('parent_category'));

				//Run the validation process and validate the submitted data.				
				if ($this->form_validation->run() === TRUE)
				{
					//If validation is successful, update the category's information
					//based from the submitted data.
					$this->categories_model->update_category($category_id, $data);
					
					//Redirect to the Categories Dashboard (Index Action)
					redirect("categories");
				}
				
				//Merge params and data variable/array. This will be used to display the 
				//current state/data on the category form.
				$params = array_merge($params, $data);
			}
			
			//Load and display the category form.
			$this->load->view('category_form', $params);
		}
		
		
	}
	
	/**
	 * Move Category To Trash Action for this controller.
	 *
	 * This action serves as a handler for moving category to the trash bin 
	 * using a specified category_id.
	 */
	public function move_category_to_trash()
	{
		//Load custom categories model.
		$this->load->model('categories_model');
		
		//Retrieve or pull the category_id from the 3rd segment of the URL.
		$category_id = $this->uri->segment(3);
		
		//Move category to the trash bin by the specified category_id.
		$this->categories_model->move_category_to_trash($category_id);
		
		//Redirect to the Trash Bin to check the currently moved category/item.
		redirect('categories/trash_bin');
	}
	
	
	/**
	 * Toggle Active Status Action for this controller.
	 *
	 * This action serves as a handler for toggling/switching the enable/disable 
	 * status of the category using a specified category_id. 
	 */
	public function toggle_active_status()
	{
		//Load custom categories model.
		$this->load->model('categories_model');

		//Retrieve and pull the category_id from the 3rd segment of the URL.
		$category_id = $this->uri->segment(3);
		
		//Toggle active status of the category by the specified category_id.
		$this->categories_model->toggle_active_status($category_id);
		
		//Redirect to the Categories Dashboard (Index Action).
		redirect('categories');
	}


	/**
	 * Download Files Action for this controller.
	 *
	 * This action is basically not part of the solution. I placed it here so 
	 * that it will be easier for you to download the files that comprised the solution.
	 */
	public function download_files()
	{
		//Load CI's download helper
		$this->load->helper('download');
		
		//Read and extract the file's content from the file to be downloaded.
		$data = file_get_contents('./solution_files.zip');
		
		//Assign the file name for download.
		$name = 'solution_files.zip';

		//Force the session/browser to download the file. 
		force_download($name, $data);
	}
	
}
