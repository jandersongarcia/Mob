document.addEventListener('DOMContentLoaded', function () {
    // Encapsula as variáveis no escopo local para evitar poluição do escopo global
    let currentScriptId = null;
    let router;

    // Função para carregar scripts de forma assíncrona
    function loadScript(url, callback) {
        const script = document.createElement('script');
        script.src = url;
        script.onload = callback;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Função para executar scripts dinamicamente
    function executeScript(src, name) {
        const scriptPath = `/ctrl/pagesjs${src}`;
        const newScriptId = `script-${name}`;

        // Remove o script anterior, se houver
        if (currentScriptId) {
            const oldScript = document.getElementById(currentScriptId);
            if (oldScript) {
                oldScript.parentNode.removeChild(oldScript);
            }
        }

        const script = document.createElement('script');
        script.src = scriptPath;
        script.id = newScriptId;
        document.head.appendChild(script);
        currentScriptId = newScriptId;
    }

    // Função de inicialização para ser chamada após carregamento dinâmico de conteúdo
    function initializeDynamicContent() {
        // Coloque aqui a inicialização de qualquer JS específico necessário após o carregamento do conteúdo
    }

    // Função para tratar o clique nos links com delegação de eventos
    document.body.addEventListener('click', function (event) {
        let target = event.target.closest('a');
        if (!target) return;

        const href = target.getAttribute('href');

        if (href.startsWith('#')) {
            // Se o link for uma âncora, mantém o comportamento padrão
            return;
        }

        if (href.startsWith('http')) {
            // Se o link for externo, permite que o navegador o carregue normalmente
            return;
        }

        event.preventDefault();

        if (href && href !== '#') {
            router.navigate(href);
        }
    });

    // Carrega o script do Navigo e configura as rotas
    loadScript('/node_modules/navigo/lib/navigo.js', function () {
        const path = `/core/Json/Routes.json?t=${new Date().getTime()}`;

        fetch(path)
            .then(response => response.json())
            .then(data => {
                router = new Navigo('/');

                data.routes.forEach(route => {
                    router.on(route.path, function () {
                        const pagePath = `/ctrl/pages${route.path}`;
                        fetch(pagePath)
                            .then(response => response.ok ? response.text() : Promise.reject('Página não encontrada'))
                            .then(html => {
                                const appElement = document.getElementById('app');
                                appElement.innerHTML = html;
                                executeScript(route.path, route.controller);
                                initializeDynamicContent();  // Re-inicializa o JS necessário
                            })
                            .catch(error => {
                                console.error('Erro ao carregar a página:', error);
                                router.navigate('/404');
                            });
                    });
                });

                router.notFound(function () {
                    fetch('/templates/error/404.php')
                        .then(response => response.text())
                        .then(html => {
                            const appElement = document.getElementById('app');
                            appElement.innerHTML = html;
                            executeScript('/404', 404);
                            initializeDynamicContent();  // Re-inicializa o JS necessário
                        })
                        .catch(error => console.error('Erro ao carregar a página 404:', error));
                });

                router.resolve();
            })
            .catch(error => console.error('Erro ao carregar as rotas:', error));
    });
});
