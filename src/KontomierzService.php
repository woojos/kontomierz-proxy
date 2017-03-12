<?php
namespace woojos\kontomierzproxy;

use woojos\kontomierz\Category;
use woojos\kontomierz\CategoryGroup;
use woojos\kontomierz\KontomierzClient;
use woojos\kontomierz\Transaction;
use woojos\kontomierz\UserAccount;

/**
 * Class KontomierzService
 * @package woojos\kontomierzproxy
 */
class KontomierzService
{
    /** @var KontomierzClient */
    private $kontomierzClient;

    /**
     * KontomierzService constructor.
     * @param KontomierzClient $kontomierzClient
     */
    public function __construct(KontomierzClient $kontomierzClient)
    {
        $this->kontomierzClient = $kontomierzClient;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        $categories = [];
        $groups = $this->kontomierzClient->getCategories();

        /** @var CategoryGroup $categoryGroup */
        foreach ($groups as $categoryGroup)
        {
            $categoriesObjects = $categoryGroup->getCategories();
            /** @var Category $category */
            foreach ($categoriesObjects as $category) {
                $categories[] = [
                    'id' => $category->getId(),
                    'name' => $category->getName() . ' ['.$category->getId().'] ',
                ];
            }
        }

        return $categories;
    }

    /**
     * @return array
     */
    public function getWallets()
    {
        $wallets = $this->kontomierzClient->getUserAccountList();
        $toReturn = [];

        /** @var UserAccount $wallet */
        foreach ($wallets as $wallet) {
            $toReturn[] = [
                'id' => $wallet->getId(),
                'name' => $wallet->getDisplayName(),
            ];
        }

        return $toReturn;
    }

    /**
     * @param Transaction $transaction
     * @return Transaction
     */
    public function addTransaction(Transaction $transaction)
    {
        return $this->kontomierzClient->createTransaction($transaction);
    }

}