<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\Tag;
use ReflectionException;

class TagController extends AbstractController
{
    /**
     * @throws ReflectionException
     */
    public function index(): string
    {
        $this->denyUnlessGranted('ROLE_USER');
        $tags = $this->getRepository(Tag::class)->findBy([
        'user' => $this->session->get('user'),
    ]);
        return $this->render('tag/index.html.twig',[
            'flash' => $this->getFlash(),
            'tags' => $tags,
            'title' => 'TMA - Meine Tags'
        ]);
    }

    /**
     * @throws ReflectionException
     */
    public function new(): string
    {
        $this->denyUnlessGranted('ROLE_USER');
        if($this->request->isPostRequest() and $this->request->isFormSubmitted())
        {
           $tag = new Tag();
           $tag->setName($this->request->post('name'));
           $tag->setUser($this->session->get('user'));
           $em = $this->getEntityManager();
           $em->persist($tag);
           $this->setFlash(202);
           $this->redirect(302,'tag');
        }

        return $this->render('tag/new.html.twig',[
            'flash' => $this->getFlash(),
            'title' => 'TMA - Neuen Tag erstellen'
        ]);
    }

    /**
     * @throws ReflectionException
     */
    public function delete(): void
    {
        $this->denyUnlessGranted('ROLE_USER');
        if($this->request->isPostRequest() and $this->request->isFormSubmitted())
        {
            $tags = $_POST['tags'];
            $tagRepository = $this->getRepository(Tag::class);

            if($tags)
            {
                foreach ($tags as $key => $value)
                {
                    $tag = $tagRepository->findOneBy(['id' => $value,'user' => $this->session->get('user')]);
                    if($tag)
                    {
                        $tag = new Tag();
                        $em = $this->getEntityManager()->remove($tag,$value);
                        $this->setFlash(200);

                    } else {
                        $this->setFlash(411,'danger');
                    }
                }
            } else {
                $this->setFlash(402,'warning');
            }
            $this->redirect(302,'tag');
        }
        $this->setFlash(401,'danger');
        $this->redirect(302,'tag');
    }
}