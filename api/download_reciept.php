
<!-- HTML for Receipt -->
<style>
    body {
        font-family: sans-serif;
        padding: 20px;
    }
    .receipt-container {
        border: 1px solid #ccc;
        padding: 20px;
    }
    .receipt-header {
        text-align: center;
        font-size: 20px;
        margin-bottom: 20px;
        color: #0d6efd;
    }
    .info {
        margin: 8px 0;
    }
</style>

<div class="receipt-container">
    <div class="receipt-header">Payment Receipt</div>
    <p class="info"><strong>Receipt ID:</strong> <?php echo $data['pmid']; ?></p>
    <p class="info"><strong>Patient:</strong> <?php echo $data['pname']; ?> (<?php echo $data['pphone']; ?>)</p>
    <p class="info"><strong>Doctor:</strong> <?php echo $data['drname']; ?></p>
    <p class="info"><strong>Appointment Date:</strong> <?php echo $data['appointment_date']; ?></p>
    <p class="info"><strong>Token #:</strong> <?php echo $data['token_num']; ?></p>
    <hr>
    <p class="info"><strong>Amount Paid:</strong> Rs. <?php echo $data['pmamount']; ?></p>
    <p class="info"><strong>Payment Method:</strong> <?php echo $data['payment_method']; ?></p>
    <p class="info"><strong>Status:</strong> <?php echo ucfirst($data['pmstatus']); ?></p>
    <p class="info"><strong>Paid On:</strong> <?php echo $data['pmcreated_at']; ?></p>
</div>
