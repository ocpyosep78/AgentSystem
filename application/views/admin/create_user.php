<div id="page-wrapper">
<?php $this->load->view('alerts');?>
<div id="box" class ="box">
    	<div class="row">
          <div class="col-lg-12">
            <h1>Create User</h1>
          </div>
        </div>
    
<?php 
	$data = array(
		'id'=>'form',
		'class'=>'form-horizontal',
		'role' => 'form'
	);
	echo form_open('admin/user/create_user', $data);?>

	
	<div class="form-group">
		<label for="first_name" class="col-sm-2 control-label">First name</label>
		<div class="col-sm-6">
			<input type = "text" class="form-control" name="first_name"/>
		</div>
	</div>
	<div class="form-group">
		<label for ="last_name" class="col-sm-2 control-label">Last name</label>
		<div class="col-sm-6">
			<input type = "text" class="form-control" name="last_name"/>
		</div>
	</div>
	
	
	<div class="form-group">
		<label for ="email" class="col-sm-2 control-label">email</label>
		<div class="col-sm-6">
			<input type = "text" class="form-control" name="email"/>
		</div>
	</div>
	
	<div class="form-group">
		<label for ="phone" class="col-sm-2 control-label">phone</label>
		<div class="col-sm-6">
			<input type = "number" class="form-control" name="phone"/>
		</div>
	</div>	
	
	<div class="form-group">
		<label for="group" class="col-sm-2 control-label">Group</label>
		<div class="col-sm-6">
			<select name = "group" class="form-control">
				<?php foreach ($user_groups as $group):?>
				<option value = "<?php echo $group['id'];?>"><?php echo $group['name'];?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label for="group" class="col-sm-2 control-label">Marketers</label>
		<div class="col-sm-6">
			<select name = "marketer" class="form-control">
				<?php foreach ($marketers as $group):?>
				<option value = "<?php echo $group['id'];?>"><?php echo $group['username'];?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type = "Submit" class="btn btn-default" value="Submit"/>
		</div>
	</div>

	</form>
</div>
</div></div>