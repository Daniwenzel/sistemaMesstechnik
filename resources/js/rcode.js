$(document).ready(function () {
  $("#compareTowers").click(function (e) {
    e.preventDefault();

    var primeiraTorre = $('#primeiraTorre').val();
    var segundaTorre = $('#segundaTorre').val();
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
          segundaTorre: segundaTorre
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

  // Exibe e esconde o componente de 'loading' no inicio e fim do ajax
  $(document).ajaxStart(function () {
    $("#loading").addClass('show');
  });
  $(document).ajaxStop(function () {
    $("#loading").removeClass('show');
  });
});