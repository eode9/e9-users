<?php

namespace E9\User\Action\API;

use Doctrine\ODM\MongoDB\DocumentManager;
use E9\Core\Action\AbstractAPIAction;
use E9\User\Document\User;
use Firebase\JWT\JWT;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuupola\Base62;

/**
 * Class AuthenticateUser
 * @package E9\User\Action\API
 */
final class DeleteUser extends AbstractAPIAction
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @param $c Container
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __construct(Container $c)
    {
        $this->dm = $c->get('dm');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $data = array();

        /** @var User $user */
        $user = $this->dm->getRepository(User::class)->find($args['id']);

        if ($user === null) {
            return $this->prepareError($response, [
                'message' => 'User not found',
                'data' => [],
                'code' => 0,
            ],
                404);
        }

        $this->dm->remove($user);
        $this->dm->flush();

        return $this->prepareSuccess($response, $data, 204);
    }
}
