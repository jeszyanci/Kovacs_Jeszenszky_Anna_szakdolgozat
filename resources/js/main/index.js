document.addEventListener("DOMContentLoaded", async function() {
    // Get methods
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
        METHOD_TYPES = response;

    // Set notification
    let deadlineResult = new Promise( (resolve, reject) => {
        $.ajax({
            type: 'GET',
            url:  CHECK_DEADLINES_URL,
            success: (response) => {
                let data = JSON.parse(response);
                resolve(data);
            },
            error: (error) => {
                reject(error);
            }
        });
    });

    let deadlineResponse = await deadlineResult;

    let notification = $('#btn-notification');

    if (deadlineResponse.length > 0) {
        notification.addClass('hasNotification');
        notification.attr('noti-length', deadlineResponse.length);
    } else {
        notification.removeClass('hasNotification');
    }
   
    //
    // NEWS
    //

    // create news holder
    let newsholder = $(`
        <div class="newsholder box light">
            <div class="news-title boxTitle">Hírek</div>
            <div class="news-content"></div>
            <div style="display: flex; align-items: center; justify-content: center; margin: 10px;">
                <textarea class="box light"></textarea>
                <button class="btn btn-submit box button">Kitűzés</button>
            </div>
        </div>
    `);
    
    newsholder.appendTo('section.mainPageContent');

    // create new item
    newsholder.find('.btn-submit').on('click', async () => {
        let text = newsholder.find('textarea').val().trim();

        if (!text.length)
            return;

        let news = new News({content:text});
        let newsData = await news.save();

        if (newsData) {
            newsholder.find('textarea').val('');

            let newsElem = $(`
                <div style="display: flex; flex-direction: row;">
                    <div class="news-item">
                        <div class="news-item-user">${newsData.user}</div>
                        <div class="news-item-text">${newsData.content}</div>
                        <div class="news-item-date">${newsData.date}</div>
                    </div>
                    <div class="news-item-delete" data-id="${newsData.id}">X</div>
                </div>
            `);

            newsElem.find('.news-item-delete').on('click', async e => {
                await news.delete();
                refreshNews();
            });

            newsElem.prependTo(newsholder.find('.news-content'));
        }
    });

    let refreshNews = () => {
        newsholder.find('.news-content').empty();

        let result = new Promise( (resolve, reject) => {
            $.ajax({
                type: 'GET',
                url:  GET_NEWS_URL,
                success: (response) => {
                    let data = JSON.parse(response).result;
                    resolve(data);
                },
                error: (error) => {
                    reject(error);
                }
            });
        });

        result.then( (response) => {;

            response.forEach( (newsData) => {
                let news = new News(newsData);

                let newsItem = $(`
                    <div style="display: flex; flex-direction: row;">
                        <div class="news-item">
                            <div class="news-item-user">${news.user}</div>
                            <div class="news-item-text">${news.content}</div>
                            <div class="news-item-date">${news.date}</div>
                        </div>
                        ${ news.modifiable ? `
                            <div class="news-item-delete" data-id="${news.id}">X</div>
                        ` : `` }
                    </div>
                `);
                newsItem.prependTo(newsholder.find('.news-content'));
                
                newsItem.find('.news-item-delete').on('click', async e => {
                    await news.delete();
                    refreshNews();
                });
            });

        });        
    }
    refreshNews();
});

