<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .payment-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
        }

        .payment-container h2 {
            margin: 0 0 20px;
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .note {
            font-size: 14px;
            color: #777;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <h2>Mobile Banking Payment</h2>
    <form>
        <!-- Select Mobile Banking System -->
        <div class="form-group">
            <label for="bank">Choose Mobile Banking:</label>
            <select id="bank" required>
                <option value="">-- Select an option --</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
                <option value="rocket">Rocket</option>
                <option value="upay">Upay</option>
            </select>
        </div>

        <!-- Enter Phone Number -->
        <div class="form-group">
            <label for="phone">Mobile Number:</label>
            <input type="tel" id="phone" placeholder="e.g., 01XXXXXXXXX" required>
        </div>

        <!-- Enter Payment Amount -->
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" id="amount" placeholder="Enter amount in BDT" required>
        </div>

        <!-- Submit Button -->
        <button type="submit">Pay Now</button>

        <p class="note">This is a demo payment gateway.</p>
    </form>
</div>

</body>
</html>

