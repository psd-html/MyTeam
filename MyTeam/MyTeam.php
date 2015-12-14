<?php
class MyTeam extends plxPlugin {
 
    public function __construct($default_lang){
    # Appel du constructeur de la classe plxPlugin (obligatoire)
    parent::__construct($default_lang);
    
    # Pour accéder à une page de configuration
    $this->setConfigProfil(PROFIL_ADMIN,PROFIL_MANAGER);
    # Déclaration des hooks

    $this->addHook('ThemeEndHead', 'ThemeEndHead');
    $this->addHook('plxShowConstruct', 'plxShowConstruct');
    $this->addHook('plxMotorPreChauffageBegin', 'plxMotorPreChauffageBegin');
    $this->addHook('plxShowStaticListEnd', 'plxShowStaticListEnd');
    $this->addHook('plxShowPageTitle', 'plxShowPageTitle');
    $this->addHook('SitemapStatics', 'SitemapStatics');
    $this->addHook('AdminTopEndHead', 'AdminTopEndHead');
    $this->addHook('MyTeam', 'MyTeam');
    } 

    public function MyTeam(){
        
            $nb_membres = floor(sizeof($this->aParams)/6); // nombre de champs
            $nb_membres = $nb_membres + 1;

        
            for($i=1; $i<$nb_membres; $i++) { // boucle pour afficher les membres
                $nom = $this->getParam('nom'.$i);
                $prenom = $this->getParam('prenom'.$i);
                $fonction = $this->getParam('fonction'.$i);
                $telephone = $this->getParam('telephone'.$i);
                $avatar = $this->getParam('avatar'.$i);
                $mail = $this->getParam('mail'.$i);


                if(!empty($nom)) { 
                    ?>
                        <div class="membre">
                            <div class="info">
                                <h3><?php echo $nom; ?><br><?php echo $prenom; ?></h3>
                                <p class="fonction"><?php echo $fonction;?></p>
                                <p>Tél: <?php echo $telephone;?></p>

                                <?php if (empty($avatar)) {?>

                                            <img src="<?php echo PLX_PLUGINS ?>MyTeam/APP/noavatar.png" alt="logo">

                                        <?php }else{ ?>

                                            <img src="<?php echo $avatar; ?>" alt="avatar">
                                            
                                        <?php } ?>

                                <p>Mail: <?php echo $mail; ?></p>
                            </div>
                            
                        </div>
                <?php }           
            } 

    }

    public function AdminTopEndHead() { ?>
    
        <link rel="stylesheet" href="<?php echo PLX_PLUGINS ?>MyTeam/APP/admin_style.css">

    <?php
    }
    
    public function ThemeEndHead() { ?>
    
        <link rel="stylesheet" href="<?php echo PLX_PLUGINS ?>MyTeam/APP/style.min.css">
        
     <?php 
    }
   


    public function plxShowConstruct() {
        # infos sur la page statique
        $string  = "if(\$this->plxMotor->mode=='team') {";
        $string .= "    \$array = array();";
        $string .= "    \$array[\$this->plxMotor->cible] = array(
            'name'      => '".$this->getParam('mnuName')."',
            'menu'      => '',
            'url'       => 'team',
            'readable'  => 1,
            'active'    => 1,
            'group'     => ''
        );";
        $string .= "    \$this->plxMotor->aStats = array_merge(\$this->plxMotor->aStats, \$array);";
        $string .= "}";
        echo "<?php ".$string." ?>";
    }


    public function plxMotorPreChauffageBegin() {
        $template = $this->getParam('template')==''?'static.php':$this->getParam('template');
        $string = "
        if(\$this->get && preg_match('/^team\/?/',\$this->get)) {
            \$this->mode = 'team';
            \$this->cible = '../../plugins/MyTeam/static';
            \$this->template = '".$template."';
            return true;
        }
        ";
        echo "<?php ".$string." ?>";
    }

    public function plxShowStaticListEnd() {
        # ajout du menu pour accèder à la page de MyTeam
        if($this->getParam('mnuDisplay')) {
            echo "<?php \$class = \$this->plxMotor->mode=='team'?'active':'noactive'; ?>";
            echo "<?php array_splice(\$menus, ".($this->getParam('mnuPos')-1).", 0, '<li><a class=\"static '.\$class.'\" href=\"'.\$this->plxMotor->urlRewrite('?team').'\">".$this->getParam('mnuName')."</a></li>'); ?>";
        }
    }


    public function plxShowPageTitle() {
        echo '<?php
            if($this->plxMotor->mode == "team") {
                echo plxUtils::strCheck($this->plxMotor->aConf["title"]." - '.$this->getParam('mnuName').'");
                return true;
            }
        ?>';
    }

    public function SitemapStatics() {
        echo '<?php
        echo "\n";
        echo "\t<url>\n";
        echo "\t\t<loc>".$plxMotor->urlRewrite("?team")."</loc>\n";
        echo "\t\t<changefreq>monthly</changefreq>\n";
        echo "\t\t<priority>0.8</priority>\n";
        echo "\t</url>\n";
        ?>';
    }

      
} // class MyTeam
?>
