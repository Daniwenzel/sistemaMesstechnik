const { default: Swal } = require("sweetalert2");

$(document).ready(function () {
    $('.btn-user-delete').on('click', function (event) {
        event.preventDefault();
        var usuario_id = $(this).attr('usuario');

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
                fetch('/users/' + usuario_id, {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'DELETE'
                }).then(function () {
                    Swal.fire(
                        'Excluido!',
                        'Este usuário foi excluido do sistema.',
                        'success'
                    ).then((result) => {
                        if (result.value) {
                            location.reload();
                        }
                    })
                })
            }
        })
    })

    $('.btn-log-show').on('click', function (event) {
        event.preventDefault();
        var status = $(this).attr('status');
        var diretorio = $(this).attr('diretorio');
        var mensagem = $(this).attr('mensagem');

        Swal.fire({
            type: status,
            title: diretorio,
            text: mensagem,
        })
    })

    $('#btn-arquivo-add').on('click', async function() {
        var sitcodigo = document.getElementById('btn-arquivo-add').dataset.sitcodigo;

        var atendimentosTorre = await fetch('/getAtendimentosTorre/'+sitcodigo).then(response => {
            return response.json().then(json => {
                return json.map(dados => [dados.codigo, dados.descricao])
            })
        })

        var pendenciasTorre = await fetch('/getPendenciasTorre/'+sitcodigo).then(response => {
            return response.json().then(json => {
                return json.map(dados => [dados.codigo, dados.descricao+'('+dados.gravidade+')'])
            })
        })

        Swal.fire({
            title: 'Adicionar Arquivo',
            width: '80%',
            html:
                '<input type="radio" name="tipo-arquivo" value="atendimento" id="arquivo-atendimento" checked>'+
                '<label for="arquivo-atendimento">Relatório Atendimento</label><br>'+
                '<input type="radio" name="tipo-arquivo" value="pendencia" id="imagem-pendencia">'+
                '<label for="imagem-pendencia">Imagem Pendência</label><br>'+
                '<input type="radio" name="tipo-arquivo" value="imagem" id="imagem-torre">'+
                '<label for="imagem-torre">Imagem Torre</label><br>'+
                '<div class="form-swal-arquivo formulario-atendimento">'+
                '<select id="select-atendimento" class="swal2-input"></select><br>'+
                '<input type="file" name="arquivo-atendimento" accept=".pdf" aria-label="Carregar arquivo do relatorio" class="swal2-file" placeholder="Arquivo pdf do relatorio"></div>'+
                '<div class="form-swal-arquivo formulario-pendencia" style="display: none">'+
                '<select id="select-pendencia" class="swal2-input"></select><br>'+
                '<input type="file" name="arquivo-pendencia" accept="image/*" aria-label="Carregar imagem da pendencia" class="swal2-file" placeholder="Imagem da pendencia"></div>'+
                '<div class="form-swal-arquivo formulario-imagem" style="display: none">'+
                '<h2 class="m-2">Imagem da Estação</h2><br>'+
                '<input type="file" name="arquivo-imagem-torre" accept="image/*" aria-label="Carregar imagem da torre" class="swal2-file" placeholder="Imagem da torre"></div>',
            focusConfirm: false,
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Adicionar',
            onOpen: () => {
                $('input[name="tipo-arquivo"]').click(function() {
                    $('.form-swal-arquivo').hide();
                    $('.formulario-'+this.value).show();
                });
                $.each(atendimentosTorre, function(i, p) {
                    $('#select-atendimento').append($('<option></option>').val(p[0]).html(p[0]+' - '+p[1]));
                })
                $.each(pendenciasTorre, function(i, p) {
                    $('#select-pendencia').append($('<option></option>').val(p[0]).html(p[0]+' - '+p[1]));  
                 })
            },
            preConfirm: () => {
                var form = new FormData();
                var arquivoAtendimento = $('input[name="arquivo-atendimento"]').prop('files')[0];
                var arquivoPendencia = $('input[name="arquivo-pendencia"]').prop('files')[0];
                var imagemTorre = $('input[name="arquivo-imagem-torre"]').prop('files')[0];
                var tipoArquivo = document.querySelector('input[name="tipo-arquivo"]:checked').value;

                form.append('codigoSite', sitcodigo);
                form.append('arquivoAtendimento', arquivoAtendimento);
                form.append('arquivoPendencia', arquivoPendencia);
                form.append('imagemTorre', imagemTorre);
                form.append('tipoArquivo', tipoArquivo);

                return fetch('/adicionarArquivoTorre', {
                    headers: {
                        "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    body: form
                }).then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }

                    return response
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            }
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Arquivo salvo!',
                    'O arquivo foi armazenado com sucesso!',
                    'success')
            }
            else if (result.dismiss === Swal.DismissReason.cancel ||
                result.dismiss === Swal.DismissReason.esc) {
                Swal.fire(
                    'Cancelado',
                    'A ação para armazenar arquivo foi cancelada!',
                    'error')
            }
        })
    })

    // $('#btn-image-add').on('click', async function () {
    //     var sitcodigo = document.getElementById('btn-image-add').dataset.sitcodigo;
    //     var { value: file } = await Swal.fire({
    //         title: 'Selecionar Imagem',
    //         input: 'file',
    //         inputAttributes: {
    //             'type': 'file',
    //             'name': 'imagemSite',
    //             'accept': 'image/*',
    //             'aria-label': 'Carregar imagem da torre'
    //         }
    //     })
    //     if (file) {
    //         const reader = new FileReader()

    //         reader.onload = (e) => {
    //             var form = new FormData();
    //             form.append('imagem', file);
    //             form.append('sitcodigo', sitcodigo);

    //             Swal.fire({
    //                 title: 'Imagem a ser salva',
    //                 imageUrl: e.target.result,
    //                 imageAlt: 'Imagem carregada'
    //             }).then((result) => {
    //                 if (result.value) {
    //                     fetch('/adicionarImagemSite', {
    //                         headers: {
    //                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                         },
    //                         method: 'POST',
    //                         body: form
    //                     }).then(response => {
    //                         if (!response.ok) {
    //                             throw new Error(response.statusText)
    //                         }
    //                         return response
    //                     }).catch(error => {
    //                         Swal.showValidationMessage(
    //                             `Request failed: ${error}`
    //                         )
    //                     })
    //                 }
    //             })
    //         }
    //         reader.readAsDataURL(file)
    //     }
    // })

    $('#btn-atendimento-add').on('click', function () {
        var sitcodigo = document.getElementById('btn-atendimento-add').dataset.sitcodigo;

        // '<label for="codigoSite">Código da torre</label>'+
        // '<input id="codigoSite" class="swal2-input" value="'+sitcodigo+'" readonly>'+

        Swal.fire({
            title: 'Adicionar atendimento',
            width: '80%',
            html: '<label for="descricaoAtendimento">Descrição</label>' +
                '<input id="descricaoAtendimento" type="text" class="swal2-input">' +
                '<label for="tipoAtendimento">Tipo</label>' +
                '<select id="tipoAtendimento" class="swal2-input">' +
                '<option value="Preventivo">Preventivo</option>' +
                '<option value="Corretivo">Corretivo</option>' +
                '<option value="Inspecao">Inspeção</option>' +
                '<option value="Instalacao">Instalação</option>' +
                '</select>' +
                '<label for="periodoAtendimento">Data</label>' +
                '<input name="periodoAtendimento" id="periodoAtendimento" class="swal2-input" type="text" autocomplete="off" required>',
            focusConfirm: false,
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Adicionar',
            onOpen: () => definirFiltroPeriodo($('input[name="periodoAtendimento"]'), "up"),
            preConfirm: () => {
                var form = new FormData();
                var descricaoAtendimento = document.getElementById('descricaoAtendimento').value;
                var inicioAtendimento = document.getElementById('periodoAtendimento').value.split(" ")[0];
                var fimAtendimento = document.getElementById('periodoAtendimento').value.split(" ")[1];
                var tipoAtendimento = document.getElementById('tipoAtendimento').value;


                form.append('codigoSite', sitcodigo);
                form.append('descricaoAtendimento', descricaoAtendimento);
                form.append('dataInicio', inicioAtendimento);
                form.append('dataFim', fimAtendimento);
                form.append('tipoAtendimento', tipoAtendimento);

                return fetch("/adicionarAtendimentoTorre", {
                    headers: {
                        "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    body: form
                }).then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }

                    var row = '<tr class="card-button"><td>'+descricaoAtendimento+'</td><td>'+tipoAtendimento+'</td><td>'+inicioAtendimento+'</td><td>'+fimAtendimento+'</td></tr>'

                    $(row).prependTo("#tabelaAtendimentos > tbody");

                    return response
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            }
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Atendimento salvo!',
                    'O registro de atendimento foi armazenado com sucesso!',
                    'success')
            }
            else if (result.dismiss === Swal.DismissReason.cancel ||
                result.dismiss === Swal.DismissReason.esc) {
                Swal.fire(
                    'Cancelado',
                    'A ação para registrar novo atendimento foi cancelada!',
                    'error')
            }
        })
    })

    $('#btn-pendencia-add').on('click', function () {
        var sitcodigo = document.getElementById('btn-pendencia-add').dataset.sitcodigo;

        // '<label for="codigoSite">Código da torre</label>'+
        // '<input id="codigoSite" class="swal2-input" value="'+sitcodigo+'" readonly>'+

        Swal.fire({
            title: 'Adicionar pendencia',
            width: '80%',
            html:
                '<label for="descricaoPendencia">Descrição</label>' +
                '<textarea id="descricaoPendencia" type="text" class="swal2-input"></textarea>'+
                '<label for="gravidadePendencia">Descrição</label>' +
                '<select id="gravidadePendencia" class="swal2-input">' +
                '<option value="Normal">Normal</option>' +
                '<option value="Urgente">Urgente</option>'+
                '</select>',
            focusConfirm: false,
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Adicionar',
            preConfirm: () => {
                var form = new FormData();
                var descricaoPendencia = document.getElementById('descricaoPendencia').value;
                var gravidadePendencia = document.getElementById('gravidadePendencia').value;

                form.append('codigoSite', sitcodigo);
                form.append('descricaoPendencia', descricaoPendencia);
                form.append('gravidadePendencia', gravidadePendencia);

                return fetch('/adicionarPendenciaTorre', {
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    body: form
                }).then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    // Adiciona uma linha na tabela pendecias quando o método fetch não retornar erro, o registro é armazenado no database pelo fetch, mas a linha é adicionada com jquery para que o usuário não precise atualizar a página

                    var row = (gravidadePendencia === "Normal" ? '<tr class="card-button alert-warning"' : '<tr class="card-button alert-danger"') +'><td>'+descricaoPendencia+'</td><td>Agora</td></tr>'

                    $(row).prependTo("#tabelaPendencias > tbody");

                    return response
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            }
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Pendência salva!',
                    'O registro da pendência foi armazenado com sucesso!',
                    'success')
            }
            else if (result.dismiss === Swal.DismissReason.cancel ||
                result.dismiss === Swal.DismissReason.esc) {
                Swal.fire(
                    'Cancelado',
                    'A ação para registrar nova pendência foi cancelada!',
                    'error')
            }
        })
    })

})

