<?php
namespace Bolt\Extension\IComeFromTheNet\BookMe\Bundle\Queue\Provider;

use DateTime;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\GroupTypeExtension;

use Bolt\Extension\IComeFromTheNet\BookMe\Form\OptionFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\Build\Custom\InlineFormContainer;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\Build\FormFieldFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\Build\SchemaFieldFactory;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONArrayBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\Form\JSONObjectBuilder;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\FunctionReferenceType;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\StringOutput;
use Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\DenseFormat;


/**
 * Loads this Queue Bunles Search Forms.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class QueueFormProvider implements ServiceProviderInterface
{
    /**
     * @var config
     */ 
    private $config;


    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $aConfig   = $this->config;
        
        $app['bm.form.output'] = function($c) {
            return new StringOutput(new DenseFormat());
        };
 
        //----------------------------------------------------------------------
        # Rules Form
        
        $app['bm.form.queue.jobsearch'] = $app->share(function ($c) use ($aConfig) {
            $oString = $c['bm.form.output'];
            
            $oForm = new InlineFormContainer($oString); 
            
            $oForm->getSchema()->addField('before',SchemaFieldFactory::createStringField($oString)
                                                 ->setTitle('Occured After')
                                                 ->setDescription('Job added to the Queue after or on this date')
                                                 ->setRequired(true)                 
            
            );
            
            $oForm->getSchema()->addField('after',SchemaFieldFactory::createStringField($oString)
                                                 ->setTitle('Occured Before')
                                                 ->setDescription('Job added to the Queue before or on this date')
                                                 ->setRequired(true)                 
            
            );
            
            $oForm
            ->getForm()
            ->addField(
                FormFieldFactory::createSectionType($oString)
                ->setTitle("Job Dates")
                ->addItems(
                    FormFieldFactory::createFormFieldCollection($oString)
                        ->addField(
                            FormFieldFactory::createDateType($oString)
                                ->setKey('before')
                        )
                        ->addField(
                            FormFieldFactory::createDateType($oString)
                            ->setKey('after')
                        )
                        ->addField(
                                        FormFieldFactory::createSubmitType($oString)
                                            ->setTitle('Submit Search')
                                    
                        )
                )
            );
            
            
            
            /*
            $oForm->addObjectValue('defaultClasses',
                OptionFactory::createObjectBuilder($oString)
                    ->addPrimitive('labelClass','control-label col-sm-2')
                    ->addPrimitive('controlClass','controls col-sm-6')
                    ->addPrimitive('groupClass','form-group row')
            );*/
            

            return $oForm;
            
        });
      
        
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        
          
        
    }
}
/* End of Service Provider */