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
                        <h3 class="font-weight-bold">USER LOG</h3>
                    </div>
                   
                    <div class="card-body--">
                        <div class="table-stats order-table ov-h">

                            
                            
                            
                            
                            
                            
                            
                            
                            
                        <div class="table-container"> 
                            <table class="table">
                                <thead class="sticky-top">
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>ID</th>
                                        <th>USER NAME</th>
                                        <th>LOGGED ACTION</th>
                                        <th>ACTION TIME</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="tableContents" id="userLogTableContents">
                                    
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('../template/footer.inc.php') ?>
<script src="../assets/js/userlog.js" type="text/javascript"></script>
</body>
</html>