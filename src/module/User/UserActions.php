<?php
namespace App\Http\Modules\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use tuanlq11\cms\skeleton\module\BaseActions;
use tuanlq11\cms\model\User;
use Form, Session, Config;
use Illuminate\Support\Facades\Input;

class UserActions extends BaseActions
{
    public function listInGroup()
    {
        // Prepare Data
        $group_id = Input::get('group_id', 0);
        $query    = $this->buildQuery();
        $query->leftJoin('user_group_relation as ug', 'ug.user_id', '=', 'users.id');
        $query->where('ug.group_id', '=', $group_id);

        $items = $this->pagination($query)->setPath($this->getGeneratedUrl('listInGroup'));
        $items->appends(['is_iframe' => true, 'group_id' => $group_id]);
        $this->generateHTMLObjAction($items);

        $filter = $this->form_filter;
        if (Session::has('filter')) {
            $this->setFilterData(Session::get('filter'));
        }

        return $this->renderView(null, get_defined_vars(), null, 'cms::iframe');
    }

    public function addUserToGroup()
    {
        // Prepare Data
        $group_id = \Input::get('group_id', 0);
        $query    = $this->buildQuery();
        $query->whereNotIn('id', function ($subQuery) use ($group_id) {
            $subQuery->select('user_id')
                ->from('user_group_relation as ug')
                ->where('ug.group_id', $group_id);
        });
        $query->where('users.is_active', '=', true);

        // Generate Items
        $items = $this->pagination($query)->setPath($this->getGeneratedUrl('addUserToGroup'));
        $items->appends(['is_iframe' => true, 'group_id' => $group_id]);
        $this->generateHTMLObjAction($items);

        // Generate Filter Form
        $this->buildFormFilter();
        $filter = $this->form_filter;

        // Render View
        return $this->renderView(null, get_defined_vars(), null, 'System::iframe');
    }

    public function storeUserToGroup($user, $group)
    {
        if (is_object($user) && is_object($group)) {
            $user->groups()->attach($group->id);

            // Add to auditing
            $group->storeAuditing($group, $user, 'add_user_to_group');

            return redirect()
                ->route('user.addUserToGroup', ['is_iframe' => true, 'group_id' => $group->id])
                ->withSuccess('Update Success');
        } else {
            return redirect()
                ->route('addUserToGroup', ['is_iframe' => true, 'group_id' => $group->id])
                ->withErrors('Cannot add user to group');
        }
    }

    /**
     * @param $user
     * @param $group
     * @return $this
     */
    public function removeUserFromGroup($user, $group)
    {
        if (is_object($user) && is_object($group)) {
            $user->groups()->detach($group->id);
            // Add to auditing
            $group->storeAuditing($group, $user, 'remove_user_from_group');

            return redirect()
                ->route('user.listInGroup', ['is_iframe' => true, 'group_id' => $group->id])
                ->withSuccess('Update Success');
        } else {
            return redirect()
                ->route('user.listInGroup', ['is_iframe' => true, 'group_id' => $group->id])
                ->withErrors('Cannot remove user from group');
        }
    }

    /**
     * @param $obj
     * @param $key
     * @param $value
     */
    protected function applyPasswordData(&$obj, $key, $value)
    {
        $obj->password = bcrypt($value);
    }

    /**
     * @param $obj Model
     * @param $key
     * @param $value
     */
    protected function applyRoleData(&$obj, $key, $value)
    {
        $obj->saved(function (User $user) use ($value) {
            // Get old roles data
            $oldRoles = $user->roles()->get()->toArray();

            $user->roles()->sync((array)$value);

            // Get new roles data
            $newRoles = $user->roles()->get()->toArray();

            // Create auditing data if assigned roles is updated
            if ($newRoles != $oldRoles) {
                $user->storeAuditing($newRoles, $oldRoles, 'updated_assigned_roles');
            }
        });
    }

    /**
     * @param $obj
     * @param $key
     * @param $value
     */
    protected function applyGroupData(&$obj, $key, $value)
    {
        $obj->saved(function (User $user) use ($value) {
            // Get old groups data
            $oldGroups = $user->groups()->get()->toArray();

            $user->groups()->sync((array)$value);

            // Get new groups data
            $newGroups = $user->groups()->get()->toArray();

            // Create auditing data if assigned roles is updated
            if ($newGroups != $oldGroups) {
                $user->storeAuditing($newGroups, $oldGroups, 'updated_assigned_groups');
            }
        });
    }

    /**
     * @param $filterName
     * @param $filterValue
     * @param $query Builder
     */
    protected function FilterRoleId($filterName, $filterValue, &$query)
    {
        if (is_null($filterValue)) return;
        $query->leftJoin('role_user_relation as ru', 'ru.user_id', '=', 'users.id')->groupBy('users.id');
        $query->where('ru.role_id', '=', $filterValue);
    }

    /**
     * @param $filterName
     * @param $filterValue
     * @param $query Builder
     */
    protected function FilterGroupId($filterName, $filterValue, &$query)
    {
        if (is_null($filterValue)) return;
        $query->leftJoin('user_group_relation as ug', 'ug.user_id', '=', 'users.id')->groupBy('users.id');
        $query->where('ug.group_id', '=', $filterValue);
    }

    /**
     * Demo! This func is custom to edit validation of each field
     *
     * @param $filter array
     * @param $object Model
     *
     * @return mixed
     */
    protected function emailPrepareValidation($filter, $object)
    {
        if (is_null($object)) return $filter;
        $filter = str_replace('{IGNORE}', $object->id, $filter);
        return $filter;
    }

    /**
     * Modify column data display display - <colum_name>MiddlewareListColumn
     * @param $object Model
     * @param $value
     * @return mixed
     */
    protected function IsActiveMiddlewareListColumn($object, $value)
    {
        $status = '';
        if (!is_null($object)) {
            if ($value == true) {
                $status = "Active";
            } else {
                $status = "Inactive";
            }
        }
        return $status;
    }

    /**
     * Override the core query to get more data on relationObjects
     * @param null $fieldsConfig
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery($fieldsConfig = null)
    {
        $query  = parent::buildQuery($fieldsConfig);
        $locale = Config::get('app.locale');

        $query->with('roles');
        $query->with([
            'groups' => function ($groups) use ($locale) {
                $groups->I18N($locale)->select('*');
            },
        ]);

        return $query;
    }

    /**
     * Customize Role Column Display
     * @param $object User
     * @param $value
     * @return string
     */
    protected function RoleIdMiddlewareListColumn($object, $value)
    {
        $result = '';
        if (!is_null($object)) {
            $roles = $object->getRelation('roles')->toArray();
            if (count($roles) > 0) {
                foreach ($roles as $role) {
                    if ($result != '') {
                        $result .= ', ';
                    }
                    $result .= $role['name'];
                }
            }
        }
        return $result;
    }

    /**
     * Customize Role Column Display
     * @param $object User
     * @param $value
     * @return string
     */
    protected function GroupIdMiddlewareListColumn($object, $value)
    {
        $result = '';
        if (!is_null($object)) {

            $groups = $object->getRelation('groups')->toArray();

            if (count($groups) > 0) {
                foreach ($groups as $group) {
                    if ($result != '') {
                        $result .= ', ';
                    }
                    $result .= $group['name'];
                }
            }
        }
        return $result;
    }

    /**
     * @param $object
     * @param $config
     * @return string
     */
    public function removeFromGroupObjectAction($object, $config)
    {
        if (!$this->isActionTrusted('removeFromGroup')) return '';

        $group_id = \Input::get('group_id', 0);
        $group    = User::find($group_id);

        return sprintf("<li>%s%s%s</li>",
            Form::open(['method' => 'post', 'url' => $this->getGeneratedUrl('removeUserFromGroup', ['user' => $object, 'group' => $group])]),
            Form::button(array_get($config, 'label'), [
                'type' => 'submit',
                //'onclick' => 'return confirm("Are you sure?")',
            ]),
            Form::close()
        );
    }

    /**
     * @param $object
     * @param $config
     * @return string
     */
    public function addToGroupObjectAction($object, $config)
    {
        if (!$this->isActionTrusted('storeUserToGroup')) return '';

        $group_id = \Input::get('group_id', 0);
        $group    = User::find($group_id);

        return sprintf("<li>%s%s%s</li>",
            Form::open(['method' => 'post', 'url' => $this->getGeneratedUrl('storeUserToGroup', ['user' => $object, 'group' => $group])]),
            Form::button(array_get($config, 'label'), [
                'type' => 'submit',
                //'onclick' => 'return confirm("Are you sure?")',
            ]),
            Form::close()
        );
    }
}