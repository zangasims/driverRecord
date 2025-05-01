<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Driver Database</title>
  <link rel="icon" type="image/png" href="fqmTrident copy.png" />
</head>
<body>
  <div class="card">
    <div class="header">
      <img src="fqmTrident.png" alt="Company Logo" class="logo" />
      <div class="dropdown">
        <button class="dropbtn">&#9881;</button>
        <div class="dropdown-content">
          <a href="index.php">Registration</a>
          <a href="#analytics">Analytics</a>
          <a href="#logout">Logout</a>
        </div>
      </div>
    </div>

    <h1>Search Records</h1>
    <input type="text" id="tableSearch" placeholder="Search..." />

    <table>
      <thead>
        <tr><th>Name</th><th>Email</th><th>Phone</th></tr>
      </thead>
      <tbody id="tableBody">
        <tr><td>Alice Mwansa</td><td>alice@example.com</td><td>0977123456</td></tr>
        <tr><td>Bob Banda</td><td>bob@example.com</td><td>0966123456</td></tr>
        <tr><td>Charlie Zulu</td><td>charlie@example.com</td><td>0955123456</td></tr>
        <tr><td>David Simfukwe</td><td>david@example.com</td><td>0978123456</td></tr>
      </tbody>
    </table>
  </div>

  <!-- Profile card popup -->
  <div id="videoModal" class="modal">
    <div class="modal-content">
      <div class="card-top">
        <div class="card-header-icons">
          <span class="hamburger">&#9776;</span>
          <span class="close-btn">&times;</span>
        </div>
        <div class="profile-img-container">
          <img id="profileImage" src="" alt="Profile Picture" />
        </div>
        <h2 id="profileName" class="profile-name"></h2>
        <p id="profileSubtitle" class="profile-subtitle"></p>
        <button class="follow-btn">Follow</button>
      </div>
      <div class="card-bottom">
        <p>Learn More About My Profile</p>
        <span class="down-arrow">&#8595;</span>
      </div>
    </div>
  </div>

  <script>
    // Search filter
    const searchInput = document.getElementById('tableSearch');
    const rows = document.querySelectorAll('#tableBody tr');
    searchInput.addEventListener('input', () => {
      const value = searchInput.value.toLowerCase();
      rows.forEach(row => row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none');
    });

    // Profile data map (image paths)
    const profileData = {
      'Alice Mwansa': { img: 'images/alice.jpg', subtitle: 'Driver at FQML Ndola' },
      'Bob Banda': { img: 'images/bob.jpg', subtitle: 'Driver at AMC Lusaka' },
      'Charlie Zulu': { img: 'images/charlie.jpg', subtitle: 'Senior Driver, ZESCO' },
      'David Simfukwe': { img: 'images/david.jpg', subtitle: 'Logistics Driver, ZEMA' }
    };

    const modal = document.getElementById('videoModal');
    const imgElem = document.getElementById('profileImage');
    const nameElem = document.getElementById('profileName');
    const subtitleElem = document.getElementById('profileSubtitle');
    const closeBtn = modal.querySelector('.close-btn');

    document.querySelectorAll('#tableBody td:first-child').forEach(cell => {
      cell.addEventListener('click', () => {
        const name = cell.textContent.trim();
        const data = profileData[name];
        if (data) {
          nameElem.textContent = name;
          subtitleElem.textContent = data.subtitle;
          imgElem.src = data.img;
          modal.style.display = 'flex';
        } else {
          alert('No profile available for ' + name);
        }
      });
    });

    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });
  </script>
</body>
</html>
