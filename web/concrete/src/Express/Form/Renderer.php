<?php
namespace Concrete\Core\Express\Form;

use Concrete\Core\Application\Application;
use Concrete\Core\Entity\Express\FieldSet;
use Concrete\Core\Entity\Express\Form;
use Concrete\Core\Express\BaseEntity;
use Doctrine\ORM\EntityManagerInterface;

class Renderer implements RendererInterface
{
    protected $entityManager;
    protected $application;

    public function __construct(Application $application, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->application = $application;
    }

    protected function getFormOpenTag()
    {
        return '<div class="ccm-express-form">';
    }

    protected function getFormCloseTag()
    {
        return '</div>';
    }

    protected function getFieldSetOpenTag(FieldSet $set)
    {
        $html = '<fieldset class="ccm-express-form-field-set">';
        if ($set->getTitle()) {
            $html .= '<legend>' . $set->getTitle() . '</legend>';
        }

        return $html;
    }

    protected function getFieldSetCloseTag()
    {
        return '</fieldset>';
    }

    protected function getCsrfTokenField()
    {
        return $this->application->make('token')->output('express_form', true);
    }

    protected function getFormField(Form $form)
    {
        return '<input type="hidden" name="express_form_id" value="' . $form->getId() . '">';
    }

    protected function renderFieldSet(FieldSet $fieldSet)
    {
        $html = $this->getFieldSetOpenTag($fieldSet);
        foreach ($fieldSet->getControls() as $control) {
            $factory = new RendererFactory($control, $this->application, $this->entityManager);
            $renderer = $factory->getFormRenderer();
            if (is_object($renderer)) {
                $html .= $renderer->render();
            }
        }
        $html .= $this->getFieldSetCloseTag();

        return $html;
    }

    public function render(Form $form, BaseEntity $entity = null)
    {
        $html = $this->getFormOpenTag();
        $html .= $this->getFormField($form);
        $html .= $this->getCsrfTokenField();
        foreach ($form->getFieldSets() as $fieldSet) {
            $html .= $this->renderFieldSet($fieldSet);
        }

        $html .= $this->getFormCloseTag();

        return $html;
    }
}