<?php

if(!function_exists('datatablesAssist')){
    function datatablesAssist($request,$config,$query){
        $request = (object) $request;
        $config = (object) $config;

        $cntQuery = $dtQuery = $query();

        $offset = ($request->page ?? 0) * ($request->limit ?? $config->limit ?? 10) + ($config->start ?? 0);
        $dtQuery = $query()
        ->limit($request->limit ?? $config->limit ?? 10)
        ->offset($offset);

        if(isset($request->search) && !empty($request->search) &&
        isset($config->searchFrom) && !empty($config->searchFrom)){
            $dtQuery->whereAny($config->searchFrom, 'like', "%{$request->search}%");
            $cntQuery->whereAny($config->searchFrom, 'like', "%{$request->search}%");
        }

        if(
            isset($request->orderBy) && !empty($request->orderBy) ||
            isset($config->orderBy) && !empty($config->orderBy)
        ) $dtQuery->orderBy($request->orderBy ?? $config->orderBy, $request->orderDir ?? $config->orderDir ?? 'ASC');


        return [
            'count' => $cntQuery->count(),
            'data' => $dtQuery->get()
        ];
    }
}