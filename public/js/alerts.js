function swalDeletarUsuario(usuario_id) {
    event.preventDefault();

    Swal.fire({
        title: 'Tem certeza?',
        text: "Não poderá reverter esta ação!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmar Exclusão!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "/user/delete/" + usuario_id
            });
            Swal.fire(
                'Excluido!',
                'Este usuário foi excluido do sistema.',
                'success'
            ).then((result) => {
                if (result.value) {
                    location.reload();
                }
            })
        }
    })
}

function swalDeletarEmpresa(empresa_id) {
    event.preventDefault();

    Swal.fire({
        title: 'Tem certeza?',
        text: "Não poderá reverter esta ação!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmar Exclusão!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "/company/delete/" + empresa_id
            });
            Swal.fire(
                'Excluido!',
                'Esta empresa foi excluida do sistema.',
                'success'
            ).then((result) => {
                if (result.value) {
                    location.reload();
                }
            })
        }
    })
}

function swalDeletarCargo(cargo_id) {
    event.preventDefault();

    Swal.fire({
        title: 'Tem certeza?',
        text: "Não poderá reverter esta ação!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmar Exclusão!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "/entitlement/roledel/" + cargo_id
            });
            Swal.fire(
                'Excluido!',
                'Este Cargo foi excluido do sistema.',
                'success'
            ).then((result) => {
                if (result.value) {
                    location.reload();
                }
            })
        }
    })
}

function swalDeletarPermissao(permissao_id) {
    event.preventDefault();

    Swal.fire({
        title: 'Tem certeza?',
        text: "Não poderá reverter esta ação!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmar Exclusão!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "/entitlement/permdel/"+permissao_id
            });
            Swal.fire(
                'Excluido!',
                'Esta Permissão foi excluida do sistema.',
                'success'
            ).then((result) => {
                if(result.value) {
                    location.reload();
                }
            })
        }
    })
}

function swalRegistrarUsuario() {
    Swal.fire({
        position: 'top-end',
        type: 'success',
        title: 'Your work has been saved',
        showConfirmButton: false,
        timer: 1500,
        onClose: function () {
            window.location.reload();
        }
    });
}
