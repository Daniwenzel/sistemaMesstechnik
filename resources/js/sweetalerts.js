const { default: Swal } = require("sweetalert2");

$(function() {
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
        var status = $(event.target).dataset.status;
        var diretorio = $(event.target).dataset.diretorio;
        var mensagem = $(event.target).dataset.mensagem;

        Swal.fire({
            type: status,
            title: diretorio,
            text: mensagem,
        })
    })

    $('#btn-arquivo-add').on('click', async function() {
        var oemcodigo = document.getElementById('btn-arquivo-add').dataset.oemcodigo;

        var atendimentosTorre = await fetch('/getAtendimentosTorre/'+oemcodigo).then(response => {
            return response.json().then(json => {
                return json.map(dados => [dados.codigo, dados.descricao])
            })
        })

        var pendenciasTorre = await fetch('/getPendenciasTorre/'+oemcodigo).then(response => {
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
                $('input[name="tipo-arquivo"]').on('click', function() {
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
                var tipoArquivo = document.querySelector('input[name="tipo-arquivo"]:checked').value;

                switch (tipoArquivo) {
                    case 'atendimento':
                        var arquivo = $('input[name="arquivo-atendimento"]').prop('files')[0];
                        var tipoCodigo = $('#select-atendimento').val();
                        break;
                    case 'pendencia':
                        var arquivo = $('input[name="arquivo-pendencia"]').prop('files')[0];
                        var tipoCodigo = $('#select-pendencia').val();
                        break;
                    case 'imagem':
                        var arquivo = $('input[name="arquivo-imagem-torre"]').prop('files')[0];
                        var tipoCodigo = "";
                        break;
                }
                
                form.append('codigoSite', oemcodigo);
                form.append('tipoArquivo', tipoArquivo);
                form.append('arquivo', arquivo);
                form.append('tipoCodigo', tipoCodigo);

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

    $('#btn-atendimento-add').on('click', function () {
        var oemcodigo = document.getElementById('btn-atendimento-add').dataset.sitcodigo;

        Swal.fire({
            title: 'Adicionar atendimento',
            width: '80%',
            html: '<label for="descricaoAtendimento">Descrição</label>' +
                '<textarea id="descricaoAtendimento" maxlength="500" class="swal2-input"></textarea>' +
                '<label for="tipoAtendimento">Tipo</label>' +
                '<select id="tipoAtendimento" class="swal2-input">' +
                '<option value="Preventivo">Preventivo</option>' +
                '<option value="Corretivo">Corretivo</option>' +
                '<option value="Inspecao">Inspeção</option>' +
                '<option value="Instalacao">Instalação</option>' +
                '</select>' +
                '<label for="periodoAtendimento">Data</label>' +
                '<input name="periodoAtendimento" id="periodoAtendimento" class="swal2-input" type="text" autocomplete="off" placeholder="yyyy-mm-dd yyyy-mm-dd" required>',
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

                form.append('codigoSite', oemcodigo);
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
        var oemcodigo = document.getElementById('btn-pendencia-add').dataset.sitcodigo;

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

                form.append('codigoSite', oemcodigo);
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

    $('#btn-equipamento-add').on('click', function () {
        var oemcodigo = document.getElementById('btn-equipamento-add').dataset.sitcodigo;

        Swal.fire({
            title: 'Adicionar equipamento',
            width: '50%',
            html: '<label for="descricaoEquipamento">Descrição</label>' +
                '<input id="descricaoEquipamento" type="text" class="swal2-input">' +
                '<label for="nroSerieEquipamento">Número de Série (caso exista)</label>' +
                '<input id="nroSerieEquipamento" type="text" class="swal2-input">' +
                '<label for="dataInstalacao">Data de Instalação</label>' +
                '<input name="dataInstalacao" id="dataInstalacao" class="swal2-input" type="text">' + '<label for="tempoOperacao">Tempo de Operação (em anos)</label>' +
                '<input name="tempoOperacao" id="tempoOperacao" class="swal2-input" type="text">' +
                '<select id="estadoEquipamento" class="swal2-input">' +
                '<option value="OK">OK</option>' +
                '<option value="Irregular">Irregular</option>' +
                '<option value="Substituido">Substituído</option>' +
                '</select>',
            focusConfirm: false,
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Adicionar',
            onOpen: () => $('#dataInstalacao').mask("99/99/9999", { placeholder: 'dd/mm/yyyy' }),
            preConfirm: () => {
                var form = new FormData();
                var descricaoEquipamento = document.getElementById('descricaoEquipamento').value;
                var nroSerieEquipamento = document.getElementById('nroSerieEquipamento').value;
                var dataInstalacao = document.getElementById('dataInstalacao').value;
                var dataSubstituicao = "";
                var tempoOperacao = "";
                if(dataInstalacao && /^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/i.test(dataInstalacao)) {
                    dataItens = dataInstalacao.split('/');
                    dataInstalacao = new Date(dataItens[2], dataItens[1]-1, dataItens[0]);
                    dataSubstituicao = dataInstalacao;
                    dataInstalacao = dataInstalacao.getUTCFullYear()+'-'+("0" + (dataInstalacao.getUTCMonth() + 1)).slice(-2)+'-'+("0" + dataInstalacao.getUTCDate()).slice(-2);
                    tempoOperacao = document.getElementById('tempoOperacao').value;
                    if(tempoOperacao) {
                        dataSubstituicao.setMonth(dataSubstituicao.getMonth()+(tempoOperacao*12));
                        dataSubstituicao = dataSubstituicao.getUTCFullYear()+'-'+("0" + (dataSubstituicao.getUTCMonth() + 1)).slice(-2)+'-'+("0" + dataSubstituicao.getUTCDate()).slice(-2);
                    } else {
                        tempoOperacao = "";
                        dataSubstituicao = "";
                    }
                }

                var estadoEquipamento = document.getElementById('estadoEquipamento').value;

                form.append('codigoSite', oemcodigo);
                form.append('descricaoEquipamento', descricaoEquipamento);
                form.append('nroSerieEquipamento', nroSerieEquipamento);
                form.append('dataInstalacao', dataInstalacao);
                form.append('tempoOperacao', tempoOperacao);
                form.append('dataSubstituicao', dataSubstituicao);
                form.append('estadoEquipamento', estadoEquipamento);

                return fetch("/adicionarEquipamentoTorre", {
                    headers: {
                        "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    body: form
                }).then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }

                    var row = '<tr class="card-button"><td>'+descricaoEquipamento+'</td><td>'+nroSerieEquipamento+'</td><td>'+dataInstalacao+'</td><td>'+tempoOperacao+'</td><td>'+dataSubstituicao+'</td><td>'+estadoEquipamento+'</td></tr>'

                    $(row).prependTo("#tabelaEquipamentos > tbody");

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
                    'Equipamento salvo!',
                    'O registro de equipamento foi armazenado com sucesso!',
                    'success')
            }
            else if (result.dismiss === Swal.DismissReason.cancel ||
                result.dismiss === Swal.DismissReason.esc) {
                Swal.fire(
                    'Cancelado',
                    'A ação para registrar novo equipamento foi cancelada!',
                    'error')
            }
        })
    })
})