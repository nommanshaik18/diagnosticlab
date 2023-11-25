<style>
    .img-thumb-path {
        width: 100px;
        height: 80px;
        object-fit: scale-down;
        object-position: center center;
    }
</style>

<script>
    
</script>

<div class="card card-outline card-primary rounded-0 shadow">
    <div class="card-header">
        <h3 class="card-title">List of Patients</h3>
        <div class="card-tools">
			<a href="<?php echo base_url ?>admin/?page=admin_patient/ptregistration" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span>  Add New Patient</a>
		</div>
    </div>
    
    <div class="card-body">
   
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-striped" data-order='[[ 0, "desc" ]]'>
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
                        <!-- <th>#</th> -->
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // $i = 1;
                    // $qry = $conn->query("SELECT * FROM `patients` order by id asc");
                    // $qry = $conn->query("SELECT * FROM `patients` ORDER BY date_created DESC");

                   $qry = $conn->query("SELECT * FROM `patients` ORDER BY id DESC");

                    while ($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <!-- <td class="text-center"><?php echo $i++; ?></td> -->
                            <td class=""><?php echo $row['id']; ?></td>
                            <td class=""><?= $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] ?></td>
                            <td class=""><?php echo $row['gender']; ?></td>
                            <td class=""><?php echo $row['dob']; ?></td>
                            <td class=""><?php echo $row['address']; ?></td>
                            <td class=""><?php echo $row['contact']; ?></td>
                            <td class=""><?php echo $row['email']; ?></td>
                            <td class=""><?php echo $row['date_created']; ?></td>
                            <td class=""><?php echo $row['date_updated']; ?></td>

                            <!-- <td class=""><?= $row['firstname'] ?></td>
                            <td class=""><?= $row['middlename'] ?></td>
                            <td class=""><?= $row['lastname'] ?></td> -->
                            <!-- <td class=""><?= $row['contact'] ?></td> -->
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // $(document).ready(function () {
        
    //     $('.edit_data').click(function () {
    //         uni_modal("Update Client Details", "path_to_your_edit_client_page.php?id=" + $(this).attr('data-id'), 'mid-large')
    //     })
    //     $('.delete_data').click(function () {
    //         _conf("Are you sure to delete this Client permanently?", "delete_client", [$(this).attr('data-id')])
    //     })
    //     $('.table td, .table th').addClass('py-1 px-2 align-middle')
    //     $('.table').dataTable({
    //         columnDefs: [
    //             {orderable: false, targets: 5}
    //         ],
    //     });
    // })

    $(document).ready(function(){
        // $('#create_new').click(function(){
		// 	uni_modal("Add New Appointment","appointments/manage_appointment.php",'mid-large')
		// })
		// $('.view_data').click(function(){
		// 	uni_modal("Appointment Details","appointments/view_appointment.php?id="+$(this).attr('data-id'))
		// })
        $('.edit_data').click(function(){
			uni_modal("Update Patient Details","admin_patient/patientedit.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Appointment permanently?","delete_patient",[$(this).attr('data-id')])
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
	})

    function delete_patient($id) {
        var id = $id
        console.log("id",id)
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_patient",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>
