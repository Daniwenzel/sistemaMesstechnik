$(document).ready(function () {
  $("#compareTowers").click(function (e) {
    e.preventDefault();

    var primeiraTorre = $('#primeiraTorre').val();
    var segundaTorre = $('#segundaTorre').val();
    var periodo = $('#dateFilter').val();

    while (primeiraTorre.length < 6) primeiraTorre = "0" + primeiraTorre;
    while (segundaTorre.length < 6) segundaTorre = "0" + segundaTorre;

    // Chama função de correlação (através do ajax) apenas se os campos tiverem algum valor
    if (primeiraTorre && segundaTorre) {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "/reports/ajaxCompare",
        data: {
          primeiraTorre: primeiraTorre,
          segundaTorre: segundaTorre,
          periodo: periodo
        },
        success: function(data) {
          window.location= '/reports/plots/'+primeiraTorre+'-'+segundaTorre;
        },
        error: function error(xhr, status, _error) {
          console.log('o erro foi no ajax EXTERNO');
          alert(xhr.responseText);
        },
      });
    }
    else {
      alert('Campos de torres vazios');
    }
  });

  $("#generateTowers").click(function (e) {
    e.preventDefault();

    var primeiraTorre = $('#primeiraTorre').val();
    var periodo = $('#dateFilter').val();

    while (primeiraTorre.length < 6) primeiraTorre = "0" + primeiraTorre;

    // Chama função de correlação (através do ajax) apenas se os campos tiverem algum valor
    if (primeiraTorre) {
      if (periodo) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "GET",
          url: "/reports/ajaxGenerate",
          data: {
            primeiraTorre: primeiraTorre,
            periodo: periodo
          },
          success: function(data) {
            window.location= '/reports/plots/'+primeiraTorre;
          },
          error: function error(xhr, status, _error) {
            console.log('o erro foi no ajax EXTERNO');
            alert(xhr.responseText);
          },
        });
      }
      else {
        alert('Campo do período vazio');
      }
    }
    else {
      alert('Campos de torres vazios');
    }
  });

  /*$('form[name="enviarArquivoEpe"]').submit(function(e){ 

    var arqEpe = new FormData();
    arqEpe.append('arquivo', $('#arquivoEpe')[0].files[0])
    /*jQuery.each($('input[name^="arquivoEpe"]')[0].files, function(i, file) {
      arqEpe.append(i, file);
    });

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      processData: false,
      type: "POST",
      url: "/reports/epe",
      data: {
        arquivoEpe: arqEpe
      },
      contentType: 'multipart/form-data',
      beforeSend: function (x) {
        if (x && x.overrideMimeType) {
            x.overrideMimeType("multipart/form-data");
        }
      },
      mimeType: 'multipart/form-data', 
      success: function(data) {
        alert(data);
      },
      error: function error(xhr, status, _error) {
        console.log('Algo deu errado');
        alert(xhr.responseText);
      },
    });
  });*/


 /* $('button[name="enviarEpeBtn"]').click(function(e){
    e.preventDefault();

    var data = new FormData($('form[name="enviarArquivoEpe"]')[0]);     

    jQuery.each($('input[name^="arquivoEpe"]')[0].files, function(i, file) {
      data.append(i, file);
    });

    //var arquivoEpe = $('input[name^="arquivoEpe"]')[0].files[0];

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: "GET",
      url: "/reports/epe",
      data: {
        arquivoEpe: data
      },
      success: function(data) {
        console.log('deu certo');
      },
      error: function error(xhr, status, _error) {
        console.log('Algo deu errado');
        alert(xhr.responseText);
      },
    });
  });*/


  // Exibe e esconde o componente de 'loading' no inicio e fim do ajax
  $(document).ajaxStart(function () {
    $("#loading").addClass('show');
  });
  $(document).ajaxStop(function () {
    $("#loading").removeClass('show');
  });
});