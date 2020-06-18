<?php

namespace App\Http\Resources;

use GraphQL\Client;
use GraphQL\Query;
use GraphQL\Variable;
use GraphQL\Exception\QueryError;
use Illuminate\Http\Resources\Json\JsonResource;

class Works extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'name' => $this->name,
            'git' => $this->git,
            'category' => $this->category,
            'tags' => $this->tags,
            'repository' => empty($this->owner)? null : $this->git($this->owner, $this->name),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    /**
     * Get related git repository detail
     *
     * @param string $owner
     * @param string $name
     * @return void
     */
    public function git($owner, $name)
    {
        $client = new Client(
            env('GITHUB_URL'),
            ['Authorization' => 'Bearer ' . env('GITHUB_TOKEN')]
        );
        $gql = (new Query('repository'))
            ->setVariables(
                [
                    new Variable('owner', 'String', true),
                    new Variable('name', 'String', true)
                ]
            )
            ->setArguments(['owner' => '$owner', 'name' => '$name'])
            ->setSelectionSet(
                [
                    'createdAt',
                    'updatedAt',
                    'pushedAt',
                    'name',
                    'nameWithOwner',
                    'description',
                    'url',
                    'homepageUrl',
                    'openGraphImageUrl',
                    'forkCount',
                    (new Query('languages'))
                        ->setArguments(['last' => 10])
                        ->setSelectionSet(
                            [
                                (new Query('nodes'))
                                    ->setSelectionSet(
                                        [
                                            'name',
                                            'color'
                                        ]
                                    )
                            ]
                        ),
                    (new Query('repositoryTopics'))
                        ->setArguments(['last' => 10])
                        ->setSelectionSet(
                            [
                                (new Query('nodes'))
                                    ->setSelectionSet(
                                        [
                                            (new Query('topic'))
                                            ->setSelectionSet(
                                                [
                                                    'name'
                                                ]
                                            ),
                                            'url'
                                        ]
                                    )
                            ]
                        ),
                    (new Query('releases'))
                        ->setArguments(['last' => 1])
                        ->setSelectionSet(
                            [
                                (new Query('nodes'))
                                    ->setSelectionSet(
                                        [
                                            'name',
                                            'tagName',
                                            'publishedAt',
                                            'description',
                                            'url'
                                        ]
                                    )
                            ]
                        ),
                    (new Query('object'))
                        ->setArguments(['expression' => 'master:README.md'])
                        ->setSelectionSet(
                            [
                                (new Query('... on Blob'))
                                    ->setSelectionSet(
                                        [
                                            'text'
                                        ]
                                    )
                            ]
                        ),
                    (new Query('licenseInfo'))
                        ->setSelectionSet(
                            [
                                'name',
                                'url'
                            ]
                        ),
                ]
        );
        try {
            $results = $client->runQuery($gql, false, ['owner' => $owner, 'name' => $name]);
        } catch (QueryError $exception) {
            return false;
        }
        return collect($results->getData()->repository)->toArray();
    }
}
