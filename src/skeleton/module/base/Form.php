<?php
namespace tuanlq11\cms\skeleton\module\base;

use App\Http\Modules\User\forms\UserForm;
use Illuminate\Database\Eloquent\Model;
use FormBuilder;
use Illuminate\Support\Str;

/**
 * Class Form. To generate Form data
 *
 * @traitUses Core\Bases\Module\BaseActions
 */
trait Form
{
    /**
     * Default form store data to render HTML and Validation
     * @var \Kris\LaravelFormBuilder\Form
     */
    protected $form = null;

    /**
     * Init form with or within Model
     *
     * @param $obj Model
     * @param $action string
     * @param $targetAction string
     *
     * @return \Kris\LaravelFormBuilder\Form
     */
    protected function buildForm($action, $targetAction, Model $obj = null)
    {
        /** @var \Kris\LaravelFormBuilder\Form $form */
        $form = null;

        /** List available namespace exists to class */
        $form_class = [
            // Static class string in custom config [1]
            $this->getConfig("$action.form_class"),
            // Auto detect exists class in module directory [2]
            sprintf("App\\Http\\Modules\\%s\\forms\\%sForm", $this->getModuleName(), $this->getModuleName()),
            // Auto detect exists class in form directory [3]
            sprintf("App\\Forms\\%sForm", $this->getModuleName()),
        ];
        /** End */

        /** Check first namespace exists */
        foreach ($form_class as $class) {
            if (empty($class)) continue;
            if (is_array($class)) $class = $class[0];

            if (class_exists($class)) {
                $form = $class;
                break;
            }
        }
        /** End */

        if (is_null($form)) {
            $message = env('APP_DEBUG') ? "Not found Form for module!" : "";
            abort(404, $message);

            $this->form = null;
            return null;
        }

        /**
         * Use FormBuilder to init class form
         */
        $url  = is_null($obj) ?
            $this->getGeneratedUrl($targetAction) : $this->getGeneratedUrl($targetAction, $obj->id);
        $form = FormBuilder::create(
            $form,
            [
                'method' => 'post',
                'url'    => $url,
                'name'   => $action,
            ]
        );

        /**
         * Get Field config from module config
         * @var  $formConfig
         */
        $formConfig = $this->getConfig('field', $action);

        /**
         * Create field to form if it not exists
         * @var string $key
         * @var  string $config
         */
        foreach ($formConfig as $key => $config) {
            if ($form->has($key)) continue;
            $key = trim($key, '[]');

            $fieldParams = [
                'default_value' => array_get($config, 'default_value', ''),
                'label'         => array_get($config, 'label', $key),
                'class'         => array_get($config, 'class', ''),
                'rules'         => array_get($config, 'validation', ''),
            ];

            if (isset($config['template'])) {
                $fieldParams['template'] = $config['template'];
            }

            if ($obj) {
                $func = "get" . Str::ucfirst($key);
                if (($is_I18N = method_exists($obj, 'saveI18N'))) {
                    $obj->load('i18n_relation');
                }
                $data = $obj->toArray();

                if (method_exists($obj, $func)) {
                    $fieldParams['value'] = $obj->$func();
                } else {
                    try {
                        $parseField = explode('.', $this->parseFieldName($key));
                        if (count($parseField) == 1) {
                            $fieldParams['value'] = $data[$parseField[0]];
                        } else if ($parseField[0] === 'i18n' && count($parseField) > 1) {
                            $field                = last($parseField);
                            $locale               = $parseField[1];
                            $indexData            = array_pluck($data['i18n_relation'], $field, 'locale');
                            $fieldParams['value'] = $indexData[$locale];
                        }
                    } catch (\Exception $ex) {

                    }
                }
            }

            /** Custom Prepare Validation in Progress */
            $methodValidation = "{$key}PrepareValidation";
            if (method_exists($this, $methodValidation)) {
                $fieldParams['rules'] = call_user_func_array([$this, $methodValidation], [$fieldParams['rules'], $obj]);
            }
            /** End */

            /** @var  array $fieldParams */
            $fieldParams = array_merge($fieldParams, (array)array_get($config, 'params', []));

            $form->add($key, array_get($config, 'type', 'text'), $fieldParams);
        }
        /** End */

        /** Default Button */
        foreach ($subActions = $this->getConfig("{$action}.action") as $subAction => $config) {
            $subAction = sprintf("%sAction", $subAction);
            if (method_exists($this, $subAction)) {
                $this->$subAction($form, $config);
            }
        }
        $form->rebuildForm();
        /** End */

        $this->form = $form;

        return $this->form;
    }

    /**
     * Validate Form
     *
     * @param \Kris\LaravelFormBuilder\Form $form
     * @param string $action
     *
     * @return boolean
     */
    protected function validateForm($form = null, $action = 'edit')
    {
        $form = is_null($form) ? $this->form : $form;

        if (is_null($form)) return false;
        $formValidation = $this->getFormValidation($action);
        $messages       = array_get($formValidation, 'messages');

        $form->validate([], $messages);

        return $form->isValid();
    }

    /**
     * Get Form validation and message in config
     *
     * @param string $action
     *
     * @return array
     */
    protected function getFormValidation($action = 'edit')
    {
        $formConfig  = $this->getConfig("{$action}.field");
        $validations = [];
        $messages    = [];

        foreach ($formConfig as $key => $config) {
            $validations[$key] = isset($config['validation']) ? $config['validation'] : '';
            $messages[$key]    = (isset($config['message']) && is_array($config['message'])) ?
                $config['message'] : [];
        }

        $messages = array_dot(['filters' => $messages]);

        $result = compact('validations', 'messages');

        return $result;
    }

    /**
     * Update Form data
     *
     * @param $data
     */
    protected function setFormData($data)
    {
        foreach ($data as $key => $value) {
            if (!$this->form->has($key)) continue;
            $oldField = $this->form->getField($key);
            $this->form->modify($key, $oldField->getType(), ['attr' => ['data-previous' => $value], 'value' => $value]);
        }
    }
}