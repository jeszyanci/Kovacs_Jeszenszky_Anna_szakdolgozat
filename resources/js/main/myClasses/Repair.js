
class Repair {
    id = null;
    buyername = '';
    buyerphone = '';
    state = 0;
    deadline = '';
    date = null;

    details = {
        gotMaterials: [],
        methods: [],
        description: ''
    }

    priceData = {
        deposit: 0,
        price: 0,
        currency: 'Ft'
    }

    constructor(repairData=false) {
        for (const key in this) {
            if (repairData?.[key])
                this[key] = repairData[key];
        }
    }

    async save(file=false) {
        let detailsData = JSON.stringify(this.details);
        let priceData   = JSON.stringify(this.priceData);

        let repairID = await new Promise( (resolve, reject) => { 
            $.ajax({
                type: "GET",
                url:  SAVEREPAIR_URL,
                data: {
                    buyerName:  this.buyername,
                    buyerPhone: this.buyerphone,
                    deadline:   this.deadline,
                    details:    detailsData,
                    price:      priceData,
                    state:      this.state,
                    deadline:   this.deadline
                },
                success: response => {
                    resolve(response);
                },
                error: (xhr, status, error) => {
                    console.error("Error saving order:", error);
                    reject(error);
                }
            });
        });

        
        if (repairID)
            this.saveSketch(file, repairID);
    }

    saveSketch(file, repairID) {
        if (file) {
            let formData = new FormData();
            formData.append('image', file);
            formData.append('id', this.id || repairID);

            $.ajax({
                type: "POST",
                url:  SAVESKETCH_URL,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    console.log("file uploaded");
                }
            })
        }
    }
}