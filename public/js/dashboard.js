$(document).on('keyup','.locationSearchByName', function(){
    searchFilter();
});

$(document).on('change', '.nearestFilter, .availableFor, .loc_city, .openNowFilter', function(){
    searchFilter();
});

$(document).on('change','.nearestFilterModal', function(){
    $('.nearestFilter[value='+$(this).val()+']').prop('checked', true);
    searchFilter();
});

$(document).on('click','.applyFilter', function(){
    searchFilter();
});
function searchFilter() {
    
    var thisVal = $('.nearestFilter:checked').val();
    var title = $('.nearestFilter:checked').data('title');

    $('.nearestFilterModal[value='+thisVal+']').prop('checked', true);

    var selected_category = $('#selected_id').attr('data-cat-id');  

    var availableFor = $('.availableFor:checked').val();
    var availableForTitle = $('.availableFor:checked').data('title');

    var loc_city = $('.loc_city').val();
    var loc_country = $('.loc_country').val();
    var openNowFilter = ($('.openNowFilter').is(':checked')) ? 1 : 0;

    var minRange = $('.minRange').val();
    var maxRange = $('.maxRange').val();

    var url = $('#availableForUrl').val();
    $('.area_filter').html(title);
    $('#gender-filter').html(availableForTitle);
    $.ajaxSetup({
       headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
    });
    $.ajax({
        type:'post',
        url:url,
        data:{
            available:availableFor,
            service_category_id:selected_category,
            nearest_filter:thisVal,
            loc_city: loc_city,
            loc_country: loc_country,
            openNowFilter: openNowFilter,
            minRange: minRange,
            maxRange: maxRange
        },
        success:function(resp){
            if(resp.status == true){
                $('#filteredData').html(resp.html);
                $('#totalLocation').html(resp.total_records);
                sliderFun();
            }
            else{
                $('#filteredData').html("");
                $('#totalLocation').html(resp.total_records);
            }
        },
        complete: function(resp) {
            $('#searchResult').text( $('#searchLocation').val() );
        }
    });
}