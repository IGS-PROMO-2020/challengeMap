
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Liste des agences</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.5/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
    <style>
        .row {
            margin:15px 0;
        }
        label {
            margin-bottom: 0;
            margin-left: 1px;
        }
    </style>
  </head>
  <body>
 
  <div>
    <div class="row">
    <div class="col-lg-8 col-md-6"> <div id="mapdiv"></div></div>

      <div class="col-lg-4 col-md-6">
          <h3>FORMULAIRE D’ENREGISTREMENT</h3>
        <form action="index.php" method="post" class="form-inline">
          <div class="row">
            <div class="form-group">
              <label for="ville">Ville</label> <br>
                <select name="ville" id="ville" class="form-control" style="width: 100%">
                    <option value="">Choisir une ville</option>
                    <?php while($ville = mysqli_fetch_assoc($resultatVille)) :?>
                        <option value="<?php echo $ville['ville'];?>"><?php echo $ville['ville'];?></option>
                    <?php endwhile ?>
                </select>
            </div>
            <div class="form-group">
              <label for="description">Description de l'agence</label> <br>
              <input type="text" class="form-control" id="description" name="description" placeholder="agence de yopland Tel :+20000">
            </div>
            <div class="form-group">
                <br>
                <input type="submit" value="Enregister" name='enregister' class="btn btn-primary">
            </div>
          </div>
          
        </form>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

  <script>
      // ijecting select2 on the ville input
    $(document).ready(function() {
        $('#ville').select2();
    });
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/openlayers/2.11/lib/OpenLayers.js"></script>
  <script>




// STARTING THE MAP IMPLEMENTATION
    let map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());
    epsg4326 =  new OpenLayers.Projection("EPSG:4326"); //WGS 1984 projection
    projectTo = map.getProjectionObject(); //The map projection (Spherical Mercator)

    var lonLat = new OpenLayers.LonLat( -4.0165792 ,5.3614244 ).transform(epsg4326, projectTo);


    var zoom=12;
    map.setCenter (lonLat, zoom);

    var vectorLayer = new OpenLayers.Layer.Vector("Overlay");


    <?php while($agence = mysqli_fetch_assoc($resultatAllAgence)):?>
       // Define markers as "features" of the vector layer:
      var feature = new OpenLayers.Feature.Vector(
              new OpenLayers.Geometry.Point( 
                <?php echo $agence["longitude"]?>, <?php echo $agence["latitude"]?>).transform(epsg4326, projectTo),
              {description:"<h1><?php echo $agence['ville']?></h1><p><?php echo $agence['description']?></p>"} ,
              {externalGraphic: 'assets/img/marker.png', graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25  }
          );
      vectorLayer.addFeatures(feature);
    <?php endwhile ?>


    map.addLayer(vectorLayer);


    //Add a selector control to the vectorLayer with popup functions
    var controls = {
      selector: new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })
    };

    function createPopup(feature) {
      feature.popup = new OpenLayers.Popup.FramedCloud("pop",
          feature.geometry.getBounds().getCenterLonLat(),
          null,
          '<div class="markerContent">'+feature.attributes.description+'</div>',
          null,
          true,
          function() { controls.selector.unselectAll(); }
      );
      feature.popup.closeOnMove = true;
      map.addPopup(feature.popup);
    }

    function destroyPopup(feature) {
      feature.popup.destroy();
      feature.popup = null;
    }

    map.addControl(controls['selector']);
    controls['selector'].activate();

// END OF THE MAP IMPLEMENTATION





  </script>
</body></html>
