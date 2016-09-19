# Simple and extendable persistence library for Elasticsearch

### Set up

```php
$documentManager = new \Seek\DocumentManager(['127.0.0.1']);
```

### Custom Document

```php
class User extends \Seek\Document\Document
{
    /**
     * @param array $data
     * @param null $id
     * @return \Seek\Document\DocumentInterface|static
     */
    public static function create(array $data, $id = null)
    {
        return parent::create([
            'name'  => $data['name'],
            'email' => $data['email'],
        ], $id);
    }
}


class UserIndex implements \Seek\Index\IndexInterface
{
    /**
     * @return string
     */
    public function getIndex() : string
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return 'user';
    }

    /**
     * @param DocumentInterface|User $document
     * @return array
     */
    public function serialize(DocumentInterface $document) : array
    {
        return [
            'name' => $document->get('name'),
            'email' => $document->get('email'),
        ];
    }

    /**
     * @param array $data
     * @param string $id
     * @return DocumentInterface|User
     */
    public function deserialize(array $data, string $id) : DocumentInterface
    {
        return User::create($data, $id);
    }

    /**
     * @return array
     */
    public function getMappings() : array
    {
        return [
            'properties' => [
                'name'  => ['type' => 'string', 'index' => 'not_analyzed'],
                'email' => ['type' => 'string', 'index' => 'not_analyzed'],
            ],
        ];
    }
}
```

### Usage

#### Create and save a new document
```php
$bob = User::create([
    'name' => 'Robert Kelso',
    'email' => 'bob@holyheart.com',
]);

$documentManager->save($bob);
```

#### Bulk create and updates
```php
// Create a document
$bob = User::create([
    'name' => 'Robert Kelso',
    'email' => 'bob@holyheart.com',
]);

// Set a new document to be entered into the database as a result of the flush operation.
$documentManager->persist($bob);

// Find a document
$john = $documentManager->getRepository(User::class)->findOneBy(['name' => 'John Dorian']);

// Update a document
$john->set('email', 'jd@holyheart.com');

// The document mananger will keep track of all fetched and persisted items.
$documentManager->flush();
```

`save($document)` will immediately save specified the document(s).
`flush()` will check the state of all the tracked documents and save the new and changed documents.

#### Searching

```php

    $repository = $documentManager->getRepository(User::class);
    
    $user = $repository->find($id);
    
    $user = $repository->findOneBy(['name' => 'John Dorian']);
    
    $userCollection = $repository->findBy(['profession' => 'doctor']);
    
    $userCollection = $repository->search(new \Seek\Criteria\Criteria([
        "query" => [
            "range" => [
               "born" => [
                  "gte" => "1991-03-25T13:00:00",
                  "lt" => "1999",
               ],
            ],
        ],
        "size" => 10,
    ]));
    
```
