<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 10:46
 */

namespace PmAbstract\Controllers\Backend;

use Shopware_Controllers_Backend_ExtJs;

/**
 * This can be used to fetch an enumeration for ExtJs (filters).
 *
 * Class AbstractEnumBackendController
 * @package PmAbstract\Controllers\Backend
 */
abstract class AbstractEnumBackendController extends Shopware_Controllers_Backend_ExtJs
{
    /**
     * Controller action which can be called over an ajax request.
     * This function is normally used for backend listings.
     * The listing will be selected over the getList function.
     *
     * The function expects the following request parameter:
     *  query - Search string which inserted in the search field.
     *  start - Pagination start value
     *  limit - Pagination limit value
     */
    public function listAction(): void
    {
        $view = $this->View();
        if (!isset($view)) {
            return;
        }

        $view->assign(
            $this->getList(
                $this->Request()->getParam('start', 0),
                $this->Request()->getParam('limit', 20),
                $this->Request()->getParam('query', '')
            )
        );
    }

    /**
     * This should return an string array if all the allowed values.
     *
     * @return string[]
     */
    abstract protected function getEnumValues(): array;

    /**
     * This should return the name that will be used as the property name when fetching the list.
     *
     * @return string
     */
    abstract protected function getFieldName(): string;

    /**
     * The getList function returns an array.
     * The pagination of the listing is handled inside this function.
     *
     * @param int|null    $offset
     * @param int|null    $limit
     * @param string|null $filter
     *
     * @return array
     */
    protected function getList($offset, $limit, $filter = ''): array
    {
        $data = $this->getEnumValues();
        //filter
        if(isset($filter) && ($filter !== '')) {
            $data = array_filter($data, static function($elem) use($filter) {
                return strpos($elem, $filter) !== false;
            });
        }
        //map
        $data = array_map(function ($elem) {
            return [
                $this->getFieldName() => $elem
            ];
        }, $data);
        //slice
        $data = array_slice($data, (int)$offset, (int)$limit);

        $count = count($data);

        return ['success' => true, 'data' => $data, 'total' => $count];
    }
}
