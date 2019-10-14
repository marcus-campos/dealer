<?php

namespace MarcusCampos\Dealer;

use MarcusCampos\Dealer\Parser;
use Illuminate\Database\Eloquent\Collection;

class Negotiation
{

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var array
     */
    private $stack;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * Negotiate
     *
     * @param string $query
     * @return Collection
     */
    public function negotiate(string $query)
    {
        $this->stack = $this->parser->parse($query);

        $namespace = config('dealer.models.namespace') ?? 'App\\';
        $this->model = app($namespace.$this->stack['model']);

        $this->model = $this->relations()
            ->filters()
            ->limit()
            ->groupBy()
            ->orderBy()
            ->get();

        return $this->only($this->model, $this->stack['fields']['only']);
    }

    /**
     * Return only selected elements
     *
     * @param Collection $collection
     * @param array $fields
     * @return Collection
     */
    private function only(Collection $collection, array $fields)
    {
        $fields = array_filter($fields);
        
        if(!$fields || $fields[0] == '*') {
            return $collection;
        }

        return $collection->map(function ($model) use($fields) {
            return $model->only($fields);
        });
    }

    /**
     * Get relations
     *
     * @return Negotiation
     */
    private function relations()
    {
        $relations = [];

        if (array_key_exists('extends', $this->stack['fields'])) {
            foreach ($this->stack['fields']['extends'] as $value) {
                $this->stack['fields']['only'][] = $value['name'];

                if (!$value['args'] || $value['args'][0] == '*') {
                    $relations[] = $value['name'];
                    continue;
                }
                $relations[] = $value['name'] . ':' . implode(',', $value['args']);
            }
        }

        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * Set filters
     *
     * @return Negotiation
     */
    private function filters()
    {
        $relations = [];

        if (array_key_exists('filters', $this->stack)) {
            if (array_key_exists('extends', $this->stack['filters'])) {
                foreach ($this->stack['filters']['extends'] as $value) {
                    $methodName = $value['name'];

                    if (!$value['args']) {
                        $this->model = $this->model->$value['name']();
                        continue;
                    }

                    $this->model = $this->model->$methodName(...$value['args']);
                }
            }
        }

        return $this;
    }

    /**
     * Set the sort
     *
     * @return Negotiation
     */
    private function orderBy()
    {
        if (array_key_exists('orderBy', $this->stack)) {
            $this->model = $this->model->orderBy($this->stack['orderBy'][0],$this->stack['orderBy'][1]);
        }

        return $this;
    }

    /**
     * Set limit
     *
     * @return Negotiation
     */
    private function limit()
    {
        if (array_key_exists('limit', $this->stack)) {
            $this->model = $this->model->limit($this->stack['limit']);
        }

        return $this;
    }

    /**
     * Set groups
     *
     * @return Negotiation
     */
    private function groupBy()
    {
        if (array_key_exists('groupBy', $this->stack)) {
            $this->model = $this->model->groupBy($this->stack['groupBy']);
        }
        return $this;
    }

    /**
     * Get data
     *
     * @return Collection
     */
    private function get()
    {
        if (array_key_exists('paginate', $this->stack)) {
            return $this->model->paginate($this->stack['paginate']);
        }

        return $this->model->get();
    }
}