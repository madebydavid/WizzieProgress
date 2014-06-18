<?php

namespace WizzieProgress\Form {
    
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormError;
    
    class LoginForgottenType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder->add('email', 'text', array('required' => true));
        }
        
        public function getName() {
            return str_replace('\\', '_', __CLASS__);
        }
        
        public function getDefaultOptions(array $options) {
            return array();
        }
        
    }
    
}
