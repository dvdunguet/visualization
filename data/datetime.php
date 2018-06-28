$(function () {
    $('#datetimepicker6').datetimepicker({
        format: 'DD/MM/YYYY HH:mm',
        minDate: new Date(<?php if(isset($minTime)) echo $minTime*1000; else echo time()-604800?>),
    maxDate: new Date(),
        date:new Date(<?php echo $_GET['startDate']*1000 ?>),
});
    $('#datetimepicker7').datetimepicker({
        format: 'DD/MM/YYYY HH:mm',
        minDate: new Date(<?php if(isset($minTime)) echo $minTime*1000; else echo time()-604800?>),
    maxDate: new Date(),
        date:new Date(<?php echo $_GET['endDate']*1000 ?>),
    useCurrent: false //Important! See issue #1075
});
    $("#datetimepicker6").on("dp.change", function (e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
        $("#startDate").val(e.date.unix());

    });
    $("#datetimepicker7").on("dp.change", function (e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        $("#endDate").val(e.date.unix());
    });

});