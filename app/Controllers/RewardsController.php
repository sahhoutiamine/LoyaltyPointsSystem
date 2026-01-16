<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Reward;
use App\Models\User;
use App\Models\PointsTransaction;

class RewardsController extends Controller
{
    private $rewardModel;
    private $userModel;
    private $pointsTransaction;

    public function __construct()
    {
        parent::__construct();
        $this->rewardModel = new Reward();
        $this->userModel = new User();
        $this->pointsTransaction = new PointsTransaction();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $rewards = $this->rewardModel->findAll();
        // If empty, add some default rewards for demo purposes
        if (empty($rewards)) {
            $this->seedRewards();
            $rewards = $this->rewardModel->findAll();
        }

        $user = $this->userModel->findById($_SESSION['user_id']);

        $this->render('rewards/index.html.twig', [
            'rewards' => $rewards,
            'user' => $user,
            'flash_success' => $_SESSION['flash_success'] ?? null,
            'flash_error' => $_SESSION['flash_error'] ?? null
        ]);

        // Clear flash messages
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
    }

    public function redeem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/rewards');
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $rewardId = $_POST['reward_id'] ?? 0;
        $reward = $this->rewardModel->findById($rewardId);

        if (!$reward) {
             $_SESSION['flash_error'] = "Récompense introuvable.";
             $this->redirect('/rewards');
        }

        try {
            $this->pointsTransaction->recordTransaction(
                $_SESSION['user_id'],
                $reward['points_required'],
                'SPENT',
                'Échange récompense: ' . $reward['name']
            );

            $_SESSION['flash_success'] = "Félicitations ! Vous avez obtenu : " . $reward['name'];
            $this->redirect('/rewards');

        } catch(\Exception $e) {
            $_SESSION['flash_error'] = "Erreur: " . $e->getMessage(); // Will show 'Insufficient points'
            $this->redirect('/rewards');
        }
    }

    private function seedRewards()
    {
        $seeds = [
            ['name' => 'Carte Cadeau 10$', 'description' => 'Un bon d\'achat de 10$ valable sur tout le site', 'points_required' => 100, 'stock' => -1],
            ['name' => 'Livraison Gratuite', 'description' => 'Frais de port offerts sur votre prochaine commande', 'points_required' => 50, 'stock' => -1],
            ['name' => 'T-shirt Exclusif', 'description' => 'T-shirt collector LoyaltySystem', 'points_required' => 500, 'stock' => 50],
            ['name' => 'Session Coaching 1h', 'description' => 'Coaching privé avec un expert', 'points_required' => 1000, 'stock' => 5]
        ];

        foreach ($seeds as $seed) {
            $this->rewardModel->create($seed);
        }
    }
}
