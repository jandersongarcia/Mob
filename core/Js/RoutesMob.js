document.addEventListener('DOMContentLoaded', function () {

    let nameId;
    let router;  // Tornar router global

    // Função para carregar scripts de forma assíncrona
    function carregarScript(url, callback) {
        var script = document.createElement('script');
        script.src = url;
        script.onload = callback;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Função para executar scripts dinamicamente
    function executarScripts(src, name) {
        var scriptPath = '/ctrl/pages.js' + src;
        let id = `el${nameId}`;
        // Supondo que você tenha um script com um ID específico
        var scriptParaRemover = document.getElementById(id);

        // Verifica se o script existe antes de tentar removê-lo
        if (scriptParaRemover) {
            scriptParaRemover.parentNode.removeChild(scriptParaRemover);
        }

        var script = document.createElement('script');
        script.src = scriptPath;
        script.id = `el${name}`;
        document.head.appendChild(script);
        nameId = `el${name}`;
    }

    // Função para tratar o clique nos links
    function handleLinkClick(event) {
        var target = event.target;

        // Verificar se o clique foi em um link (ou um elemento filho do link)
        var isLinkClick = false;
        while (target) {
            if (target.tagName === 'A') {
                isLinkClick = true;
                break;
            }
            target = target.parentElement;
        }

        if (isLinkClick) {
            // Impedir o comportamento padrão do link
            event.preventDefault();

            // Obter o atributo 'href' do link
            var hrefAttribute = target.getAttribute('href');

            // Verificar se o link tem um 'href' definido e não é igual a '#'
            if (hrefAttribute && hrefAttribute !== '#') {
                // Navegar para a rota especificada no atributo 'href' do link
                router.navigate(hrefAttribute);
            } else {
                // Se o link não tiver 'href' ou for igual a '#', você pode adicionar um comportamento alternativo ou ignorar
                // console.warn('Link sem destino específico. Adicione um comportamento adequado ou ignore.');
            }
        }
    }

    // Carregar o script do Navigo dinamicamente
    carregarScript('/node_modules/navigo/lib/navigo.js', function () {
        let caminho = '/core/json/routes.json?t=' + new Date().getTime();

        // Carregar as rotas do arquivo JSON
        fetch(caminho)
            .then(response => response.json())
            .then(data => {
                // Configurar rotas dinamicamente
                router = new Navigo('/');

                data.routes.forEach(route => {
                    router.on(route.path, function (params, query) {
                        var pagePath = '/ctrl/pages' + route.path;
                        fetch(pagePath)
                            .then(response => {
                                if (response.ok) {
                                    return response.text();
                                } else {
                                    throw new Error('Página não encontrada');
                                }
                            })
                            .then(html => {
                                var newDiv = document.createElement('div');
                                newDiv.innerHTML = html;
                                document.getElementById('app').innerHTML = newDiv.innerHTML;

                                // Executar os scripts dinamicamente após o carregamento total da página
                                executarScripts(route.path, route.controller);
                            })
                            .catch(error => {
                                console.error('Erro ao carregar a página:', error);
                                router.navigate('/404');
                            });
                    });
                });

                // Rota genérica para lidar com páginas não encontradas
                router.notFound(function () {
                    fetch('/templates/error/404.php')
                        .then(response => response.text())
                        .then(html => {
                            var newDiv = document.createElement('div');
                            newDiv.innerHTML = html;

                            var appElement = document.getElementById('app');
                            appElement.innerHTML = newDiv.innerHTML;

                            // Executar os scripts dinamicamente após o carregamento total da página
                            executarScripts('/404', 404);
                        })
                        .catch(error => console.error('Erro ao carregar a página 404:', error));
                });

                // Adicionar um ouvinte de evento para links
                document.body.addEventListener('click', handleLinkClick);

                // Iniciar o roteamento
                router.resolve();
            })
            .catch(error => console.error('Erro ao carregar as rotas:', error));
    });
});
