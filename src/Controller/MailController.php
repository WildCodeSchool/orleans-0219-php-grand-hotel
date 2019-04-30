<?php


namespace App\Controller;

class MailController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function swiftMailer()
    {

// Create the Transport
      /*  $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465))
            ->setUsername('legrandhotelorleans')
            ->setPassword('chocolats')
        ;

// Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

// Create a message
        $message = (new Swift_Message('Wonderful Subject'))
            ->setFrom(['john@doe.com' => 'John Doe'])
            ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
            ->setBody('Here is the message itself')
        ;

// Send the message
        $result = $mailer->send($message); */




        return $this->twig->render('MailController/index.html.twig');
    }
}
