<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="<?php echo e(asset('../resources/js/main/myClasses/Repair.js')); ?>"></script>
<script src="<?php echo e(asset('../resources/js/main/repairList.js')); ?>"></script>
<script async src="<?php echo e(asset('../resources/js/main/newRepair.js')); ?>"></script>

<section>
    <div class="menu" style="display: inline-flex;">
        <div id="backBtn" class="button">Vissza</div>
    </div>
</section>

<section>
    <div class="search">
        <select id="searchingColumn">
            <option value="buyername">Név</option>
            <option value="buyerphone">Telefonszám</option>
            <option value="id">Rendelési szám</option>
            <option value="created_at">Dátum</option>
            <option value="state">Állapot</option>
        </select>
        <input type="text" id="searchInput" placeholder="Keresés..">
        <input type="date" id="dateStart" style="display:none">
        <input type="date" id="dateEnd" style="display:none">
    </div>

    <div class="tableHolder"></div>
</section>

<section>
    <script>
        window.onload = async function() {
            /* let state_obj = new State();
            const STATE_TYPES = await state_obj.loadTypes(); */

            $('#backBtn').on('click', () => {
                window.location = MAIN_URL;
            });
    
            let repairList = [];
    
            let result = new Promise( resolve => {
                $.ajax({
                    type: 'GET',
                    url:  '<?php echo e(url("/repairsList/getList")); ?>',
                    success: (response) => {
                        resolve(response);
                    }
                });
            });
            
            let response = await result;

            if (response) {
                let data = JSON.parse(response);
    
                data.forEach( repair => {
                    let repairData = {
                        id:         repair.id,
                        buyername:  repair.buyername,
                        buyerphone: repair.buyerphone,
                        details:    JSON.parse(repair.details),
                        priceData:  JSON.parse(repair.price),
                        state:      repair.state,
                        date:       repair.created_at,
                        deadline:   repair.deadline
                    }

                    let newRepair = new Repair(repairData);

                    repairList.push(newRepair);
                });

                loadTable(repairList, STATE_TYPES);

                $('#searchInput').on('input', e => {
                    let dataObj = {
                        column:    $('#searchingColumn').val(),
                        word:      $('#searchInput').val(),
                        dateStart: $('#dateStart').val(),
                        dateEnd:   $('#dateEnd').val()
                    };

                    searchRepairs(dataObj, STATE_TYPES);
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

                    searchRepairs(dataObj, STATE_TYPES);
                });
            }
        }
    </script>
</section><?php /**PATH C:\laragon\www\KJ_Anna_szakdolgozat\resources\views/repairsListPage.blade.php ENDPATH**/ ?>