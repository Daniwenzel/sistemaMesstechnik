function swalDeletarUsuario(usuario_id) {
    event.preventDefault();

    swal.fire({
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
            swal.fire(
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

    swal.fire({
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
            swal.fire(
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

    swal.fire({
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
            swal.fire(
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

    swal.fire({
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
            swal.fire(
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