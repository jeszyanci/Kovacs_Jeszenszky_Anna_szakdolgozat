
class Order {
    id = null;
    buyername  = ""; 
    buyerphone = "";
    state = 0;
    deadline = null;

    details = {
        gotMaterials: [],
        methods: [],
        product: {
            color: '#s',
            weight: 0,
            fingerSize: 0,
            sizes: {
                width: {
                    value: 0,
                    unit: 'mm'
                },
                height: {
                    value: 0,
                    unit: 'mm'
                },
                length: {
                    value: 0,
                    unit: 'mm'
                },
            }
        },
        description: '',
        sketch: null
    };

    priceData = {
        deposit:  0, 
        price:    0, 
        currency: "Ft", 
    };

    date = null;

    progressData = [];

    constructor(orderData=false) {
        for (const key in this) {
            if (orderData?.[key])
                this[key] = orderData[key];
        }
    }

    async save(file=false) {
        let detailsData = JSON.stringify(this.details);
        let priceData   = JSON.stringify(this.priceData);

                    	this.saveSketch(file);
        let orderID = await new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url:  SAVEORDER_URL,
                data: {
                    id:         this.id,
                    buyerName:  this.buyername,
                    buyerPhone: this.buyerphone,
                    deadline:   this.deadline,
                    details:    detailsData,
                    price:      priceData,
                    state:      this.state,
                    deadline:   this.deadline,
                },
                success: response => {
                    resolve(response);
                },
                error: (xhr, status, error) => {
                    console.error("Error saving order:", error);
                    reject(error);
                },
            });
        });

        if (orderID)
            this.saveSketch(file, orderID);
    }

    saveSketch(file, orderID) {
        if (file) {
            let formData = new FormData();
            formData.append('image', file);
            formData.append('id', this.id || orderID);

            $.ajax({
                type: "POST",
                url:  SAVESKETCH_URL,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        }
    }

    saveMethodProgress() {
        $.ajax({
            type: "POST",
            url:  SAVE_METHODPROGRESS_URL,
            data: {
                id: this.id,
                progressData: JSON.stringify(this.progressData)
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log("method progress saved");
            }
        });
    }
}