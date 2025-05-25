<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<section>
    <div class="menu" style="display: inline-flex;">
        <div id="backBtn" class="button" onclick="window.location='<?php echo e(url('/')); ?>'">Vissza</div>
    </div>
</section>

<body>
    <section style="display:flex; justify-content:center; padding:20px;">
        <div style="width:1000px; height:500px;">
            <canvas stateGraphHolder style="width:150px; height:150px;"></canvas>
        </div>
    </section>
    <section progressGraphHolder style="display:flex; flex-direction:column; justify-content:center; padding:20px;"></section>
</body>

<script type="module">
    import Chart from '../../node_modules/chart.js/auto/auto.js';
 
    window.onload = async () => {
        // ORDERS
        let orderResult = new Promise( resolve => {
            $.ajax({
                type: 'GET',
                url:  '<?php echo e(url("/ordersList/getList")); ?>',
                success: (response) => {
                    resolve(response);
                }
            });
        });

        // REPAIRS
        let repairResult = new Promise( resolve => {
            $.ajax({
                type: 'GET',
                url:  '<?php echo e(url("/repairsList/getList")); ?>',
                success: (response) => {
                    resolve(response);
                }
            });
        });

        // ALL USERS
        let allUserData = await new Promise( resolve => {
            $.ajax({
                type: 'GET',
                url:  '<?php echo e(url("/profile/data/getAllUser")); ?>',
                success: (response) => {
                    resolve(JSON.parse(response));
                }
            });
        });

        let response = await orderResult;
        let orders = JSON.parse(response);
        let repairResponse = await repairResult;
        let repairs = JSON.parse(repairResponse);

        // Stat 1.
        let varakozik = orders.filter(order => order.state == 1).length;
        let folyamatban = orders.filter(order => order.state == 2).length;
        let kesz = orders.filter(order => order.state == 3).length;
        let atadva = orders.filter(order => order.state == 4).length;
        let visszavett = orders.filter(order => order.state == 5).length;
        let elutasitva = orders.filter(order => order.state == 6).length;

        let repairVarakozik = repairs.filter(repair => repair.state == 1).length;
        let repairFolyamatban = repairs.filter(repair => repair.state == 2).length;
        let repairKesz = repairs.filter(repair => repair.state == 3).length;
        let repairAtadva = repairs.filter(repair => repair.state == 4).length;
        let repairVisszavett = repairs.filter(repair => repair.state == 5).length;
        let repairElutasitva = repairs.filter(repair => repair.state == 6).length;

        // CREATE CHART
        new Chart( $('[stateGraphHolder]') , {
            type: 'bar',
            data: {
                labels: ['Várakozik', 'Folyamatban', 'Kész', 'Átadva', 'Visszavett', 'Elutasítva'],
                datasets: [
                    {
                        label: 'Megrendelések',
                        data: [varakozik, folyamatban, kesz, atadva, visszavett, elutasitva],
                        borderWidth: 2
                    },
                    {
                        label: 'Javítások',
                        data: [repairVarakozik, repairFolyamatban, repairKesz, repairAtadva, repairVisszavett, repairElutasitva],
                        borderWidth: 2
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Stat 2.
        let ordersInMonth = orders.filter(order => {
            let date = new Date(order.created_at);
            return date.getMonth() == new Date().getMonth() && date.getFullYear() == new Date().getFullYear();
        });

        let repairsInMonth = repairs.filter(repair => {
            let date = new Date(repair.created_at);
            return date.getMonth() == new Date().getMonth() && date.getFullYear() == new Date().getFullYear();
        });

        let counter = [];

        ordersInMonth.forEach( order => {            
            if (! order.progressData) return;

            order.progressData = JSON.parse(order.progressData);

            order.progressData.forEach( method => {
                if (! counter[method.userId]) {
                    let nickname = allUserData.find( user => user.id == method.userId)?.nickname || undefined;

                    if (nickname) {
                        counter[method.userId] = {
                            name: nickname,
                            methods: [method.name],
                            count: [parseFloat(method.time)]
                        };
                    }
                } else {
                    let index = counter[method.userId].methods.findIndex( m => m == method.name );
                    if (index == -1) {
                        counter[method.userId].methods.push(method.name);
                        counter[method.userId].count.push(parseFloat(method.time));
                    } else {
                        counter[method.userId].count[index] += parseFloat(method.time);
                    }
                }
            });
        });

        counter.forEach( user => {
            $(`<div>${user.name}</div>`).appendTo($('[progressGraphHolder]'));
            let newHolder = $('<div style="width:450;height:350px"><canvas></canvas></div>').appendTo($('[progressGraphHolder]'));

            new Chart( newHolder.find('canvas'), {
                type: 'bar',
                data: {
                    labels: user.methods,
                    datasets: [
                        {
                            label: 'Óraszámok',
                            data: user.count,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    elements: {
                        bar: {
                            borderWidth: 2
                        }
                    }
                }
            });
        });


    }
</script><?php /**PATH C:\laragon\www\KJ_Anna_szakdolgozat\resources\views/graphsPage.blade.php ENDPATH**/ ?>