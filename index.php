<?php
    include 'config/database.php';

    // getting all agences from the DB
    $queryAllAgence = "SELECT * FROM agence";
    $resultatAllAgence = mysqli_query($db,$queryAllAgence);
    // End of getting all agences 


    // Getting all the table VILLE data
    $queryVille = "SELECT * FROM ville";
    $resultatVille = mysqli_query($db,$queryVille);
    // End of getting all table VILLE data


    // Starting query to insert data in our agence table
    if(isset($_POST['enregister'])){
        // getting data from html input
        $nom = addslashes($_POST['ville']);
        $description = addslashes($_POST['description']);
        $longitude = 0;
        $latitude = 0;


        // check if ville existe and getting longitude and latitude of the vile
        $getVilleQuery = "SELECT * FROM ville WHERE ville='$nom'";
        $getVilleResultat = mysqli_query($db,$getVilleQuery);
        while($ville = mysqli_fetch_assoc($getVilleResultat)){
            $latitude = $ville['lat'];
            $longitude = $ville['lng'];
        }
        //End of getting the lng and lat of the ville in VILLE table


        // Query to insert Agence in our agence table
        $queryAddAgence = "INSERT INTO agence(ville,longitude,latitude,description)
                            VALUES('$nom','$longitude','$latitude','$description')";
        $resultatAddAgence = mysqli_query($db,$queryAddAgence);
        if($resultatAddAgence){
            echo '<script language="Javascript">';
            echo 'document.location.replace("./index.php")'; // -->
            echo ' </script>';
        }
    }

?>


<?php include 'views/index.views.php';?>