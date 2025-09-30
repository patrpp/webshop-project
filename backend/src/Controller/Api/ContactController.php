<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'contact_send', methods: ['POST'])]
    public function sendContact(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $message = $data['message'] ?? '';

        if (!$name || !$email || !$message) {
            return new JsonResponse(['status' => 'error', 'message' => 'Minden mező kitöltése kötelező!'], 400);
        }

        $content = <<<EOD
Új üzenet érkezett a kapcsolati űrlapon keresztül:

Név: $name
Email: $email

Üzenet:
$message
EOD;

        $emailMessage = (new Email())
            ->from('webshop@yourdomain.com')   
            ->to('support@yourdomain.com')    
            ->replyTo($email)                  
            ->subject('Új kapcsolatfelvétel a weboldalon')
            ->text($content);

        try {
            $mailer->send($emailMessage);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Hiba történt az üzenet küldésekor.'], 500);
        }

        return new JsonResponse(['status' => 'ok']);
    }
}
