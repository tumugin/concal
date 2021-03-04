<?php

namespace App\Http\Serializers;

use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\ArraySerializer;

class DefaultSerializer extends ArraySerializer
{
    private array $paginator_items = [];

    public function item($resourceKey, array $data): array
    {
        if ($resourceKey !== null) {
            return [
                $resourceKey => $data,
            ];
        }
        return $data;
    }

    public function paginator(PaginatorInterface $paginator): array
    {
        $this->paginator_items = [
            'pageCount' => $paginator->getLastPage(),
            'nextPage' => $paginator->getLastPage() > $paginator->getCurrentPage() + 1 ? $paginator->getCurrentPage() + 1 : null,
        ];
        // なぜか最初のキーしか使ってくれないというバグがあるため、これでお茶を濁す
        return [];
    }

    public function cursor(CursorInterface $cursor): array
    {
        return [];
    }

    public function meta(array $meta): array
    {
        return array_merge($meta, $this->paginator_items);
    }

    public function collection($resourceKey, array $data)
    {
        if ($resourceKey) {
            return [$resourceKey => $data];
        }
        return $data;
    }
}
