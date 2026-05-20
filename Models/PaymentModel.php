<?php
// ══════════════════════════════════════════════════════════════════
//  models/PaymentModel.php
//  Talks directly to the DB — no business logic here, just queries.
// ══════════════════════════════════════════════════════════════════

class PaymentModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ── Check parent exists ──────────────────────────────────────
    public function parentExists($parentID) {
        $stmt = mysqli_prepare($this->conn,
            "SELECT parentID FROM Parent WHERE parentID = ?");
        mysqli_stmt_bind_param($stmt, "i", $parentID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }

    // ── Check child exists and belongs to parent ─────────────────
    public function childBelongsToParent($childID, $parentID) {
        $stmt = mysqli_prepare($this->conn,
            "SELECT childID FROM Child WHERE childID = ? AND parentID = ?");
        mysqli_stmt_bind_param($stmt, "ii", $childID, $parentID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }

    // ── Get active subscription for parent+child ─────────────────
    public function getActiveSubscription($parentID, $childID) {
        $stmt = mysqli_prepare($this->conn,
            "SELECT subscriptionID, planName, basePrice, billingCycle
             FROM Subscription
             WHERE parentID = ? AND childID = ? AND status = 'Active'
             ORDER BY subscriptionID DESC LIMIT 1");
        mysqli_stmt_bind_param($stmt, "ii", $parentID, $childID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // ── Create a new Subscription ────────────────────────────────
    public function createSubscription($parentID, $childID, $planName, $basePrice, $billingCycle, $startDate, $dueDate) {
        $stmt = mysqli_prepare($this->conn,
            "INSERT INTO Subscription
                (parentID, childID, planName, basePrice, startDate, dueDate, status, billingCycle)
             VALUES (?, ?, ?, ?, ?, ?, 'Active', ?)");
        mysqli_stmt_bind_param($stmt, "iisdsss",
            $parentID, $childID, $planName, $basePrice, $startDate, $dueDate, $billingCycle);
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->conn);
    }

    // ── Create a Payment record ───────────────────────────────────
    public function createPayment($subscriptionID, $parentID, $amount, $gateway, $gatewayTxID, $lineItems) {
        $lineItemsJSON = json_encode($lineItems);
        $paidAt        = date('Y-m-d H:i:s');

        $stmt = mysqli_prepare($this->conn,
            "INSERT INTO Payment
                (subscriptionID, parentID, amount, gateway, gatewayTxID, status, paidAt, lineItems)
             VALUES (?, ?, ?, ?, ?, 'Paid', ?, ?)");
        mysqli_stmt_bind_param($stmt, "iidssss",
            $subscriptionID, $parentID, $amount, $gateway, $gatewayTxID, $paidAt, $lineItemsJSON);
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($this->conn);
    }

    // ── Get payment history for a parent ─────────────────────────
    public function getPaymentsByParent($parentID) {
        $stmt = mysqli_prepare($this->conn,
            "SELECT p.paymentID, p.amount, p.gateway, p.status, p.paidAt,
                    s.planName, s.billingCycle
             FROM Payment p
             JOIN Subscription s ON p.subscriptionID = s.subscriptionID
             WHERE p.parentID = ?
             ORDER BY p.paidAt DESC");
        mysqli_stmt_bind_param($stmt, "i", $parentID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
?>
