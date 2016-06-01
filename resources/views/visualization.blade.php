<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Step 2 - A Basic Donut Chart</title>
  </head>
  <body>
    <div id="chart"></div>
    <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script>
      (function(d3) {
        'use strict';

        var dataset = [
          { label: 'Abulia', count: 10 },
          { label: 'Betelgeuse', count: 20 },
          { label: 'Cantaloupe', count: 30 },
          { label: 'Dijkstra', count: 40 }
        ];

        var width = 360;
        var height = 360;
        var radius = Math.min(width, height) / 2;
        var donutWidth = 75;                            // NEW

        var color = d3.scale.category20b();

        var svg = d3.select('#chart')
          .append('svg')
          .attr('width', width)
          .attr('height', height)
          .append('g')
          .attr('transform', 'translate(' + (width / 2) +
            ',' + (height / 2) + ')');

        var arc = d3.svg.arc()
          .innerRadius(radius - donutWidth)             // NEW
          .outerRadius(radius);

        var pie = d3.layout.pie()
          .value(function(d) { return d.count; })
          .sort(null);

        var path = svg.selectAll('path')
          .data(pie(dataset))
          .enter()
          .append('path')
          .attr('d', arc)
          .attr('fill', function(d, i) {
            return color(d.data.label);
          });

      })(window.d3);
    </script>
  </body>
  <pre>
  </pre>
</html>
