<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('user', name: "user_")]

class UserController extends AbstractController
{

    #[Route('/new', name: 'new')]
    #[isGranted('ROLE_ADMIN')]
    public function userCreate(
        EntityManagerInterface $entityManager,
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ): Response{
        $hierarchy = $this->getParameter('security.role_hierarchy.roles');
        $roles = array();
        array_walk_recursive($hierarchy, function($role) use (&$roles) {
            $roleview = strtolower(preg_replace('/[_]/i', ' ', preg_replace('/^ROLE_/i','',$role)));
            $roles[$roleview] = $role;
        });

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class , ["label" => "E-mail", "required" => true])
            ->add('username' , TextType::class , ["label" => "Username"])
            ->add('password' , PasswordType::class , ["label" => "Password", "required" => true])
            ->add('conf' , PasswordType::class , ["label" => "Conferma Password", "required" => true])

            ->add('roles', ChoiceType::class, [
                "label" => "Ruoli",
                "choices" => $roles,
                "multiple" => false,
                "expanded" => false,
                "attr" => ["class" => "form-control"]
            ])

            ->add("submit", SubmitType::class, ["label" => "Crea"])
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fromData = $form->getData();
            $user = new User();

            if ($fromData['password'] === $fromData['conf']){
                $hashPsw = $passwordHasher->hashPassword($user, $fromData['password']);
            }else{
                $this->addFlash("danger", "le password devono corrispondere");
                return $this->redirectToRoute('user_new');
            }

            $user
                ->setEnabled(true)
                ->setCreatedBy($this->getUser())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUsername($fromData["username"])
                ->setEmail($fromData['email'])
                ->setRoles(in_array($fromData["roles"], $roles) ? array($fromData["roles"]) : array('ROLE_GUEST'))
                ->setPassword($hashPsw);

            $errors = $validator->validate($user);
            if(empty($errors->count())){
                $this->addFlash("success", "Hai creato l'utente: " . $user->getUsername() ?? $user->getEmail());
                $entityManager->persist($user);
                $entityManager->flush();
            }else{
                $this->addFlash("danger", "alcuni parametri sono sbagliati, " . $errors[0]->getMessage());
            }

            return $this->redirectToRoute('user_new');
        }
        return $this->render('main/new-user.twig', [
            'form' => $form->createView(),
        ]);

    }


    #[Route('/edit', name: 'edit')]
    #[isGranted('ROLE_GUEST')]
    public function userEdit(
        EntityManagerInterface $entityManager,
        Request $request,
        ValidatorInterface $validator): Response{

        $user = $this->getUser();

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class , ["label" => "E-mail", "required" => true])
            ->add('username' , TextType::class , ["label" => "Username", "required" => false])
            ->add("enabled", CheckboxType::class,["label" => "Abilitato?", 'required' => false])
            ->add("submit", SubmitType::class, ["label" => "Modifica"])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $fromData = $form->getData();
            $user
                ->setEnabled(true)
                ->setUsername($fromData["username"])
                ->setEmail($fromData['email'])
                ->setEnabled($fromData['enabled']);

            $errors = $validator->validate($user);
            if(empty($errors->count())){
//                    dd($user);
                $this->addFlash("success", "Hai modificato l'utente: " . $user->getUsername() ?? $user->getEmail());
                $entityManager->persist($user);
                $entityManager->flush();
            }else{
                $this->addFlash("danger", "alcuni parametri sono sbagliati, " . $errors[0]->getMessage());
            }

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('main/user-edit.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit-password', name: 'edit-psw')]
    #[isGranted('ROLE_USER')]
    public function userEditPassword(
        EntityManagerInterface $entityManager,
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher): Response{

        $user = $this->getUser();

        $form = $this->createFormBuilder()
            ->add('curPsw', PasswordType::class , ["label" => "Password Corrente", "required" => true])
            ->add('newPsw' , PasswordType::class , ["label" => "Nuova Password", "required" => true])
            ->add("confNewPsw", PasswordType::class,["label" => "Conferma Nuova Password", 'required' => true])
            ->add("submit", SubmitType::class, ["label" => "Modifica"])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $fromData = $form->getData();

            if ($passwordHasher->isPasswordValid($user, $fromData['curPsw']) && $fromData["newPsw"] === $fromData["confNewPsw"]){
                $hashPsw = $passwordHasher->hashPassword($user, $fromData['newPsw']);
                $user->setPassword($hashPsw);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash("success", "Hai modificato l'utente: " . $user->getUsername() ?? $user->getEmail());
            }else{
                $this->addFlash("danger", "alcuni parametri sono sbagliati");
            }

            return $this->redirectToRoute('user_edit-psw');
        }

        return $this->render('main/user-edit-psw.twig', [
            'form' => $form->createView()
        ]);
    }
}
