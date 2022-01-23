<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Model;

use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Mautic\CoreBundle\Form\RequestTrait;
use Mautic\CoreBundle\Helper\DateTimeHelper;
use Mautic\CoreBundle\Helper\InputHelper;
use Mautic\CoreBundle\Model\AjaxLookupModelInterface;
use Mautic\CoreBundle\Model\FormModel as CommonFormModel;
use Mautic\EmailBundle\Helper\EmailValidator;
use Mautic\LeadBundle\Deduplicate\CompanyDeduper;
use Mautic\LeadBundle\Entity\Company;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Event\CompanyEvent;
use Mautic\LeadBundle\Exception\UniqueFieldNotFoundException;
use Mautic\LeadBundle\Form\Type\CompanyType;
use Mautic\LeadBundle\LeadEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Class CompanyModel.
 */
class CompanyModel extends CommonFormModel implements AjaxLookupModelInterface
{
    use DefaultValueTrait;
    use RequestTrait;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var FieldModel
     */
    protected $leadFieldModel;

    /**
     * @var array
     */
    protected $companyFields;

    /**
     * @var EmailValidator
     */
    protected $emailValidator;

    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var bool
     */
    private $repoSetup = false;

    /**
     * @var CompanyDeduper
     */
    private $companyDeduper;

    /**
     * CompanyModel constructor.
     */
    public function __construct(FieldModel $leadFieldModel, Session $session, EmailValidator $validator, CompanyDeduper $companyDeduper)
    {
        $this->leadFieldModel = $leadFieldModel;
        $this->session        = $session;
        $this->emailValidator = $validator;
        $this->companyDeduper = $companyDeduper;
    }

    /**
     * @param Company $entity
     * @param bool    $unlock
     */
    public function saveEntity($entity, $unlock = true)
    {
        // Update leads primary company name
        $this->setEntityDefaultValues($entity, 'company');

        parent::saveEntity($entity, $unlock);
    }

    /**
     * Save an array of entities.
     *
     * @param array $entities
     * @param bool  $unlock
     *
     * @return array
     */
    public function saveEntities($entities, $unlock = true)
    {
        // Update leads primary company name
        foreach ($entities as $entity) {
            $this->setEntityDefaultValues($entity, 'company');
        }
        parent::saveEntities($entities, $unlock);
    }

    /**
     * {@inheritdoc}
     *
     * @return \Mautic\LeadBundle\Entity\CompanyRepository
     */
    public function getRepository()
    {
        $repo =  $this->em->getRepository('MauticLeadBundle:Company');
        if (!$this->repoSetup) {
            $this->repoSetup = true;
            $repo->setDispatcher($this->dispatcher);
            //set the point trigger model in order to get the color code for the lead
            $fields = $this->leadFieldModel->getFieldList(true, true, ['isPublished' => true, 'object' => 'company']);

            $searchFields = [];
            foreach ($fields as $groupFields) {
                $searchFields = array_merge($searchFields, array_keys($groupFields));
            }
            $repo->setAvailableSearchFields($searchFields);
        }

        return $repo;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionBase()
    {
        // We are using lead:leads in the CompanyController so this should match to prevent a BC break
        return 'lead:leads';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getNameGetter()
    {
        return 'getPrimaryIdentifier';
    }

    /**
     * {@inheritdoc}
     *
     * @throws MethodNotAllowedHttpException
     */
    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof Company) {
            throw new MethodNotAllowedHttpException(['Company']);
        }
        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(CompanyType::class, $entity, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @return Company|null
     */
    public function getEntity($id = null)
    {
        if (null === $id) {
            return new Company();
        }

        return parent::getEntity($id);
    }

    /**
     * Populates custom field values for updating the company.
     *
     * @param bool|false $overwriteWithBlank
     */
    public function setFieldValues(Company $company, array $data, $overwriteWithBlank = false)
    {
        //save the field values
        $fieldValues = $company->getFields();

        if (empty($fieldValues)) {
            // Lead is new or they haven't been populated so let's build the fields now
            if (empty($this->fields)) {
                $this->fields = $this->leadFieldModel->getEntities(
                    [
                        'filter'         => ['object' => 'company'],
                        'hydration_mode' => 'HYDRATE_ARRAY',
                    ]
                );
                $this->fields = $this->organizeFieldsByGroup($this->fields);
            }
            $fieldValues = $this->fields;
        }

        //update existing values
        foreach ($fieldValues as &$groupFields) {
            foreach ($groupFields as $alias => &$field) {
                if (!isset($field['value'])) {
                    $field['value'] = null;
                }
                // Only update fields that are part of the passed $data array
                if (array_key_exists($alias, $data)) {
                    $curValue = $field['value'];
                    $newValue = $data[$alias];

                    if (is_array($newValue)) {
                        $newValue = implode('|', $newValue);
                    }

                    if ($curValue !== $newValue && (strlen($newValue) > 0 || (0 === strlen($newValue) && $overwriteWithBlank))) {
                        $field['value'] = $newValue;
                        $company->addUpdatedField($alias, $newValue, $curValue);
                    }
                }
            }
        }
        $company->setFields($fieldValues);
    }

    /**
     * Get list of entities for autopopulate fields.
     *
     * @param $type
     * @param $filter
     * @param $limit
     * @param $start
     *
     * @return array
     */
    public function getLookupResults($type, $filter = '', $limit = 10, $start = 0)
    {
        $results = [];
        switch ($type) {
            case 'companyfield':
            case 'lead.company':
                if ('lead.company' === $type) {
                    $column    = 'companyname';
                    $filterVal = $filter;
                } else {
                    if (is_array($filter)) {
                        $column    = $filter[0];
                        $filterVal = $filter[1];
                    } else {
                        $column = $filter;
                    }
                }

                $expr      = new ExpressionBuilder($this->em->getConnection());
                $composite = $expr->andX();
                $composite->add(
                    $expr->like("comp.$column", ':filterVar')
                );

                // Validate owner permissions
                if (!$this->security->isGranted('lead:leads:viewother')) {
                    $composite->add(
                        $expr->orX(
                            $expr->andX(
                                $expr->isNull('comp.owner_id'),
                                $expr->eq('comp.created_by', (int) $this->userHelper->getUser()->getId())
                            ),
                            $expr->eq('comp.owner_id', (int) $this->userHelper->getUser()->getId())
                        )
                    );
                }

                $results = $this->getRepository()->getAjaxSimpleList($composite, ['filterVar' => $filterVal.'%'], $column);

                break;
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     *
     * @param $action
     * @param $event
     * @param $entity
     * @param $isNew
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    protected function dispatchEvent($action, &$entity, $isNew = false, Event $event = null)
    {
        if (!$entity instanceof Company) {
            throw new MethodNotAllowedHttpException(['Email']);
        }

        switch ($action) {
            case 'pre_save':
                $name = LeadEvents::COMPANY_PRE_SAVE;
                break;
            case 'post_save':
                $name = LeadEvents::COMPANY_POST_SAVE;
                break;
            case 'pre_delete':
                $name = LeadEvents::COMPANY_PRE_DELETE;
                break;
            case 'post_delete':
                $name = LeadEvents::COMPANY_POST_DELETE;
                break;
            default:
                return null;
        }

        if ($this->dispatcher->hasListeners($name)) {
            if (empty($event)) {
                $event = new CompanyEvent($entity, $isNew);
                $event->setEntityManager($this->em);
            }

            $this->dispatcher->dispatch($name, $event);

            return $event;
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    public function fetchCompanyFields()
    {
        if (empty($this->companyFields)) {
            $this->companyFields = $this->leadFieldModel->getEntities(
                [
                    'filter' => [
                        'force' => [
                            [
                                'column' => 'f.isPublished',
                                'expr'   => 'eq',
                                'value'  => true,
                            ],
                            [
                                'column' => 'f.object',
                                'expr'   => 'eq',
                                'value'  => 'company',
                            ],
                        ],
                    ],
                    'hydration_mode' => 'HYDRATE_ARRAY',
                ]
            );
        }

        return $this->companyFields;
    }

    /**
     * @param $mappedFields
     * @param $data
     *
     * @return array
     */
    public function extractCompanyDataFromImport(array &$mappedFields, array &$data)
    {
        $companyData    = [];
        $companyFields  = [];
        $internalFields = $this->fetchCompanyFields();

        if (!isset($mappedFields['companyname']) && isset($mappedFields['company'])) {
            $mappedFields['companyname'] = $mappedFields['company'];

            unset($mappedFields['company']);
        }

        foreach ($mappedFields as $mauticField => $importField) {
            foreach ($internalFields as $entityField) {
                if ($entityField['alias'] === $mauticField) {
                    $companyData[$importField]   = $data[$importField];
                    $companyFields[$mauticField] = $importField;
                    unset($data[$importField]);
                    unset($mappedFields[$mauticField]);
                    break;
                }
            }
        }

        return [$companyFields, $companyData];
    }

    /**
     * @param array $fields
     * @param array $data
     * @param null  $owner
     * @param bool  $skipIfExists
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function import($fields, $data, $owner = null, $skipIfExists = false)
    {
        $company = $this->importCompany($fields, $data, $owner, false, $skipIfExists);

        if (null === $company) {
            throw new \Exception($this->translator->trans('mautic.company.error.notfound', [], 'flashes'));
        }

        $merged = !$company->isNew();

        $this->saveEntity($company);

        return $merged;
    }

    /**
     * @param array $fields
     * @param array $data
     * @param null  $owner
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function importCompany($fields, $data, $owner = null, $persist = true, $skipIfExists = false)
    {
        try {
            $duplicateCompanies = $this->companyDeduper->checkForDuplicateCompanies($this->getFieldData($fields, $data));
        } catch (UniqueFieldNotFoundException $uniqueFieldNotFoundException) {
            return null;
        }

        $company = !empty($duplicateCompanies) ? $duplicateCompanies[0] : new Company();

        if (!empty($fields['dateAdded']) && !empty($data[$fields['dateAdded']])) {
            $dateAdded = new DateTimeHelper($data[$fields['dateAdded']]);
            $company->setDateAdded($dateAdded->getUtcDateTime());
        }
        unset($fields['dateAdded']);

        if (!empty($fields['dateModified']) && !empty($data[$fields['dateModified']])) {
            $dateModified = new DateTimeHelper($data[$fields['dateModified']]);
            $company->setDateModified($dateModified->getUtcDateTime());
        }
        unset($fields['dateModified']);

        if (!empty($fields['createdByUser']) && !empty($data[$fields['createdByUser']])) {
            $userRepo      = $this->em->getRepository('MauticUserBundle:User');
            $createdByUser = $userRepo->findByIdentifier($data[$fields['createdByUser']]);
            if (null !== $createdByUser) {
                $company->setCreatedBy($createdByUser);
            }
        }
        unset($fields['createdByUser']);

        if (!empty($fields['modifiedByUser']) && !empty($data[$fields['modifiedByUser']])) {
            $userRepo       = $this->em->getRepository('MauticUserBundle:User');
            $modifiedByUser = $userRepo->findByIdentifier($data[$fields['modifiedByUser']]);
            if (null !== $modifiedByUser) {
                $company->setModifiedBy($modifiedByUser);
            }
        }
        unset($fields['modifiedByUser']);

        if (null !== $owner) {
            $company->setOwner($this->em->getReference('MauticUserBundle:User', $owner));
        }

        $fieldData = $this->getFieldData($fields, $data);

        $fieldErrors = [];

        foreach ($this->fetchCompanyFields() as $entityField) {
            // Skip If value already exists
            if ($skipIfExists && !$company->isNew() && !empty($company->getProfileFields()[$entityField['alias']])) {
                unset($fieldData[$entityField['alias']]);
                continue;
            }

            if (isset($fieldData[$entityField['alias']])) {
                $fieldData[$entityField['alias']] = InputHelper::_($fieldData[$entityField['alias']], 'string');

                if ('NULL' === $fieldData[$entityField['alias']]) {
                    $fieldData[$entityField['alias']] = null;

                    continue;
                }

                try {
                    $this->cleanFields($fieldData, $entityField);
                } catch (\Exception $exception) {
                    $fieldErrors[] = $entityField['alias'].': '.$exception->getMessage();
                }

                // Skip if the value is in the CSV row
                continue;
            } elseif ($company->isNew() && $entityField['defaultValue']) {
                // Fill in the default value if any
                $fieldData[$entityField['alias']] = ('multiselect' === $entityField['type']) ? [$entityField['defaultValue']] : $entityField['defaultValue'];
            }
        }

        if ($fieldErrors) {
            $fieldErrors = implode("\n", $fieldErrors);

            throw new \Exception($fieldErrors);
        }

        // All clear
        foreach ($fieldData as $field => $value) {
            $company->addUpdatedField($field, $value);
        }

        if ($persist) {
            $this->saveEntity($company);
        }

        return $company;
    }

    public function checkForDuplicateCompanies(array $queryFields)
    {
        return $this->companyDeduper->checkForDuplicateCompanies($queryFields);
    }

    /**
     * @param array $fields
     * @param array $data
     */
    protected function getFieldData($fields, $data): array
    {
        // Set profile data using the form so that values are validated
        $fieldData = [];
        foreach ($fields as $importField => $entityField) {
            // Prevent overwriting existing data with empty data
            if (array_key_exists($importField, $data) && !is_null($data[$importField]) && '' != $data[$importField]) {
                $fieldData[$entityField] = $data[$importField];
            }
        }

        return $fieldData;
    }
}
