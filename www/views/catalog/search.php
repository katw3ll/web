<?php include ROOT . '/views/layouts/header.php'; ?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="left-sidebar">
                    <h2>Поиск</h2>
                    <div class="panel-group category-products search-form">
                        
                        <form action="#" method="post">
                            <p>Наименование</p>
                            <input type="text" name="name" placeholder="Введите наименование" value=""/>
                            <p>Код</p>
                            <input type="text" name="code" placeholder="123456" value=""/>
                            
                            <p>Категория</p>
                            <select name="category">
                                <?php foreach ($categories as $categoryItem): ?>
                                    <option value="<?=$categoryItem['id'];?>"><?=$categoryItem['name'];?></option>
                                <?php endforeach; ?>
                            </select>
                            <br/><br/>
                            <p>Сортировка по</p>
                            <select name="sort">
                                <option value="name-top">по имени (А-Я)</option>
                                <option value="name-back">по имени в обратном порядке (Я-А)</option>
                                <option value="price-top">по цене от большего к меньшему</option>
                                <option value="price-back">по цене от меньшего к большему</option>
                            </select>
                            <br/>
                            <br/>
                            <p>Отображать</p>
                            <select name="display">
                                <option value="new">только новые</option>
                                <option value="old">все кроме новых</option>
                                <option value="all">все товары</option>
                            </select>
                            <br/>
                            <br/>
                            <input type="submit" name="submit" class="btn btn-default" value="Отправить" />
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-9 padding-right">
                <div class="features_items"><!--features_items-->
                    <h2 class="title text-center">Найденные товары</h2>
                    
                    <?php foreach ($allProducts as $product): ?>
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="<?php echo Product::getImage($product['id']); ?>" alt="" />
                                        <h2>$<?php echo $product['price'];?></h2>
                                        <p>
                                            <a href="/product/<?php echo $product['id'];?>">
                                                <?php echo $product['name'];?>
                                            </a>
                                        </p>
                                        
                                        <a href="#" data-id="<?php echo $product['id'];?>"
                                           class="btn btn-default add-to-cart">
                                            <i class="fa fa-shopping-cart"></i>В корзину
                                        </a>
                                    </div>
                                    <?php if ($product['is_new']): ?>
                                        <img src="/template/images/home/new.png" class="new" alt="" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>                   

                </div><!--features_items-->


            </div>
        </div>
    </div>
</section>

<?php include ROOT . '/views/layouts/footer.php'; ?>