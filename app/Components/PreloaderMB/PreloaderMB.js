const loader = document.getElementById('loader');

// Execute a função para esconder o loader após um intervalo de tempo (por exemplo, 2000ms)
setTimeout(function () {
    fadeOut();
}, 1000);

// Função para realizar o fade-out
function fadeOut() {
    loader.classList.add('fadeOut');
    setInterval(function () {
        loader.style.display = 'none'
    }, 2000);

}