<?php

namespace Coolshop\CoolSonataTranslationBundle\Admin;

use Sonata\AdminBundle\Route\RouteCollection;
use Lexik\Bundle\TranslationBundle\Manager\TransUnitManagerInterface;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class TranslationAdmin extends Admin
{
    /**
     * @var TransUnitManagerInterface
     */
    protected $transUnitManager;
    /**
     * @var array
     */
    protected $editableOptions;

    /**
     * @var array
     */
    protected $defaultSelections = array();

    /**
     * @var array
     */
    protected $emptyFieldPrefixes = array();

    /**
     * @var array
     */
    protected $filterLocales = array();

    /**
     * @var array
     */
    protected $managedLocales = array();

    /**
     * @param array $options
     */
    public function setEditableOptions(array $options)
    {
        $this->editableOptions = $options;
    }

    /**
     * @TODO: remove this
     * @param array $managedLocales
     */
    public function setManagedLocales($managedLocales)
    {
        $this->managedLocales = array('en', 'it');
    }

    /**
     * @return array
     */
    public function getEmptyFieldPrefixes()
    {
        return $this->emptyFieldPrefixes;
    }

    /**
     * @return array
     */
    public function getDefaultSelections()
    {
        return $this->defaultSelections;
    }


    /**
     * @return array
     */
    public function getNonTranslatedOnly()
    {
        return array_key_exists('nonTranslatedOnly', $this->getDefaultSelections()) && (bool) $this->defaultSelections['nonTranslatedOnly'];
    }

    /**
     * @param array $selections
     */
    public function setDefaultSelections(array $selections)
    {
        $this->defaultSelections = $selections;
    }

    /**
     * @param array $prefixes
     */
    public function setEmptyPrefixes(array $prefixes)
    {
        $this->emptyFieldPrefixes = $prefixes;
    }

    /**
     * @return array
     */
    public function getFilterParameters()
    {
        $this->datagridValues = array_merge(
            array(
                'message_domain' => array(
                    'value' => $this->getDefaultDomain(),
                ),
            ),
            $this->datagridValues

        );

        return parent::getFilterParameters();
    }

    /**
     * @param unknown $name
     * @return multitype:|NULL
     */
    public function getTemplate($name)
    {
        if ($name === 'layout') {
            return 'CoolSonataTranslationBundle::translation_layout.html.twig';
        }

        if ($name === 'list') {
            return 'CoolSonataTranslationBundle:CRUD:list.html.twig';
        }

        return parent::getTemplate($name);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getOriginalTemplate($name)
    {
        return parent::getTemplate($name);
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('clear_cache')
            ->add('create_trans_unit');
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('id', 'integer')
            ->add('trans_key', 'string')
            ->add('message_domain', 'string');

        $localesToShow = count($this->filterLocales) > 0 ? $this->filterLocales : $this->managedLocales;

        foreach ($localesToShow as $locale) {
            $fieldDescription = $this->modelManager->getNewFieldDescriptionInstance($this->getClass(), $locale);
            $fieldDescription->setTemplate(
                'CoolSonataTranslationBundle:CRUD:base_inline_translation_field.html.twig'
            );
            $fieldDescription->setOption('locale', $locale);
            $fieldDescription->setOption('editable', $this->editableOptions);
            $list->add($fieldDescription);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatagrid()
    {
        if ($this->datagrid) {
            return;
        }

        $filterParameters = $this->getFilterParameters();

        // transform _sort_by from a string to a FieldDescriptionInterface for the datagrid.
        if (isset($filterParameters['locale']) && is_array($filterParameters['locale'])) {
            $this->filterLocales = array_key_exists('value', $filterParameters['locale']) ? $filterParameters['locale']['value'] : $this->managedLocales;
        }

        parent::buildDatagrid();
    }

    /**
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form)
    {
        $subject = $this->getSubject();

        if (null === $subject->getId()) {
            $subject->setDomain($this->getDefaultDomain());
        }


        $form
            ->add('trans_key', 'text')
            ->add('message_domain', 'text');
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->getConfigurationPool()->getContainer();
    }

    /**
     * @return string
     */
    protected function getDefaultDomain()
    {
        return $this->getContainer()->getParameter('cool_sonata_translation.defaultDomain');
    }

    /**
     * {@inheritdoc}
     */
    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        $actions['download'] = array(
            'label'            => $this->trans($this->getLabelTranslatorStrategy()->getLabel('download', 'batch', 'CoolSonataTranslationBundle')),
            'ask_confirmation' => false,
        );

        return $actions;
    }
}
