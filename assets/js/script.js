$(document).ready(function () {
    // Récuperation de l'id de la page 
    page = document.location.href;

    $("#joinParty").submit(function (event) {

        $.ajax({

            url: page, // La ressource ciblée

            type: 'POST', // Le type de la requête HTTP. 
            
            dataType: 'json',            

            data:'partie',

            success: function (data) {
                
                console.log(data);
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