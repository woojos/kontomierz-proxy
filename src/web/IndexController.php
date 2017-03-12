<?php
namespace woojos\kontomierzproxy\web;

use GuzzleHttp\Client;
use Silex\Application;
use woojos\kontomierz\KontomierzClient;
use woojos\kontomierz\KontomierzClientException;
use woojos\kontomierz\Transaction;
use woojos\kontomierzproxy\KontomierzService;

/**
 * Class IndexController
 * @package woojos\kontomierzproxy\web
 */
class IndexController
{
    /** @var Application */
    private $app;
    /** @var int */
    private $rowsCount = 5;
    /** @var KontomierzService */
    private $kontomierzService;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->kontomierzService = new KontomierzService(
            new KontomierzClient(
                new Client(),
                $app['apiKey']
            )
        );
    }

    public function show()
    {
        $categories = $this->kontomierzService->getCategories();
        $wallets = $this->kontomierzService->getWallets();

        return $this->app->render(
            'index.twig',
            [
                'rowsCount' => $this->rowsCount,
                'categoriesJson' => $categories,
                'wallets' => $wallets,
            ]
        );
    }

    public function pushToKontomierz()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        $response = [];

        foreach ($data['expenses'] as $row) {

            preg_match('#\[(\d*)\]#', $row['category'], $matched);
            $categoryId = $matched[1];

            try {

                $this->kontomierzService->addTransaction(
                    new Transaction(
                        0,
                        $data['wallet'],
                        $categoryId,
                        -$row['amount'],
                        'PLN',
                        \DateTime::createFromFormat('Y-m-d', $data['data']),
                        $row['desc']
                    )
                );

                $row['status'] = 'ok';
                $response[] = $row;

            } catch (KontomierzClientException $e) {
                $row['status'] = 'failed';
                $response[] = $row;
            }
        }

        return json_encode($response);
    }

}