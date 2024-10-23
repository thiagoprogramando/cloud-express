const customContextMenu = document.getElementById('customContextMenu');
let currentFolderId = null;
let requiresPassword = false;

const hideContextMenu = () => {
    customContextMenu.style.display = 'none';
};

const showContextMenu = (event, folderId, passwordRequired) => {
    event.preventDefault();

    currentFolderId = folderId;
    requiresPassword = passwordRequired;
    customContextMenu.style.left = `${event.pageX}px`;
    customContextMenu.style.top = `${event.pageY}px`;
    customContextMenu.style.display = 'block';
};

document.querySelectorAll('.folder-link').forEach(folderLink => {
    folderLink.addEventListener('contextmenu', function(event) {
        const folderId = this.getAttribute('data-folder-id');
        showContextMenu(event, folderId);
    });

    folderLink.addEventListener('dblclick', function() {
        const folderId = this.getAttribute('data-folder-id');
        window.location.href = `/folder/${folderId}`;
    });
});

document.addEventListener('click', function(event) {
    if (!customContextMenu.contains(event.target)) {
        hideContextMenu();
    }
});

document.getElementById('moveFolder').addEventListener('click', function() {
    if (currentFolderId) {

    }
});

document.getElementById('openFolder').addEventListener('click', function() {
    if (currentFolderId) {
        if (requiresPassword) {
            Swal.fire({
                title: 'Digite a Senha',
                input: 'password',
                inputPlaceholder: 'Senha',
                showCancelButton: true,
                confirmButtonText: 'Acessar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    const password = result.value;
                    axios.post('/api/validate-password', {
                        id: currentFolderId,
                        password: password
                    }).then(response => {
                        if (response.data.valid) {
                            window.location.href = `/folder/${currentFolderId}`;
                        } else {
                            Swal.fire('Senha Incorreta', '', 'error');
                        }
                    }).catch(error => {
                        Swal.fire('Erro ao validar a senha', '', 'error');
                    });
                }
            });
        } else {
            window.location.href = `/folder/${currentFolderId}`;
        }
    }
});

document.getElementById('deleteFolder').addEventListener('click', function() {
    if (currentFolderId) {
        if (requiresPassword) {
            Swal.fire({
                title: 'Digite a Senha',
                input: 'password',
                inputPlaceholder: 'Senha',
                showCancelButton: true,
                confirmButtonText: 'Excluir',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    const password = result.value;
                    axios.post('/api/validate-password', {
                        folder_id: currentFolderId,
                        password: password
                    }).then(response => {
                        if (response.data.valid) {
                            axios.post('/api/delete-folder', {
                                id: currentFolderId,
                            })
                            .then(response => {
                                const folderItem = document.querySelector(`[data-folder-id="${currentFolderId}"]`);
                                if (folderItem) {
                                    folderItem.remove();
                                }
                                Swal.fire({
                                    title: 'Sucesso!',
                                    text: response.data.message,
                                    icon: 'success',
                                    timer: 3000
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Erro!',
                                    text: error.response.data.message,
                                    icon: 'error',
                                    timer: 3000
                                });
                            });
                        } else {
                            Swal.fire('Senha Incorreta', '', 'error');
                        }
                    }).catch(error => {
                        console.error(error);
                        Swal.fire('Erro ao validar a senha', '', 'error');
                    });
                }
            });
        } else {
            axios.post('/api/delete-folder', {
                id: currentFolderId,
            })
            .then(response => {
                const folderItem = document.querySelector(`[data-folder-id="${currentFolderId}"]`);
                if (folderItem) {
                    folderItem.remove();
                }
                Swal.fire({
                    title: 'Sucesso!',
                    text: response.data.message,
                    icon: 'success',
                    timer: 3000
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Erro!',
                    text: error.response.data.message,
                    icon: 'error',
                    timer: 3000
                });
            });
        }

        hideContextMenu();
    }
});

document.getElementById('editFolder').addEventListener('click', function() {
    if (currentFolderId) {
        if (requiresPassword) {
            Swal.fire({
                title: 'Digite a Senha',
                input: 'password',
                inputPlaceholder: 'Senha',
                showCancelButton: true,
                confirmButtonText: 'Acessar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    const password = result.value;
                    axios.post('/api/validate-password', {
                        id: currentFolderId,
                        password: password
                    }).then(response => {
                        if (response.data.valid) {
                            window.location.href = `/edit-folder/${currentFolderId}`;
                        } else {
                            Swal.fire('Senha Incorreta', '', 'error');
                        }
                    }).catch(error => {
                        Swal.fire('Erro ao validar a senha', '', 'error');
                    });
                }
            });
        } else {
            window.location.href = `/edit-folder/${currentFolderId}`;
        }
    }
});