<?php


namespace whatwedo\WorkflowBundle\EventHandler;


use Twig\Error\RuntimeError;
use whatwedo\WorkflowBundle\Entity\EventDefinition;
use whatwedo\WorkflowBundle\Model\ValidationError;
use whatwedo\WorkflowBundle\Model\DummySubject;
use Twig\Environment;

class Mailsender extends AbstractEventHandler
{
    /** @var \Swift_Mailer */
    protected $mailer;

    /**
     * @param \Swift_Mailer $mailer
     * @required
     */
    public function setMailer(\Swift_Mailer $mailer): void
    {
        $this->mailer = $mailer;
    }

    public function run($subject, EventDefinition $eventDefinition): bool
    {

        $data = $this->evaluateExpression($subject, $eventDefinition);
        $body = $this->getTemplate($subject, $eventDefinition);

        $message = (new \Swift_Message($data['subject']))
            ->setFrom($data['sender'])
            ->setTo($data['receiver'])
            ->setBody(
                $body,
                'text/html'
            );

        $this->mailer->send($message);
        return true;
    }

    public function getExpressionHelp(): string
    {
        return 'subject: Email Subject;  
    sender: Email of sender; 
    receiver: Email of receiver';
    }

    public function getExpressionSample(): string
    {
        return '{
    subject: "The Subject",
    sender: "Sender@Email.com",
    receiver: "receiver@Email.com"
}';
    }

    public function getTemplateHelp(): string
    {
        return 'Avaliable Objects - User: the current user; subject: The subject entity';
    }

    public function getTemplateSample(): string
    {
        return "<h1>Hello</h1><br>
<br>
The Entity {{subject.name}} was created.
by {{user.name}}<br>
<a href=\"{{ path('entity_show', {id: entity.id} ) }}\">Show.<br>

<br>";
    }
    

    public function validateExpression(EventDefinition $eventDefinition): bool
    {
        $result = true;
        $data = $this->evaluateExpression(new DummySubject(), $eventDefinition);

        if (!isset($data['subject'])) {
            $eventDefinition->addValidationError(
                new ValidationError('Expression: Subject is not definied', 'danger')
            );
            $result = false;
        }
        if (!isset($data['sender'])) {
            $eventDefinition->addValidationError(
                new ValidationError('Expression: Sender is not definied', 'danger')
            );
            $result = false;
        }
        if (!isset($data['receiver'])) {
            $eventDefinition->addValidationError(
                new ValidationError('Expression: Receiver is not definied', 'danger')
            );
            $result = false;
        }

        return $result;
    }

}
