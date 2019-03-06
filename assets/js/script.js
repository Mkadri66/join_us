$(document).ready(function () {
    // Personnalisation du bouton
    var onParty = $('#isOnParty').val();

    console.log(onParty);

    if(onParty == true){
        $('#form_submit').removeClass('btn-primary').addClass('btn-danger').html('Quitter la partie');
     } else {
        $('#form_submit').removeClass('btn-danger').addClass('btn-primary').html('Rejoindre la partie');
     }


    // Récuperation de l id de la page 
    page = document.location.href

    $("#joinParty").submit(function (event) {

        $.ajax({

            url: page, // La ressource ciblée
            type: 'POST', // Le type de la requête HTTP.   
            dataType: 'json',            
            data:'data',

            success: function (data) {

                // on vide la liste des joueurs
                $(".users").empty();

                //Si il est dans la partie on desactive le bouton rejoindre la partie
                if(data.isOnParty == true){
                   $('#form_submit').removeClass('btn-primary').addClass('btn-danger').html('Quitter la partie');
                } else {
                   $('#form_submit').removeClass('btn-danger').addClass('btn-primary').html('Rejoindre la partie');
                }

                // On affiche les joueurs recupérés en ajax
                var count = Object.keys(data).length
                for (let i = 0; i < count; i++) {     
                    username = data[i]["username"]
                    url = data[i]["url"]
                    $(`<li> <img class="avatar_party" src="../../../../uploads/avatars/${url}" class="mx-auto d-block"> ${username} </li>`)
                    .appendTo(".users")
                    .hide()
                    .fadeIn(1000)
                }
            },

            error: function () {

                console.log('la requete a echouée')
            }

        });

        event.preventDefault()
    });
}); 