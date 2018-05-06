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
<div class="container">
    <form action="report.php" method="get">
        <div class="row">
            <div class='col-md-6'>
                Group:<select class="form-control" name="groupid" onchange="this.form.submit()">
                    <option value=0>all</option>
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
                Host:<select class="form-control" id="hostid" name="hostid" onchange="this.form.submit();">
                    <option value=0>all</option>
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
        <h2>List items</h2>
    </div>
    <form action="report/createReport.php" method="POST">
        <input type="hidden" id="startDate" name="startDate" value="">
        <input type="hidden" id="endDate" name="endDate" value="">
        <input type="hidden" id="hostname" name="hostname" value="" >
        <div class="row">
            <table class="table">
                <thead>
                <tr class="TableHead">
                    <th>
                        <input type="checkbox" name="chkall" id="chkall"  onclick="javascript:selectAll();">
                    </th>
                    <th>No</th>
                    <th>Key name</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(isset($items)){
                    $i=1;
                    foreach ($items as $value){
                        echo "<tr class=\"UnselectedRow\" onclick=\"javascript:rowClick(this);\">
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
        <div id="submit"><button class="btn btn-primary btn-lg" type="submit" ">Chọn</button></div>

    </form>
</div>
<script type="text/javascript" src="js/table.js"></script>
<script>
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