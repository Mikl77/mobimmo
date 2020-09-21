$(document).ready(function () {

    // Gestion envoi demande de renseignements page single property

    $("#messageForm").validate({
        rules:{
            first_name: "required",
            last_name: "required",
            email_address:"required",
            message:"required"
        },
        messages:{
            first_name: "Merci de renseigner votre prénom",
            last_name: "Merci de renseigner votre nom",
            email_address:"Merci de renseigner votre e-mail",
            message:"Merci de renseigner un message"
        },



        submitHandler: function(form) {
                var action = 'info_message';
                var nameKey = document.getElementById('csrf_name').value;
                var valueKey = document.getElementById('csrf_value').value;
                var fname = document.getElementById('fname').value;
                var lname = document.getElementById('lname').value;
                var pnumber = document.getElementById('pnumber').value;
                var emailid = document.getElementById('emailid').value;
                var message = document.getElementById('message').value;
                var reference = document.getElementById('reference').value;
                var managerId = document.getElementById('managerId').value;

                $.ajax({
                    url: '/action',
                    method: 'POST',
                    data: {
                        action: action,
                        csrf_name: nameKey,
                        csrf_value: valueKey,
                        fname: fname,
                        lname:lname,
                        pnumber: pnumber,
                        emailid: emailid,
                        message: message,
                        reference: reference,
                        managerId: managerId
                    },
                    success: function (data) {
                        var html ='';
                        html += '<div class="alert alert-success" role="alert">';
                        html += 'Message envoyé';
                        html += '</div>';
                        $('#confirmation').append(html);
                        $("#messageForm").hide();
                    }
                })
        }
    });

    $('form.ajax').on('submit', function(e){
        e.preventDefault();
       var that = $(this),
           formdata= {},
            action = 'estate_search',
            nameKey = document.getElementById('csrf_name').value,
            valueKey = document.getElementById('csrf_value').value,
            baseurl = window.location.origin;


        that.find('[name]').each(function(index,value){
                var that = $(this),
                    name = that.attr('name'),
                    val = that.val();

                formdata[name] = val;
            });

        $.ajax({
            url: '/action',
            method: 'POST',
            data: {
                action: action,
                csrf_name: nameKey,
                csrf_value: valueKey,
                baseurl: baseurl,
                formdata: formdata
            },
            success: function (data) {
                top.location.href = data;
                console.log(data);
            }
        })
    })

    // ordering the filtered results
    $('#order_choice').change(function(){

        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);

        if(params.has('estateOrder')) {
            params.delete('estateOrder');
        }

        params.append('estateOrder', $(this).val())

        window.location.href = location.pathname +"?"+ params.toString();

    })

    //recherche de villes correspondantes par ajax -- champ code postal / ville
    $('#search_estate_town').keyup(function () {

        var query = $(this).val();
        var action = 'search_town';
        var nameKey = document.getElementById('csrf_name').value;
        var valueKey = document.getElementById('csrf_value').value;

        $('#estate_town_id').removeAttr('value');

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
                    $('#townList').fadeIn();
                    $( ".town-list" ).remove();
                    var items = [];
                    var towns = JSON.stringify(data);
                    $.each($.parseJSON(towns), function(key,val){
                        items.push( "<li class='searchterm' id='" + val.id + "'>" + val.postal_code + " - " +  val.district_name + "</li>" );
                    });
                    $( "<ul/>", {
                        "class": "town-list",
                        "id":"results",
                        html: items.join( "" )
                    }).appendTo( "#townList");
                }
            })
        }
    })
    //choix de la ville dans menu deroulant
    $("#townList").on("click","li.searchterm",function(){
        $('#search_estate_town').val($(this).text());
        $('#estate_town_id').val($(this).attr('id'));
        $('#townList').fadeOut();
    })
})