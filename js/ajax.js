$(document).ready(function () {
  

   //super search
  var superSearch = $("#supersearch").DataTable({
    lengthChange: true,
    processing: true,
    serverSide: true,
    serverMethod: "post",
    order: [],
    searchable: true,
    columnDefs: [
      {
        targets: [0, 4],
        orderable: true,
      },
    ],
    ajax: {
      url: "ajax_action.php",
      type: "POST",
      data: { action: "superSearch" },
      dataType: "json",
    },
  });
  setInterval(function () {
    superSearch.ajax.reload(null, false);
  }, 60000);

  //new stuff to be added later

});
