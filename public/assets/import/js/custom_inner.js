$(document).ready(function () {

    //recherche de villes correspondantes par ajax -- champ code postal / ville
    $('#estate_postal_code').keyup(function () {

        var query = $(this).val();
        var action = 'search_town';
        var nameKey = document.getElementById('csrf_name').value;
        var valueKey = document.getElementById('csrf_value').value;

        if(query != ''){
            $.ajax({
                url: '/action',
                method: 'POST',
                dataType: "json",
                data: {
                    query:query,
                    action: action,
                    csrf_name: nameKey,
                    csrf_value: valueKey
                },
                success:function(data){
                    $('#list_town').fadeIn();
                    $( ".town-list" ).remove();
                    var items = [];
                    var towns = JSON.stringify(data);
                    $.each($.parseJSON(towns), function(key,val){
                        items.push( "<li class='searchterm' id='" + val.id + "'>" + val.postal_code + " - " + val.district_name + "</li>" );
                    });
                    $( "<ul/>", {
                        "class": "town-list",
                        "id":"results",
                        html: items.join( "" )
                    }).appendTo( "#list_town");
                }
            })
        }
    })
    //choix de la ville dans menu deroulant
    $("#list_town").on("click","li.searchterm",function(){
        var result = $(this).text().split('-');
        $('#estate_postal_code').val(result[0]);
        town_name = $.trim(result[1])
        $('#estate_town').val(town_name);
        $('#estate_town_id').val($(this).attr('id'));
        $('#list_town').fadeOut();
    })
})