<?php
namespace FwTest\Classes;

class ProductAction
{
    /**
     * The table name
     *
     * @access  protected
     * @var     string
     */
	protected static $table_name = 'produit';

    /**
     * The primary key name
     *
     * @access  protected
     * @var     string
     */
    protected static $pk_name = 'produit_ids';

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
            $this->_array_datas[self::$pk_name] = $datas;
        }
	}

    /**
     * Get the list of product.
     *
     * @param      $db
     * @param      $begin
     * @param      $end
     *
     * @return     array of Product
     */
	public static function getAll($db, $begin = 0, $end = 15)
	{
		$sql_get = "SELECT p.* FROM " . self::$table_name . " LIMIT " . $begin. ", " . $end;

		$result = $db->fetchAll($sql_get);

		$array_product = [];

		if (!empty($result)) {
			foreach ($result as $product) {
				$array_product[] = new Product($db, $product);
			}
		}

		return $array_product;
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

        // Let's check if an ID has been set and if this ID is validd
        if ( !empty( $array_datas[self::$pk_name] ) ) {

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

        if (empty($array_datas)) {
            return false;
        }

        $sql_dispatch = "SELECT p.*, 
                                IF (produit_lang_titreobjet IS NULL, produit_titreobjet, produit_lang_titreobjet) produit_titreobjet,
                                IF (produit_lang_nom IS NULL, produit_nom, produit_lang_nom) produit_nom,
                                IF (produit_lang_description IS NULL, produit_description, produit_lang_description) produit_description
            FROM produit p
            AND pl.fk_lang_id = :lang_id
            WHERE p.produit_id = :produit_id;";

        $params = [
            'produit_id' => $array_datas['produit_id']
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