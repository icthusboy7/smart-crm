$(document).ready(function() {
    $('#offices').select2({
        ajax: {
            delay: 1,
            datatype: 'json',
            url: '/selectOffices',
            data: function (params) {
                var queryParameters = {
                    q: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                return {results: data};
            },
            cache: true
        },
        minimumInputLength: 3,
        placeholder: 'Search for an Office'
    });
});
$(".edit_contact_button").on('click', function(event){
    var value = $(this).val();
    var contact_nif = $(this).parent('td').parent('tr').children('td').eq(0).text();
    var contact_nombre = $(this).parent('td').parent('tr').children('td').eq(1).text();
    var contact_telefono = $(this).parent('td').parent('tr').children('td').eq(2).text();
    var contact_email = $(this).parent('td').parent('tr').children('td').eq(3).text();
    var contact_direccion = $(this).parent('td').parent('tr').children('td').eq(4).text();
    var contact_observaciones = $(this).parent('td').parent('tr').children('td').eq(5).text();
    $('#contact_editing').modal('show');
    $('#contact_id').val(value);
    $('#contact_nif').val(contact_nif);
    $('#contact_nombre').val(contact_nombre);
    $('#contact_telefono').val(contact_telefono);
    $('#contact_email').val(contact_email);
    $('#contact_direccion').val(contact_direccion);
    $('#contact_observaciones').val(contact_observaciones);
});

$('#save_contact').click(function() {
    var formData = $('#set_contact').serialize();
    $.ajax({
        type: 'POST',
        url: '/updateContactos',
        data: formData,
        success: function(){
            $('#dev-table').load(document.URL +  ' #dev-table');
            swal( "Nice" ,  "Contact updated!" ,  "success" );
        }
    });

    $('#contact_editing').modal('toggle');
});

$('#create_contact').click(function() {
    var formData = $('#new_contact').serialize();
    $.ajax({
        type: 'POST',
        url: '/createContactos',
        data: formData,
        success: function(){
            swal( "Nice" ,  "Contact created!" ,  "success" );
        }
    });

    $('#add_new_contact').modal('toggle');
});

$('.delete_contact_button').click(function () {
    var formData = 'id='+$(this).val();
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to see this contact!",
        icon: "warning",
        buttons: true,
        dangerMode: true
    }).then((willDelete)=> {
            if (willDelete) {
                $.ajax({
                    type: 'GET',
                    url: '/deleteContactos',
                    data: formData,
                    success: function(){
                        $('#dev-table').load(document.URL +  ' #dev-table');
                        swal("The contact has been deleted!", {
                            icon: "success"
                        });
                    }
                });
            } else {
                swal("The contact is safe!");
            }
    })
});

$('.delete_query_button').click(function () {
    var formData = 'id='+$(this).val();
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to see this filter!",
        icon: "warning",
        buttons: true,
        dangerMode: true
    }).then((willDelete)=> {
        if (willDelete) {
            $(this).parent('span').remove();
            $.ajax({
                type: 'GET',
                url: '/deleteQueryContactos',
                data: formData,
                success: function(){
                    swal("The custom filter has been deleted!", {
                        icon: "success"
                    });
                }
            });

        } else {
            swal("The contact is safe!");
        }
    })
});

$('#save_query_btn').click(function() {
    const urlParams = new URLSearchParams(window.location.search);
    var formData = 'query_name='+$('#query_name').val()+'&'+urlParams;
    $.ajax({
        type: 'POST',
        url: '/createQuery',
        data: formData,
        success: function(){
            swal( "Nice" ,  "Query created!" ,  "success" );
        }
    });
});
