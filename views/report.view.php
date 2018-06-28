<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <link rel="StyleSheet" type="text/css" href="css/table.css">
</head>
<body>
<div class="container-fluid">
    <nav class="navbar navbar-custom">
        <ul class="nav navbar-nav">
            <li><a href="charts.php">Đồ thị</a></li>
            <li class="active"><a href="report.php">Báo cáo</a></li>
        </ul>
    </nav>
</div>
<div class="container-fluid">
    <form action="report.php" method="get">
        <div class="row">
            <div class='col-md-6'>
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
            <div class='col-md-6'>
                Máy chủ:<select class="form-control" id="hostid" name="hostid" onchange="this.form.submit();">
                    <option value=0>---Chọn máy chủ---</option>
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
        </div>
        <div style="height: 10px;"></div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    Từ ngày:
                    <div class="input-group date" id="datetimepicker6">
                        <input type="text" class="form-control">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    Đến ngày:
                    <div class="input-group date" id="datetimepicker7">
                        <input type="text" class="form-control">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <h2>Danh sách các thông tin lưu trữ </h2>
    </div>
    <form action="report/createReport.php" method="POST">
        <input type="hidden" id="startDate" name="startDate" value="">
        <input type="hidden" id="endDate" name="endDate" value="">
        <input type="hidden" id="hostname" name="hostname" value="" >
        <div class="row">
            <table class="table">
                <thead>
                <tr class="TableHead">
                    <th class="col-md-1">
                        <input type="checkbox" name="chkall" id="chkall"  onclick="javascript:selectAll();">
                    </th>
                    <th class="col-md-2" >STT</th>
                    <th class="col-md-9">Từ khóa</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(isset($items)){
                    $i=1;
                    foreach ($items as $value){
//                        table-row;
                        echo "<tr class=\"UnselectedRow\" id='number$i'";
                        if($i>10) echo "style='display: none;'";
                        echo " onclick=\"javascript:rowClick(this);\">
                                  <td>
                                    <input type=\"checkbox\" name=\"chk[]\" id=\"chk$i\" value=\"".$value['itemid']."\" onclick=\"javascript:chkClick(this);\">
                                  </td>
                                  <td>$i</td>
                                  <td>".$value['name']."</td>
                              </tr>";
                        $i++;
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="row">
            <ul class="pagination pagination-lg">
                <li><a id="prev" href="#">Previous</a></li>
                <?php
                if(isset($items)){
                    $count=count($items);
                    $i=(int)(($count-1)/10)+1;
                    for($c=1;$c<=$i;$c++){
                        if($c==1)
                            echo "<li><a class='active page' id='page$c' href=\"#\">$c</a></li>";
                        else
                            echo "<li><a class='page' id='page$c' href=\"#\">$c</a></li>";
                    }
                }
                ?>
                <li><a id="next" href="#">Next</a></li>
            </ul>
            <!--        <ul class="pagination pagination-lg">-->
            <!--            <li><button>Previous</button></li>-->
            <!--            <li><button>1</button></li>-->
            <!--            <li><button>2</button></li>-->
            <!--            <li><button>3</button></li>-->
            <!--            <li><button>4</button></li>-->
            <!--            <li><button>5</button></li>-->
            <!--            <li><button>Next</button></li>-->
            <!--        </ul>-->
        </div>
        <div id="submit"><button class="btn btn-primary btn-lg" type="submit" ">Chọn</button></div>
    </form>
</div>
<script type="text/javascript" src="js/table.js"></script>
<script>
    var count=<?php echo $count; ?>;
    var countpage=<?php echo $i; ?>;
    $('#next').click(function () {
        var active=$('.page.active');
        var id = active.attr('id').replace(/page/, '')-1;
        if(id < countpage-1){
            active.removeClass('active');
            $("#page"+(id+2)).addClass('active');
            for(var i=1;i<=count;i++){
                $("#number"+i).css("display", "none");
            }
            for(var i=1;i<=10;i++){
                var tmp=((id+1)*10)+i;
                $("#number"+tmp).css("display", "table-row");
            }
        }
    });
    $('#prev').click(function () {
        var active=$('.page.active');
        var id = active.attr('id').replace(/page/, '')-1;
        if(id > 0) {
            active.removeClass('active');
            $("#page" + id).addClass('active');
            for (var i = 1; i <= count; i++) {
                $("#number" + i).css("display", "none");
            }
            for (var i = 1; i <= 10; i++) {
                var tmp = ((id-1) * 10) + i;
                $("#number" + tmp).css("display", "table-row");
            }
        }
    });

    $('.page').click(function () {
        var id = $(this).attr('id').replace(/page/, '')-1;
        $('.page.active').removeClass('active');
        $("#page"+(id+1)).addClass('active');
        for(var i=1;i<=count;i++){
            $("#number"+i).css("display", "none");
        }
        for(var i=1;i<=10;i++){
            var tmp=(id*10)+i;
            $("#number"+tmp).css("display", "table-row");
//            table-row
        }
    });
    $('#hostname').val($("#hostid option:selected").text());
    $(function () {
        $('#datetimepicker6').datetimepicker({
            format: 'DD/MM/YYYY HH:mm',
            maxDate: new Date(),
            date:new Date(),
        });
        $('#datetimepicker7').datetimepicker({
            format: 'DD/MM/YYYY HH:mm',
            minDate:new Date(),
            maxDate: new Date(),
            date:new Date(),
            useCurrent: false //Important! See issue #1075
        });
        $("#startDate").val(Math.floor(Date.now() / 1000));
        $("#endDate").val(Math.floor(Date.now() / 1000));
        $("#datetimepicker6").on("dp.change", function (e) {
            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
            $("#startDate").val(e.date.unix());

        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
            $("#endDate").val(e.date.unix());
        });
    });

</script>
</body>
</html>