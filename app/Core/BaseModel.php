<?php
namespace App\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{

    /**
     * Create a new model.
     *
     * @param  array $input
     * @throws Exception
     * @return mixed
     */
    public static function create(array $input)
    {
        static::beforeCreate($input);
        $return = parent::create($input);
        static::afterCreate($input, $return);

        return $return;
    }

    /**
     * Before creating a new model.
     *
     * @param  array $input
     * @return mixed
     */
    public static function beforeCreate(array $input)
    {
        // can be overwritten by extending class
    }

    /**
     * After creating a new model.
     *
     * @param  array $input
     * @param  mixed $return
     * @return mixed
     */
    public static function afterCreate(array $input, $return)
    {
        // can be overwritten by extending class
    }

    /**
     * Update an existing model.
     *
     * @param  array $input
     * @throws Exception
     * @return mixed
     */
    public function update(array $input = array())
    {

        $this->beforeUpdate($input);
        $return = parent::update($input);
        $this->afterUpdate($input, $return);

        return $return;
    }

    /**
     * Before updating an existing new model.
     *
     * @param  array $input
     * @return mixed
     */
    public function beforeUpdate(array $input)
    {
        // can be overwritten by extending class
    }

    /**
     * After updating an existing model.
     *
     * @param  array $input
     * @param  mixed $return
     * @return mixed
     */
    public function afterUpdate(array $input, $return)
    {
        // can be overwritten by extending class
    }

    /**
     * Delete an existing model.
     *
     * @throws Exception
     * @return mixed
     */
    public function delete()
    {

        $this->beforeDelete();
        $return = parent::delete();
        $this->afterDelete($return);

        return $return;
    }

    /**
     * Before deleting an existing model.
     *
     * @return mixed
     */
    public function beforeDelete()
    {
        // can be overwritten by extending class
    }

    /**
     * After deleting an existing model.
     *
     * @param  mixed $return
     * @return mixed
     */
    public function afterDelete($return)
    {
        // can be overwritten by extending class
    }

    /**
     * @param $value
     * Set Phone Attribute to Integer
     * Match Type Case with database column type
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = (int) ($value);
    }

    /**
     * @param $value
     * Set Mobile Attribute to Integer
     * Match Type Case with database column type
     */
    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = (int) ($value);
    }

    /**
     * @param $value
     * Set Price Attribute to Double
     * Match Type Case with database column type
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (double) ($value);
    }

    public function setYearAttribute($value)
    {
        $this->attributes['year'] = (int) $value;
    }

    /**
     * query scope nPerGroup
     *
     * @return void
     */
    public function scopeNPerGroup($query, $group, $n = 10)
    {
        // queried table
        $table = ($this->getTable());

        // initialize MySQL variables inline
        $query->from(DB::raw("(SELECT @rank:=0, @group:=0) as vars, {$table}"));

        // if no columns already selected, let's select *
        if (!$query->getQuery()->columns) {
            $query->select("{$table}.*");
        }

        // make sure column aliases are unique
        $groupAlias = 'group_' . md5(time());
        $rankAlias  = 'rank_' . md5(time());

        // apply mysql variables
        $query->addSelect(DB::raw(
            "@rank := IF(@group = {$group}, @rank+1, 1) as {$rankAlias}, @group := {$group} as {$groupAlias}"
        ));

        // make sure first order clause is the group order
        $query->getQuery()->orders = (array) $query->getQuery()->orders;
        array_unshift($query->getQuery()->orders, ['column' => $group, 'direction' => 'asc']);

        // prepare subquery
        $subQuery = $query->toSql();

        // prepare new main base Query\Builder
        $newBase = $this->newQuery()
            ->from(DB::raw("({$subQuery}) as {$table}"))
            ->mergeBindings($query->getQuery())
            ->where($rankAlias, '<=', $n)
            ->getQuery();

        // replace underlying builder to get rid of previous clauses
        $query->setQuery($newBase);
    }

} 