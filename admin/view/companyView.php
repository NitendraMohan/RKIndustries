<?php
// require_once '../utility/sessions.php';
require("../template/top.inc.php");
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">COMPANY MASTER</h3>
                    </div>
                    <div class="card-body--">
                        <div class="table-stats order-table ov-h">


                            <!-- Modal body -->
                            <form action="" method="post" id="companyForm">
                                <div class="modal-body">

                                    <input type="hidden" id="modalid" name="id" value="" />
                                    <div class="form-group">
                                    <label for="logo">Select Logo Image</label>    
                                    <input class="form-control" type="file" name="logo" id="logo">
                                    
                                    </div>
                                <div>
                                <img src="" alt="logo image" id="logo_image" name="logo_image" onerror="this.onerror=null; this.src='../images/favicon.png'" height="20%" width="20%"/>    
                                </div>
                                    <div class="form-group">
                                        <label for="company_name">Company Name*</label>
                                        <input class="form-control modalyearfrom" type="text" placeholder="Enter Company Name" id="company_name" name="company_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="gst_no">GST Number*</label>
                                        <input class="form-control modalyearfrom" type="text" placeholder="Enter GST Number" id="gst_no" name="gst_no" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input class="form-control modalyearfrom" type="text" placeholder="Enter Address" id="address" name="address">
                                    </div>
                                    <div class="form-group">
                                        <label for="mail_id">Official Mail id</label>
                                        <input class="form-control modalyearfrom" type="email" placeholder="Enter Mail Id" id="mail_id" name="mail_id">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_number">Contact Number</label>
                                        <input class="form-control modalyearfrom" type="tel" placeholder="Enter Contact Number" id="contact_number" name="contact_number">
                                    </div>
                                </div>
                            
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary modalsubmit" id="btnSave" data-id="save">Submit</button>
                            </div>
                            </form>
                            <!-- <div class="alert alert-dark" id="hmsg" style="display:none;"></div> -->
                            <div id="msg"></div>


                            <div id="msg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('../template/footer.inc.php') ?>
<script src="../assets/js/companymaster.js" type="text/javascript"></script>
<!-- <script type="text/javascript" src="jquery-3.2.1.min.js"></script> -->
</body>

</html>