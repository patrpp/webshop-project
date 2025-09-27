<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/api/order', name: 'order_send', methods: ['POST'])]
    public function sendOrder(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? '';
        $zip = $data['zip'] ?? '';
        $city = $data['city'] ?? '';
        $address = $data['address'] ?? '';
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? '';
        $items = $data['items'] ?? [];
        $content = <<<EOD
Új rendelés érkezett:

Név: $name
Cím: $zip $city, $address
Telefonszám: $phone
Email: $email

Rendelt termékek:
EOD;

        foreach ($items as $item) {
            $itemName = $item['name'] ?? 'Ismeretlen termék';
            $itemQty = $item['quantity'] ?? 1;
            $content .= "\n- $itemQty x $itemName";
        }


        // E-mail a vásárlónak
        $userEmail = (new Email())
            ->from('webshop@yourdomain.com')
            ->to($email)
            ->bcc('cc@example.com')
            ->subject('Rendelés megerősítése')
            ->text("Köszönjük rendelését!\n\n$content");

        // E-mail belső címre
        $internalEmail = (new Email())
            ->from('webshop@yourdomain.com')
            ->to('internal@example.com')
            ->subject('Új rendelés érkezett')
            ->text($content);

        $mailer->send($userEmail);
        //Mailtrap nem tud 2 emailt küldeni az alap verzióban 
        //$mailer->send($internalEmail);

        return new JsonResponse(['status' => 'ok']);
    }
}
