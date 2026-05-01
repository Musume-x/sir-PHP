<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();

$invoice_id = (int) ($_GET['invoice_id'] ?? 0);
$invoice = null;

if ($pdo && $invoice_id) {
    $stmt = $pdo->prepare("
        SELECT i.*, u.name as doctor_name 
        FROM invoices i 
        LEFT JOIN users u ON i.doctor_id = u.id 
        WHERE i.id = ? AND i.patient_id = ? AND i.status != 'paid'
    ");
    $stmt->execute([$invoice_id, $user['id']]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$invoice) {
    header('Location: index.php?page=patient-billing');
    exit;
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Secure Checkout</h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <div class="grid-2">
            <section class="panel">
                <div class="panel-header">
                    <h3>Payment Details</h3>
                </div>
                <div style="padding: 20px;">
                    <form method="post" action="index.php?page=patient-checkout">
                        <input type="hidden" name="process_payment" value="1" />
                        <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
                        
                        <div class="form-group">
                            <label>Payment Method</label>
                            <div class="grid-2" style="gap: 10px;">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="Credit/Debit Card" checked />
                                    <span>💳 Card</span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="GCash" />
                                    <span>📱 GCash</span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="PayMaya" />
                                    <span>💠 PayMaya</span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="Bank Transfer" />
                                    <span>🏦 Bank</span>
                                </label>
                            </div>
                        </div>

                        <div id="card-details">
                            <div class="form-group">
                                <label>Card Number</label>
                                <input type="text" placeholder="xxxx xxxx xxxx xxxx" maxlength="19" />
                            </div>
                            <div class="grid-2" style="gap: 15px;">
                                <div class="form-group">
                                    <label>Expiry Date</label>
                                    <input type="text" placeholder="MM/YY" maxlength="5" />
                                </div>
                                <div class="form-group">
                                    <label>CVV</label>
                                    <input type="password" placeholder="***" maxlength="3" />
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 24px;">
                            <button type="submit" class="btn-primary full">Pay <?php echo mc_format_money($invoice['amount']); ?> Now</button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="panel">
                <div class="panel-header">
                    <h3>Order Summary</h3>
                </div>
                <div style="padding: 20px;">
                    <div class="summary-item">
                        <span>Invoice Number:</span>
                        <strong><?php echo htmlspecialchars($invoice['invoice_number']); ?></strong>
                    </div>
                    <div class="summary-item">
                        <span>Service:</span>
                        <strong><?php echo htmlspecialchars($invoice['service']); ?></strong>
                    </div>
                    <?php if ($invoice['doctor_name']): ?>
                    <div class="summary-item">
                        <span>Doctor:</span>
                        <strong><?php echo htmlspecialchars($invoice['doctor_name']); ?></strong>
                    </div>
                    <?php endif; ?>
                    <hr style="border: 0; border-top: 1px solid var(--mc-light-gray); margin: 15px 0;" />
                    <div class="summary-item total" style="font-size: 1.2rem; color: var(--mc-blue);">
                        <span>Total Amount:</span>
                        <strong><?php echo mc_format_money($invoice['amount']); ?></strong>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<style>
.payment-option {
    border: 1px solid var(--mc-light-gray);
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}
.payment-option:hover {
    background: var(--mc-bg-alt);
}
.payment-option input[type="radio"]:checked + span {
    font-weight: bold;
    color: var(--mc-blue);
}
.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    color: var(--mc-gray);
}
.summary-item strong {
    color: var(--mc-dark);
}
</style>
