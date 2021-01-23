<?php

namespace App\Http\Serializers;

use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\ArraySerializer;

class DefaultSerializer extends ArraySerializer
{
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
        return [
            'pageCount' => $paginator->getLastPage(),
        ];
    }

    public function cursor(CursorInterface $cursor): array
    {
        return [];
    }

    public function meta(array $meta): array
    {
        return $meta;
    }

    public function collection($resourceKey, array $data)
    {
        if ($resourceKey) {
            return [$resourceKey => $data];
        }
        return $data;
    }
}
