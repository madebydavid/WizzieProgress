<?php

namespace WizzieProgress\Form {
    
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Validator\Constraints;
    
    class UserType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options) {
            
            $roles = \Model\User::getAvailableRoles();

            $builder->add(
                'email', 
                'text', 
                array(
                    'constraints' => array(
                        new Constraints\NotBlank(), 
                        new Constraints\Email()
                    )
                )
            )->add(
                'firstname', 
                'text', 
                array()
            )->add(
                'lastname',
                'text',
                array()
            )->add(
                'password', 
                'repeated', 
                array(
                    'type' => 'password', 
                    'first_options' => array(
                            'label' => 'Password'
                    ),
                    'second_options' => array(
            	           'label' => 'Confirm Password'
                    ),
                    'invalid_message' => 'Passwords do not match'
                )
            )->add(
                'userRoles', 
                'choice',
                array(
                    'multiple' => false, /* users can onnly have one role */
                    'label' => 'User Role',
                    'constraints' => array(
                        new Constraints\Choice(array('choices' => array_values($roles) , 'multiple' => false))
                    ),
                    'choices' => $roles,
                )
            );
            
        }
        
        public function getName() {
            return str_replace('\\', '_', __CLASS__);
        }
        
        public function getDefaultOptions(array $options) {
            return array(
                'data_class' => 'Model\User'
            );
        }
        
    }
    
}
