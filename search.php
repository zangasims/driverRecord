<?php
// search.php
require 'server/connection.php';

// 1) fetch all drivers
$stmt = $conn->prepare("
  SELECT card_id, first_name, last_name, person_phone_number 
    FROM drivers
    ORDER BY last_name, first_name
");
$stmt->execute();
$drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Driver Database</title>
  <link rel="icon" type="image/png" href="fqmTrident copy.png" />
  <link rel="stylesheet" href="style.css" />
  <style>
    /* quick modal reset */
    .modal { display:none; position:fixed; top:0; left:0; 
             width:100%; height:100%; background:rgba(0,0,0,0.6);
             justify-content:center; align-items:center; }
    .modal-content { background:#fff; padding:1rem; border-radius:8px; 
                     max-width:320px; text-align:center; position:relative; }
    .modal-content img { max-width:100%; height:auto; display:block; margin:0 auto 1rem; }
    .close-btn { position:absolute; top:0.5rem; right:0.5rem; 
                 background:none; border:none; font-size:1.5rem; cursor:pointer; }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <img src="fqmTrident.png" class="logo" alt="Logo"/>
      <div class="dropdown">
        <button class="dropbtn">&#9881;</button>
        <div class="dropdown-content">
          <a href="index.php">Registration</a>
          <a href="#analytics">Analytics</a>
          <a href="#logout">Logout</a>
        </div>
      </div>
    </div>

    <h1>Search Drivers</h1>
    <input type="text" id="tableSearch" placeholder="Type name or card ID…"/>

    <table>
      <thead>
        <tr>
          <th>Name</th><th>Phone</th><th>Card ID</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <?php foreach($drivers as $d): ?>
          <?php 
            $full = ucfirst($d['first_name']) . ' ' . ucfirst($d['last_name']);
            $card = htmlspecialchars($d['card_id']);
            $phone = htmlspecialchars($d['person_phone_number']);
          ?>
          <tr data-card="<?php echo $card;?>">
            <td class="nameCell"><?php echo htmlspecialchars($full);?></td>
            <td><?php echo $phone;?></td>
            <td><?php echo $card;?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- image‐modal -->
  <div id="profileModal" class="modal">
    <div class="modal-content">
      <button class="close-btn">&times;</button>
      <img id="profileImg" src="" alt="Driver photo"/>
      <h2 id="profileName"></h2>
      <p id="profileCard"></p>
    </div>
  </div>

  <script>
    const searchInput = document.getElementById('tableSearch');
    const rows        = document.querySelectorAll('#tableBody tr');
    const modal       = document.getElementById('profileModal');
    const imgEl       = document.getElementById('profileImg');
    const nameEl      = document.getElementById('profileName');
    const cardEl      = document.getElementById('profileCard');
    const closeBtn    = modal.querySelector('.close-btn');

    // 1) live filter by name OR card_id
    searchInput.addEventListener('input', () => {
      const v = searchInput.value.toLowerCase();
      rows.forEach(r => {
        const txt = (r.querySelector('.nameCell').textContent + 
                     ' ' + r.dataset.card).toLowerCase();
        r.style.display = txt.includes(v) ? '' : 'none';
      });
    });

    // 2) click name => show modal + load image
    rows.forEach(r => {
      r.querySelector('.nameCell').addEventListener('click', () => {
        const full  = r.querySelector('.nameCell').textContent;
        const card  = r.dataset.card;
        nameEl.textContent = full;
        cardEl.textContent = 'Card ID: ' + card;
        imgEl.src = 'driver_image.php?card_id=' + encodeURIComponent(card);
        modal.style.display = 'flex';
      });
    });

    // 3) close modal
    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => {
      if (e.target === modal) modal.style.display = 'none';
    });
  </script>
</body>
</html>
