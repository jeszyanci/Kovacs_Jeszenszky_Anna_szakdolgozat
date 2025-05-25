@include('layout')

<section>
    <div class="menu" style="display: inline-flex;">
        <div id="backBtn" class="button box" onclick=" window.location='{{ url('/') }}'">Vissza</div>
    </div>
</section>

<body>
    <div logHolder style="display:flex; justify-content:center"></div>
</body>

<script>
    window.onload = async () => {
         // all user data
         let allUserData = "";

        let userAjax = new Promise( resolve => {
            $.ajax({
                type: 'GET',
                url:  '{{ url("/profile/data/getAllUser") }}',
                success: (response) => {
                    allUserData = JSON.parse(response);
                    resolve(allUserData);
                }
            });
        });

        await userAjax;

        // logs
        let result = new Promise( resolve => {
            $.ajax({
                type: 'GET',
                url:  GET_LOGS_URL,
                success: (response) => {
                    resolve(response);
                }
            });
        });

        let response = await result;
        if (response) {
            let logHolder = $('[logHolder]');
    
            let table = $(`<table class="box" style="width: 90%;"></table>`).appendTo(logHolder);

            response.logs.forEach( log => {
                let row = $(`<tr></tr>`).appendTo(table);

                let rowData = $(`
                    <td>${log.id}</td>
                    <td>${allUserData.find( d => d.id == log.userid)?.name || "Deleted user"}</td>
                    <td>${log.type}</td>
                    <td>${log.content}</td>
                    <td>${log.created_at}</td>    
                `).appendTo(row);

                row.prependTo(table);
            });
        }

        
    };

</script>

