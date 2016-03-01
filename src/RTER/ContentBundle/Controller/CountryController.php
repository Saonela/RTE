<?php
/**
 * Created by PhpStorm.
 * User: klaudijus
 * Date: 16.2.21
 * Time: 22.57
 */

namespace RTER\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;


/**
 * @Route("/content")
 */


class CountryController extends Controller
{
//    /**
//     * @Route("/")
//     */
//    public function indexAction()
//    {
//        return $this->render('RTERContentBundle:Default:continentList.html.twig');
//    }
//    /**
//     * @Route("/", name="homepage")
//     */
//    public function indexAction()
//    {
//        return $this->render('RTERContentBundle:Default:continentList.html.twig');
//    }

    /**
     * @Route("/{continent}", name="country_show")
     *
     */
    public function countryAction($continent)
    {
        $em = $this->getDoctrine()->getManager();

        $continentRepository = $em->getRepository('RTERContentBundle:Continent');
        $source = $continentRepository->findByName($continent);

        $countryRepository = $em->getRepository('RTERContentBundle:Country');
        $countries = $countryRepository->findByContinent($source);

        return $this->render('RTERContentBundle:Default:countryList.html.twig', array(
            'countries' => $countries,
        ));
    }

    /**
     * @Route("/{continent}/{country}", name="blog_list_show")
     *
     */
    public function blogAction($continent, $country)
    {
        $em = $this->getDoctrine()->getManager();

        $countryRepository = $em->getRepository('RTERContentBundle:Country');
        $countryObj = $countryRepository->findByName($country);

        $blogRepository = $em->getRepository('RTERContentBundle:BlogPost');
        $blogList = $blogRepository->findByCountry($countryObj);

        return $this->render('RTERContentBundle:Default:blogList.html.twig', array(
            'blogList' => $blogList,
        ));
    }

    /**
     * @Route("/{continent}/{country}/{id}", name="blog_show")
     *
     */
    public function contentAction($continent, $country, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $blogRepository = $em->getRepository('RTERContentBundle:BlogPost');
        $blogPost = $blogRepository->findOneById($id);
        if($blogPost == null)
        {
            return $this->render('RTERContentBundle:Default:error.html.twig');
        }

        return $this->render('RTERContentBundle:Default:blogPostContent.html.twig', array(
            'blogPost' => $blogPost,
        ));
    }
}
