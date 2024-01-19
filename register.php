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
            // Variables para mantener el estado de los formularios
            var formularioMailCreado = false;
            var formularioPasswordCreado = false;
            var formularioConfirmarPasswordCreado = false;
            var formularioPaisesCreado = false;
            var formularioTelefonoCreado = false;
            var formularioCiudadCreado = false;
            var formularioCodigoPostalCreado = false; // Nuevo formulario para código postal

            // (1) Agregar campo de entrada para el nombre
            $('body').append($('<label>', { for: 'nombre', text: 'Nombre:' }));
            $('body').append($('<input>', { type: 'text', id: 'nombre', name: 'nombre' }));

            // (2) Evento al escribir en el campo de nombre
            $('#nombre').on('input', function() {
                var nombre = $(this).val().trim();

                // (3) Verificar si se ha introducido una palabra en el campo de nombre
                if (nombre !== ''&& !/^\d+$/.test(nombre)) {

                    // (4) Crear el formulario de correo electrónico solo si no se ha creado antes
                    if (!formularioMailCreado) {
                        var formularioMail = $('<form>');

                        // (5) Agregar campo de entrada para el correo electrónico
                        formularioMail.append($('<label>', { for: 'mail', text: 'Correo Electrónico:' }));
                        formularioMail.append($('<input>', { type: 'email', id: 'mail', name: 'mail' }));

                        // (6) Agregar el formulario de correo electrónico al body
                        $('body').append(formularioMail);
                        formularioMailCreado = true;

                        // (7) Evento al escribir en el campo de correo electrónico
                        $('#mail').on('input', function() {
                            var correo = $(this).val().trim();

                            // (8) Verificar si el correo electrónico tiene un formato válido
                            var correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);

                            if (correoValido) {
                                // (9) Crear el formulario de contraseña solo si no se ha creado antes
                                if (!formularioPasswordCreado) {
                                    var formularioPassword = $('<form>');

                                    // (10) Agregar campo de entrada para la contraseña
                                    formularioPassword.append($('<label>', { for: 'password', text: 'Contraseña:' }));
                                    formularioPassword.append($('<input>', { type: 'password', id: 'password', name: 'password' }));

                                    // (11) Agregar el formulario de contraseña al body
                                    $('body').append(formularioPassword);
                                    formularioPasswordCreado = true;

                                    // (12) Evento al escribir en el campo de contraseña
                                    $('#password').on('input', function() {
                                        var password = $(this).val().trim();

                                        // (13) Verificar si se ha introducido algo en el campo de contraseña
                                        if (password !== '') {
                                            // (14) Crear el formulario de confirmar contraseña solo si no se ha creado antes
                                            if (!formularioConfirmarPasswordCreado) {
                                                var formularioConfirmarPassword = $('<form>');

                                                // (15) Agregar campo de entrada para confirmar contraseña
                                                formularioConfirmarPassword.append($('<label>', { for: 'confirmarPassword', text: 'Confirmar Contraseña:' }));
                                                formularioConfirmarPassword.append($('<input>', { type: 'password', id: 'confirmarPassword', name: 'confirmarPassword' }));

                                                // (16) Agregar el formulario de confirmar contraseña al body
                                                $('body').append(formularioConfirmarPassword);
                                                formularioConfirmarPasswordCreado = true;

                                                // (17) Evento al escribir en el campo de confirmar contraseña
                                                $('#confirmarPassword').on('input', function() {
                                                    var confirmarPassword = $(this).val().trim();
                                                    var password = $('#password').val().trim();

                                                    // (18) Verificar si las contraseñas coinciden
                                                    if (confirmarPassword === password) {
                                                        // (19) Crear el formulario de países solo si no se ha creado antes
                                                        if (!formularioPaisesCreado) {
                                                            var formularioPaises = $('<form>');

                                                            // (20) Agregar select de países
                                                            var selectPais = $('<select>', { id: 'pais', name: 'pais' });

                                                            // (21) Agregar las opciones al select
                                                            <?php
                                                                // (22) Obtener los países desde PHP
                                                                $conn = mysqli_connect('localhost', 'aleix', 'Caqjuueeemke64*');
                                                                mysqli_select_db($conn, 'votaciones');
                                                                $consulta = "SELECT nombre FROM `votaciones`.`paises`;";
                                                                $resultat = mysqli_query($conn, $consulta);
                                                                $paises = array();
                                                                while ($fila = mysqli_fetch_assoc($resultat)) {
                                                                    $paises[] = $fila['nombre'];
                                                                }
                                                                mysqli_close($conn);

                                                                // (23) Agregar las opciones al select
                                                                foreach ($paises as $pais) {
                                                                    echo 'selectPais.append("<option value=\'" + \'' . $pais . '\' + "\'>" + \'' . $pais . '\' + "</option>");';
                                                                }
                                                            ?>

                                                            // (24) Agregar el select al formulario
                                                            formularioPaises.append($('<label>', { for: 'pais', text: 'Selecciona un país:' }));
                                                            formularioPaises.append(selectPais);

                                                            // (25) Agregar el formulario de países al body
                                                            $('body').append(formularioPaises);
                                                            formularioPaisesCreado = true;

                                                            // (26) Script jQuery para mostrar la selección
                                                            // Mostrar todas las opciones al inicio
                                                            $('#pais option').show();

                                                            // Mostrar las opciones correspondientes al país seleccionado
                                                            $('#pais').on('change', function() {
                                                                var selectedPais = $(this).val();
                                                                if (selectedPais !== '') {
                                                                    // (27) Crear el formulario de teléfono solo si no se ha creado antes
                                                                    if (!formularioTelefonoCreado) {
                                                                        var formularioTelefono = $('<form>');

                                                                        // (28) Agregar campo de entrada para el teléfono
                                                                        formularioTelefono.append($('<label>', { for: 'telefono', text: 'Teléfono:' }));
                                                                        formularioTelefono.append($('<input>', { type: 'tel', id: 'telefono', name: 'telefono' }));

                                                                        // (29) Agregar el formulario de teléfono al body
                                                                        $('body').append(formularioTelefono);
                                                                        formularioTelefonoCreado = true;

                                                                        // (30) Evento al escribir en el campo de teléfono
                                                                        $('#telefono').on('input', function() {
                                                                            var telefono = $(this).val().trim();

                                                                            // (31) Verificar si el teléfono contiene algún número
                                                                            var contieneNumero = /\d/.test(telefono);

                                                                            if (contieneNumero) {
                                                                                // (32) Crear el formulario de ciudad solo si no se ha creado antes
                                                                                if (!formularioCiudadCreado) {
                                                                                    var formularioCiudad = $('<form>');

                                                                                    // (33) Agregar campo de entrada para la ciudad
                                                                                    formularioCiudad.append($('<label>', { for: 'ciudad', text: 'Ciudad:' }));
                                                                                    formularioCiudad.append($('<input>', { type: 'text', id: 'ciudad', name: 'ciudad' }));

                                                                                    // (34) Agregar el formulario de ciudad al body
                                                                                    $('body').append(formularioCiudad);
                                                                                    formularioCiudadCreado = true;

                                                                                    // (35) Evento al escribir en el campo de ciudad
                                                                                    $('#ciudad').on('input', function() {
                                                                                        var ciudad = $(this).val().trim();

                                                                                        // (36) Verificar si la ciudad no está vacía
                                                                                        if (ciudad !== '') {
                                                                                            // (37) Crear el formulario de código postal solo si no se ha creado antes
                                                                                            if (!formularioCodigoPostalCreado) {
                                                                                                var formularioCodigoPostal = $('<form>');

                                                                                                // (38) Agregar campo de entrada para el código postal
                                                                                                formularioCodigoPostal.append($('<label>', { for: 'codigoPostal', text: 'Código Postal:' }));
                                                                                                formularioCodigoPostal.append($('<input>', { type: 'text', id: 'codigoPostal', name: 'codigoPostal' }));

                                                                                                // (39) Agregar el formulario de código postal al body
                                                                                                $('body').append(formularioCodigoPostal);
                                                                                                formularioCodigoPostalCreado = true;
                                                                                            }
                                                                                        } else {
                                                                                            // (40) Eliminar el formulario de código postal si la ciudad está vacía
                                                                                            $('#codigoPostal').closest('form').remove();
                                                                                            formularioCodigoPostalCreado = false;
                                                                                        }
                                                                                    });
                                                                                }
                                                                            } else {
                                                                                // (41) Eliminar el formulario de ciudad si no hay número en el teléfono
                                                                                $('#ciudad').closest('form').remove();
                                                                                formularioCiudadCreado = false;
                                                                            }
                                                                        });
                                                                    }
                                                                } else {
                                                                    // (42) Eliminar el formulario de teléfono si no hay país seleccionado
                                                                    $('#telefono').closest('form').remove();
                                                                    formularioTelefonoCreado = false;

                                                                    // (43) Eliminar el formulario de ciudad si no hay país seleccionado
                                                                    $('#ciudad').closest('form').remove();
                                                                    formularioCiudadCreado = false;

                                                                    // (44) Eliminar el formulario de código postal si no hay país seleccionado
                                                                    $('#codigoPostal').closest('form').remove();
                                                                    formularioCodigoPostalCreado = false;
                                                                }
                                                            });
                                                        }
                                                    } else {
                                                        // (45) Eliminar el formulario de países si las contraseñas no coinciden
                                                        $('#pais').closest('form').remove();
                                                        formularioPaisesCreado = false;

                                                        // (46) Eliminar el formulario de ciudad si las contraseñas no coinciden
                                                        $('#ciudad').closest('form').remove();
                                                        formularioCiudadCreado = false;

                                                        // (47) Eliminar el formulario de código postal si las contraseñas no coinciden
                                                        $('#codigoPostal').closest('form').remove();
                                                        formularioCodigoPostalCreado = false;
                                                    }
                                                });
                                            }
                                        } else {
                                            // (48) Eliminar el formulario de confirmar contraseña si el campo de contraseña está vacío
                                            $('#confirmarPassword').closest('form').remove();
                                            formularioConfirmarPasswordCreado = false;
                                        }
                                    });
                                }
                            } else {
                                // (49) Eliminar el formulario de contraseña si el correo electrónico no es válido
                                $('#password').closest('form').remove();
                                formularioPasswordCreado = false;

                                // (50) Eliminar el formulario de confirmar contraseña si el correo electrónico no es válido
                                $('#confirmarPassword').closest('form').remove();
                                formularioConfirmarPasswordCreado = false;

                                // (51) Eliminar el formulario de países si el correo electrónico no es válido
                                $('#pais').closest('form').remove();
                                formularioPaisesCreado = false;

                                // (52) Eliminar el formulario de ciudad si el correo electrónico no es válido
                                $('#ciudad').closest('form').remove();
                                formularioCiudadCreado = false;

                                // (53) Eliminar el formulario de código postal si el correo electrónico no es válido
                                $('#codigoPostal').closest('form').remove();
                                formularioCodigoPostalCreado = false;

                                // (54) Eliminar el formulario de teléfono si el correo electrónico no es válido
                                $('#telefono').closest('form').remove();
                                formularioTelefonoCreado = false;
                            }
                        });
                    }
                } else {
                    // (55) Eliminar el formulario de correo electrónico si el campo de nombre está vacío
                    $('#mail').closest('form').remove();
                    formularioMailCreado = false;

                    // (56) Eliminar el formulario de contraseña si el campo de nombre está vacío
                    $('#password').closest('form').remove();
                    formularioPasswordCreado = false;

                    // (57) Eliminar el formulario de confirmar contraseña si el campo de nombre está vacío
                    $('#confirmarPassword').closest('form').remove();
                    formularioConfirmarPasswordCreado = false;

                    // (58) Eliminar el formulario de países si el campo de nombre está vacío
                    $('#pais').closest('form').remove();
                    formularioPaisesCreado = false;

                    // (59) Eliminar el formulario de ciudad si el campo de nombre está vacío
                    $('#ciudad').closest('form').remove();
                    formularioCiudadCreado = false;

                    // (60) Eliminar el formulario de código postal si el campo de nombre está vacío
                    $('#codigoPostal').closest('form').remove();
                    formularioCodigoPostalCreado = false;

                    // (61) Eliminar el formulario de teléfono si el campo de nombre está vacío
                    $('#telefono').closest('form').remove();
                    formularioTelefonoCreado = false;
                }
            });
        });
    </script>
</body>
</html>
