var ContactUs = function () {

    return {
        //main function to initiate the module
        init: function () {
			var map;
			$(document).ready(function(){
			  map = new GMaps({
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
			});
        }
    };

}();