<?php
namespace FwTest\Controller;
use FwTest\Classes as Classes;

class ProductController extends AbstractController
{
    /**
     * @Route('/product_list.php')
     */
    public function index()
    {
    	$db = $this->getDatabaseConnection();

        $list_product = Classes\Product::getAll($db, 0, $this->array_constant['product']['nb_products']);

        echo $this->render('product/list.tpl', ['list_product' => $list_product]);

    }

    /**
     * @Route('/product_detail.php')
     */
    public function detail()
    {
        $db = $this->getDatabaseConnection();

    	$id = (isset($_GET['id']) && !empty($_GET['id']))? $_GET['id']:0;

    	if (!empty($id)) {

    		$product = new Classes\Product($db, $id);

    		if (!empty($product)) {
    			echo $this->render('product/detail.tpl', ['product' => $product]);
    		} else {
    			$this->_redirect('product_list.php');
    		}
    		
    	} else {
    		$this->_redirect('product_list.php');
    	}

    }
}