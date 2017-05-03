var categories = [];
var statut = "'En stock', 'Approvisionnement en cours', 'En rupture de stock chez le fournisseur'"; //Default, all statuts
var search ='';

//Add Categories in categories array
$('.filter input').each(function() {
    categories.push($(this).val());
});

//Push categories check in the categories array
$(".filter input").change(function () {
    if (this.checked) {
        categories.push($(this).val());
    }
    else {
        categories.splice($.inArray($(this).val(), categories),1);
    }
    filter(search,statut,categories)
});

//When user write in search box, add the letter in search array
$(".search-input").keyup(function () {
    search = $(this).val();
    filter(search,statut,categories)
});

//Add statut on statut string
$(".statut input[name=statut]").change(function () {
       statut = $(this).val();
    filter(search,statut,categories);
});

//Ajax function for filter and livesearch
function filter(search,statut,categories) {

    if (!search) {
        search = ' '; //Space, because the bdd query doesn't work if no space
    }

    if (categories.length === 0) {
        $('.alert').remove();
        $('#first-panel').before("<div class='alert alert-danger'><a href='/' class='close' data-dismiss='alert'>&times;</a>Veuillez cocher au moins une catégorie</div>");

    } else {
        $('.alert').remove();
        $.ajax({
            type: "GET",
            url: "search?search=" + search + "&statut=" + statut + "&category=" + categories,
            dataType: "json",
            success: function (data) {
                if (data.cd.length == 0) { //If no results
                    $('.alert').remove();
                    $('tbody').empty();
                    $('#first-panel').before("<div class='alert alert-danger'><a href='/' class='close' data-dismiss='alert'>&times;</a>Aucun CD de trouvé</div>");
                } else {
                    $('tbody').empty();
                    $.each(data.cd, function (i, items) {
                        $('tbody').append(
                            '<tr>'+
                                '<td><img class="img-thumbnail img-size-2" src="' + items.cover + '" alt="' + items.title + '"></td>' +
                                '<td class="text-center">' + items.title +'</td>' +
                                '<td class="text-center">' + items.artist +'</td>' +
                                '<td class="text-center">' + items.categorie + '</td>' +
                                '<td class="text-center">' + items.quantity + '</td>' +
                                '<td class="text-center">' + items.price + '</td>' +
                                '<td class="text-center">' + items.statut + '</td>' +
                                '<td class="text-center"><div class="btn-group">'+
                                '<a href="editer-un-produit?id=' + items.id + '" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">' +
                                '<span class="glyphicon glyphicon-edit"></span></a>' +
                                '<a href="supprimer-un-produit?id=' + items.id + '" class="btn btn-info btn-xs"  title="Delete" data-toggle="tooltip">' +
                                '<span class="glyphicon glyphicon-trash"></span></a>' +
                                '</div></td></tr><tr>'
                        );
                    });
                }
            },
            error: function () {
                $('.alert').remove();
                $('#first-panel').before("<div class='alert alert-danger'><a href='/' class='close' data-dismiss='alert'>&times;</a>Erreur, veuillez réessayer</div>");
            }
        });
    }
}

//Function to preview cover before upload
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.cover-preview img').css('opacity', '1');
            $('.cover-preview img').removeAttr('src');
            $('.cover-preview img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".cover-upload").change(function () {

    readURL(this);

});

if ($('.cover-preview img').attr('src') == '' || $('.cover-preview img').attr('src') == 'has-error') {
    $('.cover-preview img').css('opacity', '0');
} else {
    $('.cover-preview img').css('opacity', '1');
}







