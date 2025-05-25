import './bootstrap.js';

// ??? Inkább ez? TODO
// https://metalpriceapi.com/documentation#units

window.goldPrice = 0;

let goldPriceRequest = new Promise((resolve, reject) => {
    $.ajax({
        type: 'GET',
        url:  'https://api.nbp.pl/api/cenyzlota/?format=json',
        success: (response) => {
            response.forEach( (item) => {
                let date = new Date(item.data);
                let dateStr = `${date.getFullYear()}. ${date.getMonth()+1}. ${date.getDate()}.`;

                resolve({
                    cena: item.cena,
                    data: item.data
                });
            });
        }
    });
});

let zlotyGold = await goldPriceRequest;

if (zlotyGold) {
    $.ajax({
        type: 'GET',
        url:  'https://api.freecurrencyapi.com/v1/latest?apikey=fca_live_w2UADCwcGALMMsg0JmOYEL4DR9WfTVogJ6sdP1B4&currencies=HUF&base_currency=PLN',
        success: (response) => {
            let price = (zlotyGold.cena * response.data.HUF).toFixed(3);
            window.goldPrice = price;

            let row = $(`
                <div>
                    <div style="font-size: 22px;"><strong>${price} Ft</strong> / gramm</div>
                    <div style="width:100%;text-align:right;margin-top:15px">${zlotyGold.data}</div>
                </div>
            `).appendTo($('[goldPrices]'));
        }
    });
}