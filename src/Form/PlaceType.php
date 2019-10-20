<?php

namespace whatwedo\WorkflowBundle\Form;

use Symfony\Bridge\Doctrine\RegistryInterface;
use whatwedo\WorkflowBundle\Entity\Place;
use whatwedo\WorkflowBundle\Entity\Workflow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{

    /** @var RegistryInterface */
    private $doctirine;

    /**
     * @param RegistryInterface $doctirine
     * @required
     */
    public function setDoctirine(RegistryInterface $doctirine): void
    {
        $this->doctirine = $doctirine;
    }



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'workflow',

            )
            ->add('name')
            ->add('limit')
        ;

//        $builder->get('workflow')->addModelTransformer(
//            new EntityToValueTransformer($this->doctirineHelper->getRepository(Workflow::class))
//        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}