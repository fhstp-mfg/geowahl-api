<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>GeoWahl API</title>

    <style>
      .arc text {
        font: 10px sans-serif;
        text-anchor: middle;
        fill: white;
        font-size: 1em;
      }

      .arc path {
        stroke: #fff;
      }
    </style>
  </head>

  <body>
    <input type="hidden" id="visualization_data" value="{{ $visData }}">

    <div id="chart"></div>

    <!-- scripts -->
    <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
      integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
      crossorigin="anonymous">
    </script>

    <script>
        data = JSON.parse($('#visualization_data').val().trim());
        var width  = 960,
            height = 500,
            radius = Math.min(width, height) / 2;

        var color = d3.scale.ordinal()
          .range([
            "#98abc5",
            "#8a89a6",
            "#7b6888",
            "#6b486b",
            "#a05d56",
            "#d0743c",
            "#ff8c00"
          ]);

        var arc = d3.svg.arc()
          .outerRadius(radius - 10)
          .innerRadius(radius - 70);

        var pie = d3.layout.pie()
          .sort(null)
          .value(function (d) {
            return d.votes;
          });

        var svg = d3.select("body").append("svg")
          .attr("width", width)
          .attr("height", height)
          .append("g")
          .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

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

        function type(d) {
          d.votes = +d.votes;
          return d;
        }
    </script>

    <!-- debug -->
    <!-- <pre>
      {{ $visData }}
    </pre> -->
  </body>
</html>
