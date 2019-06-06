<?php

namespace App\Http\Controllers;

use App\Tool;
use App\Tag;
use Illuminate\Http\Request;

/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     schemes={"http", "https"},
 *     host=L5_SWAGGER_CONST_HOST,
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="VUTTR",
 *         description="VUTTR API documentation",
 *         @SWG\Contact(
 *             email="tonetlds@gmail.com"
 *         ),
 *     )
 * )
 */

class ToolController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/tools",
     *      operationId="index",
     *      tags={"Tools"},
     *      summary="Get list of tools",
     *      description="Returns list of tools",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     * @SWG\Parameter(
     *          name="tag",
     *          description="Name of tag to filter the results of tools",
     *          required=false,
     *          type="string",
     *          in="query"
     *      ),
     *       @SWG\Response(response=400, description="Bad request"),
     *       security={
     *           {"api_key_security_example": {}}
     *       }
     *     )
     *
     * Returns list of Tools
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->tag) {
            return $this->getToolsByTag($request->tag);
        };
        return Tool::all();
    }

    public function getToolsByTag($tagName)
    {
        $tagObj = Tag::where('name', $tagName)->get()->first();
        if (is_object($tagObj)) {
            return $tagObj->tools;
        };
        return [];
    }

    /**
     * @SWG\Post(
     *      path="/tools",
     *      operationId="addTool",
     *      tags={"Tools"},
     *      summary="Add a new tool",
     *      description="Returns list of tools",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation"
     *       ),
     *      @SWG\Parameter(
     *          name="title",
     *          description="",
     *          required=false,
     *          type="string",
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="link",
     *          description="",
     *          required=false,
     *          type="string",
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          description="",
     *          required=false,
     *          type="string",
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="tags",
     *          description="",
     *          required=false,
     *          type="array",
     *          in="formData",
     *          items={
     *              {"type":"string"}
     *          },
     *      ),
     *       @SWG\Response(response=400, description="Bad request"),
     *       security={
     *           {"api_key_security_example": {}}
     *       }
     *     )
     *
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
            $tags = is_string($request->tags) ? explode(',', $request->tags) : $request->tags;
            foreach ($tags as $tagString) {
                $tag = Tag::firstOrCreate(['name' => $tagString]);
                $tool->tags()->attach($tag);
            }
        };
        $tool->tags = $tool->tags()->pluck('name');
        return $tool;
    }

    /**
     * @SWG\Delete(
     *      path="/tools/{id}",
     *      operationId="deleteTool",
     *      tags={"Tools"},
     *      summary="Remove a tool by id",
     *      description="Remove a tool by a passing id",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation"
     *       ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="Id of tool to exclude",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *       @SWG\Response(response=400, description="Bad request"),
     *       security={
     *           {"api_key_security_example": {}}
     *       }
     *     )
     *
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