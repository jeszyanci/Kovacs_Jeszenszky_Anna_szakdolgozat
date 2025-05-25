// CREATE NEW REPAIR OBEJCT
let repairElem = new Repair();
let tempFile = null;

function createNewRepairForm(elem, data=false, _tempFile=false) {
    if (data)
        repairElem = data;

    if (_tempFile)
        tempFile = _tempFile;

    // MAIN HOLDER
    let mainHolder = elem;

    // REAPAIRFORM ELEMENT
    let repairForm = $(`
        <div class="repairElem printableContent"></div>
    `).appendTo(mainHolder);
    
    // BUYER ELEMENT
    let buyerElement = $(`
        <div>
             <div style="display:flex; flex-direction:column; align-items: center; margin-bottom: 10px;">
                <div class="box" style="display:flex; justify-content:center; text-align:center; margin-bottom: 10px; width:100%;">
                    <div style="flex:1;">${data ? pad(repairElem.id, 8) : ''}</div>
                    <div class="title flex-5 boxTitle">Megrendelőlap</div>
                    <div id="todayDate" style="flex:1;"></div>
                    ${data ? `
                        <div id="originalDate" style="flex:1;"><i>${data.date}</i></div>
                    ` : ``}
                </div>
            </div>

            <div class="box" style="width:60%; text-align:center;">
                <div class="boxTitle">Adatok</div>
                <div style="display:flex; flex-direction:row; align-items: center;">
                    <div class="formblockHolder mb-10">
                        <div class="formBlock">
                            <span style="display:inline-block; width:150px; text-align:right;">Név:</span>
                            <input param="buyername" type="text" value="${data ? data.buyername : ''}">
                        </div>
                        <div class="formBlock">
                            <span style="display:inline-block; width:150px; text-align:right;">Tel:</span>
                            <input param="buyerphone" type="text" value="${data ? data.buyerphone : ''}">
                        </div>
                        <div class="formBlock">
                            <span style="display:inline-block; width:150px; text-align:right;">E-mail</span>
                            <input param="buyeremail" type="text" value="${data ? data.buyeremail : ''}" style="width: 200px; height: 30px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `)
    .appendTo(repairForm);

    ['buyername', 'buyerphone'].forEach( paramName => {
        buyerElement.find(`input[param="${paramName}"]`).on('change', event => {
            repairElem[paramName] = $(event.target).val().trim();
        });
    });

    // GOT MATERIAL
    let gotMaterialElement = $(`
        <div id="gotMaterial" class="formblockHolder box" style="margin-top:10px; width:60%; display:flex; flex-direction:column; align-items: center; margin-bottom: 10px; width:30%">
            <div class="boxTitle" style="display:flex; flex-direction:row; justify-content: center; align-items: center;">
                <div>Hozott anyagok</div>
                <div openPopupMaterial class="button box" style="width:fit-content; height:fit-content; cursor:pointer">+</div>
            </div>
            <div class="description">
                <div class="addedMaterialList" style="line-height: 35px; list-style-type: none; width: 100%;"></div>
            </div>
        </div>
    `).appendTo(repairForm);

    refreshAddedMats();
    
    // OPEN POPUP - MATERIAL
    $(`[openPopupMaterial]`).on('click', () => {
        if ( !$(`.popup`).length ) {
            createMaterialPopup(repairElem.details.gotMaterials);
        }
    });

    let methodsElem = $(`
        <div methodsHolder style="margin-top:15px;">
            <div openPopupMethod class="box button" style="width:fit-content; cursor:pointer">Folyamatok hozzáadása</div>
            <div tableHolder class="box light" style="width:50%; border:none !important;"></div>
        </div>
    `)
    .appendTo(repairForm);
    
    // OPEN POPUP - METHOD
    methodsElem.find(`[openPopupMethod]`).on('click', () => {
        if ( !$(`.popup`).length )
            createMethodPopup();
    });

    // EXTRA ELEMENT
    let extraElement = $(`
        <div class="formblockHolder" style="display:flex; flex-direction:row; gap:1%; margin-top:15px;">
            <div class="formBlock box" style="display:flex; flex-direction: column;width:fit-content; margin-top: 15px;">
                <span class="dataLabel boxTitle">Vázlat</span>
                <input type="file" id="sketch" name="sketch" accept="image/*" class="input" style="color:#805852;">
            </div>
            <div class="formBlock box" style="width:100%; margin-top: 15px; overflow: hidden;">
                <span class="dataLabel boxTitle" style="display:inline-block; width:100%">Leírás</span>
                <textarea description class="description" style="color:#805852;">${data.details?.description || ""}</textarea>
            </div>
        </div>
    `)
    .appendTo(repairForm);
    
    extraElement.find('[description]').on('change', event => {
        repairElem.details.description = $(event.target).val();
    });

    extraElement.find('#sketch').on('change', event => {
        repairElem.details.sketch = event.target.files.length > 0;
        tempFile = event.target.files[0];
    });

    let priceAndDeadlineHolder = $(`
        <div class="block" style="display:flex; flex-direction:row; gap:10%; margin-top:15px; padding:10px; width:100%;"></div>
    `).appendTo(repairForm);

    let priceElems = $(`
        <div class="block box" style="margin-top:15px;width:fit-content;">
            <div class="boxTitle" style="display: inline-block">Előleg és irányár</div>
            <div>
                <label style="display:inline-block;width:100px;">Pénznem</label>
                <select currency style="display:inline-flex; width:175px;text-align:center; ">
                    <option value="ft">Forint</option>
                    <option value="eu">Euro</option>
                </select>
            </div>
    
            <div style="width:100%;" class="formblockHolder ">
                <div class="formBlock">
                    <span style="display:inline-block; width:100px;">Előleg</span>
                    <input deposit type="text" style="text-align:center;">
                </div>
                
                <div id="price" class="formBlock">
                    <span style="display:inline-block; width:100px;">Irányár</span>
                    <input price type="text" style="text-align:center;">
                </div>
            </div>
        </div>
    `)
    .appendTo(priceAndDeadlineHolder);
    
    ['currency', 'deposit', 'price'].forEach( dataName => {
        priceElems.find(`[${dataName}]`).on('change', event => {
            repairElem.priceData[dataName] = $(event.target).val();
        });
        
    });

    let deadlineElem = $(`
        <div class="block box" style="margin-top:15px; width:fit-content; width:fit-content; text-align:center;">
            <div class="boxTitle" style="display: inline-block">
                <span>Határidő</span>
            </div>
            <div style="margin-bottom:5px; padding:10px">
                <span id="calculatedDays"></span>
            </div>
            <div style="width:100%;" class="formblockHolder ">
                <input type="date" id="deadline">
            </div>
        </div>
    `)
    .appendTo(priceAndDeadlineHolder);

    deadlineElem.find('#deadline').on('change', event => {
        repairElem.deadline = $(event.target).val();
    });

    // ADD TODAY'S DATE
    function setDate(element=false) {
        let time  = new Date();
        let year  = time.getFullYear();
        let month = parseInt(time.getMonth()+1);
        let day   = time.getDate();
        
        let date = `${year}-${month}-${day}`;

        if (element)
            element.text(date);

        return date;
    }
    setDate($('#todayDate'));
}

let createMaterialPopup = (gotMaterials) => {
    let popup = $(`
        <div class="popup box light">
            <div class="header boxTitle">
                <div>Hozott anyagok</div>
                <div closePopup style="cursor:pointer; float:right;">X</div>
            </div>

            <div class="content">
                <div class="formBlock">
                    <div>
                        <span>Anyag:</span>
                        <select class="dropdown" matSelector style="display: inline-block; font-size: 15px">
                            <option value="Au750">Arany - 18 karát</option>
                            <option value="Au585">Arany - 14 karát</option>
                            <option value="Ag">Ezüst</option>
                            <option value="other">egyéb</option>
                        </select>
                    </div>
                    <div matName style="display: none;">
                        <span>Elnevezés:</span>
                        <input type="text" style="min-width: 70px; text-align: center;">
                    </div>
                    <div>
                        <span>Gramm:</span>
                        <input type="number" id="gotMatWeight" style="width: 50px; text-align: center;">
                    </div>
                    <div addMaterial class="button" style="float:right">Hozzáadás</div>
                </div>
            </div>
        </div>
    `)
    .appendTo($('body'));

    // close popup
    popup.find('[closePopup]').on('click', () => {
        popup.remove();
    });

    // any other tpye of gotMaterial
    popup.find('[matSelector]').on('change', (e) => {
        let matType = $(e.target).val();
        let matNameElem = popup.find("[matName]");

        let isOther = matType == "other";
        matNameElem.toggle(isOther);
    });

    // ADD SELECTED GOT MATERIAL TO THE LIST
    popup.find('[addMaterial]').on('click', () => {
        let weightElem = popup.find('#gotMatWeight');
        let typeElem   = popup.find('[matSelector]');
        let nameElem   = popup.find('[matName] input');

        let matWeight = weightElem.val().trim();
        let matType   = typeElem.val()
        let matName   = nameElem.val().trim() ?? typeElem.text();

        // if all needed data is set
        if (matWeight && (matType != 'other' || matName) ) {
            gotMaterials.push({
                'weight' : matWeight,
                'type'   : matType,
                'name'   : matName
            });

            refreshAddedMats();
        }
    });
};

let createMethodPopup = () => {
    let methods = ['forrasztás', 'stift', 'extra stift', 'szűkítés', 'betold'];

    let popup = $(`
        <div class="popup box light">
            <div class="header">
                <div>Folyamatok</div>
                <div closePopup style="cursor:pointer; float:right;">X</div>
            </div>

            <div class="content">
                <div class="formBlock">
                    <div>
                        <span>Típus:</span>
                        <select methodSelector class="dropdown" style="display: inline-block; font-size: 15px">
                            ${methods.map( (type, i) => `<option value="${i}">${type}</option>`).join('') }
                        </select>
                    </div>

                    <div>
                        <span>Szükséges idő:</span>
                        <input neededTime type="number">
                        <span>óra</span>
                    </div>

                    <div addMethod class="button" style="float:right">Hozzáadás</div>
                </div>
            </div>
        </div>
    `)
    .appendTo($('body'));

    // close popup
    popup.find('[closePopup]').on('click', () => {
        popup.remove();
    });

    popup.find('[addMethod]').on('click', () => {
        let time = popup.find('[neededTime]').val();
        let type = popup.find('[methodSelector]').val();
        let name = popup.find('[methodSelector] option:selected').text();

        repairElem.details.methods.push({
            id:   type,
            time: time,
            name: name
        });

        createTable();

        let hours = 0;
        repairElem.details.methods.forEach( method => {
            hours += Number(method.time);
        });
        let minDays = Math.ceil(hours/8);

        $("#calculatedDays").text(`Az elkészítéshez szükséges idő min. ${minDays} nap.`);
    });
}

function createTable() {
    let holder = $('[methodsHolder] [tableHolder]');
    holder.empty();

    let methods = repairElem.details.methods;
    if (!methods)
        return;

    let table = $(`
        <table style="table-layout: fixed;">
            <tr style="color: #805852;">
                <th class="boxTitle" style="width:45%; font-size:20px; border-color:#C6AB9D;">Folyamat</th>
                <th class="boxTitle" style="width:45%; font-size:20px; border-color: #C6AB9D;">Tervezett idő</th>
                <th class="boxTitle" style="width:10%; font-size:20px; border-color:#C6AB9D">X</th>
            </tr>
        </table>
    `);

    let sumTime = 0;
    let guidePrice = 0;

    methods.forEach( (method, i) => {
        sumTime += Number(method.time);

        let newRow = $(`
            <tr style="color: #805852;">
                <td style="font-size:18px;">${method.name}</td>
                <td style="font-size:18px;">${method.time} óra</td>
                <td style="font-weight:bold;" class="deleteMethod" style="cursor: pointer;">Törlés</td>
            </tr>
        `).appendTo(table);

        newRow.find('.deleteMethod').click( e => {
            methods.splice(i, 1);
            createTable();
        });
    });

    let sumRow = $(`
        <tr style="color: #805852; font-weight: bold;">
            <td style="font-size:20px;">${methods.length} folyamat</td>
            <td style="font-size:20px;">${sumTime} óra</td>
        </tr>
    `).appendTo(table);

    
    let servicePrice = window.goldPrice * Number($(`[productWeight]`).val());
    repairElem.priceData.price = guidePrice + servicePrice;

    $(`[price]`).val(`${guidePrice + servicePrice} Ft`);

    table.appendTo(holder);
}

let refreshAddedMats = () => {
    let parent  = $('.addedMaterialList');
    parent.empty();

    repairElem?.details?.gotMaterials?.forEach( (material, i) => {
        let li = $(`
            <div class="box light" style="display: flex; align-items:center; padding: 5px; margin: 5px; font-size: 18px;">
                <div style="display: inline-block;">
                    <strong>${material.weight} gramm</strong> ${material.name} ${material.type != 'other' ? `${material.type}` : ``}
                </div>
                <div updateBtn class="button" style="align-items: center;">Módosítás</div>
                <div removeBtn class="button" style="align-items: center;">Törlés</div>
            </div>
        `).appendTo(parent);

        li.find('[removeBtn]').click( e => {
            repairElem?.details?.gotMaterials?.splice(i, 1);
            refreshAddedMats();
        });
    });
};

// SAVE ORDER
$('#saveOrder').on('click', () => {
    repairElem.save(tempFile);
});

// PRINT BTN
$('.printBtn').click( e => {
    repairElem.save(tempFile);

    let sHeight = screen.height;
    let sWidth = screen.width;

    let html = createPrintableContent();

    let win = window.open(
        "#",
        "PrintableContent",
        "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width="+sWidth*0.8 +",height="+sHeight*0.8 +",top="+sHeight/4+",left="+sWidth/4
    );
    
    win.document.write(html);

    win.print();
    win.close();
});

let createPrintableContent = (order) => {
    let html = `
        <section>
            <div>
                <div class="mb-15" style="display:flex; justify-content:center; text-align:center">
                    <div style="flex:1;">0123456</div>
                    <div class="title flex-5">Megrendelőlap</div>
                </div>

                <div class="mt-15" style="display: flex; flex-direction: column;">
                    <div class="formblockHolder mb-10">
                    <div>Megrendelő:</div>
                        <div>Dátum: ${repairElem.date}</div>
                        <div class="formBlock">
                            <span>Név: ${repairElem.buyername}</span>
                        </div>
                        <div class="formBlock">
                            <span>Tel: ${repairElem.buyerphone}</span>
                        </div>
                    </div>
                    <div>Leírás: ${repairElem.details.description}</div>
                    <div>Hozott anyagok:</div>
                    <div>
                        <ul>
                            ${repairElem.details?.gotMaterials?.map( mat => `<li>${mat.weight} gramm - ${mat.name} ${mat.type != 'other' ? `(${mat.type})` : ``}</li>`).join('')}
                        </ul>
                    </div>
                    <div>Folyamatok:</div>
                    <div>
                        <table>
                            <tr>
                                <th>Folyamat</th>
                                <th>Tervezett idő</th>
                            </tr>
                            ${repairElem.details.methods.map( method => `<tr><td>${method.name}</td><td>${method.time} óra</td></tr>`).join('')}
                        </table>
                    </div>
                    <div>Megrendelés:</div>
                    <div>Előleg és irányár:</div>
                    <div>
                        <div>Előleg: ${repairElem.priceData.deposit} ${repairElem.priceData.currency}</div>
                        <div>Irányár: ${repairElem.priceData.price} ${repairElem.priceData.currency}</div>
                    </div>
                </div>
            </div>
        </section>
    `;

    return html;
};

createNewRepairForm($('[mainBody]'));