<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="<?php echo e(asset('../resources/js/main/myClasses/News.js')); ?>"></script>
<script src="<?php echo e(asset('../resources/js/main/index.js')); ?>"></script>

<section class="menus" style="display: flex; flex-direction: row; height: 100;">
    <div style="display: flex;">
        <div class="menuHolder">
            <div class="menuTitle box button">Megrendelések</div>
            <ul>
                <li id="newOrder"   class="box button" onclick="window.location='<?php echo e(route('newOrderPage')); ?>'">Új</li>
                <li id="ordersList" class="box button" onclick="window.location='<?php echo e(route('ordersListPage')); ?>'">Felvett</li>
            </ul>
        </div>

        <div class="menuHolder">
            <div class="menuTitle box button">Javítások</div>
            <ul>
                <li id="repair"      class="box button" onclick="window.location='<?php echo e(route('newRepairPage')); ?>'">Javítás</li>
                <li id="repairsList" class="box button" onclick="window.location='<?php echo e(route('repairsListPage')); ?>'">Felvett</li>
            </ul>
        </div>

        <div class="menuHolder">
            <div class="menuTitle box button">Admin</div>
            <ul>
                <li id="log"    class="box button" onClick="window.location='<?php echo e(route('logListPage')); ?>'">Log</li>
                <li id="graphs" class="box button" onClick="window.location='<?php echo e(route('graphsPage')); ?>'">Grafikonok</li>
            </ul>
        </div>
    </div>


    <div style="display: inline-flex; flex:3; justify-content: flex-end; height: 40px;">
        <div greetings style="display:flex; align-content: center; flex-wrap: wrap; float:right"><span></span></div>
        <div id="btn-notification" class="box button" onclick="" style="float:right; margin-left:5px;"><strong>!</strong></div>
        <div id="btn-profile" class="box button" onclick="window.location='<?php echo e(route('profilePage')); ?>'" style="float:right; margin-left:20px;">Profil</div>
        <div id="btn-logout"  class="box button" onclick="window.location='<?php echo e(route('loginPage')); ?>'"   style="float:right; margin-left:20px;">Kijelentkezés</div>
    </div>
</section>

<section class="mainPageContent">

    <div class="goldPriceHolder box light" style="border: 1px solid black; width: fit-content; text-align: center">
        <div class="boxTitle">Arany árfolyam</div>
        <div goldPrices style="margin-top:10px;"></div>
    </div>

</section>

<script>
    window.onload = async function() {
        // menu
        let menuHolder = $('.menuHolder');
        menuHolder.hover( e => {
            $(e.currentTarget).find('ul').addClass('active');
        }, e => {
            $(e.currentTarget).find('ul').removeClass('active');
        });


        // profile
        $.ajax({
            type: 'GET',
            url:  '<?php echo e(url("/profile/data")); ?>',
            success: (response) => {
                window.loggedInUserData = JSON.parse(response).userData;
                $('[greetings]>span').text(`Üdv, ${window.loggedInUserData.nickname}!`);
            }
        });
    }

    $("#btn-logout").on("click", e => {
        $.ajax({
            type: 'GET',
            url:  '<?php echo e(url("/logout")); ?>',
        });

    });


</script><?php /**PATH C:\laragon\www\KJ_Anna_szakdolgozat\resources\views/mainPage.blade.php ENDPATH**/ ?>