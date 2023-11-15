function asistencia(){

    result = document.getElementById("resultado");
    const fechaHoraActual = new Date();

    const año = fechaHoraActual.getFullYear();
    const mes = fechaHoraActual.getMonth();
    const dia = fechaHoraActual.getDate();
    

    const fecha = new Date();
    const formatoFecha = fecha.toLocaleDateString(); 
    const formatoHora = fecha.toLocaleTimeString();

    const formatoFechaModificado = formatoFecha.replace(/\//g, '-');

    console.log(formatoFechaModificado);
}; 