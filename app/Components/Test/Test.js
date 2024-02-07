// Scripts JavaScript para Test
function atualizarHora() {
    var agora = new Date();
    var horas = agora.getHours().toString().padStart(2, '0');
    var minutos = agora.getMinutes().toString().padStart(2, '0');
    var segundos = agora.getSeconds().toString().padStart(2, '0');

    var horaFormatada = horas + ':' + minutos + ':' + segundos;
    
    document.getElementById('timeNow').innerHTML = horaFormatada;
}

// Atualiza a hora a cada segundo
setInterval(atualizarHora, 1000);

// Chama a função uma vez para mostrar a hora imediatamente
atualizarHora();