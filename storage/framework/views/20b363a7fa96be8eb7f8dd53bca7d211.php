
<section class="layoutSection box">
    <div class="headerHolder">
        <script src="<?php echo e(asset('../resources/js/jQuery/jquery-3.6.1.min.js')); ?>"></script>

        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>

        <link rel="stylesheet" href="<?php echo e(asset('../resources/css/global.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('../resources/css/mainPage.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('../resources/css/newOrderPage.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('../resources/css/listPages.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('../resources/css/login.css')); ?>">

        <script>
            const MAIN_URL            = `<?php echo e(url("/")); ?>`;

            const SAVEORDER_URL       = '<?php echo e(url("/newOrder/saveOrder")); ?>';
            const SAVESKETCH_URL      = '<?php echo e(url("/newOrder/saveSketch")); ?>';
            const GETORDERLIST_URL    = '<?php echo e(url("/ordersList/getList")); ?>';
            const ORDERLIST_URL       = '<?php echo e(url("/ordersList")); ?>';
            const SAVE_METHODPROGRESS_URL = '<?php echo e(url("/ordersList/save_methodProgress")); ?>';
            
            const SAVEREPAIR_URL      = '<?php echo e(url("/newRepair/saveRepair")); ?>';
            const GETREPAIRLIST_URL   = '<?php echo e(url("/repairsList/getList")); ?>';
            
            const GETSTATE_URL        = '<?php echo e(url("/ordersList/getStates")); ?>';
            const SEARCH_IN_ORDER_URL = '<?php echo e(url("/ordersList/search")); ?>';

            const CHECK_DEADLINES_URL = '<?php echo e(url("/ordersList/checkDeadlines")); ?>';
            const GETMETHODS_URL      = '<?php echo e(url("/newOrder/getMethods")); ?>';
            
            const POST_NEWS_URL       = '<?php echo e(url("/news/saveNews")); ?>';
            const GET_NEWS_URL        = '<?php echo e(url("/news/getNews")); ?>';
            const DELETE_NEWS_URL     = '<?php echo e(url("/news/deleteNews")); ?>';

            const GET_LOGS_URL        = '<?php echo e(url("/log/getLogData")); ?>';
            
            const STATE_TYPES = [
                {id: 1, name: 'Várakozik'},
                {id: 2, name: 'Folyamatban'},
                {id: 3, name: 'Kész'},
                {id: 4, name: 'Átadva'},
                {id: 5, name: 'Visszavett'},
                {id: 6, name: 'Elutasítva'}
            ];
            window.METHOD_TYPES = [];

            document.addEventListener("DOMContentLoaded", async function() {
                window.Echo.channel('newsUpdateChannel')
                           .listen('NewsUpdated', (event) => {
                                let newsHolder = $('.newsholder');

                                if (event) {
                                    let newsItem = $(`
                                        <div class="newsItem" data-id="${news.id}">
                                            <div class="newsTitle">${news.title}</div>
                                            <div class="newsContent">${news.content}</div>
                                            <div class="newsDate">${news.created_at}</div>
                                        </div>
                                    `).prependTo(newsHolder);
                                }
                           });
                
                let result = new Promise( (resolve, reject) => {
                    $.ajax({
                        type: 'GET',
                        url:  GETMETHODS_URL,
                        success: (response) => {
                            let data = JSON.parse(response);
                            resolve(data.methods);
                        },
                        error: (error) => {
                            reject(error);
                        }
                    });
                });
            
                let response = await result;

                if (response)
                    window.METHOD_TYPES = response;

            });

        </script>

        <div style="text-align:center; font-size:30px; padding:45px;">
            Ékszerüzlet és -műhely
        </div>
    </div>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</section><?php /**PATH C:\laragon\www\KJ_Anna_szakdolgozat\resources\views/layout.blade.php ENDPATH**/ ?>