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
        $securityAuthorizationChecker = $this->container->get('security.authorization_checker');
        if ($securityAuthorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $blogPosts = $em->getRepository('RTERContentBundle:BlogPost')->findByUser($user);
            $countries = $em->getRepository('RTERContentBundle:BlogPost')->findDistinctCountries($user);


            return $this->render('blogpost/index.html.twig', array(
                'blogPosts' => $blogPosts,
                'blogs' => $countries
            ));
        }
        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * Creates a new BlogPost entity.
     *
     * @Route("/new", name="blogpost_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) //galbut verta formos atvaizda iskelt nuo jos apdorojimo
    {
        $securityAuthorizationChecker = $this->container->get('security.authorization_checker');
        if ($securityAuthorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $blogPost = new BlogPost();
            $form = $this->createForm('RTER\ContentBundle\Form\BlogPostType', $blogPost);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $user = $this->container->get('security.token_storage')->getToken()->getUser();
                $blogPost->setUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($blogPost);
                $em->flush();


                return $this->redirectToRoute('blog_show', array(
                    'continent' => $blogPost->getCountry()->getContinent()->getName(),
                    'country' => $blogPost->getCountry()->getName(),
                    'id' => $blogPost->getId()
                ));
            }

            return $this->render('blogpost/new.html.twig', array(
                'blogPost' => $blogPost,
                'form' => $form->createView(),
            ));
        }
        return $this->redirectToRoute('fos_user_security_login');
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
            'form' => $editForm->createView(),
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

    /**
     * Displays a form to edit an existing BlogPost entity.
     *
     * @Route("/search/", name="blogpost_search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $q = $request->query->get('q');
        $q = strtolower($q);

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('RTERContentBundle:BlogPost');

        $query = $repo->createQueryBuilder('bp')
            ->select('bp')
            ->leftJoin('bp.country', 'co', 'WITH', 'co = bp.country')
            ->where("bp.location LIKE :q")
            ->orWhere("co.name LIKE :q")
            ->setParameter('q', '%'.$q.'%')
            ->getQuery();

        $results = $query->getResult();



        return $this->render('blogpost/searchResults.html.twig', array(
            'results' => $results,
        ));
    }
}