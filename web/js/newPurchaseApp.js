var debug = true;
var item;
var items;


var App = (function () {
    var buttonOfAddItem = $('.putItemToBasket'),
        openModal = $('#openModalButton'),
        modalChooseItem = $('#modalChooseItem'),
        contentReceiptId = $('#contentReceiptId'),
        sumPrice = $('.sumPrice'),
        formPurchase = $('#formPurchase'),
        saveButton = $('#saveButton'),
        addItem = $('#startSearchProductByArticle'),
        articleInput = $('#articleSearch'),
        urlGateWay = '/backend/product/get-item-by-article'
        ;

    var setEvents = function () {

        addItem.on('click', function () {
            if(!articleInput.val()){
                alert('Отсканируйте артикул!');
                return false;
            }

            requestForGettingNewItem();
        });


        $( "body" ).on( "click", ".putItemToBasket",  function (e) {

            // create object
            var dataOfNewItem = {
                'name': $(e.currentTarget).data('name'),
                'product_id': $(e.currentTarget).data('productid'),
            };

            itemsCollection.add(new item(dataOfNewItem));
            currentItemsView.render();

            modalChooseItem.modal('hide');
            if(debug)
            {
                console.log('choosen new item -> ');
                console.log(dataOfNewItem);
                console.log(JSON.stringify(itemsCollection));
            }

        });


        openModal.on('click', function () {
            modalChooseItem.modal('show');
        });




        saveButton.on('click', function () {

            var errorFlag = false;

            itemsCollection.each(function(model) {

                if(!errorFlag) {
                    if (!model.isValid()) {
                        alert("\"" + model.get("name") + "\"" + " " + model.validationError);
                        errorFlag = true;
                    }
                }

            });

            if(!errorFlag)
                formPurchase.submit();
        });

    };

    var requestForGettingNewItem = function () {
        $.ajax({
            url: urlGateWay,
            type: "GET",
            data: {article: articleInput.val()},
            dataType: "json",
            success: function(data){
                if(data){

                    if(data.count == 1){

                        $.each(data.products, function( index, value ) {

                            var dataOfNewItem = {
                                'name': value.name,
                                'product_id': value.product_id,
                                'article': value.article,
                            };

                            itemsCollection.add(new item(dataOfNewItem));
                            currentItemsView.render();
                        });


                    }
                }

            }
        });
    };

    var initializeApplication = function () {
        /*
         * Models
         * */
        item = Backbone.Model.extend({
            defaults: {
                "name": "",
                'product_id' : '',
                'id' : '',
                'count' : '1',
                'priceIn' : '',
                'priceOut' : '',
                'article' : '',
            },
            idAttribute: 'cid',

            validate: function(attrs, options) {
                if (attrs.count < 1 || !attrs.count) {
                    return "Не правильно задано кол-во";
                }

                if (!attrs.priceIn) {
                    return "Не правильно задана цена закупки";
                }

                if (!attrs.priceOut) {
                    return "Не правильно задана цена продажи";
                }

                if (!attrs.article) {
                    return "Не правильно задан артикул";
                }
            }
        });
        /*
         * collections
         * */
        items = Backbone.Collection.extend({
            model: item
        });
        /*
         * Views
         * */
        itemView = Backbone.View.extend({
            events: {
                'click .deleteItemFromBasket': 'deleteModel',
                'change .articleInput': 'changeModel',
                'change .countInput': 'changeModelAndRender',
                'change .priceInInput': 'changeModelAndRender',
                'change .priceOutInput': 'changeModel',
            },

            deleteModel: function(){
                itemsCollection.remove(this.model);
                currentItemsView.render();
            },

            changeModel: function (e) {
                var val = $(e.currentTarget);
                this.model.set(val.attr('id'), val.val());

                if(val.attr('id') == 'priceOut'){

                    var priceNew = parseInt(val.val()) + 2;
                    var percentOfPrice = (priceNew*0.09);

                    var element = $(val).parent().parent().parent().find('#priceIn');
                    priceNew = priceNew-percentOfPrice;
                    
                    element.val(priceNew);
                    this.model.set('priceIn', priceNew);

                    currentItemsView.render();
                    
                }
            },

            changeModelAndRender: function (e) {
                var val = $(e.currentTarget);
                this.model.set(val.attr('id'), val.val());
                currentItemsView.render();
            },

            tagName: 'tr',
            template: _.template($('#templateItems').html()),
            render: function() {
                this.$el.html(this.template(this.model.attributes));
                return this;
            }

        });
        itemsView = Backbone.View.extend({

            el: '#contentReceiptId',

            initialize: function() {
                this.render();
            },

            render: function() {
                this.$el.html('');

                var priceSum = 0;

                if(itemsCollection.length == 0){
                    this.$el.append('<tr><td colspan="6">Нет товаров</td></tr>');
                }

                itemsCollection.each(function(model) {

                    var itemLine = new itemView({
                        model: model
                    });

                    priceSum = priceSum + ((model.get('priceIn')* model.get('count')));

                    this.$el.append(itemLine.render().el);
                }.bind(this));

                if(priceSum > 0)
                    sumPrice.val(priceSum).html(priceSum);
                else
                    sumPrice.val(0).html(0);

                return this;
            }

        });

        constructorStart();
        //modalChooseItem.modal('show');
    };

    var constructorStart = function () {
        itemsCollection = new items();
        currentItemsView = new itemsView;
    };

    var start = function () {
        initializeApplication();
        setEvents();
    };

    return start();

});

$(document).ready(function () {
    if(debug)
        console.log('start application');

    App();
});