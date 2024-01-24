<?php
// Conectar a la base de datos
$conn = mysqli_connect('localhost', 'root', '', 'votaciones');

// Verificar la conexión
if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

// Verificar si el formulario se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar datos del formulario
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $prefijo = mysqli_real_escape_string($conn, $_POST['prefijo']);
    $mail = mysqli_real_escape_string($conn, $_POST['mail']);
    $password = hash('sha512', $_POST['password']);
    $pais = mysqli_real_escape_string($conn, $_POST['pais']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $ciudad = mysqli_real_escape_string($conn, $_POST['ciudad']);
    $codigoPostal = mysqli_real_escape_string($conn, $_POST['codigoPostal']);

    // Consulta SQL para verificar si el correo ya existe
    $checkQuery = "SELECT COUNT(*) as count FROM users WHERE email = '$mail'";
    $checkResult = mysqli_query($conn, $checkQuery);
    $checkData = mysqli_fetch_assoc($checkResult);

    // Si el correo ya existe, mostrar un mensaje y no realizar la inserción
    if ($checkData['count'] > 0) {
        $mensaje = "El correo electrónico ya ha sido registrado.";
        $colorFondo = "red";
        echo "<script>var mensajeNotificacion = '$mensaje'; var colorFondo = '$colorFondo';</script>";
    } else {
        // Si el correo no existe, realizar la inserción
        // Imprimir el contenido del prefijo para depuración
        echo "Contenido del prefijo: ";
        var_dump($prefijo);

        // Consulta SQL para insertar los datos
        $insertQuery = "INSERT INTO users (nombre, contrasea_cifrada, email, telefono, nombre_pais, rol, pref, nombre_ciudad, codigo_postal) VALUES ('$nombre', '$password', '$mail', '$telefono', '$pais', 'user', '$prefijo', '$ciudad', '$codigoPostal')";

        // Ejecutar la consulta
        if (mysqli_query($conn, $insertQuery)) {
            header('Location: index.php');
            exit();
        } else {
            echo "Error al insertar datos: " . mysqli_error($conn);
        }
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Dinámico</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="./Utilidades/styles.css">
    <script src="./Utilidades/scripts.js"></script>

</head>

<body>
  
    <?php include("header.php") ?>
    <?php include("footer.php") ?>
    <div id="notification-container"></div>
    <script>
        $(document).ready(function () {
            var titulo = $('<h1>', { class: 'register-info' });
            titulo.text('Registro');
            $('body').append(titulo);

            var divContenedor = $('<div>', { class: 'register-container' });
            $('body').append(divContenedor);

            var divNotification = $('<div>', {id: 'notification-container'})
            divContenedor.append(divNotification); 
            var miFormulario = $('<form>', { id: 'miFormulario', action: '', method: 'post' });
            divContenedor.append(miFormulario);  // Utilizar el div contenedor como elemento padre

            if (typeof mensajeNotificacion !== 'undefined' && typeof colorFondo !== 'undefined') {
                // Llamar a la función showNotification con el mensaje y el color de fondo
                showNotification(mensajeNotificacion, colorFondo);
            }
            

            var miFormulario = $('#miFormulario');
            var formularioNombreCreado = false;
            var formularioMailCreado = false;
            var formularioPasswordCreado = false;
            var formularioConfirmarPasswordCreado = false;
            var formularioPaisesCreado = false;
            var formularioTelefonoCreado = false;
            var formularioCiudadCreado = false;
            var formularioCodigoPostalCreado = false;
            var botonSubmitCreado = false;

            crearFormularioNombre()

           function crearFormularioNombre() {
                if (!formNombre) {
                    var formNombre = $('<div>');
                    formNombre.append($('<label>', { for: 'nombre', text: 'Nombre:' }));
                    formNombre.append($('<input>', { type: 'nombre', id: 'nombre', name: 'nombre' }));
                    formNombre.append($('<img>', { src: 'https://static.vecteezy.com/system/resources/previews/018/824/865/original/green-check-mark-button-without-text-free-png.png', class: 'imagen-correcto', alt: 'Correcto' })); // Agrega la imagen
                    miFormulario.append(formNombre);
                }

                $('#nombre').on('input', function () {
                    var nombre = $(this).val().trim();

                    if (nombre !== '' && !/^\d+$/.test(nombre)) {
                        crearFormularioMail();
                        $('.imagen-correcto').show(); // Muestra la imagen cuando el nombre es válido
                    } else {
                        $('.imagen-correcto').hide(); // Oculta la imagen si el nombre no es válido
                    }
                });
            }

            function crearFormularioMail() {
                if (!formularioMailCreado) {
                    var formularioMail = $('<div>');
                    formularioMail.append($('<label>', { for: 'mail', text: 'Correo Electrónico:' }));
                    formularioMail.append($('<input>', { type: 'email', id: 'mail', name: 'mail' }));
                    formularioMail.append($('<img>', { src: 'https://static.vecteezy.com/system/resources/previews/018/824/865/original/green-check-mark-button-without-text-free-png.png', class: 'imagen-correcto2', alt: 'Correcto' })); // Ajusta la ruta de la imagen
                    miFormulario.append(formularioMail);

                    formularioMailCreado = true;

                    formularioMail.on('submit', function (event) {
                        event.preventDefault();
                        var correo = $('#mail').val().trim();
                        validarCorreoElectronico(correo);
                    });

                    $('#mail').on('input', function () {
                        var correo = $(this).val().trim();
                        validarCorreoElectronico(correo);
                    });

                    return formularioMail;
                }
            }


            function validarCorreoElectronico(correo) {
                // Expresión regular para validar un correo electrónico
                var expresionRegularCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Verificar si el correo electrónico tiene un formato válido
                var correoValido = expresionRegularCorreo.test(correo);

                // Obtener la imagen de la marca de verificación
                var imagenCorrecto2 = $('.imagen-correcto2');

                if (correoValido) {
                    imagenCorrecto2.show(); // Mostrar la imagen si el correo es válido
                    crearFormularioPassword();
                } else {
                    imagenCorrecto2.hide(); // Ocultar la imagen si el correo no es válido
                }
            }


            function crearFormularioPassword() {
                if (!formularioPasswordCreado) {
                    var formularioPassword = $('<div>');
                    formularioPassword.append($('<label>', { for: 'password', text: 'Contraseña:' }));
                    formularioPassword.append($('<input>', { type: 'password', id: 'password', name: 'password' }));
                    formularioPassword.append($('<img>', { src: 'https://static.vecteezy.com/system/resources/previews/018/824/865/original/green-check-mark-button-without-text-free-png.png', class: 'imagen-correcto3', alt: 'Correcto' }));
                    miFormulario.append(formularioPassword);

                    formularioPasswordCreado = true;
                    
                    var imagenCorrecto3 = $('.imagen-correcto3');

                    formularioPassword.on('submit', function (event) {
                        event.preventDefault();
                        var password = $('#password').val().trim();

                        if (password !== '') {
                            crearFormularioConfirmarPassword();
                        }
                    });

                    $('#password').on('input', function () {
                        var password = $(this).val().trim();

                        if (password !== '') {
                            imagenCorrecto3.show(); 
                            crearFormularioConfirmarPassword();
                        }else{
                            imagenCorrecto3.hide();
                        }
                    });
                }
            }

            function crearFormularioConfirmarPassword() {
                if (!formularioConfirmarPasswordCreado) {
                    var formularioConfirmarPassword = $('<div>');
                    formularioConfirmarPassword.append($('<label>', { for: 'confirmarPassword', text: 'Confirmar Contraseña:' }));
                    formularioConfirmarPassword.append($('<input>', { type: 'password', id: 'confirmarPassword', name: 'confirmarPassword' }));
                    formularioConfirmarPassword.append($('<img>', { src: 'https://static.vecteezy.com/system/resources/previews/018/824/865/original/green-check-mark-button-without-text-free-png.png', class: 'imagen-correcto4', alt: 'Correcto' }));
                    miFormulario.append(formularioConfirmarPassword);

                    formularioConfirmarPasswordCreado = true;

                    var imagenCorrecto4 = $('.imagen-correcto4');

                    formularioConfirmarPassword.on('submit', function (event) {
                        event.preventDefault();
                        var confirmarPassword = $('#confirmarPassword').val().trim();
                        var password = $('#password').val().trim();

                        if (confirmarPassword === password) {
                            // Añadir clase y atributo readonly a los campos de contraseña
                            $('#password').addClass('campo-desabilitado').attr('readonly', true);
                            $('#confirmarPassword').addClass('campo-desabilitado').attr('readonly', true);
                            
                            crearFormularioPaises();
                        }
                    });

                    $('#confirmarPassword').on('input', function () {
                        var confirmarPassword = $(this).val().trim();
                        var password = $('#password').val().trim();

                        if (confirmarPassword === password) {
                            // Añadir clase y atributo readonly a los campos de contraseña
                            $('#password').addClass('campo-desabilitado').attr('readonly', true);
                            $('#confirmarPassword').addClass('campo-desabilitado').attr('readonly', true);
                            imagenCorrecto4.show(); 
                            crearFormularioPaises();
                        }
                    });
                }
            }


            function crearFormularioPaises() {
                if (!formularioPaisesCreado) {
                    var formularioPaises = $('<div>');
                    formularioPaises.append($('<label>', { for: 'pais', text: 'Selecciona un país:' }));
                    var selectPais = $('<select>', { id: 'pais', name: 'pais' });

                    // Agregar opción en blanco como la primera
                    selectPais.append('<option value="" data-pref="">Selecciona un país</option>');

                    // Agregar las opciones al select desde la base de datos
                    <?php
                    $conn = mysqli_connect('localhost', 'root', '');
                    mysqli_select_db($conn, 'votaciones');
                    $consulta = "SELECT nombre, pref FROM `votaciones`.`paises`;";
                    $resultat = mysqli_query($conn, $consulta);
                    $paises = array();
                    while ($fila = mysqli_fetch_assoc($resultat)) {
                        $paises[] = $fila;
                    }
                    mysqli_close($conn);

                    foreach ($paises as $pais) {
                        echo 'selectPais.append("<option value=\'" + \'' . $pais['nombre'] . '\' + "\' data-pref=\'" + \'' . $pais['pref'] . '\' + "\'>" + \'' . $pais['nombre'] . '\' + "</option>");';
                    }
                    ?>

                    formularioPaises.append(selectPais);
                    miFormulario.append(formularioPaises);

                    formularioPaisesCreado = true;

                    $('#pais').on('change', function () {
                        var selectedPais = $(this).val();
                        if (selectedPais !== '') {
                            crearFormularioTelefono(selectedPais);
                            
                            // Eliminar la opción "Selecciona un país" después de la selección
                            $(this).find('option[value=""]').remove();
                        }
                    });

                    // Crear el campo de teléfono inicialmente si la opción seleccionada no está vacía
                    if ($('#pais').val() !== '') {
                        crearFormularioTelefono($('#pais').val());
                        
                        // Eliminar la opción "Selecciona un país" después de la selección inicial
                        $('#pais').find('option[value=""]').remove();
                    }
                }
            }




            function crearFormularioTelefono(selectedPais) {
                if (!formularioTelefonoCreado) {
                    var formularioTelefono = $('<div>');
                    formularioTelefono.append($('<label>', { for: 'telefono', text: 'Teléfono:' }));
                    

                   
                    // Input para el prefijo (no editable por el usuario)
                    var inputPrefijo = $('<input>', { type: 'text', id: 'prefijoTexto', name: 'prefijo', readonly: true });

                    // Input oculto para el prefijo
                    var inputPrefijoHidden = $('<input>', { type: 'hidden', id: 'prefijo', name: 'prefijo' });

                    // Input para el número de teléfono (editable por el usuario)
                    var inputTelefono = $('<input>', { type: 'tel', id: 'telefono', name: 'telefono', placeholder: 'Número de teléfono' });

                    formularioTelefono.append(inputPrefijo);
                    formularioTelefono.append(inputTelefono);
                    formularioTelefono.append(inputPrefijoHidden);  // Agrega el campo oculto
                    formularioTelefono.append($('<img>', { src: 'https://static.vecteezy.com/system/resources/previews/018/824/865/original/green-check-mark-button-without-text-free-png.png', class: 'imagen-correcto5', alt: 'Correcto' }));
                    miFormulario.append(formularioTelefono);

                    formularioTelefonoCreado = true;

                    // Actualizar el prefijo al cambiar de país
                    $('#pais').on('change', function () {
                        var nuevoPrefijo = $('option:selected', this).data('pref');
                        $('#prefijoTexto').val(nuevoPrefijo);
                        $('#prefijo').val(nuevoPrefijo);  // Actualiza también el campo oculto
                    });

                    // Actualizar el prefijo inicialmente
                    var prefijoInicial = $('option:selected', '#pais').data('pref');
                    $('#prefijoTexto').val(prefijoInicial);
                    $('#prefijo').val(prefijoInicial);  // Actualiza también el campo oculto

                    formularioTelefono.on('submit', function (event) {
                       
                        event.preventDefault();
                        var telefono = $('#telefono').val().trim();

                        // Verificar si el teléfono tiene solo números y está en el rango deseado
                        var regexNumeros = /^\d+$/;
                        var longitudMinima = 8;
                        var longitudMaxima = 15;

                        if (regexNumeros.test(telefono) && telefono.length >= longitudMinima && telefono.length <= longitudMaxima) {
                            
                            crearFormularioCiudad();
                        }
                    });

                    $('#telefono').on('input', function () {
                        var imagenCorrecto5 = $('.imagen-correcto5');
                        var telefono = $(this).val().trim();

                        // Verificar si el teléfono tiene solo números y está en el rango deseado
                        var regexNumeros = /^\d+$/;
                        var longitudMinima = 8;
                        var longitudMaxima = 15;

                        if (regexNumeros.test(telefono) && telefono.length >= longitudMinima && telefono.length <= longitudMaxima) {
                            imagenCorrecto5.show();
                            crearFormularioCiudad();
                        }else{
                            imagenCorrecto5.hide();
                        }
                    });
                }
            }



            function crearFormularioCiudad() {
                if (!formularioCiudadCreado) {
                    var formularioCiudad = $('<div>');
                    formularioCiudad.append($('<label>', { for: 'ciudad', text: 'Ciudad:' }));
                    formularioCiudad.append($('<input>', { type: 'text', id: 'ciudad', name: 'ciudad' }));
                    formularioCiudad.append($('<img>', { src: 'https://static.vecteezy.com/system/resources/previews/018/824/865/original/green-check-mark-button-without-text-free-png.png', class: 'imagen-correcto8', alt: 'Correcto' })); // Ajusta la ruta de la imagen
                    miFormulario.append(formularioCiudad);
                    formularioCiudadCreado = true;

                    formularioCiudad.on('submit', function (event) {
                        var imagenCorrecto8 = $('.imagen-correcto8');
                        event.preventDefault();
                        var ciudad = $('#ciudad').val().trim();

                        if (ciudad !== '') {
                            imagenCorrecto8.show();
                            crearFormularioCodigoPostal();
                        }
                    });

                    $('#ciudad').on('input', function () {
                        var imagenCorrecto8 = $('.imagen-correcto8');
                        var ciudad = $(this).val().trim();

                        if (ciudad !== '') {
                            imagenCorrecto8.show();
                            crearFormularioCodigoPostal();
                        }else{
                            imagenCorrecto8.hide();
                        }
                    });
                }
            }

            function crearFormularioCodigoPostal() {
                if (!formularioCodigoPostalCreado) {
                    var formularioCodigoPostal = $('<div>', { id: 'formularioCodigoPostal' });
                    formularioCodigoPostal.append($('<label>', { for: 'codigoPostal', text: 'Código Postal:' }));
                    formularioCodigoPostal.append($('<input>', { type: 'text', id: 'codigoPostal', name: 'codigoPostal' }));
                    formularioCodigoPostal.append($('<img>', { src: 'https://static.vecteezy.com/system/resources/previews/018/824/865/original/green-check-mark-button-without-text-free-png.png', class: 'imagen-correcto9', alt: 'Correcto' })); // Ajusta la ruta de la imagen
                    miFormulario.append(formularioCodigoPostal);

                    // Monitorear cambios en el código postal
                    $('#codigoPostal').on('input', function () {
                        var imagenCorrecto9 = $('.imagen-correcto9');
                        var nuevoCodigoPostal = $(this).val().trim();

                        // Verificar si el código postal tiene solo números
                        var regexNumeros = /^\d+$/;

                        if (nuevoCodigoPostal !== '' && regexNumeros.test(nuevoCodigoPostal)) {
                            imagenCorrecto9.show()
                            agregarBotonEnviar();
                        } else {
                            imagenCorrecto9.hide()
                            eliminarBotonEnviar();
                        }
                    });

                    formularioCodigoPostalCreado = true;
                }
            }

            function agregarBotonEnviar() {
                if (!botonSubmitCreado) {
                    var botonSubmit = $('<button>', { type: 'submit', text: 'Enviar', class: 'enviar-registro' });
                    miFormulario.append(botonSubmit);
                    botonSubmitCreado = true;
                }
            }

            function eliminarBotonEnviar() {
                if (botonSubmitCreado) {
                    miFormulario.find('button[type="submit"]').remove();
                    botonSubmitCreado = false;
                }
            }

            function eliminarFormularios(formularios) {
                for (var i = 0; i < formularios.length; i++) {
                    $('#' + formularios[i]).closest('form').remove();
                }

                formularioMailCreado = false;
                formularioPasswordCreado = false;
                formularioConfirmarPasswordCreado = false;
                formularioPaisesCreado = false;
                formularioTelefonoCreado = false;
                formularioCiudadCreado = false;
                formularioCodigoPostalCreado = false;

                eliminarBotonEnviar();
            }

        });
    </script>

</body>

</html>
