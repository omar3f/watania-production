<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LinksController extends Controller
{
    /**Takes an array of objects and creates an associative array out of it
     * You should specify the key value pairs as the second argument
     *
     * (optional) Add an array as the third argument to get it prepended to the returned array
     * (optional) Add an array as the fourth argument to get it appended to the returned array
     * @param $arr_obj
     * @param $key_val
     * @param array $prepend
     * @param array $append
     * @return array
     */

    protected function dropdown_generator($arr_obj, array $key_val, array $prepend = [], array $append = []) {
        $new_arr = $prepend;
        foreach($arr_obj as $item) {
            foreach ($key_val as $key => $val) {
                $new_arr[$item[$key]] = $item[$val];

            }
        }
        foreach ($append as $append_key => $append_val) {
            $new_arr[$append_key] = $append_val;

        }
        return $new_arr;

    }
    public function index()
    {
        //Getting all parent links
        $parent_links = (new \App\Link)->where('parent_id','=' ,0)->orderBy('sort')->get();

        //Getting all nested links
        $child_links =  (new \App\Link)->where('parent_id', '>', 0)->orderBy('sort')->get();

        //Returning the view and passing all links to it
        return view('panel.links.all', compact('parent_links', 'child_links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Getting all parent links
        $links = (new \App\Link)->where('parent_id', '=', '0')->get();

        //Generating the dropdown list for parent pages as follows ['0' => 'No Parent', '1' => 'First Parent']
        $dropdown_links = $this->dropdown_generator($links, ['id' => 'title'], ['0' => 'No Parent']);

        //returning the create view with the dropdown list variable
        return view('panel.links.create', compact('dropdown_links'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Requests\CreateEditLinksRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Requests\CreateEditLinksRequest $request)
    {
        //Storing the validated link
        (new \App\Link)->create($request->all());

        //Redirecting back to the index so the admin can view the canges
        return redirect()->action('Panel\\LinksController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Getting the link to be edited so the form-model binder can pre-populate the form fields with old values
        $link = \App\Link::find($id);

        //Getting all parent links
        $links = (new \App\Link)->where('parent_id', '=', '0')->get();

        //Generating the dropdown list for parent pages as follows ['0' => 'No Parent', '1' => 'First Parent']
        $dropdown_links = $this->dropdown_generator($links, ['id' => 'title'], ['0' => 'No Parent']);

        return view('panel.links.edit', compact('link', 'dropdown_links'));
    }

    /**
     * @param Requests\CreateEditLinksRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Requests\CreateEditLinksRequest $request, $id)
    {

        $link = (new \App\Link)->find($id);
        $link->update($request->all());
        return redirect()->action('Panel\\LinksController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Destroying the parent
        (new \App\Link)->destroy($id);

        //Destroying any child
        (new \App\Link)->where('parent_id', $id)->delete();

        //Redirecting to index so the admin can view the changes
        return redirect()->action('Panel\\LinksController@index');

    }
}
