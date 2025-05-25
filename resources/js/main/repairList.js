function searchRepairs(dataObj) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: SEARCH_IN_ORDER_URL,
            type: 'GET',
            data: dataObj,
            success: function(response) {
                let data = JSON.parse(response);
                let repairList = [];

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

                loadTable(repairList);
                resolve();
            }
        });
    })
}

async function loadTable(list) {
    let tempFile = null;
    $('.tableHolder').empty();

    if (!list.length) {
        $('<div style="width:100%; text-align:center;">Nincs találat</div>').appendTo('.tableHolder');
        return;
    }

    list.forEach(repair => {
        console.log(repair);
        
        repairNum = pad(repair.id, 8);

        let row = $(`
            <div class="elemLine" id="${repair.id}">
                <div class="shortInfo">
                    <div>
                        <div>Rendelési szám</div>
                        <div>${repairNum}</div>    
                    </div>
                    <div>
                        <div>Megrendelő</div>
                        <div>${repair.buyername}</div>
                    </div>
                    <div>
                        <div>Dátum:</div>
                        <div>${repair.date}</div>
                    </div>
                    <div>
                        <div>Állapot:</div>
                        <select select-editState>
                            ${STATE_TYPES.map( state => `
                                <option value="${state.id}" ${state.id == repair.state ? `selected` : ``}>${state.name}</option>
                            `).join('')}
                        </select>
                    </div>
                    <div class="buttons">
                        <div class="button" btn-details style="height:fit-content">Részletek</div>
                    </div>
                    <div class="buttons">
                        <div class="button" btn-edit style="height:fit-content">Módosítás</div>
                    </div>
                    <div class="buttons">
                        <div class="button" btn-save style="height:fit-content; display:none;">Mentés</div>
                    </div>
                </div>
                <div class="details" style="display: none"></div>
                <div class="modifyHolder" style="display: none"></div>
            </div>
        `);

        let priceData = repair.priceData;

        let details = $(`
            <table class="detail-data">
                <tr>
                    <td>Telefonszám:</td>
                    <td>${repair.buyerphone}</td>
                </tr>
                <tr>
                    <td>Előleg:</td>
                    <td>${priceData.deposit} ${priceData.currency}</td>
                </tr>
                <tr>
                    <td>Ár:</td>
                    <td>${priceData.price} ${priceData.currency}</td>
                </tr>
            </table>
                
            <table>
                <tr>
                    <td>Hozott anyagok:</td>
                    <td>
                        <table>
                            ${repair.details.gotMaterials.map( m => `<tr><td>${m.weight} gramm</td><td> ${m.name} ${m.type != 'other' ? `${m.type}` : ``}</td></tr>`).join('')}
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>Folyamatok:</td>
                    <td>
                        <table>
                            ${repair.details.methods.map( m => `<tr><td>${m.name}</td><td>${m.time} óra</td></tr>`).join('')}
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>Leírás:</td>
                    <td>${repair.details.description}</td>
                </tr>
            </table>        
        `).appendTo(row.find('.details'));

        if (repair.details.sketch) {
            let imageURL = `http://localhost/KJ_Anna_szakdolgozat/storage/app/repairSketches/${repair.id}/sketch.png`;
            let sketch = $(`<img src="${imageURL}" alt="sketch" style="max-width: 100%" onerror="this.style.display = 'none'">`);
            sketch.appendTo('.details');
        }

        let gotMats = repair.details.gotMaterials;

        if (gotMats.length) {
            let ul = row.find('[gotMatList]');

            gotMats.forEach( material => {
                $(`<li>${material.weight} gramm - ${material.name} ${material.type != 'other' ? `(${material.type})` : ''}</li>`).appendTo(ul);
            });
        }

        row.find('[select-editState]').on('change', e => {
            let newState = $(e.target).val();
            repair.state = newState;
            repair.save();
        });

        row.find('[btn-details]').on('click', e => {
            let detailsElem = row.find('.details');

            if (detailsElem.is(':visible'))
                detailsElem.slideUp();
            else
                detailsElem.slideDown();
        });

        let tempFile = "asd";

        row.find('[btn-edit]').on('click', e => {
            let holder = row.find('.modifyHolder');
            holder.show();
            
            createNewRepairForm(holder, repair, tempFile);
            
            row.find('.details, [btn-edit]').hide();
            row.find('[btn-save]').show();
        });

        row.find('[btn-save]').on('click', e => {
            let holder = row.find('.modifyHolder');
            holder.hide();
            
            repair.save(tempFile);

            row.find('.details, [btn-edit]').show();
            row.find('[btn-save]').hide();
        });

        row.appendTo($('.tableHolder'));
    });
}

function pad(num, size) {return ('000000000' + num).substr(-size);}