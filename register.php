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
    <form id="miFormulario" action="procesar_formulario.php" method="post">
    <button type="submit" name="submitBtn">Enviar</button>
    

    <script>
        $(document).ready(function () {

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
                    miFormulario.append(formNombre);
                }

                $('#nombre').on('input', function () {
                    var nombre = $(this).val().trim();

                    if (nombre !== '' && !/^\d+$/.test(nombre)) {
                        crearFormularioMail();
                    }
                });
            }

            function crearFormularioMail() {
                if (!formularioMailCreado) {
                    var formularioMail = $('<div>');
                    formularioMail.append($('<label>', { for: 'mail', text: 'Correo Electrónico:' }));
                    formularioMail.append($('<input>', { type: 'email', id: 'mail', name: 'mail' }));
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

                if (correoValido) {
                    crearFormularioPassword();
                } else {
                    eliminarFormularios(['password', 'confirmarPassword', 'pais', 'ciudad', 'codigoPostal', 'telefono']);
                }
            }

            function crearFormularioPassword() {
                if (!formularioPasswordCreado) {
                    var formularioPassword = $('<div>');
                    formularioPassword.append($('<label>', { for: 'password', text: 'Contraseña:' }));
                    formularioPassword.append($('<input>', { type: 'password', id: 'password', name: 'password' }));
                    miFormulario.append(formularioPassword);

                    formularioPasswordCreado = true;

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
                            crearFormularioConfirmarPassword();
                        }
                    });
                }
            }

            function crearFormularioConfirmarPassword() {
                if (!formularioConfirmarPasswordCreado) {
                    var formularioConfirmarPassword = $('<div>');
                    formularioConfirmarPassword.append($('<label>', { for: 'confirmarPassword', text: 'Confirmar Contraseña:' }));
                    formularioConfirmarPassword.append($('<input>', { type: 'password', id: 'confirmarPassword', name: 'confirmarPassword' }));
                    miFormulario.append(formularioConfirmarPassword);

                    formularioConfirmarPasswordCreado = true;

                    formularioConfirmarPassword.on('submit', function (event) {
                        event.preventDefault();
                        var confirmarPassword = $('#confirmarPassword').val().trim();
                        var password = $('#password').val().trim();

                        if (confirmarPassword === password) {
                            crearFormularioPaises();
                        }
                    });

                    $('#confirmarPassword').on('input', function () {
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
                    var formularioPaises = $('<div>');
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
                    miFormulario.append(formularioPaises);

                    formularioPaisesCreado = true;

                    $('#pais').on('change', function () {
                        var selectedPais = $(this).val();
                        if (selectedPais !== '') {
                            crearFormularioTelefono(selectedPais);
                        }
                    });
                }
            }

            function crearFormularioTelefono(selectedPais) {
                if (!formularioTelefonoCreado) {
                    var formularioTelefono = $('<div>');
                    formularioTelefono.append($('<label>', { for: 'telefono', text: 'Teléfono:' }));

                    // Obtener el prefijo del país seleccionado
                    var prefijo = $('option:selected', '#pais').data('pref');

                    // Input para el prefijo (no editable por el usuario)
                    var inputPrefijo = $('<input>', { type: 'text', id: 'prefijo', name: 'prefijo', value: prefijo, readonly: true });

                    // Input para el número de teléfono (editable por el usuario)
                    var inputTelefono = $('<input>', { type: 'tel', id: 'telefono', name: 'telefono', placeholder: 'Número de teléfono' });

                    formularioTelefono.append(inputPrefijo);
                    formularioTelefono.append(inputTelefono);
                    miFormulario.append(formularioTelefono);

                    formularioTelefonoCreado = true;

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
                    var formularioCiudad = $('<div>');
                    formularioCiudad.append($('<label>', { for: 'ciudad', text: 'Ciudad:' }));
                    formularioCiudad.append($('<input>', { type: 'text', id: 'ciudad', name: 'ciudad' }));
                    miFormulario.append(formularioCiudad);
                    formularioCiudadCreado = true;

                    formularioCiudad.on('submit', function (event) {
                        event.preventDefault();
                        var ciudad = $('#ciudad').val().trim();

                        if (ciudad !== '') {
                            crearFormularioCodigoPostal();
                        }
                    });

                    $('#ciudad').on('input', function () {
                        var ciudad = $(this).val().trim();

                        if (ciudad !== '') {
                            crearFormularioCodigoPostal();
                        }
                    });
                }
            }

            function crearFormularioCodigoPostal() {
                if (!formularioCodigoPostalCreado) {
                    var formularioCodigoPostal = $('<div>', { id: 'formularioCodigoPostal' });
                    formularioCodigoPostal.append($('<label>', { for: 'codigoPostal', text: 'Código Postal:' }));
                    formularioCodigoPostal.append($('<input>', { type: 'text', id: 'codigoPostal', name: 'codigoPostal' }));
                    miFormulario.append(formularioCodigoPostal);

                    // Agregar el botón de envío si el código postal no está vacío
                    var codigoPostal = $('#codigoPostal').val().trim();
                    if (codigoPostal !== '') {
                        agregarBotonEnviar();
                    }

                    formularioCodigoPostalCreado = true;

                    // Monitorear cambios en el código postal
                    $('#codigoPostal').on('input', function () {
                        var nuevoCodigoPostal = $(this).val().trim();

                        if (nuevoCodigoPostal !== '') {
                            agregarBotonEnviar();
                        } else {
                            eliminarBotonEnviar();
                        }
                    });
                }
            }

            function agregarBotonEnviar() {
                if (!botonSubmitCreado) {
                    var botonSubmit = $('<button>', { type: 'submit', text: 'Enviar' });
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
