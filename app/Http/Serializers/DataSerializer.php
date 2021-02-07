<?php

namespace App\Http\Serializers;

use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\ArraySerializer;

class DataSerializer extends ArraySerializer
{
    public function item($resourceKey, array $data): array
    {
        if ($resourceKey !== null) {
            return [
                'data' => [
                    $resourceKey => $data,
                ],
            ];
        }
        return [
            'data' => $data,
        ];
    }

    public function paginator(PaginatorInterface $paginator): array
    {
        return [
            'data' => [
                'pageCount' => $paginator->getLastPage(),
            ],
        ];
    }

    public function cursor(CursorInterface $cursor): array
    {
        return [];
    }

    public function meta(array $meta): array
    {
        return [
            'data' => $meta,
        ];
    }

    public function collection($resourceKey, array $data): array
    {
        if ($resourceKey) {
            return [
                'data' => [
                    $resourceKey => $data,
                ],
            ];
        }
        return [
            'data' => $data,
        ];
    }
}
