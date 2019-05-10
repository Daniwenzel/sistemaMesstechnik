$(document).ready(function() {
    $(".btn-delete-user").on('click', function (e) {
        e.preventDefault();
        let user_id = this.getAttribute('data-id');

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
                    url: "/user/delete/"+user_id
                });
                swal.fire(
                    'Excluido!',
                    'Este usuário foi excluido do sistema.',
                    'success'
                ).then((result) => {
                    if(result.value) {
                        location.reload();
                    }
                })
            }
        })
    });

    $(".btn-delete-company").on('click', function (e) {
        e.preventDefault();
        let company_id = this.getAttribute('data-id');

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
                    url: "/company/delete/"+company_id
                });
                swal.fire(
                    'Excluido!',
                    'Esta empresa foi excluida do sistema.',
                    'success'
                ).then((result) => {
                    if(result.value) {
                        location.reload();
                    }
                })
            }
        })
    });

    $(".btn-delete-role").on('click', function (e) {
        e.preventDefault();
        let role_id = this.getAttribute('data-id');

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
                    url: "/entitlement/roledel/"+role_id
                });
                swal.fire(
                    'Excluido!',
                    'Este Cargo foi excluido do sistema.',
                    'success'
                ).then((result) => {
                    if(result.value) {
                        location.reload();
                    }
                })
            }
        })
    });


    $(".btn-delete-perm").on('click', function (e) {
        e.preventDefault();
        let perm_id = this.getAttribute('data-id');

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
                    url: "/entitlement/permdel/"+perm_id
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
    });
});