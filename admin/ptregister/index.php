<style>
    .img-thumb-path{
        width:100px;
        height:80px;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<div class="card card-outline card-primary rounded-0 shadow">
	<!-- <div class="card-header">
		<h3 class="card-title">List of Booked Appointments</h3>
	</div>
	<div class="card-body">
	<div class="card-header">
		<h3 class="card-title">List of My Appointments</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span>  Book New Appointment</a>
		</div>
	</div> -->
		<div class="container-fluid">
        <div class="container-fluid">
			<!-- <table class="table table-bordered table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="30%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-primary text-light">
						<th>#</th>
						<th>Date Created</th>
						<th>Code</th>
						<th>Patient</th>
						<th>Test</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$patient_arr = [];
						$patients = $conn->query("SELECT *,CONCAT(firstname,' ',middlename,' ', lastname) as fullname FROM `client_list` where id in (SELECT client_id FROM `appointment_list`)");
						if($patients->num_rows > 0){
							$res = $patients->fetch_all(MYSQLI_ASSOC);
							$patient_arr = array_column($res,'fullname','id');
						}
						$qry = $conn->query("SELECT * from `appointment_list` order by unix_timestamp(date_created) desc ");
						while($row = $qry->fetch_assoc()):
                            $tests = $conn->query("SELECT * FROM `test_list` where id in (SELECT test_id FROM `appointment_test_list` where appointment_id = '{$row['id']}')");
                            $test = "N/A";
                            if($tests->num_rows > 0){
                                $res = $tests->fetch_all(MYSQLI_ASSOC);
                                $test_arr = array_column($res,'name');
                                $test = implode(", ",$test_arr);
                            }
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td class=""><?= $row['code'] ?></td>
							<td class=""><p class="m-0 truncate-1"><?= isset($patient_arr[$row['client_id']]) ? $patient_arr[$row['client_id']] : 'N/A' ?></p></td>
							<td class=""><p class="m-0 truncate-1"><?= $test ?></p></td>
							<td class="text-center">
								<?php 
									switch ($row['status']){
										case 0:
											echo '<span class="rounded-pill badge badge-secondary ">Pending</span>';
											break;
										case 1:
											echo '<span class="rounded-pill badge badge-primary ">Approved</span>';
											break;
                                        case 2:
                                            echo '<span class="rounded-pill badge badge-warning ">Sample Collected</span>';
                                            break;
                                        case 3:
                                            echo '<span class="rounded-pill badge badge-primary bg-teal ">Delivered to Lab</span>';
                                            break;
                                        case 4:
                                            echo '<span class="rounded-pill badge badge-success ">Done</span>';
                                            break;
                                        case 5:
                                            echo '<span class="rounded-pill badge badge-danger ">Cancelled</span>';
                                            break;
										case 6:
											echo '<span class="rounded-pill badge-light badge border text-dark ">Report Uploaded</span>';
											break;
									}
								?>
							</td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
								  	<a class="dropdown-item" href="./?page=appointments/view_appointment&id=<?= $row['id'] ?>" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table> -->
            <form id="registration-frm" action="" method="post">
                <input type="hidden" name="id">
              <div class="row">
                  <div class="form-group col-md-4">
                      <input type="text" name="firstname" id="firstname" placeholder="John" autofocus required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Firstname</small>
                  </div>
                  <div class="form-group col-md-4">
                      <input type="text" name="middlename" id="middlename" placeholder="(optional)" class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Middlename</small>
                  </div>
                  <div class="form-group col-md-4">
                      <input type="text" name="lastname" id="lastname" placeholder="Smith" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Lastname</small>
                  </div>
              </div>
              <div class="row">
                  <div class="form-group col-md-4">
                      <select name="gender" id="gender" class="form-control form-control-sm form-control-border" required>
                          <option>Male</option>
                          <option>Female</option>
                      </select>
                      <small class="mx-2">Gender</small>
                  </div>
                  <div class="form-group col-md-4">
                      <input type="date" name="dob" id="dob" placeholder="(optional)" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Birthday</small>
                  </div>
                  <div class="form-group col-md-4">
                      <input type="text" name="contact" id="contact" placeholder="09xxxxxxxxxx" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Contact #</small>
                  </div>
              </div>
              <div class="row">
                  <div class="form-group col-md-12">
                      <small class="mx-2">Address</small>
                      <textarea name="address" id="address" rows="3" class="form-control form-control-sm rounded-0"></textarea>
                  </div>
              </div>
              <div class="row">
                    <div class="form-group col-md-10">
                      <input type="email" name="email" id="email" placeholder="jsmith@sample.com" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Email</small>
                  </div>
              </div>
              <div class="row">
                    <div class="form-group col-md-10">
                      <input type="password" name="password" id="password" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Password</small>
                  </div>
              </div>
              <div class="row">
                    <div class="form-group col-md-10">
                      <input type="password" name="cpass" id="cpass" required class="form-control form-control-sm form-control-border">
                      <small class="mx-2">Confirm Password</small>
                  </div>
              </div>
              <br>
              <div class="row align-items-center">
                <!-- <div class="col-8">
                  <a href="<?php echo base_url ?>">Already have an Account?</a>
                </div> -->
                <!-- /.col -->
                <div class="col-4">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                </div>
                <!-- /.col -->
              </div>
            </form>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('#create_new').click(function(){
			uni_modal("Add New Appointment","appointments/manage_appointment.php",'mid-large')
		})
		$('.view_data').click(function(){
			uni_modal("Appointment Details","appointments/view_appointment.php?id="+$(this).attr('data-id'))
		})
        $('.edit_data').click(function(){
			uni_modal("Update Appointment Details","appointments/manage_appointment.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Appointment permanently?","delete_appointment",[$(this).attr('data-id')])
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
	})
	function delete_appointment($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_appointment",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
    $(function(){
        end_loader();
        $('#registration-frm').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            if($('#password').val() != $('#cpass').val()){
                el.addClass('alert-danger')
                el.text("Password does not match")
                $('#password').focus()
                $('#password, #cpass').addClass('is-invalid');
                $('#registration-frm').append(el)
                el.show('slow')
                return false;
            }
            $('#password, #cpass').removeClass('is-invalid');

            start_loader();
            $.ajax({
                url:_base_url_+"classes/Users.php?f=save_client",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp == '1'){
                        location.href=_base_url_+'/admin';
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred while registering the account.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>