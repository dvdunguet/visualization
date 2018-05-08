<html>
<head>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <?php
    if($graphtype['graphtype']==0 || $graphtype['graphtype'] ==1) {
        echo '<script type="text/javascript"
            src="js/dygraph.js"></script>
            <link rel="stylesheet" src="css/dygraph.css" />
            <style type="text/css">
                .dygraph-legend {
                    background-color: rgba(200, 200, 255, 0.75) !important;
                    position: relative;
                    top:350px !important;
                    left:0px !important;
                    display:block;
                }
                pre {
                    margin-top: 30px;
                }
                .dygraph-legend > span {
                    display:block !important;
                }
                .navbar-custom .active {
                    background-color: #cccccc;
                }
            </style>
    
';
    }elseif ($graphtype['graphtype']==2 || $graphtype['graphtype'] ==3){
        echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
        echo '
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
';
    }
    ?>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-custom">
        <ul class="nav navbar-nav">
            <li class="active"><a href="charts.php">Đồ thị</a></li>
            <li><a href="report.php">Báo cáo</a></li>
        </ul>
    </nav>
</div>
<div class="container">
    <form action="charts.php" method="get">
        <div class="row">
            <div class='col-md-4'>
                Nhóm:<select class="form-control" name="groupid" onchange="this.form.submit()">
                    <option value=0>Tất cả</option>
                    <?php
                    foreach ($groups as $key => $value){
                        echo '<option value='.$value['groupid'];
                        if(isset($_SESSION['groupid']) && $_SESSION['groupid'] == $value['groupid']){
                            echo ' selected="selected"';
                        }
                        echo '>'.$value['name'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class='col-md-4'>
                Máy chủ:<select class="form-control" name="hostid" onchange="this.form.submit()">
                    <option value=0>Tất cả</option>
                    <?php
                    foreach ($hosts as $key => $value){
                        echo '<option value='.$value['hostid'];
                        if(isset($_SESSION['hostid']) && $_SESSION['hostid'] == $value['hostid']){
                            echo ' selected="selected"';
                        }
                        echo '>'.$value['name'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class='col-md-4'>
                Đồ thị:<select class="form-control" name="graphid" onchange="this.form.submit()">
                    <option value=0>--- Chọn đồ thị ---</option>
                    <?php
                    foreach ($graphs as $key => $value){
                        echo '<option value='.$value['graphid'];
                        if(isset($_SESSION['graphid']) && $_SESSION['graphid'] == $value['graphid']){
                            echo ' selected="selected"';
                        }
                        echo '>'.$value['name'].'</option>';
                    }
                    ?>
                </select>
            </div>
    </div>
    <div class="row">
        <?php
        if($graphtype['graphtype']==0 || $graphtype['graphtype'] ==1){
            echo "<div class='col-md-12'>
    Trạng thái dữ liệu:<select class='form-control' name='datatype' onchange=\"this.form.submit()\">
    <option value=0";
            if($_SESSION['datatype'] ==0) echo " selected='selected'";
            echo ">Dữ liệu liên tục</option>
    <option value=1";
            if($_SESSION['datatype'] ==1) echo " selected='selected'";
            echo ">Dữ liệu tổng hợp</option>
    </select>
    </div>
";
            echo "</div>
</form>
<div class='row'>
<div id='graphdiv2'
     style='width:100%; height:300px;'></div>
<script type='text/javascript' src=\"data/dataDygraphs.php?graphid=".$_SESSION['graphid']."&datatype=".$_SESSION['datatype']."\" ></script>";
        }elseif ($graphtype['graphtype']==2 || $graphtype['graphtype'] ==3){
            echo '<div class=\'col-md-4\'>
        <div class="form-group">
        Từ ngày:
            <div class=\'input-group date\' id=\'datetimepicker6\'>
                <input type=\'text\'  class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class=\'col-md-4\'>
        <div class="form-group">
        Đến ngày:
            <div class=\'input-group date\' id=\'datetimepicker7\'>
                <input type=\'text\'  class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <button type="submit" style="margin-top: 21px" class="btn btn-primary .btn-lg">Done</button>
        </div>
    </div>
    </div>
        <input type="hidden" id="startDate" name="startDate" value='.$_SESSION['startDate'].' />
        <input type="hidden" id="endDate" name="endDate" value='.$_SESSION['endDate'].' />
    </form>
    <div class="row">
    ';
            echo '<div id="piechart" style="width: 900px; height: 500px;"></div>';
            echo '<script type="text/javascript" src="data/drawGraph.php?graphid='.$_SESSION['graphid'].'&startDate='.$_SESSION['startDate'].'&endDate='.$_SESSION['endDate'].'"></script>';
        }
        ?>
    </div>
</div>
</body>
</html>