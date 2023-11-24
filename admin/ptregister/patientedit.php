<?php
require_once('./../../config.php');
if (isset($_GET['id'])) {

    $id = $_GET['id'];
    // echo "<div>{$id}</div>";
    $qry = $conn->query("SELECT * FROM `patients` where id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
  
}
?>
<style>
    img#cimg {
        height: 17vh;
        width: 25vw;
        object-fit: scale-down;
    }
</style>
<style>
    .img-thumb-path {
        width: 100px;
        height: 80px;
        object-fit: scale-down;
        object-position: center center;
    }
    .modal-footer{
        display: none;
    }
    .modal-body{
        padding: 0;
    }
    .card-primary.card-outline {
    border: 3px solid #007bff;
    margin: 0;
}
.updatebtn{
    justify-content: end;
    margin-bottom: 10px;
}
</style>

<div class="card card-outline card-primary rounded-0 shadow">
   
    <div class="card-body"></div>
    <div class="container-fluid">
        <div class="container-fluid">
            <form id="updatepatient-frm" action="" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="row">
                    <div class="form-group col-md-4">
                        <input type="text" name="firstname" id="firstname" placeholder="John" autofocus required
                            class="form-control form-control-sm form-control-border" value="<?php echo $firstname; ?>">
                        <small class="mx-2">Firstname</small>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="middlename" id="middlename" placeholder="(optional)"
                            class="form-control form-control-sm form-control-border"
                            value="<?php echo $middlename; ?>">
                        <small class="mx-2">Middlename</small>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="lastname" id="lastname" placeholder="Smith" required
                            class="form-control form-control-sm form-control-border" value="<?php echo $lastname; ?>">
                        <small class="mx-2">Lastname</small>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <select name="gender" id="gender" class="form-control form-control-sm form-control-border"
                            required>
                            <option <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option <?php echo ($gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                        <small class="mx-2">Gender</small>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="date" name="dob" id="dob" placeholder="(optional)" required
                            class="form-control form-control-sm form-control-border" value="<?php echo $dob; ?>">
                        <small class="mx-2">Birthday</small>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="contact" id="contact" placeholder="09xxxxxxxxxx" required
                            class="form-control form-control-sm form-control-border" value="<?php echo $contact; ?>">
                        <small class="mx-2">Contact #</small>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <small class="mx-2">Address</small>
                        <textarea name="address" id="address" rows="3"
                            class="form-control form-control-sm rounded-0"><?php echo $address; ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">
                        <input type="email" name="email" id="email" placeholder="jsmith@sample.com" required
                            class="form-control form-control-sm form-control-border" value="<?php echo $email; ?>">
                        <small class="mx-2">Email</small>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">
                        <select name="client_id" id="client_id"
                            class="form-control form-control-sm form-control-border" required>
                            <?php
                            $clients = $conn->query("SELECT id, CONCAT(firstname, ' ', middlename, ' ', lastname) as fullname FROM client_list");
                            while ($client = $clients->fetch_assoc()) {
                                $selected = ($client['id'] == $client_id) ? 'selected' : '';
                                echo '<option value="' . $client['id'] . '" ' . $selected . '>' . $client['fullname'] . '</option>';
                            }
                            ?>
                        </select>
                        <small class="mx-2">Client</small>
                    </div>
                </div>
                <br>
                <div class="row align-items-end updatebtn">
                    <div class="col-4">
                        <button type="button" onclick="updatePatient()"
                            class="btn btn-primary btn-block btn-flat">Update Patient</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

<!-- <script>
    function updatePatient() {
        var _this = $('#updatepatient-frm');
        var el = $('<div>');
        el.addClass("pop-msg alert");
        el.hide();

        start_loader();
        $.ajax({
            url: "classes/ptregpost.php",
            data: new FormData(_this[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            error: function (err) {
                console.log(err);
                alert_toast("An error occurred", 'error');
                end_loader();
            },

            success: function (resp) {
                var response = JSON.parse(resp);
                console.log(response);

                if (response.status === 'success') {
                    alert_toast(response.message, 'success');
                    _this[0].reset();
                } else {
                    el.addClass("alert-danger");
                    el.text(response.message);
                    _this.prepend(el);
                }

                el.show('slow');
                $('html,body').animate({
                    scrollTop: 0
                }, 'fast');
                end_loader();
            }
        });
    }
</script> -->
<script>
    function updatePatient() {
    var _this = $('#updatepatient-frm');
    var el = $('<div>');
    el.addClass("pop-msg alert");
    el.hide();

    start_loader();

    // Append the id to the form data
    var formData = new FormData(_this[0]);
    formData.append('id', <?php echo $id; ?>);
    console.log("formData:", formData);

    $.ajax({
        url:_base_url_+"classes/ptregpost.php",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        error: function (err) {
            console.log(err);
            alert_toast("An error occurred", 'error');
            end_loader();
        },
        success: function (resp) {
            var response = JSON.parse(resp);
            console.log(response);

            if (response.status === 'success') {
             
                alert_toast(response.message, 'success');
                _this[0].reset();
                setTimeout(function(){
                location.href = _base_url_ + 'admin/?page=ptregister/allpatient';
                } , 1000)
            } else {
                el.addClass("alert-danger");
                el.text(response.message);
                _this.prepend(el);
            }

            el.show('slow');
            $('html,body,.modal').animate({scrollTop:0},'fast')
            end_loader();
        }
    });
}

</script>