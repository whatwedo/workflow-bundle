<?php


namespace whatwedo\WorkflowBundle\Form;


use ReflectionClass;
use whatwedo\WorkflowBundle\Entity\Workflowable;
use whatwedo\WorkflowBundle\EventHandler\EventHandlerAbstract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use whatwedo\WorkflowBundle\EventHandler\WorkflowEventHandlerInterface;

class EventHandlerTypes extends AbstractType
{
    /** @var array|WorkflowEventHandlerInterface  */
    private $eventHandler;

    public function __construct()
    {
        $this->eventHandler = [];
    }

    public function addWorkflowHandler(WorkflowEventHandlerInterface $workflowSubscriber)
    {
        $klass = get_class($workflowSubscriber);
        $reflect = new ReflectionClass($klass);
        if($reflect->implementsInterface(WorkflowEventHandlerInterface::class)) {
            $this->eventHandler[$klass] = $klass;
        }
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Event Handler',
            'choices' =>
                $this->eventHandler
            ,
            'multiple' => false,
            'required' => false,
        ]);
    }

}