<style>
    .img-thumb-path {
        width:100px;
        height:80px;
        object-fit:scale-down;
        object-position:center center;
    }
</style>


<div class="card card-outline card-primary rounded-0 shadow">
    <div class="card-header">
        <h3 class="card-title">Add New Patient</h3>
    </div>
    <div class="card-body"></div>
    <div class="container-fluid">
        <div class="container-fluid">
            <form id="registration-frm" action="" method="post">
                <!-- <input type="hidden" name="id"> -->
                <div class="row">
                    <div class="form-group col-md-4">
                        <input type="text" name="firstname" id="firstname" placeholder="John" autofocus required
                            class="form-control form-control-sm form-control-border">
                        <small class="mx-2">Firstname</small>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="middlename" id="middlename" placeholder="(optional)"
                            class="form-control form-control-sm form-control-border">
                        <small class="mx-2">Middlename</small>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="lastname" id="lastname" placeholder="Smith" required
                            class="form-control form-control-sm form-control-border">
                        <small class="mx-2">Lastname</small>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <select name="gender" id="gender"
                            class="form-control form-control-sm form-control-border" required>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                        <small class="mx-2">Gender</small>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="date" name="dob" id="dob" placeholder="(optional)" required
                            class="form-control form-control-sm form-control-border">
                        <small class="mx-2">Birthday</small>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="text" name="contact" id="contact" placeholder="09xxxxxxxxxx" required
                            class="form-control form-control-sm form-control-border">
                        <small class="mx-2">Contact #</small>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <small class="mx-2">Address</small>
                        <textarea name="address" id="address" rows="3"
                            class="form-control form-control-sm rounded-0"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">
                        <input type="email" name="email" id="email" placeholder="jsmith@sample.com" required
                            class="form-control form-control-sm form-control-border">
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
                                echo '<option value="' . $client['id'] . '">' . $client['fullname'] . '</option>';
                            }
                            ?>
                        </select>
                        <small class="mx-2">Client</small>
                    </div>
                </div>
                <br>
                <div class="row align-items-center">
                    <div class="col-4">
                        <button type="button" onclick="registerPatient()"
                            class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <script>
    function registerPatient() {
        var _this = $('#registration-frm');
        var el = $('<div>');
        el.addClass("pop-msg alert");
        el.hide();

        start_loader();
        $.ajax({
            url: window.location.href,
            data: new FormData(_this[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            error: function(err) {
                console.log(err);
                alert_toast("An error occurred", 'error');
                end_loader();
            },

            success: function (resp) {
                console.log("res",resp)
                // try {
                //     var response = JSON.parse(resp);
                //     console.log(response);

                //     if (response.status === 'success') {
                //         alert_toast(response.message, 'success');
                //         _this[0].reset();
                //     } else {
                //         el.addClass("alert-danger");
                //         el.text(response.message);
                //         _this.prepend(el);
                //     }
                // } catch (e) {
                //     console.log("Error parsing JSON response:", e);
                // }

                el.show('slow');
                $('html,body').animate({ scrollTop: 0 }, 'fast');
                end_loader();
            }
        });
    }
</script>
 -->
 <script>
    $(document).ready(function () {
        $('#registration-frm').submit(function (e) {
            e.preventDefault();
            registerPatient();
        });
    });
    function registerPatient() {
        var _this = $('#registration-frm');
        console.log(_this[0]);
        $('.pop-msg').remove();
        var el = $('<div>');
        el.addClass("pop-msg alert");
        el.hide();

        start_loader();
        $.ajax({
            url:_base_url_+"classes/ptregpost.php",
            data: new FormData(_this[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
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
                // try {
                //     var response = JSON.parse(resp);
                //     console.log(response);

                //     if (response.status === 'success') {
                //         alert_toast(response.message, 'success');
                //         _this[0].reset();
                //     } else {
                //         el.addClass("alert-danger");
                //         el.text(response.message);
                //         _this.prepend(el);
                //     }
                // } catch (e) {
                //     console.log("Error parsing JSON response:", e);
                // }

                el.show('slow');
                $('html,body').animate({ scrollTop: 0 }, 'fast');
                end_loader();
            }
        });
    }
</script>


