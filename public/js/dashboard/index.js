$(document).ready(function(){
    loadDataDashboard();
 });
 $("#min-date, #min-date2, #min-date3").datepicker( {
    format: "M yyyy",
    viewMode: "months", 
    minViewMode: "months"
}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
    setDateValue(ev.date);
    loadDataDashboard();
});

function setDateValue(date){
  $("#min-date").datepicker("update", date);
  $("#min-date2").datepicker("update", date);
  $("#min-date3").datepicker("update", date);  
  $("#timepicker").val(changeFormatDateToMonth(date));
}

function loadDataDashboard(){
    loadInitChart();
    let periode = $("#timepicker").val();
    $.get(link+"/home/data_dashboard?periode="+periode+"&user_id="+$("#user_id").val(), function(data){
        $("#d-total_payment").html(data.total_payment);
        $("#d-settlement").html(data.settlement);
        $("#d-callback").html(data.fail_callback);
    });
}

function changeFormatDateToMonth(date){
  var date = new Date(date);
  var month = date.getMonth()+1;
  if(month < 10){
    month = "0"+month;
  }
  var year = date.getFullYear();
  return year+'-'+month;      
}


function loadInitChart(){
    let periode = $("#timepicker").val();
    $.get(link+"/home/grafik_transaction?periode="+periode+"&user_id="+$("#user_id").val(), function(data){
        var ctx1 = document.getElementById("chart1").getContext("2d");
        var arrayPeriode = [];
        var jumlahData = [];
        var jumlahData2 = [];
        var periode = data.day;
        var seluruh = data.payment;
        var dataku = data.settlement;
        for (var i = 0; i < periode.length; i++) {
            arrayPeriode.push(periode[i]);
            jumlahData.push(seluruh[i]);
            jumlahData2.push(dataku[i]);
        }

        var data1 = {
            labels: arrayPeriode,
            datasets: [
                {
                    label: "Data Seluruh",
                    fillColor: "#009efb",
                    strokeColor: "#009efb",
                    pointColor: "#009efb",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "#009efb",
                    data: jumlahData
                },
                    {
                    label: "Dataku",
                    fillColor: "#28a745",
                    strokeColor: "#28a745",
                    pointColor: "#28a745",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "#28a745",
                    data: jumlahData2
                }
                
            ],
            
        };
        Chart.types.Line.extend({
            name: "LineAlt",
            initialize: function () {
            Chart.types.Line.prototype.initialize.apply(this, arguments);

            var ctx = this.chart.ctx;
            var originalStroke = ctx.stroke;
            ctx1.stroke = function () {
                ctx1.save();
                ctx1.shadowBlur = 10;
                ctx1.shadowOffsetX = 8;
                ctx1.shadowOffsetY = 8;
                originalStroke.apply(this, arguments)
                ctx1.restore();

            }
            }
        });
        var chart1 = new Chart(ctx1).LineAlt(data1, {
            scaleShowGridLines : true,
            scaleGridLineWidth : 0,
            scaleShowHorizontalLines: true,
            scaleShowVerticalLines: true,
            bezierCurve : true,
            bezierCurveTension : 0.4,
            pointDot : true,
            pointDotRadius : 4,
            pointDotStrokeWidth : 2,
            pointHitDetectionRadius : 2,
            datasetStroke : true,
            tooltipCornerRadius: 2,
            datasetStrokeWidth : 0,
            datasetFill : false,            
            responsive: true,
            legend: {
                    display: true
            }
        });
    });
}
