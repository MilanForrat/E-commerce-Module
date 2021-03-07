<?php

namespace App\Data;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

Class Cart
{
    private $session;

    /**
     * Je créer un construct afin d'avoir accès à ma $session dans toutes les fonctions de ma classe
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $cart = $this->session->get('cart');

        // si ma variable cart n'est pas vide notament pour tel id
        if(!empty($cart[$id]))
        {
            // si le produit existe déjà alors tu ajoutes une quantité
            $cart[$id]++;
        }else {
            // sinon c'est que la quantité est 1
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart');

        // unset removes key from an array
        unset($cart[$id]);

        // I need to reset the cart in order to update the cart session
        return $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        // vérifier si mon produit n'est pas égal à 1
        $cart = $this->session->get('cart', []);

        // si ma quantité est supérieure à 1 alors je peux retirer une quantité sinon...
        if($cart[$id] > 1){
            $cart[$id]--;
        }
        else{
            // je supprime mon produit
            unset($cart[$id]);
        }
        return $this->session->set('cart', $cart);
    }

    public function getCartInfos()
    {
        // j'initialise une variable qui va stocker les infos complètes de mon panier 
        $cartComplete = [];

        // je boucle sur ma variable pour obtenir les infos du panier ET du produit commandé (afin de récupéré toutes les infos de ce dernier : image, nom ...) => utile pour la template du panier
        //$this = $cart ici
        if($this->get()){
            foreach ($this->get() as $id => $quantity){
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

                // si un  utilisateur tente d'ajoute run produit via l'url, à la main alors que l'id de ce produit n'existe pas, je l'intercepte
                if(!$product_object){
                    $this->delete($id);
                    // je retire ce produit inexistant dans ma BDD et je dis à PHP de continuer
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity,
                ];
            }
        }
        return $cartComplete;
    }
}