<?php
// ══════════════════════════════════════════════════════════════════
//  controllers/PaymentController.php
//  Entry point called by main.js via fetch().
//  Validates input → calls PaymentModel → returns JSON response.
// ══════════════════════════════════════════════════════════════════

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// ── DB connection ─────────────────────────────────────────────────
// Connect to wellucation database
$conn = mysqli_connect("localhost", "root", "", "wellucation");

if (!$conn) {
    echo json_encode(["success" => false, "message" => "DB connection failed: " . mysqli_connect_error()]);
    exit;
}

// ── Load Model ────────────────────────────────────────────────────
require_once __DIR__ . '/../models/PaymentModel.php';
$model = new PaymentModel($conn);

// ── Route by action param ─────────────────────────────────────────
$action = $_GET['action'] ?? 'process';

switch ($action) {

    // ── POST: process a new payment ───────────────────────────────
    case 'process':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "message" => "POST required."]);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        // ── Required fields ───────────────────────────────────────
        $parentID    = isset($data['parentID'])    ? intval($data['parentID'])       : 0;
        $childID     = isset($data['childID'])     ? intval($data['childID'])        : 0;
        $gateway     = isset($data['gateway'])     ? trim($data['gateway'])          : '';
        $gatewayTxID = isset($data['gatewayTxID']) ? trim($data['gatewayTxID'])      : null;
        $lineItems   = isset($data['lineItems'])   ? $data['lineItems']              : [];

        // ── Validate gateway ──────────────────────────────────────
        $allowedGateways = ['Paymob', 'Fawry', 'ValU'];
        if (!in_array($gateway, $allowedGateways)) {
            echo json_encode(["success" => false, "message" => "Invalid payment gateway."]);
            exit;
        }

        // ── Validate parent & child ───────────────────────────────
        if ($parentID < 1 || $childID < 1) {
            echo json_encode(["success" => false, "message" => "Parent ID and Child ID are required."]);
            exit;
        }
        if (!$model->parentExists($parentID)) {
            echo json_encode(["success" => false, "message" => "Parent ID $parentID not found in database."]);
            exit;
        }
        if (!$model->childBelongsToParent($childID, $parentID)) {
            echo json_encode(["success" => false, "message" => "Child ID $childID does not belong to Parent ID $parentID."]);
            exit;
        }

        // ── Calculate total from lineItems ────────────────────────
        // lineItems example: [{"label":"Monthly Tuition","amount":850}, ...]
        $total = 0;
        foreach ($lineItems as $item) {
            $total += floatval($item['amount']);
        }
        if ($total <= 0) {
            echo json_encode(["success" => false, "message" => "Order total must be greater than zero."]);
            exit;
        }

        // ── Get or create subscription ────────────────────────────
        $subscription = $model->getActiveSubscription($parentID, $childID);

        if (!$subscription) {
            // No active subscription — create one from lineItems
            $planName     = isset($data['planName'])     ? trim($data['planName'])     : 'Basic';
            $billingCycle = isset($data['billingCycle']) ? trim($data['billingCycle']) : 'Monthly';
            $basePrice    = $total;
            $startDate    = date('Y-m-d');
            $dueDate      = ($billingCycle === 'Termly')
                ? date('Y-m-d', strtotime('+1 year'))
                : date('Y-m-d', strtotime('+1 month'));

            $subscriptionID = $model->createSubscription(
                $parentID, $childID, $planName, $basePrice, $billingCycle, $startDate, $dueDate
            );
        } else {
            $subscriptionID = $subscription['subscriptionID'];
        }

        // ── Save payment ──────────────────────────────────────────
        $paymentID = $model->createPayment(
            $subscriptionID, $parentID, $total, $gateway, $gatewayTxID, $lineItems
        );

        echo json_encode([
            "success"        => true,
            "message"        => "Payment of $" . number_format($total, 2) . " processed successfully!",
            "paymentID"      => $paymentID,
            "subscriptionID" => $subscriptionID,
            "amount"         => $total
        ]);
        break;

    // ── GET: fetch payment history for a parent ───────────────────
    case 'history':
        $parentID = isset($_GET['parentID']) ? intval($_GET['parentID']) : 0;
        if ($parentID < 1) {
            echo json_encode(["success" => false, "message" => "Valid parentID required."]);
            exit;
        }
        $payments = $model->getPaymentsByParent($parentID);
        echo json_encode(["success" => true, "payments" => $payments]);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Unknown action."]);
}

mysqli_close($conn);
?>
