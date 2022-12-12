<?php

namespace FwTest\Controller;
use FwTest\Classes as Classes;
use SimpleXMLElement ;

class ShopsController extends AbstractController
{

    /**
     * @Route('/api_list.php')
     */
    public function api_list()
    {
        $db = $this->getDatabaseConnection();

        $list_shop2 = Classes\Shops::getAll($db, 0, $this->array_constant['shop']['nb_shops']);

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><shops></shops>');
        $list_array = array() ;
        $test = 0;
        foreach ($list_shop2 as $shop2) {
            $list_array[$shop2->id_shop] = $shop2->name_shop;
            $test++;

            $shop_xml = $xml->addChild("shop");
            $shop_xml->addChild("id",$shop2->id_shop);
            $shop_xml->addChild("name",$shop2->name_shop);
        }


        header("Content-type: text/xml");
        print $xml->asXML();


    }

    /**
     * @Route('/api_add.php')
     */
    public function api_add()
    {

        // On s'assure que l'utilisateur a bien entré le nom de la boutique
        if(!isset($_GET['shop_name'])) {
            echo "Veuillez saisir le nom de la boutique dans la variable shop_name" ;
            exit();
        }

        // Ici on défini une liste de caractères autorisés pour le nom de la boutique
        // C'est a la fois un controle de sécurité et aussi un controle utilisateur
        $pattern = '[A-Za-z0-9\+\/\=]*' ;
        // Pour des raison de sécurité, on vérifie la variable utilisateur
        if (!$this->check_pattern($pattern,$_GET['shop_name']) ) {
            echo "Veuillez saisir un nom valide. Les caractères spéciaux ne sont pas autorisés." ;
            exit();
        }
        $db = $this->getDatabaseConnection();

        $shop_name =  $_GET['shop_name'] ;

        $result = $db->add("INSERT INTO shops (name_shop) values (:name) ;", array('name'=> $shop_name));

        // Ce bout de code ne fonctionne pas car il faut catcher l'erreur d'unicité PDO
        if($result) {
            echo "La boutique ".$shop_name. " a bien été ajoutée";
        }
        else {
            if($this->check_pattern('Duplicata', $result)) {
                echo "La boutique ".$shop_name. " existe déjà.";
            }
            else {
                echo "Une erreur s'est produite : " .$result;
            }
        }

    }

    // Fonction qui renvoie si la pattern est correct
    private function check_pattern($pattern,$val) {
        if ( is_string($val) ) {
            return @eval('return preg_match(\'/^'.$pattern.'$/\',$val);');
        }
        return false;
    }



    /**
     * @Route('/api_del.php')
     */
    public function api_del()
    {

        // On s'assure que l'utilisateur a bien entré l'ID de la boutique
        if(!isset($_GET['shop_id'])) {
            echo "Veuillez saisir l'ID de la boutique dans la variable shop_id" ;
            exit();
        }

        // Ici on défini une liste de caractères autorisés pour l'ID de la boutique
        // C'est a la fois un controle de sécurité et aussi un controle utilisateur
        $pattern = '[0-9]*' ;
        // Pour des raison de sécurité, on vérifie la variable utilisateur
        if (!$this->check_pattern($pattern,$_GET['shop_id']) ) {
            echo "Veuillez saisir un ID valide. Les caractères spéciaux ne sont pas autorisés." ;
            exit();
        }
        $db = $this->getDatabaseConnection();

        $shop_id =  $_GET['shop_id'] ;

        $db->del("DELETE FROM shops WHERE id_shop = :id_shop ;", array('id_shop'=> $shop_id));

    }


    /**
     * @Route('/api_update.php')
     */
    public function api_update()
    {

        // On s'assure que l'utilisateur a bien entré le nom de la boutique
        if(!isset($_GET['shop_name']) || !isset($_GET['shop_id'])) {
            echo "Veuillez saisir le nom de la boutique dans la variable shop_name et un ID dans la variable shop_id." ;
            exit();
        }

        // Ici on défini une liste de caractères autorisés pour le nom de la boutique
        // C'est a la fois un controle de sécurité et aussi un controle utilisateur
        $pattern = '[A-Za-z0-9\+\/\=]*' ;
        // Pour des raison de sécurité, on vérifie la variable utilisateur
        if (!$this->check_pattern($pattern,$_GET['shop_name']) ) {
            echo "Veuillez saisir un nom valide. Les caractères spéciaux ne sont pas autorisés." ;
            exit();
        }

        $pattern = '[0-9]*' ;
        // Pour des raison de sécurité, on vérifie la variable utilisateur
        if (!$this->check_pattern($pattern,$_GET['shop_id']) ) {
            echo "Veuillez saisir un nom valide. Les caractères spéciaux ne sont pas autorisés." ;
            exit();
        }


        $db = $this->getDatabaseConnection();

        $shop_id =  $_GET['shop_id'] ;
        $shop_name =  $_GET['shop_name'] ;

        $result = $db->add("UPDATE shops SET name_shop = :name_shop WHERE id_shop = :id_shop ;", array('name_shop'=> $shop_name, 'id_shop' => $shop_id));

        // Ce bout de code ne fonctionne pas car il faut catcher l'erreur d'unicité PDO
        if($result) {
            echo "La boutique ".$shop_name. " a bien été ajoutée";
        }
        else {
            if($this->check_pattern('Duplicata', $result)) {
                echo "La boutique ".$shop_name. " existe déjà.";
            }
            else {
                echo "Une erreur s'est produite : " .$result;
            }
        }

    }

}