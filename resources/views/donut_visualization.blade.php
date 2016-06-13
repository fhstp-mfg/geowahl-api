<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>GeoWahl API</title>
  </head>
  <body>
    <style type="text/css">
      .arc text {
        font: 10px sans-serif;
        text-anchor: middle;
        fill: white;
        font-size: 1em;
      }

      .arc path {
        stroke: #fff;
      }

      html,
      body {
        margin:0;
        padding:0;
        width:100%;
        height:100%;
      }

      .chart-container {
        width:100%;
        height:100%;
      }
    </style>
    <input type="hidden" id="visualization_data" value="{{$visData}}" />
    <div class="chart-container"></div>
    <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script>
        //data = JSON.parse($('#visualization_data').val().trim());
        dataAll = JSON.parse($('#visualization_data').val().trim());
        //data = dataAll['data'];
        data = $.parseJSON(dataAll['data']);
        console.log(dataAll['color']);

        var color = d3.scale.ordinal()
        	.range(dataAll['color']);

        var $container = $('.chart-container'),
            τ = 2 * Math.PI,
            width = $container.width(),
            height = $container.height(),
            outerRadius = Math.min(width,height)/2,
            innerRadius = (outerRadius/5)*4,
            fontSize = (Math.min(width,height)/4);

        var arc = d3.svg.arc()
            .innerRadius(innerRadius)
            .outerRadius(outerRadius);

        var svg = d3.select('.chart-container').append("svg")
            .attr("width", '100%')
            .attr("height", '100%')
            .attr('viewBox','0 0 '+Math.min(width,height) +' '+Math.min(width,height) )
            .attr('preserveAspectRatio','xMinYMin')
            .append("g")
            .attr("transform", "translate(" + Math.min(width,height) / 2 + "," + Math.min(width,height) / 2 + ")");

        var pie = d3.layout.pie()
        	.sort(null)
        	.value(function (d) {
        		return d.votes;
        	});

        var g = svg.selectAll(".arc")
        	.data(pie(data))
        	.enter().append("g")
        	.attr("class", "arc");

        g.append("path")
        	.attr("d", arc)
        	.style("fill", function (d) {
        		return color(d.data.name);
        	});

        g.append("text")
        	.attr("transform", function (d) {
        		return "translate(" + arc.centroid(d) + ")";
        	})
        	.attr("dy", ".35em")
        	.text(function (d) {
        		return d.data.name;
        	});

        g.append("text")
          .attr("transform", function (d) {
            return "translate(" + arc.centroid(d) + ")";
          })
          .attr("dy", "1.8em")
          .text(function (d) {
            return d.data.percent + "%";
        });

        function type(d) {
        	d.votes = +d.votes;
        	return d;
        }
    </script>
    <!-- <pre>
      {{$visData}}
    </pre> -->
  </body>
</html>
