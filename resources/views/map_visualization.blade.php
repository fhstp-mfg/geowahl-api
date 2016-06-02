<html>
<body>
  <script src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.3/d3.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/topojson/1.6.9/topojson.min.js"></script>
  <!-- <script type="text/javascript" src="{{ URL::asset('public/assets/js/datamaps.world.min.js') }}"></script> -->
  <script type="text/javascript" src="{{ URL::asset('public/assets/js/datamaps.aut.min.js') }}"></script>
  <div id="container" style="position: relative; width: 1200px; height: 1200px;"></div>
  <script type="text/javascript">
  //basic map config with custom fills, mercator projection
  var width = 1200,
    height = 1200;
  var map = new Datamap({
    scope: 'aut',
    element: document.getElementById('container'),
    fills: {
      defaultFill: '#bada55',
      someKey: '#fa0fa0'
    },
    setProjection: function(element) {
      var projection = d3.geo.albers()
      	.center([0, 45.5])
      	.rotate([-13.8, 0])
      	.parallels([40, 50])
      	.scale(9000)
      	.translate([width / 2, height / 2]);
      var path = d3.geo.path()
        .projection(projection);

      return {path: path, projection: projection};
    },
    data: {
      "AT.WI":{
        "fillKey" : "someKey"
      }
    }
  });
</script>

</body>
</html>
