var optionNumber = 2;
$(document).ready(function () {
    var array_opciones_encuesta = [];
    var indice_opciones = 0;
    // Crear Fecha Inicio Encuesta
    var container_poll = $('<div>').addClass('poll-container');
    var box_poll = $('<div>').attr('id', 'box');
    var fecha_inicio = $('<label>').text('Fecha inicio:');
    var inputElement = $('<input>').attr({
        type: 'date',
        name: 'fecha_inicio',
        id: 'fecha_inicio'
    }).on('input', function () {
        $(this).closest('#box').nextAll('#box').remove();
    }).keypress(function(event) {
        if (event.which == 13) {
            validatePoll("fecha_inicio");
        }
    });
    var buttonElement = $('<button>').attr({id: 'validate', class: 'button-login'}).text('Validar').click(function() {
        validatePoll("fecha_inicio");
    });
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
        $('<label>').text('Fecha Final:'),
        $('<input>').attr({ type: 'date', name: 'fecha_final', id: 'fecha_final'}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
        }).keypress(function(event) {
            if (event.which == 13) {
                validatePoll("fecha_final");
            }
        }),
        $('<button>').attr({ id: 'validate', class: 'button-login'}).text('Validar').click(function(){
            if (inputElement.next('#box').length === 0) {
                validatePoll($(this).prev("input[name]").attr("name"));  }  
        })
    );

    $('.poll-container').append(inputElement);
}

// Crear Titulo Encuesta
function createBoxTitle(){
    var inputElement = $('<div id="box">').append(
        $('<label>').text('Titulo encuesta:'),
        $('<input>').attr({ type: 'text', name: 'titulo', id:'titulo', placeholder: 'TITULO'}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
        }).keypress(function(event) {
            if (event.which == 13) {
                validatePoll("titulo");
            }
        }),
        $('<input>').attr({ type: 'file', name: 'imgTitulo', accept:"image/*"}),
        $('<button>').attr({ id: 'validate', class: 'button-login'}).text('Validar').click(function(){
            if (inputElement.next('#box').length === 0) {
                validatePoll($(this).prev("input[name]").attr("name"));  }  
        })
    );

    $('.poll-container').append(inputElement);
}
// Crear Opción 1 Encuesta
function createBoxOption1(){
    var optionDiv = $('<div id="box">').append(
        $('<label>').text('Opción encuesta 1:'),
        $('<input>').attr({ type: 'text', name: 'opcion1', id: 'opcion', placeholder: 'Opción 1'}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            // Habilitar el botón de "Añadir opción" solo en la opción actual
            $('.add-option').prop('disabled', false);
            $('.add-option').not('[data-option="1"]').prop('disabled', true);
        }).keypress(function(event) {
            if (event.which == 13) {
                validatePoll("opcion1");
            }
        }),
        $('<input>').attr({ type: 'file', name: 'imgOpcion1', accept:"image/*"}),
        $('<button>').attr({ id: 'validate', class: 'add-option button-login', 'data-option': '1' }).text('Añadir opción').click(function(){
            if (optionDiv.next('#box').length === 0) {
                validatePoll("opcion1");  }  
                scrollToBottom();
            })
    );

    $('.poll-container').append(optionDiv);
    
}


// Función para crear una nueva opción
function createBoxOptions(optionNumber) {
    var optionDiv = $('<div id="box">').append(
        $('<label>').text('Opción encuesta ' + optionNumber + ':'),
        $('<input>').attr({ type: 'text', name: 'opcion' + optionNumber, placeholder: 'Opción ' + optionNumber}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            // Habilitar el botón de "Añadir opción" solo en la opción actual
            $('.add-option').prop('disabled', false);
            $('.add-option').not('[data-option="' + optionNumber + '"]').prop('disabled', true);
        }).keypress(function(event) {
            if (event.which == 13) {
                validatePoll("opcion" + optionNumber);
                scrollToBottom();
            }
        }),
        $('<input>').attr({ type: 'file', name: 'imgOpcion' + optionNumber, accept:"image/*"}),
        $('<button>').attr({ class: 'add-option button-login', 'data-option': optionNumber }).text('Añadir opción').prop('disabled', true).click(function(){
            var currentOptionNumber = $(this).data('option');
            if ($('input[name=opcion' + currentOptionNumber + ']').val().trim() === "") {
                showNotification("La opción " + currentOptionNumber + " no puede estar vacía", 'red');
            } else {
                localStorage.setItem('nameOpcion' + currentOptionNumber, $('input[name=opcion' + currentOptionNumber + ']').val());
                createBoxOptions(optionNumber + 1);
                scrollToBottom();
            }
        }),
        $('<button>').attr({ id: 'send-poll', class: 'button-login', 'data-option': optionNumber }).text('Enviar encuesta').click(function(){
            var currentOptionNumber = $(this).data('option');
            if ($('input[name=opcion' + currentOptionNumber + ']').val().trim() === "") {
                showNotification("La opción " + currentOptionNumber + " no puede estar vacía", 'red');
            } else {
                localStorage.setItem('nameOpcion' + currentOptionNumber, $('input[name=opcion' + currentOptionNumber + ']').val());
                createBoxBD();
                scrollToBottom();
            }
        })
    );
    $('.poll-container').append(optionDiv);

    // Habilitar el botón de "Añadir opción" solo en la opción actual
    $('.add-option').prop('disabled', false);
    $('.add-option').not('[data-option="' + optionNumber + '"]').prop('disabled', true);
}

function validatePoll(inputType){
    console.log(inputType);
    switch(inputType) {
        case "fecha_inicio":
            var nameInicio = $('input[name=fecha_inicio]').val();
            var dateHoy = new Date();
            var dateInicio = new Date(nameInicio);
            if (nameInicio.trim()===""){
                showNotification("La fecha inicial no puede estar vacía", 'red');
            }
            else if(dateHoy>dateInicio){
                showNotification("La fecha inicial tiene que ser posterior al dia de hoy", "red");
            }  
            else{
                localStorage.setItem('nameInicio',nameInicio);
                createBoxFinal();
            }
            break;

        case "fecha_final":
            var nameFinal = $('input[name=fecha_final]').val();
            var nameInicio = $('input[name=fecha_inicio]').val();
            var dateInicio = new Date(nameInicio);
            var dateFinal = new Date(nameFinal);
            if (nameFinal.trim()===""){
                showNotification("La fecha final no puede estar vacía", 'red');
            }
            else if(dateFinal<dateInicio){
                showNotification("La fecha final no puede ser inferior a la fecha inicial", "red");
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
                localStorage.setItem('nameTitulo',nameTitulo);
                createBoxOption1();
            }
            break;

        case "imgTitulo":
            var nameTitulo = $('input[name=titulo]').val();
            var imgTitulo = $('input[name=imgTitulo]').val();
            if(nameTitulo.trim()===""){
                showNotification("El titulo no puede estar vacío", 'red');
            }
            else{
                localStorage.setItem('nameTitulo',nameTitulo);
                localStorage.setItem('imgTitulo',imgTitulo);
                createBoxOption1();
            }
            break;

        case "opcion" + optionNumber:
            var nameOpcion = $('input[name=opcion' + optionNumber + ']').val();
            if(nameOpcion.trim()===""){
                showNotification("La opción" + optionNumber + "no puede estar vacía", 'red');
            }
            else {
                localStorage.setItem('nameOpcion' + optionNumber +'',nameOpcion);
                console.log(optionNumber);
                optionNumber++;
                createBoxOptions(optionNumber);
            }
            break;

         case "opcion1":
            var nameOpcion1 = $('input[name=opcion1]').val();
            if(nameOpcion1.trim()===""){
                showNotification("La opción 1 no puede estar vacía", 'red');
            }
            else {
                localStorage.setItem('nameOpcion1',nameOpcion1);
                createBoxOptions(2);
            }
            break;

        /* case "opcion2":
            var nameOpcion2 = $('input[name=opcion2]').val();
            if(nameOpcion2.trim()===""){
                showNotification("La opción 2 no puede estar vacía", 'red');
            }
            else {
                localStorage.setItem('nameOpcion2',nameOpcion2);
                createBoxOptions(3);
            }
            break; */

        case "send":
            var nameOpcion = $('input[name=opcion' + optionNumber + ']').val();
            if(nameOpcion.trim()===""){
                showNotification("La opción" + optionNumber + "no puede estar vacía", 'red');
            }
            else {
                localStorage.setItem('nameOpcion' + optionNumber +'',nameOpcion);
                // Guardar en la BD todo y guardar las imagenes en "uploads"
                createBoxBD();
            }
            break;
        }
    }
}
);

function createBoxBD() {
    var titulo = localStorage.getItem('nameTitulo');
    var optionsS = localStorage.getItem('opcion');

    var options = JSON.parse(optionsS);

    var start = localStorage.getItem('start');
    var end = localStorage.getItem('end');
    var form = $('<form>').attr({
        action: 'create_poll.php',
        method: 'POST'
    });

    var hiddenFields = [
        { name: 'titulo', value: titulo },
        { name: 'start', value: start },
        { name: 'end', value: end }
    ];


    $.each(hiddenFields, function(index, field) {
        $('<input>').attr({
            type: 'hidden',
            name: field.name,
            value: field.value
        }).appendTo(form);
    });


    $.each(options, function(index, option) {
        $('<input>').attr({
            type: 'hidden',
            name: 'option[]', 
            value: option
        }).appendTo(form);
    });

    form.append('<h4>Encuesta creada correctamente!!</h4>');
    form.append($('<button>').attr('type', 'submit').text('Aceptar'));

    var sendDiv = $('<div id="box">').append(form);

    $('.container').append(sendDiv);

    scrollTo('input[name="end"]');


}




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
                    notificationContainer.prepend(notificationDiv);
                }