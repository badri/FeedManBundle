<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\FeedManBundle\Form\Type;

use Mautic\CoreBundle\Factory\MauticFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class ConstantContactType
 *
 * @package Mautic\FormBundle\Form\Type
 */
class FeedType extends AbstractType
{


	const CLASSNAME = __CLASS__;
    /**
     * @var MauticFactory
     */
    private $factory;

    public function __construct (MauticFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
		 $builder->add('name', 'text')->add('url','text')->add('save','submit', array('attr' => array('class' => 'btn button'),'label' => "Add Feed"));
        
    }

   

    /**
     * @return string
     */
    public function getName ()
    {
        return "feed";
    }
}