<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ItemManager;
use App\Model\ReviewManager;
use App\Services\CleanForm;

/**
 * Class ReviewController
 *
 */
class ReviewController extends AbstractController
{
    const FORMRULES = [
        "nameMaxCharacters" => 25,
        "reviewMaxCharacters" => 500,
        "minimumGrade" => 1,
        "maximumGrade" => 5];

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

        return $this->twig->render('Review/index.html.twig', ['reviews' => $reviews, 'rules' => self::FORMRULES]);
    }


    public function addreview()
    {
        $errors = [];
        $postData = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;
            foreach ($postData as $datum) {
                trim($datum);
                $cleanForm = new CleanForm();
                foreach ($postData as $key => $rubric) {
                    $errors = $cleanForm->checkIfEmpty($rubric, $errors, $key);
                }
                $errors =
                    $cleanForm->checkMaxLength(
                        $postData['name'],
                        $errors,
                        self::FORMRULES['nameMaxCharacters'],
                        'name'
                    );
                $errors = $cleanForm->checkMaxLength(
                    $postData['review'],
                    $errors,
                    self::FORMRULES['reviewMaxCharacters'],
                    'review'
                );
                $errors = $cleanForm->checkGrade(
                    $postData['grade'],
                    $errors,
                    self::FORMRULES['minimumGrade'],
                    self::FORMRULES['maximumGrade'],
                    'grade'
                );
            }
            if ((empty($errors) && (!empty($postData)))) {
                $ReviewManager = new ReviewManager();
                $ReviewManager->insert($postData);
            }
        }
        return $this->twig->render(
            'Review/addreview.html.twig',
            ['postdata' => $postData, 'errors' => $errors, 'rules' => self::FORMRULES]
        );
    }
}
