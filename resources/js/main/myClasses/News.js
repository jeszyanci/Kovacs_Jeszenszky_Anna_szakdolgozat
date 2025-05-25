class News {
    id = null;
    content = null;
    modifiable = false;
    date = null;
    user = null;

    constructor(newsData=false) {
        for (const key in this) {
            if (newsData?.[key])
                this[key] = newsData[key];
        }
    }

    save() {
        return new Promise( (resolve, reject) => {
            $.ajax({
                type: 'POST',
                url:  POST_NEWS_URL,
                data: {content: this.content},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    response = JSON.parse(response);
                    resolve(response.data);
                },
                error: (error) => {
                    reject(error);
                }
            });
        });
    }

    delete() {
        return new Promise( (resolve, reject) => {
            $.ajax({
                type: 'POST',
                url:  DELETE_NEWS_URL,
                data: {id: this.id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    resolve();
                }
            });
        });
    }
}