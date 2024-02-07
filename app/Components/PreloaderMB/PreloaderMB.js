var loader = document.getElementById('loader');

// Execute a função para esconder o loader após um intervalo de tempo (por exemplo, 2000ms)
setTimeout(function () {
    fadeOut(loader);
}, 1000);

// Função para realizar o fade-out
function fadeOut(element) {
    var opacity = 1;
    var interval = setInterval(function () {
        if (opacity <= 0) {
            // Quando a opacidade atinge 0, remova o elemento e pare o intervalo
            element.style.display = 'none';
            clearInterval(interval);
        } else {
            // Reduza a opacidade gradualmente
            element.style.opacity = opacity;
            opacity -= 0.1;
        }
    }, 100);
}