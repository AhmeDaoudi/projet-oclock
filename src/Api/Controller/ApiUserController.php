<?php

namespace App\Api\Controller;

use App\Entity\User;
use App\Form\Api\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiUserController extends FOSRestController
{
    
    /**
     * @Rest\Post(path = "signup", name="user_new")
     * @ParamConverter("user", converter = "fos_rest.request_body", options = {"validator" = {"groups" = "create"}})
     */
    public function newUserAction(User $user, UserRepository $userRepository, Request $request, EntityManagerInterface $em, ConstraintViolationList $violations, UserPasswordEncoderInterface $encoder)
    {   
        if(count($violations)){
            
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $userEmail = $userRepository->findOneByEmail($request->request->get('email'));
        $email = $request->request->get('email');
        $userPseudo = $userRepository->findOneByPseudo($request->request->get('pseudo'));
        $pseudo = $request->request->get('pseudo');
        $password = $request->request->get('password');
        $confirmpassword = $request->request->get('confirmpassword');

        if($password != $confirmpassword){

            return $this->view('Mot de passe différent.', Response::HTTP_FORBIDDEN);

        } else if($password === null || $confirmpassword === null){

            return $this->view('Veuillez indiquer votre mot de passe.', Response::HTTP_FORBIDDEN);

        } else if($userEmail != null){

            return $this->view('L\'email est déjà prit.', Response::HTTP_FORBIDDEN);

        } else if($email === null){

            return $this->view('Veuillez indiquer votre email.', Response::HTTP_FORBIDDEN);

        } else if($userPseudo != null){

            return $this->view('Le pseudo est déjà prit.', Response::HTTP_FORBIDDEN);

        } else if($pseudo === null){

            return $this->view('Veuillez indiquer votre pseudo.', Response::HTTP_FORBIDDEN);
        }    

        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all());

        $hash = $encoder->encodePassword($user, $request->request->get('password'));
        $user->setPassword($hash);

        $em->persist($user);
        $em->flush();

        return $this->view($user, Response::HTTP_CREATED, [
            
            ]);
    }

    /**
     * @Rest\Put(path = "user/edit", name="user_edit")
     */
    public function editUserAction(TokenStorageInterface $token, Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {   
        
        $user = $token->getToken()->getUser();
        $oldPassword = $user->getPassword();

        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all());

        if(is_null($request->request->get('password'))){

            $hash = $oldPassword;

        } else {

            $hash = $encoder->encodePassword($user, $request->request->get('password'));
            $user->setPassword($hash);
        }

        $user->setUpdatedAt(new \DateTime());

        $em->flush();

        return $this->view('', Response::HTTP_OK, [
            
            ]);
    }

    /**
     * @Rest\Post(path = "user/profil", name="user_profil")
     */
    public function getUserAction(TokenStorageInterface $token, SerializerInterface $serializer)
    {   
        
        $user = $token->getToken()->getUser();

        $user = $serializer->serialize($user, 'json', [
            'groups' => 'profil_read',
        ]);

        return JsonResponse::fromJsonString($user);
    }
}