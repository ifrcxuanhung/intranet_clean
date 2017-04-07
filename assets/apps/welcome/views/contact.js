define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone) {
        var contactView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function() {

            },
            events: {
                
            },
            index: function() {
                function contactMap() {
                        var map = new GMaps({
        				div: '#map',
        				// kinh do , vi do bx thay vo cho nay 
        				lat: 10.731822,
        				lng: 106.719048,
        				scrollwheel: false
                        });
        			   var marker = map.addMarker({
        		            lat: 10.731822,
        					lng: 106.719048,
        		            title: 'IFRC Vietnam, Inc.',
        		            infoWindow: {
        		                content: "<b>IFRC Vietnam, Inc.</b> Thien Son Plaza, Office 3.12,<br>800 Nguyen Van Linh St., Phu My Hung, Dist 7,<br> HCMC, VIET NAM "
        		            }
        		        });
        
        			   marker.infoWindow.open(map, marker);
                        };
              contactMap();
              },
              render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return new contactView;
});