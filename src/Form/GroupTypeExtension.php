<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Form;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A Group to contain form elements.
 * 
 * @website https://github.com/kbond/ZenstruckFormBundle
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class GroupTypeExtension extends AbstractTypeExtension 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->setAttribute('group', $options['group']);
        
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['group'] = $form->getConfig()->getAttribute('group');
    }
    
    /**
     * Add the image_path option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
         $resolver->setDefaults(array(
                'group' => null,
            ));
    }
   
    public function getExtendedType()
    {
        return FormType::class;
    }
    
    public function getBlockPrefix()
    {
        return 'grouptype';
    }
    
    public function getName()
    {
        return $this->getBlockPrefix();
    }
    
}
/* End of Class */