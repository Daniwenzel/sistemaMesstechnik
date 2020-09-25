$(document).ready(function () {
  $("#compareTowers").click(function (e) {
    e.preventDefault();

    var primeiroArq = $('input[name="primeiroEpe"]')[0].files[0];
    var segundoArq = $('input[name="segundoEpe"]')[0].files[0];

    if(primeiroArq && segundoArq) {
      $('form[name="plotsCorrelacao"]').submit();
    }
    
    else {

      // O primeiro codigo sempre será a torre de referência, mas a url retornada será com o maior codigo
      // Para evitar que duas pastas da mesma correlação sejam criadas.
      // ex: diretórios /../000321-000322/ e /../000322-000321/ para armazenar a mesma correlação
      var torreRef = $('#primeiraTorre').val();
      var torreSec = $('#segundaTorre').val();
      var periodo = $('#dateFilter').val();

      function adicionarZeros(estacao) {
        while(estacao.length < 6) estacao = "0" + estacao;

        return estacao;
      } 
    
      // Busca e aplica tratamento no maior e menor código estação, para montar a url
      var codigoMaior = adicionarZeros(Math.max(torreRef,torreSec)+"");
      var codigoMenor = adicionarZeros(Math.min(torreRef,torreSec)+"");
      var diretorio = codigoMaior+'-'+codigoMenor;
      // Aplica tratamento nos codigos, para serem enviados através do ajax, para o script R
      torreRef = adicionarZeros(torreRef);
      torreSec = adicionarZeros(torreSec);

      // Se os campos não forem nulos e iguais, envia as informações para o Controller através do Ajax
      if ((torreRef && torreSec) && (torreRef != torreSec)) {
        if (periodo) {
          $.ajax({
            //headers: {
            //  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //},
            type: "GET",
            url: "/reports/ajaxCompare",
            data: {
              torreReferencia: torreRef,
              torreSecundaria: torreSec,
              periodo: periodo,
              diretorio: diretorio
            },
            success: function(data) {
              window.location= '/reports/plots/'+diretorio;
            },
            error: function error(xhr, status, _error) {
              console.log('o erro foi no ajax EXTERNO');
              alert(xhr.responseText);
            },
          });
        }
        else {
          alert('Campo do período vazio.');
        }
      }
      else {
        alert('Estações inválidas ou iguais.');
      }
    }
  })

  $("#generatePlots").click(function (e) {
    e.preventDefault();

    if($('input[name="arquivoEpe"]')[0].files[0]) {
      $('form[name="plotsTorre"]').submit();
    }
    else {
      var primeiraTorre = $('#primeiraTorre').val();
      var periodo = $('#dateFilter').val();

      while (primeiraTorre.length < 6) primeiraTorre = "0" + primeiraTorre;

      // Chama função de correlação (através do ajax) apenas se os campos tiverem algum valor
      if (primeiraTorre) {
        if (periodo) {
          $.ajax({
            //headers: {
            //  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //},
            type: "GET",
            url: "/reports/ajaxGenerate",
            data: {
              primeiraTorre: primeiraTorre,
              periodo: periodo,
              diretorio: primeiraTorre
            },
            success: function(data) {
              window.location.href= '/reports/plots/'+primeiraTorre;
            },
            error: function error(xhr, status, _error) {
              console.log('o erro foi no ajax EXTERNO');
              alert(xhr.responseText);
            },
          });
        }
        else {
          alert('Campo do período vazio.');    
        }
      }
      else {
        alert('Campos de torres vazios.');
      }
    }
  });

  // Exibe e esconde o componente de 'loading' no inicio e fim do ajax
  $(document).ajaxStart(function () {
    $("#loading").addClass('show');
  });
  $(document).ajaxStop(function () {
    $("#loading").removeClass('show');
  });
});

window.teste = async function() {
  const teste = await fetch('/generateCsv')
  const stringe = await teste.json();

  TESTER = document.getElementById('tester');

  Plotly.plot(TESTER, [{
      x: [1, 2, 3, 4, 5],
      y: [1, 2, 4, 8, 16] }], { 
      margin: { t: 0 } }, {showSendToCloud:true} );
  
  /* Current Plotly.js version */
  console.log( Plotly.BUILD );

  console.log(stringe);
}
  // .then(function(response) {
  //   console.log(response.arrayBuffer());
    // console.log(response.blob());
    // console.log(response.json());
    // console.log(response.text());
    // console.log(response.formData());
