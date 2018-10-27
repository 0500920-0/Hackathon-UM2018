var today = new Date();
var yesterday = new Date();
yesterday.setDate(today.getDate() - 1);

var today_d = today.getDate();
var today_m = today.getMonth();
var today_y = today.getFullYear();
var yesterday_d = yesterday.getDate();
var yesterday_m = yesterday.getMonth();
var yesterday_y = yesterday.getFullYear();


function dimSeries (chart) {
  $(chart.series).each(function(i, serie){
  $(serie.legendItem.element).hover(function(){
    highlight(chart.series, serie.index, true);
  }, function(){
    highlight(chart.series, serie.index, false);
  });
});

function highlight(series, index, hide) {
  $(series).each(function (i, serie) {
    if(i != index) {
      $.each(serie.data, function (k, data) {
        if(data.series) {
          data.series.graph && data.series.graph.attr("stroke", (hide ? "#D4D4D4": serie.color));
          data.series.markerGroup && data.series.markerGroup.attr("visibility", (hide ? "hidden": "visible"));
        }
      });

    } else {
      serie.group.toFront();
      $.each(serie.data, function (k, data) {
        if(data.series) {
          data.series.graph && data.series.graph.attr("stroke", serie.color);
        }
      });
    }
  });
}
};

function addMaxMin (chart, showType) {
var seriesCnt = chart.series.length;

for (i = 0; i < seriesCnt; i++) {
  if (typeof(chart.series[i].dataMax) != 'undefined') {
    if (showType == 'MAX' || showType == 'MINMAX') {
      var maxIndex = chart.series[i].processedYData.indexOf(chart.series[i].dataMax);
      chart.series[i].points[maxIndex].options.showLabel = true;
      chart.series[i].points[maxIndex].options.labelType = 'MAX';
    }

    if (showType == 'MIN' || showType == 'MINMAX') {
      var minIndex = chart.series[i].processedYData.indexOf(chart.series[i].dataMin);
      chart.series[i].points[minIndex].options.showLabel = true;
      chart.series[i].points[minIndex].options.labelType = 'MIN';
    }
    chart.series[i].isDirty = true;
  }
}
chart.redraw();
};
