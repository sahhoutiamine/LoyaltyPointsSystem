<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

use App\Models\Cart;

class DashboardController extends Controller
{
    private $userModel;
    private $cart;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->cart = new Cart();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $user = $this->userModel->findById($_SESSION['user_id']);
        
        $transactionModel = new \App\Models\PointsTransaction();
        $transactions = $transactionModel->getUserTransactions($_SESSION['user_id']);

        $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'cart_count' => $this->cart->getCount(),
            'transactions' => $transactions
        ]);
    }
}
