<?php

namespace App\Http\Controllers;

use App\Tool;
use App\Tag;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->tag) {
            $tag = Tag::where('name', $request->tag)->get()->first();
            if (is_object($tag)) {
                return $tag->tools;
            };
            return [];
        };
        return Tool::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newTool = [
            'title' => $request->title,
            'link' => $request->link,
            'description' => $request->description
        ];
        $tool = Tool::create($newTool);
        if ($request->tags) {
            foreach ($request->tags as $tagString) {
                $tag = Tag::firstOrCreate(['name' => $tagString]);
                $tool->tags()->attach($tag);
            }
        };
        $tool->tags = $tool->tags()->pluck('name');
        return $tool;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function show(Tool $tool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function edit(Tool $tool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tool $tool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tool  $tool
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tool $tool)
    {
        return !$tool->delete() ?: response(json_encode([]), 200);
    }
}