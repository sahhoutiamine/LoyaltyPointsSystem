<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Models\User;
use App\Core\Config\Database;

try {
    echo "Starting User Model Tests...\n\n";

    // 1. Setup
    // Database connection is now handled internally by User model, 
    // but we might want to check connection existence or just rely on User model.
    // $db = Database::getInstance()->getConnection(); 
    $userModel = new User();
    $testEmail = "test_" . time() . "@example.com";
    $testPassword = "password123";
    $testName = "Test User " . time();

    // 2. Test emailExists (Should be false)
    echo "2. Testing emailExists (new email)...\n";
    if (!$userModel->emailExists($testEmail)) {
        echo "[PASS] Email does not exist.\n";
    } else {
        echo "[FAIL] Email unexpectedly exists.\n";
    }

    // 3. Test create
    echo "3. Testing create...\n";
    $userId = $userModel->create([
        'email' => $testEmail,
        'password' => $testPassword,
        'name' => $testName
    ]);
    if ($userId) {
        echo "[PASS] User created with ID: $userId\n";
    } else {
        echo "[FAIL] Failed to create user.\n";
        exit;
    }

    // 4. Test emailExists (Should be true)
    echo "4. Testing emailExists (existing email)...\n";
    if ($userModel->emailExists($testEmail)) {
        echo "[PASS] Email exists.\n";
    } else {
        echo "[FAIL] Email should exist but doesnt.\n";
    }

    // 5. Test findById
    echo "5. Testing findById...\n";
    $userById = $userModel->findById($userId);
    if ($userById && $userById['email'] === $testEmail) {
        echo "[PASS] User found by ID.\n";
    } else {
        echo "[FAIL] User not found by ID or data mismatch.\n";
    }

    // 6. Test findByEmail
    echo "6. Testing findByEmail...\n";
    $userByEmail = $userModel->findByEmail($testEmail);
    if ($userByEmail && $userByEmail['id'] == $userId) {
        echo "[PASS] User found by Email.\n";
    } else {
        echo "[FAIL] User not found by Email or ID mismatch.\n";
    }

    // 7. Test verifyPassword
    echo "7. Testing verifyPassword...\n";
    if ($userModel->verifyPassword($testPassword, $userById['password_hash'])) {
        echo "[PASS] Password verified correctly.\n";
    } else {
        echo "[FAIL] Password verification failed.\n";
    }
    
    if (!$userModel->verifyPassword("wrongpassword", $userById['password_hash'])) {
        echo "[PASS] Wrong password rejected correctly.\n";
    } else {
        echo "[FAIL] Wrong password was accepted.\n";
    }

    // 8. Test updateTotalPoints
    echo "8. Testing updateTotalPoints...\n";
    $newPoints = 100;
    if ($userModel->updateTotalPoints($userId, $newPoints)) {
        $updatedUser = $userModel->findById($userId);
        if ($updatedUser['total_points'] == $newPoints) {
            echo "[PASS] Points updated correctly.\n";
        } else {
            echo "[FAIL] Points update reported success but value didn't change (Value: {$updatedUser['total_points']}).\n";
        }
    } else {
        echo "[FAIL] updateTotalPoints return false.\n";
    }

    echo "\nAll Tests Completed.\n";

} catch (Exception $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
