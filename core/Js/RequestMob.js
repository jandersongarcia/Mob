const mob = {
    get: function (url, successCallback, errorCallback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Sucesso
                successCallback(xhr.responseText);
            } else {
                // Erro
                errorCallback(xhr.statusText);
            }
        };

        xhr.onerror = function () {
            // Erro de rede
            errorCallback("Erro de rede");
        };

        // Enviar a requisição
        xhr.send();
    },

    post: function (url, formData, successCallback, errorCallback, progressCallback) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);

        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                var percentComplete = (event.loaded / event.total) * 100;
                if (progressCallback) {
                    progressCallback(percentComplete);  // Chama o callback de progresso
                }
            }
        };

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Sucesso
                successCallback(xhr.responseText);
            } else {
                // Erro
                errorCallback(xhr.statusText);
            }
        };

        xhr.onerror = function () {
            // Erro de rede
            errorCallback("Erro de rede");
        };

        // Enviar a requisição
        xhr.send(formData);
    },

    put: function (url, data, successCallback, errorCallback) {
        var xhr = new XMLHttpRequest();
        xhr.open('PUT', url, true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Sucesso
                successCallback(xhr.responseText);
            } else {
                // Erro
                errorCallback(xhr.statusText);
            }
        };

        xhr.onerror = function () {
            // Erro de rede
            errorCallback("Erro de rede");
        };

        // Criar um objeto FormData e adicionar os dados
        var formData = new FormData();
        for (var key in data) {
            formData.append(key, data[key]);
        }

        // Enviar a requisição
        xhr.send(formData);
    }
};
