const fileContextMenu = document.getElementById('fileContextMenu');
let currentFileId = null;

const hideFileContextMenu = () => {
    fileContextMenu.style.display = 'none';
};

const showFileContextMenu = (event, fileId) => {
    event.preventDefault();
    
    currentFileId = fileId;
    fileContextMenu.style.left = `${event.pageX}px`;
    fileContextMenu.style.top = `${event.pageY}px`;
    fileContextMenu.style.display = 'block';
};

document.querySelectorAll('.file-link').forEach(fileLink => {
    fileLink.addEventListener('contextmenu', function(event) {
        const fileId = this.getAttribute('data-file-id');
        showFileContextMenu(event, fileId);
    });

    fileLink.addEventListener('dblclick', function() {
        const fileId = this.getAttribute('data-file-id');
        window.location.href = `/file/${fileId}`;
    });
});

document.addEventListener('click', function(event) {
    if (!fileContextMenu.contains(event.target)) {
        hideFileContextMenu();
    }
});

document.getElementById('openFile').addEventListener('click', function() {
    if (currentFileId) {
        axios.get(`/file/${currentFileId}`)
        .then(function(response) {
            const fileUrl = response.data.url;
            window.open(fileUrl, '_blank');
        })
        .catch(function(error) {
            Swal.fire({
                title: 'Error',
                text: 'Erro ao carregar arquivo!',
                icon: 'error',
                timer: 2000
            });
        });
    }
});

document.getElementById('downloadFile').addEventListener('click', function() {
    if (currentFileId) {
        window.location.href = `/file/${currentFileId}/true`;
    }
});

document.getElementById('openGoogleDriveFile').addEventListener('click', function() {
    if (currentFileId) {
        data = {
            id: currentFileId
        }
        axios.post('/api/upload-file-to-google-drive', data, { withCredentials: true })
        .then(response => {
            console.log(response);
        })
        .catch(error => {
            console.log(error);
            Swal.fire({
                title: 'Atenção',
                text: error.response.data.message,
                icon: 'info',
            });
        });
    }
});

document.getElementById('editFile').addEventListener('click', function() {
    if (currentFileId) {
        window.location.href = `/edit-file/${currentFileId}`;
    }
});

document.getElementById('deleteFile').addEventListener('click', function() {
    if (currentFileId) {
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Você ainda poderá restaurar o arquivo da lixeira!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('/api/delete-file', { id: currentFileId })
                    .then(response => {
                        const fileItem = document.querySelector(`[data-file-id="${currentFileId}"]`);
                        if (fileItem) {
                            fileItem.remove();
                        }
                        Swal.fire('Sucesso!', response.data.message, 'success');
                    })
                    .catch(error => {
                        Swal.fire('Erro!', error.response.data.message, 'error');
                    });
            }
        });
        
        hideFileContextMenu();
    }
});
