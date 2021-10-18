<?php

namespace App\Repositories;

use App\Models\Person;

class MongoPersonRepository implements PersonRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function getList(): iterable
    {
        return Person::all();
    }

    /**
     * @inheritdoc
     */
    public function add(array $data)
    {
        $person = new Person([
            'name' => $data['name'],
            'birthdate' => $data['birthdate'],
            'timezone' => $data['timezone'],
        ]);
        $person->save();
    }
}
