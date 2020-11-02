<div class="modal-header">
	<h5 class="modal-title h4" id="myLargeModalLabel">Add New User</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>
</div>
<div class="modal-body">
	<div class="form">
		<form action="{{ url(action('UserController@postRegister')) }}" method="post" class="register-form">
			<div class="row">
				{{ csrf_field() }}
				<div class="col-md-12">
					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control" name="name" placeholder="insert name" required>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Username</label>
						<input type="text" class="form-control" name="username" placeholder="insert username" required>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" name="password" placeholder="insert password"
							required>
					</div>
				</div>
			</div>
			<button type="submit" class="btn btn-info btn-fill">Create User</button>
		</form>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>