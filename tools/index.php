<html>
    <head>
        <title>Generation of Sample Data</title>
        <style type="text/css">
            *{
                font-family:Arial, Verdana, sans-serif;
                font-color:black;
                font-size:11px;
            }
            body{
                margin:40px 20px 20px 80px;
                background-color:#cccccc;
            }
            h1{
                font-size:18px;
                text-transform:uppercase;
            }
        </style>
    </head>
    <body>
        <h1>generation of sample data (ZGONC VERSION)</h1>
        <p>
            Description:<br/><br/>
            - Version 0.1 (06.06.2012)<br/>
            - Script automatically generates categories, attribute-sets, attributes and products<br/>
            - if number of attributesets = 0, all attributes are assigned to the default attribute set<br/>
            - important: before creating new sample data, delete old sample data to ensure data integrity<br/>
            - Tested with Magento CE 1.7<br/>
            <br/><br/><br/>
        </p>

        <div>
            <br/><br/>
            <h2>1. Delete old sample data</h2>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <input type="hidden" name="sent" value="1" />
                <input type="hidden" name="action" value="delete" />
                <p><input type="submit" value="delete sample data!" /></p>
            </form>

        </div>
        
        <div>
            <h2>2. Generate new sample data</h2>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <input type="hidden" name="sent" value="1" />
                <input type="hidden" name="action" value="create" />
                <p><input type="text" name="num_cats" value="1" /><span>number of categories = attributesets = products</span></p>
                <p><input type="checkbox" name="noattributeset" /><span>do not generate attributesets</span></p>
                <p><input type="text" name="num_atts" value="1" /><span>number of attributes</span></p>
                <p><input type="submit" value="generate sample data!" /></p>
            </form>

        </div>
        
        <div>
        <?php
        
        /*
         * TODOS:
         * - import start und endzeit anzeigen!
         * 
         * - aktuell werden alle attribute nur vom typ text angelegt
         * - globale konfiguration von store id, root category etc
         * - eine variante erstellen mit flat categories, eine variante mit verschachtelung
         * - des öfteren hängt sich magento auf, es wird auf tools/import umgeleitet, kann an meiner veränderten mage klasse liegen oder am script? --> temp lösung: cache und session ordner löschen
         * - error handling / error messages
         * - die ids global speichern (wegen abfrage nach sample-category-n)
         * - performance optimieren, z.b. wird in _assignAttribute einmal das attribute aus db gelesen und in der api funktion auch nochmal
         * - attribute already in set, remove from old set
         * - set default value of attributes, or set attribute values when creating products
         * - bei mehrsprachigen seiten testen, oder so einstellen, dass man es manuell eingeben kann? (storeId konfigurable machen)
         * - beim löschen fraglich, ob alles so fehlerfrei gemacht werden kann, was wenn es schon bestellungen etc. auf produkte gab? und die ids werden raufgezählt
         * - TODOS durchgehen
         */

         
        if(isset($_POST['sent'])){
            require_once 'sampledata.php';
            set_time_limit(0);
            ini_set('max_execution_time ', 0);
            
            if($_POST['action'] == "create"){
                $numofattsets = $_POST['num_cats'];
                
                if(isset($_POST['noattributeset'])) $numofattsets = 0;
                $config = Array(
                'categories' => Array(
                                'items' => $_POST['num_cats'],
                                'is_anchor' => 1
                                ),
                'attributes' => Array(
                                'items' => $_POST['num_atts'],
                                'is_visible_on_front' => 1
                                ),
                'attributesets' => Array(
                                'items' => $numofattsets
                                ),
                'products' => Array(
                                'items' => $_POST['num_cats']
                                )
                );
                
                $sampledata  = new LimeSoda_Create_Sampledata($config);
                $sampledata->create();
            }
            
            if($_POST['action'] == "delete"){
                $sampledata  = new LimeSoda_Create_Sampledata(Array());
                $sampledata->delete();
            }           
            
            
        }
        
        
        ?>
        </div>
    </body>
</html>

