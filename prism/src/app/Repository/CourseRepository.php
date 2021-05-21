<?php


namespace PrismGestion\Repository;

use PrismGestion\Models\Cours;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;
use Slim\Http\Response;

/**
 * Class CourseRepository
 * @package PrismGestion\Repository
 */
class CourseRepository
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * CourseRepository constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @param $request
     * @param $response
     * @param $args
     * @return Response
     */
    public function postCourse($request, $response, $args): Response
    {
        $content = $request->getParsedBody();
        $cours = new Cours();
        $cours->uuid = Uuid::uuid4();
        $cours->intitule = $content['intitule'];
        $cours->salle = $content['salle'];
        $cours->save();
        $data = [
            'type' => "success",
            'code' => 201,
            'cours' => $cours
        ];
        return $this->container->get('ResponseWriter')->responseWriter($response, $data);
    }

}