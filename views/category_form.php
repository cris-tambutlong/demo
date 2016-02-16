<?php $page_data = array('page_title' => 'Category Form - Development Solution') ?>
<?php $this->load->view('header', $page_data); ?>

<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <div>
		<h2 class="text-center">Category Form - Development Test Solution</h2>
		<table class="table table-bordered table-hover">
			<caption>
				<p class="text-center head-caption">
					To go back to the categories dashboard click <a href="<?php echo base_url() ?>">here</a>.
				</p>
			</caption>
		</table>
		<div class="form">
			<div class="validation-errors">
				<?php echo validation_errors(); ?>
			</div>
			<?php echo form_open($form_handler); ?>
				<?php echo form_hidden('posted', true); ?>
				
				<div class="form-group">
					<label for="category_name">Category Name</label>
					<input name="category_name" class="form-control" value="<?php echo $category_name ?>" placeholder="Category Name">
				</div>
				<div class="form-group">
					<label for="slug">Slug</label>
					<input name="slug" class="form-control" value="<?php echo $slug ?>" placeholder="Slug">
				</div>
				<div class="form-group">
					<label for="parent_category">Parent Category</label>
					<select name="parent_category" class="form-control" placeholder="Parent Category" size="10">
						<option value="0" selected>Default Parent (First Level)</option>
						<?php echo $parent_categories_option; ?>
					</select>
				</div>
				<div class="pad-top">
					<button type="submit" class="btn btn-primary">Save Category</button>
				</div>
			</form>
		</div>		
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('footer'); ?>