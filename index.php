<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>New Driver</title>
  <link rel="icon" type="image/png" href="fqmTrident copy.png" />
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Responsive two-column layout */
    .form-grid {
      display: grid;
      gap: 1rem;
      grid-template-columns: 1fr;
    }
    @media (min-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr 1fr;
      }
    }
    .form-grid label {
      display: block;
      font-weight: bold;
      margin-bottom: 0.25rem;
    }
    .form-grid input,
    .form-grid select {
      width: 100%;
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    video, canvas {
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-top: 0.5rem;
    }
    #open-camera, #capture-photo {
      margin-top: 0.5rem;
      padding: 0.5rem 1rem;
      border: none;
      background-color: #007BFF;
      color: white;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <img src="fqmTrident.png" alt="Company Logo" class="logo">
      <div class="dropdown">
        <button class="dropbtn">&#9881;</button>
        <div class="dropdown-content">
          <a href="#analytics">Analytics</a>
          <a href="search.php">Search</a>
          <a href="#logout">Logout</a>
        </div>
      </div>
    </div>

    <h1>New Driver Registration</h1>
    <div id="error-message" style="color:red; display:none; margin-bottom:1em;"></div>

    <form id="registration-form"
          method="POST"
          action="server/insertDriver.php"
          enctype="multipart/form-data">

      <div class="form-grid">
        <!-- Card scanner field -->
        <div>
          <label for="card-id">Card ID (Company-issued)</label>
          <input type="text"
                 id="card-id"
                 name="card_id"
                 placeholder="Swipe your card…"
                 required
                 autofocus />
        </div>

        <div>
          <label for="first-name">First Name</label>
          <input type="text" id="first-name" name="first_name" required />
        </div>

        <div>
          <label for="last-name">Last Name</label>
          <input type="text" id="last-name" name="last_name" required />
        </div>

        <div>
          <label for="license-no">License Number</label>
          <input type="text" id="license-no" name="license_no" />
        </div>

        <div>
          <label for="licence-type">License Type</label>
          <select id="licence-type" name="licence_type" required>
            <option value="">-- Select Type --</option>
            <option value="Car Class A">Car Class A</option>
            <option value="Car Class B">Car Class B</option>
            <option value="Truck">Truck</option>
            <option value="ADT">ADT</option>
          </select>
        </div>

        <div>
          <label for="department">Department</label>
          <input type="text" id="department" name="department" />
        </div>

        <div>
          <label for="site">Site</label>
          <input type="text" id="site" name="site" />
        </div>

        <div>
          <label for="issue-date">Issue Date</label>
          <input type="date" id="issue-date" name="issue_date" />
        </div>

        <div>
          <label for="expiry-frequency">Phone Number</label>
          <input type="text" id="expiry-frequency" name="person_phone_number" placeholder="+260" />
        </div>

        <div>
          <label for="driver-img">Photo / ID Scan</label>
          <!-- Camera preview -->
          <video id="preview" autoplay playsinline style="display:none;"></video>
          <button type="button" id="open-camera">Open Camera</button>
          <button type="button" id="capture-photo" style="display:none;">Capture Photo</button>
          <canvas id="snapshot" style="display:none;"></canvas>
          <!-- Hidden file input fallback -->
          <input type="file" id="driver-img" name="driver_img" accept="image/*" capture="environment" style="margin-top:0.5rem;" />
        </div>
      </div> <!-- end form-grid -->

      <button type="submit">Register Driver</button>
    </form>
  </div>

  <script>
    const form = document.getElementById('registration-form');
    const err = document.getElementById('error-message');
    const preview = document.getElementById('preview');
    const openCamera = document.getElementById('open-camera');
    const capture = document.getElementById('capture-photo');
    const snapshot = document.getElementById('snapshot');
    let stream;

    openCamera.addEventListener('click', async () => {
      try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        preview.srcObject = stream;
        preview.style.display = 'block';
        capture.style.display = 'inline-block';
        openCamera.style.display = 'none';
      } catch (e) {
        console.error(e);
        alert('Unable to access camera.');
      }
    });

    capture.addEventListener('click', () => {
      const ctx = snapshot.getContext('2d');
      snapshot.width = preview.videoWidth;
      snapshot.height = preview.videoHeight;
      ctx.drawImage(preview, 0, 0);
      snapshot.style.display = 'block';
      preview.style.display = 'none';
      capture.style.display = 'none';
      stream.getTracks().forEach(track => track.stop());
      // convert to blob and attach to form
      snapshot.toBlob(blob => {
        const fileInput = document.getElementById('driver-img');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(new File([blob], 'capture.png', { type: 'image/png' }));
        fileInput.files = dataTransfer.files;
      });
    });

    form.addEventListener('submit', function(e) {
      const card = form.person_phone_number.value.trim();
      const first = form.first_name.value.trim();
      const last = form.last_name.value.trim();
      if (!card || !first || !last) {
        e.preventDefault();
        err.textContent = 'Card ID, first name and last name are required.';
        err.style.display = 'block';
        return;
      }
      err.style.display = 'none';
    });
  </script>
</body>
</html>
