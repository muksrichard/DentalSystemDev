// Call the dataTables jQuery plugin
/* $(document).ready(function () {
  var dataRecords = $("#dataTable").DataTable({
    rowCallback: function (row, data, index) {
      if (data[4] == "^" + "0 Day(s)") {
        $(row).find("td:eq(4)").css("color", "red");
      } else if (data[4] == "jogodo") {
        $(row).addClass("red");
      }
    },
  });
}); */

/* $(document).ready(function () {
  var dataRecords = $("#dataTablex").DataTable({});
}); */

// Call the dataTables jQuery plugin
$(document).ready(function () {
  $("table.table").DataTable({
    order: [],
  });
});
