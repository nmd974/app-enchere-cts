<?php 

//Ici on gere la modification de l'état de l'enchere Si c'est le bouton activer ou desactiver
if(isset($_POST['submit_activer'])){
    $id = $_POST['indice'];//On stock dans l'input du nom indice l'id en valeur mais il est en display none afin de ne pas passer par l'url
    
    foreach($_SESSION['DUMMY_ARRAY'] as $key => $items){//pour chaque enchere on va chercher quel endroit du tableau se trouve celui dont on veut modifier selon l'id
        if($items['id'] == $id){
            date_default_timezone_set("Indian/Reunion");
            $_SESSION['DUMMY_ARRAY'][$key]['date_fin'] = mktime(date("H")+ (int)$items['duree'], date("i"), date("s"), date("m"), date("d"), date("Y"));
            $_SESSION['DUMMY_ARRAY'][$key]['etat'] =  'actif'; //A l'emplacement (key) du tableau on change l'etat qui est actif et la date de fin e nsecondes
        }
    }
}
if(isset($_POST['submit_desactiver'])){
    $id = $_POST['indice'];
    foreach($_SESSION['DUMMY_ARRAY'] as $key => $items){
        if($items['id'] == $id){
            $_SESSION['DUMMY_ARRAY'][$key]['etat'] =  'inactif'; //A l'emplacement (key) du tableau on change l'etat en inactif
        }
    }
}
?>


<div id="articles" class="container-fluid mt-5">
    <h2 class="text-center mb-5 font-weight-bold">ARTICLES AJOUTES</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="align-middle text-center" scope="col">Image</th>
                    <th class="align-middle text-center" scope="col">Decription</th>
                    <th class="align-middle text-center" scope="col">Etat</th>
                    <th class="align-middle text-center" scope="col">Prix de lancement</th>
                    <th class="align-middle text-center" scope="col">Durée de l'enchère</th>
                    <th class="align-middle text-center" scope="col">Prix du clic</th>
                    <th class="align-middle text-center" scope="col">Augmentation du prix</th>
                    <th class="align-middle text-center" scope="col">Augmentation durée</th>
                    <th class="align-middle text-center" scope="col">Activer / Desactiver</th>
                </tr>
            </thead>
            <tbody>

            <!--Boucle pour chaque items dans le tableau dans la variable session-->
            <?php foreach(array_reverse($_SESSION['DUMMY_ARRAY']) as $items) :?>
                <tr>
                    <td id="<?= $items['id'] ?>" class="">
                        <img src="ressources/img/<?= $items['image_upload'] ?>" alt="" class="img-thumbnail"
                            style="max-width: 150px; border: none;">
                    </td>
                    <td class="align-middle text-center"><?= $items['description'] ?></td>
                    <td class="align-middle text-center"><?= $items['etat'] == 'actif' ? 'Actif' : 'Inactif' ?></td>
                    <td class="align-middle text-center"><?= $items['prix_lancement'] ?> €</td>
                    <td class="align-middle text-center"><?= $items['duree']?> h</td>
                    <td class="align-middle text-center"><?= $items['prix_clic'] ?> €</td>
                    <td class="align-middle text-center"><?= $items['augmentation_prix'] ?> €</td>
                    <td class="align-middle text-center"><?= $items['augmentation_duree'] ?> sec</td>
                    <td class="align-middle text-center">
                        <form method="POST" enctype="multipart/form-data" action="#<?= $items['id']?> ">
                            <input name="indice" value="<?= $items['id'] ?>" style="display: none;">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary btn-manager w-50
                                <?php // Si l'état de l'article est 'actif', on ajoute la class 'active' au bouton
                                $classActive = "";
                                if($items['etat'] == 'actif') {
                                    $classActive = "active";
                                } else {
                                    $classActive = "";
                                }
                                echo $classActive;
                                ?>" name="">
                                    <button id="option1" class="bg-transparent border-0 text-white font-weight-bold" 
                                    name="submit_activer" 
                                    <?php // Si l'état de l'article est 'actif', on ajoute l'attribut 'disabled' au bouton
                                    $attrDisabled = "";
                                    if($items['etat'] == 'actif') {
                                        $attrDisabled = "disabled";
                                    } else {
                                        $attrDisabled = "";
                                    }
                                    echo $attrDisabled;
                                    ?>>Activer</button>
                                </label>
                                <label class="btn btn-secondary btn-manager w-50
                                <?php // Si l'état de l'article est 'inactif', on ajoute la class 'active' au bouton
                                $classActive = "";
                                if($items['etat'] == 'inactif') {
                                    $classActive = "active";
                                } else {
                                    $classActive = "";
                                }
                                echo $classActive;
                                ?>" name="">
                                    <button id="option2" class="bg-transparent border-0 text-white font-weight-bold pr-4" 
                                    name="submit_desactiver"
                                    <?php // Si l'état de l'article est 'inactif', on ajoute l'attribut 'disabled' au bouton
                                    $attrDisabled = "";
                                    if($items['etat'] == 'inactif') {
                                        $attrDisabled = "disabled";
                                    } else {
                                        $attrDisabled = "";
                                    }
                                    echo $attrDisabled;
                                    ?>>Desactiver</button>
                                </label>
                            </div> 
                        </form>
                        <!--Gestion_des_modifications_apporter_aux_enchères_david-->
                        <!--Ajout du boutton modifier qui envoie à la page de modification_formulaire_david-->
                        <form method="POST" action="modificationenchere.php?id=<?=$items['id']?>" class="d-flex justify-content-center">
                        <!--Nous partons sur le principe qu'une enchere déjà activée ne peut pas être modifiée-->
                            <button 
                                class="btn btn-light  mt-2" 
                                type="submit" 
                                value="1"
                                class="btn btn-warning p-0 align-items-center" 
                                <?php if($items['etat'] == "actif"){echo "disabled";};?>>
                                    Modifier
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
