<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployeeEducationCollection extends ResourceCollection
{
    public static $wrap = null;

    public function toArray($request)
    {
        $paginate = ($request->paginate) ? $request->paginate : null;
        if(is_numeric($paginate)){
            $this->collection = $this->paginate($this->collection);
            return [
                'status' => true,
                'code' => 200,
                'message' => 'Success Show List',
                'pagination' => [
                    'total' => $this->collection->total(),
                    'count' => $this->collection->count(),
                    'per_page' => $this->collection->perPage(),
                    'current_page' => $this->collection->currentPage(),
                    'total_pages' => $this->collection->lastPage()
                ],
                'data' => EmployeeEducationResource::collection($this->collection)
            ];

        }else{
            return [
                'status' => true,
                'code' => 200,
                'message' => 'Success Show List',
                'data' => EmployeeEducationResource::collection($this->collection)
            ];
        }
    }

    public function withResponse($request, $response)
    {
        $jsonResponse = json_decode($response->getContent(), true);
        unset($jsonResponse['links'],$jsonResponse['meta']);
        $response->setContent(json_encode($jsonResponse));
    }
}
