// Populate timezone display
const now = new Date();
$('#timezoneNow').text(now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));

// Global variable to hold grouped slots
window.slotsGroupedByDate = {};

// Form submit handler
$('#calendlyForm').on('submit', function(event) {
  event.preventDefault();
  const calendarLink = $('#calendlyLink').val().trim();
  console.log(`Calendar link is ${calendarLink}`);
  $('#result').html('Loading...');

  $.ajax({
    type: 'POST',
    url: '/calendly/fetch',
    data: { calendly_link: encodeURIComponent(calendarLink) },
    success: function(response) {
      if (response.status === 'success') {
        window.slotsGroupedByDate = {};
        response.slots.forEach(slot => {
          if (!window.slotsGroupedByDate[slot.date]) {
            window.slotsGroupedByDate[slot.date] = [];
          }
          window.slotsGroupedByDate[slot.date].push(slot);
        });

        // Populate calendar days dynamically
        populateCalendar(Object.keys(window.slotsGroupedByDate));

        $('#result').html('<div class="alert alert-info">Please select a date above to see time slots.</div>');
      } else {
        $('#result').html(`<div class="alert alert-warning">${response.message}</div>`);
      }
    },
    error: function() {
      $('#result').html('<div class="alert alert-danger">Server error</div>');
    }
  });
});

// Populate calendar day buttons
function populateCalendar(dates) {
  const calendar = $('#calendar');
  calendar.empty();
  dates.forEach(date => {
    const dayNumber = date.split('-')[2];
    calendar.append(`<div class="calendar-day btn btn-outline-primary m-1" data-date="${date}">${dayNumber}</div>`);
  });

  // Attach click handler
  $('.calendar-day').on('click', function() {
    const selectedDate = $(this).data('date');
    renderSlotsForDate(selectedDate);
  });
}

// Render time slots for selected date
function renderSlotsForDate(date) {
  const slots = window.slotsGroupedByDate[date] || [];
  if (slots.length === 0) {
    $('#result').html('<div class="alert alert-warning">No slots for this date.</div>');
    return;
  }

  let html = '<div class="d-flex flex-wrap">';
  slots.forEach(slot => {
    const time = new Date(slot.start_time).toLocaleTimeString([], {
      hour: 'numeric',
      minute: '2-digit'
    });
    html += `<button class="btn btn-outline-primary m-1">${time}</button>`;
  });
  html += '</div>';
  $('#result').html(html);
}
