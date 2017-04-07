define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var portfolioView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function() {

            },
            events: {
                
            },
            index: function(){
               $('.mix-grid').mixitup();
               $(document).ready(function(){
				
               });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
    return new portfolioView;
});