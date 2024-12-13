<?php

namespace Stringkey\BitMagicBundle\Form;

use Stringkey\BitMagicBundle\Form\Transformer\BitmaskToArrayTransformer;
use Stringkey\BitMagicBundle\Utilities\BitOperations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BitMaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->addModelTransformer(new BitmaskToArrayTransformer($options['choices']));

        // Event handler is triggered after the data transform
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $options = $form->getConfig()->getOptions();
            $enableMask = $options['enable_mask'];
            $data = $event->getData();
            $event->setData($data);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'multiple' => true, // always true as we are handling a bitmask, so always many bits not one
            'expanded' => true, // always true, behaviour is unpredictable otherwise
            'choices' => BitOperations::createOptions(32, 0xFFFFFFFF),
            'enable_mask' => ~0 // enable all bit by default
        ]);

        $resolver->setAllowedTypes('choices', ['array']);

        // Validate that the values of the choices only contain a single bit designator
        $resolver->setAllowedValues('choices', function (array $values): bool {
            foreach ($values as $value) {
                // only if none or more than 1 bit is set return with a negative verdict
                if ($value == 0 || $value & ($value - 1)) {
                    return false;
                }
            }

            return true;
        });

        $resolver->setNormalizer('enable_mask', function (OptionsResolver $options, ?string $value): int {
            return intval($value);
        });

        // Only sensible combination is showing a list of checkboxes per bit
        $resolver->setAllowedValues('expanded', [true]);
        $resolver->setAllowedValues('multiple', [true]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        // disable checkboxes
        $enableMask = $form->getConfig()->getOption('enable_mask');
        $expanded = $form->getConfig()->getOption('expanded');

        if (!$expanded) {
            return;
        }
        $children = $view->children;
        foreach ($children as $bitField) {
            if (((int)$bitField->vars['value']) & ~$enableMask) {;
                $bitField->vars['attr']['disabled'] = true;
            }
        }
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'bitmask';
    }
}
