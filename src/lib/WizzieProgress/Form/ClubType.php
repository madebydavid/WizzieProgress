<?php

namespace WizzieProgress\Form {
    
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Validator\Constraints;
    
    class ClubType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options) {
            
            $builder->add(
                'name', 
                'text', 
                array(
                    'constraints' => array(
                        new Constraints\NotBlank()
                    )
                )
            );
            
        }
        
        public function getName() {
            return str_replace('\\', '_', __CLASS__);
        }
        
        public function getDefaultOptions(array $options) {
            return array(
                'data_class' => 'Model\Club'
            );
        }
        
    }
    
}
