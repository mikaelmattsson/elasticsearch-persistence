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
            'mappings' => [
                $this->getType() => [
                    '_source'    => [
                        'enabled' => true,
                    ],
                    'properties' => [
                        'name'  => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'email' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                    ],
                ],
            ],
        ];
    }
}
```

### Usage

```php
// Create and save a new document
$bob = User::create([
    'name' => 'Robert Kelso',
    'email' => 'bob@holyheart.com',
]);

$documentManager->save($bob);

// Find a document
$john = $documentManager->getRepository(User::class)->findOneBy('name', 'John Dorian');

$john->set('email', 'jd@holyheart.com');

// The document mananger will keep track of all fetched items.
$documentManager->flush();

// Set a new document to be entered into the database as a result of the flush operation.
$documentManager->persist($bob);
```

`save($document)` will immediately save specified the document(s).
`flush()` will check the state of all the tracked documents and save the new and changed documents.

    
