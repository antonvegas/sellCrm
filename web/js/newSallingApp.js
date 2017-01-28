var debug = true;
var item;
var items;


var App = (function () {
    var addItem = $('#addItem'),
        articleInput = $('#articleInput'),
        modalChooseItem = $('#modalChooseItem'),
        contentReceiptId = $('#contentReceiptId'),
        sumPrice = $('.sumPrice'),
        discount = $('#discountId'),
        saveButton = $('#saveButton'),
        formSale = $('#formSale'),
        money = $('#money'),
        short_change = $('#short_change'),
        modalChooseItem = $('#modalChooseItem'),
        urlGateWay = '/backend/product/get-item-by-article'
        ;

    var setEvents = function () {

        $('#discountId').on('change', function () {
            currentItemsView.render();
        });

        addItem.on('click', function () {
            if(!articleInput.val()){
                alert('Отсканируйте артикул!');
                return false;
            }

            requestForGettingNewItem();
        });

        articleInput.on('focus', function () {
            articleInput.val('');
        });

        money.on('change', function () {
            if(sumPrice.val() > 0){
                short_change.val( $(this).val() - sumPrice.val()+' руб.');
            }
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
                formSale.submit();
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

                    if(debug)
                        console.log(data);

                    var newData = [];
                    var countOfGoodItem = 0;

                    if(data.count > 0){

                        $.each(data.products, function( index, value ) {

                            if(value.count > 0){
                                var dataOfNewItem = {
                                    'name': value.name,
                                    'price': value.price,
                                    'count': value.count,
                                    'product_id': value.product_id,
                                    'article': value.article,
                                    'url_image': value.url_image,
                                    'id': value.id,
                                };
                                newData.push(dataOfNewItem);
                                countOfGoodItem++;
                            }
                        });
                    }
                    
                    if(countOfGoodItem == 0){

                        alert(
                            'По заданному артикулу на складе товара нет!'
                        );

                    }else if(countOfGoodItem == 1){

                        $.each(newData, function( index, value ) {

                            var dataOfNewItem = {
                                'name': value.name,
                                'price': value.price,
                                'countOnStock': value.count,
                                'count': value.count > 1 ? 1 : 0,
                                'product_id': value.product_id,
                                'article': value.article,
                                'url_img': value.url_image,
                                'stock_id': value.id,
                            };

                            var createdModel = itemsCollection.where({ stock_id: value.id });

                            if(createdModel.length == 0){
                                itemsCollection.add(dataOfNewItem);
                                currentItemsView.render();
                            }else{
                                alert('Данный товар уже в корзине');
                            }


                        });


                    }else{
                        prepareItemsCollection.reset();

                        $.each(newData, function( index, value ) {

                            var dataOfNewItem = {
                                'name': value.name,
                                'price': value.price,
                                'countOnStock': value.count,
                                'product_id': value.product_id,
                                'article': value.article,
                                'url_img': value.url_image,
                                'stock_id': value.id,
                            };
                            prepareItemsCollection.add(dataOfNewItem);
                        });

                        prepareChooseItemsView.render();
                        modalChooseItem.modal('show');
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
                "price": "",
                "count": "1",
                "countOnStock": "",
                "stock_id": "",
                'product_id' : '',
                'id' : '',
                'article' : '',
                'url_img' : '',
            },
            idAttribute: 'cid',

            validate: function(attrs, options) {
                if (!attrs.countOnStock) {
                    return "на складе нет, удалите из списка.";
                }

                if (attrs.count > attrs.countOnStock) {
                    return "вы выбираете " + attrs.count  + " шт. На складе " + attrs.countOnStock + " шт.";
                }

                if (attrs.count < 1 || !attrs.count) {
                    return "не правильно задано кол-во";
                }

                if (!attrs.product_id) {
                    return "Не проставился product_id. Обратитесь к администратору.";
                }

                if (!attrs.article) {
                    return "Не проставился article. Обратитесь к администратору.";
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
                'change .countInput': 'changeCountAndRender',
            },

            deleteModel: function(){
                itemsCollection.remove(this.model);
                currentItemsView.render();
            },

            changeCountAndRender:function (e) {
                var val = $(e.currentTarget);

                this.model.set(val.attr('id'), val.val());

                if(val.val() > this.model.get('countOnStock')){
                    val.parent().addClass('has-error');
                    alert('Ошибка! Вы выбираете ' + val.val() + ' шт. На складе ' + this.model.get('countOnStock') + ' шт.');
                }else{
                    val.parent().removeClass('has-error');
                    currentItemsView.render();
                }

            },

            tagName: 'tr',
            template: _.template($('#templateItems').html()),
            render: function() {

                if(!(this.model.attributes.countOnStock > 1)){
                    this.$el.addClass('redTr');
                }

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
                    this.$el.append('<tr><td colspan="5">Нет товаров</td></tr>');
                }

                itemsCollection.each(function(model) {

                    var itemLine = new itemView({
                        model: model
                    });

                    priceSum = priceSum + ((model.get('price')* model.get('count')));

                    this.$el.append(itemLine.render().el);
                }.bind(this));

                if(priceSum > 0)
                    sumPrice.val((priceSum-discount.val())).html((priceSum-discount.val()));
                else
                    sumPrice.val(0).html(0);
                return this;
            }

        });
        
        prepareItemView = Backbone.View.extend({

            events: {
                'click .chooseItemFromBasket': 'putItemToBasket',
            },

            putItemToBasket: function(){

                var createdModel = itemsCollection.where({ stock_id: this.model.get('stock_id')});

                modalChooseItem.modal('hide');

                if(createdModel.length == 0){
                    itemsCollection.add(this.model.attributes);
                    currentItemsView.render();
                }else{
                    alert('Данный товар уже в корзине');
                }
            },

            tagName: 'tr',
            template: _.template($('#templatePrepareItems').html()),
            render: function() {

                this.$el.html(this.template(this.model.attributes));
                return this;
            }

        });
        prepareItemsView = Backbone.View.extend({

            el: '#contentChooseReceiptId',

            initialize: function() {
                this.render();
            },

            render: function() {
                this.$el.html('');
                
                prepareItemsCollection.each(function(model) {

                    var itemLine = new prepareItemView({
                        model: model
                    });

                    this.$el.append(itemLine.render().el);
                }.bind(this));
                
                return this;
            }

        });

        constructorStart();
    };

    var constructorStart = function () {
        itemsCollection = new items();
        currentItemsView = new itemsView;
        prepareItemsCollection = new items();
        prepareChooseItemsView = new prepareItemsView;


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