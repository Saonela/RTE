<?php

namespace RTER\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RTER\ContentBundle\Entity\BlogPost;
use RTER\ContentBundle\Form\BlogPostType;

/**
 * BlogPost controller.
 *
 * @Route("/blogpost")
 */
class BlogPostController extends Controller
{
    /**
     * Lists all BlogPost entities.
     *
     * @Route("/", name="blogpost_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $blogPosts = $em->getRepository('RTERContentBundle:BlogPost')->findAll();

        return $this->render('blogpost/index.html.twig', array(
            'blogPosts' => $blogPosts,
        ));
    }

    /**
     * Creates a new BlogPost entity.
     *
     * @Route("/new", name="blogpost_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $blogPost = new BlogPost();
        $form = $this->createForm('RTER\ContentBundle\Form\BlogPostType', $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $blogPost->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($blogPost);
            $em->flush();

            return $this->redirectToRoute('blog_show', array('continent' => $blogPost->getCountry()->getContinent(),
            'country' => $blogPost->getCountry()->getName(),
            'id' => $blogPost->getId()
            ));
        }

        return $this->render('blogpost/new.html.twig', array(
            'blogPost' => $blogPost,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a BlogPost entity.
     *
     * @Route("/{id}", name="blogpost_show")
     * @Method("GET")
     */
    public function showAction(BlogPost $blogPost)
    {
        $deleteForm = $this->createDeleteForm($blogPost);

        return $this->render('blogpost/show.html.twig', array(
            'blogPost' => $blogPost,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing BlogPost entity.
     *
     * @Route("/{id}/edit", name="blogpost_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, BlogPost $blogPost)
    {
        $deleteForm = $this->createDeleteForm($blogPost);
        $editForm = $this->createForm('RTER\ContentBundle\Form\BlogPostType', $blogPost);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blogPost);
            $em->flush();

            return $this->redirectToRoute('blogpost_edit', array('id' => $blogPost->getId()));
        }

        return $this->render('blogpost/edit.html.twig', array(
            'blogPost' => $blogPost,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a BlogPost entity.
     *
     * @Route("/{id}", name="blogpost_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, BlogPost $blogPost)
    {
        $form = $this->createDeleteForm($blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blogPost);
            $em->flush();
        }

        return $this->redirectToRoute('blogpost_index');
    }

    /**
     * Creates a form to delete a BlogPost entity.
     *
     * @param BlogPost $blogPost The BlogPost entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(BlogPost $blogPost)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blogpost_delete', array('id' => $blogPost->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}