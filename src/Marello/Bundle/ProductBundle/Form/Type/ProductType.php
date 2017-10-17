<?php

namespace Marello\Bundle\ProductBundle\Form\Type;

use Marello\Bundle\PricingBundle\Form\EventListener\ChannelPricingSubscriber;
use Marello\Bundle\PricingBundle\Form\EventListener\PricingSubscriber;
use Marello\Bundle\SalesBundle\Form\EventListener\DefaultSalesChannelSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Oro\Bundle\AttachmentBundle\Form\Type\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class ProductType extends AbstractType
{
    const NAME = 'marello_product_form';

    /** @var DefaultSalesChannelSubscriber $defaultSalesChannelSubscriber*/
    protected $defaultSalesChannelSubscriber;

    /** @var PricingSubscriber $pricingSubscriber */
    protected $pricingSubscriber;

    /** @var ChannelPricingSubscriber $channelPricingSubscriber */
    protected $channelPricingSubscriber;

    /**
     * ProductType constructor.
     * @param DefaultSalesChannelSubscriber $defaultSalesChannelSubscriber
     * @param PricingSubscriber $pricingSubscriber
     * @param ChannelPricingSubscriber $channelPricingSubscriber
     */
    public function __construct(
        DefaultSalesChannelSubscriber $defaultSalesChannelSubscriber,
        PricingSubscriber $pricingSubscriber,
        ChannelPricingSubscriber $channelPricingSubscriber
    ) {
        $this->defaultSalesChannelSubscriber    = $defaultSalesChannelSubscriber;
        $this->pricingSubscriber         = $pricingSubscriber;
        $this->channelPricingSubscriber  = $channelPricingSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                [
                    'required' => true,
                    'label'    => 'marello.product.name.label',
                ]
            )
            ->add(
                'sku',
                'text',
                [
                    'required' => true,
                    'label'    => 'marello.product.sku.label',
                ]
            )
            ->add(
                'weight',
                'number',
                [
                    'required'  => false,
                    'scale'     => 2,
                    'label'     => 'marello.product.weight.label'
                ]
            )
            ->add(
                'warranty',
                'number',
                [
                    'required'  => false,
                    'label'     => 'marello.product.warranty.label',
                    'tooltip'   => 'marello.product.form.tooltip.warranty'
                ]
            )
            ->add(
                'status',
                'entity',
                [
                    'label'    => 'marello.product.status.label',
                    'class'    => 'MarelloProductBundle:ProductStatus',
                    'property' => 'label',
                    'required' => true,
                ]
            )
            ->add(
                'addSalesChannels',
                'oro_entity_identifier',
                [
                    'class'    => 'MarelloSalesBundle:SalesChannel',
                    'required' => false,
                    'mapped'   => false,
                    'multiple' => true,
                ]
            )
            ->add(
                'removeSalesChannels',
                'oro_entity_identifier',
                [
                    'class'    => 'MarelloSalesBundle:SalesChannel',
                    'required' => false,
                    'mapped'   => false,
                    'multiple' => true,
                ]
            )
            ->add(
                'suppliers',
                'marello_product_supplier_relation_collection_form',
                [
                    'label'              => 'marello.supplier.entity_label',
                    'cascade_validation' => true,
                ]
            )
            ->add('taxCode', 'marello_tax_taxcode_select')
            ->add(
                'salesChannelTaxCodes',
                'marello_product_channel_tax_relation_collection_form',
                [
                    'label'              => 'marello.tax.taxcode.entity_label',
                    'cascade_validation' => true,
                ]
            )
            ->add('image',
                ImageType::class,
                [
                    'label' => 'marello.product.image.label',
                    'required' => false
                ]
            );
        ;

        $builder->addEventSubscriber($this->defaultSalesChannelSubscriber);
        $builder->addEventSubscriber($this->pricingSubscriber);
        $builder->addEventSubscriber($this->channelPricingSubscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Marello\Bundle\ProductBundle\Entity\Product',
            'intention'          => 'product',
            'single_form'        => true,
            'cascade_validation' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}
