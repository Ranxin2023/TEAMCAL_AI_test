<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Calendly Availability Checker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="index.css" rel="stylesheet">
</head>
<body data-backend-timezone="<?= getenv('CALENDLY_TIMEZONE') ?>">

  <div class="container container-main">
    <div class="row">
      <!-- Left section -->
      <div class="col-md-5 mb-3">
        <!-- <h4>Sai Kumar</h4> -->
        <h2><strong>30 Minute Meeting</strong></h2>
        <p><i class="bi bi-clock"></i> 30 min</p>
      </div>

      <!-- Right section -->
      <div class="col-md-7">
        <h5>Select a Date & Time</h5>
        <div id="calendar" class="mb-3">
          <!-- Calendar days will be filled by fetchcalendar.js -->
        </div>
        <div class="timezone">
          <!-- Time zone: Pacific Time - US & Canada (<span id="timezoneNow"></span>) -->
        </div>

        <form id="calendlyForm" class="mt-3">
          <input type="url" id="calendlyLink" class="form-control mb-2" placeholder="Enter Calendly link" required>
          <button type="submit" class="btn btn-primary">Check Availability</button>
        </form>

        <div id="result" class="mt-3"></div>
      </div>
    </div>
  </div>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!--custom fetch calendar custom JS -->
  <script src="js/fetchcalendar.js"></script>
</body>
</html>
