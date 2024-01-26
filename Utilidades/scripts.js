$(document).ready(function () {
    var array_opciones_encuesta = [];
    var indice_opciones = 0;
    
    // Crear Titulo Encuesta desde el script
    var container_poll = $('<div>').addClass('poll-container');
    var box_poll = $('<div>').attr('id', 'box');
    var titulo_encuesta = $('<h2>').text('Titulo encuesta:');
    var inputElement = $('<input>').attr({
        type: 'text',
        name: 'titulo',
        placeholder: 'TITULO'
    });

    inputElement.on('input', function () {
        $(this).closest('#box').nextAll('#box').remove();
        /* $(this).css('background-color', ''); */
    });
    var buttonElement = $('<button>').attr('id', 'validate').text('Validar');
    box_poll.append(titulo_encuesta, inputElement, buttonElement);
    container_poll.append(box_poll);
    $('.create_poll').append(container_poll);
$('#validate').click(function(){
    if (box_poll.next('#box').length === 0) {
    validatePoll($(this).prev("input[name]").attr("name"));  }
});}
);

function createBoxOption(){
    var optionDiv = $('<div id="box">').append(
        $('<h2>').text('Opción encuesta:'),
        $('<input>').attr({ type: 'text', name: 'opcion1', placeholder: 'Opción 1'}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            $(this).css('background-color', '');
        }),
        $('<h2>').text('Opción encuesta:'),
        $('<input>').attr({ type: 'text', name: 'opcion2', placeholder: 'Opción 2'}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            $(this).css('background-color', '');
        }),
        $('<button>').attr({ id: 'validate' }).text('Añadir').click(function(){
            if (optionDiv.next('#box').length === 0) {
                validatePoll($(this).prev("input[name]").attr("name"));  }  
        }),
        $('<button>').attr({ id: 'enviar' }).text('Enviar').click(function(){
            if (optionDiv.next('#box').length === 0) {
                validatePoll($(this).prev("input[name]").attr("name"));  } 
        })
    );

    $('.poll-container').append(optionDiv);
    
}

function validatePoll(inputType){
    console.log(inputType);
    switch(inputType) {
        case "titulo":
            var nameTitulo = $('input[name=titulo]').val();
            if(nameTitulo.trim()===""){
                showNotification("El titulo no puede estar vacío", 'red');
            }
            else{
                /* $('input[name=titulo]').css('background-color', '#b4e7b3'); */
                localStorage.setItem('nameTitulo',nameTitulo);
                createBoxOption();
            }
            break;

        case "opcion2":
            var nameOpcion1 = $('input[name=opcion1]').val();
            var nameOpcion2 = $('input[name=opcion2]').val();
            if(nameOpcion1.trim()===""){
                showNotification("La opción 1 no puede estar vacía", 'red');
            }
            else if(nameOpcion2.trim()===""){
                showNotification("La opción 2 no puede estar vacía", 'red');
            }
            else {
                /* $('input[name=titulo]').css('background-color', '#b4e7b3'); */
                localStorage.setItem('nameOpcion1',nameOpcion1);
                localStorage.setItem('nameOpcion2',nameOpcion2);
                createBoxOptions();
            }

            /* if($('input[name=opcion1]').val()===$('input[name=opcion2]').val()){
                var pwd = $('input[name=opcion1]').val();
                if(pwd.trim().length>=8){
                    $('input[name=opcion1]').css('background-color', '#b4e7b3');
                    $('input[name=opcion2]').css('background-color', '#b4e7b3');
                    createBoxSendData(pwd);
                    
                }

                else{
                    showNotification("La contraseña debe contener como minimo 8 caracteres");
                }
            }else{
                showNotification("Las contraseñas no coinciden");
            } */
            break;
        }
    }




























    var label = $("<label>").attr("for", "opciones_encuesta").text("Opciones Encuesta:");
    $("form").append(label);

    $("#add-option").click(function () {
        var option_to_add = $("#opciones_encuesta").val();
        if (option_to_add !== "") {
            array_opciones_encuesta.push(option_to_add);
            sessionStorage.setItem("opciones", JSON.stringify(array_opciones_encuesta));
            $("form").append('<input style="display: none;" type="text" name="opcion_encuesta' + array_opciones_encuesta.length + '" id="opcion_encuesta' + array_opciones_encuesta.length + '">');
            $('#opcion_encuesta' + array_opciones_encuesta.length).val(array_opciones_encuesta[indice_opciones]);
            indice_opciones++;
            showNotification('Opción añadida correctamente ')
        } else {
            showNotification('No se puede dejar en blanco ', 'orange')
        }
        $("#opciones_encuesta").val("");
        if (array_opciones_encuesta.length >= 2 && $("#send-poll").length === 0) {
            $("form").append('<button type="submit" id="send-poll" class="button button-login">Enviar encuesta</button>');
        }
    });

    $("#send-poll").click(function () {
        array_opciones_encuesta = [];
        sessionStorage.removeItem("opciones");
    });

    function showNotification(message, bgColor) {
        var notificationContainer = $("#notification-container");

        var notificationDiv = $("<div>").addClass("notification");
        notificationDiv.text(message);

        if (bgColor) {
            notificationDiv.css("background-color", bgColor);
        }

        var closeButton = $("<button>").addClass("close-button");
        closeButton.html("&times;");
        closeButton.click(function () {
            notificationDiv.remove();
        });

        notificationDiv.append(closeButton);
        notificationContainer.append(notificationDiv);
    }
