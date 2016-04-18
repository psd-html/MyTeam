<?php if (!defined('PLX_ROOT')) exit; 
# Control du token du formulaire
plxToken::validateFormToken($_POST);

# nombre de membres existants
$nbmembres = floor(sizeof($plxPlugin->getParams())/6);
if(!empty($_POST)) {


	# configuration de la page 
	if (!empty($_POST['mnuDisplay']) AND !empty($_POST['mnuName']) AND !empty($_POST['mnuPos']) AND !empty($_POST['template']))  {

		$plxPlugin->setParam('mnuInfo', $_POST['mnuInfo'], 'cdata');
		$plxPlugin->setParam('mnuDisplay', $_POST['mnuDisplay'], 'numeric');
		$plxPlugin->setParam('mnuName', $_POST['mnuName'], 'cdata');
		$plxPlugin->setParam('mnuPos', $_POST['mnuPos'], 'numeric');
		$plxPlugin->setParam('template', $_POST['template'], 'string');
		$plxPlugin->setParam('champs1', $_POST['champs1'], 'string');
		$plxPlugin->setParam('champs2', $_POST['champs2'], 'string');
		$plxPlugin->saveParams();
	}


	if (!empty($_POST['nom-new']) AND !empty($_POST['prenom-new']) AND !empty($_POST['fonction-new']))  {
        # création d'un nouveau membre
        $newmembre = $nbmembres + 1;
		$plxPlugin->setParam('nom'.$newmembre, plxUtils::strCheck($_POST['nom-new']), 'cdata');
		$plxPlugin->setParam('prenom'.$newmembre, plxUtils::strCheck($_POST['prenom-new']), 'cdata');
		$plxPlugin->setParam('fonction'.$newmembre, plxUtils::strCheck($_POST['fonction-new']), 'cdata');
		$plxPlugin->setParam('telephone'.$newmembre, plxUtils::strCheck($_POST['telephone-new']), 'cdata');
		$plxPlugin->setParam('avatar'.$newmembre, plxUtils::strCheck($_POST['avatar-new']), 'cdata');
		$plxPlugin->setParam('mail'.$newmembre, plxUtils::strCheck($_POST['mail-new']), 'cdata');
        $plxPlugin->saveParams();
        
	}else{
        
        # Mise à jour des membres existants
        for($i=1; $i<=$nbmembres; $i++) {
            if($_POST['delete'.$i] != "1" AND !empty($_POST['nom'.$i]) AND !empty($_POST['prenom'.$i]) AND !empty($_POST['fonction'.$i])){ // si on ne supprime pas et que les commentaires ne sont pas vide
                
                #mise a jour du auteur et commentaire
                $plxPlugin->setParam('nom'.$i, plxUtils::strCheck($_POST['nom'.$i]), 'cdata');
                $plxPlugin->setParam('prenom'.$i, plxUtils::strCheck($_POST['prenom'.$i]), 'cdata');
                $plxPlugin->setParam('fonction'.$i, plxUtils::strCheck($_POST['fonction'.$i]), 'cdata');
                $plxPlugin->setParam('telephone'.$i, plxUtils::strCheck($_POST['telephone'.$i]), 'cdata');
                $plxPlugin->setParam('mail'.$i, plxUtils::strCheck($_POST['mail'.$i]), 'cdata');
                $plxPlugin->setParam('avatar'.$i, plxUtils::strCheck($_POST['avatar'.$i]), 'cdata');
                $plxPlugin->saveParams();
            
            }elseif($_POST['delete'.$i] == "1"){
                $plxPlugin->setParam('nom'.$i, '', '');
                $plxPlugin->setParam('prenom'.$i, '', '');
                $plxPlugin->setParam('fonction'.$i, '', '');
                $plxPlugin->setParam('telephone'.$i, '', '');
                $plxPlugin->setParam('mail'.$i, '', '');
                $plxPlugin->setParam('avatar'.$i, '', '');
                $plxPlugin->saveParams();
            }
        }
    }
}
	# mise à jour du nombre de membres existants
	$nbmembres = floor(sizeof($plxPlugin->getParams())/2);
	

	$mnuDisplay =  $plxPlugin->getParam('mnuDisplay')=='' ? 1 : $plxPlugin->getParam('mnuDisplay');
	$mnuName =  $plxPlugin->getParam('mnuName')=='' ? 'MyTeam' : $plxPlugin->getParam('mnuName');
	$mnuPos =  $plxPlugin->getParam('mnuPos')=='' ? 2 : $plxPlugin->getParam('mnuPos');
	$template = $plxPlugin->getParam('template')=='' ? 'static.php' : $plxPlugin->getParam('template');
	$label1 = $plxPlugin->getParam('champs1')=='' ? 'Info 1' : $plxPlugin->getParam('champs1');
	$label2 = $plxPlugin->getParam('champs2')=='' ? 'Info 2' : $plxPlugin->getParam('champs2');

	# On récupère les templates des pages statiques
	$files = plxGlob::getInstance(PLX_ROOT.'themes/'.$plxAdmin->aConf['style']);
	if ($array = $files->query('/^static(-[a-z0-9-_]+)?.php$/')) {
		foreach($array as $k=>$v)
			$aTemplates[$v] = $v;
	}
?>	


<p>
	<h2><?php echo $plxPlugin->getInfo("description") ?></h2>
</p>


<style>
	input, textarea {border-radius: 5px;width: 40%}
	input.numeric{width: 100px}
	textarea {min-height: 50px}
	label{font-style: italic}
	td>input{width: 100%}
</style>



<form action="parametres_plugin.php?p=MyTeam" method="post">

	<!-- navigation sur la page configuration du plugin -->
	<nav id="tabby-1" class="tabby-tabs" data-for="example-tab-content">
		<ul>
			<li><a data-target="tab1" class="active" href="#"><?php $plxPlugin->lang('L_NAV_LIEN1') ?></a></li>
			<li><a data-target="tab2" href="#"><?php $plxPlugin->lang('L_NAV_LIEN2') ?></a></li>
			<li><a data-target="tab3" href="#"><?php $plxPlugin->lang('L_NAV_LIEN3') ?></a></li>
		</ul>
	</nav>	
    
    <!-- contenu de la page configuration -->
	<div class="tabby-content" id="example-tab-content">

	<div data-tab="tab1">
		<div class="formulaire">
	        <!-- membres déja créés -->
	        <form action="parametres_plugin.php?p=Testimonials" method="post">
	            <fieldset>
	                <table class="full-width">
	                    <thead>
	                        <tr>
	                            <th>N°</th>
	                            <th>Nom</th>
	                            <th>Prénom</th>
	                            <th>Fonction</th>
	                            <th><?php echo $plxPlugin->getParam('champs1'); ?></th>
	                            <th><?php echo $plxPlugin->getParam('champs2'); ?></th>
	                            <th>Avatar</th>
	                            <th class="checkbox">Effacer</th>
	                        </tr>
	                    </thead>

	                    <tbody>
	                        <?php for($i=1; $i<=$nbmembres; $i++) {?>

	                        <?php $nom = $plxPlugin->getParam(nom.$i);

	                        if(!empty($nom)) { ?>
	                        
	                        <tr class="line-<?php echo $i%2 ?>">
	                            <td class="id">
	                                <?php echo $i; ?>
	                            </td>
	                            
	                            <td>
	                                <input type="text"  name="nom<?php echo $i; ?>" value="<?php echo $plxPlugin->getParam(nom.$i) ?>" />
	                            </td>
	                            
	                            <td>
	                            	<input type="text"  name="prenom<?php echo $i; ?>" value="<?php echo $plxPlugin->getParam(prenom.$i) ?>" />
	                            </td>
	                            
	                            <td>
	                                <input type="text" name="fonction<?php echo $i; ?>" value="<?php echo $plxPlugin->getParam(fonction.$i) ?>" />
	                            </td>

	                            <td>
	                                <input type="text" name="telephone<?php echo $i; ?>" value="<?php echo $plxPlugin->getParam(telephone.$i) ?>" />
	                            </td>

	                            <td>
	                                <input type="text" name="mail<?php echo $i; ?>" value="<?php echo $plxPlugin->getParam(mail.$i) ?>" />
	                            </td>

	                            <td>

	                            	<?php $avatar = $plxPlugin->getParam(avatar.$i);
	                            	
	                            	if (!empty($avatar)) {?>


									<a id="toggler_thumbnail" href="javascript:void(0)" onclick="mediasManager.openPopup('avatar<?php echo $i; ?>', true)"><img src="<?php echo  PLX_ROOT.$plxPlugin->getParam(avatar.$i) ?>" alt="avatar" height="64" width="64"></a>
	                            		
	                            	<?php }else{ ?>

	                            		<a id="toggler_thumbnail" href="javascript:void(0)" onclick="mediasManager.openPopup('avatar<?php echo $i; ?>', true)"><img src="<?php echo PLX_PLUGINS ?>MyTeam/APP/noavatar.png" alt="logo" height="64" width="64"></a>
	                            		
	                                <?php } ?>

	                                <input style="display:none" type="text" id="avatar<?php echo $i; ?>" name="avatar<?php echo $i; ?>" value="<?php echo $plxPlugin->getParam(avatar.$i) ?>" />
	                            </td>	
	                            
	                            <td class="checkbox">
	                                <input type="checkbox" name="delete<?= $i; ?>" value="1">
	                            </td>
	                        </tr>
	                            <?php }; ?>

	                                <?php }; ?>

	                    </tbody>

	                </table>
	            </fieldset>

	                    <p class="in-action-bar">
	                        <?php echo plxToken::getTokenPostMethod() ?>
	                        <input class="bt" type="submit" name="submit" value="Mettre à jour" />
	                    </p>
	        </form>
	    </div>
	</div>

	<div data-tab="tab2">


		    <form action="parametres_plugin.php?p=MyTeam" method="post">

		        <p>
		            <label for="nom">Nom (Champ obligatoire)</label>
		             <input type="text" name="nom-new" value="" />
		        </p>
		        
		        <p>
		            <label for="prenom">Prénom (Champ obligatoire)</label>
		            <input type="text" name="prenom-new" value="" />
		        </p>
		        
		        <p>
		            <label for="fonction">Fonction (Champ obligatoire)</label>
		            <input type="text" name="fonction-new" value="" />
		        </p>

		        <p>
		            <label for="label1"><?php echo $label1; ?></label>
		            <input type="text" name="telephone-new" value="" />
		        </p>

		        <p>
		            <label for="label2"><?php echo $label2;  ?></label>
		            <input type="text" name="mail-new" value="" />
		        </p>

		        <p>
					<label for="avatar">Avatar
					<a id="toggler_thumbnail" href="javascript:void(0)" onclick="mediasManager.openPopup('avatar-new', true)">+</a>
					</label>

					<input id="avatar-new" name="avatar-new"  maxlength="255" value="<?php echo plxUtils::strCheck($plxPlugin->getParam("avatar-new")) ?>">
				</p>                                                                 
		                       
		        <p class="in-action-bar">
		            <?php echo plxToken::getTokenPostMethod() ?>
		            <input class="bt" type="submit" name="submit" value="Sauvegarder" />
		        </p>

		    </form>	

	</div>


	<div data-tab="tab3">

		<form action="parametres_plugin.php?p=MyTeam" method="post">

				<p>
					<label for="id_content">Texte en haut de page</label>
					<textarea id="id_content" rows="5"  name="mnuInfo"><?php echo $plxPlugin->getParam('mnuInfo'); ?></textarea>
				</p>

				<p>
		            <label for="champs1">Titre du champs 1</label>
		             <input type="text" name="champs1" value="<?php echo $plxPlugin->getParam('champs1'); ?>" />
		        </p>
		        
		        <p>
		            <label for="champs2">Titre du champs 2</label>
		             <input type="text" name="champs2" value="<?php echo $plxPlugin->getParam('champs2'); ?>" />
		        </p>

				<p>
					<label for="mnuDisplay">Afficher la page dans la navigation</label>
					<select name="mnuDisplay" id="mnuDisplay">
						<option value="1"  <?php if ($mnuDisplay == '1') { echo'selected';}?> >Oui</option>
						<option value="0" <?php if ($mnuDisplay == '0') { echo'selected';}?> >Non</option>
					</select>

				<p>
					<label for="mnuName">Titre de la page</label>
					<input id="mnuName" name="mnuName"  maxlength="255" value="<?php echo $plxPlugin->getParam("mnuName"); ?>">
				</p>
				<p>
					<label for="mnuPos">Position de la page</label>
					<input id="mnuPos" name="mnuPos"  maxlength="255" value="<?php echo $plxPlugin->getParam("mnuPos"); ?>">
				</p>

				<p>
					<label for="template">Template de votre page</label>
					<?php plxUtils::printSelect('template', $aTemplates, $template) ?>
				</p>


			<p class="in-action-bar">
				<?php echo plxToken::getTokenPostMethod() ?>
				<input type="submit" name="submit" value="<?php $plxPlugin->lang('SUBMIT') ?>" />
			</p>

		</form>
	</div>
	

<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="<?php echo PLX_PLUGINS ?>MyTeam/APP/jquery.tabby.js"></script>
<script>
    $(document).ready(function(){
        $('#tabby-1').tabby();
    });
</script>	




