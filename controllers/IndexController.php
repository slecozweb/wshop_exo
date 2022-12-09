<?php
namespace FwTest\Controller;

class IndexController extends AbstractController
{
    /**
     * @Route('/index.php')
     */
    public function index()
    {
        echo 'Index.';
    }
}