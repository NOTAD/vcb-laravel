<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectService
{

    /**
     * @param $params
     * @return mixed
     */
    public function searchProject($params)
    {
        $query = Project::with('category')->whereIsDeleted(false);
        if (!empty($params['id'])) {
            $query->where('id', $params['id']);
        }
        if (!empty($params['search'])) {
            $query->where(function ($query) use ($params) {
                $query->where('id', $params['search'])
                    ->orWhere('name', 'like', '%' . $params['search'] . '%')
                    ->orWhere('name_en', 'like', '%' . $params['search'] . '%');
            });
        }
        if (!empty($params['except_id'])) {
            $query->where('id', '<>', $params['except_id']);
        }
        return $query->paginate(config('app.pagination'));
    }

    /**
     * @param array $projectAttributes
     * @return mixed
     */
    public function createProject(array $projectAttributes)
    {
        return DB::transaction(function () use ($projectAttributes) {
            $project = new Project($projectAttributes);
            $project->save();
            $project->slug = Str::slug($project->name) . '-' . $project->id;
            $project->save();
            return $project->load('category');
        });
    }

    /**
     * @param Project $project
     * @param array $projectAttributes
     * @return mixed
     */
    public function updateProject(Project $project, array $projectAttributes)
    {
        return DB::transaction(function () use ($projectAttributes, $project) {
            $project->update($projectAttributes);
            return $project->load('category');
        });
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function deleteProject(Project $project)
    {
        return DB::transaction(function () use ($project) {
            $project->is_deleted = true;
            $project->save();
            return $project;
        });
    }
}
