

  <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="http://yandex.st/highlightjs/8.0/styles/default.min.css" rel="stylesheet">
  <link href="../src/loading.css" rel="stylesheet">
  

  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

  <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.4.2/randomColor.min.js"></script>
  <script src="../src/loading.js"></script>



  <div class="example col-lg-4 col-sm-6">
    <h3>Controls with events handling</h3>

    <button id="loading-control-btn" class="btn btn-primary btn-block">Click me to start loading</button>

    <div id="loading-control" class="loading-div">
      This div is button controled, using the events `loading.start` and `loading.stop` to change the state (text) of the button
    </div>

    <script>
      $('#loading-control-btn').click(function() {
        $('#loading-control').loading('toggle');
      });

      $('#loading-control').on('loading.start', function() {
        $('#loading-control-btn').html('Click me to stop loading');
      }).on('loading.stop', function() {
        $('#loading-control-btn').html('Click me to start loading');
      });
    </script>
  </div>
