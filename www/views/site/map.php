<?php include ROOT . '/views/layouts/header.php'; ?>

<section>
    <div class="container">
        <div class="row">

            <div class="col-lg-6">
                <h4>Карта сайта</h4>

                <br/>
                
                <img src="/template/images/map/map.png" class="map" alt="Навигация по сайту" usemap="#Navigation" />

                <p><map name="Navigation">

                    <area shape="rect" coords="253,13,339,46" href="/" alt="Главная">

                    <area shape="rect" coords="10,64,96,97" href="/cart" alt="Корзина">
                    <area shape="rect" coords="107,64,193,97" href="/catalog" alt="Каталог">
                    <area shape="rect" coords="206,64,292,97" href="/about" alt="О магазине">
                    <area shape="rect" coords="303,64,389,97" href="/contacts" alt="Контакты">
                    <area shape="rect" coords="403,64,489,97" href="/cabinet" alt="Аккаунт">
                    <area shape="rect" coords="507,64,592,97" href="/search" alt="Поиск">
                    
                    <area shape="rect" coords="403,116,489,183" href="/cabinet/edit" alt="Редактировать аккаунт">

                </map></p>
                
            </div>
        </div>
    </div>
</section>

<?php include ROOT . '/views/layouts/footer.php'; ?>