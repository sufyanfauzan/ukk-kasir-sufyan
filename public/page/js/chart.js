{/* <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> */}
  // dummy bar
  var dates = ['2023-01-01', '2023-01-02', '2023-01-03', '2023-01-04', '2023-01-05'];
  var totals = [120, 150, 200, 170, 190];

  var ctx = document.getElementById('salesChart').getContext('2d');
  var salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: dates,
      datasets: [{
        label: 'Jumlah Transaksi',
        data: totals,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // dummy pie
  var product_names = ['Produk A', 'Produk B', 'Produk C', 'Produk D', 'Produk E'];
  var product_qty = [50, 80, 120, 60, 40];

  var ctx2 = document.getElementById('productChart').getContext('2d');
  var productChart = new Chart(ctx2, {
    type: 'pie',
    data: {
      labels: product_names,
      datasets: [{
        label: 'Jumlah Terjual',
        data: product_qty,
        backgroundColor: [
          'rgba(255, 99, 132, 0.6)',
          'rgba(54, 162, 235, 0.6)',
          'rgba(255, 206, 86, 0.6)',
          'rgba(75, 192, 192, 0.6)',
          'rgba(153, 102, 255, 0.6)',
          'rgba(255, 159, 64, 0.6)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true
    }
  });

  let cart_checkout = [];

  function updateQuantity(id, change, maxStock) {
      var qtyInput = document.getElementById('qty-' + id);
      var qty = parseInt(qtyInput.value) || 0;
      var newQty = qty + change;

      if (newQty < 0) {
          newQty = 0;
      }
      if (newQty > maxStock) {
          newQty = maxStock;
      }

      qtyInput.value = newQty;

      var found = false;
      for (var i = 0; i < cart_checkout.length; i++) {
          if (cart_checkout[i].id === id) {
              if (newQty === 0) {
                  cart_checkout.splice(i, 1);
              } else {
                  cart_checkout[i].qty = newQty;
              }
              found = true;
              break;
          }
      }

      if (!found && newQty > 0) {
          cart_checkout.push({ id: id, qty: newQty });
      }
  }

  document.getElementById('checkout-form').addEventListener('submit', function() {
      document.getElementById('cart_checkout').value = JSON.stringify(cart_checkout);
  });
