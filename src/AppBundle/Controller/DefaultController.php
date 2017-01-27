<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $client = $this->get('guzzle.client.marvel');
        $hash = $this->hashCreation();

        $response = $client->get('/v1/public/characters', [
            'query' => [
                'apikey' => '0d7e9cdff2b90438536e71566a4e0187',
                'hash' => $hash,
                'ts' => 1,
                'limit' => 22,
                'offset' => 100,
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $result['data']['results'],
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('default/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/character/show/{id}", name="show_character")
     */
    public function characterAction(Request $request, $id)
    {
        $client = $this->get('guzzle.client.marvel');
        $hash = $this->hashCreation();

        $response = $client->get('/v1/public/characters/'.$id, [
            'query' => [
                'apikey' => '0d7e9cdff2b90438536e71566a4e0187',
                'hash' => $hash,
                'ts' => 1,
                'limit' => 1,
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $this->render('character/show.html.twig', [
            'character' => $result['data']['results'][0],
        ]);
    }

    private function hashCreation()
    {
        $publicKey = $this->getParameter('marvel_api_public_key');
        $privateKey = $this->getParameter('marvel_api_private_key');

        return md5('1'.$privateKey.$publicKey);
    }
}
