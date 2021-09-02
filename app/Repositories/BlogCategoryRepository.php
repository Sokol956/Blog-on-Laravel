<?php

namespace App\Repositories;

use App\Models\BlogCategory;
use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;


/**
 * Class BlogCategoryRepository
 *
 * @package App\Repositpries
 *
 * @property-read BlogCategory $parentCategory
 * @property-read string       $parentTitle
 */
class BlogCategoryRepository extends CoreRepository
{

    /**
     * @return string
     */

    protected function getModelClass()
    {
        /**
         * @var Model
         */
        return Model::class;
    }

    /**
     * Получить модель для редактирования в админке.
     *
     * @param int $id
     *
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Получить список категорий для вывода в выпадающем списке
     * @return Collection
     */

    public function getForComboBox()
    {
        return $this->startConditions()->all();

        $columns = implode(', ', ['id', 'CONCAT (id, ". ", title) AS title',]);

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->paginate($perPage);
            //->get();

        return $result;
    }

    /**
     * Получить категории для вывода пагинатором.
     * @param int|null $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LingthAwarePaginator
     */
    public function getAllWithPaginate($perPage = null)
    {
        $fields = ['id', 'title', 'parent_id'];

        $result = $this
            ->startConditions()
            ->select($fields)
            ->with(['parentCategory:id,title',])
            ->paginate($perPage);
        return $result;
    }
}
