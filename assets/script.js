// === FARMER CRUD ===
// === DASHBOARD STATS ===
function loadStats() {
  fetch('api/stats.php')
    .then(res => res.json())
    .then(data => {
      document.getElementById('statFarmers').textContent = data.totalFarmers;
      document.getElementById('statProduce').textContent = data.totalProduce;
      document.getElementById('statQuantity').textContent = data.totalQuantity;
      document.getElementById('statValue').textContent = '$' + data.totalValue;
    });
}

function loadFarmers() {
  fetch('api/farmer.php')
    .then(res => res.json())
    .then(data => {
      let html = "<h3>All Farmers</h3><table><tr><th>ID</th><th>Name</th><th>Location</th><th>Phone</th><th>Actions</th></tr>";
      data.forEach(f => {
        html += `<tr>
          <td>${f.farmer_id}</td>
          <td>${f.name}</td>
          <td>${f.location}</td>
          <td>${f.phone}</td>
          <td>
            <button onclick="editFarmer(${f.farmer_id},'${f.name}','${f.location}','${f.phone}')">Edit</button>
            <button onclick="deleteFarmer(${f.farmer_id})">Delete</button>
          </td>
        </tr>`;
      });
      html += "</table>";
      document.getElementById("farmerList").innerHTML = html;
    });
}

function saveFarmer() {
  let data = {
    name: farmer_name.value,
    location: farmer_location.value,
    phone: farmer_phone.value
  };
  if (farmer_id.value) data.farmer_id = farmer_id.value;

  fetch('api/farmer.php', {
    method: 'POST',
    body: JSON.stringify(data)
  })
    .then(res => res.json())
    .then(() => {
      loadFarmers();
      loadStats(); // refresh dashboard
      clearFarmer();
    });
}

function editFarmer(id, name, location, phone) {
  farmer_id.value = id;
  farmer_name.value = name;
  farmer_location.value = location;
  farmer_phone.value = phone;
}

function deleteFarmer(id) {
  fetch('api/farmer.php', { method: 'DELETE', body: `id=${id}` })
    .then(res => res.json())
    .then(() => {
      loadFarmers();
      loadStats(); // refresh dashboard
    });
}

function clearFarmer() {
  farmer_id.value = "";
  farmer_name.value = "";
  farmer_location.value = "";
  farmer_phone.value = "";
}

// === PRODUCE CRUD ===
function saveProduce() {
  let data = {
    farmer_id: produce_farmer_id.value,
    produce_name: produce_name.value,
    quantity: produce_quantity.value,
    price_per_kg: produce_price.value
  };
  if (produce_id.value) data.produce_id = produce_id.value;

  fetch('api/produce.php', {
    method: 'POST',
    body: JSON.stringify(data)
  })
    .then(res => res.json())
    .then(() => {
      searchProduce();
      loadStats(); // refresh dashboard
      clearProduce();
    });
}

function clearProduce() {
  produce_id.value = "";
  produce_farmer_id.value = "";
  produce_name.value = "";
  produce_quantity.value = "";
  produce_price.value = "";
}

function searchProduce() {
  let q = document.getElementById("search_name").value;
  fetch('api/produce.php?name=' + q)
    .then(res => res.json())
    .then(data => {
      let html = "<table><tr><th>ID</th><th>Farmer</th><th>Produce</th><th>Qty</th><th>Price</th><th>Actions</th></tr>";
      data.forEach(p => {
        html += `<tr>
          <td>${p.produce_id}</td>
          <td>${p.farmer_name}</td>
          <td>${p.produce_name}</td>
          <td>${p.quantity}</td>
          <td>$${p.price_per_kg}</td>
          <td>
            <button onclick="editProduce(${p.produce_id},${p.farmer_id},'${p.produce_name}',${p.quantity},${p.price_per_kg})">Edit</button>
            <button onclick="deleteProduce(${p.produce_id})">Delete</button>
          </td>
        </tr>`;
      });
      html += "</table>";
      document.getElementById("produceList").innerHTML = html;
    });
}

function editProduce(id, farmer_id, name, qty, price) {
  produce_id.value = id;
  produce_farmer_id.value = farmer_id;
  produce_name.value = name;
  produce_quantity.value = qty;
  produce_price.value = price;
}

function deleteProduce(id) {
  fetch('api/produce.php', { method: 'DELETE', body: `id=${id}` })
    .then(res => res.json())
    .then(() => {
      searchProduce();
      loadStats(); // refresh dashboard
    });
}

window.onload = () => {
  loadStats();
  loadFarmers();
  searchProduce();
};