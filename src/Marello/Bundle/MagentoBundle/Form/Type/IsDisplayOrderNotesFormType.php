<?php

namespace Marello\Bundle\MagentoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IsDisplayOrderNotesFormType extends AbstractType
{
    const NAME = 'oro_magento_is_display_order_notes_type';

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'marello.magento.magentotransport.is_display_order_notes.label',
            'tooltip' => 'marello.magento.magentotransport.is_display_order_notes.tooltip',
            'choices' => [
                'marello.magento.magentotransport.is_display_order_notes.value.true.label' => true,
                'marello.magento.magentotransport.is_display_order_notes.value.false.label' => false,
            ],
            'placeholder' => false,
            'choices_as_values' => true,
            'required' => false
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::NAME;
    }
}
