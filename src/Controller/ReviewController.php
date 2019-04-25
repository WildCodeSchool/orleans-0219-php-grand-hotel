<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ReviewManager;

/**
 * Class ReviewController
 *
 */
class ReviewController extends AbstractController
{


    /**
     * Display item listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $reviewManager = new ReviewManager();
        $reviews = $reviewManager->selectAllOnLine();

        return $this->twig->render('Review/index.html.twig', ['reviews' => $reviews]);
    }


    public function addreview()
    {


        return $this->twig->render('Review/addreview.html.twig');
    }
}
