<?php

namespace FwTest\Classes;

class Shops
{
    /**
     * The table name
     *
     * @access  protected
     * @var     string
     */
    protected static $table_name = 'shops';

    /**
     * The primary key name
     *
     * @access  protected
     * @var     string
     */
    protected static $pk_name = 'id_shop';

    /**
     * The object datas
     *
     * @access  private
     * @var     array
     */
    private $_array_datas = array();

    /**
     * The object id
     *
     * @access  private
     * @var     int
     */
    private $id;

    /**
     * The lang id
     *
     * @access  private
     * @var     int
     */
    private $lang_id = 1;

    /**
     * The link to the database
     *
     * @access  public
     * @var     object
     */
    public $db;

    /**
     * Product constructor.
     *
     * @param      $db
     * @param      $datas
     *
     * @throws Class_Exception
     */
    public function __construct($db, $datas)
    {
        if (($datas != intval($datas)) && (!is_array($datas))) {
            throw new Class_Exception('The given datas are not valid.');
        }

        $this->db = $db;

        if (is_array($datas)) {
            $this->_array_datas = array_merge($this->_array_datas, $datas);
        } else {
            var_dump("@@");
        }
    }

    /**
     * Get the list of shops.
     *
     * @param      $db
     * @param      $begin
     * @param      $end
     *
     * @return     array of Shop
     */
    public static function getAll($db, $begin = 0, $end = 15)
    {
        // Ajout de l'alias "p" manquant dans le FROM
        $sql_get = "SELECT p.* FROM " . self::$table_name . " p LIMIT " . $begin. ", " . $end;

        $result = $db->fetchAll($sql_get);

        $array_shop = [];

        if (!empty($result)) {
            foreach ($result as $shop) {
                $array_shop[] = new Shops($db, $shop);
            }
        }

        return $array_shop;
    }

    /**
     * Delete a product.
     *
     * @return     bool if succeed
     */
    public function delete()
    {
        $id = $this->getId();
        $sql_delete = "DELETE FROM " . self::$table_name . " WHERE " . self::$pk_name . " = ?";

        return $this->db->query($sql_delete, $id);
    }

    /**
     * Get the primary key
     *
     * @return     int
     */
    public function getId()
    {
        return $this->_array_datas[self::$pk_name];
    }

    /**
     * Access properties.
     *
     * @param      $param
     *
     * @return     string
     */
    public function __get( $param ) {

        $array_datas = $this->_array_datas;
        //file_put_contents("debug.txt", "-- Start GET  \n", FILE_APPEND);
        //file_put_contents("debug.txt", "-- GET Data array : ".serialize($array_datas)." \n", FILE_APPEND);
        //file_put_contents("debug.txt", "-- GET pkname : ".self::$pk_name." \n", FILE_APPEND);

        // Let's check if an ID has been set and if this ID is validd
        if ( !empty( $array_datas[self::$pk_name] ) ) {
            //file_put_contents("debug.txt", "-- Check GET \n", FILE_APPEND);

            // If it has been set, then try to return the data
            if ( array_key_exists($param, $array_datas ) ) {
                return $array_datas[$param];
            }

            // Let's dispatch all the values in $_array_datas
            $this->_dispatch();

            $array_datas = $this->_array_datas;

            if ( array_key_exists($param, $array_datas ) ) {

                return $array_datas[$param];

            }
        }

        return false;

    }

    /**
     * @return bool
     */
    private function _dispatch()
    {
        $array_datas = $this->_array_datas;
        file_put_contents("debug.txt", "-- Start Dispatch : \n", FILE_APPEND);

        if (empty($array_datas)) {
            return false;
        }

        /*
         */
        $sql_dispatch = "SELECT s.*, 
            FROM shop s
            WHERE s.shop_id = :id_shop;";

        /*
         * J'ai rajouté un Bind pour la variable lang_id :
         * 'lang_id' => 1,
         */
        $params = [
            'id_shop' => intval($array_datas['id_shop'])
        ];

        $array_product = $this->db->fetchRow($sql_dispatch, $params);

        // If the request has been executed, so we read the result and set it to $_array_datas
        if (is_array($array_product)) {
            $this->_array_datas = array_merge($array_datas, $array_product);
            return true;
        }

        return false;
    }
}