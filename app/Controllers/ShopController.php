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
                'name' => 'Premium Smartphone',
                'price' => 999.99,
                'description' => 'Flagship device with 4K OLED display and pro camera system.',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=800&auto=format&fit=crop'
            ],
            2 => [
                'id' => 2,
                'name' => 'Wireless Noise-Canceling Headphones',
                'price' => 249.99,
                'description' => 'Immersive sound with industry-leading noise cancellation.',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop'
            ],
            3 => [
                'id' => 3,
                'name' => 'Smart Watch Series 7',
                'price' => 399.99,
                'description' => 'Advanced health tracking and always-on retina display.',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop'
            ],
            4 => [
                'id' => 4,
                'name' => 'Mechanical Gaming Keyboard',
                'price' => 129.99,
                'description' => 'RGB backlit keys with tactile mechanical switches.',
                'image' => 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?q=80&w=800&auto=format&fit=crop'
            ],
            5 => [
                'id' => 5,
                'name' => 'Ergonomic Office Chair',
                'price' => 299.99,
                'description' => 'Designed for comfort and productivity with lumbar support.',
                'image' => 'https://m.media-amazon.com/images/I/71BG5CaX8oL._AC_SL1500_.jpg'
            ],
            6 => [
                'id' => 6,
                'name' => 'Professional Camera Lens',
                'price' => 899.99,
                'description' => 'Sharp prime lens perfect for portraits and low light.',
                'image' => 'https://images.unsplash.com/photo-1617005082133-548c4dd27f35?q=80&w=800&auto=format&fit=crop'
            ],
            7 => [
                'id' => 7,
                'name' => 'Minimalist Backpack',
                'price' => 79.99,
                'description' => 'Water-resistant fabric with dedicated laptop compartment.',
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=800&auto=format&fit=crop'
            ],
            8 => [
                'id' => 8,
                'name' => '4K Action Camera',
                'price' => 349.99,
                'description' => 'Capture your adventures in stunning 4K resolution.',
                'image' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?q=80&w=800&auto=format&fit=crop'
            ],
            9 => [
                'id' => 9,
                'name' => 'Smart Home Speaker',
                'price' => 99.99,
                'description' => 'Voice-controlled assistant with filling room sound.',
                'image' => 'https://images.unsplash.com/photo-1589492477829-5e65395b66cc?q=80&w=800&auto=format&fit=crop'
            ],
            10 => [
                'id' => 10,
                'name' => 'Wireless Charging Pad',
                'price' => 39.99,
                'description' => 'Fast charging for all your Qi-enabled devices.',
                'image' => 'https://images.unsplash.com/photo-1618483109337-251916ee95d1?q=80&w=800&auto=format&fit=crop'
            ],
            11 => [
                'id' => 11,
                'name' => 'Designer Sunglasses',
                'price' => 159.99,
                'description' => 'Classic style with polarized UV protection lenses.',
                'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?q=80&w=800&auto=format&fit=crop'
            ],
            12 => [
                'id' => 12,
                'name' => 'Premium Coffee Maker',
                'price' => 199.99,
                'description' => 'Brew barista-quality coffee at home every morning.',
                'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=800&auto=format&fit=crop'
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
