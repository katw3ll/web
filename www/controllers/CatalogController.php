<?php

class CatalogController
{

    public function actionIndex()
    {
        $categories = Category::getCategoriesList();

        $latestProducts = Product::getLatestProducts(12);

        require_once(ROOT . '/views/catalog/index.php');
        return true;
    }

    public function actionCategory($categoryId, $page = 1)
    {
        $categories = Category::getCategoriesList();

        $categoryProducts = Product::getProductsListByCategory($categoryId, $page);

        $total = Product::getTotalProductsInCategory($categoryId);

        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

        require_once(ROOT . '/views/catalog/category.php');
        return true;
    }

    public function actionSearch()
    {
        $categories = Category::getCategoriesList();

        if (isset($_POST['submit']))
        {
            $name = $_POST['name'];
            $code = $_POST['code'];
            $categoryId = $_POST['category'];
            $sort = $_POST['sort'];
            $display = $_POST['display'];


            $allProducts = Product::getSearchProducts($name,$code,$categoryId,$sort,$display);
        }
        else
        {
            $allProducts = Product::getAllProducts();
        }
        

        require_once(ROOT . '/views/catalog/search.php');
        return true;
    }

}
