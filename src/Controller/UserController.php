<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/users', name: "user_list")]
    public function listUsers()
    {
        return $this->render('user/list.html.twig',
            ['users' => $this->getDoctrine()->getRepository(User::class)->findAll()]);
    }

    #[Route('/users/create', name: "user_create")]
    public function create(Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $form->get('password')->getData();
            $encodedPassword = $passwordEncoder->hashPassword($user, $password);
            $user->setPassword($encodedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{id}/edit', name: "user_edit")]
    public function editUser(User $user, Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $password = $form->get('password')->getData();
            $encodedPassword = $passwordEncoder->hashPassword($user, $password);
            $user->setPassword($encodedPassword);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
