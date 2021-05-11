// Order widgets form
$('#save_order_widgets').click(function() {

    var formData = $('#set_order_widgets').serialize();
    $.ajax({
        type: 'POST',
        url: '/updateOrden',
        data: formData,
        success: function(){
            $('#widgets_container').load(document.URL +  ' #widgets_container');
            $('#sidebar_widgets').load(document.URL +  ' #sidebar_widgets');
            swal( "Nice" ,  "Order updated!" ,  "success" );
        }
    });

    $('#orden_widgets_modal').modal('toggle');
});

$('.input_flagseen').click(function() {
    var formData = $(this).children('input').serialize();
    $.ajax({
        type: 'POST',
        url: '/updateFlagNotify',
        data: formData,
        success: function () {
        }
    });
    var myClass = $(this).children('i').attr("class");
    var eyeClass = 'far fa-eye';
    if(myClass == eyeClass){
        $(this).children('i').removeClass();
        $(this).children('i').addClass('far fa-eye-slash');
    }else{
        $(this).children('i').removeClass();
        $(this).children('i').addClass('far fa-eye');
    }
});

function openNav() {
    document.getElementById("sidebar_menu").style.width = "250px";
}

/* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
function closeNav() {
    document.getElementById("sidebar_menu").style.width = "0";
}

function openSidebarRight() {
    document.getElementById("sidebar_right").style.width = "250px";
    $.ajax({
        url: '/seenNotificationsGroup',
        success: function() {
        }
    })
}

/* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
function closeSidebarRight() {
    document.getElementById("sidebar_right").style.width = "0";
}

function openSidebarRight2() {
    document.getElementById("sidebar_right2").style.width = "250px";
    $.ajax({
        url: '/seenNotificationsUser',
        success: function() {
        }
    })
}

/* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
function closeSidebarRight2() {
    document.getElementById("sidebar_right2").style.width = "0";
}

