<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Stigmergy</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <style media="screen">
    body, html {
      margin: 0;
      font-family: Dagny Pro;
    }
    .text {
      font-family: "Comic Sans MS";
    }
    .link,
    .text {
      margin: 1em;
      padding: 1em;
      display: inline-block;
      position: absolute;
      border: 1px solid transparent;
      min-width: 10em;
    }
    .title {
      cursor: text;
    }
    .link:hover,
    .text:hover {
      border: 1px solid #eee;
    }
    .close {
      display: none;
      background: white;
      color: red;
      border: 1px solid red;
      width: 1em;
      height: 1em;
      line-height: .85em;
      text-align: center;
      font-size: 1em;
      position: absolute;
        top: -.5em;
        right: -.5em;
      border-radius: 50%;
      cursor: pointer;
    }
    .link:hover .close,
    .text:hover .close {
      display: block;
    }
    .close:hover {
      background: red;
      color: white;
      border: 1px solid red;
    }
    #canvas {
      width: 100vw;
      height: 100vh;
    }
    #paper {
      position: absolute;
        top: 0;
        left: 0;
      width: 100vw;
      height: 100vh;
    }
    small {
      font-weight: 100;
    }
    .truncate {
      white-space: nowrap;
    }
    .select2 {
      z-index: 1000;
      position:absolute;
        bottom: 0;
        left: 0;
      width: 100vw!important;
    }
    .
    </style>
    <script type="text/javascript">
    var data = [
    <?php
    include 'pinboard-api.php';
    $pinboard = new PinboardAPI('USERNAME', 'PASSWORD');
    $i = 0;
    foreach ($pinboard->get_recent($count = '100') as $p) { ?>
    {
      id: '<?= $i; ?>',
      text: '<?= $p->title; ?>',
      url: '<?= $p->url; ?>',
    },
    <?php
    $i++;
    } ?>];
    </script>
  </head>
  <body>
    <select class="select">
      <option></option>
    </select>
    <canvas id="paper"></canvas>
    <div id="canvas"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://code.interactjs.io/v1.3.4/interact.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.full.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      $(".select").select2({
        data: data,
        placeholder: 'Search for a bookmark'
      });
    });
    $('.select').on('select2:select', function (e) {
      var data = e.params.data;
      $( "#canvas" ).append( "<div class='link draggable'><span class='title' contenteditable>" +data.text+"</span><br><a href='"+data.url+"'><small>"+data.url+"</small></a><span class='close'>x</span></div>" );

      $( ".close" ).click(function() {
        $( this ).parent().remove();
      });
    });
    </script>

    <script type="text/javascript">
    // target elements with the "draggable" class
    interact('.draggable').draggable({
      // enable inertial throwing
      inertia: true,
      // enable autoScroll
      autoScroll: true,

      // call this function on every dragmove event
      onmove: dragMoveListener
      });

      function dragMoveListener (event) {
        var target = event.target,
            // keep the dragged position in the data-x/data-y attributes
            x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
            y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

        // translate the element
        target.style.webkitTransform =
        target.style.transform =
          'translate(' + x + 'px, ' + y + 'px)';

        // update the position attributes
        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);
      }
    </script>
    <script type="text/javascript" src="paper-full.min.js"></script>
    <script type="text/paperscript" canvas="paper">
    var myPath;

    function onMouseDown(event) {
    	myPath = new Path();
      myPath.strokeColor = {
    		hue: Math.random() * 360,
    		saturation: 1,
    		brightness: 1
    	};
    }

    function onMouseDrag(event) {
    	myPath.add(event.point);
    }

    function onClick(event) {
      this.strokeColor = 'red';
    }
    </script>
    <script type="text/javascript">
      $( ".close" ).click(function() {
        $( this ).parent().remove();
      });

      $("body").dblclick(function(event) {
        $( "#canvas" ).append( "<div class='text draggable' style='transform:translate("+event.pageX+"px,"+event.pageY+"px);' data-x='"+event.pageX+"' data-y='"+event.pageY+"'><span class='title' contenteditable>Hello</span><span class='close'>x</span></div>" );
        $( ".close" ).click(function() {
          $( this ).parent().remove();
        });
      });
    </script>
  </body>
</html>
