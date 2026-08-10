
<!-- HTML for the token PDF -->
<style>
    body {
        font-family: sans-serif;
    }
    .token-container {
        border: 2px dashed #999;
        padding: 20px;
        text-align: center;
    }
    .token-number {
        font-size: 36px;
        color: #d63384;
        margin-top: 10px;
    }
    .info {
        margin: 10px 0;
    }
</style>

<div class="token-container">
    <h2>Appointment Token</h2>
    <hr>
    <p class="info"><strong>Patient Name:</strong> <?php echo htmlspecialchars($data['pname']); ?></p>
    <p class="info"><strong>Phone:</strong> <?php echo htmlspecialchars($data['pphone']); ?></p>
    <p class="info"><strong>Doctor:</strong> <?php echo htmlspecialchars($data['drname']); ?></p>
    <p class="info"><strong>Appointment Date:</strong> <?php echo htmlspecialchars($data['appointment_date']); ?></p>
    <p class="token-number">Token #: <?php echo htmlspecialchars($data['token_num']); ?></p>
</div>
