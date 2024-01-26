$(document).ready(function () {
    var array_opciones_encuesta = [];
    var indice_opciones = 0;
    var maxOptions = 100;
    var optionNumber = 1;

    // Crear Fecha Inicio Encuesta
    var container_poll = $('<div>').addClass('poll-container');
    var box_poll = $('<div>').attr('id', 'box');
    var fecha_inicio = $('<h2>').text('Fecha inicio:');
    var inputElement = $('<input>').attr({
        type: 'date',
        name: 'fecha_inicio',
        id: 'fecha_inicio'
    });
    inputElement.on('input', function () {
        $(this).closest('#box').nextAll('#box').remove();
    });
    var buttonElement = $('<button>').attr('id', 'validate').text('Validar');
    box_poll.append(fecha_inicio, inputElement, buttonElement);
    container_poll.append(box_poll);
    $('.create_poll').append(container_poll);
$('#validate').click(function(){
    if (box_poll.next('#box').length === 0) {
    validatePoll($(this).prev("input[name]").attr("name"));  }
});

// Crear Fecha Final Encuesta
function createBoxFinal(){
    var inputElement = $('<div id="box">').append(
        $('<h2>').text('Fecha Final:'),
        $('<input>').attr({ type: 'date', name: 'fecha_final', id: 'fecha_final'}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            $(this).css('background-color', '');
        }),
        $('<button>').attr({ id: 'validate' }).text('Validar').click(function(){
            if (inputElement.next('#box').length === 0) {
                validatePoll($(this).prev("input[name]").attr("name"));  }  
        })
    );

    $('.poll-container').append(inputElement);
}

// Crear Titulo Encuesta
function createBoxTitle(){
    var inputElement = $('<div id="box">').append(
        $('<h2>').text('Titulo encuesta:'),
        $('<input>').attr({ type: 'text', name: 'titulo', id:'titulo', placeholder: 'TITULO'}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            $(this).css('background-color', '');
        }),
        $('<button>').attr({ id: 'validate' }).text('Validar').click(function(){
            if (inputElement.next('#box').length === 0) {
                validatePoll($(this).prev("input[name]").attr("name"));  }  
        })
    );

    $('.poll-container').append(inputElement);
}
// Crear Opción 1 Encuesta
/* function createBoxOption1(){
    var optionDiv = $('<div id="box">').append(
        $('<h2>').text('Opción encuesta 1:'),
        $('<input>').attr({ type: 'text', name: 'opcion1', id: 'opcion', placeholder: 'Opción 1'}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            $(this).css('background-color', '');
        }),
        $('<button>').attr({ id: 'validate' }).text('Añadir').click(function(){
            if (optionDiv.next('#box').length === 0) {
                validatePoll($(this).prev("input[name]").attr("name"));  }  
        })
    );

    $('.poll-container').append(optionDiv);
    
}

function createBoxOption2(){
    var optionDiv = $('<div id="box">').append(
        $('<h2>').text('Opción encuesta 2:'),
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
    
} */

// Función para crear una nueva opción
function createBoxOption(optionNumber) {
    var optionDiv = $('<div id="box">').append(
        $('<h2>').text('Opción encuesta ' + optionNumber + ':'),
        $('<input>').attr({ type: 'text', name: 'opcion' + optionNumber, placeholder: 'Opción ' + optionNumber}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            console.log(optionNumber);
            $(this).css('background-color', '');
        }),
        $('<button>').attr({ id: 'validate', class: 'validateOption', 'data-option': optionNumber }).text('Añadir').click(function(){
            if (optionDiv.next('#box').length === 0) {
                validatePoll($(this).prev("input[name]").attr("name"));
            }
            /* var optionNumber = $(this).data('option');
            if ($('input[name=opcion' + optionNumber + ']').val().trim() === "") {
                showNotification("La opción " + optionNumber + " no puede estar vacía", 'red');
            } else {
                localStorage.setItem('nameOpcion' + optionNumber, $('input[name=opcion' + optionNumber + ']').val());
                if (optionNumber < maxOptions) {
                    createBoxOption(optionNumber + 1);
                }
            } */
        }),
    );
    $('.poll-container').append(optionDiv);
}

// Al hacer clic en "Añadir", se crea una nueva opción
/* $('#validate').click(function() {
    if (indice_opciones < maxOptions) {
        indice_opciones++;
        createBoxOption(indice_opciones);
    } else {
        showNotification("Se ha alcanzado el máximo de opciones permitidas.", 'red');
    }
}); */

function validatePoll(inputType){
    console.log(inputType);
    switch(inputType) {
        case "fecha_inicio":
            var nameInicio = $('input[name=fecha_inicio]').val();
            if (nameInicio.trim()===""){
                showNotification("La fecha inicial no puede estar vacía", 'red');
            }
            else{
                localStorage.setItem('nameInicio',nameInicio);
                createBoxFinal();
            }
            break;
        case "fecha_final":
            var nameFinal = $('input[name=fecha_final]').val();
            if (nameFinal.trim()===""){
                showNotification("La fecha final no puede estar vacía", 'red');
            }
            else{
                localStorage.setItem('nameFinal',nameFinal);
                createBoxTitle();
            }
            break;
        case "titulo":
            var nameTitulo = $('input[name=titulo]').val();
            if(nameTitulo.trim()===""){
                showNotification("El titulo no puede estar vacío", 'red');
            }
            else{
                /* $('input[name=titulo]').css('background-color', '#b4e7b3'); */
                localStorage.setItem('nameTitulo',nameTitulo);
                createBoxOption(optionNumber);
            }
            break;

        case "opcion" + optionNumber:
            var nameOpcion = $('input[name=opcion' + optionNumber + ']').val();
            if(nameOpcion.trim()===""){
                showNotification("La opción" + optionNumber + "no puede estar vacía", 'red');
            }
            else {
                /* $('input[name=titulo]').css('background-color', '#b4e7b3'); */
                localStorage.setItem('nameOpcion' + optionNumber +'',nameOpcion);
                console.log(optionNumber);
                optionNumber++;
                createBoxOption(optionNumber);
            }
            break;

/*         case "opcion1":
            var nameOpcion1 = $('input[name=opcion1]').val();
            if(nameOpcion1.trim()===""){
                showNotification("La opción 1 no puede estar vacía", 'red');
            }
            else {
                localStorage.setItem('nameOpcion1',nameOpcion1);
                createBoxOption2();
            }
            break;

        case "opcion2":
            var nameOpcion2 = $('input[name=opcion2]').val();
            if(nameOpcion2.trim()===""){
                showNotification("La opción 2 no puede estar vacía", 'red');
            }
            else {
                /* $('input[name=titulo]').css('background-color', '#b4e7b3');
                localStorage.setItem('nameOpcion2',nameOpcion2);
                createBoxOptions();
            }
            break; */
        }
    }
}
);


























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
