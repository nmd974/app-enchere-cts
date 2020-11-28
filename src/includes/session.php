<?php
    // Start the session
    session_start();
    date_default_timezone_set("Indian/Reunion"); //On définie l'heure à la reunion
    //Si la session est déjà lancée alors on en redefini pas le tableau
    if(!isset($_SESSION['DUMMY_ARRAY'])){
        $_SESSION['DUMMY_ARRAY'] = [
            [
                'id' => 1,
                'description' => 'Iphone X',
                'prix_lancement' => 1,
                'duree' => 48,
                'prix_clic' => 0.50,
                'augmentation_duree' => 30,
                'augmentation_prix' => 0.50,
                'image_upload' => 'no_image.png',
                'date_fin' => 1606345200,
                'etat' => 'inactif',
                'nombre_clic' => 0
            ]
        ];
    }
?>