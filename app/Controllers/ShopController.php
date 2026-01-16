<?php

namespace App\Controllers;

use App\Models\Cart;

class ShopController extends \App\Core\Controller
{
    private Cart $cart;
    private array $products;
    private \App\Models\User $userModel;
    private \App\Models\PointsTransaction $pointsTransaction;

    public function __construct()
    {
        parent::__construct();
        $this->cart = new Cart();
        $this->userModel = new \App\Models\User();
        $this->pointsTransaction = new \App\Models\PointsTransaction();
        
        $this->products = [
            1 => [
                'id' => 1,
                'name' => 'Smartphone Premium',
                'price' => 799.99,
                'description' => 'Dernier modèle avec écran OLED et caméra 108MP',
                'icon' => '📱'
            ],
            2 => [
                'id' => 2,
                'name' => 'Casque Bluetooth',
                'price' => 129.99,
                'description' => 'Son haute fidélité avec réduction de bruit active',
                'icon' => '🎧'
            ],
            3 => [
                'id' => 3,
                'name' => 'Livre PHP',
                'price' => 49.99,
                'description' => 'Maîtrisez PHP 8 et les design patterns MVC',
                'icon' => '📚'
            ],
            4 => [
                'id' => 4,
                'name' => 'T-shirt ShopEasy',
                'price' => 24.99,
                'description' => 'T-shirt 100% coton bio avec logo ShopEasy',
                'icon' => '👕'
            ],
            5 => [
                'id' => 5,
                'name' => 'Sac à dos',
                'price' => 59.99,
                'description' => 'Sac à dos ergonomique avec compartiment laptop',
                'icon' => '🎒'
            ],
            6 => [
                'id' => 6,
                'name' => 'Tablette graphique',
                'price' => 299.99,
                'description' => 'Tablette professionnelle pour designers',
                'icon' => '🖊️'
            ]
        ];
    }

    private function getCommonData(): array
    {
        if (!isset($_SESSION['user_id'])) {
             // Redirect if not logged in is handled by views logic or routes usually, 
             // but here we just return safe default
             return ['cart_count' => 0];
        }

        $user = $this->userModel->findById($_SESSION['user_id']);

        return [
            'user' => $user, // Contains 'total_points' which maps to loyalty_points usage in views? View uses user.loyalty_points. 
            // DB column is total_points. I might need to map it or change view.
            // Let's alias it for safety.
            'current_user' => $user,
            'loyalty_points' => $user['total_points'] ?? 0, // Fallback
            'cart_count' => $this->cart->getCount()
        ];
    }
    
    // View variable adjustment:
    // Views use {{ user.loyalty_points }}. DB uses total_points.
    // I should create a mapped user array.
    private function getUserData(): array
    {
         if (!isset($_SESSION['user_id'])) return [];
         $user = $this->userModel->findById($_SESSION['user_id']);
         // Ad-hoc fix for property name mismatch
         $user['loyalty_points'] = $user['total_points'];
         return $user;
    }

    public function index(): void
    {
        $this->render('shop/index.html.twig', [
            'user' => $this->getUserData(),
            'cart_count' => $this->cart->getCount(),
            'products' => $this->products
        ]);
    }

    public function cart(): void
    {
        $this->render('shop/cart.html.twig', [
            'user' => $this->getUserData(),
            'cart_count' => $this->cart->getCount(),
            'cart_items' => $this->cart->getItems(),
            'cart_total' => $this->cart->getTotal(),
            'loyalty_points' => $this->cart->calculateLoyaltyPoints()
        ]);
    }

    public function addToCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /shop');
            exit;
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        if (isset($this->products[$productId]) && $quantity > 0) {
            $product = $this->products[$productId];
            $this->cart->addItem(
                $product['id'],
                $product['name'],
                $product['price'],
                $quantity
            );
        }

        header('Location: /shop/cart');
        exit;
    }

    public function updateCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /shop/cart');
            exit;
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 0);

        $this->cart->updateQuantity($productId, $quantity);

        header('Location: /shop/cart');
        exit;
    }

    public function removeFromCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /shop/cart');
            exit;
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $this->cart->removeItem($productId);

        header('Location: /shop/cart');
        exit;
    }

    public function checkout(): void
    {
        if ($this->cart->isEmpty()) {
            header('Location: /shop/cart');
            exit;
        }

        $checkoutError = $_SESSION['checkout_error'] ?? null;
        unset($_SESSION['checkout_error']);

        $this->render('shop/checkout.html.twig', [
            'user' => $this->getUserData(),
            'cart_count' => $this->cart->getCount(),
            'cart_items' => $this->cart->getItems(),
            'cart_total' => $this->cart->getTotal(),
            'loyalty_points' => $this->cart->calculateLoyaltyPoints(),
            'checkout_error' => $checkoutError
        ]);
    }

    public function processCheckout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /shop/checkout');
            exit;
        }

        if ($this->cart->isEmpty()) {
            header('Location: /shop/cart');
            exit;
        }
        
        if (!isset($_SESSION['user_id'])) {
             header('Location: /login');
             exit;
        }

        $cardName = trim($_POST['card_name'] ?? '');
        $cardNumber = trim($_POST['card_number'] ?? '');
        $cardExpiry = trim($_POST['card_expiry'] ?? '');
        $cardCvv = trim($_POST['card_cvv'] ?? '');

        if (empty($cardName) || empty($cardNumber) || empty($cardExpiry) || empty($cardCvv)) {
            $_SESSION['checkout_error'] = 'Veuillez remplir tous les champs.';
            header('Location: /shop/checkout');
            exit;
        }

        $cartTotal = $this->cart->getTotal();
        $pointsEarned = $this->cart->calculateLoyaltyPoints();
        $user = $this->getUserData();
        $previousPoints = $user['loyalty_points'];

        try {
            // Save to DB
            $this->pointsTransaction->recordTransaction(
                $_SESSION['user_id'],
                $pointsEarned,
                'EARNED',
                'Achat boutique - Commande #' . time()
            );
            
            // Get updated points
            $updatedUser = $this->getUserData();
            
             $_SESSION['last_purchase'] = [
                'items' => $this->cart->getItems(),
                'total' => $cartTotal,
                'points_earned' => $pointsEarned,
                'previous_points' => $previousPoints,
                'new_points' => $updatedUser['loyalty_points'],
                'date' => date('Y-m-d H:i:s')
            ];

            $this->cart->clear();

            header('Location: /shop/purchase-result');
            exit;

        } catch (\Exception $e) {
             $_SESSION['checkout_error'] = 'Erreur lors de la transaction: ' . $e->getMessage();
             header('Location: /shop/checkout');
             exit;
        }
    }

    public function purchaseResult(): void
    {
        if (!isset($_SESSION['last_purchase'])) {
            header('Location: /shop');
            exit;
        }

        $this->render('shop/purchase_result.html.twig', [
             'user' => $this->getUserData(),
             'cart_count' => 0,
             'purchase' => $_SESSION['last_purchase']
        ]);
    }
}
