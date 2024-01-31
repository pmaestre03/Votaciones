var optionNumber = 1;
$(document).ready(function () {
    localStorage.removeItem('nameInicio');
    localStorage.removeItem('nameFinal');
    localStorage.removeItem('nameTitulo');
    localStorage.removeItem('nameOpciones');
    localStorage.removeItem('imgOpciones');
    
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
        $('.borrar').hide();
        $('.borrar[data-option="101"]').show();
    }).keypress(function(event) {
        if (event.which == 13) {
            validatePoll("fecha_inicio");
        }
    });
    var buttonElement = $('<button>').attr({id: 'validate', class: 'borrar button-login', 'data-option': '101'}).text('Validar').click(function() {
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
            $('.borrar').hide();
            $('.borrar[data-option="102"]').show();
        }).keypress(function(event) {
            if (event.which == 13) {
                validatePoll("fecha_final");
            }
        }),
        $('<button>').attr({ id: 'validate', class: 'borrar button-login', 'data-option': '102'}).text('Validar').click(function(){
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
        $('<input>').attr({ type: 'file', name: 'imgTitulo', accept:"image/*"}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            $('.borrar').hide();
            $('.borrar[data-option="103"]').show();
        }),
        $('<input>').attr({ type: 'text', name: 'titulo', id:'titulo', placeholder: 'TITULO'}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            $('.borrar').hide();
            $('.borrar[data-option="103"]').show();
        }).keypress(function(event) {
            if (event.which == 13) {
                validatePoll('titulo');
            }
        }),
        $('<button>').attr({ id: 'validate', class: 'borrar button-login', 'data-option': '103'}).text('Validar').click(function(){
            if (inputElement.next('#box').length === 0) {
                validatePoll('titulo');  }  
        })
    );

    $('.poll-container').append(inputElement);
}

// Función para crear una nueva opción
function createBoxOptions(optionNumber) {
    var optionDiv = $('<div id="box">').append(
        $('<label>').text('Opción encuesta ' + optionNumber + ':'),
        $('<input>').attr({ type: 'file', name: 'imgOpcion' + optionNumber, accept:"image/*"}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            // Habilitar el botón de "Añadir opción" solo en la opción actual
            $('.add-option').hide();
            $('.add-option[data-option="' + optionNumber + '"]').show();
        }),
        $('<input>').attr({ type: 'text', name: 'opcion' + optionNumber, placeholder: 'Opción ' + optionNumber}).on('input', function () {
            $(this).closest('#box').nextAll('#box').remove();
            // Habilitar el botón de "Añadir opción" solo en la opción actual
            $('.add-option').hide();
            $('.add-option[data-option="' + optionNumber + '"]').show();
        }).keypress(function(event) {
            if (event.which == 13) {
                createBoxOptions(optionNumber + 1);
                scrollToBottom();
            }
        }),
        $('<button>').attr({ class: 'add-option button-login', 'data-option': optionNumber }).text('Añadir opción').prop('disabled', false).click(function(){
            var currentOptionNumber = $(this).data('option');
            if ($('input[name=opcion' + currentOptionNumber + ']').val().trim() === "") {
                showNotification("La opción " + currentOptionNumber + " no puede estar vacía", 'red');
            } else {
                createBoxOptions(optionNumber + 1);
                scrollToBottom();
            }
        }),

    );
    if (optionNumber >= 2) {
        $('<button>').attr({ id: 'send-poll', class: 'add-option button-login', 'data-option': optionNumber }).text('Enviar encuesta').prop('disabled', false).click(function(){
            var currentOptionNumber = $(this).data('option');
            var nameOpciones = [];
            // Recorrer los inputs de opciones y guardar valores
            for (var i = 1; i <= currentOptionNumber; i++) {
                var opcionValue = $('input[name=opcion' + i + ']').val().trim();
                if (opcionValue !== "") {
                    nameOpciones.push(opcionValue);
                } else {
                    showNotification("La opción " + i + " no puede estar vacía", 'red');
                    return;
                }
            }
            //Guardar TODAS las opciones en una array
            localStorage.setItem('nameOpciones', JSON.stringify(nameOpciones));

            createBoxBD();
        }).appendTo(optionDiv);
    }
    $('.poll-container').append(optionDiv);

    // Habilitar el botón de "Añadir opción" solo en la opción actual
    $('.add-option').hide();
    $('.add-option[data-option="' + optionNumber + '"]').show();
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
            else if((dateInicio)=>dateHoy){
                localStorage.setItem('nameInicio',nameInicio);
                $('.borrar').hide();
                $('.borrar[data-option="102"]').show();
                createBoxFinal();
            }
            else{
                showNotification("La fecha inicial tiene que ser posterior al dia de hoy", "red");
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
                $('.borrar').hide();
                $('.borrar[data-option="103"]').show();
                createBoxTitle();
            }
            break;

        case "titulo":
            var nameTitulo = $('input[name=titulo]').val();
            var imgTitulo = $('input[name=imgTitulo]').val();
            if(nameTitulo.trim()===""){
                showNotification("El titulo no puede estar vacío", 'red');
            }
            else{
                localStorage.setItem('nameTitulo',nameTitulo);
                $('.borrar').hide();
                createBoxOptions(1);
            }
            break;
        }
    }
}
);

function createBoxBD() {
    var inicio = localStorage.getItem('nameInicio');
    var final = localStorage.getItem('nameFinal');
    var titulo = localStorage.getItem('nameTitulo');
    var opciones = localStorage.getItem('nameOpciones');
    var options = JSON.parse(opciones);

    var form = $('<form>').attr({
        action: 'create_poll.php',
        method: 'POST',
        enctype: "multipart/form-data"
    });

    var fechasTitulo = [
        { name: 'titulo', value: titulo },
        { name: 'inicio', value: inicio },
        { name: 'final', value: final }
    ];

    $.each(fechasTitulo, function(i, campos) {
        $('<input>').attr({
            type: 'hidden',
            name: campos.name,
            value: campos.value
        }).appendTo(form);
    });

    $.each(options, function(i, option) {
        $('<input>').attr({
            type: 'hidden',
            name: 'option[]', 
            value: option
        }).appendTo(form);
    });

    //agregar inputs de imagen al form
    for (var i = 1; i <= options.length; i++) {
        // Obtener el input de tipo file correspondiente
        var inputFile = $('input[name="imgOpcion' + i + '"]');
        inputFile.addClass('hidden');
        inputFile.css('display', 'none')
        // Clonar y agregar el input de tipo file al formulario
        form.append(inputFile.clone());
    }

    $('body').append(form);
    form.submit();
    
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

function scrollToBottom() {
    $('html, body').animate({
        scrollTop: $(document).height()
    }, 1200); 
}
