<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Dinámico</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>

<body>
    <h1>Exemple de lectura de dades a MySQL</h1>

    <script>
        $(document).ready(function() {
            var formularioMailCreado = false;
            var formularioPasswordCreado = false;
            var formularioConfirmarPasswordCreado = false;
            var formularioPaisesCreado = false;
            var formularioTelefonoCreado = false;
            var formularioCiudadCreado = false;
            var formularioCodigoPostalCreado = false;
            var botonSubmitCreado = false;

            // Agregar campo de entrada para el nombre
            $('body').append($('<label>', { for: 'nombre', text: 'Nombre:' }));
            $('body').append($('<input>', { type: 'text', id: 'nombre', name: 'nombre' }));

            // Evento al escribir en el campo de nombre
            $('#nombre').on('input', function() {
                var nombre = $(this).val().trim();

                if (nombre !== '' && !/^\d+$/.test(nombre)) {
                    crearFormularioMail();
                }
            });

            function crearFormularioMail() {
                if (!formularioMailCreado) {
                    var formularioMail = $('<form>');
                    formularioMail.append($('<label>', { for: 'mail', text: 'Correo Electrónico:' }));
                    formularioMail.append($('<input>', { type: 'email', id: 'mail', name: 'mail' }));
                    $('body').append(formularioMail);
                    formularioMailCreado = true;

                    $('#mail').on('input', function() {
                        var correo = $(this).val().trim();
                        validarCorreoElectronico(correo);
                    });
                }
            }

            function validarCorreoElectronico(correo) {
                // Expresión regular para validar un correo electrónico
                var expresionRegularCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Verificar si el correo electrónico tiene un formato válido
                var correoValido = expresionRegularCorreo.test(correo);

                if (correoValido) {
                    crearFormularioPassword();
                } else {
                    eliminarFormularios(['password', 'confirmarPassword', 'pais', 'ciudad', 'codigoPostal', 'telefono']);
                }
            }

            function crearFormularioPassword() {
                if (!formularioPasswordCreado) {
                    var formularioPassword = $('<form>');
                    formularioPassword.append($('<label>', { for: 'password', text: 'Contraseña:' }));
                    formularioPassword.append($('<input>', { type: 'password', id: 'password', name: 'password' }));
                    $('body').append(formularioPassword);
                    formularioPasswordCreado = true;

                    $('#password').on('input', function() {
                        var password = $(this).val().trim();

                        if (password !== '') {
                            crearFormularioConfirmarPassword();
                        }
                    });
                }
            }

            function crearFormularioConfirmarPassword() {
                if (!formularioConfirmarPasswordCreado) {
                    var formularioConfirmarPassword = $('<form>');
                    formularioConfirmarPassword.append($('<label>', { for: 'confirmarPassword', text: 'Confirmar Contraseña:' }));
                    formularioConfirmarPassword.append($('<input>', { type: 'password', id: 'confirmarPassword', name: 'confirmarPassword' }));
                    $('body').append(formularioConfirmarPassword);
                    formularioConfirmarPasswordCreado = true;

                    $('#confirmarPassword').on('input', function() {
                        var confirmarPassword = $(this).val().trim();
                        var password = $('#password').val().trim();

                        if (confirmarPassword === password) {
                            crearFormularioPaises();
                        }
                    });
                }
            }

            function crearFormularioPaises() {
                if (!formularioPaisesCreado) {
                    var formularioPaises = $('<form>');
                    formularioPaises.append($('<label>', { for: 'pais', text: 'Selecciona un país:' }));
                    var selectPais = $('<select>', { id: 'pais', name: 'pais' });

                    // Agregar las opciones al select
                    <?php
                        $conn = mysqli_connect('localhost', 'aleix', 'Caqjuueeemke64*');
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
                    $('body').append(formularioPaises);
                    formularioPaisesCreado = true;

                    $('#pais').on('change', function() {
                        var selectedPais = $(this).val();
                        if (selectedPais !== '') {
                            crearFormularioTelefono(selectedPais);
                        } 
                    });
                }
            }

            function crearFormularioTelefono(selectedPais) {
                if (!formularioTelefonoCreado) {
                    var formularioTelefono = $('<form>');
                    formularioTelefono.append($('<label>', { for: 'telefono', text: 'Teléfono:' }));

                    // Obtener el prefijo del país seleccionado
                    var prefijo = $('option:selected', '#pais').data('pref');

                    // Input para el prefijo (no editable por el usuario)
                    var inputPrefijo = $('<input>', { type: 'text', id: 'prefijo', name: 'prefijo', value: prefijo, readonly: true });

                    // Input para el número de teléfono (editable por el usuario)
                    var inputTelefono = $('<input>', { type: 'tel', id: 'telefono', name: 'telefono', placeholder: 'Número de teléfono' });

                    formularioTelefono.append(inputPrefijo);
                    formularioTelefono.append(inputTelefono);
                    $('body').append(formularioTelefono);
                    formularioTelefonoCreado = true;

                    $('#telefono').on('input', function() {
                        var telefono = $(this).val().trim();

                        // Verificar si el teléfono tiene solo números y está en el rango deseado
                        var regexNumeros = /^\d+$/;
                        var longitudMinima = 8;
                        var longitudMaxima = 15;

                        if (regexNumeros.test(telefono) && telefono.length >= longitudMinima && telefono.length <= longitudMaxima) {
                            crearFormularioCiudad();
                        }
                    });

                    // Crear campo oculto para el prefijo
                    var inputPrefijoHidden = $('<input>', { type: 'hidden', id: 'prefijo', name: 'prefijo', value: prefijo });
                    formularioTelefono.append(inputPrefijoHidden);
                }
            }


            function crearFormularioCiudad() {
                if (!formularioCiudadCreado) {
                    var formularioCiudad = $('<form>');
                    formularioCiudad.append($('<label>', { for: 'ciudad', text: 'Ciudad:' }));
                    formularioCiudad.append($('<input>', { type: 'text', id: 'ciudad', name: 'ciudad' }));
                    $('body').append(formularioCiudad);
                    formularioCiudadCreado = true;

                    $('#ciudad').on('input', function() {
                        var ciudad = $(this).val().trim();

                        if (ciudad !== '') {
                            crearFormularioCodigoPostal();
                        } 
                    });
                }
            }

            function crearFormularioCodigoPostal() {
                if (!formularioCodigoPostalCreado) {
                    var formularioCodigoPostal = $('<form>');
                    formularioCodigoPostal.append($('<label>', { for: 'codigoPostal', text: 'Código Postal:' }));
                    formularioCodigoPostal.append($('<input>', { type: 'text', id: 'codigoPostal', name: 'codigoPostal' }));
                    $('body').append(formularioCodigoPostal);
                    formularioCodigoPostalCreado = true;

                    $('#codigoPostal').on('input', function () {
                        var codigoPostal = $(this).val().trim();

                        if (codigoPostal !== '') {
                            crearBotonSubmit();
                        }
                    });
                }
            }

            function crearBotonSubmit() {
                if (!botonSubmitCreado) {
                    var botonSubmit = $('<button>', { type: 'submit', text: 'Enviar' });
                    $('body').append(botonSubmit);
                    botonSubmitCreado = true;
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
            }
        });
    </script>

    <!-- Formulario PHP -->
    <form method="post" action="">
        <input type="hidden" name="form_submitted" value="1">
    </form>

    <?php

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_submitted']) && $_POST['form_submitted'] == 1) {
            // Verificar si se han enviado datos por POST
            $nombre = $_POST['nombre'];
            
            // Conectar a la base de datos
            $conn = mysqli_connect('localhost', 'aleix', 'Caqjuueeemke64*');
            mysqli_select_db($conn, 'votaciones');

            // Insertar solo el nombre en la tabla users
            $insertQuery = "INSERT INTO `votaciones`.`users` (`nombre`) VALUES ('$nombre');";

            if (mysqli_query($conn, $insertQuery)) {
                echo "Nombre insertado correctamente en la tabla users.";
            } else {
                echo "Error al insertar nombre: " . mysqli_error($conn);
            }

            // Cerrar la conexión
            mysqli_close($conn);
        }
    ?>


</body>

</html>
