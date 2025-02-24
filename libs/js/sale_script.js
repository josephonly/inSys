document.getElementById('checkout-button').addEventListener('click', function () {
    const customerMoney = parseFloat(document.getElementById('customer-money').value);
    
    if (customerMoney < total) {
        alert('Insufficient funds!');
        return;
    }

    let saleItems = [];
    document.querySelectorAll('#bill-items .bill-item').forEach(row => {
        let id = row.getAttribute('data-id');
        let quantity = parseInt(row.querySelector('.item-quantity').value);

        saleItems.push({ id, quantity });
    });

    // Send data to process_sale.php
    fetch('process_sale.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `sale_items=${JSON.stringify(saleItems)}&total_price=${total}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Transaction successful!');
            document.getElementById('bill-items').innerHTML = '';
            total = 0;
            document.getElementById('total-price').innerText = '0.00';
            document.getElementById('customer-money').value = '';
            document.getElementById('change-amount').innerText = '0.00';
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
