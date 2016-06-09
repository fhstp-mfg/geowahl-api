<html>
<body>
  <style>
  .gemeinden {
    fill: #bbb;
  }

  .gemeinden-boundary {
    fill: none;
    stroke: #fff;
    stroke-linejoin: round;
  }

  text {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 10px;
    text-anchor: middle;
  }

  </style>
  <script src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.3/d3.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/topojson/1.6.20/topojson.js"></script>
  <!-- <script type="text/javascript" src="{{ URL::asset('public/assets/js/datamaps.world.min.js') }}"></script> -->
  <script type="text/javascript" src="{{ URL::asset('public/assets/js/datamaps.aut.min.js') }}"></script>
  <!-- <div id="container" style="position: relative; width: 1200px; height: 1200px;"></div> -->
  <script type="text/javascript">
  //basic map config with custom fills, mercator projection
  // var width = 1200,
  //   height = 1200;
  // var map = new Datamap({
  //   scope: 'aut',
  //   element: document.getElementById('container'),
  //   fills: {
  //     defaultFill: '#bada55',
  //     someKey: '#fa0fa0'
  //   },
  //   setProjection: function(element) {
  //     var projection = d3.geo.albers()
  //     	.center([0, 45.5])
  //     	.rotate([-13.8, 0])
  //     	.parallels([40, 50])
  //     	.scale(9000)
  //     	.translate([width / 2, height / 2]);
  //     var path = d3.geo.path()
  //       .projection(projection);
  //
  //     return {path: path, projection: projection};
  //   },
  //   data: {
  //     "AT.WI":{
  //       "fillKey" : "someKey",
  //       "labels" : "Hofer"
  //     }
  //   }
  // });
  var width = 960,
	height = 1160;

  var svg = d3.select('body').append('svg')
      .attr('width', width)
      .attr('height', height);

  var projection = d3.geo.albers()
      .center([0, 45.5])
      .rotate([-13.8, 0])
      .parallels([40, 50])
      .scale(9000)
      .translate([width / 2, height / 2]);
  // var projection = d3.geo.albersUsa()
  //     .scale(1000)
  //     .translate([width / 2, height / 2]);
  // https://github.com/mbostock/d3/wiki/Geo-Projections#wiki-translate

  var path = d3.geo.path()
      .projection(projection);

  // d3.json('public/assets/json/gemeinden_topojson.json', function(error, us) {
  //     svg.append('path')
  //         .datum(topojson.feature(us, us.objects.state))
  //       //  .attr('class', 'states') // defined in CSS
  //         .attr('d', path);
  // });
  // d3.json('public/assets/json/gemeinden_topojson.json', function(error, aut) {
  //   if (error) return console.error(error);
  //   console.log(aut);
  //   svg.selectAll(".subunit")
  //     .data(topojson.feature(aut, aut.objects.gemeinden).features)
  //     .enter().append("path")
  //     .attr("class", "gemeinden")
  //     // .attr("class", function(d) {
  //     //   return "subunit " + d.id;
  //     // })
  //     .attr("d", path);
  // });
  // if (error) throw error;

  d3.json("public/assets/json/gemeinden_topojson.json", function(error, aut) {
    if (error) throw error;

    var gemeinden = topojson.feature(aut, aut.objects.gemeinden);
    console.log(aut);
    svg.append("path")
        .datum(gemeinden)
        .attr("class", "gemeinden")
        .attr("d", path);

    svg.append("path")
        .datum(topojson.mesh(aut, aut.objects.gemeinden, function(a, b) { return a !== b; }))
        .attr("class", "gemeinden-boundary")
        .attr("d", path);

    svg.selectAll("text")
        .data(gemeinden.features)
      .enter().append("text")
        .attr("transform", function(d) { return "translate(" + path.centroid(d) + ")"; })
        .attr("dy", ".35em")
        .text(function(d) { return d.properties.name; });
  });

</script>

</body>
</html>
