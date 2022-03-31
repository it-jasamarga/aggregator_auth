<?php


namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author Tashya Dwi Askara Siahaan
 * @enhance Riandy Candra Winahyu <riandycandra.dev@gmail.com>
 **/
abstract class Repository
{
    protected $getClassName;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->getClassName = $this->getClassName();
    }

    abstract function getClassName();

    /**
     * @param string $getClassName
     *
     * @return mixed
     */
    private function createModel(string $getClassName)
    {
        return new $getClassName;
    }

    /**
     * @param string|null $getClassName
     * @return mixed
     */
    protected function createQueryBuilder(string $getClassName = null)
    {
        $model = $this->createModel($getClassName ?: $this->getClassName);

        return $model->newQuery();
    }

    /**
     * @param string $id
     * @param array $with
     * @return mixed
     */
    public function findOrFail(string $id, $with = [])
    {
        $model = $this->createModel($this->getClassName);

        if (null !== $result = $model::with($with)->find($id))
        {
            return $result;
        }

        throw (new ModelNotFoundException())->setModel(get_class($model), [$id]);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param array $with
     * @return mixed
     */
    public function findOneBy(array $criteria, array $orderBy = [], array $with = [])
    {
        $qb = $this->createQueryBuilder();

        foreach ($criteria as $key => $value) {
            $qb->where($key, $value);
        }

        foreach($orderBy as $key => $value)
        {
            $qb->orderBy($key, $value);
        }

        $qb->with($with);

        return $qb->first();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param array $with
     * @return mixed
     */
    public function findBy(array $criteria, array $orderBy = [], array $with = [])
    {
        $qb = $this->createQueryBuilder();

        foreach ($criteria as $key => $value) {
            $qb->where($key, $value);
        }

        foreach($orderBy as $key => $value)
        {
            $qb->orderBy($key, $value);
        }

        $qb->with($with);

        return $qb->get();
    }

    /**
     * @param array $criteria
     * @param array $data
     *
     * @return mixed
     */
    public function update(array $criteria = [], array $data = [])
    {
        $qb = $this->createQueryBuilder();

        foreach ($criteria as $key => $value) {
            $qb->where($key, $value);
        }

        return $qb->update($data);
    }

    /**
     * @param array $orderBy
     * @param array $with
     * @return mixed
     */
    public function getAll(array $orderBy = [], array $with = [])
    {
        $qb = $this->createQueryBuilder();

        foreach($orderBy as $key => $value)
        {
            $qb->orderBy($key, $value);
        }

        $qb->with($with);

        return $qb->get();
    }
}
