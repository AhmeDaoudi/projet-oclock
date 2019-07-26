<?php

namespace App\Api\Controller;

use App\Entity\Score;
use App\Form\Api\ScoreType;
use App\Repository\GameRepository;
use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class ApiScoreController extends FOSRestController
{

   /**
     * @Rest\Post(path = "score/new", name = "score_new")
     * @ParamConverter("score", converter="fos_rest.request_body")
     */
    public function newScoreAction(Request $request, Score $score, ScoreRepository $scoreRepository, TokenStorageInterface $token, GameRepository $gameRepository, EntityManagerInterface $em, ConstraintViolationList $violations)
    {
        if(count($violations)){
            
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $user = $token->getToken()->getUser();
        $game = $gameRepository->findOneById($request->request->get('game', 'id'));

        // if($scoreRepository->findOneByUser($user) != null && $scoreRepository->findOneByGame($game) != null){

        //     return $this->view('403 Forbidden - Vous avez déjà voté pour ce jeu', Response::HTTP_FORBIDDEN);
        // }

        $score = new Score();
        $score->setUser($user);
        $score->setGame($game);

        $form = $this->createForm(ScoreType::class, $score);
        $form->submit($request->request->all());
        
        $em->persist($score);
        $em->flush();
        
        $average = ((int)$scoreRepository->averageScore($game)[0][1]);
        $game->setScore($average);
        $em->persist($game);
        $em->flush();

        return $this->view('', Response::HTTP_CREATED, [

            ]);
    }

    /**
     * @Rest\Put(path = "score/edit", name = "score_edit")
     */
    public function editScoreAction(Request $request, EntityManagerInterface $em, ScoreRepository $scoreRepository, GameRepository $gameRepository, TokenStorageInterface $token)
    {

        $user = $token->getToken()->getUser();
        $game = $gameRepository->findOneById($request->request->get('game', 'id'));
        $score = $scoreRepository->findOneById($request->request->get('id'));

        // if($score->getUser() != $user || $score->getGame() != $game){

        //     return $this->view('403 Forbidden - Ce vote ne vous appartient pas.', Response::HTTP_FORBIDDEN);
        // }
        
        $form = $this->createForm(ScoreType::class, $score);
        $form->submit($request->request->all());
        $score->setUpdatedAt(new \DateTime());

        
        $em->flush();
        
        $game->setScore((int)$scoreRepository->averageScore($game)[0][1]);
        $game->setUpdatedAt(new \DateTime());
        $em->flush();

        return $this->view('', Response::HTTP_OK, [
            
            ]);
    }

    /**
     * @Rest\Delete(path = "score/delete", name = "score_delete")
     */
    public function deleteScoreAction(Request $request, EntityManagerInterface $em, ScoreRepository $scoreRepository, GameRepository $gameRepository, TokenStorageInterface $token)
    {

        $user = $token->getToken()->getUser();
        $score = $scoreRepository->findOneById($request->request->get('id'));
        
        // if($score->getUser() != $user || $score->getGame() != $game){
            
            //     return $this->view('403 Forbidden - Ce vote ne vous appartient pas.', Response::HTTP_FORBIDDEN);
            // }
            
        $em->remove($score);
        $em->flush();
            
        $game = $gameRepository->findOneById($request->request->get('game', 'id'));
        $game->setScore((int)$scoreRepository->averageScore($game)[0][1]);
        $em->flush();


        return $this->view('', Response::HTTP_OK, [
            
            ]);
    }
}
