<?php

namespace App\Http\Resources;

use GraphQL\Client;
use GraphQL\Query;
use GraphQL\Variable;
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
            'repository' => $this->git($this->owner, $this->name),
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
                    'name',
                    'description',
                    'url',
                    'homepageUrl',
                    (new Query('languages'))
                        ->setArguments(['first' => 10])
                        ->setSelectionSet(
                            [
                                (new Query('edges'))
                                    ->setSelectionSet(
                                        [
                                            (new Query('node'))
                                                ->setSelectionSet(
                                                    [
                                                        'name',
                                                        'color'
                                                    ]
                                                )
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
                        )
                ]
        );
        $results = $client->runQuery($gql, false, ['owner' => $owner, 'name' => $name]);
        return collect($results->getData()->repository)->toArray();
    }
}
