document.getElementById("btnEnviar").addEventListener("click", function () {
    // Capturar los valores ingresados
    const fecha = document.getElementById("fecha").value;
    const horaLlegada = document.getElementById("horaLlegada").value;
    const turno = document.getElementById("turno").value;
    const lineaTransporte = document.getElementById("lineaTransporte").value;
    const logistica = document.getElementById("logistica").value;
    const numeroASN = document.getElementById("numeroASN").value;
    const supervisor = document.getElementById("supervisor").value;
    const cantidadTarimas = document.getElementById("cantidadTarimas").value;
    const horaSalida = document.getElementById("horaSalida").value;

    // Verificar que todos los campos estén llenos
    if (
        !fecha || !horaLlegada || !turno || !lineaTransporte ||
        !logistica || !numeroASN || !supervisor || !cantidadTarimas || !horaSalida
    ) {
        Swal.fire({
            icon: 'warning', // Icono de advertencia
            title: 'Campos incompletos',
            text: 'Por favor, completa todos los campos antes de enviar.',
            confirmButtonText: 'Entendido'
        });
        return; // Detener la ejecución si falta algún campo
    }

    // Crear un nuevo registro para el historial
    const nuevoRegistro = document.createElement("li");
    nuevoRegistro.textContent =
        `Fecha: ${fecha}, Hora de Llegada: ${horaLlegada}, Turno: ${turno}, Línea: ${lineaTransporte}, 
        Logística: ${logistica}, ASN: ${numeroASN}, Supervisor: ${supervisor}, Tarimas: ${cantidadTarimas}, 
        Hora de Salida: ${horaSalida}`;

    // Agregar el registro al historial
    document.getElementById("historialRegistros").appendChild(nuevoRegistro);

    // Limpiar los campos de entrada
    document.querySelectorAll("input, select").forEach(input => input.value = "");

    // Mostrar mensaje de envío exitoso
    Swal.fire({
        icon: 'success', // Icono de éxito
        title: '¡Envío exitoso!',
        text: 'El registro se guardó correctamente.',
        confirmButtonText: 'Perfecto'
    });
});



/* boton excel */
document.getElementById('btnExportar').addEventListener('click', function () {
    // Obtener las filas del cuerpo de la tabla
    const filas = document.querySelectorAll('#tabla-registros tbody tr');

    // Crear un array para almacenar los datos
    const datos = [];

    // Agregar los encabezados de la tabla
    const encabezados = Array.from(document.querySelectorAll('#tabla-registros thead th'))
        .map(th => th.innerText);
    datos.push(encabezados);

    // Recorrer las filas de la tabla
    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        const filaDatos = [];

        celdas.forEach(celda => {
            // Capturar el valor del input o select dentro de la celda
            const input = celda.querySelector('input, select');
            if (input) {
                filaDatos.push(input.value); // Agregar el valor al array de datos
            } else {
                filaDatos.push(celda.innerText); // En caso de texto estático
            }
        });

        // Agregar la fila al array de datos
        datos.push(filaDatos);
    });

    // Crear una hoja de cálculo
    const ws = XLSX.utils.aoa_to_sheet(datos);

    // Crear un libro de Excel
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Historial');

    // Exportar el archivo
    XLSX.writeFile(wb, 'historial_registros.xlsx');
});

document.getElementById('btnExportar').addEventListener('click', function () {
    // Crear un array para almacenar los datos
    const datos = [];

    // Agregar encabezados manualmente (ajusta según tus columnas)
    datos.push([
        'Fecha',
        'Hora de Llegada',
        'Turno',
        'Línea Transporte',
        'Logística',
        'Número ASN',
        'Supervisor',
        'Cantidad Tarimas',
        'Hora de Salida'
    ]);

    // Capturar los datos del historial
    const historial = document.querySelectorAll('#historialRegistros li');

    // Recorrer cada elemento del historial
    historial.forEach(fila => {
        const textoFila = fila.textContent.trim(); // Obtener el texto de cada fila

        // Usar un regex para extraer los valores después de cada etiqueta
        const valores = textoFila.match(/: ([^,]+)/g).map(celda => celda.replace(': ', '').trim());

        // Agregar los valores como una fila al array
        datos.push(valores);
    });

    // Crear una hoja de cálculo
    const ws = XLSX.utils.aoa_to_sheet(datos);

    // Crear un libro de Excel
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Historial');

    // Exportar el archivo
    XLSX.writeFile(wb, 'historial_registros.xlsx');
});
