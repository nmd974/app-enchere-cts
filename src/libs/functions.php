<?php
//Cette fonction permet de verifier si tous les elements sont bien completes dans le formulaire, de gerer l'enregistrement de l'image et 
//de gerer l'ajout des données dans le tableau
    function validationFormulaireAjoutEnchere () 
    {
        //On verifie que chaque input soient bien completes sauf pour l'image pour laquelle on peut ne pas mettre
        $inputsRequired = ["description", "prix_lancement", "duree", "prix_clic", "augmentation_duree", "augmentation_prix"];
        foreach($inputsRequired as $input){ //Pour chaque elements du tableau on verifie via le $_POSt et le nom de l'input si c'est vide
            if($_POST["$input"] == ""){
                $validationForms = false; //Si oui alors le formulaire n'est pas validé
            }else{
                $validationForms = true; //Sinon le formulaire est validé et on passe à la suite
            }
        };
        //Ici on poursuit si le formulaire est incomplet alors on envoie un message d'erreur et on stop la fonction
        if($validationForms === false){
            echo '
                <div class="col-6 d-flex justify-content-center>    
                <div class="alert alert-danger">Veuillez remplir tous les champs demandés!</div></div>';
        //sinon on va gérer l'image et tester si elle a été ajoutée ou non
        }else{
            $fileName = $_FILES['image_upload']['name']; //On met dans une variable le nom de l'image pour vérifier si l'utilisateur a ajouté une
            if($fileName !== ""){ //On verifie si cette variable n'est pas vide alors
                $validExt = array('.jpg', '.jpeg', '.gif', '.png'); //On spécifie les extensions que l'on souhaite prendre
                if($_FILES['image_upload']['error'] > 0)//On verifie dans la variable $_FILES s'il n'y a pas d'erreur interne
                {
                    echo '<div class="alert alert-danger">Erreur survenue lors du transfert de l\'image</div>'; //Si oui alors on arrete la fonction et on affiche qu'il y a eu une erreur lors du transfert
                    die;
                }
                $maxSize = 10000000; //On spécifie ici la taille maximale de l'image
                $fileSize = $_FILES['image_upload']['size'];//On recupere via la $_FILES la taille de l'image ajoutée dans l'input
                if($fileSize > $maxSize) //Taille de l'image doit être < à $maxSize
                {
                    echo '<div class="alert alert-danger"> Le fichier est trop lourd !!</div>'; //Si trop lourd alors on envoie le message que le fichier est trop lourd
                    die;
                }
                $fileExt = strtolower(substr(strrchr($fileName, '.'), 1)); //On met en minuscule tout le nom du fichier puis à partir du . on récupère tout ce qu'il y a à la suite soit l'extension et on enregistre dans une nouvelle variable
                if(!in_array("." . $fileExt, $validExt))//On recherche dans le tableau des extensions valides si l'extension du fichier ajouté correspond
                { 
                    echo '<div class="alert alert-danger">Le fichier n\'est pas une image !!</div>';
                    die;
                }
                //Arrive ici cela veut dire que nos vérifications on été validées alors on peut procéder à l'envoie de l'image dans son bon dossier
                $tmpName = $_FILES['image_upload']['tmp_name']; //On recupère le nom temporaire ajouté par le serveur pour la gestion de l'image
                $idName = md5(uniqid(rand(), true)); //On attribue un id unique à l'image via la fonction md5 uniqid et random
                $fileDir = "ressources/img/" . $idName . "." . $fileExt; //On spécifie la direction d'enregistrement de l'image
                $_POST['image_upload'] = $idName . "." . $fileExt; //On attribue dans la superglobale $_POST le nom de l'image qui ira dans le tableau
                $resultat = move_uploaded_file($tmpName, $fileDir);//On utilise la fonction de la superglobale pour transferer le nom temporaire attribué vers le dossier indiqué
                //Si le fichier a bien été déplacé alors on ajoute toutes les données dans le tableau et on ajoute les dernieres données necessaires pour une enchere
                if($resultat)
                {
                    //Tout d'abord on attribue un id unique à l'enchere
                    $idEnchere = md5(uniqid(rand(), true)); 
                    $_POST['id'] = $idEnchere;
                    //Attribution de l'etat inactif par défaut d'une enchere
                    $_POST['etat'] = 'inactif';
                    //On ajoute la propriete date_fin à l'enchere à la valeur null cela permettra d'ajouter lors de l'activation de la carte par l'user
                    $_POST['date_fin'] = null;
                    //On ajoute un nombre de clic à 0
                    $_POST['nombre_clic'] = 0;
                    // On ajoute toutes les valeurs de $_POST dans le tableau de la variable session
                    array_push($_SESSION['DUMMY_ARRAY'], $_POST);
                    //On envoie le message de confirmation de l'envoie du formulaire et que tout s'est bien passé
                    echo 
                    '<div class="col-12 d-flex justify-content-center">
                        <div class="alert alert-success">Le produit a bien été ajouté !</div>
                    </div>';         
                }
            }else{ //S'il n'y a pas d'image alors on met une image par défaut
                $_POST['image_upload'] = "no_image.png";//L'image par défaut se nomme no_image.png
                //Tout d'abord on attribue un id unique à l'enchere
                $idEnchere = md5(uniqid(rand(), true)); 
                $_POST['id'] = $idEnchere;
                //Attribution de l'etat inactif par défaut d'une enchere
                $_POST['etat'] = 'inactif';
                //On ajoute la propriete date_fin à l'enchere à la valeur null cela permettra d'ajouter lors de l'activation de la carte par l'user
                $_POST['date_fin'] = null;
                //On ajoute un nombre de clic à 0
                $_POST['nombre_clic'] = 0;
                //On ajoute dans le tableau global
                array_push($_SESSION['DUMMY_ARRAY'], $_POST);
                //On confirme que tout s'est bien passé
                echo '
                <div class="col-12 d-flex justify-content-center">
                    <div class="alert alert-success">Le produit a bien été ajouté !</div>
                </div>';
            };
        };
    };   
?>

<?php 
    //Validation de la modification de l'enchere
    //Comme l'ajout d'une enchere, on va gérer la validation des inputs au cas où les données sont effacées par erreur et la gestion de la nouvelle image s'il y en a une
    function validationFormulaireModificationEnchere(string $id)//On recupere l'id de l'enchere à modifier
    {
        //On verifie que chaque input soient bien completes sauf pour l'image pour laquelle on peut ne pas mettre
        $inputsRequired = ["description", "prix_lancement", "duree", "prix_clic", "augmentation_duree", "augmentation_prix"];
        foreach($inputsRequired as $input){ //Pour chaque elements du tableau on verifie via le $_POSt et le nom de l'input si c'est vide
            if($_POST["$input"] == ""){
                $validationForms = false; //Si oui alors le formulaire n'est pas validé
            }else{
                $validationForms = true; //Sinon le formulaire est validé et on passe à la suite
            }
        };
        //Ici on poursuit si le formulaire est incomplet alors on envoie un message d'erreur et on stop la fonction
        if($validationForms === false){
            echo '
                <div class="col-6 d-flex justify-content-center>    
                <div class="alert alert-danger">Veuillez remplir tous les champs demandés!</div></div>';
        //sinon on va gérer l'image et tester si elle a été ajoutée ou non
        }else{
            $fileName = $_FILES['image_upload']['name']; //On met dans une variable le nom de l'image pour vérifier si l'utilisateur a ajouté une
            $validExt = array('.jpg', '.jpeg', '.gif', '.png'); //On spécifie les extensions que l'on souhaite prendre
            if($_FILES['image_upload']['error'] > 0)//On verifie dans la variable $_FILES s'il n'y a pas d'erreur interne
            {
                echo '<div class="alert alert-danger">Erreur survenue lors du transfert de l\'image</div>'; //Si oui alors on arrete la fonction et on affiche qu'il y a eu une erreur lors du transfert
                die;
            }
            $maxSize = 10000000; //On spécifie ici la taille maximale de l'image
            $fileSize = $_FILES['image_upload']['size'];//On recupere via la $_FILES la taille de l'image ajoutée dans l'input
            if($fileSize > $maxSize) //Taille de l'image doit être < à $maxSize
            {
                echo '<div class="alert alert-danger"> Le fichier est trop lourd !!</div>'; //Si trop lourd alors on envoie le message que le fichier est trop lourd
                die;
            }
            $fileExt = strtolower(substr(strrchr($fileName, '.'), 1)); //On met en minuscule tout le nom du fichier puis à partir du . on récupère tout ce qu'il y a à la suite soit l'extension et on enregistre dans une nouvelle variable
            if(!in_array("." . $fileExt, $validExt))//On recherche dans le tableau des extensions valides si l'extension du fichier ajouté correspond
            { 
                echo '<div class="alert alert-danger">Le fichier n\'est pas une image !!</div>';
                die;
            }
            //Arrive ici cela veut dire que nos vérifications on été validées alors on peut procéder à l'envoie de l'image dans son bon dossier
            $tmpName = $_FILES['image_upload']['tmp_name']; //On recupère le nom temporaire ajouté par le serveur pour la gestion de l'image
            $idName = md5(uniqid(rand(), true)); //On attribue un id unique à l'image via la fonction md5 uniqid et random
            $fileDir = "ressources/img/" . $idName . "." . $fileExt; //On spécifie la direction d'enregistrement de l'image
            $_POST['image_upload'] = $idName . "." . $fileExt; //On attribue dans la superglobale $_POST le nom de l'image qui ira dans le tableau
            $resultat = move_uploaded_file($tmpName, $fileDir);//On utilise la fonction de la superglobale pour transferer le nom temporaire attribué vers le dossier indiqué
            //Si le fichier a bien été déplacé alors on ajoute toutes les données dans le tableau et on ajoute les dernieres données necessaires pour une enchere
            if($resultat)
            {
                foreach($_SESSION['DUMMY_ARRAY'] as $key => $items){//On recherche dan le tableau l'endroit où il y a l'id que l'on cherche pour modifier à cette endroit les proprietes
                    if($items['id'] == $id){
                        //On verifie le nom de l'ancienne image si c'est le no_image alors on ne fait rien sinon on supprime l'ancienne image
                        if($_SESSION['DUMMY_ARRAY'][$key]['image_upload'] !== "no_image.png")
                        {
                            $oldFilename = "ressources/img/" . $_SESSION['DUMMY_ARRAY'][$key]['image_upload'];
                            unlink( $oldFilename);
                        }
                        $_SESSION['DUMMY_ARRAY'][$key]['description'] =  $_POST['description'];
                        $_SESSION['DUMMY_ARRAY'][$key]['prix_lancement'] =  $_POST['prix_lancement'];
                        $_SESSION['DUMMY_ARRAY'][$key]['duree'] = $_POST['duree'];
                        $_SESSION['DUMMY_ARRAY'][$key]['prix_clic'] = $_POST['prix_clic'];
                        $_SESSION['DUMMY_ARRAY'][$key]['augmentation_duree'] = $_POST['augmentation_duree'];
                        $_SESSION['DUMMY_ARRAY'][$key]['augmentation_prix'] = $_POST['augmentation_prix'];
                        $_SESSION['DUMMY_ARRAY'][$key]['image_upload'] = $_POST['image_upload'];
                    }
                }
                //On envoie le message de confirmation de l'envoie du formulaire et que tout s'est bien passé
                echo 
                '<div class="col-12 d-flex justify-content-center">
                <div class="alert alert-success">Le produit a bien été ajouté !</div>
                </div>';         
            }
        };
    };
?>


  

