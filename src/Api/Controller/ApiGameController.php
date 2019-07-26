<?php

namespace App\Api\Controller;

use App\Repository\GameRepository;
use App\Repository\ScoreRepository;
use App\Repository\StateRepository;
use App\Repository\CommentRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiGameController extends FOSRestController
{

    /**
     * @Rest\Get(path = "game/list", name = "games_list")
     */
    public function getGamesAction(GameRepository $gameRepository, SerializerInterface $serializer)
    {   
        
        $games = $gameRepository->findAllGames();

        $allGames = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);
    
       return JsonResponse::fromJsonString($allGames);
    }

    /**
     * @Rest\Get(path = "game/{id}", name = "game_show", requirements = {"id" = "\d+"})
     */
    public function getGameAction($id, GameRepository $gameRepository, SerializerInterface $serializer)
    {   

        $game = $gameRepository->findOneById($id);

        $showGame = $serializer->serialize($game, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($showGame);
    }

    /**
     * @Rest\Get(path = "game/search", name = "game_search")
     */
    public function getSearchGamesAction(GameRepository $gameRepository, Request $request, SerializerInterface $serializer)
    {   

        $games = $gameRepository->findGames($request->query->get('name'));

        $allGames = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);
    
       return JsonResponse::fromJsonString($allGames);
    }

    /**
     * @Rest\Get(path = "game/{id}/edit", name = "game_edit", requirements = {"id" = "\d+"})
     */ 
    public function getGameEdit($id, ScoreRepository $scoreRepository, StateRepository $stateRepository, CommentRepository $commentRepository, GameRepository $gameRepository, Request $request, SerializerInterface $serializer, TokenStorageInterface $token){

        $user = $token->getToken()->getUser();
        $game = $gameRepository->findOneById($id);

        $comment = $commentRepository->findGameInfo($user, $game);
        $score = $scoreRepository->findGameInfo($user, $game);
        $state = $stateRepository->findGameState($user, $game);

        if ($score == null && $comment == null){

            $game = ["game" => $game, "info" => ["state" => $state, "comment" => null, "score" => null]];

            $gamesListWaiting = $serializer->serialize($game, 'json', [
                'groups' => 'game_info',
            ]);
    
            return JsonResponse::fromJsonString($gamesListWaiting);
            
        } else if($comment == null){

            $game = ["game" => $game, "info" => ["state" => $state, "comment" => null, "score" => $score]];

            $gamesListWaiting = $serializer->serialize($game, 'json', [
                'groups' => 'game_info',
            ]);
    
            return JsonResponse::fromJsonString($gamesListWaiting);

        } else if ($score == null){

            $game = ["game" => $game, "info" => ["state" => $state, "comment" => $comment, "score" => null]];

            $gamesListWaiting = $serializer->serialize($game, 'json', [
                'groups' => 'game_info',
            ]);
    
            return JsonResponse::fromJsonString($gamesListWaiting);

        }

        $game = ["game" => $game, "info" => ["state" => $state, "comment" => $comment, "score" => $score]];

        $gamesListWaiting = $serializer->serialize($game, 'json', [
            'groups' => 'game_info',
        ]);

        return JsonResponse::fromJsonString($gamesListWaiting);
    }

    /**
     * @Rest\Get(path = "game/list/nextmonth", name = "games_next_month")
     */
    public function getNextMonthGamesAction(GameRepository $gameRepository, SerializerInterface $serializer)
    {

        $games = $gameRepository->findNextMonthGames();

        $nextMonthGames = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($nextMonthGames);
    }

    /**
     * @Rest\Get(path = "game/list/lastmonth", name = "games_last_month")
     */
    public function getLastMonthGamesAction(GameRepository $gameRepository, SerializerInterface $serializer)
    {

        $games = $gameRepository->findLastMonthGames();

        $lastMonthGames = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($lastMonthGames);
    }

    /**
     * @Rest\Get(path = "game/list/random", name = "games_random")
     */ 
    public function getRandomGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findRandomGamesList();

        $randomGamesList = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($randomGamesList);
    }

    /**
     * @Rest\Get(path = "game/list/bestever", name = "games_best_ever")
     */ 
    public function getBestEverGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByBestScore();

        $bestEverGamesList = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($bestEverGamesList);
    }

    /**
     * @Rest\Get(path = "game/list/worstever", name = "games_worst_ever")
     */ 
    public function getWorstEverGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByWorstScore();

        $worstEverGamesList = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($worstEverGamesList);
    }


    /**
     * @Rest\Get(path = "game/list/bestlastyear", name = "games_best_last_year")
     */ 
    public function getBestLastYearGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByBestLastYearScore();

        $bestGamesLastYear = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($bestGamesLastYear);
    }

    /**
     * @Rest\Get(path = "game/list/worstlastyear", name = "games_worst_last_year")
     */ 
    public function getWorstLastYearGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByWorstLastYearScore();

        $worstGamesLastYear = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($worstGamesLastYear);
    }

    /**
     * @Rest\Get(path = "game/list/bestlastmonth", name = "games_best_last_month")
     */ 
    public function getBestLastMonthGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByBestLastMonthScore();

        $bestGamesLastMonth = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($bestGamesLastMonth);
    }

    /**
     * @Rest\Get(path = "game/list/worstlastmonth", name = "games_worst_last_month")
     */ 
    public function getWorstLastMonthGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByWorstLastMonthScore();

        $worstGamesLastMonth = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

       return JsonResponse::fromJsonString($worstGamesLastMonth);
    }

    /**
     * @Rest\Get(path = "game/list/bestlastweek", name = "games_best_last_week")
     */ 
    public function getBestLastWeekGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByBestLastWeekScore();

        $bestGamesLastWeek = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

       return JsonResponse::fromJsonString($bestGamesLastWeek);
    }

    /**
     * @Rest\Get(path = "game/list/worstlastweek", name = "games_worst_last_week")
     */ 
    public function getWorstLastWeekGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByWorstLastWeekScore();

        $worstGamesLastWeek = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

       return JsonResponse::fromJsonString($worstGamesLastWeek);
    }
    
    /**
     * @Rest\Get(path = "game/list/bestyear", name = "games_best_year")
     */ 
    public function getBestYearGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByBestYearScore();

        $bestGamesYear = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($bestGamesYear);
    }

    /**
     * @Rest\Get(path = "game/list/worstyear", name = "games_worst_year")
     */ 
    public function getWorstYearGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByWorstYearScore();

        $worstGamesYear = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($worstGamesYear);
    }

    /**
     * @Rest\Get(path = "game/list/bestmonth", name = "games_best_month")
     */ 
    public function getBestMonthGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByBestMonthScore();

        $bestGamesMonth = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($bestGamesMonth);
    }

    /**
     * @Rest\Get(path = "game/list/worstmonth", name = "games_worst_month")
     */ 
    public function getWorstMonthGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByWorstMonthScore();

        $worstGamesMonth = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($worstGamesMonth);
    }

    /**
     * @Rest\Get(path = "game/list/bestweek", name = "games_best_week")
     */ 
    public function getBestWeekGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByBestWeekScore();

        $bestGamesWeek = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($bestGamesWeek);
    }

    /**
     * @Rest\Get(path = "game/list/worstweek", name = "games_worst_week")
     */ 
    public function getWorstWeekGamesList(GameRepository $gameRepository, SerializerInterface $serializer){

        $games = $gameRepository->findGamesByWorstWeekScore();

        $worstGamesWeek = $serializer->serialize($games, 'json', [
            'groups' => 'game_read',
        ]);

        return JsonResponse::fromJsonString($worstGamesWeek);
    }
}