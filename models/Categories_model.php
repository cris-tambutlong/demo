<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/New_York');

class Categories_model extends CI_Model {

	/**
	 * Constructor for the Categories Model.
	 *
	 */
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}

	/**
	 * Get Categories
	 *
	 * Retrieve all active non-trashed categories.
	 *
	 * @param int
	 * @param int
	 * @return array
	 */
	public function get_categories($parent_id = 0, $level = 0)
	{
		//Set initial categories container.
		$categories = array();
		
		//Increment level as it moves along the process.
		//This is to keep track on the depth of each category.
		$level++;
		
		//Set database filters based on the some pre-conditioned value
		//and passed parameter(s).
		$this->db->where('parent_category', $parent_id);
		$this->db->where('is_trashed', 0);
		$this->db->where('is_active', 1);
		
		//Sort entries by its category name in an ascending order.
		$this->db->order_by("category_name", "asc");
		
		//Pull records from database and check to see if we got any records
		//basing on the query's filter that we put in place.
		$query = $this->db->get('categories');	
		if ($query->num_rows() > 0)
		{
			//Iterate through all the records, processing them one by one. 
			foreach ($query->result() as $row)
			{
				//Set a temporary container for the pulled information
				//of a particular category node.
				$category = array();
				$category['info'] = $row;
				$category['level'] = $level;
				
				//Recursively run through all the sub categories of this current category
				//and process the same by calling this function as callback until
				//we reach the last child/depth.
				$category['sub_categories'] = $this->get_categories($row->category_id, $level);
				
				//store the temporary container to our categories container
				//which will be use as a result of the process.
				$categories[$row->category_name] = $category;
			}
		}

		//return all the processed categories.
		return $categories;
	}
	
	
	/**
	 * Get Trashed Categories
	 *
	 * Retrieve all trashed categories.
	 *
	 * @return array
	 */
	public function get_trashed_categories()
	{
		//Set database filters based on the some pre-conditioned value.
		$this->db->where('is_trashed', 1);
		
		//Sort entries by its category name in an ascending order.
		$this->db->order_by("category_name", "asc");
		
		//Pull records from database basing on the query's filter that we put in place.
		$query = $this->db->get('categories');
		
		//return the categories pulled and the number of rows/records returned.
		return array(
					'result' => $query->result(),
					'count' => $query->num_rows()
					);
	}
	
	/**
	 * Get Disabled Categories
	 *
	 * Retrieve all disabled categories.
	 *
	 * @return array
	 */
	public function get_disabled_categories()
	{
		//Set database filters based on the some pre-conditioned value.
		$this->db->where('is_active', 0);
		$this->db->where('is_trashed', 0);
		
		//Sort entries by its category name in an ascending order.
		$this->db->order_by("category_name", "asc");
		
		//Pull records from database basing on the query's filter that we put in place.
		$query = $this->db->get('categories');
		
		//return the categories as a result of the process.
		return $query->result();
	}
	
	/**
	 * Get Category
	 *
	 * Retrieve a category based on a specified category id.
	 *
	 * @param int
	 * @return object
	 */
	public function get_category($category_id)
	{
		//Set database filters based on the some pre-conditioned value
		//and passed parameter(s).
		$this->db->where('category_id', $category_id);
		
		//Pull the record from the database basing on the query's filter that we put in place.
		$query = $this->db->get('categories');	
		
		//return a category object.
		return $query->row();
	}
	
	/**
	 * Create Category
	 *
	 * Insert or add new category information into the categories table.
	 *
	 * @param array
	 * @return boolean
	 */
	public function create_category($data)
	{
		//Create new entry by inserting the data into the categories table.
		$this->db->insert('categories', $data);
		
		//Validated whether we were able to create the record successfully.
		return ($this->db->affected_rows()) ? TRUE : FALSE;
    }
	
	/**
	 * Update Category
	 *
	 * Update the information of an existing category specified by a category id.
	 *
	 * @param int
	 * @param array
	 * @return boolean
	 */
	public function update_category($category_id, $data)
	{
		//Set database filters based on the some pre-conditioned value
		//and passed parameter(s).
		$this->db->where('category_id', $category_id);
		
		//Update the information with the latest submitted data.
		$this->db->update('categories', $data);
		
		//Validated whether we were able to update the record successfully.
		return ($this->db->affected_rows()) ? TRUE : FALSE;
	}
	
	/**
	 * Delete Category
	 *
	 * Delete a category record permanently specified by a category id. 
	 *
	 * @param int
	 * @return boolean
	 */
	public function delete_category($category_id)
	{
		//Set database filters based on the some pre-conditioned value
		//and passed parameter(s).
		$this->db->where('category_id', $category_id);
		
		//Delete the specified category from the categories table.
        $this->db->delete('categories');
				
		//Validated whether we were able to delete the record successfully.
		return ($this->db->affected_rows()) ? TRUE : FALSE;
	}
	
	/**
	 * Move Category To Trash
	 *
	 * Update the is_trashed field to symbolically trash the category.
	 *
	 * @param int
	 * @return boolean
	 */
	public function move_category_to_trash($category_id)
	{
		//Setting the fields to update.
		$data = array('is_trashed' => 1,
					  'date_trashed' => date("Y-m-d H:i:s"));
        
		//Set database filters based on the some pre-conditioned value
		//and passed parameter(s).	
		$this->db->where('category_id', $category_id);
		
		//Update the is_trashed field to 1, symbolizing that the category was recently trashed.
		$this->db->update('categories', $data);

		//Validated whether we were able to update or move the 
		//category specified into the trash bin by updating 
		//it's is_trashed flag successfully.
		
		return ($this->db->affected_rows()) ? TRUE : FALSE;
	}
	
	/**
	 * Restore Category
	 *
	 * Update the is_trashed to zero(0) to symbolically removed
	 * the specified category out from the trash.
	 *
	 * @param int
	 * @return boolean
	 */
	public function restore_category($category_id)
	{
		//Setting fields to update.
		$data = array('is_trashed' => 0);
        
		//Set database filters based on the some pre-conditioned value
		//and passed parameter(s).
		$this->db->where('category_id', $category_id);
		
		//Remove the category from trash by updating it's is_trashed field to 0.
		$this->db->update('categories', $data);
		
		//Validated whether we were able to toggle/switch the is_trashed flag successfully.
		return ($this->db->affected_rows()) ? TRUE : FALSE;
	}
	
	/**
	 * Toggle Active Status
	 *
	 * Toggle or switch the current is_active field. If the current is_active
	 * value is 1 then it will become 0, and vice versa. This will symbolically
	 * set the category to either in an enabled or disabled state.
	 *
	 * @param int
	 * @param string
	 * @return boolean
	 */
	public function toggle_active_status($category_id, $activation_option = 'retain')
	{
		//Set default change level to empty string.
		$change_level = '';
		
		//If activation option is "new" then we are moving or changing the 
		//the parent_category field of the category to the default or first level
		//parent by setting the parent_category field to zero(0).
		
		if ($activation_option === 'new') $change_level = ',`parent_category` = 0';
		
		//Building a predefined query string. We are currently using a Query Binding
		//method to safely execute the query and to prevent SQL Injection.
		$sql = "UPDATE `categories` SET `is_active` = !`is_active`{$change_level} WHERE `category_id` = ?";
		
		//Execute the above query.
		$this->db->query($sql, array($category_id));
		
		//Validated whether we were able to toggle/switch the is_active flag successfully.
		return ($this->db->affected_rows()) ? TRUE : FALSE;
	}
	
}
