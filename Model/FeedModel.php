<?php
// plugins/FeedManBundle/Model/Feed.php

namespace MauticPlugin\FeedManBundle\Model;

use Mautic\CoreBundle\Model\CommonModel;

class FeedModel extends CommonModel
{

    /**
     * Send contact email
     * 
     * @param array $data
     */
    public function sendContactEmail($data)
    {
        // Get mailer helper
        $mailer = $this->factory->getMailer();

        $mailer->message->addTo(
            $this->factory->getParameter('mailer_from_email')
        );

        $this->message->setFrom(
            array($data['email'] => $data['name'])
        );

        $mailer->message->setSubject($data['subject']);

        $mailer->message->setBody($data['message']);

        $mailer->send();
    }
	
}