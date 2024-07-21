$("#wrdeadline").flatpickr({
  enableTime: true,
  dateFormat: "m/d/y H:i:S",
  minDate: "today",
  allowInput: true

});

$("#actdeadline").flatpickr({
  enableTime: true,
  dateFormat: "m/d/y H:i:S",
  minDate: "today",
  "disable": [
    function(date) {
        // return true to disable
        return (date.getDay() === 0 || date.getDay() === 6);

    }
],
  allowInput: true
});

/* $("#duedate").flatpickr({
  enableTime: true,
  dateFormat: "d/m/y H:i:S",
  minDate: "today",
}); */

$("#duedate").flatpickr({
  enableTime: true,
  altInput: true,
  altFormat: "F j, Y",
  //dateFormat: "Y-m-d H:i:S",
  minDate: "today",
});

$('#start-date').flatpickr({
  todayBtn:'linked',
  format: "yyyy-mm-dd",
  autoclose: true
  });

  $('#end-date').flatpickr({
    todayBtn:'linked',
    format: "yyyy-mm-dd",
    autoclose: true
    });
