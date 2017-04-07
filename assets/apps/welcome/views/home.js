define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var homeView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function() {

            },
            events: {
                
            },
            index: function(){
                $(document).ready(function(){
                    
                });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
    return new homeView;
});