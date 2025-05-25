
<section class="layoutSection box">
    <div class="headerHolder">
        <script src="{{asset('../resources/js/jQuery/jquery-3.6.1.min.js')}}"></script>

        @vite(['resources/js/app.js'])

        <link rel="stylesheet" href="{{ asset('../resources/css/global.css') }}">
        <link rel="stylesheet" href="{{ asset('../resources/css/mainPage.css') }}">
        <link rel="stylesheet" href="{{ asset('../resources/css/newOrderPage.css') }}">
        <link rel="stylesheet" href="{{ asset('../resources/css/listPages.css') }}">
        <link rel="stylesheet" href="{{ asset('../resources/css/login.css') }}">

        <script>
            const MAIN_URL            = `{{ url("/") }}`;

            const SAVEORDER_URL       = '{{ url("/newOrder/saveOrder") }}';
            const SAVESKETCH_URL      = '{{ url("/newOrder/saveSketch") }}';
            const GETORDERLIST_URL    = '{{ url("/ordersList/getList") }}';
            const ORDERLIST_URL       = '{{ url("/ordersList") }}';
            const SAVE_METHODPROGRESS_URL = '{{ url("/ordersList/save_methodProgress") }}';
            
            const SAVEREPAIR_URL      = '{{ url("/newRepair/saveRepair") }}';
            const GETREPAIRLIST_URL   = '{{ url("/repairsList/getList") }}';
            
            const GETSTATE_URL        = '{{ url("/ordersList/getStates") }}';
            const SEARCH_IN_ORDER_URL = '{{ url("/ordersList/search") }}';

            const CHECK_DEADLINES_URL = '{{ url("/ordersList/checkDeadlines") }}';
            const GETMETHODS_URL      = '{{ url("/newOrder/getMethods") }}';
            
            const POST_NEWS_URL       = '{{ url("/news/saveNews") }}';
            const GET_NEWS_URL        = '{{ url("/news/getNews") }}';
            const DELETE_NEWS_URL     = '{{ url("/news/deleteNews") }}';

            const GET_LOGS_URL        = '{{ url("/log/getLogData") }}';
            
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
</section>