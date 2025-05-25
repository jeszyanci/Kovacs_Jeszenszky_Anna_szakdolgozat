function searchOrders(dataObj, allUserData) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: SEARCH_IN_ORDER_URL,
            type: 'GET',
            data: dataObj,
            success: function(response) {
                let data = JSON.parse(response);
                let orderList = [];

                data.forEach( order => {
                    let orderData = {
                        id:         order.id,
                        buyername:  order.buyername,
                        buyerphone: order.buyerphone,
                        details:    JSON.parse(order.details),
                        priceData:  JSON.parse(order.price),
                        state:      order.state,
                        date:       order.created_at,
                        deadline:   order.deadline
                    }

                    let newOrder = new Order(orderData);

                    orderList.push(newOrder);
                });

                loadTable(orderList, allUserData);
                resolve();
            }
        });
    })
}

async function loadTable(list, allUserData) {
    let tempFile = null;
    $('.tableHolder').empty();

    if (!list.length) {
        $('<div style="width:100%; text-align:center;">Nincs találat</div>').appendTo('.tableHolder');
        return;
    }

    list.forEach(order => {
        orderNum = pad(order.id, 8);

        let row = $(`
            <div class="elemLine box" id="${order.id}">
                <div class="shortInfo" style="font-size:18px; ">
                    <div>
                        <div>${orderNum}</div>    
                    </div>
                    <div>
                        <div>${order.buyername}</div>
                    </div>
                    <div>
                        <div>${order.date}</div>
                    </div>
                    <div>
                        <select select-editState>
                            ${STATE_TYPES.map( state => `
                                <option value="${state.id}" ${state.id == order.state ? `selected` : ``}>${state.name}</option>
                            `).join('')}
                        </select>
                    </div>
                    <div class="buttons">
                        <div class="button" btn-details style="height:fit-content">Részletek</div>
                    </div>
                    <div class="buttons">
                        <div class="button" btn-tracking style="height:fit-content">Tevékenységkövetés</div>
                    </div>
                    <div class="buttons">
                        <div class="button" btn-edit style="height:fit-content">Módosítás</div>
                    </div>
                    <div class="buttons">
                        <div class="button" btn-save style="height:fit-content; display:none;">Mentés</div>
                    </div>
                </div>
                <div class="details box light" style="display: none"></div>
                <div class="tracking box light" style="display: none"></div>
                <div class="modifyHolder box light" style="display: none"></div>
            </div>
        `);

        let priceData = order.priceData;
        let product = order.details.product;
        let productSizes = product.sizes;

        let details = $(`
            <div class="detailsTableHolder" style="display: flex; flex-direction: row; gap: 10px; margin-bottom: 10px;">
                <div class="box" style="flex: 1; height: fit-content;">
                    <table class="detail-data" style="table-layout: fixed; height: fit-content;">
                        <tr>
                            <td class="boxTitle" style="width:200px;">Telefonszám</td>
                            <td>${order.buyerphone}</td>
                        </tr>
                        <tr>
                            <td class="boxTitle" style="width:200px;">Előleg</td>
                            <td>${priceData.deposit} ${priceData.currency}</td>
                        </tr>
                        <tr>
                            <td class="boxTitle" style="width:200px;">Ár</td>
                            <td>${priceData.price} ${priceData.currency}</td>
                        </tr>
                    </table>
                </div>
                <div class="box" style="flex: 1; height: fit-content;">
                    <table style="table-layout: fixed; height: 100%;">
                        <tr>
                            <td class="boxTitle" style="width:200px;">Szín</td>
                            <td>${product.color}</td>
                        </tr>
                        <tr>
                            <td class="boxTitle">Súly</td>
                            <td>${product.weight}</td>
                        </tr>
                        <tr>
                            <td class="boxTitle">Ujjméret</td>
                            <td>${product.fingerSize}</td>
                        </tr>
                        <tr>
                            <td class="boxTitle">Méretek</td>
                            <td style="text-align: left;">
                                <div>Szélesség: ${productSizes.width.value} ${productSizes.width.unit}</div>
                                <div>Magasság: ${productSizes.height.value} ${productSizes.height.unit}</div>
                                <div>Hossz: ${productSizes.length.value} ${productSizes.length.unit}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            ${ order.details.description ? `
                <div class="formBlock box light" style="width:100%; margin-bottom: 15px; overflow: hidden; display: flex; flex-direction: column; align-items: center;">
                    <span class="dataLabel boxTitle" style="display:inline-block; width:100%">Leírás</span>
                    <div style="padding:5px;">${order.details.description}</div>    
                </div>
            ` : ``
            }
            <div class="detailsTableHolder" style="display: flex; flex-direction: row; gap: 10px; ">
                <div class="formBlock box light" style="flex:1;overflow: hidden; display: flex; flex-direction: column; align-items: center; height: fit-content;">
                    <div class="boxTitle">Hozott anyagok</div>
                    <div style="width: 100%; height:fit-content;">
                        <table style="width:100%; height:100%">
                            ${order.details.gotMaterials.map( m => `<tr><td>${m.weight} gramm</td><td> ${m.name} ${m.type != 'other' ? `${m.type}` : ``}</td></tr>`).join('')}
                        </table>
                    </div>
                </div>
                <div class="formBlock box light" style="flex:1;overflow: hidden; display: flex; flex-direction: column; align-items: center;">
                    <div class="boxTitle">Folyamatok</div>
                    <div style="width: 100%; height: 100%;">
                        <table style="width:100%; height:100%">
                            ${order.details.methods.map( m => `<tr><td>${m.name}</td><td>${m.time} óra</td></tr>`).join('')}
                        </table>
                    </div>
                </div>
            </div>
        `).appendTo(row.find('.details'));
        
        if (order.details.sketch) {
            let imageURL = `http://localhost/KJ_Anna_szakdolgozat/storage/app/orderSketches/${order.id}/sketch.png`;
            let sketch = $(`<img src="${imageURL}" alt="sketch" style="max-width: 20%" onerror="this.style.display = 'none'">`);
            sketch.appendTo(row.find('.details'));
        }

        let gotMats = order.details.gotMaterials;

        if (gotMats.length) {
            let ul = row.find('[gotMatList]');

            gotMats.forEach( material => {
                $(`<li>${material.weight} gramm - ${material.name} ${material.type != 'other' ? `(${material.type})` : ''}</li>`).appendTo(ul);
            });
        }

        row.find('[select-editState]').on('change', e => {
            let newState = $(e.target).val();
            order.state = newState;
            order.save();
        });

        row.find('[btn-details]').on('click', e => {
            let detailsElem = row.find('.details');

            if (detailsElem.is(':visible'))
                detailsElem.slideUp();
            else
                detailsElem.slideDown();
        });

        row.find('[btn-tracking]').on('click', e => {
            let trackingElem = row.find('.tracking');

            if (trackingElem.is(':visible'))
                trackingElem.slideUp();
            else
                trackingElem.slideDown();
        });

        let tempFile = "asd";

        row.find('[btn-edit]').on('click', e => {
            let holder = row.find('.modifyHolder');
            holder.show();
            
            createNewOrderForm(holder, order, tempFile, true);
            
            row.find('.details, [btn-edit]').hide();
            row.find('[btn-save]').show();
        });

        row.find('[btn-save]').on('click', e => {
            let holder = row.find('.modifyHolder');
            holder.hide();
            
            row.find('.details, [btn-edit]').show();
            row.find('[btn-save]').hide();
        });

        row.appendTo($('.tableHolder'));
        
        loadTrackData(order, allUserData, row.find('.tracking'));
    });

}

function pad(num, size) {return ('000000000' + num).substr(-size);}

let loadTrackData = (order, allUserData, parent) => {
    let table = $(`
        <table class="tracking-data box" style="table-layout: fixed;">
            <tr skip style="font-size: 20px;">
                <td>Folyamat</td>
                <td>Alkalmazott</td>
                <td>Óraszám</td>
                <td>Megjegyzés</td>
            </tr>
        </table>
    `);

    if (order.progressData.length)
        console.log(order.progressData);

    order.details.methods.forEach( method => {
        let progressData = order.progressData?.find( m => m.name == method.name );

        let row = $(`
            <tr>
                <td methodName class="boxTitle">${method.name}</td>
                <td>
                    <select class="userSelect">
                        ${allUserData.map( user => `
                            <option value="${user.id}" ${user.id == progressData?.userId ? `selected` : ``}>${user.nickname}</option>
                        `).join('')}
                    </select>
                </td>
                <td>
                    <span>Tervezett: ${method.time} óra</br></span>
                    <span>Jelenleg:</span><input progressTime type="number" value="${progressData?.time ?? '0'}" min="0" step="0.5" style="width: 50px; text-align:right;"> óra</br>
                    ${progressData ? `<span>Eltérés: ${progressData.time - method.time} óra</span>` : ``}
                </td>
                <td>
                    <textarea type="text" progressNote style="width: 80%; color:#805852;">${progressData?.note ?? ''}</textarea>
                </td>
            </tr>
        `);

        row.appendTo(table);
        
        row.find('.userSelect, [progressTime], [progressNote]').on('change', e => {
            saveProgess(order, table); 
        });
    });

    parent.append(table);
};

let saveProgess = (order, table) => {
    order.progressData = [];

    table.find('tr').not('[sum], [skip]').each( (i, elem) => {
        let row = $(elem);
        let methodName = row.find('[methodName]').text();
        let selectedUser = row.find('.userSelect').val();
        let time = row.find('[progressTime]').val();
        let note = row.find('[progressNote]').val();
        

        order.progressData.push({
            name: methodName,
            userId: selectedUser,
            time: time,
            note: note
        });
    });
    
    order.saveMethodProgress();
};