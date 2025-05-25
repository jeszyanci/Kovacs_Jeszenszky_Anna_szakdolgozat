<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="<?php echo e(asset('../resources/js/main/myClasses/Order.js')); ?>"></script>
<script src="<?php echo e(asset('../resources/js/main/orderList.js')); ?>"></script>
<script async src="<?php echo e(asset('../resources/js/main/newOrder.js')); ?>"></script>

<section>
    <div class="menu" style="display: inline-flex;">
        <div id="backBtn" class="button box">Vissza</div>
    </div>
</section>

<section>
    <div class="search">
        <select id="searchingColumn" class="select box">
            <option value="buyername">Név</option>
            <option value="buyerphone">Telefonszám</option>
            <option value="id">Rendelési szám</option>
            <option value="created_at">Dátum</option>
            <option value="state">Állapot</option>
        </select>
        <input type="text" id="searchInput" class="box input" style="color:#805852" placeholder="Keresés..">
        <input type="date" id="dateStart" class="box input" style="display:none">
        <input type="date" id="dateEnd" class="box input" style="display:none">
    </div>

    <div class="tableHolder"></div>
</section>

<section>
    <script>
        window.onload = async function() {
            $('#backBtn').on('click', () => {
                window.location = MAIN_URL;
            });

            let allUserData = await new Promise( resolve => {
                $.ajax({
                    type: 'GET',
                    url:  '<?php echo e(url("/profile/data/getAllUser")); ?>',
                    success: (response) => {
                        resolve(JSON.parse(response));
                    }
                });
            });
    
            let orderList = [];
    
            let result = new Promise( resolve => {
                $.ajax({
                    type: 'GET',
                    url:  '<?php echo e(url("/ordersList/getList")); ?>',
                    success: (response) => {
                        resolve(response);
                    }
                });
            });

            let response = await result;

            if (response) {
                let data = JSON.parse(response);
    
                data.forEach( order => {
                    let orderData = {
                        id:         order.id,
                        buyername:  order.buyername,
                        buyerphone: order.buyerphone,
                        details:    JSON.parse(order.details),
                        priceData:  JSON.parse(order.price),
                        state:      order.state,
                        progressData: JSON.parse(order.progressData),
                        date:       order.created_at,
                        deadline:   order.deadline
                    }

                    let newOrder = new Order(orderData);

                    orderList.push(newOrder);
                });

                loadTable(orderList, allUserData);

                $('#searchInput').on('input', e => {
                    let dataObj = {
                        column:    $('#searchingColumn').val(),
                        word:      $('#searchInput').val(),
                        dateStart: $('#dateStart').val(),
                        dateEnd:   $('#dateEnd').val()
                    };

                    searchOrders(dataObj, allUserData);
                });

                $('#searchingColumn, #dateStart, #dateEnd').on('change', e => {
                    let column = $('#searchingColumn').val();

                    let dataObj = {
                        column:    column,
                        word:      $('#searchInput').val(),
                        dateStart: $('#dateStart').val(),
                        dateEnd:   $('#dateEnd').val()
                    };

                    if (column === 'created_at') {
                        $('#searchInput').hide();
                        $('#dateStart, #dateEnd').show();

                    } else {
                        $('#dateStart, #dateEnd').hide();
                        $('#searchInput').show();
                    }

                    searchOrders(dataObj, allUserData);
                });
            }
        }
    </script>
</section><?php /**PATH C:\laragon\www\KJ_Anna_szakdolgozat\resources\views/ordersList.blade.php ENDPATH**/ ?>