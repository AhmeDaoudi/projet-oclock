<?php

namespace App\Api\Controller;

use App\Entity\State;
use App\Form\Api\StateType;
use App\Repository\GameRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiStateController extends FOSRestController
{

    /**
     * @Rest\Post(path = "game/state/add", name="state_add")
     * @ParamConverter("state", converter = "fos_rest.request_body")
     */
    public function addStateGameListAction(State $state, StateRepository $stateRepository, GameRepository $gameRepository, TokenStorageInterface $token, Request $request, EntityManagerInterface $em)
    {   
        $user = $token->getToken()->getUser();
        $game = $gameRepository->findOneById($request->request->get('game', 'id'));

        // if($stateRepository->findOneByUser($user) != null && $stateRepository->findOneByGame($game) != null){

        //     return $this->view('403 Forbidden - Vous avez déjà ajouté ce jeu dans votre liste.', Response::HTTP_FORBIDDEN);
        // }
        
        $state = new State();
        $state->setGame($game);
        $state->setUser($user);

        $form = $this->createForm(StateType::class, $state);
        $form->submit($request->request->all());

        $em->persist($state);
        $em->flush();

        return $this->view('', Response::HTTP_CREATED, [
            
            ]);
    }

    /**
     * @Rest\Put(path = "game/state/edit", name="state_edit")
     */
    public function editStateGameListAction(StateRepository $stateRepository, GameRepository $gameRepository, TokenStorageInterface $token, Request $request, EntityManagerInterface $em)
    {   
        $user = $token->getToken()->getUser();
        $game = $gameRepository->findOneById($request->request->get('game', 'id'));
        $state = $stateRepository->findOneById($request->request->get('id'));

        // if($state->getUser() != $user || $state->getGame() != $game){

        //     return $this->view('403 Forbidden - Cette liste ne vous appartient pas.', Response::HTTP_FORBIDDEN);
        // }

        $form = $this->createForm(StateType::class, $state);
        $form->submit($request->request->all());

        $em->flush();

        return $this->view('', Response::HTTP_OK, [
            
            ]);
    }

    /**
     * @Rest\Delete(path = "game/state/delete", name="state_delete")
     */
    public function deleteStateGameListAction(StateRepository $stateRepository, GameRepository $gameRepository, TokenStorageInterface $token, Request $request, EntityManagerInterface $em)
    {   
        $user = $token->getToken()->getUser();
        $game = $gameRepository->findOneById($request->request->get('game', 'id'));
        $state = $stateRepository->findOneById($request->request->get('id'));

        // if($state->getUser() != $user || $state->getGame() != $game){

        //     return $this->view('403 Forbidden - Cette liste ne vous appartient pas.', Response::HTTP_FORBIDDEN);
        // }

        $em->remove($state);
        $em->flush();

        return $this->view('', Response::HTTP_OK, [
            
            ]);
    }

    /**
     * @Rest\Get(path = "game/list/have", name = "state_games_have")
     */ 
    public function getGamesListHave(StateRepository $stateRepository, TokenStorageInterface $token, GameRepository $gameRepository,Request $request, SerializerInterface $serializer){

        $user = $token->getToken()->getUser();
        $games = $stateRepository->findGamesListByStatus($user, 'have');

        $gamesListHave = $serializer->serialize($games, 'json', [
            'groups' => 'status_read',
        ]);

        return JsonResponse::fromJsonString($gamesListHave);
    }

    /**
     * @Rest\Get(path = "game/list/want", name = "state_games_want")
     */ 
    public function getGamesListWant(StateRepository $stateRepository, TokenStorageInterface $token, SerializerInterface $serializer){

        $user = $token->getToken()->getUser();
        $games = $stateRepository->findGamesListByStatus($user, 'want');

        $gamesListWant = $serializer->serialize($games, 'json', [
            'groups' => 'status_read',
        ]);

        return JsonResponse::fromJsonString($gamesListWant);
    }

    /**
     * @Rest\Get(path = "game/list/waiting", name = "state_games_waiting")
     */ 
    public function getGamesListWaiting(StateRepository $stateRepository, TokenStorageInterface $token, SerializerInterface $serializer){

        $user = $token->getToken()->getUser();
        $games = $stateRepository->findGamesListByStatus($user, 'waiting');

        $gamesListWaiting = $serializer->serialize($games, 'json', [
            'groups' => 'status_read',
        ]);

        return JsonResponse::fromJsonString($gamesListWaiting);
    }

    /**
     * @Rest\Get(path = "game/{id}/state", name = "state_game", requirements = {"id" = "\d+"})
     */ 
    public function getGameState($id, StateRepository $stateRepository, TokenStorageInterface $token, GameRepository $gameRepository, SerializerInterface $serializer){

        $user = $token->getToken()->getUser();
        $userId = $user->getId();

        $game = $gameRepository->findOneById($id);
        $releasedAt = $game->getReleasedAt();
        $now = new \DateTime('now');

        if($releasedAt > $now){

            $games = $stateRepository->findGameState($userId, $id);
            $state = ["availability" => "unavailable", "info" => $games];

            $gamesListWaiting = $serializer->serialize($state, 'json', [
                'groups' => 'status_read',
            ]);
    
            return JsonResponse::fromJsonString($gamesListWaiting);

        }

        $games = $stateRepository->findGameState($userId, $id);
        $state = ["availability" => "available", "info" => $games];

        $gamesListWaiting = $serializer->serialize($state, 'json', [
            'groups' => 'status_read',
        ]);

        return JsonResponse::fromJsonString($gamesListWaiting);
    }
}
