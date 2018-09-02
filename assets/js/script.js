$(document).ready(function () {
    // Récuperation de l id de la page 
    page = document.location.href;

    $("#joinParty").submit(function (event) {

        $.ajax({

            url: page, // La ressource ciblée

            type: 'POST', // Le type de la requête HTTP. 
            
            dataType: 'json',            

            data:'data',

            success: function (data) {

                $(".user").remove().hide().fadeOut(1000);

                console.log(data);

                var data_length = data.length;
                for (var i = 0; i < data_length; i++) {
                    console.log(data[i]["id"] + " " + data[i]["username"]);
                    id = data[i]["id"];
                    username = data[i]["username"];
                    $(".users").css({ 'display': "block" });
                    $(`<li> <img class="avatar_partie" src="../../../../uploads/img/${id}.png"  class="mx-auto d-block"> ${username} </li>`)
                    .appendTo(".users")
                    .hide()
                    .fadeIn(2000)
                }



            },

            error: function () {

                console.log('la requete a echouée');
            }

        });

        // console.log(data);

        event.preventDefault();
    });


    $("#form_quit").submit(function (event) {
        $.ajax({

            url: "{{ path('partie_show') }}",  // La ressource ciblée

            type: 'POST', // Le type de la requête HTTP.

            data: 'utilisateurs'

        });

        console.log(data);

        event.preventDefault();
    });



}); 