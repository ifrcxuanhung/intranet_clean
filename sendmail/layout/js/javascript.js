$(document).ready(function(){
    if(typeof oTable != "undefined"){
        $(".datatable").dataTable().fnDestroy();
    }
    oTable = $(".datatable").dataTable({
        "iDisplayLength": 10,
        "iDisplayStart": 0,
        "aaSorting": [],
        "sPaginationType": "full_numbers",
        "bRetrieve": true,
        sDom: '<"block-controls"f><"pagination"p>'
    });
    
    $(document).tooltip();

    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body");
    /* .fadeIn(200); */
    }
    /* helper for returning the weekends in a period */
    function weekendAreas(axes) {
        var markings = [];
        var d = new Date(axes.xaxis.min);
        /* go to the first Saturday */
        d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
        d.setUTCSeconds(0);
        d.setUTCMinutes(0);
        d.setUTCHours(0);
        var i = d.getTime();
        do {
            /* when we don't set yaxis the rectangle automatically */
            /* extends to infinity upwards and downwards */
            markings.push({
                xaxis: {
                    from: i,
                    to: i + 2 * 24 * 60 * 60 * 1000
                }
            });
            i += 7 * 24 * 60 * 60 * 1000;
        } while (i < axes.xaxis.max);

        return markings;
    }

    var previousPoint = false, itemdata = false;

    /* sort table members list */
    $('.sort').live("click",function(e){
        e.preventDefault();
        var anchor = $(this);
        if (anchor.data("disabled")) {
            return false;
        }
        anchor.data("disabled", "disabled");
        var sort;
        switch ($(this).text()) {
            case 'Last Name':
                sort = 'last_name';
                break;
            case "Email":
                sort = 'email';
                break;
            case "City":
                sort = 'city';
                break;
            default:
                sort = '';
                break;
        }
        $('.formsortdata').val(sort);
        var page = $("#page_number").html();
        var search = $("#letter").html();
        $("#loading").show();
        $.ajax({
            url: '',
            type: 'post',
            data: 'sort=' + sort + '&ps=' + page + '&change=true' + '&search_fields[start-letter]=' + search,
            loading: '',
            success: function(response){
                $("#loading").hide();
                var $html = {};
                $html = $(response).find("#search_form").html();
                $('#search_form').html($html);
                anchor.removeData("disabled");
            }
        });
    });
/* end sort table members list */
});